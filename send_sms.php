<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
include 'phpmailer.php';


$curl = curl_init();

$phone = '8137277305';

$sms_content = 'Verification code for your requirement is : ' . $otp;

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.tiltx.com/sms/send-sms',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
    "to":"' . $phone . '",
     "text":"' . $sms_content . '"
}',
    CURLOPT_HTTPHEADER => array(
        'x-api-key: JzSpDZu1wx8fN6qNlMXxV4WmhpaOBX1k3O8DO9Eb',
        'Content-Type: text/plain'
    ),
));

$response = curl_exec($curl);
curl_close($curl);
DFA($response);




?>