<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
include 'phpmailer.php';

$secretKey = '6Lf_pJInAAAAAJ76SrjSF7cVs0SJG5v7y3Db28KO';

// DFA($_POST);
// exit;

$customerID = '';
$redirect_URL = "verify_leads.php";
$home_url = 'index.php';
$areaID = (isset($_POST['areaid'])) ? db_input2($_POST['areaid']) : "";
$mail_content2=$mail_content='';
$recaptcha_response = (isset($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : '';
$q1 = (isset($_POST['q1'])) ? $_POST['q1'] : '';
$q2 = (isset($_POST['q2'])) ? $_POST['q2'] : '';
$q7 = (isset($_POST['q7'])) ? $_POST['q7'] : '';
$q5 = (isset($_POST['q5'])) ? $_POST['q5'] : '';
$q8 = (isset($_POST['q8'])) ? $_POST['q8'] : '';
$q3 = (isset($_POST['q3'])) ? $_POST['q3'] : '';
$q4 = (isset($_POST['q4'])) ? $_POST['q4'] : '';
$q10 = (isset($_POST['q10'])) ? $_POST['q10'] : '';
if (empty($areaID)) {
    header('location:' . $home_url);
    exit;
}

$num_of_Q = (isset($_POST['num_of_Q'])) ? db_input2($_POST['num_of_Q']) : '';
$name_of_company = (isset($_POST['name_of_company'])) ? db_input2($_POST['name_of_company']) : '';
$first_name = (isset($_POST['first_name'])) ? db_input2($_POST['first_name']) : '';
$last_name = (isset($_POST['last_name'])) ? db_input2($_POST['last_name']) : '';
$position = (isset($_POST['position'])) ? db_input2($_POST['position']) : '';
$phone = (isset($_POST['phone'])) ? db_input2($_POST['phone']) : '';
$email = (isset($_POST['email'])) ? db_input2($_POST['email']) : '';
$email2 = (isset($_POST['email2'])) ? db_input2($_POST['email2']) : '';
$Notes = (isset($_POST['Notes'])) ? db_input2($_POST['Notes']) : '';
$c_address = (isset($_POST['c_address'])) ? db_input2($_POST['c_address']) : '';
$cmbindustry = (isset($_POST['cmbindustry'])) ? db_input2($_POST['cmbindustry']) : '';//MODIFIED 2023/11/19

if (empty($name_of_company) || empty($first_name) || empty($last_name) || empty($position) || empty($phone) || empty($email) || empty($c_address)) {
    header('location:' . $home_url);
    exit;
}



$api_url = 'https://www.google.com/recaptcha/api/siteverify';
$resq_data = array(
    'secret' => $secretKey,
    'response' => $_POST['g-recaptcha-response'],
    'remoteip' => $_SERVER['REMOTE_ADDR']
);

$curlConfig = array(
    CURLOPT_URL => $api_url,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $resq_data
);

$ch = curl_init();
curl_setopt_array($ch, $curlConfig);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response);

if ($responseData->success) {
    $area_q = "SELECT zip,city,state FROM areas WHERE id='$areaID'";
    $area_q = "SELECT 
            z.zip_code,
            ci.city_name,
            s.state_name,
            c.country_name
        FROM 
            zip_codes z
        JOIN 
            cities ci ON z.city_id = ci.city_id
        JOIN 
            states s ON ci.state_id = s.state_id
        JOIN 
            countries c ON ci.country_id = c.country_id where  z.zip_code = '$areaID'";
    $area_r = sql_query($area_q, "");
    list($zip, $city, $state, $country) = sql_fetch_row($area_r);
    $cust_u_exist_q = "SELECT iCustomerID FROM customers WHERE vEmail='$email' LIMIT 1";
    $cust_u_exist_q_res = sql_query($cust_u_exist_q, "");

    $to = $email;
    $subject = "Welcome Email";
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
    $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
    $mail_content2 .= "<html>";
    $mail_content2 .= "<body>";
    $mail_content2 .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;"><p>Hi,</p>';
    $mail_content2 .= "<p>Welcome to Quote Masters where “YOU are the MASTER of the Janitorial Quotes you get!”</p>";
    $mail_content2 .= "<p>You have completed the request form and now you will receive  bids for your janitorial needs. Each cleaning company that will visit your office has been selected because they have some of the highest ratings on Google / Yelp. </p>";
    $mail_content2 .= "<p>Shortly once our Customer Service Team selects your cleaners you will receive the Quote Master Report for each of them.</p>";
    $mail_content2 .= "<p>You will know the following:</p>";
    $mail_content2 .= "<ol>
                                <li>Company name and contact information</li>
                                <li>Years of expertise</li>
                                <li>Current star rating</li>
                                <li>Name of the manager you will be meeting with</li>
                                <li>Day and time you selected to meet</li>
                               
                                </ol>  ";
    $mail_content2 .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';
    $mail_content2 .= "<p>You can Login to your account using your email and password: qm#1234 from below link </p>";
    $mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit thequotemasters.com to connect </a></p>';
    $mail_content2 .= "<p>Questions? Need help? Please</p>";
    $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
    $mail_content2 .= "<p>Quote Masters</p>";
    $mail_content2 .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
    $mail_content2 .= "</body>";
    $mail_content2 .= "</html>";


    if (sql_num_rows($cust_u_exist_q_res)) {
        list($customerID) = sql_fetch_row($cust_u_exist_q_res);
    } else {
        LockTable("customers");
        $customerID = NextID("iCustomerID", "customers");
        $cust_q = "INSERT INTO customers(iCustomerID, vFirstname, vLastname, vName_of_comapny, vAddress, vPosition, vEmail, vEmail2, vPassword, vPhone, dtRegistration, cMailsent, cStatus)  VALUES ('$customerID','$first_name','$last_name','$name_of_company','$c_address','$position','$email','$email2','hLn15@)T~r7m}U{o-^q','$phone',NOW(),'N','A')";
        sql_query($cust_q, "");
        // mail($to, $subject, $mail_content2, $headers);
    }
    $lockq = "LOCK TABLE booking WRITE , adm_otp WRITE ";
    sql_query($lockq, "lock tables");
    $bid = NextID("iBookingID", "booking");
    $otpID = NextID("iOTPID", "adm_otp");
    $otp = rand(1000, 100000);
    $dtTo = date('Y-m-d H:i:s', strtotime('+50 minutes'));
    $qr2 = "INSERT INTO booking VALUES ('$bid','0','$areaID','$customerID','$num_of_Q','Y','P','0','001',NOW(),'$Notes','A')";
    sql_query($qr2, "");
    $query = array();


    if (!empty($q3)) {
        $q2ans = implode(",", $q3);

        $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','3','$q2ans',NOW(),'NA','C','A')";
    }
    if (!empty($q4)) {
        $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','4','$q4',NOW(),'NA','C','A')";
    }
    $qr4 = "INSERT INTO adm_otp VALUES ('$otpID',NOW(),'A','$customerID','A','$customerID','$bid','$otp','$email',NOW(),'$dtTo',NOW(),'C','1','A')";
    sql_query($qr4, "");
    UnlockTable();
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','1','$q1',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','2','$q2',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','5','$q5',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','7','$q7',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','8','$q8',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','10','$q10',NOW(),'NA','C','A')";
    $query[] = "INSERT INTO leads_answersheet VALUES ('$bid','$customerID','9','$cmbindustry',NOW(),'NA','C','A')"; ////MODIFIED 2023/11/19
    //LockTable("schedule");
    $lockq = "LOCK TABLE schedule WRITE , appointments WRITE ";
    sql_query($lockq, "lock tables");
    for ($i = 0, $j = 1; $i < $num_of_Q; $i++, $j++) {
        $Ndate = $_POST['date' . $j];
        $dateArr = explode('-', $Ndate);
        $iScheduleID = NextID("iScheduleID", "schedule");
        //$_q = "INSERT INTO schedule(iBookingID, Ddatetime,iScheduleID) VALUES ('$bid','" . $_POST['date' . $j] . "','$iScheduleID')";
        //sql_query($_q,'');
        $ApptID = NextID('iApptID', 'appointments');
        $_q2 = "INSERT INTO appointments VALUES ('$ApptID','$bid','0','$areaID','$customerID',NOW(),'0','1','" . $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1] . "','" . $_POST['Time' . $j] . "','P','A')";
        sql_query($_q2, '');
    }
    UnlockTable();
    foreach ($query as $key => $value) {
        sql_query($value, "Lead_answersheet");
    }
    $EncryptID = EncryptStr($bid);
    $to = $email;
    $subject = "Lead Verification";
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
    $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";

    //mail($to, $subject, $message, $headers);
    $mail_content .= "<html>";
    $mail_content .= "<body>";
    $mail_content .= '<div style="background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;"><p>Hi, ' . $first_name . '</p>';
    $mail_content .= '<p>Welcome to Quote Masters!</p>';
    $mail_content .= "<p>We have received your requirements for a janitorial quote. </p>";
    $mail_content .= '<p>Verification code for your requirement is : ' . $otp . '</p>';
    $mail_content .= '<p>Enter this verification code at the following link : </p>';
    $mail_content .= '<p><a href="https://thequotemasters.com/verify_leads.php?key=' . $EncryptID . '">Click here to verify your request</a></p>';
    $mail_content .= "<p>Questions? Need help? Please</p>";
    $mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
    $mail_content .= "<p>At your service,<br>Quote Master</p>";
    $mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
    $mail_content .= "</body>";
    $mail_content .= "</html>";
    //mail($to, $subject, $mail_content, $headers);
    //Send_mail('', '', $to, '', "", "darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $mail_content, '');
    SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com,vik@teamleadgeneration.onmicrosoft.com,gemma@teamleadgeneration.onmicrosoft.com');

    $curl = curl_init();

    $sms_content = 'Verification code for your requirement is : ' . $otp;

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.tiltx.com/sms/send-sms',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
        "to":"' . $phone . '",
         "text":"' . $sms_content . '"
    }',
        CURLOPT_HTTPHEADER => array(
            'x-api-key: JzSpDZu1wx8fN6qNlMXxV4WmhpaOBX1k3O8DO9Eb',
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);


    header('location:' . $redirect_URL . '?key=' . $EncryptID);
    exit;

}else{
    header('location:' . $home_url . '?error=recaptcha');
    exit;
}


 

//$qr3 = "INSERT INTO `schedule`(`iBookingID`, `Ddatetime`) VALUES ('[value-1]','[value-2]')";

?>
