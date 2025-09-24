<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';

$today=TODAY;
$dateTime=NOW;
$q="SELECT id FROM service_providers WHERE cStatus='A' and id not in (SELECT  iSPID  FROM coverage_sync_logs WHERE date_format(dtDate,'%Y-%m-%d')='$today' )  limit 3 ";
$r=sql_query($q);
if(sql_num_rows($r))
{
    // while (list($SPID)=sql_fetch_row($r)) {
    //     UpdateCoverages4($SPID);
    //     LockTable('coverage_sync_logs');
    //     $LOGID=NextID('iLogID','coverage_sync_logs');
    //     $q2="INSERT INTO coverage_sync_logs(iLogID, iSPID, dtDate, cStatus) VALUES ('$LOGID','$SPID','$dateTime','A')";
    //     sql_query($q2);
    //     UnlockTable();

    // }

}
sql_close();
?>