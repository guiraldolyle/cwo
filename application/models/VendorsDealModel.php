<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VendorsDealModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getDeals($supplierID)
    {
        $result = $this->db->SELECT("vl.vendor_deal_head_id, vh.vendor_deal_code, vl.line_no, vl.type, vl.description, vl.disc_1, vl.disc_2, vl.disc_3, vl.disc_4, vl.disc_5, vh.period_from, vh.period_to")
            ->FROM("vendors_deal_line vl")
            ->JOIN("vendors_deal_header vh", "vl.vendor_deal_head_id = vh.vendor_deal_head_id", "LEFT")
            ->WHERE("vh.supplier_id = '$supplierID'")
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    public function getSupplier($id)
    {
        $result = $this->db->SELECT('*')
            ->FROM('suppliers')
            ->WHERE('supplier_id', $id)
            ->GET()
            ->ROW();

        return $result;
    }

    public function getDuplicate($code)
    {
        $result = $this->db->SELECT('*')
            ->FROM('vendors_deal_header')
            ->WHERE('vendor_deal_code', $code)
            ->GET()
            ->ROW();

        return $result;
    }

    public function getItemDeptCode($supId)
    {
        $result = $this->db->query('SELECT i.item_department_code, vl.description FROM cwo.items i 
                                    INNER JOIN vendors_deal_line vl ON vl.number = i.item_department_code 
                                    WHERE i.supplier_id = '.$supId. ' GROUP BY i.item_department_code');
        return $result->result_array();             
    }


}
