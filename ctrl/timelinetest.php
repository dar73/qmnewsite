<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php"; 

$activity_txt = "Test activity for timeline";
AddSPActivity($sess_user_id, 1, $ACTIVITY_TIMELINE_ARR[1], "app_sp", $activity_txt, "U");