<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
include 'phpmailer.php';
$error = NULL;
$page_title = 'Verify Account';
$TITLE = SITE_NAME . ' | ' . $page_title;
if (isset($_GET['key'])) {
    $key = $_GET['key'];
    $q = "SELECT email_address FROM service_providers WHERE email_verify='0' AND email_verify_key='$key' LIMIT 1";
    $r = sql_query($q);
    list($email) = sql_fetch_row($r);
    $to = $email;
    $subject = "Welcome To Quote Masters";
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From:Quote Masters <ops@janitorialquotemasters.com>' . "\r\n";
    $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
    $mail_content = '';



    $mail_content .= "<html>";
    $mail_content .= "<body>";
    $mail_content .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;"><p>Hi,</p>';
    $mail_content .= "<p>WELCOME to the QUOTE MASTERS family where YOU are the MASTER of the leads you receive!  With Quote Master you will receive an amazing source of janitorial leads! </p>";
    $mail_content .= "<p>You receive the following information with each lead:</p>";
    $mail_content .= "<ol>
                            <li>A verified appointment day and time for your meeting </li>
                            <li>All the pertinent details of the meeting including company name, contact name, position, address, current frequency and notes on current cleaning situation  </li>
                </ol>  ";
    $mail_content .= "<p>With Quote Masters we offer you ways to increase your monthly revenue by:</p>";
    $mail_content .= "<ol>
                            <li>Getting you in front of more companies to present your services  </li>
                            <li>Improving your online satisfaction rating</li>
                            <li>An easy system to accept appointments and when applicable, get credits </li>
                </ol>  ";
    $mail_content .= "<p>You are a valued service provider in the Quote Master's family!  </p>";
    $mail_content .= "<p>Happy Bidding,</p>";
    $mail_content .= '<p><a href="https://thequotemasters.com/clogin.php">visit thequotemasters.com to connect </a></p>';
    $mail_content .= "<p>Questions? Need help? Please</p>";
    $mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
    $mail_content .= "<p>Quote Masters</p>";
    $mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
    $mail_content .= "</body>";
    $mail_content .= "</html>";





    if (sql_num_rows($r)) {
        $result = sql_query("UPDATE service_providers SET email_verify='1',cStatus='A' WHERE email_verify_key='$key'");
        if ($result) {
            $error = ' <a href="plogin.php">Your email has been verified, click here to login</a> ';
            //mail($to, $subject, $mail_content, $headers);
            //Send_mail('', '', $to, '', '', '', $subject, $mail_content, '');
            SendInBlueMail($subject, $to,$mail_content, '', '', '','');
        } else {
            $error = sql_error($conn);
        }
    } else {
        $error = 'This account has been already verified or Invalid account';
    }
} else {
    die("something went wrong");
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'load.link.php'; ?>

</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <section class="content">
            <div class="container-fluid">
                <div class="card my-5">
                    <div class="card-body mx-auto">
                        <?php
                        echo $error;
                        ?>
                    </div>
                    <!-- /.form-box -->
                </div><!-- /.card -->
            </div>
        </section>
        <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
</body>

</html>