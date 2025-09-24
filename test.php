<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include 'includes/common_front.php';


SendInBlueMail("Appointment cancelled", 'darshankubal1@gmail.com',"TEST MAIL", '', '', '', "");



echo $_SERVER['DOCUMENT_ROOT'];
exit;

?>