<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['id'])) {
    $q = "SELECT t2.* FROM service_providers_areas t1 INNER JOIN areas t2 ON t1.zip=t2.zip WHERE t1.service_providers_id='" . $_POST['id'] . "' ";
    $r = sql_query($q);
    $data = $output = array();
    while ($row = mysqli_fetch_assoc($r)) {
        array_push($data, $row);
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
