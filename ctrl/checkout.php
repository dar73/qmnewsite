<?php
require('stripe-php/init.php');
require_once 'secrets.php';
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
if (isset($_POST['Bid'],$_POST['pID'],$_POST['amt'])) {
    \Stripe\Stripe::setApiKey($stripeSecretKey);
    header('Content-Type: application/json');
    $amt=db_input($_POST['amt']);
    $Bid=db_input($_POST['Bid']);
    $pID=db_input($_POST['pID']);
    $appID=db_input($_POST['APPID']);
    $final_amt=$amt*100;
    
    //$YOUR_DOMAIN = 'http://localhost:84/stripe';
    
    $checkout_session = \Stripe\Checkout\Session::create([
      'line_items' => [[
        # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Leads',
          ],
          'unit_amount' => $final_amt,
        ],
        'quantity' => 1,
      ]],
      'mode' => 'payment',
      'success_url' => SITE_ADDRESS . 'ctrl/pay_success.php?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => SITE_ADDRESS . 'ctrl/cancel.php',
    ]);
       
}
//DFA($checkout_session);
///exit;
LockTable('transaction');
$sessionID=$checkout_session->id;
$ID=NextID('id', 'transaction');
$_q= "insert into transaction values ('$ID','$Bid','$appID','$pID','$sessionID','','$amt',NOW(),'online','P')";
$_r=sql_query($_q,"");
UnlockTable();
header("HTTPS/1.1 303 See Other");
header("Location: " . $checkout_session->url);
?>