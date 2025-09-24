<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
require_once('includes/ti-salt.php');
include 'phpmailer.php';
$cartArr = isset($_SESSION[COVERAGE]) ? $_SESSION[COVERAGE] : $_SESSION[COVERAGE]=array();
// DFA($_SESSION);
// DFA($_POST);
if (isset(
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['cname'],
    $_POST['phone'],
    $_POST['street'],
    $_POST['email'],
    $_POST['passwd1'],
    $_POST['passwd2'],
    $_POST['state_adr'],
    $_POST['county_name_adr'],
    $_POST['city_adr']

)) {
    $first_name = db_input($_POST['first_name']);
    $last_name = db_input($_POST['last_name']);
    $c_name = db_input($_POST['cname']);
    $phone = db_input($_POST['phone']);
    $street = db_input($_POST['street']);
    $email = db_input($_POST['email']);
    $passwd1 = db_input($_POST['passwd1']);
    $passwd2 = db_input($_POST['passwd2']);
    $state_adr = db_input($_POST['state_adr']);
    $county_adr = db_input($_POST['county_name_adr']);
    $city_adr = db_input($_POST['city_adr']);
    // $state = $_POST['state'];
    // $county_name = $_POST['county_name'];
    $txtpassword = htmlspecialchars_decode($passwd2);
    $salt_obj = new SaltIT;
    $txtpassword = $salt_obj->EnCode($txtpassword);
    // echo $txtpassword;
    // exit;
    //$txtpassword = md5($txtpassword);
   // $cityarr = $_POST['city'];
    $str1 = $mail_content = '';
    $to = $email;
    $subject = "Email Verification";
    // Always set content-type when sending HTML email
    // $headers = "MIME-Version: 1.0" . "\r\n";
    // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // // More headers
    // $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
    // $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";

    //mail($to, $subject, $message, $headers);

   
   
    $data = array();
    $q = "SELECT * FROM service_providers WHERE email_address='$email' ";
    $r = sql_query($q);
    if (sql_num_rows($r) > 0) {
        echo '<script>
        alert("Email address is already registered with quote master .Please choose another email");
        window.location.href = "vendor_register.php";
        </script>';

        //header('location:vendor_register.php');
        exit;
    }
    // if ($passwd1 != $passwd2) {

    //     echo '<script>
    //     alert("Both the Password should match");
    //     window.location.href = "vendor_register.php";
    //     </script>';

    //     //header('location:new_vendor.php');
    //     exit;
    // }
    $vkey = md5(time() . $first_name);
    LockTable("service_providers");
    $q = "INSERT INTO service_providers( dDate,First_name, Last_name, company_name, phone, email_address,password,email_verify_key, email_verify, cStatus,street,state,county,city) VALUES (NOW(),'$first_name','$last_name','$c_name','$phone','$email','$txtpassword','$vkey','0','I','$street','$state_adr','$county_adr','$city_adr')";
    $result = sql_query($q);
    //echo $result . '<br>' . Lastid();
    if ($result) {
        $lastid = Lastid();
        UnLockTable();
        //LockTable('coverages');
        $lockq = "LOCK TABLE coverages WRITE , areas WRITE ,service_providers WRITE,service_providers_areas WRITE";
        sql_query($lockq, "lock tables");
        if (isset($_SESSION[COVERAGE])) {
            foreach ($_SESSION[COVERAGE] as $key => $value) {
                $icoverageID=NextID('iCoverageId', 'coverages');//icrementing the ID of coverages table

                $str1='';
                $state = " state='$key' ";

                $str1 .= "  AND  County_name IN ('" . implode("','", $value['county']) . "')";

                $str2 = '';
                if (!empty($value['city'])) {
                    //$cityarray = explode(',', $cityarr);
                    $str2 .= "  AND  city NOT IN ('" . implode("','", $value['city']) . "')";
                    sql_query("INSERT INTO coverages VALUES ('$icoverageID','$lastid','$key','". implode(",", $value['county']) ."','". implode(",", $value['city'])."')");
                }else {
                    sql_query("INSERT INTO coverages VALUES ('$icoverageID','$lastid','$key','" . implode(",", $value['county']) . "','')");
                }

                $Getzipq = "SELECT zip,id FROM areas WHERE " . $state . $str1 . $str2;
               
                $GetzipqR = sql_query($Getzipq);
                while ($R = sql_fetch_assoc($GetzipqR)) {
                    //array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
                    sql_query("INSERT INTO service_providers_areas( service_providers_id, zip) VALUES ('$lastid','" . $R['zip'] . "')");
                }
            }
        }
        unset($_SESSION[COVERAGE]);
        UnlockTable();
        $mail_content .= "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Hi,</p>";
        $mail_content .= "<p>Thank you for creating a QuoteMaster account. For your security, please verify your account by clicking the link below.</p>";
        $mail_content .= '<p><a href="https://thequotemasters.com/verify.php?key=' . $vkey . '">Click here to verify your email</a></p>';
        $mail_content .= "<p>Questions? Need help? Please</p>";
        $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content .= "<p>Happy Bidding<br>Quote Master</p>";
        $mail_content .= "</body>";
        $mail_content .= "</html>";
        // mail($to, $subject, $mail_content, $headers);
        Send_mail('', '', $to, '', '', '', $subject,$mail_content, '');
        echo '<script>
                alert("Regsitration Successful. Please check email to verify");
               window.location.href = "plogin.php";
            </script>';
    }
}
?>