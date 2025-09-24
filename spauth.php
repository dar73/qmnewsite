<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include "includes/common.php";


$PID =(isset($_GET['qcode'])) ? $_GET['qcode'] : ''; //EncodeParam(588); 
if (empty($PID)) {
    echo 'Invalid Access !!!';
    exit;
}

$PID = DecodeParam($PID);
$IS_SP_EXISTS = GetXFromYID("select count(*) from service_providers where id='$PID' and cStatus='A' ");
if (empty($IS_SP_EXISTS)) {
    echo 'Invalid Access !!!';
    exit;
}

$u_id = $u_level = $ref_type = $ref_id = 0;
//$q1 = "select iCustomerID,vFirstname,vPassword from customers where vEmail='$email' and cStatus='A'  "; //customers table
$q2 = "select id,First_name,password from service_providers where id='$PID' and cStatus='A'   "; //vendor table
$r = sql_query($q2, 'AUTH.61');
if (sql_num_rows($r)) {
    list($u_id, $u_name, $u_pass) = sql_fetch_row($r);
    $ret = 1;//($u_pass == ($txtpassword)) ? 1 : -1;    // 1 - txtpassword Matches ::  -1 - txtpassword MisMatch
    // echo $u_pass . '<br>' . $txtpassword;
    // exit;

    if ($ref_type == 'A') $ref_id = $u_id;
} else
    $ret = -2;    //No User Found

if ($ret == -1 || $ret == -2) {
    echo 'Invalid Access !!!';
    exit;
} elseif ($ret == 1) {
    session_destroy();
    session_start();
    session_regenerate_id();
    ${PROJ_SESSION_ID} = new userdat;

    $randomtoken = base64_encode(uniqid(rand(), true));

    $_SESSION[PROJ_SESSION_ID] = new userdat;
    $_SESSION[PROJ_SESSION_ID]->log_time = NOW2;
    $_SESSION[PROJ_SESSION_ID]->log_stat = "A";
    $_SESSION[PROJ_SESSION_ID]->user_id = $u_id;
    $_SESSION[PROJ_SESSION_ID]->user_pic = '';
    $_SESSION[PROJ_SESSION_ID]->user_name = $u_name;
    $_SESSION[PROJ_SESSION_ID]->user_level = $usertype;
    $_SESSION[PROJ_SESSION_ID]->user_type = $usertype;
    $_SESSION[PROJ_SESSION_ID]->user_reftype = '';
    $_SESSION[PROJ_SESSION_ID]->user_refid = '';
    $_SESSION[PROJ_SESSION_ID]->sess = session_id();
    $_SESSION[PROJ_SESSION_ID]->rmadr = $_SERVER['REMOTE_ADDR'];
    $_SESSION[PROJ_SESSION_ID]->lhs_menu = true;
    $_SESSION[PROJ_SESSION_ID]->sess_token = $randomtoken;
    $_SESSION[PROJ_SESSION_ID]->sess_active = 'Y';

    $q = "update users set dtLastLogin='" . NOW . "', vLastLoginIP='" . $_SERVER['REMOTE_ADDR'] . "', vToken='$randomtoken', cActive='Y' where iUserID=$u_id";
    $r = sql_query($q, 'AUTH.78');

    $browser = '';
    $browser2 = getBrowser();
    if (!empty($browser2) && count($browser2))
        $browser = $browser2['name'] . ' ' . $browser2['version'];

    $ipaddress = $_SERVER['REMOTE_ADDR'];
    sql_query("insert into log_signin (dDate, cRefType, iRefID, dtEntry, vIPAddress, vBrowser, cStatus) values ('" . TODAY . "', 'V', '$ref_id', '" . NOW . "', '$ipaddress', '$browser', 'A')", "");
    header('location:ctrl/v_profile.php');
    exit;
} else {
    echo 'Invalid Access !!!';
    exit;
}

sql_close();
?>