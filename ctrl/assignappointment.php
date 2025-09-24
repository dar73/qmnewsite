<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';

$BID = (isset($_GET['BID'])) ? $_GET['BID'] : '120';
$APP_ID = (isset($_GET['APPID'])) ? $_GET['APPID'] : '391';
$PID = (isset($_GET['PID'])) ? $_GET['PID'] : '312';
$AMOUNT = (isset($_GET['AMT'])) ? $_GET['AMT'] : '125';
if(empty($BID) && empty($APP_ID) && empty($PID) && empty($AMOUNT))
{
    echo 'Invalid Access!';
    exit;
}
$CFEE = $AMOUNT * 0.03;
$FINAL_AMT = $AMOUNT + $CFEE;
$FINAL_AMT = (int)$FINAL_AMT;
$TRANS_REF = "DIRECTPAY";
LockTable('transaction');
$ID = NextID('id', 'transaction');
$_q = "insert into transaction values ('$ID','$BID','$APP_ID','$PID','','','$FINAL_AMT','" . NOW . "','online','P')";
$_r = sql_query($_q, "");
UnlockTable();

$_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$ID'  ";
$_r1 = sql_query($_q1, "");
list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
$_q2 = "update transaction set payment_id='$TRANS_REF',payment_status='S' where id='$ID' ";
sql_query($_q2, "");

$updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$ID' "; //update dat table
sql_query($updatebookingdat);

$_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$FINAL_AMT','$TRANS_REF')";
sql_query($_q3, "");
$_q4 = "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
sql_query($_q4, "");
$_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
sql_query($_q5, "");
echo 'Done';
?>