<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProformaVSPI_model extends CI_Model {


    function __construct()
    {
        parent::__construct();

        $this->load->library('session');
    }

    public function getSuppliers()
    {
        $query =  $this->db->select('supplier_id, supplier_name, acroname')
                           ->where('status', 1)
                           ->order_by('supplier_name','ASC')
                           ->get('suppliers');          
        return $query->result_array();
    }

    public function getCustomers()
    {
        $query =  $this->db->select('customer_code, customer_name')
                           ->where('status', 1) 
                           ->order_by('customer_name','ASC')
                           ->get('customers');
        return $query->result_array();
    }

    public function getSupplierData($value, $where)
    {
        $query = $this->db->get_where('suppliers', array($where => $value));
        return $query->row_array();
    }   

    public function getVATData()
    {
        $query = $this->db->get_where('vat', array('status' => 1));
        return $query->row_array();
    }

    public function getCustomerData($value, $where)
    {
        $query = $this->db->get_where('customers', array($where => $value));
        return $query->row_array();
    }  

    public function getPoData($value, $select, $where)
    {
        $query = $this->db->select($select)
                          ->get_where('po_header', array($where => $value));
        return $query->row();
    } 

    public function uploadHeader($piNo,$vendorInvoiceNo,$postingDate,$amtIncVat,$supId,$cusId)
    {
        $query = $this->db->get_where('purchase_invoice_header', array('pi_no' => $piNo, 'supplier_id' => $supId));
        if($query->num_rows() == 0)
        {
            $header = array('pi_no'             => $piNo,
                            'vendor_invoice_no' => $vendorInvoiceNo,     
                            'posting_date'      => $postingDate,
                            'amt_including_vat' => $amtIncVat,
                            'supplier_id'       => $supId,
                            'customer_code'     => $cusId,
                            'po_header_id'      => 0,
                            'user_id'           => $this->session->userdata('user_id'),
                            'date_uploaded'     => date("Y-m-d"),
                            'status'            => "PENDING"                            
                           );

            return $this->db->insert('purchase_invoice_header', $header);
            // return $this->db->insert_id();

        }else
        {
            return false;
        }
    }

    public function getPiHeadData($piNo,$supId)
    {
        $query = $this->db->get_where('purchase_invoice_header', array('pi_no' => $piNo,'supplier_id' => $supId));
        return $query->row_array();
    }
    public function uploadLine($itemcode_sup,$itemCode,$desc,$uom,$qty,$directUnitCost,$unitCostLcy,$amt,$amtIncVat,$unitCost,$lineAmt,$qtyPerUom,$uomCode,$piId,$lineDiscPer,$lineDiscAmt)
    {
        $query = $this->db->select('pi_line_id')
                          ->get_where('purchase_invoice_line', array('pi_head_id' =>$piId, 'item_code' => $itemCode));                     
        
        if($query->num_rows() == 0 )
        {
            $line = array('item_code'           => $itemCode,
                          'itemcode_sup'        => (is_null($itemcode_sup)) ? '' : $itemcode_sup,
                          'description'         => $desc,
                          'uom'                 => $uom,
                          'qty'                 => $qty,
                          'direct_unit_cost'    => $directUnitCost,
                          'unit_cost_lcy'       => $unitCostLcy,
                          'amt'                 => $amt,
                          'amt_including_vat'   => $amtIncVat,
                          'unit_cost'           => $unitCost,
                          'line_amt'            => $lineAmt,
                          'qty_per_uom'         => $qtyPerUom,
                          'uom_code'            => $uomCode,
                          'pi_head_id'          => $piId,
                          'line_disc_percent'   => $lineDiscPer,
                          'line_disc_amt'       => $lineDiscAmt
                         );
            
            return $this->db->insert('purchase_invoice_line', $line);

        }else
        {
            die("duplicate2");
        }
    }

    public function loadPiCm($supId, $cusId)
    {
        $query = $this->db->select('head.pi_head_id as piId, head.pi_no as pi_no, head.posting_date as date , head.status as status, c.customer_name as customer_name, p.po_no as po_no, cm.cm_head_id, cm.cm_no, cm.posting_date, cm.amt_including_vat')
                          ->from('purchase_invoice_header head')
                          ->join('customers c', 'c.customer_code = head.customer_code', 'inner')
                          ->join('po_header p', 'p.po_header_id = head.po_header_id','inner')
                          ->join('cm_head cm', 'cm.pi_head_id = head.pi_head_id', 'left')
                          ->where('head.supplier_id', $supId)
                          ->where('head.customer_code', $cusId)
                          ->where('head.status', 'PENDING')
                          ->order_by('head.pi_head_id', 'DESC')
                          ->get();      
        return $query->result_array();
    }

    public function getPiDetails($piId)
    {
        $query  = $this->db->get_where('purchase_invoice_line', array('pi_head_id' => $piId));
        return $query->result_array();
    }

    public function updateRemarks($lineId,$piHeadId,$remarks)
    {
        $query = $this->db->set('remarks', strtoupper($remarks))
                          ->where('pi_line_id', $lineId)
                          ->where('pi_head_id', $piHeadId)
                          ->update('purchase_invoice_line');
        return $query;
    }

    public function updatePrice($piLineId,$piHeadId,$newPrice,$newAmt,$itemCode,$remarks)
    {    
        $query  = $this->db->set('direct_unit_cost', $newPrice)
                           ->set('amt_including_vat', $newAmt)
                           ->set('remarks', strtoupper($remarks))
                           ->where('pi_line_id', $piLineId)
                           ->where('pi_head_id', $piHeadId)
                           ->where('item_code', $itemCode)                           
                           ->update('purchase_invoice_line');        
        return $query;
    }

    public function getItemPriceLog($piLineId,$piHeadId,$itemCode)
    {
        $query = $this->db->select('pl.*, s.username')
                          ->from('purchase_invoice_pricelog pl')
                          ->join('users s', 's.user_id =  pl.user_id', 'left')
                          ->where('pl.pi_line_id',$piLineId)
                          ->where('pl.pi_head_id',$piHeadId)
                          ->where('pl.item_code',$itemCode)
                          ->get();
        return $query->result_array();
    }

    public function loadCrf($supId, $cusId)
    {
        $query = $this->db->select('f.crf_id,f.crf_no,f.crf_date,f.crf_amt,f.supplier_id,h.sop_id,h.sop_no')
                          ->from('crf f')
                          ->join('sop_head h', 'h.sop_id = f.sop_id','left')
                          ->where('f.supplier_id',$supId)
                          ->where('f.customer_code',$cusId)
                          ->get();
        return $query->result_array();
    }

    public function loadProformaInCrf($crfId)
    {       
        $query = $this->db->query('SELECT p.proforma_header_id as profId,p.proforma_code as profCode, p.delivery_date as delivery,o.po_no as po, 
                                    (CASE WHEN c.customer_code = 1 THEN "CDC"   
                                          WHEN c.customer_code = 2 THEN "PM"
                                          WHEN c.customer_code = 3 THEN "LDI"
                                          WHEN c.customer_code = 4 THEN "COLM"
                                    END) as loc,SUM(l.qty * l.price) as item_total
                                  FROM proforma_header p 
                                  INNER JOIN po_header o on o.po_header_id = p.po_header_id
                                  INNER JOIN customers c on c.customer_code = p.customer_code  
                                  INNER JOIN proforma_line l on l.proforma_header_id = p.proforma_header_id
                                  WHERE EXISTS
                                  (SELECT * FROM crf_line f WHERE f.crf_id = '.$crfId.' AND f.proforma_header_id = p.proforma_header_id 
                                  AND f.supplier_id = p.supplier_id 
                                  AND f.pi_head_id = 0) GROUP BY p.proforma_header_id   ');
        return $query->result_array();
    }

    public function getSumDiscVat($profId,$supId)
    {
        $query = $this->db->query('SELECT proforma_header_id, sum(total_discount) as addless FROM discountvat 
                                   WHERE proforma_header_id = '.$profId.' AND supplier_id = '.$supId);
        return $query->row_array();
    }

    public function loadPiInCrf($crfId)
    {        
        $query = $this->db->query('SELECT i.pi_head_id as piId,i.pi_no as piNo,i.posting_date as postDate,o.po_no as po, 
                                    (CASE WHEN c.customer_code = 1 THEN "CDC"
                                          WHEN c.customer_code = 2 THEN "PM"
                                          WHEN c.customer_code = 3 THEN "LDI"
                                          WHEN c.customer_code = 4 THEN "COLM"
                                    END) as loc,
                                    SUM(l.amt_including_vat) as total_amount
                                    FROM purchase_invoice_header i INNER JOIN po_header o on o.po_header_id = i.po_header_id
                                    INNER JOIN customers c on c.customer_code = i.customer_code
                                    INNER JOIN purchase_invoice_line l on l.pi_head_id = i.pi_head_id
                                    WHERE EXISTS 
                                    (SELECT * FROM crf_line f WHERE f.crf_id = '.$crfId.' AND f.pi_head_id = i.pi_head_id 
                                    AND f.proforma_header_id = 0) GROUP BY i.pi_head_id ');
        return $query->result_array();
    }

    public function getPiDet($supId,$piId)
    {
        $query = $this->db->query('SELECT p.pi_head_id, p.pi_no, p.posting_date, p.po_header_id, p.vendor_invoice_no, p.customer_code, sum(l.amt_including_vat) as amount from purchase_invoice_header  p INNER JOIN purchase_invoice_line l on l.pi_head_id = p.pi_head_id where p.pi_head_id ='.$piId );
        return $query->row_array();
    }

    public function applyPiToCrf($crfId, $piId,$insertCrfLine)
    {
        $query = $this->db->get_where('crf_line', array('crf_id' =>$crfId, 'pi_head_id' =>$piId));
        if($query->num_rows() == 0)
        {
            return $this->db->insert('crf_line',$insertCrfLine);
        }else
        {
            return false;
        }
    }

    public function getPiData($where,$value)
    {
        $query = $this->db->get_where('purchase_invoice_header', array($where =>$value));
        return $query->row_array();
    }

    public function getCrfinLedger($crfId)
    {
        $query = $this->db->select('reference_no')->get_where('subsidiary_ledger', array('crf_id' =>$crfId));
        return $query->row_array();
    }

    public function getSupCusinCrf($crfId)
    {
        $query = $this->db->select('supplier_id, customer_code')->get_where('crf', array('crf_id' => $crfId));
        return $query->row_array();
    }

    public function getProformaInCrf($crfId)
    {
        $query = $this->db->select('l.proforma_header_id, count(pl.proforma_line_id) as numberOfItems, l.supplier_id, l.customer_code,l.po_header_id, p.po_no')
                          ->from('crf_line l')
                          ->join('po_header p', 'p.po_header_id = l.po_header_id','inner')
                          ->join('proforma_line pl','pl.proforma_header_id = l.proforma_header_id', 'inner')
                          ->where('l.crf_id' ,$crfId)
                          ->where('l.pi_head_id', 0)
                          ->group_by('l.proforma_header_id')
                          ->get();
        return $query->result_array();
    }

    public function getPiInCrf($crfId)
    {
        $query = $this->db->select('l.pi_head_id, count(pi.pi_line_id) as numberOfItems, l.supplier_id, l.customer_code,l.po_header_id, p.po_no')
                          ->from('crf_line l')
                          ->join('po_header p', 'p.po_header_id = l.po_header_id','inner')
                          ->join('purchase_invoice_line pi', 'pi.pi_head_id = l.pi_head_id', 'inner')
                          ->where('l.crf_id' ,$crfId)
                          ->where('l.proforma_header_id', 0)
                          ->group_by('l.pi_head_id')
                          ->get();
        return $query->result_array();
    }

    public function getDeals($dealId)
    {
        $query = $this->db->get_where('vendors_deal_line', array('vendor_deal_head_id' => $dealId));
        return $query->result_array();
    }

    public function getProformaHead($profId)
    {
        $query  = $this->db->select('p.proforma_header_id as profId,p.so_no,p.delivery_date as delivery,p.proforma_code as profCode,p.supplier_id,p.customer_code,c.l_acroname,p.po_header_id as poId, o.po_no,count(dv.discount_id) as numberOfDiscount')
                           ->from('proforma_header p')
                           ->join('po_header o', 'o.po_header_id = p.po_header_id','inner')
                           ->join('customers c', 'c.customer_code = p.customer_code','inner')
                           ->join('discountvat dv', 'dv.proforma_header_id = p.proforma_header_id','inner')
                           ->where('p.proforma_header_id',$profId)
                           ->get();
        return $query->row_array();                
    }

    public function getProfLine($profId)
    {
        $query = $this->db->query('SELECT h.proforma_header_id AS profId, h.proforma_code AS profCode, po.po_header_id AS poId, i.id,
                                   l.item_code AS item, l.description AS idesc, sum(l.qty) AS qty, l.uom,l.price, sum(l.amount) AS amount, i.itemcode_loc, i.item_division, i.item_department_code, i.item_group_code
                                   FROM proforma_line l
                                   INNER JOIN proforma_header h ON h.proforma_header_id = l.proforma_header_id
                                   INNER JOIN po_header po ON po.po_header_id = h.po_header_id                                    
                                   LEFT JOIN items i ON i.itemcode_sup = l.item_code WHERE h.proforma_header_id = '.$profId.'
                                   GROUP BY l.item_code, po.po_header_id
                                   ORDER BY h.proforma_header_id');
        return $query->result_array();
    }

    public function getProfLineItemCounter($profId)
    {
        $query = $this->db->query('SELECT pl.proforma_header_id,pl.item_code, ph.po_header_id, count(1) AS itemCount 
                                   FROM proforma_line pl 
                                   INNER JOIN proforma_header ph ON ph.proforma_header_id = pl.proforma_header_id
                                   INNER JOIN po_header po ON po.po_header_id = ph.po_header_id
                                   WHERE ph.proforma_header_id = '.$profId.'
                                   GROUP BY pl.item_code, po.po_header_id
                                   ORDER BY pl.proforma_header_id');
        return $query->result_array();
    }

    public function getVatDisc($profId)
    {
        $query = $this->db->get_where('discountvat', array('proforma_header_id' => $profId));
        return $query->result_array();
    }

    public function getPiHead($piId)
    {
        $query  = $this->db->get_where('purchase_invoice_header', array('pi_head_id' => $piId));
        return $query->row_array();
    }

    public function getPiLine($piId)
    {
        $query  = $this->db->query('SELECT h.pi_head_id AS piId, h.posting_date AS piDate, h.pi_no AS piNo, po.po_header_id AS poId,
                                   i.id,l.item_code AS item, i.itemcode_sup AS itemSup, l.description AS idesc, l.uom, l.qty, 
                                   l.direct_unit_cost AS direct, l.amt_including_vat AS amount,i.item_division, i.item_department_code, i.item_group_code
                                   FROM purchase_invoice_header h 
                                   INNER JOIN po_header po ON po.po_header_id = h.po_header_id 
                                   INNER JOIN purchase_invoice_line l ON l.pi_head_id = h.pi_head_id 
                                   LEFT JOIN items i ON i.itemcode_loc = l.item_code WHERE h.pi_head_id = '. $piId);
        return $query->result_array();
    }
    
    public function getDocNo($useNext = false)
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
                'table'     =>  'profvpi_transaction',
                'column'    => 'tr_no'
            ],

            $useNext
        );

        return $sequence;
    }

    public function getPiItemOnly($piId)
    {
        $query = $this->db->select('itemcode_sup')->get_where('purchase_invoice_line', array('pi_head_id' => $piId));
        return $query->result_array();
    }

    public function managersKey($username, $password)
    {
        $query = $this->db->select('userType')
                          ->get_where('users', array('username' => $username, 'password' => $password));
        return $query->row();
    }
    
    public function checkItem($select, $itemCode)
    {
        $query = $this->db->select($select)->get_where('items', array('itemcode_loc' => $itemCode))->row_array();
        return $query;
    }

    public function getSopDeduction($crfId)
    {
        $query = $this->db->query('SELECT f.crf_id, sop.sop_no, sop.datetime_created, ded.* FROM crf f 
                                   INNER JOIN sop_head sop ON sop.sop_id = f.sop_id
                                   INNER JOIN sop_deduction ded ON ded.sop_id = f.sop_id
                                   WHERE f.crf_id = '.$crfId);
        return $query->result_array();
    }

    public function uploadCMHeader($no, $piId, $date, $amount, $supId)
    {
        $query = $this->db->get_where('cm_head', array('cm_no' => $no,'pi_head_id' => $piId,'supplier_id' => $supId));
        if($query->num_rows() == 0){

            $cm =     array('cm_no'             => $no , 
                            'pi_head_id'        => $piId, 
                            'posting_date'      => $date, 
                            'amt_including_vat' => $amount,
                            'supplier_id'       => $supId,
                            'date_uploaded'     => date("Y-m-d"),
                            'user_id'           => $this->session->userdata('user_id'));

            $this->db->insert('cm_head', $cm);
            return $this->db->insert_id();
        } else {
            return false;
        }

    }

    public function uploadCMLine($item, $desc, $uom, $qty, $direct, $unitLcy, $amt, $amtVat, $unitCost, $line, $cmId)
    {
        $query = $this->db->get_where('cm_line', array('cm_head_id' => $cmId, 'item_code' => $item));
        if($query->num_rows() == 0){
            $line = array('cm_head_id'       => $cmId,
                          'item_code'        => $item,
                          'description'      => $desc,
                          'uom'              => $uom,
                          'qty'              => $qty,
                          'direct_unit_cost' => $direct,
                          'unit_cost_lcy'    => $unitLcy,
                          'amt'              => $amt,
                          'amt_including_vat'=> $amtVat,
                          'unit_cost'        => $unitCost,
                          'line_amt'         => $line );

            return $this->db->insert('cm_line', $line);        
        } 
    }

    public function getCmDetails($cmId)
    {
        $query  = $this->db->query('SELECT cm_head_id,item_code, description,unit_cost_lcy * 1.12 AS price,uom,qty FROM cm_line WHERE cm_head_id = '. $cmId);
        return $query->result_array();
    }

    public function getCmHead($piId)
    {
        $query = $this->db->get_where('cm_head', array('pi_head_id' => $piId));
        return $query->row_array();
    }

    public function getCmLineDetails($cmId)
    {
        $query = $this->db->select('h.cm_head_id,h.cm_no,h.posting_date,pi.pi_no,l.item_code,l.description,l.uom,l.qty,l.direct_unit_cost,i.item_department_code,i.itemcode_loc,i.item_group_code')
                          ->from('cm_line l')
                          ->join('cm_head h', 'h.cm_head_id = l.cm_head_id', 'inner')
                          ->join('purchase_invoice_header pi', 'pi.pi_head_id = h.pi_head_id', 'inner')
                          ->join('items i', 'i.itemcode_loc = l.item_code', 'inner')
                          ->get();
        return $query->result_array();
    }

 
}