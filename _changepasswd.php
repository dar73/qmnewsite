<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
require_once('includes/ti-salt.php');
if (isset($_POST['type'],$_POST['mode'],$_POST['passwd1'],$_POST['passwd2'])) {
    $type=db_input($_POST['type']);
    $mode=db_input($_POST['mode']);
    $passwd1=db_input($_POST['passwd1']);
    //$txtpassword=md5($passwd1);
    $txtuserid=db_input($_POST['txtuserid']);
    $ID=DecryptStr($txtuserid);
    $passwd2=db_input($_POST['passwd2']);
    $txtpassword = htmlspecialchars_decode($passwd2);
    $salt_obj = new SaltIT;
    $txtpassword = $salt_obj->EnCode($txtpassword);
    if ($type == 'V') {
        $q = "update  service_providers set password='$txtpassword' WHERE id='$ID' ";
    } elseif ($type == 'C') {
        $q = "update  customers set vPassword='$txtpassword' WHERE iCustomerID='$ID' ";
    }

    $r = sql_query($q, "");
    header('location:pass_change_success.php');
    exit;

}else{
    echo 'Invalid Access';
    exit;
}
?>