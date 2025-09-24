<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
$mail_content2='';
include 'phpmailer.php';
require_once('includes/common.php');
include "includes/GoogleCalendarApi.class.php";


$googleCalendar = new GoogleCalendarApi();

$MASTER_OTP = '12345';

$TIME_ARR=GetXArrFromYID("select Id,time from apptime",'3');
if (isset($_POST['bid'], $_POST['otp'])) {

    $bid = db_input2($_POST['bid']);
    $otp = db_input2($_POST['otp']);
    $CUST_ID = GetXFromYID("select iCustomerID from booking WHERE iBookingID='$bid' ");
    $BOOKING_DETAILS=GetDataFromCOND("booking"," and iBookingID='$bid' ");
    $APPOINTMENTS_DATA=GetDataFromCOND('appointments'," and iBookingID=$bid");
    $CUSTOMER_DATA=GetDataFromCOND('customers'," and iCustomerID='$CUST_ID' ");
    $CUST_ADDRESS=$CUSTOMER_DATA[0]->vAddress;
    $APPOINTMENT_EMAIL=$CUSTOMER_DATA[0]->vEmail;
    if ($otp==$MASTER_OTP)
     {

        // DFA($CUSTOMER_DATA);

        // [iCustomerID] => 2
        //     [vFirstname] => Laxman
        //     [vLastname] => Kubal
        //     [vName_of_comapny] => RMS.TECH
        //     [vAddress] => dada
        //     [vPosition] => add
        //     [vEmail] => darshankubal1@gmail.com
        //     [vPassword] => hLn15@)T~r7m}U{o-^q
        //     [vPhone] => 7350807077
        //     [dtRegistration] => 2023-11-19 07:34:18
        //     [cMailsent] => Y
        //     [cStatus] => A




        // [iBookingID] => 129
        // [iAreaID] => 32244
        // [iCustomerID] => 2
        // [iNo_of_quotes] => 2
        // [cSelf_schedule] => Y
        // [cService_status] => P
        // [bverified] => 0
        // [iBookingCode] => 1
        // [dDate] => 2024-11-24 21:45:04
        // [vNotes] => 
        // [cStatus] => A

        //DFA($BOOKING_DETAILS);
        //exit;
        $APPOINTMENTS_DATA=GetDataFromCOND('appointments'," and iBookingID=$bid");
        //DFA($APPOINTMENTS_DATA);
        //exit;
        $Email = GetXFromYID("select vEmail from customers where iCustomerID='$CUST_ID' ");
        //list($iOTPID, $vOTP, $Email) = sql_fetch_row($adm_o_q_res);
        $upadte_b_q = "UPDATE booking SET bverified='1' WHERE iBookingID='$bid'";
        sql_query($upadte_b_q, "update_booking_table");
        $to = $Email;
        $subject = "Welcome Email";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com <ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content2 .= "<html>";
        $mail_content2 .= "<body>";
        $mail_content2 .= "<p>Hi,</p>";
        $mail_content2 .= "<p>WELCOME to the QUOTE MASTERS family where YOU are the MASTER of the leads you receive!  With Quote Master you will receive an amazing source of janitorial leads! </p>";

        $mail_content2 .= "<p>You receive the following information with each lead:  </p>";
        $mail_content2 .= "<ol>
                            <li>A verified appointment day and time for your meeting</li>
                            <li>Years of expertise</li>
                            <li>Current star rating</li>
                            <li>Name of the manager you will be meeting with</li>
                            <li>Day and time you selected to meet</li>
                           
                            </ol>  ";
        $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
        $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
        $mail_content2 .= "<p>Questions? Need help? Please</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content2 .= "<p>Quote Master</p>";
        $mail_content2 .= "</body>";
        $mail_content2 .= "</html>";
        $_q1 = "select vEmail from customers where vEmail='$Email' and cMailsent='N' ";
        $_r1 = sql_query($_q1, "check_mail_sent");
        if (sql_num_rows($_r1)) {
            //mail($to, $subject, $mail_content2, $headers);
            //Send_mail('', '', $to, '',"", "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $mail_content2, '');
            SendInBlueMail($subject,$to,$mail_content2, '', '','', "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com");
            sql_query("update customers set cMailsent='Y' where vEmail='$Email' ", "UPDATE_EMAIL_STATUS");



            //
            if (!empty($APPOINTMENTS_DATA)) {
                for ($u = 0; $u < sizeof($APPOINTMENTS_DATA); $u++) {
                    //         [iApptID] => 406
            // [iBookingID] => 129
            // [iAreaID] => 32244
            // [iCustomerID] => 2
            // [dBookingDate] => 2024-11-24
            // [bverified] => 0
            // [iBookingCode] => 1
            // [dDateTime] => 2024-11-28 00:00:00
            // [iAppTimeID] => 7
            // [cService_status] => P
            // [cStatus] => A
                    $EVENT_DATE=date('Y-m-d',strtotime($APPOINTMENTS_DATA[$u]->dDateTime));
                    $EVENT_TIME=date('H:i',strtotime($TIME_ARR[$APPOINTMENTS_DATA[$u]->iAppTimeID]));
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
                    
                    $attendees = ['ops@thequotemasters.com',$APPOINTMENT_EMAIL];
                    
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
                    
                    //$response = $googleCalendar->CreateCalendarEventWithInvite($access_token, 'primary', $event_data, 0, $event_datetime, $timezone, $attendees);
                    //DFA($response);
                    //exit;

            
                }
            }
        }

        echo 1;
        exit;
        
    }else
    {
        $adm_o_q = "SELECT iOTPID,vOTP,vEmail FROM adm_otp WHERE  NOW()<dtTo AND iBid='$bid' AND vOTP='$otp' AND cUsed='A' ";
        $adm_o_q_res = sql_query($adm_o_q, "");
    
        if (sql_num_rows($adm_o_q_res)) 
        {
            list($iOTPID, $vOTP, $Email) = sql_fetch_row($adm_o_q_res);
            $upadte_b_q = "UPDATE booking SET bverified='1' WHERE iBookingID='$bid'";
            sql_query($upadte_b_q, "");
            $update_otp_q = "UPDATE adm_otp SET cUsed='X' WHERE iOTPID='$iOTPID' ";
            sql_query($update_otp_q, "");
            $to = $Email;
            $subject = "Welcome Email";
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
            // More headers
            $headers .= 'From: thequotemasters.com <ops@janitorialquotemasters.com>' . "\r\n";
            $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
            $mail_content2 .= "<html>";
            $mail_content2 .= "<body>";
            $mail_content2 .= "<p>Hi,</p>";
            $mail_content2 .= "<p>WELCOME to the QUOTE MASTERS family where YOU are the MASTER of the leads you receive!  With Quote Master you will receive an amazing source of janitorial leads! </p>";
    
            $mail_content2 .= "<p>You receive the following information with each lead:  </p>";
            $mail_content2 .= "<ol>
                                <li>A verified appointment day and time for your meeting</li>
                                <li>Years of expertise</li>
                                <li>Current star rating</li>
                                <li>Name of the manager you will be meeting with</li>
                                <li>Day and time you selected to meet</li>
                               
                                </ol>  ";
            $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
            $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
            $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
            $mail_content2 .= "<p>Questions? Need help? Please</p>";
            $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
            $mail_content2 .= "<p>Quote Master</p>";
            $mail_content2 .= "</body>";
            $mail_content2 .= "</html>";
            $_q1= "select vEmail from customers where vEmail='$Email' and cMailsent='N' ";
            $_r1=sql_query($_q1,"check mail sent");
            if (sql_num_rows($_r1)) {
                //mail($to, $subject, $mail_content2, $headers);
                //Send_mail('', '', $to, '', "", "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $mail_content2, '');
                SendInBlueMail($subject, $to, $mail_content2, '', '','', "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com");
                sql_query("update customers set cMailsent='Y' where vEmail='$Email' ","update email status");         
            }


            if (!empty($APPOINTMENTS_DATA)) {
                for ($u = 0; $u < sizeof($APPOINTMENTS_DATA); $u++) {
                    //         [iApptID] => 406
            // [iBookingID] => 129
            // [iAreaID] => 32244
            // [iCustomerID] => 2
            // [dBookingDate] => 2024-11-24
            // [bverified] => 0
            // [iBookingCode] => 1
            // [dDateTime] => 2024-11-28 00:00:00
            // [iAppTimeID] => 7
            // [cService_status] => P
            // [cStatus] => A
                    $EVENT_DATE=date('Y-m-d',strtotime($APPOINTMENTS_DATA[$u]->dDateTime));
                    $EVENT_TIME=date('H:i',strtotime($TIME_ARR[$APPOINTMENTS_DATA[$u]->iAppTimeID]));
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
                    
                    $attendees = ['ops@thequotemasters.com',$APPOINTMENT_EMAIL];
                    
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
                    
                    //$response = $googleCalendar->CreateCalendarEventWithInvite($access_token, 'primary', $event_data, 0, $event_datetime, $timezone, $attendees);
                    //DFA($response);
                    //exit;

            
                }
            }
    
            echo 1;
            exit;
        } else {
            echo 0;
            exit;
        }

    }
}
// ALTER TABLE `quote_master`.`customers`
//   ADD COLUMN `cMailsent` varchar(255) NULL DEFAULT NULL AFTER `dtRegistration`;

?>