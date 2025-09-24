<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
$mail_content2 = '';
include 'phpmailer.php';
require_once('../includes/common.php');
include "../includes/GoogleCalendarApi.class.php";


$googleCalendar = new GoogleCalendarApi();

$TIME_ARR = GetXArrFromYID("select Id,time from apptime", '3');
if (isset($_POST['bid'])) {
    $bid = db_input2($_POST['bid']);
    //$otp = db_input2($_POST['otp']);
    $CUST_ID = GetXFromYID("select iCustomerID from booking WHERE iBookingID='$bid' ");
    $BOOKING_DETAILS = GetDataFromCOND("booking", " and iBookingID='$bid' ");
    $APPOINTMENTS_DATA = GetDataFromCOND('appointments', " and iBookingID=$bid");
    $CUSTOMER_DATA = GetDataFromCOND('customers', " and iCustomerID='$CUST_ID' ");
    $CUST_ADDRESS = $CUSTOMER_DATA[0]->vAddress;
    $APPOINTMENT_EMAIL = $CUSTOMER_DATA[0]->vEmail;

    $APPOINTMENTS_DATA = GetDataFromCOND('appointments', " and iBookingID=$bid");
    //DFA($APPOINTMENTS_DATA);
    //exit;
    $output=array();


    if (!empty($APPOINTMENTS_DATA)) {
        for ($u = 0; $u < sizeof($APPOINTMENTS_DATA); $u++) {
            $EVENT_DATE = date('Y-m-d', strtotime($APPOINTMENTS_DATA[$u]->dDateTime));
            $EVENT_TIME = date('H:i', strtotime($TIME_ARR[$APPOINTMENTS_DATA[$u]->iAppTimeID]));
            $end_time = date("H:i", strtotime("$EVENT_TIME +30 minutes"));
            $event_data = array(
                'summary' => 'Appointment Invite',
                'location' => $CUST_ADDRESS,
                'description' => 'Appointment booked with thequotemasters.com'
            );

            $event_datetime = array(
                'event_date' => $EVENT_DATE,
                'start_time' => $EVENT_TIME,
                'end_time' => $end_time
            );

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
                //echo "0~Refresh Token Expired please request new one.";
                //header("location: $disp_url");
                //exit;
            }
            $access_token = $ACCESS['data']['access_token'];

            $attendees = ['ops@thequotemasters.com', $APPOINTMENT_EMAIL];

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
            //DFA($response);
            //exit;
            if(!empty($response['data']))
            {
                $output = array('error' => false, 'message' => 'Alerts send');
            }else{
                $output = array('error' => true, 'message' => 'Opps !! some error occured..');
            }


        }
    }
}

header('Content-Type: application/json');
echo json_encode($output);
exit;

// ALTER TABLE `quote_master`.`customers`
//   ADD COLUMN `cMailsent` varchar(255) NULL DEFAULT NULL AFTER `dtRegistration`;
