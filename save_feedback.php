<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
// DFA($_POST);
// exit;
// Array
// (
//     [meeting_status] => 1
//     [no_reason] => 4
// )
$meeting_status=(isset($_POST['meeting_status']))? $_POST['meeting_status']:'';
$appid=(isset($_POST['appid']))? $_POST['appid']:'';
$no_reason=(isset($_POST['no_reason']))? $_POST['no_reason']:'';
LockTable('feedback');
$feedbackid=NextID('Id', 'feedback');
sql_query("insert into feedback values($feedbackid,$appid,NOW(),'A')");
UnlockTable();
LockTable('feedback_response');
$FD_responseid=NextID('Id', 'feedback_response');
sql_query("insert into feedback_response values('$FD_responseid','$feedbackid','$appid','1','$meeting_status','$no_reason','A')");
UnlockTable();
header('location:feedback_success.php');
exit;
// CREATE TABLE `feedback` (
//   `Id` int(11) NOT NULL AUTO_INCREMENT,
//   `iApptID` int(11) DEFAULT NULL,
//   `dt_Ans` datetime DEFAULT NULL,
//   `cStatus` char(2) DEFAULT NULL,
//   PRIMARY KEY (`Id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
// CREATE TABLE `feedback_response` (
//   `Id` int(11) NOT NULL AUTO_INCREMENT,
//   `iApptID` int(11) DEFAULT NULL,
//   `iQuestionID` int(11) DEFAULT NULL,
//   `meeting_status` int(11) DEFAULT NULL,
//   `no_reason` varchar(255) DEFAULT NULL,
//   `cStatus` char(2) DEFAULT NULL,
//   PRIMARY KEY (`Id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

?>