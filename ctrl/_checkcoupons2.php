<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';


function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}
// DFA($_POST);
// exit;
//SELECT `iBookingID`, `iAppID`, `vCouponCode`, `fDisc`, `iVendorID`, `dtDate`, `cStatus` FROM `buyed_leads_dat` WHERE 1
//SELECT `iDiscountID`, `vName`, `vCode`, `fPercentage`, `iRank`, `cStatus` FROM `discounts` WHERE 1
// $bookingid = db_input2($_POST['bookingid']);
// $appointmentid = db_input2($_POST['appointmentid']);
$vendorid = db_input2($_POST['vendorid']);
$promocode = db_input2($_POST['promocode']);
$leadprice = db_input2($_POST['leadprice']);
$AMOUNT = 0;
// $CFEE = $AMOUNT * 0.03;
// $FINAL_AMT = $AMOUNT + $CFEE;
$_q1 = "select * from discounts where vCode='$promocode' and cStatus='A' ";
$_r1 = sql_query($_q1);
if(sql_num_rows($_r1))
{
    $_q2 = "select * from platinumfee_dat where vCouponCode='$promocode' and iVendorID='$vendorid' and cStatus='A' ";
    $_r2 = sql_query($_q2);
    if(sql_num_rows($_r2)){
        echo '401~~You have already used this promo code ,you can avail this offer only once !!';
        exit;
    }else{
        $_q3 = "select  fPercentage from discounts where cStatus='A' and vCode='$promocode' and '".TODAY."' between dtFrom and dtTo ";
        $_r3 = sql_query($_q3);
        if(sql_num_rows($_r3))
        {
            list($perc) = sql_fetch_row($_r3);
            $leadprice = (float) $leadprice;
            $perc = (float) $perc;
            $DISC_AMT = $leadprice * $perc / 100;
            $AMOUNT = $leadprice - $DISC_AMT;
            $CFEE = $AMOUNT * 0.03;
            $FINAL_AMT = $AMOUNT + $CFEE;
            $FINAL_AMT = number_format($FINAL_AMT, 2);
            //$FINAL_AMT = (int)$FINAL_AMT;
            $BATCH_ID = generateRandomNumber();
            LockTable('platinum_fee');
            $ID = NextID('id', 'platinum_fee');
            $_q = "insert into platinum_fee values ('$ID','$vendorid','','$FINAL_AMT','".NOW."','online','P')";
            $_r = sql_query($_q, "");
            UnlockTable();
            $data = array(
                'MAC' => 'MKrXPYxYMok0cH3dsL585NQJA35MO/e5Y3eaO6H/gXA=',
                'AMOUNT' => $FINAL_AMT,
                'TRAN_NBR' => $ID,
                'TRAN_GROUP' => 'SALE',
                'REDIRECT_URL' => 'https://thequotemasters.com/ctrl/paymentsuccess.php',
                'BATCH_ID' => $BATCH_ID
            );

            $data_string = http_build_query($data);


            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://keyexch.epx.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $xmlResponse = curl_exec($curl);
            curl_close($curl);
            $responseXML = simplexml_load_string($xmlResponse);

            // Access the value of the TAC field
            $tacValue = (string)$responseXML->FIELDS->FIELD;

            sql_query("INSERT INTO platinumfee_dat(iTransID,vCouponCode, fDisc, iVendorID, dtDate, cStatus) VALUES ('$ID','$promocode','$perc','$vendorid','".NOW."','P')");

            echo '200~~Success, you got $'.$DISC_AMT.' off  .~~' . $tacValue.'~~'.$BATCH_ID.'~~'.$AMOUNT.'~~'.$CFEE.'~~'.$FINAL_AMT.'~~'. $DISC_AMT;
            exit;
            

        }else{
            echo '401~~Not a valid coupon code!!';
            exit; 
        }


    }
}else{
    echo '401~~Not a valid coupon code!!';
    exit;
}





?>