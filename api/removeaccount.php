<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$spid = (isset($_POST['spid'])) ? db_input2($_POST['spid']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();

if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    //$STATE_ARR = GetXArrFromYID("SELECT DISTINCT state,state FROM areas order by state", '3');
    if(sql_num_rows(sql_query("select * from service_providers  where id=$spid ")))
    {
        sql_query("update service_providers set cStatus='X' where id=$spid ");
        $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Account deleted!", "data" => $data);
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        exit;
        
    }else{
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Not a valid user!!");
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        exit;
    }
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
}
?>