<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
include "../includes/GoogleCalendarApi.class.php";

$event_data = array(
    'summary' => 'Calendar Invite Test',
    'location' => '123 Sample Street',
    'description' => 'This is a sample event.'
);

$event_datetime = array(
    'event_date' => '2024-12-04',
    'start_time' => '1:30',
    'end_time' => '2:00'
);



$googleCalendar = new GoogleCalendarApi();


$RefreshToken = GetXFromYID("select vRefreshToken from ops_calendar_config ");

$ACCESS = $googleCalendar->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $RefreshToken);

//DFA($ACCESS);
// Array
// (
//     [data] => Array
//         (
//             [access_token] => ya29.a0AeDClZCNxEqxl_pvpOr6v-CRPDMtQOx_OOlvff3Z9jYVCCTcgympfyhFyH5Wde0G5LroHuq32HCWBNt-IbAIusxvoabO38vlFT4mP5yXpbIUSw-7s8HkVnMOdvny4ifZZ4wpfMkqxiV-jFpJQAMR0g-VxXFoX4vozzX7Md5HaCgYKAQASARESFQHGX2Mi3LUGgcvZ323VNnFEwjWC7w0175
//             [expires_in] => 3599
//             [scope] => https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile openid
//             [token_type] => Bearer
//             [id_token] => eyJhbGciOiJSUzI1NiIsImtpZCI6ImQ5NzQwYTcwYjA5NzJkY2NmNzVmYTg4YmM1MjliZDE2YTMwNTczYmQiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiOTE2MDE4Njc5Nzc1LWQzaHIxbWRuM3ZpNG9zNDFpbDdlMmIyamZkamczazMwLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiOTE2MDE4Njc5Nzc1LWQzaHIxbWRuM3ZpNG9zNDFpbDdlMmIyamZkamczazMwLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTA5NTg1Mjc3NDE4ODQzNjQwNDc4IiwiZW1haWwiOiJkYXJzaGFua3ViYWwxQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoid1JjME1sZFdLQ0RNYVZuX1VmMlhmUSIsImlhdCI6MTczMjQ2MzgxOCwiZXhwIjoxNzMyNDY3NDE4fQ.Cy7mPKkaqHpKJhlRPgOlEAvLMXt2WBe4Og1M9IBfFXUz8Bad-Nx3hP6dCkSW1y4fuG4xDQ8BMAFDL3W_8H_WqWK3BQPSaMC3KQV29fewp6czI2IZVFrJhy3vy7EJPnqOpi9MP3kop4XVkeEgSkSO8twldR_nMBC6J9HANc-H_aFotdqmvyBQ5Yc0jkMlVJWBL8aQGt1a6lltQmsIhnyKYE_5FFd2_xnSdl9pvvzK2j5t37CHrJjR6tkuANe4ito0Kd0T-DMBMUCaoKqZOYTevxb4p89n2pYjecz6XNz9t9SSYuVFSp55pQmaz9PYHrOdJhZNL3VNmxk1i5Vr-5jJLw
//         )

//     [msg] => success
// )


if (empty($ACCESS['data'])) {
    echo "0~Refresh Token Expired please request new one.";
    //header("location: $disp_url");
    exit;
}
$access_token = $ACCESS['data']['access_token'];

$attendees = ['ops@thequotemasters.com','darshankubal1@gmail.com'];

// Get the user's calendar timezone 
$user_timezone = $googleCalendar->GetUserCalendarTimezone($access_token);
//list($timezone,$msg) = $user_timezone;
if ($user_timezone['msg'] == 'fail') {
    $ACCESS_RES = $googleCalendar->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $RefreshToken);
    $access_token = $ACCESS_RES['data']['access_token'];
    sql_query("update ops_calendar_config set vAccessToken='$access_token'  ");
}
$user_timezone = $googleCalendar->GetUserCalendarTimezone($access_token);
$timezone = $user_timezone['data'];

$response = $googleCalendar->CreateCalendarEventWithInvite($access_token, 'primary', $event_data, 0, $event_datetime, $timezone, $attendees);

DFA($response);

// Array
// (
//     [data] => ul98st3ck8dd76lis6hq27psdc
//     [msg] => success
// )





?>