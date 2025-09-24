<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$PAGE_TITLE2 = 'Calendar';

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

$access_token = '';//GetXFromYID("select vAccessToken from service_providers where id=$sess_user_id ");
$refresh_token = '';//GetXFromYID("select vRefreshToken from service_providers where id=$sess_user_id ");

$data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
$refresh_token = $data['refresh_token'];
$access_token = $data['access_token'];
sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");
header('location:v_profile.php');
exit;
?>