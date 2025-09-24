<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common.php');
include '../phpmailer.php';

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

// DFA($_POST);
// exit;

$RESPONSE = isset($_POST['AUTH_RESP']) ? $_POST['AUTH_RESP'] : '';
$TRANS_ID = isset($_POST['TRAN_NBR']) ? $_POST['TRAN_NBR'] : '';
$TRANS_REF = isset($_POST['AUTH_GUID']) ? $_POST['AUTH_GUID'] : '';
$TRANS_AMT = isset($_POST['AUTH_AMOUNT']) ? $_POST['AUTH_AMOUNT'] : '';
$TRAN_TYPE = isset($_POST['TRAN_TYPE']) ? $_POST['TRAN_TYPE'] : '';
$success_url = 'psuccess.php';
$failure_url = 'pfail.php';



//echo 'Response='.$RESPONSE;


$payment_id = $statusMsg = '';
$status = 'error';
$redirectURL = 'leads_disp.php';
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$PAYMENT_STR = '';
$PAYMENT_STR = json_encode($_POST);
SendInBlueMail("Payment Ping", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');

if ($RESPONSE == '00' && $TRAN_TYPE == 'CCE8') {
    sql_query("update transaction2 set payment_id='$TRANS_REF',payment_status='A' where id='$TRANS_ID' ");
    $status = 'success';
    $statusMsg = 'Your Payment has been Successful!';
    //$_SESSION[PROJ_SESSION_ID]->success_info = $statusMsg;
    //   DFA($_POST);
    //   exit;
    header('location:' . $success_url);
    exit;
} elseif ($RESPONSE == '00') {
    $PAYMENT_STR = json_encode($_POST);
    // $_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$TRANS_ID'  ";
    // $_r1 = sql_query($_q1, "");
    // list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
    $_q2 = "update platinum_fee set payment_id='$TRANS_REF',payment_status='S' where id='$TRANS_ID' ";
    sql_query($_q2, "");

    $SP_ID = GetXFromYID("select pid from platinum_fee where id='$TRANS_ID'");
    if (!empty($SP_ID)) sql_query("update service_providers set cRFeePaid='Y' where id='$SP_ID'");

    // $updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$TRANS_ID' "; //update dat table
    // sql_query($updatebookingdat);

    // $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$TRANS_AMT','$TRANS_REF')";
    // sql_query($_q3, "");
    // $_q4 = "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
    // sql_query($_q4, "");
    // $_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
    // sql_query($_q5, "");

    // $_q6 = "select iCustomerID,dDateTime,iAppTimeID from appointments where iApptID='$iApptID' ";
    // $_q6r = sql_query($_q6, "");
    // list($CUSTID, $DATEB, $TIMEID) = sql_fetch_row($_q6r);

    // $Customer_name = GetXFromYID("select  CONCAT(vFirstname, ' ', vLastname) as full_name from customers where iCustomerID='$CUSTID' ");
    // $ADATE = date('m-d-Y', strtotime($DATEB));
    // $ATIME = $TIMEPICKER_ARR[$TIMEID];

    // //send mail alert to customers
    // $email = GetXFromYID("select vEmail from customers where iCustomerID='$CUSTID' ");
    // $company_name = GetXFromYID("select company_name from service_providers where id='$pid' ");
    // $Cleaners_name = GetXFromYID("select  CONCAT(First_name, ' ', Last_name) as full_name from service_providers where id='$pid' ");
    // $SP_EMAIL = GetXFromYID("select  email_address  from service_providers where id='$pid' ");
    // $to = db_output2($email);
    // $subject = "Appointment Update";
    // // Always set content-type when sending HTML email
    // $headers = "MIME-Version: 1.0" . "\r\n";
    // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // // More headers
    // $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
    // $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
    // $mail_content = '';
    // $mail_content .= "<html>";
    // $mail_content .= "<body>";
    // $mail_content .= "<p>Hello $Customer_name,</p>";
    // $mail_content .= "<p>Great news! </p>";
    // $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
    // $mail_content .= "<p>$company_name, will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links $Cleaners_name that you may want to view before your scheduled meeting.</p>";

    // $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid=' . $pid . '">Click here to see the cleaners profile</a></p>';
    // //$mail_content .= '<ol type="1">';
    // $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
    // $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
    // $mail_content .= "<p>Happy bid collecting!<br>The Quote Master's Team</p>";
    // $mail_content .= "</body>";
    // $mail_content .= "</html>";
    //mail($to, $subject, $mail_content, $headers);
    //Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
    //Send_mail('', '','darshankubal1@gmail.com', '', '', '', "Payment Ping", $PAYMENT_STR, '');
    //SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com');
    SendInBlueMail("Payment Ping SUCCESS", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');
    //sql_query("update customers set cMailsent='Y' where vEmail='$Email' ", "update email status");   

    //MODIFIED TO SENT ALERT TO SP REGARDING LEAD PURCHASED
    //$MAIL_BODY = GET_LEAD_MAIL_CONTENT($iApptID, $Cleaners_name);
    //SendInBlueMail("Lead Purchase Success", $SP_EMAIL, $MAIL_BODY, '', '', '', "darshankubal1@gmail.com,michael2@thequotemasters.com");


    $status = 'success';
    $statusMsg = 'Your Payment has been Successful!';
    //$_SESSION[PROJ_SESSION_ID]->success_info = $statusMsg;
    header('location:' . $success_url);
    exit;
} else {
    $statusMsg = 'Your Payment has failed!';
    ///$_SESSION[PROJ_SESSION_ID]->error_info = $statusMsg;
    header('location:' . $failure_url);
    exit;
}


// Array
// (
//     [MSG_VERSION] => 003
//     [CUST_NBR] => 9001
//     [MERCH_NBR] => 900300
//     [DBA_NBR] => 2
//     [TERMINAL_NBR] => 21
//     [TRAN_TYPE] => CCE1
//     [BATCH_ID] => 5102589354
//     [TRAN_NBR] => 90
//     [LOCAL_DATE] => 101823
//     [LOCAL_TIME] => 154710
//     [AUTH_GUID] => 0A1KTUAPW9QQKUZHEB8
//     [AUTH_RESP] => 00
//     [AUTH_CODE] => 042143
//     [AUTH_CVV2] => M
//     [AUTH_RESP_TEXT] => APPROVAL 042143
//     [AUTH_CARD_TYPE] => V
//     [AUTH_TRAN_DATE_GMT] => 10/18/2023 07:47:09 PM
//     [AUTH_AMOUNT_REQUESTED] => 10.00
//     [AUTH_AMOUNT] => 10.00
//     [AUTH_CURRENCY_CODE] => 840
//     [NETWORK_RESPONSE] => 00
//     [AUTH_CARD_COUNTRY_CODE] => 840
//     [AUTH_CARD_CURRENCY_CODE] => 840
//     [AUTH_CARD_B] => D
//     [AUTH_CARD_C] => F
//     [AUTH_CARD_E] => N
//     [AUTH_CARD_F] => Y
//     [AUTH_CARD_G] => N
//     [AUTH_CARD_I] => Y
//     [AUTH_MASKED_ACCOUNT_NBR] => ************1111
//     [ORIG_TRAN_TYPE] => CCE1
//     [AUTH_TRAN_IDENT] => 353291712302720
//     [AUTH_PAR] => V41111111114589CED5703F989F79
// )
