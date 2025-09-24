<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';
sql_close();
exit;
$mail_content = '';
$mail_content .= "<html>";
$mail_content .= "<body>";
$mail_content .= "<p>Hello QM Team,</p><br>";
$mail_content .= "<p>This is the test message to ensure that ops@thequotemasters.com is working fine.</p>";
$mail_content .= "</body>";
$mail_content .= "</html>";
$subject = 'Test message to check the mail';

if(Send_mail('', '', "ops@thequotemasters.com", '', '', 'darshankubal1@gmail.com', $subject, $mail_content, ''))
{
    echo 'success';

}else{
    echo 'Failure';
}



?>