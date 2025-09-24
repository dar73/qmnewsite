<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";


$activity_txt = "Started the process of setting up the Google calendar ,clicked on the button";
AddSPActivity($sess_user_id, 3, $ACTIVITY_TIMELINE_ARR[3], "app_sp", $activity_txt, "U");


header("Location: $googleOauthURL");
exit;

?>

