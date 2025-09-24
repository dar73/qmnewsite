<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$code = $_GET['code'];

$access_token = '';
// echo $code;
// exit;
//$authcode = '4/0AcvDMrD9_jvSYJWVvoAEbVji_yZGRXLipd_TGmelckGStvPbVypd6Ua-OhHnqBHWJLYZVw';
// $_SESSION['google_access_token'] = '';
// exit;
// [access_token] => ya29.a0AXooCgvUMV6l31xtZ8mBCUQGvqjEYYZTOhaWdXZfEEd9lqt7t7Q3iUPRNmrSTOiy4wngPNngXl4Qu6DmdTNOubVYVVsuGTrq65avHpEa-_wnaytsYwVDGOhPqrRzae_iEEvKAXRARyHdtWuMdSuKk2YLFHEf64Le3v6EaCgYKAU8SARESFQHGX2MiE6qiP1078WtKPsZ7mT1x0g0171
//     [expires_in] => 3599
//     [refresh_token] => 1//0gDS9oFmMYqIzCgYIARAAGBASNwF-L9IrMEdL_NO_jwmTjkwN1WiUCVyUBTlKSpMwz7P1uJXnujXOz2ta-sRFgtA8-tkSNkNkhaw
//     [scope] => https://www.googleapis.com/auth/calendar
//     [token_type] => Bearer

// $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
// $access_token = $data['access_token'];
// $refresh_token = $data['refresh_token'];
// sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");


$data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
if(empty($data))
{
    $refresh_token = isset($data['refresh_token']) ? $data['refresh_token'] : '';
    if(empty($refresh_token)) {
        $_SESSION[PROJ_SESSION_ID]->error_info = "Opps there is some issue with connecting your account!!";
        header('location:v_profile.php');
        exit;
    }
    $_SESSION[PROJ_SESSION_ID]->error_info = "Opps there is some issue with connecting your account!!";
    header('location:v_profile.php');
    exit;
}
// DFA($data);
// exit;

// Array
// (
//     [access_token] => ya29.a0AeXRPp5yLL292qL0mZxb_wRY2C1kU2VVBitXXl-NBy8AYL4w1jqBzcGa4N2kKrCRkzS3j-vr1ppdvg-ma14hV8WHYthh8cnY9uNAlNv_WKcUUuEwMOD4Aa-tB08JHtphjVHpWcPlXxVuiPDr10lXc1xLmWbXH6cOBhE05N4gkDsaCgYKAZ4SARESFQHGX2MizPCMZFAHqzUw5-iTgvCJOA0178
//     [expires_in] => 3598
//     [scope] => https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.events
//     [token_type] => Bearer
// )
$refresh_token = isset($data['refresh_token']) ? $data['refresh_token'] : '';
if (empty($refresh_token)) {
    $activity_txt = "Refresh Token not captured in step 2 for Google Calendar setup";
    AddSPActivity($sess_user_id, 4, $ACTIVITY_TIMELINE_ARR[4], "app_sp", $activity_txt, "U");
    $_SESSION[PROJ_SESSION_ID]->error_info = "Opps there is some issue with connecting your account!!";
    header('location:v_profile.php');
    exit;
}
$access_token = $data['access_token'];
if(isset($_SESSION['ADMIN_CALENDAR_SETUP']))
{
    //UPDATE `ops_calendar_config` SET `vAccessToken`='[value-1]',`vRefreshToken`='[value-2]' WHERE 1
    sql_query("update ops_calendar_config set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' ");
    $_SESSION[PROJ_SESSION_ID]->success_info = "Google Calendar setup successfully Done!!";
    header('location:home.php');
    exit;    
}
// $access_token = GetXFromYID("select vAccessToken from service_providers where id=$sess_user_id ");
// $refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$sess_user_id ");

$activity_txt = "Successfully completed the Google Calendar setup";
AddSPActivity($sess_user_id, 4, $ACTIVITY_TIMELINE_ARR[4], "app_sp", $activity_txt, "U");

sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");
$_SESSION[PROJ_SESSION_ID]->success_info = "Google Calendar setup successfully Done!!";
sql_close();
header('location:v_profile.php');
exit;
?>