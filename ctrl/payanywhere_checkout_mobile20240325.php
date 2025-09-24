<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';

$success_url = 'psuccess.php';
$failure_url = 'pfail.php';

$PAGE_TITLE2 = 'Check Out';

$PAGE_TITLE .= $PAGE_TITLE2;

$BID = (isset($_GET['BID'])) ? $_GET['BID'] : '';
$APP_ID = (isset($_GET['APPID'])) ? $_GET['APPID'] : '';
$PID = (isset($_GET['PID'])) ? $_GET['PID'] : '';

if(empty($PID) || empty($APP_ID) || empty($BID))
{
    echo 'Invalid Access !!!';
    exit;
}

$BID = DecodeParam($BID);
$APP_ID = DecodeParam($APP_ID);
$PID = DecodeParam($PID);


$AMOUNT = 0;

$Leads_Ans = $Leads_Ans2 = array();
$CUSTOMER_ARR = $ADDRESS_ARR = array();

$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}



$BID = GetXFromYID("select iBookingID from appointments where iApptID='$APP_ID' ");
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}


$_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3')";
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}

if ($Leads_Ans[0]->iAnswerID == '101' || $Leads_Ans[0]->iAnswerID == '102') {
    $AMOUNT = 85;
} else {
    $AMOUNT = 125;
}



$CFEE = $AMOUNT * 0.03;
$FINAL_AMT = $AMOUNT + $CFEE;

function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}

$_q1 = "select *  from buyed_leads where ibooking_id='$BID' and iApptID='$APP_ID' ";
$_r1 = sql_query($_q1);
if(sql_num_rows($_r1))
{
$statusMsg = 'Your Payment has failed!';
///$_SESSION[PROJ_SESSION_ID]->error_info = $statusMsg;
header('location:' . $failure_url);
exit;
}

$BATCH_ID = generateRandomNumber();
LockTable('transaction');
$ID = NextID('id', 'transaction');
$NOW = NOW;
$_q = "insert into transaction values ('$ID','$BID','$APP_ID','$PID','','','$FINAL_AMT','$NOW','online','P')";
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
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$SCHEDULE_ARR = GetDataFromID("appointments", "iApptID", $APP_ID);

$zip = $ADDRESS_ARR[$SCHEDULE_ARR[0]->iAreaID]['zip'];
$state = $ADDRESS_ARR[$SCHEDULE_ARR[0]->iAreaID]['state'];
$county = $ADDRESS_ARR[$SCHEDULE_ARR[0]->iAreaID]['County_name'];
$city = $ADDRESS_ARR[$SCHEDULE_ARR[0]->iAreaID]['city'];

