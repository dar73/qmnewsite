<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');

$customerID = '';
$redirect_URL = "verify_leads.php";
$areaID = $_POST['areaid'];
$mail_content2=$mail_content='';
$q1 = (isset($_POST['q1'])) ? $_POST['q1'] : '';
$q2 = (isset($_POST['q2'])) ? $_POST['q2'] : '';
$q7 = (isset($_POST['q7'])) ? $_POST['q7'] : '';
$q5 = (isset($_POST['q5'])) ? $_POST['q5'] : '';
$q8 = (isset($_POST['q8'])) ? $_POST['q8'] : '';
$num_of_Q = $_POST['num_of_Q'];
$q3 = (isset($_POST['q3'])) ? $_POST['q3'] : '';
$q4 = (isset($_POST['q4'])) ? $_POST['q4'] : '';
$name_of_company = db_input($_POST['name_of_company']);
$first_name = db_input($_POST['first_name']);
$last_name = db_input($_POST['last_name']);
$position = db_input($_POST['position']);
$phone = db_input($_POST['phone']);
$email = db_input($_POST['email']);
$c_address = db_input($_POST['c_address']);
$area_q = "SELECT zip,city,state FROM areas WHERE id='$areaID'";
$area_r = sql_query($area_q, "");
list($zip, $city, $state) = sql_fetch_row($area_r);
$cust_u_exist_q = "SELECT iCustomerID FROM customers WHERE vEmail='$email' LIMIT 1";
$cust_u_exist_q_res = sql_query($cust_u_exist_q, "");

$to = $email;
$subject = "Welcome Email";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <ops@janitorialquotemasters.com>' . "\r\n";
$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
$mail_content2 .= "<html>";
$mail_content2 .= "<body>";
$mail_content2 .= "<p>Hi,</p>";
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
$mail_content2 .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
$mail_content2 .= "<p>Questions? Need help? Please</p>";
$mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
$mail_content2 .= "<p>Quote Master</p>";
$mail_content2 .= "</body>";
$mail_content2 .= "</html>";


if (sql_num_rows($cust_u_exist_q_res)) {
    list($customerID) = sql_fetch_row($cust_u_exist_q_res);
} else {
    LockTable("customers");
    $customerID = NextID("iCustomerID", "customers");
    $cust_q = "INSERT INTO customers  VALUES ('$customerID','$first_name','$last_name','$name_of_company','$c_address','$position','$email','1f5f8934aab7f6813f308f34dfe050b9','$phone',NOW(),'N','A')";
    sql_query($cust_q, "");
   // mail($to, $subject, $mail_content2, $headers);
}
$lockq = "LOCK TABLE booking WRITE , adm_otp WRITE ";
sql_query($lockq, "lock tables");
$bid = NextID("iBookingID", "booking");
$otpID = NextID("iOTPID", "adm_otp");
$otp = rand(1000, 100000);
$dtTo = date('Y-m-d H:i:s', strtotime('+50 minutes'));
$qr2 = "INSERT INTO booking VALUES ('$bid','$areaID','$customerID','$num_of_Q','Y','P','0','001',NOW())";
sql_query($qr2, "");
$query = array();


if (!empty($q3)) {
    $q2ans= implode(",", $q3);
    
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
//LockTable("schedule");
$lockq = "LOCK TABLE schedule WRITE , appointments WRITE ";
sql_query($lockq, "lock tables");
for ($i = 0, $j = 1; $i < $num_of_Q; $i++, $j++) {
    $Ndate = $_POST['date' . $j];  
    $dateArr = explode('-', $Ndate);
    $iScheduleID = NextID("iScheduleID", "schedule");
    $_q = "INSERT INTO schedule(iBookingID, Ddatetime,iScheduleID) VALUES ('$bid','" . $_POST['date' . $j] . "','$iScheduleID')";
    sql_query($_q,'');
    $ApptID=NextID('iApptID','appointments');
    $_q2= "INSERT INTO appointments VALUES ('$ApptID','$bid','$areaID','$customerID',NOW(),'0','1','". $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1]. "','" . $_POST['Time' . $j] . "','P')";
    sql_query($_q2,'');
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
$headers .= 'From: <ops@janitorialquotemasters.com>' . "\r\n";
$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";

//mail($to, $subject, $message, $headers);
$mail_content .= "<html>";
$mail_content .= "<body>";
$mail_content .= '<p>Hi, ' . $first_name . '</p>';
$mail_content .= '<p>Welcome to Quote Masters</p>';
$mail_content .= "<p>We have received your requirements for a janitorial quote . </p>";
$mail_content .= '<p>Verification code for your requirement is : ' . $otp . '</p>';
$mail_content .= '<p>Enter this verification code at the following link : </p>';
$mail_content .= '<p><a href="https://thequotemasters.com/verify_leads.php?key=' . $EncryptID . '">Click here to verify your request</a></p>';
$mail_content .= "<p>Questions? Need help? Please</p>";
$mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
$mail_content .= "<p>At your service,<br>Quote Master</p>";
$mail_content .= "</body>";
$mail_content .= "</html>";
mail($to, $subject, $mail_content, $headers);

$curl = curl_init();

$sms_content= 'Verification code for your requirement is : ' . $otp ;

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
    "to":"'.$phone.'",
     "text":"'.$sms_content.'"
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

//$qr3 = "INSERT INTO `schedule`(`iBookingID`, `Ddatetime`) VALUES ('[value-1]','[value-2]')";

?>

// [areaid] => 1043
//     [num_of_Q] => 1
//     [q1] => 101
//     [q2] => 202
//     [q3] => Array
//         (
//             [0] => 301
//             [1] => 302
//             [2] => 303
//         )

//     [q4] => 401
//     [q5] => 501
//     [date1] => 2023-01-27T12:00
//     [q7] => 701
//     [name_of_company] => gj
//     [first_name] => hjhj
//     [last_name] => ghg
//     [position] => gjk
//     [phone] => bn
//     [email] => hg