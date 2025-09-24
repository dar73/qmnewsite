<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';


$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();

if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $returnArr = array("ResponseCode" => "200","ResponseMsg" => "login success!", "data" => $INDUSTRY_EXC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
} else {
    $returnArr = array("ResponseCode" => "401","ResponseMsg" => "Invalid token!!");
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
}
?>