<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$SP_DATA = GetDataFromCOND("service_providers", " and id='562' ");

$access_token = $SP_DATA[0]->vRefreshToken;
$refresh_token = $SP_DATA[0]->vAccessToken;
// Get the user's calendar timezone 
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);

if ($user_timezone['msg'] == 'fail') {
    $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
    DFA($ACCESS_RES);
    $access_token = $ACCESS_RES['data']['access_token'];
    //sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
}
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
$timezone = $user_timezone['data'];
$calendar_event = array(
    'summary' => 'Test',
    'location' => 'Test',
    'description' => 'Desc'
);

$event_datetime = array(
    'event_date' => '2024-09-04',
    'start_time' =>'10:00',
    'end_time' => '10:30'
);


// Get the user's calendar timezone 
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
//list($timezone,$msg) = $user_timezone;
if ($user_timezone['msg'] == 'fail') {
    $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
    $access_token = $ACCESS_RES['data']['access_token'];
    //sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
}
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
$timezone = $user_timezone['data'];
//$data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');

//DFA($data);

// Create an event on the primary calendar 
$EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);

if ($EVENT_RESPONSE['msg'] == 'token_expire') {
    $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
    $access_token = $ACCESS_RES['data']['access_token'];
    //sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
    $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
    $timezone = $user_timezone['data'];
    $EVENT_RESPONSE = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);
}

DFA($EVENT_RESPONSE);
exit;


?>