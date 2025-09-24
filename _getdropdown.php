<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
$type=(isset($_POST['type']))?$_POST['type']:'';
if (!empty($type)) {
    if ($type==1) {
        if (isset($_POST['state'])) {
            $state = $_POST['state'];
            $q = "SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name ";
            $r = sql_query($q);
            $data = array();
            while ($R = sql_fetch_assoc($r)) {
                array_push($data, array('county_name' => $R['County_name']));
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }elseif ($type==2) {
        if (isset($_POST['state'])) {
            $state = $_POST['state'];
            $q = "SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name ";
            $r = sql_query($q);
            $data = array();
            while ($R = sql_fetch_assoc($r)) {
                array_push($data, array('county_name' => $R['County_name']));
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}
