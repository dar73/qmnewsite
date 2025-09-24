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

$CUSTOMER_ARR = GetXArrFromYID("select iCustomerID,concat(vFirstname,'',vLastname) FROM customers", '3');

$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}


if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $q = "select t1.* from appointments t1 inner join buyed_leads t2 on t2.iApptID=t1.iApptID where t2.ivendor_id='$spid' order by iApptID DESC";
    $r = sql_query($q, "ERR.88");
    if (sql_num_rows($r)) {
        for ($i = 1; $o = sql_fetch_object($r); $i++) {
            $Booking_no = $o->iBookingID;
            $APP_ID = $o->iApptID;
            $iAreaID = $o->iAreaID;
            $zip = $ADDRESS_ARR[$iAreaID]['zip'];
            $state = $ADDRESS_ARR[$iAreaID]['state'];
            $county = $ADDRESS_ARR[$iAreaID]['County_name'];
            $city = $ADDRESS_ARR[$iAreaID]['city'];
            $data[] = array('BID' => $Booking_no, 'ZIP' => $zip, 'STATE' => $state, 'COUNTY' => $county, 'CITY' => $city,'APP_ID'=> 'QM-'.$APP_ID);
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