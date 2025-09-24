<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

//$GoogleCalendarApi = new GoogleCalendarApi();

$PAGE_TITLE2 = 'Payment';


$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'calendar.php';
$edit_url = 'addcalendar.php';

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        #calendar {

            margin: 0 auto;
        }

        /* Form Container */
        #checkout_form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form Heading */
        #checkout_form h3 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Group Styling */
        #checkout_form .form-group label {
            font-weight: 600;
            color: #444;
        }

        #checkout_form .form-group input {
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        /* Input Focus */
        #checkout_form .form-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
        }

        /* Submit Button */
        #checkout_form input[type="submit"] {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        #checkout_form input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Customizing Required Fields */
        #checkout_form .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        /* Column Padding */
        #checkout_form .col-md-2,
        #checkout_form .col-md-3 {
            padding-right: 15px;
            padding-left: 15px;
        }

        /* Adjustments for Small Screens */
        @media (max-width: 767.98px) {

            #checkout_form .col-md-2,
            #checkout_form .col-md-3 {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<?php include '_include_form.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php' ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $PAGE_TITLE2 ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $PAGE_TITLE2 ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">

                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="card qmbgtheme">
                                <div class="card-header">

                                </div>
                                <div class="card-body">
                                    <h3>Instruction:</h3>
                                    <div class="m-3">
                                        <!-- <li class="text-danger" style="font-weight: bold;">Please complete the process by completing the payment with $0.</li>
                                        <li class="text-danger" style="font-weight: bold;">This step is mandatory for the platinum plan.</li> -->
                                        <li class="text-danger" style="font-weight: bold;">To store your payment for future use we require a one time payment for $0</li>
                                        <li class="text-danger" style="font-weight: bold;">This is the final registration step for QM Platinum</li>
                                        <li class="text-danger" style="font-weight: bold;">Please put the expiry date in YYMM format ex. 2913.</li>
                                    </div>
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <div class="row">

                                        <!-- /.col -->
                                        <div class="col-md-12">
                                            <form action="https://services.epx.com/browserpost/" method="post" id="checkout_form">
                                                <h3 class="">Payment</h3>
                                                <label for="ccnum"><span class="text-danger">Amount : $0.00 *</span></label>
                                                <div class="form-row">

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


                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="ccnum">Credit card number <span class="text-danger">*</span></label>
                                                            <input type="text" required id="ACCOUNT_NBR" class="form-control" name="ACCOUNT_NBR" placeholder="1111-2222-3333-4444">

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="expyear"><span class="text-danger">Exp Year(Exp year in MMYY format)</span></label>
                                                            <input type="text" maxlength="4" onkeypress="return numbersonly(event);" class="form-control" required id="EXP_DATE" name="EXP_DATE" placeholder="MMYY">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="cvv">CVV <span class="text-danger">*</span></label>
                                                            <input type="text" required id="CVV2" name="CVV2" class="form-control" placeholder="352">

                                                        </div>
                                                    </div>

                                                    <br>
                                                    <br>

                                                </div>
                                                <input type="submit" value="Continue" class="btn btn-success">
                                            </form>

                                            <!-- /.card -->
                                        </div>
                                        <!-- /.col -->
                                    </div>



                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>


                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?php include 'load.footer.php' ?>


    </div>
    <?php include 'load.scripts.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
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
            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#EXP_DATE').val(newExp);
            }

            return ret_val;
        });
    </script>
    <script>
        $(document).ready(function() {

        });
    </script>
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