//DFA($SCHEDULE_ARR);
$BOOKING_ARR = GetDataFromID("appointments", "iApptID", $APP_ID);
$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);
$MODAL_BODY = '';
?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'load.links.php' ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .bgimage {
            background-image: url('../Images/logo.png');
            background-size: cover;
            /* You can adjust this property to control how the image fits the screen */
            background-repeat: no-repeat;
            /* Prevents the image from repeating */
            background-attachment: fixed;
            /* Optionally, you can fix the background in place */
        }


        body {
            font-family: Arial;
            font-size: 17px;
            padding: 8px;
        }

        * {
            box-sizing: border-box;
        }

        .row {
            display: -ms-flexbox;
            /* IE10 */
            display: flex;
            -ms-flex-wrap: wrap;
            /* IE10 */
            flex-wrap: wrap;
            margin: 0 -16px;
        }

        .col-25 {
            -ms-flex: 25%;
            /* IE10 */
            flex: 25%;
        }

        .col-50 {
            -ms-flex: 50%;
            /* IE10 */
            flex: 50%;
        }

        .col-75 {
            -ms-flex: 75%;
            /* IE10 */
            flex: 75%;
        }

        .col-25,
        .col-50,
        .col-75 {
            padding: 0 16px;
        }

        .container {
            background-color: #f2f2f2;
            padding: 5px 20px 15px 20px;
            border: 1px solid lightgrey;
            border-radius: 3px;
        }

        input[type=text] {
            width: 100%;
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        label {
            margin-bottom: 10px;
            display: block;
        }

        .icon-container {
            margin-bottom: 20px;
            padding: 7px 0;
            font-size: 24px;
        }

        .btn {
            background-color: #04AA6D;
            color: white;
            padding: 12px;
            margin: 10px 0;
            border: none;
            width: 100%;
            border-radius: 3px;
            cursor: pointer;
            font-size: 17px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        a {
            color: #2196F3;
        }

        hr {
            border: 1px solid lightgrey;
        }

        span.price {
            float: right;
            color: grey;
        }

        /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
        @media (max-width: 800px) {
            .row {
                flex-direction: column-reverse;
            }

            .col-25 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <h2 class="text-center">Buy Leads</h2>

    <!-- <p>Resize the browser window to see the effect. When the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other.</p> -->
    <div class="row">
        <div class="col-75 ">

            <div class="container ">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="../Images/logo.png" alt="User profile picture">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <form action="https://services.epx.com/browserpost/" method="post" id="checkout_form">

                    <div class="row">
                        <div class="col-50">
                            <h3 class="m-4">Leads Info</h3>

                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center text-muted">Lead Number</span>
                                            <span class="info-box-number text-center text-muted mb-0">QM-<?php echo $APP_ID; ?></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <h3 class="text-center">Date & Time</h3>
                            <div class="col-12 col-md-12 col-lg-4 order-1 order-lg-2 ">
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>Date</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"><?php echo date('l' . ', ' . 'm/d/Y', strtotime($SCHEDULE_ARR[0]->dDateTime)); ?></b>
                                </p>
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>Time</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"><?php echo $TIMEPICKER_ARR[$SCHEDULE_ARR[0]->iAppTimeID]; ?></b>
                                </p>
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>Zip</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"><?php echo $zip; ?></b>
                                </p>
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>State</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"><?php echo $state; ?></b>
                                </p>
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>County</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"><?php echo $county; ?></b>
                                </p>
                                <p class="text-sm  d-flex"><span class="col-5 col-lg-12"><b>City</b></span>
                                    <b class="d-block ml-lg-0 col-7 col-lg-12"> <?php echo $city; ?></b>
                                </p>
                            </div>
                            <h3 class="text-center">Leads questions and answers</h3>
                            <div class="row">
                                <div class="col-12">
                                    <?php
                                    if (!empty($Leads_Ans)) {
                                        for ($i = 0; $i < count($Leads_Ans); $i++) { ?>
                                            <div class="post clearfix pb-0">
                                                <div class="user-block">
                                                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                    <span class="username">
                                                        <a href="#"><?php echo $Question_ARR[$Leads_Ans[$i]->iQuesID]; ?></a>
                                                    </span>
                                                    <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                </div>
                                                <!-- /.user-block -->
                                                <p class="ml-5">
                                                    <?php echo  $Ans_ARR[$Leads_Ans[$i]->iAnswerID]; ?>
                                                </p>
                                                <p>

                                                </p>
                                            </div>

                                    <?php    }
                                    } ?>


                                    <?php if (!empty($Leads_Ans2)) {

                                        $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
                                        foreach ($Ansarr as $value) {
                                            $MBODY = (isset($Ans_ARR[$value])) ? $Ans_ARR[$value] . '<br>' : 'NA';
                                            $MODAL_BODY .= $MBODY;
                                        }
                                    ?>

                                        <div class="post clearfix pb-0">
                                            <div class="user-block">
                                                <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                <span class="username">
                                                    <a href="#"><?php echo $Question_ARR[$Leads_Ans2[0]->iQuesID]; ?></a>
                                                </span>
                                                <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                            </div>
                                            <!-- /.user-block -->
                                            <p class="ml-5">

                                                <?php echo $MODAL_BODY; ?>
                                            </p>

                                        </div>

                                    <?php   } ?>




                                </div>
                            </div>
                        </div>

                        <div class="col-50">
                            <h3 class="m-4">Payment</h3>
                            <!-- <label for="fname">Accepted Cards</label> -->
                            <!-- <div class="icon-container">
                                <i class="fa fa-cc-visa" style="color:navy;"></i>
                                <i class="fa fa-cc-amex" style="color:blue;"></i>
                                <i class="fa fa-cc-mastercard" style="color:red;"></i>
                                <i class="fa fa-cc-discover" style="color:orange;"></i>
                            </div> -->
                            <input type="hidden" name="TAC" value="<?php echo $tacValue; ?>">

                            <input type="hidden" name="TRAN_CODE" value="SALE">

                            <input type="hidden" name="BATCH_ID" value="<?php echo $BATCH_ID; ?>">
                            <input type="hidden" name="USER_DATA_1" value="<?php echo $PID; ?>">

                            <input type="hidden" name="CUST_NBR" value="3001">

                            <input type="hidden" name="MERCH_NBR" value="3130034428641">

                            <input type="hidden" name="DBA_NBR" value="1">

                            <input type="hidden" name="TERMINAL_NBR" value="3">

                            <input type="hidden" name="AMOUNT" value="<?php echo $FINAL_AMT; ?>">

                            <input type="hidden" name="INDUSTRY_TYPE" value="E">
                            <input type="hidden" name="REDIRECT_URL" value="https://thequotemasters.com/ctrl/paymentsuccess.php">
                            <!-- <label for="cname">Name on Card</label>
                            <input type="text" required id="cname" name="cardname" placeholder="John More Doe"> -->
                            <label for="ccnum">Credit card number</label>
                            <input type="text" required id="ACCOUNT_NBR" name="ACCOUNT_NBR" placeholder="1111-2222-3333-4444">
                            <!-- <label for="expmonth">Exp Month</label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="September"> -->
                            <div class="row">
                                <div class="col-50">
                                    <label for="expyear"><span class="text-danger">Exp Year(Please enter the Exp year in MMYY format)</span></label>
                                    <input type="text" maxlength="4" onkeypress="return numbersonly(event);" required id="EXP_DATE" name="EXP_DATE" placeholder="MMYY">
                                </div>
                                <div class="col-50">
                                    <label for="cvv">CVV</label>
                                    <input type="text" required id="CVV2" name="CVV2" placeholder="352">
                                </div>
                            </div>
                        </div>

                    </div>
                    <label>
                        <input type="checkbox" id="check_agree" name="check_agree"> I understand that I will not be able to ask for a credit if I miss the scheduled I am now purchasing.
                    </label>
                    <input type="submit" value="Continue to checkout" class="btn">
                </form>
            </div>
        </div>
        <div class="col-25">
            <div class="container">
                <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> <b>1</b></span></h4>
                <p><a href="#">Lead price</a> <span class="price">$<?php echo $AMOUNT; ?></span></p>
                <p><a href="#">Convenience fee (3%)</a> <span class="price">$<?php echo $CFEE; ?></span></p>
                <!-- <p><a href="#">Product 3</a> <span class="price">$8</span></p>
                <p><a href="#">Product 4</a> <span class="price">$2</span></p> -->
                <hr>
                <p>Total <span class="price" style="color:black"><b>$<?php echo $FINAL_AMT; ?></b></span></p>
            </div>
        </div>
    </div>
    <?php include 'load.scripts.php' ?>
    <script>
        $('#checkout_form').submit(function() {
            // alert('Hii');
            var err = 0;
            var ret_val = true;
            var ExpDate = $('#EXP_DATE');
            var ExpSTR = $.trim(ExpDate.val());
            // console.log($.trim(ExpDate.val()));
            // console.log(ExpDate.val().length);
            // console.log(ExpDate.val().length);
            var newExp = ExpSTR.substring(2, 4) + ExpSTR.substring(0, 2);

            // console.log(ExpSTR.substring(2, 4));
            //console.log(newExp);
            let text = "We are going to send a verification code to the email address and cell phone number you have provided";
            if (!$('#check_agree').is(':checked')) {
                alert('Please agree to the terms.');
                err++;
            }

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#EXP_DATE').val(newExp);

            }

            return ret_val;
        });
    </script>
</body>

</html>
<?php sql_close(); ?>