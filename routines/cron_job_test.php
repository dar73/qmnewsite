<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';


sql_close();
//Send_mail('', '', 'darshankubal1@gmail.com', '', '', 'darshankubal1@gmail.com', "Test Cron Job".NOW, "Test the cron job TIME is  ".date('Y-m-d h:i:s A',strtotime(NOW)), '');

?>