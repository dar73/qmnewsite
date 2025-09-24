<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['bid'])) {
    $bid = $_POST['bid'];
    $BOOKING_DATA = GetDataFromID('booking', 'iBookingID', $bid);
    $areaID = $BOOKING_DATA[0]->iAreaID;
    $AREAD_DETAILS = GetDataFromID('areas', 'id', $areaID);
    $q1 = "SELECT zip FROM areas WHERE id='$areaID'";
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
        $headers .= 'From: <ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content .= "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Hello Quote Master Provider!!</p>";
        $mail_content .= "<p>City, State: {" . $AREAD_DETAILS[0]->city . "," . $AREAD_DETAILS[0]->state . "}</p>";
        $mail_content .= "<p>Zip Code Match: {" . $AREAD_DETAILS[0]->zip . "}</p>";
        $mail_content .= "<p>Frequency of Service: {" . $BOOKING_DATA[0]->vAns1 . "}</p>";
        $mail_content .= "<p>Note on current service: {" . $BOOKING_DATA[0]->vAns3 . "}</p>";
        $mail_content .= '<p>If you would like this appointment confirm here that you understand the location, appointment date and time.<a href="https://thequotemasters.com/">check here</a></p>';
        $mail_content .= '<p>No credit given if you miss the set appt date and time confirm you understand here  <a href="https://thequotemasters.com/">check here</a></p>';
        $mail_content .= '<p>If youd like to purchase this appointment for {$125} press here  <a href="https://thequotemasters.com/">press here</a></p>';
        $mail_content .= "<p>Questions? Need help? Please</p>";
        $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content .= "<p>Quote Master</p>";
        $mail_content .= "</body>";
        $mail_content .= "</html>";
        mail($to, $subject, $mail_content, $headers);
    }
}
