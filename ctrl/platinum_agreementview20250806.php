<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
$PAGE_TITLE2 = 'Platinum Agreement';
$MEMORY_TAG = "AGRREMENT";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'service_providers_disp.php';
$edit_url = 'platinum_agreementview.php';

$mode = 'E';
if (isset($_POST['mode']))
    $mode = $_POST['mode'];

$id = '';
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_POST['txtid'])) $id = $_POST['txtid'];

if (empty($id)) {
    header('location:' . $disp_url);
    exit;
}


$Agrrementdone = $signature = $dtsignature = '';
$sign_display_style = 'display:none;';
$signature_pad_style = '';
$bank_name = '';
$account_num = '';
$Agrrementdone = GetXFromYID("select cPlatinumAgreement from service_providers where id='$id' ");

if ($Agrrementdone != 'Y') {
    $_SESSION[PROJ_SESSION_ID]->alert_info = "Agreement not sign yet";
    header("location: $disp_url");
    exit;
}



$SP_DATA = GetDataFromCOND('service_providers', " and id='$id' ");

if ($Agrrementdone == 'Y') {
    $signature = GetXFromYID("select vSign from service_providers where id='$id' ");
    $dtsignature = GetXFromYID("select dtAgreement from service_providers where id='$id' ");
    $bank_name = GetXFromYID("select vBankName from service_providers where id='$id' ");
    $account_num = GetXFromYID("select vAcctNum from service_providers where id='$id' ");
    $signature_pad_style = 'display:none;';
    $sign_display_style = '';
}


if ($mode == 'U') {

    $q = "update service_providers set cPlatinumAgreement='N' where id='$id' ";
    sql_query($q);
    $_SESSION[PROJ_SESSION_ID]->success_info = "Agreement Status changes to N ";
    header("location: $disp_url");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        p {
            margin: 0 0 1em 0;
        }

        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
        }

        canvas {
            border: 1px solid #ccc;
            display: block;
            margin: 20px auto;
            max-width: 100%;
            /* Make sure the canvas doesn't exceed screen width */
            height: auto;
            /* Allow the height to scale based on the width */
        }
    </style>
