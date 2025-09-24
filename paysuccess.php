<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');

DFA($_POST);
exit;

$RESPONSE = isset($_POST['AUTH_RESP']) ? $_POST['AUTH_RESP'] : '';
$TRANS_ID = isset($_POST['TRAN_NBR']) ? $_POST['TRAN_NBR'] : '';
$TRANS_REF = isset($_POST['AUTH_GUID']) ? $_POST['AUTH_GUID'] : '';
$TRANS_AMT = isset($_POST['AUTH_AMOUNT']) ? $_POST['AUTH_AMOUNT'] : '';

if ($RESPONSE == '00') {
    sql_query("update transaction2 set payment_id='$TRANS_REF',payment_status='A' where id='$TRANS_ID' ");

}

header('location:ctrl/v_profile.php');
exit;
?>