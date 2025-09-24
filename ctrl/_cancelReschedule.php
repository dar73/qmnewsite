<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$return=0;
$_q= "UPDATE appointments SET cService_status = 'X' WHERE iApptID = $id ";
if (sql_query($_q)) {
    $return=1;
}
echo $return;
exit;
?>