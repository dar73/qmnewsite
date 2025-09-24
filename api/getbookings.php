<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$spid = (isset($_POST['spid'])) ? db_input2($_POST['spid']) : '';
$returnArr = array();
$data = array();
$CUSTOMER_ARR = $ADDRESS_ARR = array();
$cond2 = '';
$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}


if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $BUYED_BOOKING_ID = GetXArrFromYID("select ibooking_id from buyed_leads where ivendor_id='$spid' ");
    //DFA($BUYED_BOOKING_ID);
    // exit;
    if (!empty($BUYED_BOOKING_ID)) {
        $cond2 .= " and iBookingID not in(" . implode(",", $BUYED_BOOKING_ID) . ")";
    }

    $BIDS_ARR = GetIDString2("select distinct(iBookingID) from appointments where 1 and cService_status='P' and iAreaID in (SELECT DISTINCT t1.id FROM areas t1 INNER JOIN service_providers_areas t2 ON t1.zip=t2.zip WHERE 1 AND t2.service_providers_id='$spid') and cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >='" . TODAY . "'  order by dDateTime DESC ");
    //DFA($BIDS_ARR);
    if (empty($BIDS_ARR) || $BIDS_ARR == '-1')
    $BIDS_ARR = '0';

    $q = "select * from booking where 1  and iBookingID  in(" .  $BIDS_ARR . ") and cStatus='A' and bverified='1' $cond2  ";
    $r = sql_query($q, "ERR.88");
    if (sql_num_rows($r)) {
        for ($i = 1; $o = sql_fetch_object($r); $i++) {
            $Booking_no = $o->iBookingID;
            $iNo_of_quotes = $o->iNo_of_quotes;
            $iAreaID = $o->iAreaID;
            $zip = $ADDRESS_ARR[$iAreaID]['zip'];
            $state = $ADDRESS_ARR[$iAreaID]['state'];
            $county = $ADDRESS_ARR[$iAreaID]['County_name'];
            $city = $ADDRESS_ARR[$iAreaID]['city'];
            $data[] = array('BID' => $Booking_no, 'ZIP' => $zip, 'STATE' => $state, 'COUNTY' => $county, 'CITY' => $city);
        }
    }
    $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "success!", "data" => $data);
    sql_close();
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    exit;
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}
?>