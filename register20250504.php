<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
require_once('includes/ti-salt.php');
include 'phpmailer.php';
$cartArr = isset($_SESSION[COVERAGE]) ? $_SESSION[COVERAGE] : $_SESSION[COVERAGE] = array();
$success_url = 'plogin.php';
$failure_url = 'vendor_register.php';
// DFA($_SESSION);
// DFA($_POST);
// exit;
//  [txtbox1] => nnn
//     [txt_max_leadsPerweek] => 1
//     [txt_max_leadsPerMonth] => 4
$secretKey = '6Lf_pJInAAAAAJ76SrjSF7cVs0SJG5v7y3Db28KO';

$first_name = (isset($_POST['first_name'])) ? db_input2($_POST['first_name']) : '';
$last_name = (isset($_POST['last_name'])) ? db_input2($_POST['last_name']) : '';
$c_name = (isset($_POST['cname'])) ? db_input2($_POST['cname']) : '';
$phone = (isset($_POST['phone'])) ? db_input2($_POST['phone']) : '';
$street = (isset($_POST['street'])) ? db_input2($_POST['street']) : '';
$email = (isset($_POST['email'])) ? db_input2($_POST['email']) : '';
$passwd1 = (isset($_POST['passwd1'])) ? db_input2($_POST['passwd1']) : '';
$passwd2 = (isset($_POST['passwd2'])) ? db_input2($_POST['passwd2']) : '';
$state_adr = (isset($_POST['state_adr'])) ? db_input2($_POST['state_adr']) : '';
$county_adr = (isset($_POST['county_name_adr'])) ? db_input2($_POST['county_name_adr']) : '';
$city_adr = (isset($_POST['city_adr'])) ? db_input2($_POST['city_adr']) : '';
$source = (isset($_POST['source'])) ? db_input2($_POST['source']) : '';
$plan = (isset($_POST['plan'])) ? db_input2($_POST['plan']) : 'S';
$rdISBI = (isset($_POST['rdISBI'])) ? db_input2($_POST['rdISBI']) : '';

$txtbox1 = '';//(isset($_POST['txtbox1'])) ? db_input2($_POST['txtbox1']) : '';
$cmbindustrylist = (isset($_POST['cmbindustrylist'])) ? $_POST['cmbindustrylist'] : '';

if (!empty($cmbindustrylist))
    $txtbox1 = implode(",", $cmbindustrylist);
$txt_max_leadsPerweek = (isset($_POST['txt_max_leadsPerweek'])) ? db_input2($_POST['txt_max_leadsPerweek']) : '';
$txt_max_leadsPerMonth = (isset($_POST['txt_max_leadsPerMonth'])) ? db_input2($_POST['txt_max_leadsPerMonth']) : '';

$cleaningstatus = (isset($_POST['cleaningstatus'])) ? db_input2($_POST['cleaningstatus']) : 'N';
$recaptcha_response = (isset($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : '';
if (!empty($first_name) && !empty($last_name) && !empty($c_name) && !empty($phone) && !empty($street) && !empty($email) && !empty($passwd1) && !empty($passwd2) && !empty($state_adr) && !empty($county_adr) && !empty($city_adr) && !empty($source) && !empty($rdISBI)) {
    // $state = $_POST['state'];
    // $county_name = $_POST['county_name'];
    $txtpassword = htmlspecialchars_decode($passwd2);
    $salt_obj = new SaltIT;
    $txtpassword = $salt_obj->EnCode($txtpassword);

    $str1 = $mail_content = '';
    $to = $email;
    $subject = "Email Verification";



    // Google reCAPTCHA verification API Request  
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

    // Decode JSON data of API response in array  
    $responseData = json_decode($response);

    // DFA($responseData);
    // exit;
    $NOW = NOW;

    // If the reCAPTCHA API response is valid  
    if ($responseData->success) {
        $vkey = md5(time() . $first_name);
        LockTable("service_providers");
        $txtspid = NextID('id', 'service_providers');
        $q = "INSERT INTO service_providers(id,dDate,First_name, Last_name, company_name, phone, email_address,password,email_verify_key, email_verify, cStatus,street,state,county,city,cSource,cHaveBI,cCleaningStatus,cUsertype,vSnotes,vLeadPerMonth) VALUES ('$txtspid','$NOW','$first_name','$last_name','$c_name','$phone','$email','$txtpassword','$vkey','0','I','$street','$state_adr','$county_adr','$city_adr','$source','$rdISBI','$cleaningstatus','$plan','$txtbox1','$txt_max_leadsPerMonth')";
        $result = sql_query($q);
        UnLockTable();
        //echo $result . '<br>' . Lastid();
        if ($result) {
            //$lastid = Lastid();
            //LockTable('coverages');
            $lockq = "LOCK TABLE coverages WRITE , areas WRITE ,service_providers WRITE,service_providers_areas WRITE";
            sql_query($lockq, "lock tables");
            if (isset($_SESSION[COVERAGE])) {
                foreach ($_SESSION[COVERAGE] as $key => $value) {
                    $icoverageID = NextID('iCoverageId', 'coverages'); //icrementing the ID of coverages table

                    $str1 = '';
                    $state = " state='$key' ";

                    $str1 .= "  AND  County_name IN ('" . implode("','", $value['county']) . "')";

                    $str2 = '';
                    if (!empty($value['city'])) {
                        //$cityarray = explode(',', $cityarr);
                        $str2 .= "  AND  city  IN ('" . implode("','", $value['city']) . "')";
                        sql_query("INSERT INTO coverages(iCoverageId, iproviderID,vStates,vCounties,vCities) VALUES ('$icoverageID','$txtspid','$key','" . implode(",", $value['county']) . "','" . implode(",", $value['city']) . "')");
                    } else {
                        sql_query("INSERT INTO coverages(iCoverageId, iproviderID,vStates,vCounties,vCities) VALUES ('$icoverageID','$txtspid','$key','" . implode(",", $value['county']) . "','')");
                    }

                    $Getzipq = "SELECT zip,id FROM areas WHERE " . $state . $str1 . $str2;

                    $GetzipqR = sql_query($Getzipq);
                    while ($R = sql_fetch_assoc($GetzipqR)) {
                        //array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
                        sql_query("INSERT INTO service_providers_areas( service_providers_id, zip) VALUES ('$txtspid','" . $R['zip'] . "')");
                    }
                }
            }
            unset($_SESSION[COVERAGE]);
            UnlockTable();
            $mail_content = '<html>
                                <body>
                                    <p>Hi,</p>
                                    <p>Thank you for creating a QuoteMaster account. For your security, please verify your account by clicking the link below.</p>
                                    <p><a href="https://thequotemasters.com/verify.php?key=' . $vkey . '">Click here to verify your email</a></p>
                                    <p>Questions? Need help? Please</p>
                                    <p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>
                                    <p>Happy Bidding<br>Quote Master</p>
                                </body>
                                </html>';
            // mail($to, $subject, $mail_content, $headers);
            //Send_mail('', '', $to, '', '', '', $subject, $mail_content, '');
            SendInBlueMail($subject, $to, $mail_content, '', '', '', '');
            $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'platinumupgrademail.php');
            SendInBlueMail("Platinum Upgrade",$to, $MAIL_BODY, '', '', '', "kvikrantrao1@gmail.com,gemma@teamleadgeneration.onmicrosoft.com,service@thequotemasters.com");

            header('location:' . $success_url . '?err=444');
            exit;
        }
    } else {
        header('location:' . $failure_url . '?err=23');
        exit;
    }
} else {
    header('location:' . $failure_url . '?err=32');
    exit;
}
