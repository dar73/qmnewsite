<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $q = "SELECT * FROM service_providers WHERE email_address='$email'";
    $r = sql_query($q);
    if (sql_num_rows($r) > 0) {
        echo 1;
    } else {
        echo 0;
    }
}
