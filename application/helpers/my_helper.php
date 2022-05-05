<?php

function JSONResponse($data)
{
    header('Content-Type: application/json');
    die(json_encode($data));
}

function getSequenceNo(array $seq, array $option, $useNext = false)
{
    $seq = (object) $seq;
    $option = (object) $option;

    $CI = &get_instance();
    $db = &$CI->db;


    $query = $db->query("
        SELECT  
            *, 
            concat( `code`, lpad(`number`, `lpad`, `pad_string`)) as sequence 
        FROM 
            sequence
        WHERE
            code = '$seq->code'
        LIMIT 
            1
    ");


    if ($query->num_rows() == 0) {
        unset($seq->table);

        $db->insert('sequence', (array)$seq);
        $seq->sequence = $seq->code . str_pad($seq->number, $seq->lpad, $seq->pad_string, STR_PAD_LEFT);
    } else {
        $seq = $query->row();

        $seq->number++;
        $seq->sequence = $seq->code . str_pad($seq->number, $seq->lpad, $seq->pad_string, STR_PAD_LEFT);
    }


    $update_result = true;


    do {
        $existing = true;
        while ($existing) {
            $fquery = $db->query("
                SELECT  
                    *    
                FROM 
                    $option->table
                WHERE
                    $option->column = '$seq->sequence'
            ");

            if ($fquery->num_rows() == 0) {
                $existing = false;

                if (!$useNext) {
                    return $seq->sequence;
                }
            } else {

                if (!$useNext) {
                    $db->update('sequence', ['number' => $seq->number], ['code' => $seq->code]);
                }

                $seq->number++;
                $seq->sequence = $seq->code . str_pad($seq->number, $seq->lpad, $seq->pad_string, STR_PAD_LEFT);
            }
        }

        $db->update('sequence', ['number' => $seq->number], ['code' => $seq->code]);

        if ($useNext && $db->affected_rows() == 0) {
            $update_result = false;
            $seq->number++;
            $seq->sequence = $seq->code . str_pad($seq->number, $seq->lpad, $seq->pad_string, STR_PAD_LEFT);
        } else {
            $update_result = true;
        }
    } while (!$update_result);

    return $seq->sequence;
}

function backToGrossSop($supId,$amount,$disc1,$disc2,$disc3,$vat)
{    
    if($supId == 1){ //n/a
        $gross = ($amount / $disc1) * $vat;
    } else if($supId == 2){ //js unitrade /7%/4%
        $gross = $amount * $vat / $disc1 / $disc2 ;
    } else if($supId == 5){ //intelligent /10%/10%/4% * VAT        
        $gross =  $amount * $vat  / $disc1 / $disc2 / $disc3  ;//$amount * 1.12  / 0.90 / 0.90 / 0.96  ; 
    } else if($supId == 9){ //mondelez 6%or7% * VAT
        $gross = ($amount / $disc1) * $vat ;
    } else if($supId == 14){ //VALIANT
        $gross = ($amount / $disc1) * $vat;
    } else if($supId == 13){ //ACS
        $gross = $amount  ;
    } else if($supId == 3){ //COSMETIQUE
        $gross = $amount / $disc1 / $disc2 / $disc3 ;
    } else if($supId == 10){ //SUYEN
        $gross = $amount / $disc1 ;
    } else if($supId == 16){ //SCPG
        $gross = $amount * $vat ;
    } else if($supId == 15){ //MCKENZIE
        $gross = ($amount / $disc1) * $vat ;
    } else if($supId == 18){ //ALECO
        $gross = $amount  ;
    }             
    return $gross ;
}

function backToGross($supId,$amount,$disc1,$disc2,$disc3,$vat)
{    
    if($supId == 1){ //n/a
        $gross = ($amount / $disc1) * $vat;
    } else if($supId == 2){ //js unitrade /7%/4%
        $gross = $amount * $vat  ;
    } else if($supId == 5){ //intelligent /10%/10%/4% * VAT        
        $gross =  $amount * $vat  / $disc1 / $disc2 / $disc3  ; //$amount * 1.12  / 0.90 / 0.90 / 0.96  ; 
    } else if($supId == 9){ //mondelez 6%or7% * VAT
        $gross = ($amount / $disc1) * $vat ;
    } else if($supId == 16){ //SCPG
        $gross = ($amount / $disc1) * $vat ; 
    } else if($supId == 14){ //VALIANT
        $gross = ($amount / $disc1) * $vat ;
    } else if($supId == 13){ //ACS
        $gross = $amount  ;
    } else if($supId == 3){ //COSMETIQUE
        $gross = $amount ;
    } else if($supId == 10){ //SUYEN
        $gross = $amount ;
    } else if($supId == 15){ //MCKENZIE
        $gross = ($amount / $disc1) * $vat ;
    } else if($supId == 18){ //ALECO
        $gross = $amount ;
    }     
       
    return $gross ;
}

function netPrice($supId,$pricing,$price,$disc1,$disc2,$disc3,$vat)
{
    if($pricing == "NETofVAT&Disc"){        //mondelez,intelligent,valiant 
        $netPrice  = $price;
    } else if($pricing == "GROSSofVAT&Disc"){
         if($supId == 3){ //COSMETIQUE
            $netPrice = ($price / $vat) * $disc1 * $disc2 * $disc3;
        } else if($supId == 10){ //SUYEN
            $netPrice = ($price / $vat) * $disc1 ;
        } else if($supId == 13){ //ACS
            $netPrice  = ($price / $vat) * $disc1 * $disc2 ; 
        } else if($supId == 18){ //ALECO
            $netPrice  = ($price / $vat) * $disc1 ; 
        } 
    } else if($pricing == "NETofVATwDisc"){
        if($supId == 2){ // js unitrade
            $netPrice  = $price * $disc1 * $disc2 ; //1401.88 * 0.93 * 0.96
        } 
    } else if($pricing == "GROSSofDiscwoVAT") {
        if($supId == 16){ //SCPG
            $netPrice = $price * $disc1 ;
        } else if($supId == 15){ //MCKENZIE
            $netPrice = $price * $disc1 ;
        }
    }

    return $netPrice ;
}

function discountedPrice($supId,$amount,$disc1,$disc2,$disc3,$vat)
{
    if($supId == 14){ //valiant
        $discounted = $amount  * $vat ; // net of discount & vat, so ibalik ra ang vat aron makuha ang discounted price
    } else if($supId == 15){ //MCKENZIE
        $discounted = $amount * $vat ;
    } else if($supId == 3){//COSMETIQUE
        $discounted = $amount * $disc1 * $disc2 * $disc3 ;
    } else if($supId == 13){ //ACS
        $discounted = $amount * $disc1 * $disc2  ;
    } else if($supId == 5){ //INTELLIGENT
        $discounted = $amount * $vat ;
    } else if($supId == 2){ //JS
        $discounted = $amount * $vat  ; 
    } else if($supId == 10){ //SUYEN 
        $discounted = $amount * $disc1 ;
    } else  if($supId == 16){ //SCPG
        $discounted = $amount * $vat ;
    } else  if($supId == 9){ //MONDELEZ
        $discounted = $amount * $vat ;
    } else  if($supId == 18){ //ALECO
        $discounted = $amount * $disc1 ;
    }
    return $discounted ;
}

function netPricePi($supId,$price,$disc1,$disc2,$disc3,$vat)
{ 
    if($supId == 3){ //COSMETIQUE
        $netPrice = ($price / $vat) * $disc1 * $disc2 * $disc3 ;
    } else if($supId == 10){ //SUYEN
        $netPrice = ($price / $vat) * $disc1 ;
    } else if($supId == 13){ //ACS
        $netPrice  = ($price / $vat) * $disc1 * $disc2 ; 
    } else if($supId == 2){ // js unitrade
        $netPrice  = ($price * $disc1 * $disc2) / $vat ; //1401.88 * 0.93 * 0.96
    } else if($supId == 16){ //SCPG
        $netPrice = ($price / $vat) * $disc1 ;
    } else if($supId == 15){ //MCKENZIE
        $netPrice = $price * $disc1 ;
    } else if($supId == 5){ //INTELLIGENT
        $netPrice = ($price / $vat)  * $disc1 * $disc2 * $disc3 ; //($price / 1.12)  * 0.90 * 0.90 * 0.96 ;
    } else if($supId == 14){ //VALIANT
        $netPrice = ($price / $vat) * $disc1 ;
    } else if($supId == 18){ //ALECO 
        $netPrice = ($price / $vat) * $disc1 ;
    } else if($supId == 9){ //MONDELEZ 
        $netPrice = ($price / $vat) * $disc1 ;
    }

    return $netPrice ;
}

function discountedPricePi($supId,$amount,$disc1,$disc2,$disc3)
{
    if($supId == 3){ //COSMETIQUE
        $discounted = $amount * $disc1 * $disc2 * $disc3 ;
    } else if($supId == 15){ //MCKENZIE
        $discounted = $amount * $disc1  ;
    } else if($supId == 13){ //ACS
        $discounted = $amount * $disc1 * $disc2 ;
    } else if($supId == 5){ //INTELLIGENT
        $discounted = $amount * $disc1 * $disc2 * $disc3 ;//$amount * 0.90 * 0.90 * 0.96  ; 
    } else if($supId == 2){ //JS
        $discounted = $amount * $disc1 * $disc2 ; 
    } else if($supId == 14){ //VALIANT 
        $discounted = $amount * $disc1  ;
    } else if($supId == 10){ //SUYEN 
        $discounted = $amount * $disc1  ;
    } else if($supId == 16){ //SCPG
        $discounted = $amount  * $disc1 ;
    } else if($supId == 9){ //MONDELEZ
        $discounted = $amount  * $disc1 ;
    } else if($supId == 18){ //ALECO
        $discounted = $amount  * $disc1 ;
    }
    return $discounted ;
}

/* search by value multid */
function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}


function array_flatten($array) {

    $return = array();
    foreach ($array as $key => $value) {
        if (is_array($value)){ $return = array_merge($return, array_flatten($value));}
        else {$return[$key] = $value;}
    }
    return $return;
 
 }
