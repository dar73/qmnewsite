<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
require_once('includes/ti-salt.php');
include 'phpmailer.php';

$MAILS_ARR=['dariusp21@gmail.com', 'info@superiorcleaningindustries.com'];

$MAIL_BODY = file_get_contents(SITE_ADDRESS . 'platinumupgrademail.php');
foreach ($MAILS_ARR as $to) {
   echo SendInBlueMail("Platinum Upgrade", $to, $MAIL_BODY, '', '', '', "");
}
//SendInBlueMail("Platinum Upgrade", 'darshankubal1@gmail.com', $MAIL_BODY, '', '', '', "");