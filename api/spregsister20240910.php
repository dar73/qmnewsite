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


$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$returnArr = array();
$data = array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
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
    $rdISBI = (isset($_POST['rdISBI'])) ? db_input2($_POST['rdISBI']) : '';
    $recaptcha_response = (isset($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : '';
    if (!empty($first_name) && !empty($last_name) && !empty($c_name) && !empty($phone) && !empty($street) && !empty($email) && !empty($passwd1) && !empty($passwd2) && !empty($state_adr) && !empty($county_adr) && !empty($city_adr) && !empty($source) && !empty($rdISBI)) {
        // $state = $_POST['state'];
        // $county_name = $_POST['county_name'];
        $_q = "select * from service_providers where email_address='$email' and cStatus!='X' ";
        $_r = sql_query($_q);
        if (sql_num_rows($_r)) {
            $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Email already exists!!");
            header('Content-Type: application/json');
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
        $q = "INSERT INTO service_providers(id,dDate,First_name, Last_name, company_name, phone, email_address,password,email_verify_key, email_verify, cStatus,street,state,county,city,cSource,cHaveBI) VALUES ('$txtspid',NOW(),'$first_name','$last_name','$c_name','$phone','$email','$txtpassword','$vkey','0','I','$street','$state_adr','$county_adr','$city_adr','$source','$rdISBI')";
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
            echo json_encode($returnArr);
            sql_close();
            exit;
        }
    } else {
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "some fields are missing!!");
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        sql_close();
        exit;
    }
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}

?>

