<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$data = array();
$sql = "SELECT *  FROM areas";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($data, array('id' => $row['id'], 'zip' => $row['zip']));
    }
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo '<p class="list-group list-group-item">Records Not found</p>';
}
