<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();
// sleep(5);
// DFA($_POST);
// exit;

//QM ACCESS TOKEN
$RefreshToken = GetXFromYID("select vRefreshToken from ops_calendar_config ");

$ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $RefreshToken);

$access_token = $ACCESS['data']['access_token'];

//$attendees = ['ops@thequotemasters.com', $APPOINTMENT_EMAIL];


// Get the user's calendar timezone 
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
//list($timezone,$msg) = $user_timezone;
if ($user_timezone['msg'] == 'fail') {
    $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $RefreshToken);
    $access_token = $ACCESS_RES['data']['access_token'];
    //sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
}
$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
$timezone = $user_timezone['data'];

// echo $timezone;
// exit;
//$data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');

//DFA($data);


$calendar_event = array(
    'summary' => "TEST APPT",
    'location' => "TEST",
    'description' => "Description"
);

$event_datetime = array(
    'event_date' => '2025-02-27',
    'start_time' => '20:00',
    'end_time' => '21:30'
);

DFA($event_datetime);

$attendees = ['ops@thequotemasters.com','darshankubal1997@outlook.com','darshankubal1@gmail.com', 'chartrand.michael2@outlook.com'];

$response = $GoogleCalendarApi->CreateCalendarEventWithInvite($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone, $attendees);

if ($response['msg'] == 'token_expire') {
    $ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $RefreshToken);

    $access_token = $ACCESS['data']['access_token'];
    // echo $access_token;
    // exit;
    $response = $GoogleCalendarApi->CreateCalendarEventWithInvite($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone, $attendees);
}

DFA($response);
exit;




function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}
//DFA($keyValuePairs);

function SendInBlueMail($subject, $email, $contents, $attachment, $cc = '', $site_title = '', $bcc = '')
{
    if (empty($site_title))
        $site_title = 'Quote Masters';

    if (!empty($contents))
        //$contents .= '<br /><img src="'.SITE_ADDRESS.'img/mail_signature-latest.jpg" alt="Mail Signature" />';

        $cc = '';
    // if ($subject != 'OTP for Login') //empty($bcc) && 
    // $bcc = 'ops@thequotemasters.com';

    $config = array();
    $config['api_key'] = "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";
    $config['api_url'] = "https://api.sendinblue.com/v3/smtp/email";

    $message = array();
    $message['sender'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");
    $message['to'][] = array("email" => "$email");
    $message['replyTo'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");

    if (!empty($cc)) {
        $cc_arr = explode(",", $cc);
        for ($c = 0; $c < sizeof($cc_arr); $c++)
            $message['cc'][] = array("email" => "$cc_arr[$c]");
    }

    if (!empty($bcc)) {
        $bcc_arr = explode(",", $bcc);
        if (!in_array('ops@thequotemasters.com', $bcc_arr)) {
            $bcc_arr[] = 'ops@thequotemasters.com';
            $bcc = implode(",", $bcc_arr);
        }
        for ($b = 0; $b < sizeof($bcc_arr); $b++)
            $message['bcc'][] = array("email" => "$bcc_arr[$b]");
    } else {
        $bcc = 'ops@thequotemasters.com';
    }

    $message['subject'] = $subject;
    $message['htmlContent'] = $contents;

    if (!empty($attachment)) {
        if (is_array($attachment)) {
            $attachment_item[] = array('url' => $attachment);
            $attachment_list = array($attachment_item);

            // Ends pdf wrapper
            $message['attachment'] = $attachment_list;
        } else {
            $attachment_item = array('url' => $attachment);
            $attachment_list = array($attachment_item);
            // Ends pdf wrapper

            $message['attachment'] = $attachment_list;
        }
    }

    $message_json = json_encode($message);

    $ch = curl_init();
    curl_setopt(
        $ch,
        CURLOPT_URL,
        $config['api_url']
    );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: application/json',
        'api-key: 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',
        'content-type: application/json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
