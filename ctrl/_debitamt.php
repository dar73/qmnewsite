<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common.php');
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$disp_url="post_pay.php";
// sleep(5);
// DFA($_POST);
// exit;

// [IsDiscount] => Y
//     [txtdisamt] => 10
//  [mode] => U
//     [APPID] => 259
//     [txtspID] => 380
//     [title] => test
//     [description] => test
//     [location] => shadsa
$APPID = $_POST['appid'];
$SPID = $_POST['spid'];
$AMOUNT = $_POST['AMT'];


$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");


$SP_DATA = GetDataFromCOND("service_providers", " and id='$SPID' ");
$SP_FIRST_NAME = db_input2($SP_DATA[0]->First_name);
$SP_LAST_NAME = db_input2($SP_DATA[0]->Last_name);
$SP_ADDRESS = db_input2($SP_DATA[0]->street);
$SP_CITY = db_input2($SP_DATA[0]->city);
$SP_STATE = db_input2($SP_DATA[0]->state);

$CITY_NAME = GetXFromYID("select city_name from cities where city_id='$SP_CITY' ");
$STATE_NAME = GetXFromYID("select state_name from states where state_id='$SP_STATE' ");

$access_token = GetXFromYID("select vAccessToken from service_providers where id=$SPID ");
$refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$SPID ");


$BID = GetXFromYID("select iBookingID from appointments where iApptID='$APPID' "); //db_input2($_POST['BID']);
$date = ''; //db_input2($_POST['date']);
$start_time = ''; //db_input2($_POST['time_from']);
$end_time = ''; //db_input2($_POST['time_to']);

$Q1ANS = GetXFromYID("select iAnswerID from leads_answersheet where iResponseID='$BID' and  iQuesID='1' ");

// if ($Q1ANS == '101' || $Q1ANS == '102') {
//     $AMOUNT = 85;
// } elseif ($Q1ANS == '103') {
//     $AMOUNT = 99;
// } else {


  
//}

// echo $AMOUNT;
// exit;

//$AMOUNT = 1;

$CFEE = $AMOUNT * 0.03;
$DEBIT_AMT = $AMOUNT + $CFEE;
$DEBIT_AMT = number_format($DEBIT_AMT, 2);



$ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);

if (empty($ACCESS['data'])) {
    //echo "0~Refresh Token Expired please request new one.";
    //header("location: $disp_url");
    // $_SESSION[PROJ_SESSION_ID]->error_info ="Refresh Token Expired please request new one.";
    // header("location: $disp_url");
    // exit;
}
//$access_token =  $ACCESS['data']['access_token'];


$AUTH_GUID = GetXFromYID("select payment_id from transaction2 where pid='$SPID' and payment_status='A' order by id desc limit 1 ");
if (empty($AUTH_GUID)) {
    $_SESSION[PROJ_SESSION_ID]->error_info ="SP's AUTH_GUID DOES NOT EXIST CANNOT PROCEED WITH DEBIT .";
    header("location: $disp_url");
    exit;
}

//STEP 1 STARTS 
sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE");

$ID = NextID('id', 'transaction');
$_q = "insert into transaction values ('$ID','$BID','$APPID','$SPID','','','$DEBIT_AMT','" . NOW . "','online','P')";
$_r = sql_query($_q, "");

UnlockTable();

$curl = curl_init();

$BATCH_ID = generateRandomNumber();

$data = array('CUST_NBR' => '3001', 'MERCH_NBR' => '3130034428641', 'DBA_NBR' => '1', 'TERMINAL_NBR' => '3', 'TRAN_TYPE' => 'CCE1', 'AMOUNT' => $DEBIT_AMT, 'BATCH_ID' => $BATCH_ID, 'TRAN_NBR' => $ID, 'ORIG_AUTH_GUID' => $AUTH_GUID, 'INDUSTRY_TYPE' => 'E', 'FIRST_NAME' => $SP_FIRST_NAME, 'LAST_NAME' => $SP_LAST_NAME, 'ADDRESS' => $SP_ADDRESS, 'CITY' => $CITY_NAME, 'STATE' => $STATE_NAME, 'ZIP_CODE' => '12345', 'ACI_EXT' => 'RB');

$data_string = http_build_query($data);

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://secure.epx.com',
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

$response = curl_exec($curl);

curl_close($curl);
$xmlObject = simplexml_load_string($response);
$json = json_encode($xmlObject);
$array = json_decode($json, true);

