<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProformaVSCrf_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();

        $this->load->library('session');
    }


    public function getSuppliers()
    {
        $query =  $this->db->select('supplier_id, supplier_name, acroname')
                           ->where('status', 1)
                           ->order_by('supplier_name', 'ASC')
                           ->get('suppliers');
        return $query->result_array();
    }

    public function getCustomers()
    {
        $query =  $this->db->select('customer_code, customer_name')
                           ->where('status', 1)
                           ->order_by('customer_name', 'ASC')
                           ->get('customers');
        return $query->result_array();
    }

    public function getSupplierData($select, $where, $target)
    {
        $query = $this->db->select($select)
                          ->get_where('suppliers', array( $where => $target));
        return $query->row_array();
    }

    public function getVATData()
    {
        $query = $this->db->get('vat', array('status' => 1));
        return $query->row_array();
    }

    public function getCustomerData($cusId, $field)
    {
        $query = $this->db->select($field)
                          ->get_where('customers', array('customer_code' => $cusId));
        return $query->row();
    }

    public function getCrf($crfId)
    {
        $query = $this->db->get_where('crf', array('crf_id' => $crfId));
        return $query->row_array();
    }

    public function getUnAppliedProforma($crfId, $supId, $str)
    {
        $query = $this->db->query('SELECT * from proforma_header prof INNER JOIN po_header po on po.po_header_id = prof.po_header_id 
                                 INNER JOIN customers c on  c.customer_code = prof.customer_code  
                                 WHERE NOT EXISTS (SELECT * from crf_line l where l.proforma_header_id = prof.proforma_header_id  
                                 AND l.crf_id  = "' . $crfId . '" and l.supplier_id = "' . $supId . '" ) 
                                 AND (prof.crf_id IS NULL or prof.crf_id = 0) 
                                 AND prof.supplier_id  = '.$supId.'
                                 AND ( prof.proforma_code LIKE "%' . $str . '%"  OR 
                                       prof.so_no LIKE "%' . $str . '%" OR
                                       prof.order_no LIKE "%' . $str . '%" OR
                                       po.po_no LIKE "%' . $str . '%"  OR 
                                       po.po_reference LIKE "%' . $str . '%"  )
                                 ORDER BY prof.po_header_id DESC
                                 LIMIT 10
                                ');
        return $query->result_array();
    }

    public function getAppliedProforma($crfId,$supId)
    {
        $query = $this->db->query('SELECT h.proforma_header_id, h.supplier_id, h.proforma_code, h.delivery_date, p.po_no, sum(l.amount)  as amount from proforma_header h 
                                   INNER JOIN proforma_line l on l.proforma_header_id = h.proforma_header_id 
                                   INNER JOIN po_header p on p.po_header_id = h.po_header_id
                                   WHERE EXISTS 
                                   (SELECT * from crf_line c WHERE c.crf_id = '.$crfId.' AND c.proforma_header_id = h.proforma_header_id 
                                   AND c.pi_head_id = 0 AND c.supplier_id = '.$supId.') 
                                   GROUP BY h.proforma_header_id');    
        return $query->result_array();
    }
  
    public function getAppliedProformaInCrfLine($crfId)
    {
        $query = $this->db->get_where('crf_line', array('crf_id' => $crfId, 'pi_head_id' => 0));
        return $query->result_array();
    }

    public function getCrfs($supId, $cusId)
    {
        $query = $this->db->select('c.*,s.*,h.sop_no')
                          ->from('crf c')
                          ->join('suppliers s', 's.supplier_id = c.supplier_id', 'inner')
                          ->join('sop_head h','h.sop_id = c.sop_id','left')
                          ->where('c.supplier_id', $supId)
                          ->where('c.customer_code', $cusId)
                          ->where('c.status', 'PENDING')
                          ->order_by('crf_id', 'DESC')
                          ->get();
        return $query->result_array();
    }

    public function proformaHead($proformaId)
    {
        $query  = $this->db->select('ph.proforma_header_id, ph.so_no, ph.proforma_code, ph.delivery_date, ph.proforma_code, c.l_acroname, ph.customer_code,o.po_no,count(dv.discount_id) as numberOfDiscount')
                           ->from('proforma_header ph')
                           ->join('po_header o', 'o.po_header_id = ph.po_header_id','inner')
                           ->join('customers c', 'c.customer_code = ph.customer_code','inner')
                           ->join('discountvat dv', 'dv.proforma_header_id = ph.proforma_header_id','left')
                           ->where('ph.proforma_header_id', $proformaId)
                           ->get();
        return $query->row_array();
    }

    public function proformaLine($proformaId)
    {
        $query = $this->db->select('l.*,i.id,i.itemcode_loc,i.itemcode_sup,i.item_division, i.item_department_code, i.item_group_code')
                          ->from('proforma_line l')
                          ->join('items i','i.itemcode_sup = l.item_code','left')
                          ->where('l.proforma_header_id', $proformaId)
                          ->get();
        return $query->result_array();
    }

    public function proformaDiscVat($proformaId)
    {
        $query = $this->db->get_where('discountvat', array('proforma_header_id' => $proformaId));
        return $query->result_array();
    }

    public function getDeals($dealId)
    {
        $query = $this->db->get_where('vendors_deal_line', array('vendor_deal_head_id' => $dealId));
        return $query->result_array();
    }

    public function getSumDiscVat($profId,$supId)
    {
        $query = $this->db->query('SELECT proforma_header_id, sum(total_discount) as discVat FROM discountvat WHERE proforma_header_id = '.$profId. ' AND supplier_id = '.$supId);
        return $query->row_array();
    }
    public function uploadCrf($crfNo, $supId, $cusId, $insert)
    {
        $query = $this->db->get_where('crf', array('crf_no' => $crfNo, 'supplier_id' => $supId, 'customer_code' => $cusId));

        if ($query->num_rows() == 0) {
            $this->db->insert('crf', $insert);
            return $this->db->insert_id();    
        } else {
           
            die("exists");           
            
        }
    }

    public function getProfData($profId)
    {
        $query = $this->db->get_where('proforma_header', array('proforma_header_id' => $profId));
        return $query->row_array();
    }

    public function getDocNo($useNext = false)
    {
        $sequence = getSequenceNo(
            [
                'code'          => "RN",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Reference Number"
            ],
            [
                'table'     =>  'subsidiary_ledger',
                'column'    => 'reference_no'
            ],

            $useNext
        );

        return $sequence;
    }

    public function getDocNo2($useNext = false)
    {
        $sequence = getSequenceNo(
            [
                'code'          => "TR",
                'number'        => '1',
                'lpad'          => '7',
                'pad_string'    => '0',
                'description'   => "Transaction"
            ],
            [
                'table'     =>  'profvcrf_transaction',
                'column'    => 'tr_no'
            ],

            $useNext
        );

        return $sequence;
    }

    public function untagProforma($profId)
    {
         $query = $this->db->set('crf_id', 0)
                          ->where('proforma_header_id', $profId)
                          ->update('proforma_header');
        return $query;
    }

    public function getSopDeduction($crfId) //old
    {
        $query = $this->db->query('SELECT f.crf_id, sop.sop_no, sop.datetime_created, ded.* FROM crf f 
                                   INNER JOIN sop_head sop ON sop.sop_id = f.sop_id
                                   INNER JOIN sop_deduction ded ON ded.sop_id = f.sop_id
                                   WHERE f.crf_id = '.$crfId);
        return $query->result_array();
    }    

    public function getData($query1)
    {
        $query  = $this->db->query($query1);
        return $query->result_array();
    }

    public function getVendorsDealBySupplier($supId)
    {   
        $query = $this->db->get_where('vendors_deal_header', array('supplier_id' => $supId));
        return $query->result_array();
    }
    
}
