<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$token = (isset($request->token)) ? db_input2($request->token) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $spid = (isset($request->spid)) ? db_input2($request->spid) : '0';
    $REFRESH_TOKEN = $ACCESS_TOKEN = '';
    $q="select vAccessToken,vRefreshToken from service_providers where id=$spid ";
    $r = sql_query($q);
    list($ACCESS_TOKEN, $REFRESH_TOKEN) = sql_fetch_row($r);
    if(empty($ACCESS_TOKEN) && empty($REFRESH_TOKEN)){
        $returnArr = array("ResponseCode" => "400", "ResponseMsg" => "Access Token Does not exist", 'data' => $data);
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        sql_close();
        exit;

    }

    $ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $REFRESH_TOKEN);

    if(empty($ACCESS['data'])){
        $returnArr = array("ResponseCode" => "400", "ResponseMsg" => "Not able to Create Token", 'data' => $data);
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        sql_close();
        exit;
    }
    $access_token = $ACCESS['data']['access_token'];
    $data['ACCESS_TOKEN'] = $access_token;


    $returnArr = array("ResponseCode" => "200","ResponseMsg" => "Success",'data'=>$data);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}
