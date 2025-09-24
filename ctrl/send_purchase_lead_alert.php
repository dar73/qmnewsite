<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$txtid = '425';



$SCHEDULE_ARR = GetDataFromID("appointments", "iApptID", $txtid);
$GET_AREA_ARRAY = array();
$Q = "SELECT id, zip, zipcode_name, city, state, County_name FROM areas";
$R = sql_query($Q);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($R)) {
    if (!isset($GET_AREA_ARRAY[$id]))
        $GET_AREA_ARRAY[$id] = array('id' => $id, 'zip' => $zip, 'zipcodename' => $zipcode_name, 'state' => $state, 'city' => $city, 'county_name' => $County_name);
}
$BOOKING_ARR = GetDataFromID("appointments", "iApptID", $txtid);
//DFA($BOOKING_ARR);
$areaId = $BOOKING_ARR[0]->iAreaID;
$BID = $BOOKING_ARR[0]->iBookingID;
$AREAD_DETAILS = GetDataFromID('areas', 'id', $areaId);

$Leads_Ans = $Leads_Ans2 = array();
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' and iQuesID not in ('8','7','5') ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}
$_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3') "; // multiple choice answer
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);


$mail_content = '<h3>Lead Details</h3>';
if (!empty($Leads_Ans)) {
    for ($i = 0; $i < count($Leads_Ans); $i++) {

        $mail_content .= '<div class="post clearfix pb-0">
            <div class="user-block">' . $Question_ARR[$Leads_Ans[$i]->iQuesID] . '</div>
            <p class="ml-5">
                ' . $Ans_ARR[$Leads_Ans[$i]->iAnswerID] . '
            </p>
        </div>';
    }
}

if (!empty($Leads_Ans2)) {
    $mail_content .= '<div class="post clearfix pb-0">
    <div class="user-block">' . $Question_ARR[$Leads_Ans2[0]->iQuesID] . '</div>
    
    <p class="ml-5">';
    $ANS_STR = '';
    $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
    foreach ($Ansarr as  $value) {
        $ANS_STR .= $Ans_ARR[$value] . ',';
    }


    $mail_content .= $ANS_STR . '</p>
    <p>
        
    </p>
</div>
<hr>';
}


$mail_content .= '<div class="col-12 col-md-4 order-1 order-md-2">
                                <h3 class="text-primary"><i class="fas fa-paint-brush"></i>Company Details</h3>
                                <div class="text-muted">
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Client Company</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vName_of_comapny . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">First Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vFirstname . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Last Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vLastname . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Position</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vPosition . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Email</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vEmail . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Phone</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vPhone . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Address</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vAddress . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">zip</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . str_pad($GET_AREA_ARRAY[$areaId]['zip'], 5, '0', STR_PAD_LEFT) . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">county</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $GET_AREA_ARRAY[$areaId]['county_name'] . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">City</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $GET_AREA_ARRAY[$areaId]['city'] . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">State</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $GET_AREA_ARRAY[$areaId]['state'] . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Time</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $TIMEPICKER_ARR[$BOOKING_ARR[0]->iAppTimeID] . '</b>
                                    </p>';


if (!empty($SCHEDULE_ARR)) {
    for ($i = 0, $j = 1; $i < count($SCHEDULE_ARR); $i++, $j++) {
        $mail_content.= '<hr>';
        $mail_content.= '<b class="d-block ml-md-0 col-7 col-md-12">Appointment </b>';

        $mail_content.= date('l' . ', ' . 'm/d/Y', strtotime($SCHEDULE_ARR[0]->dDateTime));
    }
}



$LEAD_ADD = " " . $AREAD_DETAILS[0]->city . " , " . $AREAD_DETAILS[0]->state . " , " . $AREAD_DETAILS[0]->zip;

$MAIL_BODY = file_get_contents(SITE_ADDRESS . 'ctrl/email_template_pl.php');
$MAIL_BODY = str_replace('<PNAME>', 'Darshan', $MAIL_BODY);
$MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
$MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);

echo GET_LEAD_MAIL_CONTENT(425,'DARSHAN');


SendInBlueMail("Lead Purchased", 'darshankubal1@gmail.com', $MAIL_BODY, '', '', '', "");


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
