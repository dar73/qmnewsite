<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

function sendFCM($to, $BDATA)
{
    // FCM API Url
    $url = 'https://fcm.googleapis.com/fcm/send';

    // Put your Server Key here
    $apiKey = "AAAAzQoghps:APA91bH6C2hi1rBUBfXWhnAmcHqODdDX7q2HxFrXZ0-LkZCbcBEC2ceBMd_k9BECJKNwfY5WkJBhG-UGrsKBRsWX4H0C3GCTjOXomvTFav80c0QriRXfKKA0kQ-FWhiPDM_FA3L3oghQ";

    // Compile headers in one variable
    $headers = array(
        'Authorization:key=' . $apiKey,
        'Content-Type:application/json'
    );

    // Add notification content to a variable for easy reference
    $notifData = [
        'title' => "Lead Alert",
        'body' => "Test notification body",
        //  "image": "url-to-image",//Optional
        'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
    ];

    $dataPayload = [
        'to' => 'My Name',
        'points' => 80,
        'other_data' => $BDATA
    ];

    // Create the api body
    $apiBody = [
        'notification' => $notifData,
        'data' => $dataPayload, //Optional
        'time_to_live' => 600, // optional - In Seconds
        //'to' => '/topics/mytargettopic'
        //'registration_ids' = ID ARRAY
        'to' => $to
    ];

    // Initialize curl with the prepared headers and body
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

    // Execute call and save result
    $result = curl_exec($ch);
    print($result);
    // Close curl after call
    curl_close($ch);

    return $result;
}

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A'  ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$Lead_price = 0;

$CUSTOMER_ARR = $ADDRESS_ARR = array();
$cond2 = '';
$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}

$_q = "select iBookingID,iAreaID,dDate from booking where 1 and cStatus='A' and bverified='1' ";
// $_q = "select iBookingID,iAreaID,dDate from booking where cService_status='P' and DATE_FORMAT(dDate,'%Y-%m-%d') >=NOW() and cStatus='A' ";
$_r = sql_query($_q, "Select Pending Appointments");
while (list($BID, $iAreaID, $date) = sql_fetch_row($_r)) {

    $Leads_Ans = $Leads_Ans2 = array();
    //$BID = GetXFromYID("select iBookingID from appointments where iApptID='$iApptID' ");

    $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5') order by iQuesID";
    $q_r_L_Ans = sql_query($q_L_Ans, '');
    if (sql_num_rows($q_r_L_Ans)) {
        while ($row = sql_fetch_object($q_r_L_Ans)) {
            $Leads_Ans[] = $row;
        }
    }

    $_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3')";
    $_q_ans_r = sql_query($_q_ans, '');
    if (sql_num_rows($_q_ans_r)) {
        while ($row = sql_fetch_object($_q_ans_r)) {
            $Leads_Ans2[] = $row;
        }
    }


    // echo $iApptID;
    // exit;
    $AREAD_DETAILS = GetDataFromID('areas', 'id', $iAreaID);
    $q1 = "SELECT zip FROM areas WHERE id='$iAreaID'";
    $r1 = sql_query($q1);
    list($zip) = sql_fetch_row($r1);
    $q2 = "SELECT service_providers_id FROM service_providers_areas WHERE zip='$zip'";
    $r2 = sql_query($q2);
    while (list($providersID) = sql_fetch_row($r2)) {
       // echo $providersID;
        //echo '</br>';
        $data = array();
        //check if he has already purchase the lead from that booking ID
        $spcheck_q = "SELECT * FROM buyed_leads where  ivendor_id='$providersID' and ibooking_id='$BID' ";
        $spcheck_q_r = sql_query($spcheck_q, 'ERR.Checksp');
        if (!sql_num_rows($spcheck_q_r)) {
            $q = "select * from booking where 1  and iBookingID='$BID' and cStatus='A' and bverified='1'   ";
            $r = sql_query($q, "ERR.88");
            if (sql_num_rows($r)) {
                for ($i = 1; $o = sql_fetch_object($r); $i++) {
                    $Booking_no = $o->iBookingID;
                    $iNo_of_quotes = $o->iNo_of_quotes;
                    $iAreaID = $o->iAreaID;
                    $zip = $ADDRESS_ARR[$iAreaID]['zip'];
                    $state = $ADDRESS_ARR[$iAreaID]['state'];
                    $county = $ADDRESS_ARR[$iAreaID]['County_name'];
                    $city = $ADDRESS_ARR[$iAreaID]['city'];
                    $data = array('BID' => $Booking_no, 'ZIP' => $zip, 'STATE' => $state, 'COUNTY' => $county, 'CITY' => $city);
                }
            }

           // DFA($data);

            $q3 = "SELECT email_address,First_name,vFirebaseAuthToken FROM service_providers WHERE id='$providersID' and cAdmin_approval='A' and cStatus='A' ";
            $r3 = sql_query($q3);
            list($email, $SPNAME,$FIREBASEAUTHTOKEN) = sql_fetch_row($r3);
            $to = $FIREBASEAUTHTOKEN;
            $subject = "Leads Alert";

            //Get all pending appointments
            $_q4 = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >=NOW() and iBookingID='$BID' and cStatus!='X' order by dDateTime DESC";
           // echo $_q4;
            //echo '</br>';
            $_r4 = sql_query($_q4, "ERR.566");
            if (sql_num_rows($_r4)) {
                if (!is_null($to)) {
                    sendFCM($to,$data);
                    echo '</br>';
                    //DFA($data);
                    //echo $to;sssss
                    //Send_mail('', '', "$to", '', "", "darshankubal1@gmail.com,kvikrantrao1@gmail.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $MAIL_BODY, '');
                    //SendInBlueMail($subject, $to, $MAIL_BODY, '', '', '', "kvikrantrao1@gmail.com,gemma@teamleadgeneration.onmicrosoft.com");
                    //exit;
                    //$MID = NextID('Id', 'mailsent');
                    //sql_query("insert into mailsent values ($MID,$BID,'Y',NOW(),$providersID)", "mailsent table insert");
                    echo 'Success';
                }
            }

            //exit;

        }
    }
}
sql_close();
?>