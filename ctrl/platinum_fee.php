<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// $NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';

$success_url = 'psuccess.php';
$failure_url = 'pfail.php';

$PAGE_TITLE2 = 'Check Out';

$PAGE_TITLE .= $PAGE_TITLE2;

$BID = (isset($_POST['Bid'])) ? $_POST['Bid'] : '';
$APP_ID = (isset($_POST['APPID'])) ? $_POST['APPID'] : '';
$PID = $sess_user_id;


$AMOUNT = 250;

$CFEE = $AMOUNT * 0.03;
$FINAL_AMT = $AMOUNT + $CFEE;
$FINAL_AMT = number_format($FINAL_AMT, 2);

function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}

// $_q1 = "select *  from buyed_leads where ibooking_id='$BID' and iApptID='$APP_ID' ";
// $_r1 = sql_query($_q1);
// if (sql_num_rows($_r1)) {
//     $statusMsg = 'Your Payment has failed!';
//     ///$_SESSION[PROJ_SESSION_ID]->error_info = $statusMsg;
//     header('location:' . $failure_url);
//     exit;
// }

$BATCH_ID = generateRandomNumber();
LockTable('platinum_fee');
$ID = NextID('id', 'platinum_fee');
$_q = "insert into platinum_fee values ('$ID','$sess_user_id','','$FINAL_AMT','" . NOW . "','online','P')";
$_r = sql_query($_q, "");
UnlockTable();
$data = array(
    'MAC' => 'MKrXPYxYMok0cH3dsL585NQJA35MO/e5Y3eaO6H/gXA=',
    'AMOUNT' => $FINAL_AMT,
    'TRAN_NBR' => $ID,
    'TRAN_GROUP' => 'SALE',
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



// DFA($Leads_Ans2);
// DFA($Leads_Ans);
// DFA($Question_ARR);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Upgrade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6a11cb;
            --secondary: #2575fc;
            --accent: #ff4e50;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #36d1dc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: var(--light);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            position: relative;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .gradient-text {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .logo-container {
            text-align: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .logo-img {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }

        .blinking-text {
            font-size: 24px;
            font-weight: 700;
            animation: blink 1.5s step-start infinite, glow 2s ease-in-out infinite alternate;
        }

        @keyframes blink {
            50% {
                opacity: 0.7;
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px rgba(255, 78, 80, 0.7);
            }

            to {
                text-shadow: 0 0 15px rgba(255, 78, 80, 0.9), 0 0 20px rgba(255, 78, 80, 0.6);
            }
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.4);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-group-text {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(106, 17, 203, 0.6);
            background: linear-gradient(135deg, var(--secondary), var(--primary));
        }

        .btn-outline-primary {
            border: 1px solid var(--secondary);
            color: var(--secondary);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
        }

        .cart-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .price {
            color: var(--success);
            font-weight: 600;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.3), transparent);
            margin: 20px 0;
        }

        .promo-section {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .icon-container {
            font-size: 2rem;
            text-align: center;
            margin: 20px 0;
        }

        .icon-container i {
            margin: 0 10px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .icon-container i:hover {
            color: var(--accent);
            transform: scale(1.2);
        }

        .card-number-group {
            position: relative;
        }

        .card-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
        }

        /* Back Button Styles */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: translateX(-5px);
            box-shadow: 0 6px 20px rgba(106, 17, 203, 0.6);
        }

        @media (max-width: 768px) {
            .logo-img {
                max-width: 100px;
            }

            .blinking-text {
                font-size: 20px;
            }

            .back-button {
                top: 15px;
                left: 15px;
            }

            .btn-back {
                width: 40px;
                height: 40px;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(54, 209, 220, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(54, 209, 220, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(54, 209, 220, 0);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="v_profile.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-7 mb-4">
                <div class="glass-card p-4 mb-4">
                    <div class="logo-container">
                        <img class="logo-img" src="../Images/logo.png" alt="Company logo">
                        <h2 class="blinking-text">UPGRADE TO PLATINUM</h2>
                        <p class="mb-0">Unlock exclusive features and benefits</p>
                    </div>

                    <form action="https://services.epx.com/browserpost/" method="post" name="checkout_form" id="checkout_form">
                        <input type="hidden" name="vendorid" id="vendorid" value="<?php echo $PID; ?>">
                        <input type="hidden" name="leadprice" id="leadprice" value="<?php echo $AMOUNT; ?>">
                        <input type="hidden" id="TAC" name="TAC" value="<?php echo $tacValue; ?>">
                        <input type="hidden" name="TRAN_CODE" value="SALE">
                        <input type="hidden" name="BATCH_ID" id="BATCH_ID" value="<?php echo $BATCH_ID; ?>">
                        <input type="hidden" name="USER_DATA_1" value="<?php echo $PID; ?>">
                        <input type="hidden" name="USER_DATA_2" value="<?php echo $ID; ?>">
                        <input type="hidden" name="CUST_NBR" value="3001">
                        <input type="hidden" name="MERCH_NBR" value="3130034428641">
                        <input type="hidden" name="DBA_NBR" value="1">
                        <input type="hidden" name="TERMINAL_NBR" value="3">
                        <input type="hidden" id="AMOUNT" name="AMOUNT" value="<?php echo $FINAL_AMT; ?>">
                        <input type="hidden" name="INDUSTRY_TYPE" value="E">
                        <input type="hidden" name="REDIRECT_URL" value="https://thequotemasters.com/ctrl/paymentsuccess.php">

                        <h3 class="mb-4 gradient-text"><i class="fas fa-credit-card mr-2"></i>Payment Information</h3>

                        <div class="form-group card-number-group">
                            <label for="ACCOUNT_NBR">Credit card number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" required id="ACCOUNT_NBR" name="ACCOUNT_NBR" placeholder="1234 5678 9012 3456">
                            <span class="card-icon"><i class="fas fa-credit-card"></i></span>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="EXP_DATE">Expiration Date (MMYY) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" maxlength="4" onkeypress="return numbersonly(event);" required id="EXP_DATE" name="EXP_DATE" placeholder="MMYY">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="CVV2">CVV <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" required id="CVV2" name="CVV2" placeholder="123">
                            </div>
                        </div>

                        <div class="icon-container">
                            <i class="fab fa-cc-visa"></i>
                            <i class="fab fa-cc-mastercard"></i>
                            <i class="fab fa-cc-amex"></i>
                            <i class="fab fa-cc-discover"></i>
                        </div>

                        <div class="promo-section" style="">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-light">Have a promo code?</span>
                                <button type="button" class="btn btn-outline-primary" id="togglePromo">Apply Code</button>
                            </div>
                            <div class="promo-code">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="promocode" id="promocode" placeholder="Enter promo code">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" onclick="ValidateCouponcode();">Apply</button>
                                    </div>
                                </div>
                                <span id="promocode_span" class="text-danger small mt-2" style="">Please enter a valid promo code</span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block py-3 pulse">
                            <i class="fas fa-lock mr-2"></i>Complete Payment 
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 col-md-5">
                <div class="glass-card p-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-4">
                        <span><i class="fas fa-shopping-cart mr-2"></i>Order Summary</span>
                        <span class="badge gradient-bg badge-pill">1</span>
                    </h4>

                    <div class="cart-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="my-0">Lead Price</h6>
                            </div>
                            <span id="LPRICE" class="price">$<?php echo $AMOUNT; ?></span>
                        </div>
                    </div>

                    <div class="cart-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="my-0">Convenience Fee (3%)</h6>
                            </div>
                            <span id="LCFEE" class="price">$<?php echo $CFEE; ?></span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span>$<?php echo $AMOUNT + $CFEE; ?></span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span><strong>Total (USD)</strong></span>
                        <strong id="LTOTAL" class="total-price">$<?php echo $FINAL_AMT; ?></strong>
                    </div>

                    <div class="divider"></div>

                    <div class="text-center mt-4">
                        <p class="small mb-2"><i class="fas fa-shield-alt mr-2"></i>Secure payment</p>
                        <p class="small mb-0"><i class="fas fa-lock mr-2"></i>Your information is encrypted</p>
                    </div>
                </div>

                <div class="glass-card p-3 mt-3">
                    <div class="text-center">
                        <p class="mb-1"><i class="fas fa-crown text-warning mr-2"></i>Platinum Benefits</p>
                        <div class="d-flex justify-content-around mt-3">
                            <div class="text-center">
                                <i class="fas fa-star text-warning"></i>
                                <p class="small mb-0 mt-1">Premium Support</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-bolt text-success"></i>
                                <p class="small mb-0 mt-1">Faster Access</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-gem text-info"></i>
                                <p class="small mb-0 mt-1">Exclusive Features</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'load.scripts.php' ?>
    <script>
        // Toggle promo code field
        document.getElementById('togglePromo').addEventListener('click', function() {
            const promoCodeField = document.querySelector('.promo-code');
            if (promoCodeField.style.display === 'none') {
                promoCodeField.style.display = 'block';
                this.textContent = 'Cancel';
            } else {
                promoCodeField.style.display = 'none';
                this.textContent = 'Apply Code';
            }
        });

        // // Card number formatting
        // document.getElementById('ACCOUNT_NBR').addEventListener('input', function(e) {
        //     let value = e.target.value.replace(/\D/g, '');
        //     if (value.length > 0) {
        //         value = value.match(/.{1,4}/g).join(' ');
        //     }
        //     e.target.value = value;
        // });

        // // CVV formatting
        // document.getElementById('CVV2').addEventListener('input', function(e) {
        //     e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
        // });

        // Expiration date formatting
        // document.getElementById('EXP_DATE').addEventListener('input', function(e) {
        //     let value = e.target.value.replace(/\D/g, '');
        //     if (value.length > 2) {
        //         value = value.substring(0, 2) + '/' + value.substring(2, 4);
        //     }
        //     e.target.value = value;
        // });

        function PageBlock(msg) {
            // Simple loading indicator
            document.body.style.opacity = '0.7';
            document.body.style.pointerEvents = 'none';
        }

        function PageUnBlock(msg) {
            document.body.style.opacity = '1';
            document.body.style.pointerEvents = 'auto';
        }

        $('#checkout_form').submit(function() {
            var err = 0;
            var ret_val = true;
            var ExpDate = $('#EXP_DATE');
            var ExpSTR = $.trim(ExpDate.val());
            var newExp = ExpSTR.substring(2, 4) + ExpSTR.substring(0, 2);

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#EXP_DATE').val(newExp);
            }

            return ret_val;
        });

        function ValidateCouponcode() {
            var frm = document.checkout_form;
            var promocode = $('#promocode');
            var vendorid = $('#vendorid');
            var leadprice = $('#leadprice');
            var err = 0;
            var ret_val = true;

            if ($.trim(promocode.val()) == '') {
                $('#promocode_span').css("display", "block");
                err++;
            } else {
                $('#promocode_span').css("display", "none");
            }

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                PageBlock();
                var data = 'vendorid=' + vendorid.val() + '&promocode=' + promocode.val() + '&leadprice=' + leadprice.val();
                setTimeout(function() {
                    $.ajax({
                        url: '_checkcoupons2.php',
                        async: false,
                        method: 'POST',
                        data: data,
                        success: function(res) {
                            PageUnBlock();
                            var RES_ARR = res.split("~~");
                            console.log(RES_ARR);
                            if (RES_ARR[0] == '401') {
                                alert(RES_ARR[1]);
                            } else if (RES_ARR[0] == '200') {
                                alert(RES_ARR[1]);
                                $('#TAC').val(RES_ARR[2]);
                                $('#BATCH_ID').val(RES_ARR[3]);
                                $('#AMOUNT').val(RES_ARR[6]);
                                $('#LPRICE').html('$' + RES_ARR[4]);
                                $('#LCFEE').html('$' + RES_ARR[5]);
                                $('#LTOTAL').html('$' + RES_ARR[6]);
                                if (RES_ARR[6] == '0.00') {
                                    frm.action = 'paymentsuccess2.php';
                                }
                            }
                        }
                    });
                }, 2000);
            }
        }
    </script>
</body>

</html>