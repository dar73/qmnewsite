<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';

$DATE2 = date('Y-m-d');

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array

$SP_DATA = GetXArrFromYID("select id,company_name from service_providers where 1", "3");

$CUSTOMER_ARR = $SP_DETAILS= array();
$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
}



$SP_DATAQ = "select * from service_providers where 1 and cAdmin_approval='A' and cStatus='A' ";
$SP_DATAQR = sql_query($SP_DATAQ);
if (sql_num_rows($SP_DATAQR)) {
    while ($row = sql_fetch_assoc($SP_DATAQR)) {
        if (!isset($SP_DETAILS[$row['id']])) {
            $SP_DETAILS[$row['id']] = $row;
        }
    }
}


$APPOINTMENTS_ARR = array();
$APP_Q = "SELECT iApptID,dDateTime,iAppTimeID  FROM appointments WHERE 1  ";
$APP_R = sql_query($APP_Q, "");
if (sql_num_rows($APP_R)) {
    while (list($iApptID, $dDateTime, $iAppTimeID) = sql_fetch_row($APP_R)) {
        if (!isset($APPOINTMENTS_ARR[$iApptID]))
            $APPOINTMENTS_ARR[$iApptID] = array('iApptID' => $iApptID, 'dDateTime' => $dDateTime, 'iAppTimeID' => $iAppTimeID);
    }
}


$PUSHED_IDS = GetIDString2("select iApptID from appt_alert_mails_sent where date_format(dDate,'%Y-%m-%d')='$DATE2'");
if (empty($PUSHED_IDS) || $PUSHED_IDS == '-1')
      $PUSHED_IDS = '0';

$_q3 = "SELECT AP.iCustomerID,BL.ivendor_id,BL.iApptID FROM appointments as AP inner join buyed_leads as BL on AP.iApptID=BL.iApptID  WHERE 1 and AP.iApptID  in (select iApptID from buyed_leads) and AP.iApptID not in (" . $PUSHED_IDS . ") and AP.cStatus='A' and date_format(AP.dDateTime,'%Y-%m-%d') > '" . YESTERDAY . "' ";
$_r3 = sql_query($_q3);
if(sql_num_rows($_r3))
{
    while(list($CUST_ID,$VENDOR_ID,$APP_ID)=sql_fetch_row($_r3))
    {
        // if(isset($APPOINTMENTS_ARR[$APP_ID]))
        // {
        //     continue;
        // }
        $to = $CUSTOMER_ARR[$CUST_ID]['vEmail'];
        $subject = "Appointment Update";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $ADATE = date('m-d-Y', strtotime($APPOINTMENTS_ARR[$APP_ID]['dDateTime']));
        $ATIME = $TIMEPICKER_ARR[$APPOINTMENTS_ARR[$APP_ID]['iAppTimeID']];
        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content = '';
        $mail_content .= "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Hello ".$CUSTOMER_ARR[$CUST_ID]['vFirstname'].",</p>";
        $mail_content .= "<p>Great news! </p>";
        $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
        $mail_content .= "<p>".$SP_DETAILS[$VENDOR_ID]['company_name'].", will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links ". $SP_DETAILS[$VENDOR_ID]['First_name']. " ". $SP_DETAILS[$VENDOR_ID]['Last_name']." that you may want to view before your scheduled meeting.</p>";

        $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid=' . $VENDOR_ID . '">Click here to see the cleaners profile</a></p>';
        //$mail_content .= '<ol type="1">';
        $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
        $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content .= "<p>Happy bid collecting!<br>The Quote Master's Team</p>";
        $mail_content .= "</body>";
        $mail_content .= "</html>";
        //echo $mail_content;
        //mail($to, $subject, $mail_content, $headers);
        if(!is_null($to))
        {
            sql_query("insert into appt_alert_mails_sent values('$CUST_ID','".NOW."','$APP_ID','A') ");
            SendInBlueMail($subject, $to, $mail_content, '', '','', '');
            //Send_mail('', '', "$to", '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
        }
    }

}
sql_close();

?>