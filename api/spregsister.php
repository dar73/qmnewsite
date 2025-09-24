<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../includes/ti-salt.php';
include '../phpmailer.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$token = (isset($request->token)) ? db_input2($request->token) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $first_name = (isset($request->first_name)) ? db_input2($request->first_name) : '';
    $last_name = (isset($request->last_name)) ? db_input2($request->last_name) : '';
    $c_name = (isset($request->cname)) ? db_input2($request->cname) : '';
    $phone = (isset($request->phone)) ? db_input2($request->phone) : '';
    $street = (isset($request->street)) ? db_input2($request->street) : '';
    $email = (isset($request->email)) ? db_input2($request->email) : '';
    $passwd1 = (isset($request->passwd1)) ? db_input2($request->passwd1) : '';
    $passwd2 = (isset($request->passwd2)) ? db_input2($request->passwd2) : '';
    $state_adr = (isset($request->state_adr)) ? db_input2($request->state_adr) : '';
    $county_adr = (isset($request->county_name_adr)) ? db_input2($request->county_name_adr) : '';
    $city_adr = (isset($request->city_adr)) ? db_input2($request->city_adr) : '';
    $source = (isset($request->source)) ? db_input2($request->source) : '';
    $plan = (isset($request->plan)) ? db_input2($request->plan) : 'S';
    $txtbox1 = '';
    $cmbindustrylist = (isset($request->cmbindustrylist)) ? $request->cmbindustrylist : '';
    if (!empty($cmbindustrylist))
        $txtbox1 = implode(",", $cmbindustrylist);
    $txt_max_leadsPerMonth = (isset($request->txt_max_leadsPerMonth)) ? db_input2($request->txt_max_leadsPerMonth) : '';
    $rdISBI = (isset($request->rdISBI)) ? db_input2($request->rdISBI) : '';
    //$recaptcha_response = (isset($request->g-recaptcha-response)) ? $request->g-recaptcha-response : '';
    if (!empty($first_name) && !empty($last_name) && !empty($c_name) && !empty($phone) && !empty($street) && !empty($email) && !empty($passwd1) && !empty($passwd2) && !empty($state_adr) && !empty($county_adr) && !empty($city_adr) && !empty($source) && !empty($rdISBI)) {
        // $state = $_POST['state;
        // $county_name = $_POST['county_name;
        $_q = "select * from service_providers where email_address='$email' and cStatus!='X' ";
        $_r = sql_query($_q);
        if (sql_num_rows($_r)) {
            $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Email already exists!!");
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode($returnArr);
            sql_close();
            exit;
        }

        $txtpassword = htmlspecialchars_decode($passwd2);
        $salt_obj = new SaltIT;
        $txtpassword = $salt_obj->EnCode($txtpassword);

        $str1 = $mail_content = '';
        $to = $email;
        $subject = "Email Verification";

        $vkey = md5(time() . $first_name);
        LockTable("service_providers");
        $txtspid = NextID('id', 'service_providers');
        // $q = "INSERT INTO service_providers(id,dDate,First_name, Last_name, company_name, phone, email_address,password,email_verify_key, email_verify, cStatus,street,state,county,city,cSource,cHaveBI,cCleaningStatus,cUsertype,vSnotes,vLeadPerWeek,vLeadPerMonth) VALUES ('$txtspid',NOW(),'$first_name','$last_name','$c_name','$phone','$email','$txtpassword','$vkey','0','I','$street','$state_adr','$county_adr','$city_adr','$source','$rdISBI','$cleaningstatus','$plan','$txtbox1','$txt_max_leadsPerweek','$txt_max_leadsPerMonth')";
        $q = "INSERT INTO service_providers(id,dDate,First_name, Last_name, company_name, phone, email_address,password,email_verify_key, email_verify, cStatus,street,state,county,city,cSource,cHaveBI,cUsertype,vSnotes,vLeadPerMonth) VALUES ('$txtspid',NOW(),'$first_name','$last_name','$c_name','$phone','$email','$txtpassword','$vkey','0','I','$street','$state_adr','$county_adr','$city_adr','$source','$rdISBI','$plan','$txtbox1','$txt_max_leadsPerMonth')";
        $result = sql_query($q);
        UnLockTable();
        //echo $result . '<br>' . Lastid();
        if ($result) {
            $lastid = Lastid();
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
            SendInBlueMail($subject, $to, $mail_content, '', '', '', "");
            $returnArr = array("ResponseCode" => "201", "Result" => "true", "ResponseMsg" => "Account created successfuly,please check your email you will receive a mail for account verification,required for login!!");
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode($returnArr);
            sql_close();
            exit;
        }
    } else {
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "some fields are missing!!");
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode($returnArr);
        sql_close();
        exit;
    }
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode($returnArr);
    sql_close();
    exit;
}
