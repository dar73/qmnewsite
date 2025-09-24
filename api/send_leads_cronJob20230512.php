<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$_q = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' ";
$_r = sql_query($_q, "Select Pending Appointments");
while (list($iApptID, $iAreaID, $date, $TIMEID) = sql_fetch_row($_r)) {

    $Leads_Ans = $Leads_Ans2 = array();
    $BID = GetXFromYID("select iBookingID from appointments where iApptID='$iApptID' ");
    $Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
    $Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
    $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
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
        $q3 = "SELECT email_address FROM service_providers WHERE id='$providersID'";
        $r3 = sql_query($q3);
        list($email) = sql_fetch_row($r3);
        $to = $email;
        $subject = "Leads Alert";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        //$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content = "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Hello Quote Master Provider!!</p>";
        $mail_content .= "<p>City, State: {" . $AREAD_DETAILS[0]->city . "," . $AREAD_DETAILS[0]->state . "}</p>";
        $mail_content .= "<p>Zip Code Match: {" . $AREAD_DETAILS[0]->zip . "}</p>";
        $mail_content .= "<p>appointment date: {" . date('m/d/Y', strtotime($date)) . "}</p>";
        $mail_content .= '<p class="pb-3 border-bottom">appointment time: {' . $TIMEPICKER_ARR[$TIMEID] . '}</p>';
        if (!empty($Leads_Ans)) {
            for ($i = 0; $i < count($Leads_Ans); $i++) {
                $mail_content .= '<p class"mt-2"><b>' . $Question_ARR[$Leads_Ans[$i]->iQuesID] . '</b></p>';
                $mail_content .= '<p class"mb-2">  ' .  $Ans_ARR[$Leads_Ans[$i]->iAnswerID] . '</p>';
            }
        }

        // $mail_content .= "<p>Frequency of Service: {" . $BOOKING_DATA[0]->vAns1 . "}</p>";
        // $mail_content .= "<p>Note on current service: {" . $BOOKING_DATA[0]->vAns3 . "}</p>";
        $mail_content .= '<p class="pt-3 border-top">If you would like this appointment confirm here that you understand the location, appointment date and time.  <a href="https://thequotemasters.com/">   check here</a></p>';
        $mail_content .= '<br><p>No credit given if you miss the set appt date and time confirm you understand here  <a href="https://thequotemasters.com/">check here</a></p>';
        $mail_content .= '<br><p>If youd like to purchase this appointment for {$125} press here  <a href="https://thequotemasters.com/">press here</a></p>';
        $mail_content .= "<br><p>Questions? Need help? Please</p>";
        $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content .= "<p>Quote Master</p>";
        $mail_content .= "</body>";
        $mail_content .= "</html>";

        //echo $mail_content;
        mail($to, $subject, $mail_content, $headers);
        $MID=NextID('Id', 'mailsent');
        sql_query("insert into mailsent values ($MID,$iApptID,'Y',NOW(),$providersID)","mailsent table insert");
        echo 'Success';
        //exit;
    }
}
?>