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
$APPID = $_POST['APPID'];
$SPID = $_POST['txtspID'];
$title = db_input2($_POST['title']);
$desc = db_input2($_POST['description']);
$location = db_input2($_POST['location']);

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");


$SP_DATA = GetDataFromCOND("service_providers", " and id='$SPID' ");
$SP_FIRST_NAME = db_input2($SP_DATA[0]->First_name);
$SP_LAST_NAME = db_input2($SP_DATA[0]->Last_name);
$SP_ADDRESS = db_input2($SP_DATA[0]->street);
$SP_CITY = db_input2($SP_DATA[0]->city);
$SP_STATE = db_input2($SP_DATA[0]->state);

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
$AMOUNT = 0;
   
//}

// echo $AMOUNT;
// exit;

//$AMOUNT = 1;

$CFEE = $AMOUNT * 0.03;
$DEBIT_AMT = $AMOUNT + $CFEE;
$DEBIT_AMT = number_format($DEBIT_AMT, 2);

//check if already sold
$_q1 = "select * from appointments  where cService_status='O' and cStatus='A' and iApptID='$APPID' ";
$_r1 = sql_query($_q1);
// echo sql_num_rows($_r1);
// exit;
if (sql_num_rows($_r1)) {
    echo "0~Appointment slot has been sold already.";
    //header("location: $disp_url");
    exit;
}

if (empty($access_token) && empty($refresh_token)) {
    //$access_token = $access_token;
    echo "0~Calendar Approval not done.";
    //header("location: $disp_url");
    exit;
}

$ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);

if (empty($ACCESS['data'])) {
    echo "0~Refresh Token Expired please request new one.";
    //header("location: $disp_url");
    exit;
}
$access_token = $ACCESS['data']['access_token'];


$AUTH_GUID = GetXFromYID("select payment_id from transaction2 where pid='$SPID' and payment_status='A' order by id desc limit 1 ");
if (empty($AUTH_GUID)) {
    echo  "0~SP's AUTH_GUID DOES NOT EXIST CANNOT PROCEED WITH DEBIT .";
    //header("location: $disp_url");
    exit;
}

//STEP 1 STARTS 
sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE");

$ID = NextID('id', 'transaction');
$_q = "insert into transaction values ('$ID','$BID','$APPID','$SPID','','','$DEBIT_AMT','" . NOW . "','online','P')";
$_r = sql_query($_q, "");

UnlockTable();


SendInBlueMail("Payment Ping FROM CURL/Credit", 'darshankubal1@gmail.com', 'Credit Lead ', '', 'michael2@thequotemasters.com', '', 'michael2@thequotemasters.com');

if (true) {
    // echo 'hiii';
    // exit;
    sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE,buyed_leads_dat WRITE,booking WRITE,customers WRITE,service_providers WRITE");
    $TRANS_ID = $ID;//$RESPONSE_ARR['7']['TRAN_NBR'];
    $TRANS_REF = 'Credit100';//$RESPONSE_ARR['10']['AUTH_GUID'];

    $_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$TRANS_ID'  ";
    $_r1 = sql_query($_q1, "");
    list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
    $_q2 = "update transaction set payment_id='$TRANS_REF',payment_status='S' where id='$TRANS_ID' ";
    sql_query($_q2, "");

    $updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$TRANS_ID' "; //update dat table
    sql_query($updatebookingdat);

    $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$DEBIT_AMT','$TRANS_REF')";
    sql_query($_q3, "");
    $_q4 = "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
    sql_query($_q4, "");
    $_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
    sql_query($_q5, "");

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
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
    $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
    $mail_content = '';
    $mail_content .= "<html>";
    $mail_content .= "<body>";
    $mail_content .= "<p>Hello $Customer_name,</p>";
    $mail_content .= "<p>Great news! </p>";
    $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
    $mail_content .= "<p>$company_name, will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links $Cleaners_name that you may want to view before your scheduled meeting.</p>";

    $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid=' . $pid . '">Click here to see the cleaners profile</a></p>';
    //$mail_content .= '<ol type="1">';
    $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
    $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
    $mail_content .= "<p>Happy bid collecting!<br>The Quote Master's Team</p>";
    $mail_content .= "</body>";
    $mail_content .= "</html>";
    //mail($to, $subject, $mail_content, $headers);
    //Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
    //Send_mail('', '','darshankubal1@gmail.com', '', '', '', "Payment Ping", $PAYMENT_STR, '');
    //SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com');
    //SendInBlueMail("Payment Ping", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');


    //MODIFIED TO SENT ALERT TO SP REGARDING LEAD PURCHASED
    $MAIL_BODY = GET_LEAD_MAIL_CONTENT($APPID, $Cleaners_name);
    SendInBlueMail("Lead Purchase Success", $SP_EMAIL, $MAIL_BODY, '', '', '', "darshankubal1@gmail.com,michael2@thequotemasters.com");

    //STEP 2 START

    $q = "SELECT date_format(A.dDateTime,'%Y-%m-%d'),time_format(T.time,'%H:%i') FROM appointments A INNER JOIN apptime T ON T.Id = A.iAppTimeID where A.cService_status='P' and A.cStatus='A' and A.iApptID='$APPID'  ";
    $r = sql_query($q);
    if (sql_num_rows($r)) {
        list($date, $start_time) = sql_fetch_row($r);
        $end_time = date("H:i", strtotime("$start_time +30 minutes"));
    }

    $calendar_event = array(
        'summary' => $title,
        'location' => $location,
        'description' => $desc
    );

    $event_datetime = array(
        'event_date' => $date,
        'start_time' => $start_time,
        'end_time' => $end_time
    );


    // Get the user's calendar timezone 
    $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
    //list($timezone,$msg) = $user_timezone;
    if ($user_timezone['msg'] == 'fail') {
        $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
        $access_token = $ACCESS_RES['data']['access_token'];
        sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
    }
    $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
    $timezone = $user_timezone['data'];
    //$data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');

    //DFA($data);

    // Create an event on the primary calendar 
    $EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);

    if ($EVENT_RESPONSE['msg'] == 'token_expire') {
        $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
        $access_token = $ACCESS_RES['data']['access_token'];
        sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
        $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
        $timezone = $user_timezone['data'];
        $EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);
    }

    //   DFA($EVENT_RESPONSE);
    //   exit;


    echo "1~Event Details Successfully Inserted";
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
