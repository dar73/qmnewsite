<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$client_id = 'cd7591fd-db33-4acf-b3cf-8bdd9031d685';
$client_secret = 'JCz8Q~eWZ1_GV3V.mOSlolgep.NdnUJTJt.2UaQE';
$refresh_token = GetXFromYID("SELECT  vRefreshToken FROM ops_calendar_config WHERE 1");; // from DB
$scope = 'https://graph.microsoft.com/Calendars.ReadWrite offline_access';

$token_url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

$post_fields = [
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'refresh_token',
    'refresh_token' => $refresh_token,
    'scope' => $scope
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
curl_close($ch);

$token_data = json_decode($response, true);

if (isset($token_data['access_token'])) {
    echo "New Access Token: " . $token_data['access_token'];
    // Optional: save new refresh_token too (if provided)
    if (isset($token_data['refresh_token'])) {
        echo "<br>New Refresh Token: " . $token_data['refresh_token'];
    }
} else {
    echo "Error refreshing token:<br><pre>";
    print_r($token_data);
    echo "</pre>";
}
