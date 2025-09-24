<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common.php');
require_once('../includes/ti-salt.php');
include("../includes/recaptchalib.php");
// var_dump($_POST);
// exit;

$secret = "6LeULAgbAAAAANCpGnhyhdopqY2RFkh4VV-dfCLE";
$response = null;
$reCaptcha = new ReCaptcha($secret);
if (isset($_POST["g-recaptcha-response"])) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

//if($response != null && $response->success)
if (true) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        ####################################################################################
        // First, make sure the form was posted from a browser.
        // For basic web-forms, we don't care about anything  other than requests from a browser:
        if (!isset($_SERVER['HTTP_USER_AGENT']))
            ForceOutV(5);

        // Make sure the form was indeed POST'ed: (requires your html form to use: action="post")
        if (!$_SERVER['REQUEST_METHOD'] == "POST")
            ForceOutV(5);

        #########################################################################################
        if (isset($_POST["txtemail"]) && isset($_POST["txtpassword"])) // && isset($_POST["btnlogin"]))
        {
            // DFA($_POST);
            // exit;
            // $usertype = $_POST['usertype'];
            $email = db_input($_POST["txtemail"]);
            $txtpassword = htmlspecialchars_decode(db_input($_POST["txtpassword"]));

            $salt_obj = new SaltIT;
            $txtpassword = $salt_obj->EnCode($txtpassword);

            $ret = 0; //error flag

            if ($txtpassword == '')
                ForceOutV(8);
            elseif ($email == '')
                ForceOutV(7);
            else {
                $u_id = $u_level = 0;
                $q1 = "select id,First_name,password from service_providers where email_address='$email' ";
                $r = sql_query($q1, 'AUTH.61');
                if (sql_num_rows($r)) {
                    list($u_id, $u_name, $u_pass) = sql_fetch_row($r);
                    $ret = ($u_pass == ($txtpassword)) ? 1 : -1;    // 1 - txtpassword Matches ::  -1 - txtpassword MisMatch
                    // echo $u_pass . '<br>' . $txtpassword;
                    // exit;

                    if ($ref_type == 'A') $ref_id = $u_id;
                } else
                    $ret = -2;    //No User Found

                if ($ret == -1 || $ret == -2) {
                    ForceOutV(4);
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
                    $_SESSION[PROJ_SESSION_ID]->user_level = 2;
                    $_SESSION[PROJ_SESSION_ID]->user_type = 2;
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
                    sql_query("insert into log_signin (dDate, cRefType, iRefID, dtEntry, vIPAddress, vBrowser, cStatus) values ('" . TODAY . "', 'V', '$ref_id', '" . NOW . "', '$ipaddress', '$browser', 'A')", "");

                    $URL = SITE_ADDRESS . "ctrl/v_profile.php";



                    header("location:" . $URL);
                    exit;
                }
            }
        } else
            ForceOutV(4);
    } else {
        session_destroy(); // destroy all data in session
        die("Forbidden - You are not authorized to view this page");
        exit;
    }
} else {
    session_destroy(); // destroy all data in session
    die("Forbidden - You are not authorized to view this page");
    exit;
}
