<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PoController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->library('form_validation');
        date_default_timezone_set('Asia/Manila');

        if (!$this->session->userdata('cwo_logged_in')) {

            redirect('home');
        }

        $this->load->model('po_model');

        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    public function getSuppliersForPO()
    {
        $result = $this->po_model->loadSupplier();
        return JSONResponse($result);
    }

    public function getCustomersForPO()
    {
        $result = $this->po_model->loadCustomer();
        return JSONResponse($result);
    }

    public function getPOs()
    {
        $fetch_data = json_decode($this->input->raw_input_stream, TRUE);
        $result     = $this->po_model->loadPo($fetch_data['supId'], $fetch_data['cusId']);
        return JSONResponse($result);
    }

    public function uploadPo()
    {
        $fetch_data   = $this->input->post(NULL, TRUE);
        $supId        = $fetch_data['selectSupplier'];
        $cusId        = $fetch_data['selectCustomer'];
        $invalidExt   = array();
        $msg          = array();
        $itemNotFound = array();
        $poInserted   = 0;

        $this->db->trans_start();

        for ($i = 0; $i < count($_FILES['pofile']['name']); $i++) 
        {
            $getExt =  pathinfo($_FILES['pofile']['name'][$i], PATHINFO_EXTENSION);   
            if( $getExt == "ICM-SA-PST" || $getExt == "CENT-DC-PST" || $getExt == "CENT-DC" || $getExt == "ICM-SA" || 
                $getExt == "txt" || $getExt == "TXT"  ){
                echo '';
            } else {
                $invalidExt[] = $getExt;
            } 

            $poContent  = file_get_contents($_FILES['pofile']['tmp_name'][$i]);
            $line       = explode("\n", $poContent);
            $totalLine  = count($line);

            for ($n = 0; $n < $totalLine; $n++) {
                if ($line[$n] != NULL) {
                    $blits = str_replace('"', "", $line[$n]);
                    $refined = explode("|", $blits);
                    $countRefined = count($refined);
    
                    if ($countRefined == 11) {
                        $checkItem = $this->po_model->checkItem(trim($refined[1]));
                        if (!$checkItem) {
                            $itemNotFound[$n] =  trim($refined[1]);
                        }
                    }
                }
            }
        }

        if( !empty($invalidExt) ){
            $msg = [ 'info' => 'Error-ext', 'message' => 'Invalid file detected!', 'ext' => array_unique($invalidExt,SORT_STRING ) ];
        }
        if( !empty($itemNotFound) ){
            $msg = [ 'info' => 'Error-item', 'message' => 'Item not found in the masterfile!', 'item' => $itemNotFound  ];
        }     

        if( empty($invalidExt) && empty($itemNotFound) ){
            for ($p = 0; $p < count($_FILES['pofile']['name']); $p++) 
            {
                $poContent  = file_get_contents($_FILES['pofile']['tmp_name'][$p]);
                $line       = explode("\n", $poContent);
                $totalLine  = count($line);

                for ($i = 0; $i < $totalLine; $i++) 
                {
                    if ($line[$i] != NULL) {
                        $blits = str_replace('"', "", $line[$i]);
                        $refined = explode("|", $blits);
                        $countRefined = count($refined);
    
                        if ($countRefined == 7) {
                            $getSupCode = $this->po_model->getSupplierData($supId, 'supplier_code', 'supplier_id');                            
                            if ($getSupCode->supplier_code == trim($refined[5])) //validate if same ang sa selected supplier sa supplier  naa sa texfile
                            {    
                                $poNo        = trim($refined[0]);
                                $orderDate   = trim($refined[1]);
                                $postingDate = trim($refined[2]);
                                $reference   = trim($refined[4]);
                                $vendor      = trim($refined[5]);
    
                                $poId = $this->po_model->uploadHeader($poNo, $orderDate, $postingDate, $reference, $supId, $cusId);
                                if($poId){
                                    $poInserted ++ ;
                                }
                            } else {
                                $msg = [ 'info' => 'Error', 'message' => 'Invalid supplier!'];
                                break;
                            }
                        }
    
                        if ($countRefined == 11) {
                            $barcode     = trim($refined[0]);
                            $itemCode    = trim($refined[1]);
                            $qty         = trim($refined[2]);
                            $unitCost    = trim($refined[3]);
                            $uom         = trim($refined[4]);    
                            $insertLine = $this->po_model->uploadLine($barcode, $itemCode, $qty, $unitCost, $uom, $poId);
                        }
                    }
                }
            }

            if( count($_FILES['pofile']['name']) == $poInserted ){
                $msg = [ 'info' => 'Success', 'message' => 'PO uploaded successfully!'];
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $error = array('action' => 'Uploading Purchase Order', 'error_msg' => $this->db->error()); //Log error message to `error_log` table
            $this->db->insert('error_log', $error);
            $msg = [ 'info' => 'Error', 'message' => 'Error uploading PO!'];
        } 

        return JSONResponse($msg);       
    }

    public function getPoDetails()
    {
        $poId      = $this->uri->segment(4);
        $poDetails = $this->po_model->poDetails($poId);
        return JSONResponse($poDetails);
    }
}
