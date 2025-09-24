<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include "includes/common.php";
require_once('includes/ti-salt.php');

$result = '0';

$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if (empty($mode)) {
    echo 'Invalid Access Detected.';
    exit;
}

if ($mode == 'REGISTRATION') {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $cname = $_POST['cname'];
    $pname = $_POST['pname'];
    $txtpasswd = $_POST['passwd'];

    if (!empty($fname) && !empty($mobile) && !empty($email) && !empty($cname) && !empty($pname) && !empty($txtpasswd)) {

        $u_exist = GetXFromYID("select iCustomerID from customers where vPhone = '$mobile' or vEmail = '$email'");
        $txtpasswd = md5($txtpasswd);

        if (empty($u_exist)) {
            // inserting data into customer table
            LockTable('customers');
            $txtid = NextID('iCustomerID', 'customers');
            $txtnow = NOW;
            // $q = "insert into customer (iCustID, vFirstName, vLastName, vContact, vEmail, vCity, vState, vCountry, dtRegistration, cStatus) values ('$txtid', '$fname', '$lname', '$mobile', '$email', '$city', '$state', '$country', '$txtnow', 'A')";
            $q = "INSERT INTO customers(iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPassword, vPhone, dtRegistration, cStatus) VALUES ('$txtid','$fname','$lname','$cname','$pname','$email','$txtpasswd','$mobile','$txtnow','A')";
            $r = sql_query($q, "");
            UnLockTable();

            $name = $fname . ' ' . $lname;

            // setting the session for the entered customer
            session_destroy();
            session_start();
            session_regenerate_id();
            ${PROJ_SESSION_ID} = new userdat;

            $randomtoken = base64_encode(uniqid(rand(), true));

            $_SESSION[PROJ_SESSION_ID] = new userdat;
            $_SESSION[PROJ_SESSION_ID]->log_time = NOW2;
            $_SESSION[PROJ_SESSION_ID]->log_stat = "A";
            $_SESSION[PROJ_SESSION_ID]->user_id = $txtid;
            $_SESSION[PROJ_SESSION_ID]->user_pic = '';
            $_SESSION[PROJ_SESSION_ID]->user_name = $name;
            $_SESSION[PROJ_SESSION_ID]->user_level = 3;
            $_SESSION[PROJ_SESSION_ID]->user_type = 3;
            $_SESSION[PROJ_SESSION_ID]->user_reftype = '';
            $_SESSION[PROJ_SESSION_ID]->user_refid = '';
            $_SESSION[PROJ_SESSION_ID]->sess = session_id();
            $_SESSION[PROJ_SESSION_ID]->rmadr = $_SERVER['REMOTE_ADDR'];
            $_SESSION[PROJ_SESSION_ID]->lhs_menu = true;
            $_SESSION[PROJ_SESSION_ID]->sess_token = $randomtoken;
            $_SESSION[PROJ_SESSION_ID]->sess_active = 'Y';

            $q = "update users set dtLastLogin='" . NOW . "', vLastLoginIP='" . $_SERVER['REMOTE_ADDR'] . "', vToken='$randomtoken', cActive='Y' where iUserID=$txtid";
            $r = sql_query($q, 'AUTH.78');

            $browser = '';
            $browser2 = getBrowser();
            if (!empty($browser2) && count($browser2))
                $browser = $browser2['name'] . ' ' . $browser2['version'];

            $ipaddress = $_SERVER['REMOTE_ADDR'];
            sql_query("insert into log_signin (dDate, cRefType, iRefID, dtEntry, vIPAddress, vBrowser, cStatus) values ('" . TODAY . "', 'V', 'C', '" . NOW . "', '$ipaddress', '$browser', 'A')", "");


            $result = 1;
        } else {
            $result = -1;
        }
    }
} else if ($mode == 'LOGIN') {
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];
    $usertype = $_POST['usertype'];

    if (!empty($email) && !empty($passwd)) {

        $txtpassword = htmlspecialchars_decode(db_input($passwd));
        $salt_obj = new SaltIT;
        $txtpassword = $salt_obj->EnCode($txtpassword);
      
        $u_id = $u_level = $ref_type = $ref_id = 0;
        $q1 = "select iCustomerID,vFirstname,vPassword from customers where vEmail='$email' and cStatus='A'  "; //customers table
        $q2 = "select id,First_name,password from service_providers where email_address='$email' and cStatus='A'   "; //vendor table
        if ($usertype == 2) {

            $r = sql_query($q2, 'AUTH.61');
        } else {
            $r = sql_query($q1, 'AUTH.61');
        }


        if (sql_num_rows($r)) {
            list($u_id, $u_name, $u_pass) = sql_fetch_row($r);
            $ret = ($u_pass == ($txtpassword)) ? 1 : -1;    // 1 - txtpassword Matches ::  -1 - txtpassword MisMatch
            // echo $u_pass . '<br>' . $txtpassword;
            // exit;

            if ($ref_type == 'A') $ref_id = $u_id;
        } else
            $ret = -2;    //No User Found

        if ($ret == -1 || $ret == -2) {
            if ($usertype == 3) {
                ForceOutC(4);
            } else if ($usertype == 2) {
                ForceOutV(4);
            } 
        } elseif ($ret == 1) {
            session_destroy();
            session_start();
            session_regenerate_id();
            ${PROJ_SESSION_ID} = new userdat;

            $randomtoken = base64_encode(uniqid(rand(), true));

            $_SESSION[PROJ_SESSION_ID] = new userdat;
            $_SESSION[PROJ_SESSION_ID]->log_time = NOW2;
            $_SESSION[PROJ_SESSION_ID]->log_stat = "A";
            $_SESSION[PROJ_SESSION_ID]->user_id = $u_id;
            $_SESSION[PROJ_SESSION_ID]->user_pic = '';
            $_SESSION[PROJ_SESSION_ID]->user_name = $u_name;
            $_SESSION[PROJ_SESSION_ID]->user_level = $usertype;
            $_SESSION[PROJ_SESSION_ID]->user_type = $usertype;
            $_SESSION[PROJ_SESSION_ID]->user_reftype = '';
            $_SESSION[PROJ_SESSION_ID]->user_refid = '';
            $_SESSION[PROJ_SESSION_ID]->sess = session_id();
            $_SESSION[PROJ_SESSION_ID]->rmadr = $_SERVER['REMOTE_ADDR'];
            $_SESSION[PROJ_SESSION_ID]->lhs_menu = true;
            $_SESSION[PROJ_SESSION_ID]->sess_token = $randomtoken;
            $_SESSION[PROJ_SESSION_ID]->sess_active = 'Y';

            $q = "update users set dtLastLogin='" . NOW . "', vLastLoginIP='" . $_SERVER['REMOTE_ADDR'] . "', vToken='$randomtoken', cActive='Y' where iUserID=$u_id";
            $r = sql_query($q, 'AUTH.78');

            $browser = '';
            $browser2 = getBrowser();
            if (!empty($browser2) && count($browser2))
                $browser = $browser2['name'] . ' ' . $browser2['version'];

            $ipaddress = $_SERVER['REMOTE_ADDR'];
            sql_query("insert into log_signin (dDate, cRefType, iRefID, dtEntry, vIPAddress, vBrowser, cStatus) values ('" . TODAY . "', 'V', '$u_id', '" . NOW . "', '$ipaddress', '$browser', 'A')", "");
            if ($usertype==3) {
                header('location:ctrl/user_booking.php');
                exit;
                
            }
            else if ($usertype==2) {
                header('location:ctrl/v_profile.php');
                exit;
            } 
        } else {
            if ($usertype == 3) {
                ForceOutC(4);
            } else if ($usertype == 2) {
                ForceOutV(4);
            } 
            //ForceOutC();
        }
    } else {
        if ($usertype == 3) {
            ForceOutC(4);
        } else if ($usertype == 2) {
            ForceOutV(4);
        } 
    }
}

echo $result;
exit;
?>