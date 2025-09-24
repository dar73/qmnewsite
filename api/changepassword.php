<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include "../includes/ti-salt.php";


$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$txtpassword = (isset($_POST['txtpassword'])) ? db_input2($_POST['txtpassword']) : ''; 
$txtnewpassword = (isset($_POST['txtnewpassword'])) ? db_input2($_POST['txtnewpassword']) : '';
$spid = (isset($_POST['spid'])) ? db_input2($_POST['spid']) : '';
$returnArr = array();
$data = array();
$txtpassword = htmlspecialchars_decode($txtpassword);
$salt_obj = new SaltIT;
$txtpassword = $salt_obj->EnCode($txtpassword);

$txtnewpassword = htmlspecialchars_decode($txtnewpassword);
$salt_obj = new SaltIT;
$txtnewpassword = $salt_obj->EnCode($txtnewpassword);
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $currentPassword = GetXFromYID('select password from service_providers where id=' . $spid);
    if (htmlspecialchars_decode($currentPassword) != $txtpassword){
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Incorrect Current Password Entered");
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        exit;
    }
    else {
        $values = " password='$txtnewpassword'";
        $QUERY = UpdataData('service_providers', $values, "id=$spid");
        $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Password Successfully Updated ", "data" => $data);
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