<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['county_name'], $_POST['state'])) {
    $county_name = $_POST['county_name'];
    $state = $_POST['state'];
    foreach ($county_name as  $value) {
        $Q = "SELECT DISTINCT city FROM areas WHERE state='$state' AND County_name='$value' ";
        $r = sql_query($Q);
        $str .= ' <optgroup label="' . $value . '">';
        while ($R = sql_fetch_assoc($r)) {
            $str .= '<option value="' . $R['city'] . '">' . $R['city'] . '</option>';
        }
        $str .= '</optgroup>';
    }
    echo $str;
    exit;
} elseif (isset($_POST['state'])) {
    $state = $_POST['state'];
    $q = "SELECT DISTINCT city FROM areas WHERE state IN ('" . implode("','", $state) . "') ";
    $r = sql_query($q);
    $data = array();
    while ($R = sql_fetch_assoc($r)) {
        array_push($data, array('city' => $R['city']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>

//SELECT  DISTINCT city,state FROM areas WHERE NOT County_name='PROVIDENCE' AND NOT County_name='WASHINGTON' AND state='RI';