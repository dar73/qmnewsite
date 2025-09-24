<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';


$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$state = (isset($_POST['state'])) ? db_input2($_POST['state']) : '0'; //d7c9918c7c21e17qm@12340f7b6543f1
$county = (isset($_POST['county'])) ? db_input2($_POST['county']) : '0'; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    //$q = "SELECT DISTINCT County_name,County_name FROM areas WHERE state='$state' order by County_name";
    $STATE_ARR = GetXArrFromYID("SELECT DISTINCT city,city FROM areas WHERE state='$state' AND County_name='$county' order by city ", '3');
    $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "success!", "data" => $STATE_ARR);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}
?>
