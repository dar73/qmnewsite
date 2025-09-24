<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';

$output = array();
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array

$SP_DATA = GetXArrFromYID("select id,company_name from service_providers where 1","3");

$CUSTOMER_ARR = array();
$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
}

$APPOINTMENTS_ARR = array();
$APP_Q = "SELECT iApptID,dDateTime,iAppTimeID  FROM appointments WHERE 1";
$APP_R = sql_query($APP_Q,"");
if(sql_num_rows($APP_R)){
while (list($iApptID,$dDateTime,$iAppTimeID)=sql_fetch_row($APP_R)) {
        if (!isset($APPOINTMENTS_ARR[$iApptID]))
            $APPOINTMENTS_ARR[$iApptID] = array('iApptID'=> $iApptID, 'dDateTime'=>$dDateTime, 'iAppTimeID'=> $iAppTimeID);

}
}

// DFA($APPOINTMENTS_ARR);
// exit;

$PUSHED_IDS = GetIDString2('select iApptID from buyed_leads ');
if (empty($PUSHED_IDS) || $PUSHED_IDS == '-1')
    $PUSHED_IDS = '0';

$RESPONSE_IDS = GetIDString2("select iApptID from feedback_log_mails_sent ");
if (empty($RESPONSE_IDS) || $RESPONSE_IDS == '-1')
    $RESPONSE_IDS = '0';


$subject = "Feedback Email";
$_q1 = "SELECT AP.iCustomerID,BL.ivendor_id,BL.iApptID FROM appointments as AP inner join buyed_leads as BL on AP.iApptID=BL.iApptID  WHERE 1 and AP.iApptID  in (" . $PUSHED_IDS . ") and date_format(AP.dDateTime,'%Y-%m-%d') < '".YESTERDAY."' and AP.iApptID not  in (" . $RESPONSE_IDS . ") ";
// echo $_q1;
// Send_mail('', '', 'darshankubal1@gmail.com', '', '', 'darshankubal1@gmail.com', $subject, $_q1, '');
// exit;
$_r1 = sql_query($_q1, "GET_FEEDBACK_TO_SEND_DATA");
if(sql_num_rows($_r1))
{
while(list($iCustomerID,$ivendor_id,$iApptID)=sql_fetch_row($_r1))
{
    $to = $CUSTOMER_ARR[$iCustomerID]['vEmail'];
    $subject = "Feedback Email";
    $mail_content2 = "<html>";
    $mail_content2 .= "<body>";
    $mail_content2 .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">';
    $mail_content2 .= "<p>Hi," . $CUSTOMER_ARR[$iCustomerID]['vFirstname'] . ' ' . $CUSTOMER_ARR[$iCustomerID]['vLastname'] . "</p>";
    $mail_content2 .= "<p>Hello from QM! We hope you are having a great day!</p>";

    $mail_content2 .= "<p>We wanted to do a quick survey to see if you were able to meet with " . $SP_DATA[$ivendor_id] . " on ".date('m/d/Y',strtotime($APPOINTMENTS_ARR[$iApptID]['dDateTime']))." at  " . $TIMEPICKER_ARR[$APPOINTMENTS_ARR[$iApptID]['iAppTimeID']] . " ?  </p>";
    $mail_content2 .= "<p><a href='https://thequotemasters.com/feedback.php?appid=$iApptID'>Please click on the link to submit the feedback </a></p>";
    $mail_content2 .= "<p>Questions? Need help? Please</p>";
    $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
    $mail_content2 .= "<p>Quote Master</p>";
    $mail_content2 .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
    $mail_content2 .= "</body>";
    $mail_content2 .= "</html>";
    //echo $mail_content2;
    // mail($to, $subject, $mail_content2, $headers);
    sql_query("INSERT INTO feedback_log_mails_sent(iApptID, cStatus, dtDateTime) VALUES ('$iApptID','A',NOW())");
    SendInBlueMail($subject,$to, $mail_content2, '', '','', '');
    //Send_mail('', '', "$to", '', '', 'darshankubal1@gmail.com', $subject, $mail_content2, '');

}
}
// if(sql_num_rows($_r1))
// {
// while (list()=sql_fetch_row($_r1)) {

// }
// }

sql_close();
?>