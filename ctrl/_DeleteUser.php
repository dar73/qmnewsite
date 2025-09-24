<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
$type = (isset($_POST['type'])) ? $_POST['type'] : '';
$result=0;
if ($type=='SP') {
    sql_query("update service_providers set cStatus='X' where id=$id ");
    $result=1;
}elseif ($type == 'C') {
    sql_query("update customers set cStatus='X' where iCustomerID=$id ");
    $result = 1;
}
echo $result;
exit;
?>