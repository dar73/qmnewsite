<?php
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $key = "xyfVJQxKvJuaJnHqAE8JU";
    $url = "https://apps.emaillistverify.com/api/verifyEmail?secret=" . $key . "&email=" . $email;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);
    //echo $response;
    curl_close($ch);
    if ($response == 'ok') {
        echo 1;
    } else {
        echo 0;
    }
}
