<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();
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
$APPID = isset($_GET['appid']) ? $_GET['appid'] : 0;
$SPID = isset($_GET['spid']) ? $_GET['spid'] : 0;

echo $APPID.'~'. $SPID;
// exit;

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");


$SP_DATA = GetDataFromCOND("service_providers", " and id='$SPID' ");
$SP_FIRST_NAME = db_input2($SP_DATA[0]->First_name);
$SP_LAST_NAME = db_input2($SP_DATA[0]->Last_name);
$SP_ADDRESS = db_input2($SP_DATA[0]->street);
$SP_CITY = db_input2($SP_DATA[0]->city);
$SP_STATE = db_input2($SP_DATA[0]->state);



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
$AMOUNT = 0;

//}

// echo $AMOUNT;
// exit;

//$AMOUNT = 1;

$CFEE = $AMOUNT * 0.03;
$DEBIT_AMT = $AMOUNT + $CFEE;
$DEBIT_AMT = number_format($DEBIT_AMT, 2);

//check if already sold
$_q1 = "select * from appointments  where cService_status='B' and cStatus='A' and iApptID='$APPID' ";
$_r1 = sql_query($_q1);
// echo sql_num_rows($_r1);
// exit;
if (sql_num_rows($_r1)) {
    echo "0~Appointment slot has been sold already.";
    // //header("location: $disp_url");
    exit;
}





//STEP 1 STARTS 
sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE,platinum_purchase_leads write");

$ID = NextID('id', 'transaction');
$_q = "insert into transaction values ('$ID','$BID','$APPID','$SPID','','','$DEBIT_AMT','" . NOW . "','online','P')";
$_r = sql_query($_q, "");

UnlockTable();


SendInBlueMail("Assigned Lead $APPID", 'darshankubal1@gmail.com', "Assigned Lead $APPID", '', 'michael2@thequotemasters.com', '', 'michael2@thequotemasters.com');

if (true) {
    // echo 'hiii';
    // exit;
    sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE");
    $TRANS_ID = $ID; //$RESPONSE_ARR['7']['TRAN_NBR'];
    $TRANS_REF = 'ADDED BY ADMIN'; //$RESPONSE_ARR['10']['AUTH_GUID'];

    $_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$TRANS_ID'  ";
    $_r1 = sql_query($_q1, "");
    list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
    $_q2 = "update transaction set payment_id='$TRANS_REF',payment_status='S' where id='$TRANS_ID' ";
    sql_query($_q2, "");

    $updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$TRANS_ID' "; //update dat table
    sql_query($updatebookingdat);

    $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$DEBIT_AMT','$TRANS_REF')";
    sql_query($_q3, "");
    $_q4 = "UPDATE booking SET cService_status='B' WHERE iBookingID='$bookingID' ";
    sql_query($_q4, "");
    $_q5 = "UPDATE appointments SET cService_status='B' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
    sql_query($_q5, "");

    //insert into purchase leads table
    // $_qpl = "INSERT INTO platinum_purchase_leads(dDate, ivendor_id, ibooking_id, iApptID, fAmt, vTransactionID, cPaid,cStatus) VALUES (NOW(),'$pid','$bookingID','$iApptID','$DEBIT_AMT','$TRANS_REF','N','A')";
    // sql_query($_qpl);

    UnlockTable();

    $_q6 = "select iCustomerID,dDateTime,iAppTimeID from appointments where iApptID='$iApptID' ";
    $_q6r = sql_query($_q6, "");
    list($CUSTID, $DATEB, $TIMEID) = sql_fetch_row($_q6r);

    $Customer_name = GetXFromYID("select  CONCAT(vFirstname, ' ', vLastname) as full_name from customers where iCustomerID='$CUSTID' ");
    $ADATE = date('m-d-Y', strtotime($DATEB));
    $ATIME = $TIMEPICKER_ARR[$TIMEID];

    //send mail alert to customers
    $email = GetXFromYID("select vEmail from customers where iCustomerID='$CUSTID' ");
    $company_name = GetXFromYID("select company_name from service_providers where id='$pid' ");
    $Cleaners_name = GetXFromYID("select  CONCAT(First_name, ' ', Last_name) as full_name from service_providers where id='$pid' ");
    $SP_EMAIL = GetXFromYID("select  email_address  from service_providers where id='$pid' ");
    $to = db_output2($email);
    $subject = "Appointment Update";
    
    //mail($to, $subject, $mail_content, $headers);
    //Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
    //Send_mail('', '','darshankubal1@gmail.com', '', '', '', "Payment Ping", $PAYMENT_STR, '');
    //SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com');
    //SendInBlueMail("Payment Ping", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');


    //MODIFIED TO SENT ALERT TO SP REGARDING LEAD PURCHASED
    $MAIL_BODY = GET_LEAD_MAIL_CONTENT($APPID, $Cleaners_name);
    SendInBlueMail("Lead Purchase Success", $SP_EMAIL, $MAIL_BODY, '', '', '', "darshankubal1@gmail.com,michael2@thequotemasters.com");

    //STEP 2 START


    // DFA($response);
    // exit;

//     ArrayRESPONSE OF CALENDAR EVENT
// (
//     [data] => 0jgknrjffpa2v4v9f6f1lho340
//     [msg] => success
// )

    // Create an event on the primary calendar 
    // $EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);

    // if ($EVENT_RESPONSE['msg'] == 'token_expire') {
    //     $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
    //     $access_token = $ACCESS_RES['data']['access_token'];
    //     sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
    //     $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
    //     $timezone = $user_timezone['data'];
    //     $EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);
    // }

    //   DFA($EVENT_RESPONSE);
    //   exit;


    echo "1~Lead Details Successfully Inserted";
    exit;
} else {
    echo "0~Card debit  failed!!";
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
