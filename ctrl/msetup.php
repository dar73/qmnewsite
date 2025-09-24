<?php
include "../includes/common.php";

// $_SESSION['ADMIN_CALENDAR_SETUP'] = true;

$activity_txt = "Started the process of setting up the Microsoft calendar ,click on the button";
AddSPActivity($sess_user_id, 3, $ACTIVITY_TIMELINE_ARR[3], "app_sp", $activity_txt, "U");

$client_id = 'cd7591fd-db33-4acf-b3cf-8bdd9031d685';
$redirect_uri = 'https://thequotemasters.com/ctrl/mcalendar_callback.php';
$scopes = 'https://graph.microsoft.com/Calendars.ReadWrite offline_access';

$authorize_url = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?" . http_build_query([
    'client_id' => $client_id,
    'response_type' => 'code',
    'redirect_uri' => $redirect_uri,
    'response_mode' => 'query',
    'scope' => $scopes,
]);

header('Location: ' . $authorize_url);
exit;
