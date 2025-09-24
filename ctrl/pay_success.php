<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';
include '../phpmailer.php';
require('stripe-php/init.php');
require_once 'secrets.php';
$payment_id = $statusMsg = '';
$status = 'error';
$redirectURL='leads_disp.php';
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

// Check whether stripe checkout session is not empty 
if (!empty($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Include the Stripe PHP library 
    //require_once 'stripe-php/init.php';

    // Set API key 
    $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

    // Fetch the Checkout Session to display the JSON result on the success page 
    try {
        $checkout_session = $stripe->checkout->sessions->retrieve($session_id);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $checkout_session) {
        // Get customer details 
        $customer_details = $checkout_session->customer_details;

        // Retrieve the details of a PaymentIntent 
        try {
            $paymentIntent = $stripe->paymentIntents->retrieve($checkout_session->payment_intent);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $paymentIntent) {
            // Check whether the payment was successful
            // var_dump($paymentIntent);
            // exit;
            if (!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
                // Transaction details  
                $transactionID = $payment_id = $paymentIntent->id;
                $paidAmount = $paymentIntent->amount;
                $paidAmount = ($paidAmount / 100);
                $paidCurrency = $paymentIntent->currency;
                $payment_status = $paymentIntent->status;
                $_q1= "select  booking_id, pid,sessionid,iApptID FROM transaction where sessionid='$session_id'  ";
                $_r1=sql_query($_q1,"");
                list($bookingID,$pid,$session_id,$iApptID)=sql_fetch_row($_r1);
                $_q2= "update transaction set payment_id='$transactionID',payment_status='S' where sessionid='$session_id' ";
                sql_query($_q2,"");
                $_q3= "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$paidAmount','$transactionID')";
                sql_query($_q3,"");
                $_q4= "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
                sql_query($_q4,"");
                $_q5= "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
                sql_query($_q5,"");

                $_q6= "select iCustomerID,dDateTime,iAppTimeID from appointments where iApptID='$iApptID' ";
                $_q6r=sql_query($_q6,"");
                list($CUSTID,$DATEB,$TIMEID)=sql_fetch_row($_q6r);

                $Customer_name=GetXFromYID("select  CONCAT(vFirstname, ' ', vLastname) as full_name from customers where iCustomerID='$CUSTID' ");
                $ADATE=date('m-d-Y',strtotime($DATEB));
                $ATIME=$TIMEPICKER_ARR[$TIMEID];

                //send mail alert to customers
                $email=GetXFromYID("select vEmail from customers where iCustomerID='$CUSTID' ");
                $company_name=GetXFromYID("select company_name from service_providers where id='$pid' ");
                $Cleaners_name = GetXFromYID("select  CONCAT(First_name, ' ', Last_name) as full_name from service_providers where id='$pid' ");
                $to = db_output2($email);
                $subject = "Appointment Update";
                // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
                $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
                $mail_content = '';
                $mail_content .= "<html>";
                $mail_content .= "<body>";
                $mail_content .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">';
                $mail_content .= "<p>Hello $Customer_name,</p>";
                $mail_content .= "<p>Great news! </p>";
                $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
                $mail_content .= "<p>$company_name, will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links $Cleaners_name that you may want to view before your scheduled meeting.</p>";

                $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid='.$pid.'">Click here to see the cleaners profile</a></p>';
                //$mail_content .= '<ol type="1">';
                $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
                $mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
                $mail_content .= "<p>Happy bid collecting!<br>The Quote Masters' Team</p>";
                $mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
                $mail_content .= "</body>";
                $mail_content .= "</html>";
                //mail($to, $subject, $mail_content, $headers);
                Send_mail('', '', $to, '', '', '', $subject, $mail_content, '');


                $status = 'success';
                $statusMsg = 'Your Payment has been Successful!';
                $_SESSION[PROJ_SESSION_ID]->success_info = $statusMsg;
                header('location:'.$redirectURL);
                // echo $ts
                 exit;
            } else {
                $statusMsg = "Transaction has been failed!";
            }
        } else {
            $statusMsg = "Unable to fetch the transaction details! $api_error";
        }
    } else {
        $statusMsg = "Invalid Transaction! $api_error";
    }
} else {
    $statusMsg = "Invalid Request!";
}
//echo $statusMsg;
//DFA($paymentIntent);
$_SESSION[PROJ_SESSION_ID]->alert_info = $statusMsg;
header('location:'.$redirectURL);
exit;
?>
