<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
$PAGE_TITLE2 = 'Platinum Agreement';
$MEMORY_TAG = "AGRREMENT";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'v_profile.php';
$edit_url = 'platinum_agreement.php';

$mode = '';
if (isset($_POST['mode']))
    $mode = $_POST['mode'];

$Agrrementdone = $signature = $dtsignature = '';
$sign_display_style = 'display:none;';
$signature_pad_style = '';
$Agrrementdone = GetXFromYID("select cPlatinumAgreement from service_providers where id='$sess_user_id' ");

$SP_DATA = GetDataFromCOND('service_providers', " and id='$sess_user_id' ");

if ($Agrrementdone == 'Y') {
    $signature = GetXFromYID("select vSign from service_providers where id='$sess_user_id' ");
    $dtsignature = GetXFromYID("select dtAgreement from service_providers where id='$sess_user_id' ");
    $signature_pad_style = 'display:none;';
    $sign_display_style = '';
}


if ($mode == 'U') {
    //DFA($_POST);
    $folderPath = "signature/";
    // Upload Signature 
    $image_parts = explode(";base64,", $_POST['signature1']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);

    $imageData = $_POST['signature1'];
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);

    // Need to decode before saving since the data we received is already base64 encoded
    $unencodedData = base64_decode($filteredData);

    $file = $folderPath . $sess_user_id . '_' . uniqid() . '.' . $image_type;

    $fp = fopen($file, 'wb') or die("File does not exist!");;
    fwrite($fp, $unencodedData);
    fclose($fp);

    $q = "update service_providers set cPlatinumAgreement='Y',dtAgreement=NOW(),vSign='$file' where id='$sess_user_id' ";
    sql_query($q);
    $_SESSION[PROJ_SESSION_ID]->success_info = "Agreement Details Successfully submitted";
    header("location: $disp_url");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
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


                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <div>
                                        <h2>Authorization to Debit Bank Account</h2>
                                        <p>You hereby authorize Quote Masters to initiate Automated Clearing House (ACH) debits from the bank account listed above, in accordance with the terms of the Platinum program.</p>

                                        <h3>PAD Category: Business</h3>
                                        <p><strong>Amount of Payment:</strong> $125 per transaction.</p>
                                        <p><strong>Frequency of Payment:</strong> Based on the lead frequency selected in your 'Lead Preferences.'</p>

                                        <h3>How to Cancel This Agreement</h3>
                                        <p>You may cancel this Preauthorization for Automated Debit (PAD) at any time by providing Quote Masters with 10 days' written notice. To cancel this agreement, please contact us at:</p>

                                        <p><strong>Quote Masters Contact Information:</strong><br>
                                            Phone: (866) 958-8773<br>
                                            Email: ops@thequotemasters.com<br>
                                            Mailing Address: ops@thequotemasters.com</p>

                                        <p>Upon cancellation, you must ensure that any outstanding payments due under the Platinum program are settled.</p>

                                        <h3>Recourse Statement</h3>
                                        <p>You, the Customer, have certain recourse rights if any debit does not comply with this agreement. For example, you are entitled to receive reimbursement for any debit that is not authorized or that is inconsistent with this PAD agreement. To obtain more information on your recourse rights, contact your financial institution directly.</p>

                                        <p><strong>Customer Signature:</strong><br>
                                            I agree to the terms and conditions of this Preauthorization for Automated Debit (PAD) agreement.</p>
                                    </div>


                                    <div class="row mt-4">
                                        <!-- <div class="row mlm-150"> -->
                                        <div class="col-6">

                                            <span class="text-danger">Signed and Delivered by <?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?>
                                                <b>DATE:<?php echo date('m-d-Y', strtotime($dtsignature)); ?></b>

                                        </div>
                                        <div class="col-6">


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
                                        <!-- </div> -->


                                    </div>
                                    <form action="<?php echo $edit_url ?>" method="post" enctype="multipart/form-data">

                                        <input type="hidden" name="mode" value="U">
                                        <textarea id="signature1" name="signature1" style="display: none"></textarea>
                                        <?php if ($Agrrementdone != 'Y') { ?>
                                            <button type="submit" onclick="validate();" class="btn btn-success">Submit</button>
                                        <?php } ?>

                                    </form>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var canvas = document.getElementById("signature-pad");
        var canvas1 = document.getElementById("signature-pad1");

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);


            canvas1.width = canvas1.offsetWidth * ratio;
            canvas1.height = canvas1.offsetHeight * ratio;
            canvas1.getContext("2d").scale(ratio, ratio);
        }
        window.onresize = resizeCanvas;
        resizeCanvas();



        var signaturePad1 = new SignaturePad(canvas1, {
            backgroundColor: 'rgb(250,250,250)'
        });

        document.getElementById("clear_sign1").addEventListener('click', function() {
            signaturePad1.clear();
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
        $(document).ready(function() {

            //fetch_data();


            $(document).on('click', '.send_leads', function() {
                alert($(this).data('booking_id'));
                $.ajax({
                    url: '../api/send_leads.php',
                    method: 'POST',
                    data: {
                        bid: $(this).data('booking_id'),
                    },
                    success: function(res) {
                        console.log(res);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });


            });



        });
    </script>
</body>

</html>