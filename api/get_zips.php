<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['city'], $_POST['county_name'], $_POST['state'])) {
    $city = $_POST['city'];
    $county_name = $_POST['county_name'];
    $state = $_POST['state'];
    $str1 = '';

    $str1 .= "  AND  County_name IN ('" . implode("','", $county_name) . "')";

    $str2 = '';
    $str2 .= "  AND  city IN ('" . implode("','", $city) . "')";

    $q = "SELECT zip,id FROM areas WHERE state IN ('" . implode("','", $state) . "') " . $str1 . $str2;
    $r = sql_query($q);
    $data = array();
    while ($R = sql_fetch_assoc($r)) {
        array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_POST['city'], $_POST['state'])) {
    $city = $_POST['city'];
    $state = $_POST['state'];
    $str2 = '';

    $str2 .= "  AND  city IN ('" . implode("','", $city) . "')";

    $q = "SELECT zip,id FROM areas WHERE state IN ('" . implode("','", $state) . "') " . $str2;
    $r = sql_query($q);
    $data = array();
    while ($R = sql_fetch_assoc($r)) {
        array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_POST['county_name'], $_POST['state'])) {
    $county_name = $_POST['county_name'];
    $state = $_POST['state'];
    $str1 = '';
    $str1 .= "  AND  County_name IN ('" . implode("','", $county_name) . "')";

    $q = "SELECT zip,id FROM areas WHERE state IN ('" . implode("','", $state) . "') " . $str1;
    $r = sql_query($q);
    $data = array();
    while ($R = sql_fetch_assoc($r)) {
        array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_POST['state'])) {
    $state = $_POST['state'];
    $q = "SELECT DISTINCT zip,id FROM `areas` WHERE state IN ('" . implode("','", $state) . "') ";
    $r = sql_query($q);
    $data = array();
    while ($R = sql_fetch_assoc($r)) {
        array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
