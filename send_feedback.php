<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
include 'phpmailer.php';
$output=array();
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array
if (isset($_POST['appid'])) {
    $appid=db_input2($_POST['appid']);
    $APP_DATA=GetDataFromID('appointments', 'iApptID',$appid,'');
    $BUYED_LEADS=GetDataFromID('buyed_leads', 'iApptID',$appid,'');
    $SP_ID=$BUYED_LEADS[0]->ivendor_id;
    $SP_DATA=GetDataFromID('service_providers', 'id',$SP_ID,'');
    $customerID=GetXFromYID("select iCustomerID from appointments where iApptID='$appid' ");
    $CUSTOMER_DATA=GetDataFromID('customers', 'iCustomerID',$customerID,'');
    $is_feedback=GetXFromYID("select count(*) from feedback where iApptID='$appid' and cStatus='A' ");
    $mail_content2='';
    if ($is_feedback==0) {
        $to = $CUSTOMER_DATA[0]->vEmail;
        $subject = "Feedback Email";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content2 .= "<html>";
        $mail_content2 .= "<body>";
        $mail_content2 .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">';
        $mail_content2 .= "<p>Hi,".$CUSTOMER_DATA[0]->vFirstname.' '.$CUSTOMER_DATA[0]->vLastname."</p>";
        $mail_content2 .= "<p>Hello from QM! We hope you are having a great day!</p>";
        
        $mail_content2 .= "<p>We wanted to do a quick survey to see if you were able to meet with ".$SP_DATA[0]->company_name." at ".$TIMEPICKER_ARR[$APP_DATA[0]->iAppTimeID]." ?  </p>";
        $mail_content2 .= "<p><a href='https://thequotemasters.com/feedback.php?appid=$appid'>Please click on the link to submit the feedback </a></p>";
        $mail_content2 .= "<p>Questions? Need help? Please</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content2 .= "<p>Quote Master</p>";
        $mail_content2 .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
        $mail_content2 .= "</body>";
        $mail_content2 .= "</html>";
       // mail($to, $subject, $mail_content2, $headers);
       Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content2, '');

        $output['error'] = false;
        $output['message'] = 'feedback email  sent';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit;
        
    }elseif ($is_feedback>0) {
        $output['error']=true;
        $output['message']='feedback email already sent';
        header('Content-Type: application/json');
        echo json_encode($output);
        exit;
    }
}
?>