<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';
$to = 'darshankubal1@gmail.com';
$subject = "Welcome Email";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
$mail_content2 = "<html>";
$mail_content2 .= "<body>";
$mail_content2 .= "<p>Hi,</p>";
$mail_content2 .= "<p>Welcome to Quote Masters where “YOU are the MASTER of the Janitorial Quotes you get!”</p>";
$mail_content2 .= "<p>You have completed the request form and now you will receive  bids for your janitorial needs. Each cleaning company that will visit your office has been selected because they have some of the highest ratings on Google / Yelp. </p>";
$mail_content2 .= "<p>Shortly once our Customer Service Team selects your cleaners you will receive the Quote Master Report for each of them.</p>";
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
$mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
$mail_content2 .= "<p>Questions? Need help? Please</p>";
$mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
$mail_content2 .= "<p>Quote Master</p>";
$mail_content2 .= "</body>";
$mail_content2 .= "</html>";
$MAIL_SUBJECT = 'Lead Alert';
$MAIL_BODY = '';
$MAIL_BODY = file_get_contents(SITE_ADDRESS . 'api/email_template_leads.php');
$MAIL_BODY = str_replace('<PNAME>', 'Laxman ', $MAIL_BODY);
// $MAIL_BODY = str_replace('<EMAIL>', $email, $MAIL_BODY);
//mail($to, $subject, $mail_content2, $headers);
Send_mail('', '', "darshankubal1@gmail.com", '', "", "", $MAIL_SUBJECT, $MAIL_BODY, '');
?>