//DFA($array);
$RESPONSE_ARR = array();
foreach ($xmlObject->FIELDS->FIELD as $field) {
    $key = (string)$field['KEY'];
    $value = (string)$field;
    //echo "Key: $key, Value: $value\n";
    $RESPONSE_ARR[] = array($key => $value);
}

$FULLNAME=$SP_FIRST_NAME.' '.$SP_LAST_NAME;

$PAYMENT_STR = json_encode($RESPONSE_ARR);
SendInBlueMail("Post Payment Ping FROM CURL FOR ". $FULLNAME, 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', 'michael2@thequotemasters.com');

if ($RESPONSE_ARR['11']['AUTH_RESP'] == '00') {
    sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE,platinum_purchase_leads write");
    $TRANS_ID = $RESPONSE_ARR['7']['TRAN_NBR'];
    $TRANS_REF = $RESPONSE_ARR['10']['AUTH_GUID'];

    $_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$TRANS_ID'  ";
    $_r1 = sql_query($_q1, "");
    list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
    $_q2 = "update transaction set payment_id='$TRANS_REF',payment_status='S' where id='$TRANS_ID' ";
    sql_query($_q2, "");

    
    $_q3 = "update  buyed_leads set fAmt='$DEBIT_AMT',vTransactionID='$TRANS_REF' where iApptID='$iApptID' ";
    sql_query($_q3, "");
   

    $_q4="update platinum_purchase_leads set fAmt='$DEBIT_AMT',vTransactionID='$TRANS_REF',cPaid='Y',dtPaid=NOW() where  iApptID='$iApptID'";
    sql_query($_q4);

    UnlockTable();

    

    //echo "1~Card Debit successfull with Trans Ref $TRANS_REF";
    $_SESSION[PROJ_SESSION_ID]->success_info = "Card Debit successfull with Trans Ref $TRANS_REF";
    header("location: $disp_url");
    exit;
} else {
    //echo "0~Card debit  failed!!";
    $_SESSION[PROJ_SESSION_ID]->error_info = "Card debit  failed!!";
    header("location: $disp_url");
    exit;
}




function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}
//DFA($keyValuePairs);

function SendInBlueMail($subject, $email, $contents, $attachment, $cc = '', $site_title = '', $bcc = '')
{
    if (empty($site_title))
        $site_title = 'Quote Masters';

    if (!empty($contents))
        //$contents .= '<br /><img src="'.SITE_ADDRESS.'img/mail_signature-latest.jpg" alt="Mail Signature" />';

        $cc = '';
    // if ($subject != 'OTP for Login') //empty($bcc) && 
    // $bcc = 'ops@thequotemasters.com';

    $config = array();
    $config['api_key'] = "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";
    $config['api_url'] = "https://api.sendinblue.com/v3/smtp/email";

    $message = array();
    $message['sender'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");
    $message['to'][] = array("email" => "$email");
    $message['replyTo'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");

    if (!empty($cc)) {
        $cc_arr = explode(",", $cc);
        for ($c = 0; $c < sizeof($cc_arr); $c++)
            $message['cc'][] = array("email" => "$cc_arr[$c]");
    }

    if (!empty($bcc)) {
        $bcc_arr = explode(",", $bcc);
        if (!in_array('ops@thequotemasters.com', $bcc_arr)) {
            $bcc_arr[] = 'ops@thequotemasters.com';
            $bcc = implode(",", $bcc_arr);
        }
        for ($b = 0; $b < sizeof($bcc_arr); $b++)
            $message['bcc'][] = array("email" => "$bcc_arr[$b]");
    } else {
        $bcc = 'ops@thequotemasters.com';
    }

    $message['subject'] = $subject;
    $message['htmlContent'] = $contents;

    if (!empty($attachment)) {
        if (is_array($attachment)) {
            $attachment_item[] = array('url' => $attachment);
            $attachment_list = array($attachment_item);

            // Ends pdf wrapper
            $message['attachment'] = $attachment_list;
        } else {
            $attachment_item = array('url' => $attachment);
            $attachment_list = array($attachment_item);
            // Ends pdf wrapper

            $message['attachment'] = $attachment_list;
        }
    }

    $message_json = json_encode($message);

    $ch = curl_init();
    curl_setopt(
        $ch,
        CURLOPT_URL,
        $config['api_url']
    );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: application/json',
        'api-key: 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',
        'content-type: application/json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
