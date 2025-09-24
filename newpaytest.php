<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');


$BATCH_ID = generateRandomNumber();
LockTable('transaction2');
$ID = NextID('id', 'transaction2');
$_q = "insert into transaction2 values ('$ID','$sess_user_id','','0','" . NOW . "','online','P')";
$_r = sql_query($_q, "");
UnlockTable();


$data = array(
    'MAC' => 'MKrXPYxYMok0cH3dsL585NQJA35MO/e5Y3eaO6H/gXA=',
    'AMOUNT' => '0.00',
    'TRAN_NBR' => $ID,
    'TRAN_GROUP' => 'STORAGE',
    'REDIRECT_URL' => 'https://thequotemasters.com/ctrl/paymentsuccess.php',
    'BATCH_ID' => $BATCH_ID
);

$data_string = http_build_query($data);


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://keyexch.epx.com',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$xmlResponse = curl_exec($curl);
curl_close($curl);
$responseXML = simplexml_load_string($xmlResponse);

// Access the value of the TAC field
$tacValue = (string)$responseXML->FIELDS->FIELD;

//echo $tacValue;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h3,
        h4 {
            color: #BE1E2D;
            /* Using the theme color #BE1E2D */
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #BE1E2D;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #a61724;
        }

        .text-danger {
            color: #BE1E2D;
            /* Using the theme color */
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .col-50 {
            width: 48%;
        }

        @media (max-width: 600px) {
            .col-50 {
                width: 100%;
            }
        }

        input[type="text"]:focus,
        input[type="submit"]:focus {
            outline: none;
            border-color: #BE1E2D;
            /* Highlight with theme color */
            box-shadow: 0 0 5px rgba(190, 30, 45, 0.5);
        }
    </style>
</head>

<body>
    <form action="https://services.epx.com/browserpost/" method="post" id="checkout_form">

        <div class="row">

            <div class="col-50">
                <h3 class="m-4">Payment</h3>
                <h4>Amount:0.00</h4>

                <input type="hidden" id="TAC" name="TAC" value="<?php echo $tacValue; ?>">

                <input type="hidden" name="TRAN_CODE" value="STORAGE">

                <input type="hidden" name="BATCH_ID" id="BATCH_ID" value="<?php echo $BATCH_ID; ?>">
                <input type="hidden" name="USER_DATA_1" value="14">

                <input type="hidden" name="CUST_NBR" value="3001">

                <input type="hidden" name="MERCH_NBR" value="3130034428641">

                <input type="hidden" name="DBA_NBR" value="1">

                <input type="hidden" name="TERMINAL_NBR" value="3">
                <input type="hidden" id="AMOUNT" name="AMOUNT" value="0.00">
                <input type="hidden" name="INDUSTRY_TYPE" value="E">
                <input type="hidden" name="REDIRECT_URL" value="https://thequotemasters.com/ctrl/paymentsuccess.php">
                <!-- <label for="cname">Name on Card</label>
                            <input type="text" required id="cname" name="cardname" placeholder="John More Doe"> -->
                <label for="ccnum">Credit card number <span class="text-danger">*</span></label>
                <input type="text" required id="ACCOUNT_NBR" value="4000000000000002" name="ACCOUNT_NBR" placeholder="4000000000000002">
                <!-- <label for="expmonth">Exp Month</label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="September"> -->
                <div class="row">
                    <div class="col-md-6">
                        <label for="expyear"><span class="text-danger">Exp Year(Exp year in YYMM format)</span></label>
                        <input type="text" maxlength="4" onkeypress="return numbersonly(event);" required id="EXP_DATE" name="EXP_DATE" value="2912" placeholder="2912">
                    </div>
                    <div class="col-md-6">
                        <label for="cvv">CVV<span class="text-danger">*</span></label>
                        <input type="text" required id="CVV2" name="CVV2" value="123" placeholder="123">
                    </div>
                </div>
                <br>
                <br>

            </div>

        </div>

        <input type="submit" value="Continue to checkout" class="btn">
    </form>
</body>

</html>

<?php
function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}

?>

<!-- Array
(
[MSG_VERSION] => 003
[CUST_NBR] => 9001
[MERCH_NBR] => 900300
[DBA_NBR] => 2
[TERMINAL_NBR] => 21
[TRAN_TYPE] => CCE8
[BATCH_ID] => 3908063840
[TRAN_NBR] => 9
[LOCAL_DATE] => 081324
[LOCAL_TIME] => 110821
[AUTH_GUID] => 0A1LM2A2G67QEF43K29
[AUTH_RESP] => 00
[AUTH_CODE] => 749143
[AUTH_RESP_TEXT] => APPROVAL
[AUTH_CARD_TYPE] => V
[AUTH_TRAN_DATE_GMT] => 08/13/2024 03:08:21 PM
[AUTH_AMOUNT_REQUESTED] => 0.00
[AUTH_AMOUNT] => 0.00
[AUTH_CURRENCY_CODE] => 840
[NETWORK_RESPONSE] => 00
[AUTH_CARD_COUNTRY_CODE] => 840
[AUTH_CARD_CURRENCY_CODE] => 840
[AUTH_CARD_B] => D
[AUTH_CARD_C] => F
[AUTH_CARD_E] => N
[AUTH_CARD_F] => Y
[AUTH_CARD_G] => N
[AUTH_CARD_I] => Y
[AUTH_MASKED_ACCOUNT_NBR] => ************0002
[AUTH_CARD_L] => P
[ORIG_TRAN_TYPE] => CCE8
) -->