<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
$mail_content2='';
include 'phpmailer.php';
require_once('includes/common.php');
$MASTER_OTP = '12345';
if (isset($_POST['bid'], $_POST['otp'])) {

    $bid = db_input2($_POST['bid']);
    $otp = db_input2($_POST['otp']);
    if ($otp==$MASTER_OTP)
     {

        $CUST_ID = GetXFromYID("select iCustomerID from booking WHERE iBookingID='$bid' ");
        $Email = GetXFromYID("select vEmail from customers where iCustomerID='$CUST_ID' ");
        //list($iOTPID, $vOTP, $Email) = sql_fetch_row($adm_o_q_res);
        $upadte_b_q = "UPDATE booking SET bverified='1' WHERE iBookingID='$bid'";
        sql_query($upadte_b_q, "update_booking_table");
        $to = $Email;
        $subject = "Welcome Email";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com <ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content2 .= "<html>";
        $mail_content2 .= "<body>";
        $mail_content2 .= "<p>Hi,</p>";
        $mail_content2 .= "<p>WELCOME to the QUOTE MASTERS family where YOU are the MASTER of the leads you receive!  With Quote Master you will receive an amazing source of janitorial leads! </p>";

        $mail_content2 .= "<p>You receive the following information with each lead:  </p>";
        $mail_content2 .= "<ol>
                            <li>A verified appointment day and time for your meeting</li>
                            <li>Years of expertise</li>
                            <li>Current star rating</li>
                            <li>Name of the manager you will be meeting with</li>
                            <li>Day and time you selected to meet</li>
                           
                            </ol>  ";
        $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
        $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
        $mail_content2 .= "<p>Questions? Need help? Please</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content2 .= "<p>Quote Master</p>";
        $mail_content2 .= "</body>";
        $mail_content2 .= "</html>";
        $_q1 = "select vEmail from customers where vEmail='$Email' and cMailsent='N' ";
        $_r1 = sql_query($_q1, "check_mail_sent");
        if (sql_num_rows($_r1)) {
            //mail($to, $subject, $mail_content2, $headers);
            //Send_mail('', '', $to, '',"", "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $mail_content2, '');
            SendInBlueMail($subject,$to,$mail_content2, '', '','', "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com");
            sql_query("update customers set cMailsent='Y' where vEmail='$Email' ", "UPDATE_EMAIL_STATUS");
        }

        echo 1;
        exit;
        
    }else
    {
        $adm_o_q = "SELECT iOTPID,vOTP,vEmail FROM adm_otp WHERE  NOW()<dtTo AND iBid='$bid' AND vOTP='$otp' AND cUsed='A' ";
        $adm_o_q_res = sql_query($adm_o_q, "");
    
        if (sql_num_rows($adm_o_q_res)) 
        {
            list($iOTPID, $vOTP, $Email) = sql_fetch_row($adm_o_q_res);
            $upadte_b_q = "UPDATE booking SET bverified='1' WHERE iBookingID='$bid'";
            sql_query($upadte_b_q, "");
            $update_otp_q = "UPDATE adm_otp SET cUsed='X' WHERE iOTPID='$iOTPID' ";
            sql_query($update_otp_q, "");
            $to = $Email;
            $subject = "Welcome Email";
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
            // More headers
            $headers .= 'From: thequotemasters.com <ops@janitorialquotemasters.com>' . "\r\n";
            $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
            $mail_content2 .= "<html>";
            $mail_content2 .= "<body>";
            $mail_content2 .= "<p>Hi,</p>";
            $mail_content2 .= "<p>WELCOME to the QUOTE MASTERS family where YOU are the MASTER of the leads you receive!  With Quote Master you will receive an amazing source of janitorial leads! </p>";
    
            $mail_content2 .= "<p>You receive the following information with each lead:  </p>";
            $mail_content2 .= "<ol>
                                <li>A verified appointment day and time for your meeting</li>
                                <li>Years of expertise</li>
                                <li>Current star rating</li>
                                <li>Name of the manager you will be meeting with</li>
                                <li>Day and time you selected to meet</li>
                               
                                </ol>  ";
            $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
            $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
            $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
            $mail_content2 .= "<p>Questions? Need help? Please</p>";
            $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
            $mail_content2 .= "<p>Quote Master</p>";
            $mail_content2 .= "</body>";
            $mail_content2 .= "</html>";
            $_q1= "select vEmail from customers where vEmail='$Email' and cMailsent='N' ";
            $_r1=sql_query($_q1,"check mail sent");
            if (sql_num_rows($_r1)) {
                //mail($to, $subject, $mail_content2, $headers);
                //Send_mail('', '', $to, '', "", "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $mail_content2, '');
                SendInBlueMail($subject, $to, $mail_content2, '', '','', "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com");
                sql_query("update customers set cMailsent='Y' where vEmail='$Email' ","update email status");         
            }
    
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }

    }
}
// ALTER TABLE `quote_master`.`customers`
//   ADD COLUMN `cMailsent` varchar(255) NULL DEFAULT NULL AFTER `dtRegistration`;

?>