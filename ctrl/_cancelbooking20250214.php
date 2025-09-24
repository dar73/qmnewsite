<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$edit_url = '_debitamt.php';
$APP_ID=$_POST['appid'];
$SP_ID=$_POST['spid'];

//$PREMIUM_SP=GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cPlatinumAgreement='Y' and cUsertype='P' and id=578","3");
//COPY TO  BACKUP TABLE

$_q1="INSERT INTO buyed_leads_backup(dDate, ivendor_id, ibooking_id, iApptID, fAmt, vTransactionID)
SELECT dDate, ivendor_id, ibooking_id, iApptID, fAmt, vTransactionID
FROM buyed_leads
WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID";
sql_query($_q1);

//Delete from buyed leads
$_q2="delete from buyed_leads
WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID";
sql_query($_q2);

//set appointment status to X

sql_query("update appointments set cStatus='X' where iApptID=$APP_ID");

sql_query("UPDATE platinum_purchase_leads SET cStatus='X' WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID");


echo '1~~*~~Booking Cancelled!!';
exit;
