<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';
require('stripe-php/init.php');
require_once 'secrets.php';
$payment_id = $statusMsg = '';
$status = 'error';
$redirectURL='leads_disp.php';

// Check whether stripe checkout session is not empty 
if (!empty($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Include the Stripe PHP library 
    //require_once 'stripe-php/init.php';

    // Set API key 
    $stripe = new \Stripe\StripeClient($stripeSecretKey);

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