<?php
include '../includes/common.php';
// Include the Stripe PHP library 
require_once 'stripe-php/init.php';


$productName = "Leads";
$productID = "DP12345";
$productPrice = 125;
$currency = "usd";

$response=array();

// Set API key 
$stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

$createCheckoutSession=$_POST['createCheckoutSession'];


if (!empty($createCheckoutSession)) {
    // Convert product price to cent 
    $stripeAmount = round($productPrice * 100, 2);

    // Create new Checkout Session for the order 
    try {
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'product_data' => [
                        'name' => $productName,
                        'metadata' => [
                            'pro_id' => $productID
                        ]
                    ],
                    'unit_amount' => $stripeAmount,
                    'currency' => $currency,
                ],
                'quantity' => 1
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:84/quotemasters.com/ctrl/pay_success.php' . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:84/quotemasters.com/pay_success.php',
        ]);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $checkout_session) {
        $response = array(
            'status' => 1,
            'message' => 'Checkout Session created successfully!',
            'sessionId' => $checkout_session->id
        );
    } else {
        $response = array(
            'status' => 0,
            'error' => array(
                'message' => 'Checkout Session creation failed! ' . $api_error
            )
        );
    }
}

// Return response 
echo json_encode($response);
//header('location:pay_success.php?session_id='. $checkout_session->id);
?>