<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';


$PAGE_TITLE2 = 'Check Out';

$PAGE_TITLE .= $PAGE_TITLE2;

$BID = (isset($_POST['Bid'])) ? $_POST['Bid'] : '120';
$APP_ID = (isset($_POST['APPID'])) ? $_POST['APPID'] : '120';
$PID = (isset($_POST['pID'])) ? $_POST['pID'] : '120';
$AMOUNT = 85;
$FINAL_AMT = $AMOUNT + $AMOUNT * 0.03;

function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}

$BATCH_ID = generateRandomNumber();
LockTable('transaction');
$ID = NextID('id', 'transaction');
$_q = "insert into transaction values ('$ID','$BID','$APP_ID','$PID','','','$FINAL_AMT',NOW(),'online','P')";
$_r = sql_query($_q, "");
UnlockTable();
$data = array(
    'MAC' => '2ifP9bBSu9TrjMt8EPh1rGfJiZsfCb8Y',
    'AMOUNT' => $FINAL_AMT,
    'TRAN_NBR' => $ID,
    'TRAN_GROUP' => 'SALE',
    'REDIRECT_URL' => 'https://thequotemasters.com/ctrl/paymentsuccess.php',
    'BATCH_ID' => $BATCH_ID
);

$data_string = http_build_query($data);


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://keyexch.epxuap.com',
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


