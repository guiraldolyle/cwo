<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SOPController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('Pdf');
        $this->load->library('evalmath');
        $this->load->helper(array('form', 'url'));
        date_default_timezone_set('Asia/Manila');

        if (!$this->session->userdata('cwo_logged_in')) {

            redirect('home');
        }

        $this->load->model('sop_model');
        //Disable Cache
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        ('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }   

    private function printSOP($sopId)
    {
        $supplier      = $this->sop_model->getData('SELECT s.supplier_code, s.supplier_name FROM sop_head sop INNER JOIN suppliers s ON s.supplier_id = sop.supplier_id WHERE sop.sop_id = ' . $sopId);
        $customer      = $this->sop_model->getData('SELECT c.customer_code, c.customer_name FROM sop_head sop INNER JOIN customers c ON c.customer_code = sop.customer_code WHERE sop.sop_id = ' . $sopId);
        $headData      = $this->sop_model->getHeadData($sopId);
        $invoiceData   = $this->sop_model->getInvoiceData($sopId);
        $deductionData = $this->sop_model->getDeductionData($sopId);
        $chargesData   = $this->sop_model->getChargesData($sopId);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215, 279.4), true, 'UTF-8', false); //215.9 by 279.4 mm

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Mariel Taray');
        $pdf->SetTitle('CWO-SOP');
        $pdf->SetSubject('CWO-SOP');
        $pdf->SetKeywords('CWO, SOP');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 004', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(17, 15, 15);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 5, 'ALTURAS GROUP OF COMPANIES', 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 5, 'B. INTING ST., TAGBILARAN CITY', 0, 0, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 5, 'SUMMARY OF PAYMENTS', 0, 0, 'C');
        $pdf->Ln(15);

        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(20, 5, 'SUPPLIER :', 0, 0, 'L');
        $pdf->Cell(110, 5, $supplier['supplier_code'] . ' - ' . $supplier['supplier_name'], 0, 0, 'L');
        $pdf->Cell(18, 5, 'NUMBER : ', 0, 0, 'L');
        $pdf->Cell(35, 5, $headData['sop_no'], 0, 0, 'R');
        $pdf->Ln(5);
        $pdf->Cell(20, 5, 'SECTION :', 0, 0, 'L');
        $pdf->Cell(110, 5, $customer['customer_code'] . ' - ' . $customer['customer_name'], 0, 0, 'L');
        $pdf->Cell(18, 5, 'DATE : ', 0, 0, 'L');
        $pdf->Cell(35, 5, date("m/d/Y", strtotime($headData['datetime_created'])), 0, 0, 'R');
        $pdf->Ln(5);
        $pdf->Cell(0, 0, '', 'B', '', '', false);
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 10);

        $pdf->Cell(40, 8, 'PO NO.', 0, 0, 'L');
        $pdf->Cell(30, 8, 'PO DATE', 0, 0, 'L');

        $pdf->Cell(40, 4, 'PROFORMA', 0, 0, 'L');
        $pdf->Ln();
        $pdf->setX(87);
        $pdf->Cell(40, 4, 'INVOICE NO.', 0, 0, 'L');

        $pdf->Ln(-4.7);
        $pdf->setX(127);
        $pdf->Cell(32, 4, 'PROFORMA', 0, 0, 'L');
        $pdf->Ln();
        $pdf->setX(127);
        $pdf->Cell(32, 4, 'INVOICE DATE', 0, 0, 'L');

        $pdf->Ln(-4);
        $pdf->setX(159);
        $pdf->Cell(40, 8, 'AMOUNT', 0, 0, 'R');
        $pdf->Ln(4);
        $pdf->Cell(0,0,'','B','','',false);
        $pdf->Ln(5);


        $pdf->SetFont('helvetica', '', 9);

        $invTotal    = 0;
        $chargeTotal = 0;
        $dedTotal    = 0;
        $net         = 0;
        foreach( $invoiceData as $inv)
        {
            $pdf->Cell(40, 0, $inv['po_no'], 0, 0, 'L');
            $pdf->Cell(30, 0, date("m/d/Y", strtotime($inv['po_date'])), 0, 0, 'L');
            $pdf->Cell(40, 0, $inv['so_no'], 0, 0, 'L');
            $pdf->Cell(32, 0, date("m/d/Y", strtotime($inv['order_date'])) , 0, 0, 'L');
            $pdf->Cell(40, 0, number_format($inv['invoice_amount'] ,2), 0, 0, 'R');

            $invTotal += $inv['invoice_amount'];
            $pdf->Ln();
        }

        $pdf->Ln(2);
        $pdf->Cell(0,0,'------------------------------',0,0,'R');
        $pdf->Ln(3);
        $pdf->Cell(40, 0, '', 0, 0, 'L');
        $pdf->Cell(40, 0, 'Proforma Sales Invoice Total', 0, 0, 'L');
        $pdf->Cell(103, 0,'P '. number_format($invTotal ,2), 0, 0, 'R');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'I', 7);
        $pdf->Cell(43, 0, '', 0, 0, 'L');
        $pdf->Cell(30, 0, 'PSI (Net of VAT)', 0, 0, 'L');
        $pdf->Cell(30, 0, 'P '. number_format($invTotal / 1.12 ,2), 0, 0, 'R');
        $pdf->Ln(4);
        $pdf->Cell(43, 0, '', 0, 0, 'L');
        $pdf->Cell(30, 0, 'VAT', 0, 0, 'L');
        $pdf->Cell(30, 0, 'P '. number_format( $invTotal - ( $invTotal / 1.12 ) ,2), 0, 0, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', '', 9);
        if(  !empty($chargesData) ){
            $pdf->Cell(40, 0, '', 0, 0, 'L');
            $pdf->Cell(40, 0, 'Add : Charges', 0, 0, 'L');
            $pdf->Ln();
            foreach( $chargesData as $charge)
            {
                $pdf->Cell(45, 0, '', 0, 0, 'L');
                $pdf->Cell(80,5, $charge['description'],0,0,'L');
                $pdf->Cell(30,5, number_format($charge['charge_amount'],2),0,0,'R');

                $chargeTotal += $charge['charge_amount'] ;
                $pdf->Ln();
            }

            $pdf->Cell(183, 0,'P '. number_format($chargeTotal ,2), 0, 0, 'R');
            $pdf->Ln(2);
            $pdf->Cell(0,0,'------------------------------',0,0,'R');
            $pdf->Ln(2);
        }

        if( !empty($deductionData) ){
            $pdf->Cell(40, 0, '', 0, 0, 'L');
            $pdf->Cell(40, 0, 'Less : Deductions', 0, 0, 'L');
            $pdf->Ln();
            foreach( $deductionData as $ded)
            {
                $pdf->Cell(45, 0, '', 0, 0, 'L');
                $pdf->Cell(80,5, $ded['description'],0,0,'L');
                $pdf->Cell(30,5, number_format($ded['deduction_amount'],2),0,0,'R');

                $dedTotal += $ded['deduction_amount'] ;
                $pdf->Ln();
            }
            $pdf->Cell(183, 0,'P '. number_format($dedTotal ,2), 0, 0, 'R');
            $pdf->Ln(2);
            $pdf->Cell(0,0,'------------------------------',0,0,'R');
            $pdf->Ln(5);
        }

        $net = $invTotal + $chargeTotal + $dedTotal;

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(42, 0, '', 0, 0, 'L');
        $pdf->Cell(40, 0, 'NET PAYABLE AMOUNT', 0, 0, 'R');
        $pdf->Cell(102, 0, 'P ' . number_format($net, 2), 0, 0, 'R');

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(10, 0, 'Legend:', 0, 0, 'L');
        $pdf->Cell(30, 0, 'PSI - Proforma Sales Invoice', 0, 0, 'L');
        $pdf->Ln(1);
        $pdf->Cell(0,0,'','B','','',false);
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(20, 0, 'Prepared by :', 0, 0, 'L');
        $pdf->Cell(50, 0, $this->session->userdata('name'), 'B', 0, 'C');
        $pdf->Cell(38, 0, '', 0, 0, 'L');
        $pdf->Cell(20, 0, 'Audited by :', 0, 0, 'L');
        $pdf->Cell(50, 0, '', 'B', 0, 'L');
        $pdf->Ln();
        $pdf->Cell(20, 0, '', 0, 0, 'L');
        $pdf->Cell(50, 0, '(Accounts Payable Clerk)', 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->SetX(70);
        $pdf->Cell(20, 0, 'Approved by :', 0, 0, 'L');
        $pdf->Cell(50, 0, '', 'B', 0, 'L');
        $pdf->Ln();
        $pdf->SetX(90);
        $pdf->Cell(50, 0, '(Section/Department Head)', 0, 0, 'C');

        $pdf->Ln(10);
        $pdf->Cell(27, 0, 'Pricing Incharge :', 0, 0, 'L');
        $pdf->Cell(30, 0, '', 'B', 0, 'L');
        $pdf->Cell(25, 0, 'Inv. Clerk :', 0, 0, 'R');
        $pdf->Cell(30, 0, '', 'B', 0, 'L');
        $pdf->Cell(32, 0, 'Checked by :', 0, 0, 'R');
        $pdf->Cell(30, 0, '', 'B', 0, 'L');

        $fileName = "CWO-" . $headData['sop_no'] . time() . '.pdf';
        $pdf->Output(getcwd() . '/files/Reports/SOP/' . $fileName, 'F');

        return $fileName;
    }


    public function getSuppliersSop()
    {
        $result = $this->sop_model->getSuppliers();
        return JSONResponse($result);
    }

    public function getSupplierName()
    {
        $fetch_data = json_decode($this->input->raw_input_stream, TRUE);
        $result     = $this->sop_model->getSupData('supplier_name', $fetch_data['supId'], 'supplier_id');
        return JSONResponse($result->supplier_name);
    }

    public function getCustomersSop()
    {
        $result = $this->sop_model->getCustomers();
        return JSONResponse($result);
    }

    public function loadVendorsDeal()
    {
        $fetch_data   = json_decode($this->input->raw_input_stream, TRUE);
        $getDeals     = $this->sop_model->getDeals($fetch_data['supId']);
        return JSONResponse($getDeals);
    }

    public function loadSONos()
    {
        $fetch_data               = json_decode($this->input->raw_input_stream, TRUE);
        $eval                     = new EvalMath();
        $getSOs                   = $this->sop_model->loadSONos($fetch_data['supId']);
        $getNoOfDiscount          = $this->sop_model->getSupData('number_of_discount', $fetch_data['supId'], 'supplier_id');
        $getHasDeal               = $this->sop_model->getSupData('has_deal', $fetch_data['supId'], 'supplier_id');
        $vat                      = $this->sop_model->getVATData()['value'];
        $getProformaItemAmount    = $this->sop_model->getProformaItemAmount($fetch_data['supId']);
        $getVendorsDeal           = $this->sop_model->getVendorsDealLine($fetch_data['dealId']);
 
        $final           = array();
        $proformaItems   = array();
        $itemCodeToFind  = "";        
        $disc1           = 0.00;
        $disc2           = 0.00;
        $disc3           = 0.00;
        $evaluatedAmount = 0;

        foreach($getSOs as $so)
        {
            $proformaAmount  = 0;
            foreach($getProformaItemAmount as $prof)//foreach($getProformaItemAmount as &$prof)
            {         
                if($getHasDeal->has_deal == "1"){
                    foreach($getVendorsDeal as $deal)
                    {                    
                        if($deal['type'] == "Item Department"){
                            $itemCodeToFind = $prof['item_department_code'] ;
                        } else if($deal['type'] == "Item") {
                            $itemCodeToFind = $prof['itemcode_loc'] ;
                        } else if($deal['type'] == "Item Group"){
                            $itemCodeToFind = $prof['item_group_code'] ;
                        }     
                        // $find = $this->sop_model->findItemCodeInDeals($itemCodeToFind,$fetch_data['dealId'])['number'];  
                        // if($find){
                            if($deal['number'] == $itemCodeToFind){
                                if($getNoOfDiscount->number_of_discount == "1"){                               
                                    $disc1 = 1.00 - $deal['disc_1'] * 0.01 ; 
                                    $evaluatedAmount = backToGrossSop($fetch_data['supId'],$prof['amount'],$disc1,0,0,$vat); 
                                } else if($getNoOfDiscount->number_of_discount == "2"){
                                    $disc1 = 1.00 - $deal['disc_1'] * 0.01 ; 
                                    $disc2 = 1.00 - $deal['disc_2'] * 0.01 ; 
                                    $evaluatedAmount = backToGrossSop($fetch_data['supId'],$prof['amount'],$disc1,$disc2,0,$vat);
                                } else if($getNoOfDiscount->number_of_discount == "3"){                                
                                    $disc1 = 1.00 - $deal['disc_1'] * 0.01; 
                                    $disc2 = 1.00 - $deal['disc_2'] * 0.01; 
                                    $disc3 = 1.00 - $deal['disc_3'] * 0.01;
                                    $evaluatedAmount = backToGrossSop($fetch_data['supId'],$prof['amount'],$disc1,$disc2,$disc3,$vat);
                                }  
                            }
                            
                            // $prof['deals'] = "Yes";
                           
                        // } else {
                            // $prof['deals'] = "No";
                        // }                       
                    } 
                } else if($getHasDeal->has_deal == "0"){
                    $evaluatedAmount = backToGrossSop($fetch_data['supId'],$prof['amount'],0,0,0,$vat);
                }

                if($so['proforma_header_id'] == $prof['proforma_header_id'])
                {    
                    $proformaAmount += $evaluatedAmount; 
                }              
            }

            $final[] = array('proforma_header_id' =>$so['proforma_header_id'], 'so_no' =>$so['so_no'], 'order_date' =>$so['delivery_date'],
                            'po_no' =>$so['po_no'],'poDate' => $so['poDate'], 'amount' => $proformaAmount);
        }
        //  var_dump($final);
        // die();

        $returnArr = [ 'SONOs' => $final, 'items' => $getProformaItemAmount ];
        return JSONResponse($returnArr);
    }

    public function checkUserTypeSOP()
    {
        $userType = $this->session->userdata('userType');
        return JSONResponse($userType);
    }

    public function loadDeductionType()
    {
        $getDeductionType = $this->sop_model->loadDeductionType();
        return JSONResponse($getDeductionType);
    }

    public function loadDeduction()
    {
        $fetch_data   = json_decode($this->input->raw_input_stream, TRUE);
        $getNames     = $this->sop_model->getDeductionNames($fetch_data['typeId'], $fetch_data['supId']);
        return JSONResponse($getNames);
    }

    public function calcAmountToBeDeductedForRegDisc()
    {
        $fetch_data       = json_decode($this->input->raw_input_stream, TRUE);
        $invoiceData      = $fetch_data['invoice'];
        $getDeductionData = $this->sop_model->getData('SELECT * FROM deduction WHERE deduction_id= '. $fetch_data['dedId']);
        $vat              = $this->sop_model->getVATData()['value'];
        $getDeals         = $this->sop_model->getVendorsDealLine($fetch_data['dealId']);
        $line             = array();
        $profAmount       = 0;
        $disc1            = 0.00;
        $disc2            = 0.00;
        $disc3            = 0.00;

        foreach($invoiceData as $inv)
        {
            if(!empty($inv))
            {
                $line[] = $this->sop_model->getProformaLine($inv['profId']);
            }            
        }

        // $flatLine = array_merge([],...$line); #not compatible in 5.4
        $flatLine = call_user_func_array('array_merge', $line);
        foreach($flatLine as $l)
        {
            $itemCodeToFind = "";
            foreach($getDeals as $deals)
            {
                if($deals['type'] == "Item Department"){
                    $itemCodeToFind = $l['item_department_code'];
                } else if($deals['type'] == "Item"){
                    $itemCodeToFind = $l['itemcode_loc'];
                } else if($deals['type'] == "Item Group"){
                    $itemCodeToFind = $l['item_group_code'];
                }
                if($deals['number'] == $itemCodeToFind){                                                  
                    $disc1 = 1.00 - $deals['disc_1'] * 0.01; 
                    $disc2 = 1.00 - $deals['disc_2'] * 0.01; 
                    $disc3 = 1.00 - $deals['disc_3'] * 0.01; 
                }
                if($deals['number'] == $itemCodeToFind && $deals['disc_1'] == $getDeductionData['value_in_vd'] ){
                    $profAmount += backToGrossSop($fetch_data['supId'],$l['amount'],$disc1,$disc2,$disc3,$vat);
                }
            }
        }

        return JSONResponse($profAmount);        
        
    }

    public function calculateDeduction()
    {
        $fetch_data      = json_decode($this->input->raw_input_stream, TRUE);
        $eval            = new EvalMath();
        $getFormula      = $this->sop_model->getDeductionFormula($fetch_data['discountId']);
        $toEval          = $fetch_data['amount'] . ' ' . $getFormula->formula;
        $deductionAmount = round($eval->evaluate($toEval), 2);

        return JSONResponse($deductionAmount);
    }

    public function loadChargesType()
    {
        $getChargesType = $this->sop_model->loadChargesType();
        return JSONResponse($getChargesType);
    }

    public function submitSOP()
    {
        $fetch_data   = $this->input->post(NULL, TRUE);
        $msg          = array();
        $hasInvoice   = false;
        $hasDeduction = false;
        $hasCharges   = false;
        $saveInvoice  = 0;
        $saveDeduction = 0;
        $saveCharges  = 0;
        $sopNo        = "";
        $status       = "";

        $this->db->trans_start();
        $sopNo        = $this->sop_model->getDocNo(true);
        $headId       = $this->sop_model->saveHead($sopNo, $fetch_data['supId'], $fetch_data['cusId'], $fetch_data['invoiceAmount'], $fetch_data['chargesAmount'], $fetch_data['dedAmount'], $fetch_data['netAmount']);

        if ($headId) {

            if (!empty($fetch_data['invoice'])) {
                $hasInvoice = true;
                foreach ($fetch_data['invoice'] as $inv) {
                    $invoice = $this->sop_model->saveInvoice($inv['profId'], $inv['invoiceAmount'], $headId);
                    if ($invoice) {
                        $saveInvoice++;
                    }
                }
            }

            if (!empty($fetch_data['deduction'])) {
                $hasDeduction = true;
                foreach ($fetch_data['deduction'] as $ded) {
                    $deduction = $this->sop_model->saveDeduction($ded['dedId'], $ded['dedName'], $ded['dedAmount'], $ded['sopInvId'], $headId);
                    if ($deduction) {
                        $saveDeduction++;
                    }
                }
            }

            if (!empty($fetch_data['charges'])) {
                $hasCharges = true;
                foreach ($fetch_data['charges'] as $charge) {
                    $charges = $this->sop_model->saveCharges($charge['chargeId'], $charge['description'], $charge['chargeAmount'], $headId);
                    if ($charges) {
                        $saveCharges++;
                    }
                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $error = array('action' => 'Saving CWO SOP', 'error_msg' => $this->db->error()); //Log error message to `error_log` table
            $this->db->insert('error_log', $error);
            die("incomplete");
        } else {

            $file = $this->printSOP($headId);

            // for history
            $transaction = array(
                'tr_no'         => $sopNo,
                'tr_date'       => date("F d, Y - h:i:s A"),
                'supplier_id'   => $fetch_data['supId'],
                'customer_code' => $fetch_data['cusId'],
                'filename'      => $file,
                'user_id'       => $this->session->userdata('user_id')
            );
            $history = $this->db->insert('sop_transaction', $transaction);
            // for history

            if ($history) {
                $status = 'including History';
            } else {
                $status = 'excluding History';
            }
            if ($hasInvoice && $hasDeduction && $hasCharges && $headId) {
                if ($saveInvoice != 0 && $saveDeduction != 0 && $saveCharges != 0) {
                    $msg = ['info' => 'success', 'message' => 'SOP (Invoice, Deduction & Charges ' . $status . ') saved successfully!', 'file' => $file];
                }
            } else if ($hasInvoice && !$hasDeduction && $hasCharges && $headId) {
                if ($saveInvoice != 0  && $saveCharges != 0) {
                    $msg = ['info' => 'success', 'message' => 'SOP (Invoice & Charges ' . $status . ') saved successfully!', 'file' => $file];
                }
            } else if ($hasInvoice && $hasDeduction && !$hasCharges && $headId) {
                if ($saveInvoice != 0  && $saveDeduction != 0) {
                    $msg = ['info' => 'success', 'message' => 'SOP (Invoice & Deduction ' . $status . ') saved successfully!', 'file' => $file];
                }
            }

            return JSONResponse($msg);
        }
    }

    public function loadCwoSop()
    {
        $fetch_data    = json_decode($this->input->raw_input_stream, TRUE);
        $getCwoSopData = $this->sop_model->loadCwoSop($fetch_data['supId'], $fetch_data['cusId']);
        return JSONResponse($getCwoSopData);
    }

    public function loadSopDetails()
    {
        $fetch_data   = json_decode($this->input->raw_input_stream, TRUE);
        $getInvoice   = $this->sop_model->getInvoiceData($fetch_data['sopId']);
        $getDeduction = $this->sop_model->getDeductionData($fetch_data['sopId']);
        $getCharges   = $this->sop_model->getChargesData($fetch_data['sopId']);
        $data         = ['invoice' => $getInvoice, 'deduction' => $getDeduction, 'charges' => $getCharges];

        return JSONResponse($data);
    }

    public function generateSopHistory()
    {
        $fetch_data   = $this->input->post(NULL, TRUE);
        $history      = array();
        $document1    = array();

        if (!empty($fetch_data)) {

            $history = $this->sop_model->getTransactionHistory($fetch_data['transactionType'], $fetch_data['supplierSelect'], $fetch_data['locationSelect']);
        }

        return JSONResponse($history);
    }

    public function tagAsAudited()
    {
        $fetch_data = json_decode($this->input->raw_input_stream, TRUE);
        $tag        = $this->sop_model->tagAsAudited($fetch_data['sopId']);
        if($tag){
            $msg = ['info' => 'Success', 'message' => 'Successfully tagged as AUDITED!'];
        } else {
            $msg = ['info' => 'Error', 'message' => 'Failed to tag as AUDITED!'];
        }
        return JSONResponse($msg);
    }

    public function searchSOP()
    {
        $fetch_data = json_decode($this->input->raw_input_stream, TRUE);
        $sop        = $this->sop_model->getUnMentionSOPInv($fetch_data['supId'],$fetch_data['str']);
        return JSONResponse($sop);
    }

    
}
