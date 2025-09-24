<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$spid = (isset($_POST['spid'])) ? db_input2($_POST['spid']) : '';
$bid = (isset($_POST['bid'])) ? db_input2($_POST['bid']) : '';
$ch = (isset($_POST['ch'])) ? db_input2($_POST['ch']) : '';
$returnArr = array();
$data = array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $SPID = DecodeParam($spid);
    $BID = DecodeParam($bid);
    if ($ch == 'N') {
        $_q = "SELECT  * FROM unsubscribe_leads WHERE 1 and  iSPID='$SPID' and  iBookingID='$BID' and cStatus!='X' ";
        $_r = sql_query($_q);
        if (sql_num_rows($_r)) {
            $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "thanks for your feedback.", "data" => '');
            header('Content-Type: application/json');
            echo json_encode($returnArr);
            exit;

        }else{
            LockTable('unsubscribe_leads');
            $UsubID = NextID('iUsubID', 'unsubscribe_leads');
            $_q2 = "INSERT INTO unsubscribe_leads(iUsubID, iSPID, iBookingID, dDate, cStatus) VALUES ('$UsubID','$SPID','$BID',NOW(),'A')";
            sql_query($_q2);
            UnlockTable();
            $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "thanks for your feedback.", "data" => '');
            header('Content-Type: application/json');
            echo json_encode($returnArr);
            exit;

        }
    }
    
    $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "thanks for your feedback.", "data" =>'');
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
}
?>