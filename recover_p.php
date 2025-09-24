<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
include 'phpmailer.php';
$type=$_POST['type'];
$email=db_input($_POST['email']);

if ($type=='V') {
    $q= "SELECT id FROM service_providers WHERE email_address='$email' and cStatus!='X' LIMIT 1";
    
}elseif ($type=='C') {
    $q= "SELECT iCustomerID FROM customers WHERE vEmail='$email' and cStatus!='X' LIMIT 1";
}
$r=sql_query($q,"");
list($ID)=sql_fetch_row($r);
$d=EncryptStr($ID);
if (empty($ID)) {
    if ($type == 'V') {
        header('location:forgot_password_p.php?err=1');
        exit;
    } elseif ($type == 'C') {
        header('location:forgot_password_c.php?err=1');
        exit;
    }
}


$str1 = $mail_content = '';
$to = $email;
$subject = "Reset Password";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From:Quote Masters <ops@janitorialquotemasters.com>' . "\r\n";
$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
$mail_content .= "<html>";
$mail_content .= "<body>";
$mail_content .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">';

$mail_content .= "<p>Hi,</p>";
$mail_content .= "<p>A password reset for your account was requested.</p>";
$mail_content .= "<p>Please click the button below to change your password.Note that this link is valid for 24 hours. After the time limit has expired, you will have to resubmit the request for a password reset.
</p>";
$mail_content .= '<p><a href="https://thequotemasters.com/change-password-p.php?Q=' . $d . '&utype=' . $type .'">Change Your Password</a></p>';
$mail_content .= "<p>Questions? Need help? Please</p>";
$mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
$mail_content .= "<p>At your service<br>Quote Masters</p>";
$mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
$mail_content .= "</body>";
$mail_content .= "</html>";
//mail($to, $subject, $mail_content, $headers);

///Send_mail('', '', $to, '', '', '', $subject, $mail_content, '');
SendInBlueMail($subject, $to,$mail_content, '', '', '');

//header('location:change-password-p.php?Q=' . $d.'&utype='.$type);
if ($type == 'V') {
    header('location:forgot_password_p.php?err=2');
    exit;
} elseif ($type == 'C') {
    header('location:forgot_password_c.php?err=2');
    exit;
}

?>