</head>

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

                                <form action="<?php echo $edit_url ?>" onsubmit="return ValidateForm();" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="txtid" value="<?php echo $id; ?>">
                                    <div class="card-body">
                                        <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                        <div id="alert_message"></div>
                                        <div class="container mt-5">
                                            <h1 class="text-center text-danger">Preauthorization for Automated Debit (PAD) Agreement</h1>
                                            <h2 class="text-center text-danger">Quote Masters Platinum Program</h2>

                                            <h3>Customer Information</h3>
                                            <div class="form-group">
                                                <label for="customer-name">Name:</label>
                                                <input type="text" class="form-control" id="customer-name" readonly value="<?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-account">Last 4 Digits of Bank or Credit Card (Used for Leads) <span class="text-danger">*</span>:</label>
                                                <input type="text" value="<?php echo $account_num; ?>" name="bank-account" class="form-control" id="bank-account" placeholder="Insert Account Number">
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-name">Bank Name<span class="text-danger">*</span>:</label>
                                                <input type="text" value="<?php echo $bank_name; ?>" name="bank-name" class="form-control" id="bank-name" placeholder="Insert Bank Name">
                                            </div>

                                            <h3>Authorization to Debit Bank Account</h3>
                                            <p>By signing this agreement, you, <span style="font-weight: bold;"><?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?></span>, authorize Quote Masters to debit the specified bank account listed above as part of the Quote Masters Platinum program, based on the terms and conditions outlined below.</p>

                                            <p><strong>PAD Category:</strong> Business</p>
                                            <p><strong>Amount of Payment:</strong> $125 per transaction.</p>
                                            <p><strong>Payment Schedule:</strong> Payment Deducted 24 hours after the scheduled appointment if no credit is requested. See “CREDITS” section below. </p>

                                            <h3>Appointment Scheduling Criteria</h3>
                                            <p>Appointments may be scheduled and charged for cleaning services with a frequency of 1 to 7 times per week. Appointments may be booked for any day or time, except:</p>
                                            <ul>
                                                <li>Days specified as unavailable in "Additional Parameters."</li>
                                                <li>If less than two hours from any "Event" listed on the calendar synchronized with The Quote Masters.</li>
                                                <li>Based on hour preferences outlined in "Additional Parameters."</li>
                                            </ul>
                                            <p>It is the Service Provider's responsibility to keep their availability updated. No credit will be provided if the Service Provider fails to update their schedule, resulting in missed or conflicting appointments.</p>

                                            <section>
                                                <h3>Billing</h3>
                                                <p><strong>Frequency of Payment:</strong> Based on the lead frequency selected in your 'Lead Preferences.'</p>
                                                <p>
                                                    <strong>Nonpayment:</strong> In the event that the Service Provider’s payment is declined,
                                                    Quote Masters will discontinue services until payment is satisfied.
                                                    If the Service Provider is unable to make a satisfactory payment (balance paid in full)
                                                    on two consecutive occasions, we reserve the right to refuse any further services to the Provider.
                                                </p>
                                            </section>

                                            <section>
                                                <h3>Preferences</h3>
                                                <p>
                                                    The preferences that will govern your scheduling and subsequent Automated Debit Transactions (ACH)
                                                    are based on the information provided in your registration form. These preferences include:
                                                </p>
                                                <ul>
                                                    <li>Lead Volume</li>
                                                    <li>Industry Exclusions</li>
                                                    <li>Zip Code Coverage Area</li>
                                                    <li>Scheduling Preferences</li>
                                                </ul>
                                                <p>These preferences will be used for automated scheduling and billing. Changes take effect two weeks after they are made.</p>
                                            </section>

                                            <section>
                                                <h3>Credits</h3>
                                                <p><strong>If you were not able to submit a proposal/quote:</strong></p>
                                                <p>Service Provider must submit the following within 24 hours of the scheduled appointment in question:</p>
                                                <ul>
                                                    <li>Date and Time of Appointment</li>
                                                    <li>Point of Contact Name and Company</li>
                                                    <li>Reason for Credit:</li>
                                                    <ul>
                                                        <li>Point of contact was unavailable</li>
                                                        <li>Point of contact refused a meeting</li>
                                                        <li>Point of contact cancelled meeting</li>
                                                        <li>Business is not at a commercial office location</li>
                                                        <li>Point of contact requested less than 1 time per week service</li>
                                                        <li>Business is a current customer of Service Provider’s company</li>
                                                        <li>Business Type was on Service Provider’s Do Not Contact list prior to booking</li>
                                                    </ul>
                                                </ul>
                                                <p>All Credit requests must be emailed to <a href="mailto:service@thequotemasters.com">service@thequotemasters.com</a>.</p>
                                            </section>

                                            <h3>Definitions</h3>
                                            <p><strong>"We"</strong> refers to The Quote Masters, including its employees, systems, and processes.</p>
                                            <p><strong>"You"</strong> refers to the Service Provider entering into this agreement.</p>
                                            <p><strong>"Our System"</strong> refers to the suite of tools used by The Quote Masters to facilitate scheduling.</p>

                                            <h3>How to Cancel This Agreement</h3>
                                            <p>You may cancel this Preauthorization for Automated Debit (PAD) at any time by providing Quote Masters with 10 days' written notice. To cancel, contact us at:</p>
                                            <p>Phone:(866) 958-8773</p>
                                            <p>Email: ops@thequotemasters.com</p>
                                            <p>Mailing Address: ops@thequotemasters.com</p>
                                            <p>8875 Hidden River Parkway, </p>
                                            <p>Tampa FL 33637 </p>

                                            <h3>Recourse Statement</h3>
                                            <p>You, the Customer, have certain recourse rights if any debit does not comply with this agreement. Contact your financial institution for more information.</p>


                                            <div class="row mt-4">
                                                <!-- <div class="row mlm-150"> -->
                                                <div class="col-md-12">

                                                    <span class="text-danger">Signed by <?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?>
                                                        <b>DATE:<?php echo date('m-d-Y', strtotime($dtsignature)); ?></b>

                                                </div>

                                                <!-- </div> -->


                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">


                                                    <center>
                                                        <div class="custom-signature-upload" style="<?php echo $signature_pad_style;  ?>">
                                                            <div class="wrapper">
                                                                <canvas id="signature-pad1" style="border:1px solid;"></canvas>

                                                            </div>
                                                            <div class="clear-btn">
                                                                <button id="clear_sign1"><span> Clear </span></button>
                                                            </div>
                                                        </div>
                                                        <div class="custom-signature-upload" style="<?php echo $sign_display_style; ?>">
                                                            <div class="wrapper1">
                                                                <img src="<?php echo SIGN_PATH . $signature; ?>" width="95%" height="95%" alt="Italian Trulli">
                                                            </div>
                                                        </div>
                                                    </center>
                                                    </center>

                                                </div>

                                            </div>
                                        </div>
                                        <input type="hidden" name="mode" value="U">
                                        <textarea id="signature1" name="signature1" style="display: none"></textarea>
                                        <?php
                                        if ($sess_user_level == '1' || $sess_user_level == '0') { ?>
                                            <button type="submit" class="btn btn-danger">Remove Signature</button>
                                        <?php } ?>

                                    </div><!-- /.card-body -->
                                </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var canvas = document.getElementById("signature-pad");
        var canvas1 = document.getElementById("signature-pad1");

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);

            // Set canvas width dynamically based on the parent container or viewport
            var canvasWidth = Math.min(window.innerWidth * 0.9, 600); // 90% of the screen width or max 600px
            var canvasHeight = canvasWidth * 0.5; // Set height to a proportion of the width (e.g., 50%)

            canvas1.width = canvasWidth * ratio;
            canvas1.height = canvasHeight * ratio;
            canvas1.style.width = canvasWidth + "px"; // Set the display size
            canvas1.style.height = canvasHeight + "px"; // Set the display size

            canvas1.getContext("2d").scale(ratio, ratio);
        }
        window.onresize = resizeCanvas;
        resizeCanvas();



        var signaturePad1 = new SignaturePad(canvas1, {
            backgroundColor: 'rgb(250,250,250)'
        });

        document.getElementById("clear_sign1").addEventListener('click', function() {
            signaturePad1.clear();
            document.getElementById('signature1').value = '';
        })

        function validate() {
            var canvas = document.getElementById("signature-pad1");
            var data1 = canvas.toDataURL('image/png');
            //console.log(data1);
            document.getElementById('signature1').value = data1;
            return false;
        }
    </script>
    <script>
        function ValidateForm() {
            var ret_val = true;
            var err = 0;
            var bank_account = $('#bank-account');
            var bank_name = $('#bank-name');
            var signature = $('#signature1');


            if (err > 0) {
                ret_val = false;
            }

            //console.log(err);

            return ret_val;
        }
        $(document).ready(function() {
            //fetch_data();

        });
    </script>
</body>

</html>

<!-- ALTER TABLE `qm20231221`.`service_providers`
ADD COLUMN `vBankName` varchar(255) NULL DEFAULT NULL;
SHOW CREATE TABLE `qm20231221`.`service_providers`;
SELECT * FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='qm20231221' AND `TABLE_NAME` IN ('service_providers');
ALTER TABLE `qm20231221`.`service_providers`
ADD COLUMN `vAcctNum` varchar(255) NULL DEFAULT NULL;
SHOW CREATE TABLE `qm20231221`.`service_providers`; -->