//echo $response;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        html {
            font-size: 10px;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
            padding: 0;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'IBM Plex Mono', monospace;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--backgroundColor, #C3A4FF);
            line-height: 1.6;
        }

        img {
            width: 100%;
            object-fit: cover;
        }

        .product-container {
            border: 5px solid #000000;
            background: #DBFF6E;
            width: 75rem;
            height: 45rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-inline: 7rem;
        }

        .product-container .image {
            width: 35rem;
            transform: rotate(-20deg);
            margin-left: -4rem;
        }

        .product-container .details .cost {
            font-size: 2rem;
            font-weight: 600;
        }

        .product-container .details .title {
            font-size: 1.6rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .card-container {
            border: 5px solid #000000;
            background-color: #ffffff;
            height: 35rem;
            width: 60rem;
            margin-left: -32rem;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(5, 1fr);
        }

        .card-details {
            grid-area: 2/1/5/4;
            display: grid;
            row-gap: 1rem;
            column-gap: 1rem;
            grid-template-columns: repeat(3, 1fr);
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        input {
            font-family: 'IBM Plex Mono', monospace;
            padding: 0.5rem 0.5rem;
            font-size: 1.7rem;
            width: 90%;
        }

        .card-number {
            grid-area: 1/1/1/4;
            align-self: end;
        }

        .card-name {
            grid-area: 2/1/2/4;
            align-self: end;
        }

        .expires,
        .cvc {
            align-self: end;
            width: 100%;
        }

        .field input {
            border-bottom: 2px solid #000000;
            border-top: none;
            border-left: none;
            border-right: none;
            outline: none;
        }

        .mastercard {
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            grid-area: 1/4;
            justify-self: end;
        }

        .logo {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #FCE32F;
            opacity: 0.8;
            position: relative;
        }

        .logo::after {
            position: absolute;
            content: '';
            width: 2rem;
            height: 2rem;
            background-color: #EA6564;
            border-radius: 50%;
            left: -50%;
        }

        .name {
            font-size: 1rem;
        }

        .purchase-button {
            font-family: 'IBM Plex Mono', monospace;
            padding: 1rem 1.25rem;
            position: relative;
            background-color: #000000;
            font-weight: 600;
            font-size: 2rem;
            letter-spacing: 0.02rem;
            display: inline-block;
            cursor: pointer;
            outline: none;
            border: transparent;
            grid-area: 5/4;
        }

        .purchase-button::after {
            content: attr(data-content);
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: #FEFC00;
            border: 2px solid #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #000000;
            transform: translate(-0.4rem, -0.4rem);
            transition: transform .2s cubic-bezier(.34, 1.56, .64, 1);
        }

        .purchase-button:hover::after {
            transform: translate(0, 0);
        }

        @media screen and (max-width: 1050px) {
            body {
                display: flex;
                flex-direction: column;
                justify-content: start;
            }

            .product-container {
                width: 100%;
                border-color: transparent;
                height: 35rem;
                align-items: center;
                padding: 1.5rem;
                justify-content: end;
            }

            .product-container .image {
                width: 30rem;
            }

            .product-container .details {
                text-align: center;
            }

            .card-container {
                width: 100%;
                height: 100%;
                max-width: 100%;
                border-color: transparent;
                margin-left: 0;
            }

            .field label {
                font-size: 1.3rem;
            }

            .card-details {
                grid-area: 2/1/5/5;
            }
        }
    </style>
</head>

<body>
    <div class="product-container">
        <div class="image">
            <img src="../Images/logo.png" alt="International-Women-s-Day-Facebook-Post" border="0" />
        </div>
        <div class="details">
            <h1 class="cost">$<?php echo $FINAL_AMT; ?></h1>
            <h3 class="title">Purchase Leads</h3>
        </div>
    </div>
    <div class="card-container">
        <div class="mastercard">
            <div class="logo"></div>
            <div class="name">mastercard</div>
        </div>
        <form action="https://services.epxuap.com/browserpost/" method="post">
            <div class="card-details">
                <!-- <div class="card-number field">
                <label for="cn">CARD NUMBER</label>
                <input id="cn" type="text" />
            </div>
            <div class="card-name field">
                <label for="cna">NAME ON CARD</label>
                <input id="cna" type="text" />
            </div>
            <div class="expires field">
                <label for="exp">EXPIRES</label>
                <input id="exp" type="text" />
            </div>
            <div class="cvc field">
                <label for="cvc">CVC</label>
                <input id="cvc" type="text" />
            </div> -->

                <input type="hidden" name="TAC" value="<?php echo $tacValue; ?>">

                <input type="hidden" name="TRAN_CODE" value="SALE">

                <input type="hidden" name="BATCH_ID" value="<?php echo $BATCH_ID; ?>">
                <input type="hidden" name="USER_DATA_1" value="<?php echo $PID; ?>">

                <input type="hidden" name="CUST_NBR" value="9001">

                <input type="hidden" name="MERCH_NBR" value="900300">

                <input type="hidden" name="DBA_NBR" value="2">

                <input type="hidden" name="TERMINAL_NBR" value="21">

                <input type="hidden" name="AMOUNT" value="<?php echo $FINAL_AMT; ?>">

                <input type="hidden" name="INDUSTRY_TYPE" value="E">
                <input type="hidden" name="REDIRECT_URL" value="https://thequotemasters.com/ctrl/paymentsuccess.php">

                <div class="card-number field">
                    <label for="cn">CARD NUMBER</label>
                    <input type="text" class="form-control" name="ACCOUNT_NBR" placeholder="cardnumber" value="">
                </div>
                <div class="card-name field">
                    <label for="cna">NAME ON CARD</label>
                    <input id="cna" type="text" />
                </div>
                <div class="expires field">
                    <label for="exp">EXPIRES</label>
                    <input type="text" class="form-control" name="EXP_DATE" placeholder="YYMM" value="">
                </div>
                <div class="cvc field">
                    <label for="cvc">CVC</label>
                    <input type="text" class="form-control" name="CVV2" placeholder="CVV" value="">
                </div>


            </div>
            <br>
            <br>
            <button type="submit" data-content="PURCHASE" class=" purchase-button">PURCHASE</button>
        </form>
        <!-- <button class="purchase-button" data-content="PURCHASE">PURCHASE</button> -->
    </div>

    <?php include 'load.scripts.php' ?>
</body>

</html>