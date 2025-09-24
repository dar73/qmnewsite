<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
include 'phpmailer.php';
if (isset($_POST['bid'], $_POST['otp'])) {
    $bid = $_POST['bid'];
    $otp = $_POST['otp'];
    $adm_o_q = "SELECT iOTPID,vOTP,vEmail FROM adm_otp WHERE  NOW()<dtTo AND iBid='$bid' AND vOTP='$otp' AND cUsed='A' ";
    $adm_o_q_res = sql_query($adm_o_q, "");

    if (sql_num_rows($adm_o_q_res)) {
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
        $mail_content2 = "<html>";
        $mail_content2 .= "<body>";
        $mail_content2 .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;"><p>Hi,</p>';
        $mail_content2 .= "<p>Welcome to Quote Masters where YOU are the MASTER of the Janitorial Quotes you get!</p>";
        $mail_content2 .= "<p>You have completed the request form and now you will receive  bids for your janitorial needs. Each cleaning company that will visit your office has been selected because they have some of the highest ratings on Google / Yelp. </p>";
        $mail_content2 .= "<p>Shortly once our Customer Service Team selects your cleaners you will receive the Quote Masters Report for each of them.</p>";
        $mail_content2 .= "<p>You will know the following:</p>";
        $mail_content2 .= "<ol>
                            <li>Company name and contact information</li>
                            <li>Years of expertise</li>
                            <li>Current star rating</li>
                            <li>Name of the manager you will be meeting with</li>
                            <li>Day and time you selected to meet</li>
                           
                            </ol>  ";
        $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
        $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit thequotemasters.com to connect </a></p>';
        $mail_content2 .= "<p>Questions? Need help? Please</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
        $mail_content2 .= "<p>Quote Masters</p>";
        $mail_content2 .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
        $mail_content2 .= "</body>";
        $mail_content2 .= "</html>";
        $_q1= "select vEmail from customers where vEmail='$Email' and cMailsent='N' ";
        $_r1=sql_query($_q1,"check mail sent");
        if (sql_num_rows($_r1)) {
            //mail($to, $subject, $mail_content2, $headers);
            Send_mail('', '', $to, '', '', '', $subject, $mail_content2, '');
            sql_query("update customers set cMailsent='Y' where vEmail='$Email' ","update email status");         
        }

        echo 1;
    } else {
        echo 0;
    }
}
// ALTER TABLE `quote_master`.`customers`
//   ADD COLUMN `cMailsent` varchar(255) NULL DEFAULT NULL AFTER `dtRegistration`;

?>