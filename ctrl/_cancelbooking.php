<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$edit_url = '_debitamt.php';
$APP_ID=$_POST['appid'];
$SP_ID=$_POST['spid'];

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

//$PREMIUM_SP=GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cPlatinumAgreement='Y' and cUsertype='P' and id=578","3");
//COPY TO  BACKUP TABLE

$_q1="INSERT INTO buyed_leads_backup(dDate, ivendor_id, ibooking_id, iApptID, fAmt, vTransactionID)
SELECT dDate, ivendor_id, ibooking_id, iApptID, fAmt, vTransactionID
FROM buyed_leads
WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID";
sql_query($_q1);

$q3 = "SELECT email_address,First_name FROM service_providers WHERE id='$SP_ID'  ";
$r3 = sql_query($q3);
list($SP_EMAIL, $SPNAME) = sql_fetch_row($r3);

$APPOINTMENTS_DATA=GetDataFromCOND('appointments'," and iApptID='$APP_ID'");
$ZIP_CODE=$APPOINTMENTS_DATA[0]->vZip;

$_qa = "SELECT 
        z.zip_code,
        c.country_name,
        s.state_name,
        ci.city_name
    FROM 
        zip_codes z
    JOIN 
        cities ci ON z.city_id = ci.city_id
    JOIN 
        states s ON ci.state_id = s.state_id
    JOIN 
        countries c ON ci.country_id = c.country_id where 1 and z.zip_code='$ZIP_CODE' ";
$_qr = sql_query($_qa);
$z=$CN=$ST=$CT = '';
//$AREAD_DETAILS = GetDataFromID('areas', 'id', $APPOINTMENTS_DATA[0]->iAreaID);
if(sql_num_rows($_qr)) {
    list($z,$CN,$ST,$CT)=sql_fetch_row($_qr);
    //$AREAD_DETAILS = sql_fetch_all($_qr);
} 

$LEAD_ADD = " " . $CT . " , " . $ST . " , " . $ZIP_CODE;

//SELECT `iApptID`, `iBookingID`, `iAreaID`, `iCustomerID`, `dBookingDate`, `bverified`, `iBookingCode`, `dDateTime`, `iAppTimeID`, `cService_status`, `cStatus` FROM `appointments` WHERE 1

//SELECT  `vFirstname`, `vLastname`, `vName_of_comapny`, `vAddress`, `vPosition`, `vEmail`, `vPassword`, `vPhone`, `dtRegistration`, `cMailsent`, `cStatus` FROM `customers` WHERE 

$CUSTID=$APPOINTMENTS_DATA[0]->iCustomerID;

$CUST_NAME=GetXFromYID("select concat(vFirstname,' ',vLastname) from customers where iCustomerID='$CUSTID'");

$SCHEDULE= "<p>Date: " . date('m/d/Y', strtotime($APPOINTMENTS_DATA[0]->dDateTime)) . "  @ " . $TIMEPICKER_ARR[$APPOINTMENTS_DATA[0]->iAppTimeID] . "</p>";

$MAIL_BODY = '';
$MAIL_BODY = file_get_contents(SITE_ADDRESS . 'app_cancel_alert.php');
$MAIL_BODY = str_replace('<SPNAME>', $SPNAME, $MAIL_BODY);
$MAIL_BODY = str_replace('<LOCATION>', $LEAD_ADD, $MAIL_BODY);
$MAIL_BODY = str_replace('<DTIME>', $SCHEDULE, $MAIL_BODY);
$MAIL_BODY = str_replace('<CNAME>', $CUST_NAME, $MAIL_BODY);

SendInBlueMail("Appointment cancelled", $SP_EMAIL, $MAIL_BODY, '', '', '', "darshankubal1@gmail.com,michael2@thequotemasters.com");

//Delete from buyed leads
$_q2="delete from buyed_leads
WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID";
sql_query($_q2);

//set appointment status to X

sql_query("update appointments set cService_status='P' where iApptID=$APP_ID");

sql_query("UPDATE platinum_purchase_leads SET cStatus='X' WHERE ivendor_id =$SP_ID AND iApptID =$APP_ID");


echo '1~~*~~Booking Cancelled!!';
exit;



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
