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
$bank_name = '';
$account_num = '';
$Agrrementdone = GetXFromYID("select cPlatinumAgreement from service_providers where id='$sess_user_id' ");

$SP_DATA = GetDataFromCOND('service_providers', " and id='$sess_user_id' ");

if ($Agrrementdone == 'Y') {
    $signature = GetXFromYID("select vSign from service_providers where id='$sess_user_id' ");
    $dtsignature = GetXFromYID("select dtAgreement from service_providers where id='$sess_user_id' ");
    $bank_name = GetXFromYID("select vBankName from service_providers where id='$sess_user_id' ");
    $account_num = GetXFromYID("select vAcctNum from service_providers where id='$sess_user_id' ");
    $signature_pad_style = 'display:none;';
    $sign_display_style = '';
}


if ($mode == 'U') {
    // DFA($_POST);
    // exit;
    // [bank-account] => ewrewr
    // [bank-name] => eeee
    $bank_name = $_POST['bank-name'];
    $account_num = $_POST['bank-account'];
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

    $q = "update service_providers set cPlatinumAgreement='Y',dtAgreement=NOW(),vSign='$file',vBankName='$bank_name',vAcctNum='$account_num' where id='$sess_user_id' ";
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

                                <form action="<?php echo $edit_url ?>" onsubmit="return ValidateForm();" method="post" enctype="multipart/form-data" id="multiStepForm">
                                    <div class="card-body">
                                        <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                        <div id="alert_message"></div>

                                        <!-- Step 1 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Customer Information</h2>
                                            <div class="form-group">
                                                <label for="customer-name">Name:</label>
                                                <input type="text" class="form-control" id="customer-name" readonly
                                                    value="<?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-account">Last 4 Digits of Bank or Credit Card <span class="text-danger">*</span>:</label>
                                                <input type="text" value="<?php echo $account_num; ?>" name="bank-account"
                                                    class="form-control required-step" id="bank-account" placeholder="Insert Account Number">
                                            </div>
                                            <div class="form-group">
                                                <label for="bank-name">Bank Name<span class="text-danger">*</span>:</label>
                                                <input type="text" value="<?php echo $bank_name; ?>" name="bank-name"
                                                    class="form-control required-step" id="bank-name" placeholder="Insert Bank Name">
                                            </div>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Authorization to Debit Bank Account</h2>
                                            <p>By signing this agreement, you, , authorize Quote Masters to debit the specified bank account listed above as part of the Quote Masters Platinum program, based on the terms and conditions outlined below.</p>
                                            <strong>PAD Category:</strong> Business
                                            <br>
                                            <strong>Amount of Payment:</strong> $125 per transaction.
                                            Payment Schedule: Payment Deducted 24 hours after the scheduled appointment if no credit is requested. See “CREDITS” section below.

                                        </div>

                                        <!-- Step 3 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Appointment Scheduling Criteria - Lead Parameters</h2>
                                            <ul>
                                                <li><span class="text-danger">Appointment scheduling is automated</span> based on the parameters outlined in this section. </li>
                                                <li>We will not verify with you on an individual appointment basis. We will use your parameters and calendar.</li>
                                                <li> will be charged for appointments scheduled based on these parameters.
                                                </li>
                                            </ul>
                                            <p>Appointments may be scheduled and charged for cleaning services with a frequency of 1 to 7 times per week. Appointments may be booked for any day or time, except:</p>
                                            <ul>
                                                <li>Days specified as unavailable in "Additional Parameters."</li>
                                                <li>If less than two hours from any "Event" listed on the calendar synchronized with The Quote Masters.</li>
                                                <li>Based on hour preferences outlined in "Additional Parameters."</li>
                                            </ul>
                                            <p>It is the Service Provider's responsibility to keep their availability updated. No credit will be provided if the Service Provider fails to update their schedule, resulting in missed or conflicting appointments.</p>
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Billing</h2>
                                            <p>
                                                Registration Fee: <strong>$250</strong> is collected upon registration. This is kept as payment insurance.
                                                If you decide to discontinue our services, the fee is refunded in full, less any outstanding balance.
                                                <em>See Below:</em>
                                            </p>

                                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; max-width: 500px; text-align: center; border: 1px solid #ccc; padding: 10px;">
                                                <div><strong>Description</strong></div>
                                                <div><strong>Original</strong></div>
                                                <div><strong>Final</strong></div>

                                                <div>Fee</div>
                                                <div>$250</div>
                                                <div>$250</div>

                                                <div>Balance</div>
                                                <div>$0</div>
                                                <div>-$128.50 (Unpaid)</div>

                                                <div>Returned</div>
                                                <div>$250</div>
                                                <div>$121.50</div>
                                            </div>


                                            <p><strong>Frequency of Payment:</strong> Based on the lead frequency selected in your 'Lead Preferences.'</p>
                                            <p>
                                                <strong>Nonpayment:</strong> In the event that the Service Provider’s payment is declined,
                                                Quote Masters will discontinue services until payment is satisfied.
                                                If the Service Provider is unable to make a satisfactory payment (balance paid in full)
                                                on two consecutive occasions, we reserve the right to refuse any further services to the Provider.
                                            </p>
                                        </div>

                                        <!-- Step 5 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Preferences</h2>
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
                                            <p>These preferences will be used for automated scheduling and billing. Changes take effect two weeks after they are made/requested.</p>
                                        </div>

                                        <!-- Step 6 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Notifications</h2>

                                            <strong>Service Providers:</strong>
                                            <p>It is the responsibility of the Service Provider to maintain accountability of all scheduled appointments. We use multiple channels to notify Service Providers about each appointment. These channels are:</p>

                                            <strong>Calendar Invitation</strong>
                                            <p>If the Service Provider has shared their Google Calendar with us, they will be included on the same event invitation as the customer (decision maker).</p>

                                            <strong>Email Notification</strong>
                                            <p>All Service Providers are emailed with the full details of an appointment as soon as they are booked for it.</p>

                                            <strong>Phone Call</strong>
                                            <p>If an appointment is 2 days from scheduling or less, the Service Provider assigned will receive a phone call notifying them of the scheduling. Failure to return a missed call does not prevent appointment scheduling.</p>

                                            <strong>Customers:</strong>
                                            <p>All customers (Appointments) are notified through multiple channels about their upcoming bids.</p>
                                            <ul>
                                                <li><strong>Calendar Invitation</strong></li>
                                                <li><strong>Email with Service Provider's QM Profile</strong></li>
                                                <li><strong>Phone call at least 24 hours in advance to verify interest and attendance.</strong></li>
                                            </ul>






                                            <strong>NOTE:</strong>
                                            <p>No contact between the Service Provider and the business, or person, they are scheduled with prior to the appointment.<span class="text-danger"> ANY CONTACT PRIOR TO THE APPOINTMENT WILL DISQUALIFY THE SERVICE PROVIDER FROM RECEIVING A CREDIT.</span></p>
                                        </div>

                                        <!-- Step 7 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Credits</h2>
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
                                            <p class="text-danger">The following are not acceptable reasons for a credit:</p>
                                            <ul>

                                                <li>Previously visited or serviced the lead in question</li>
                                                <li>Lead did not receive individual approval from you</li>
                                                <li>Size of facility did not meet expectations</li>
                                                <li>Staffing Shortages</li>
                                                <li>Outside of preferred frequency</li>

                                            </ul>

                                        </div>

                                        <div class="step">
                                            <h2 class="text-center text-danger">Definitions</h2>
                                            <p><strong>"We"</strong> refers to The Quote Masters, including its employees, systems, and processes.</p>
                                            <p><strong>"You"</strong> refers to the Service Provider entering into this agreement.</p>
                                            <p><strong>"Our System"</strong> refers to the suite of tools used by The Quote Masters to facilitate scheduling.</p>

                                        </div>
                                        <div class="step">
                                            <h2 class="text-center text-danger">How to Cancel This Agreement</h2>
                                            <p>You may cancel this Preauthorization for Automated Debit (PAD) at any time by providing Quote Masters with 10 days' written notice. To cancel, contact us at:</p>
                                            <p>Phone:(866) 958-8773</p>
                                            <p>Email: ops@thequotemasters.com</p>
                                            <p>Mailing Address: ops@thequotemasters.com</p>
                                            <p>8875 Hidden River Parkway, </p>
                                            <p>Tampa FL 33637 </p>
                                        </div>

                                        <div class="step">
                                            <h2 class="text-center text-danger"> Recourse Statement</h2>
                                            <p>You, the Customer, have certain recourse rights if any debit does not comply with this agreement. Contact your financial institution for more information.</p>
                                        </div>

                                        <!-- Step 8 -->
                                        <div class="step">
                                            <h2 class="text-center text-danger">Terms & Signature</h2>
                                            <?php if ($Agrrementdone != 'Y') { ?>
                                                <div class="row mt-4">
                                                    <div class="col-md-8 mb-4">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" id="agreeTerms" name="terms" value="agree" class="required-step">
                                                            <label for="agreeTerms" class="text-dark">
                                                                I agree to the <a href="javascript:void()" data-toggle="modal" data-target="#SP-terms-modal">terms</a>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <span class="text-danger">Signed by <?php echo $SP_DATA[0]->First_name . ' ' . $SP_DATA[0]->Last_name; ?>
                                                        <b>DATE:<?php echo date('m-d-Y', strtotime($dtsignature)); ?></b>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <div class="custom-signature-upload" style="<?php echo $signature_pad_style; ?>">
                                                        <canvas id="signature-pad1" style="border:1px solid;"></canvas>
                                                        <div class="clear-btn">
                                                            <button type="button" id="clear_sign1">Clear</button>
                                                        </div>
                                                    </div>
                                                    <div class="custom-signature-upload" style="<?php echo $sign_display_style; ?>">
                                                        <img src="<?php echo SIGN_PATH . $signature; ?>" width="95%" height="95%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Navigation Buttons -->
                                        <div class="mt-4 text-center">
                                            <button type="button" class="btn btn-secondary" id="prevBtn">Previous</button>
                                            <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                                            <?php if ($Agrrementdone != 'Y') { ?>
                                                <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Submit</button>
                                            <?php } ?>
                                        </div>

                                        <input type="hidden" name="mode" value="U">
                                        <textarea id="signature1" name="signature1" style="display:none"></textarea>
                                    </div>
                                </form>



                                <style>
                                    .is-invalid {
                                        border-color: red !important;
                                    }
                                </style>




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
            var agree = $('#agreeTerms');


            if (!(agree.is(":checked"))) {
                err++;
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    subtitle: '',
                    body: 'Please  agree to the terms .',
                    delay: 8000, // 3 seconds
                    autohide: true
                })
            }

            if ($.trim(bank_account.val()) == '') {
                ShowError(bank_account, "Please enter your account number");
                err++;
            } else {
                HideError(bank_account);
            }

            if ($.trim(bank_name.val()) == '') {
                ShowError(bank_name, "Please enter your bank name");
                err++;
            } else HideError(bank_name);

            validate();

            if ($.trim(signature.val()) == '') {
                alert("Please complete the signature process");
                err++;
            } //else HideError(signature);

            //console.log($.trim(signature.val()));


            //alert("Please enter the bank account number");

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
    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".step");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const submitBtn = document.getElementById("submitBtn");

        function showStep(n) {
            steps.forEach((step, i) => {
                step.style.display = (i === n) ? "block" : "none";
            });
            prevBtn.style.display = (n === 0) ? "none" : "inline-block";
            nextBtn.style.display = (n === steps.length - 1) ? "none" : "inline-block";
            submitBtn.style.display = (n === steps.length - 1) ? "inline-block" : "none";
        }

        function validateStep(n) {
            let valid = true;
            let stepInputs = steps[n].querySelectorAll(".required-step");

            stepInputs.forEach(input => {
                if ((input.type === "checkbox" && !input.checked) || (input.value.trim() === "")) {
                    input.classList.add("is-invalid");
                    valid = false;
                } else {
                    input.classList.remove("is-invalid");
                }
            });

            return valid;
        }

        nextBtn.addEventListener("click", () => {
            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            } else {
                alert("Please complete required fields before proceeding.");
            }
        });

        prevBtn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Initialize
        showStep(currentStep);
    </script>
    <div class="modal fade" id="SP-terms-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">SERVICE PROVIDER AGREEMENT</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p>This Service Agreement (the “Agreement”) is entered into by and between QUOTE
                        MASTERS LLC, a Florida limited liability company (“Quote Masters”), and Service Provider.</p>
                    <p>Quote Masters is an online company which provides a quick and easy way for Consumers to find a service provider who is interested in accepting a request for janitorial services. The goal is to connect the Consumer and Service Provider, for the bidding on a project. Should the Consumer select them to perform the work, all contracts, communications, and contractual obligations are between the Consumer and the Service Provider.
                    </p>
                    <ol>
                        <li>
                            <strong>Role of Quote Masters:</strong> Quote Masters sole responsibility to Service
                            Provider is to connect Service Provider and a consumer for the provision of services. Quote
                            Masters makes no representation as to the consumer. Service Provider is solely responsible for
                            all communications and negotiations between Service Provider and consumer, as Quote Masters
                            acts only as a facilitator of the introductions between Service Provider and consumer. The
                            provision of any services by the Service Provider will be subject to an entirely separate contract
                            and/or agreement. If there are any issues with the performance of those services by Service
                            Provider and/or consumer, then any claims and/or rights Service Provider may have will be
                            solely against the consumer and not against Quote Masters. Quote Masters bears no
                            responsibility with respect to the Consumer, work/services, or any related transaction.
                        </li><br>
                        <li>
                            <strong>Lead Generation:</strong> Below is a description of how the Lead Generation will work:
                            <ul>
                                <li>A consumer will submit a proposed for work or janitorial related services
                                    (“Services”) through Quote Masters’ website.</li>
                                <li>Quote Masters will compile basic information, including Consumer’s approximate location and the description of the Consumer’s Project (the “Lead”), and send the Lead via email or Google Calendar invitation, to eligible service providers that perform the type of services described in the Lead in the area in which the Consumer is located. The number of service providers to which Quote Masters will send the Lead may vary, in Quote Masters’ sole discretion, depending on a number of factors.
                                </li>
                                <li>Service Provider will then purchase the Lead from Quote Masters based upon
                                    the fee schedule outlined below in Paragraph 3 below (Lead Fee).<strong> The
                                        payment for the Lead is for the scheduling of appointments with Consumer.</strong></li>
                                <li>All Leads are set based on parameters deemed fit by Quote Masters. These parameters may change based on industry evolution.</li>
                            </ul>
                        </li><br>
                        <li>
                            <strong>Lead Fee:</strong> Service Provider hereby agrees that it will be required to pay Quote Masters a flat fee (“Fee”) for each Lead (defined herein below) purchased. The Fee shall be determined by how many appointments are scheduled. A fee of $125.00 will be due to Quote Masters if the Consumer schedules recurring services for frequencies from 1 x per week up to frequencies of 7x per week.The Fee will be deducted in accordance with the Preauthorization for Debit agreement no less than 24 hours from the appointment in question, unless the appointment qualifies for credit.

                        </li><br>
                        <!-- <p style="font-style: italic;"> -->
                        <ul>
                            <li>The Fee is non-refundable unless an appointment was charged or scheduled in error.</li>
                            <li>Also, if customer requests for a reschedule and offers a different date and time than what was previously requested and accepted by the Service Provider, and if the Service Provider is not able to accommodate the new suggested date and time, the Service Provider will removed from the booking.
                            </li>
                            <li><span class="text-danger">If you (Service Provider) attempt to contact the customer before the scheduled meeting you will not receive credit no matter the outcome of your contact.</span></li>
                            <li>Finally, if an “Act of God” such as earthquake, tornado, hurricane or any natural disaster causes appointment to be cancelled and a new meeting time is found by the consumer, but the service provider is unable to attend then a credit will be awarded.</li>

                        </ul>

                        <!-- </p> -->
                        <li>
                            <strong>Eligibility Requirements:</strong> Service Provider hereby represents, warrants, covenants, and agrees that, at the time of Lead Generation, it:
                            <ul>
                                <li>Possesses all applicable state and local licensing, registration, insurance,
                                    bonding, or other trade requirements to provide the work and/or services as
                                    described in the Lead;</li>
                                <li>Is willing and able to complete the work and/or services described in the Lead to Consumer’s satisfaction at the Consumer’s location;</li>
                                <li>Will abide by all applicable federal, state, or local laws, rules, and regulations;</li>
                                <li>Will maintain a completed Form W-9.</li>
                            </ul>
                        </li><br>
                        <li>
                            <strong>Provision of Services:</strong> Service Provider agrees to, at all times, perform the
                            services obtained through the Lead Generation in a good and workmanlike manner, consistent
                            with the best practices and highest level of service available in the relevant industry, and shall be
                            solely and independently responsible for such performance. Service Provider shall commence
                            performance of the work and/or services for a Consumer within the time frame agreed to
                            between Service Provider and the Consumer. Notwithstanding the foregoing, all services
                            performed for a Consumer shall be performed pursuant to a written contract between Service
                            Provider and Consumer.
                        </li><br>
                        <li>
                            <strong>Publication and Distribution of Content:</strong> Quote Masters does not guarantee
                            the accuracy, integrity, quality or appropriateness of any content transmitted to or through its
                            website. Service Provider acknowledges that Quote Masters simply acts as a passive conduit and
                            an interactive computer service provider for the publication and distribution of content posted by
                            Service Provider or Consumer. Service Provider understands that all content posted on,
                            transmitted through, or linked through Quote Masters’ website, are the sole responsibility of the
                            person from whom such content originated. Service Provider further acknowledges that Quote
                            Masters has no obligation to screen, preview, monitor or approve any content published by
                            Service Provider, Consumer, or third party.
                        </li><br>
                        <li>
                            <strong>Representations by Service Provider:</strong> Service Provider hereby represents
                            and warrants to Quote Masters that: (a) it has full power, authority, and legal capacity to execute
                            and deliver this Agreement; (b) it is legally and properly licensed to and possesses all requisite
                            licenses and permits to provide the work and/or services described in the Lead; and (c) none of its trademarks, service-marks, logo or other marks used in advertisements infringe or violate any
                            other person’s or entity’s intellectual property rights.
                        </li><br>
                        <li>
                            <strong>Indemnification by Service Provider:</strong> Service Provider hereby agrees to
                            indemnify, defend and hold harmless Quote Masters and its respective directors, managers,
                            officers, stockholders, employees, agents, and insurers from and against any and all claims,
                            demands, actions, losses, expenses, damages, liabilities, costs (including, without limitation,
                            interest, penalties and attorneys’ fees) and/or judgments incurred or suffered by any of the
                            indemnitees that result from or arise out of, directly or indirectly, (i) any breach of any
                            representation and warranty made by Service Provider in this Agreement; (ii) any breach by
                            Service Provider of any covenant or agreement under this Agreement; (iii) the failure to perform
                            services for any Consumer of Quote Masters or any other persons; (iv) failure or refusal to honor
                            any quote made to a Consumer; or (v) any negligence or willful misconduct by Service Provider.
                        </li><br>
                        <li>
                            <strong>Quote Masters’ Limitation of Liability:</strong> IN NO EVENT SHALL QUOTE
                            MASTERS BE LIABLE TO YOU FOR LOSS OF PROFITS, LOSS OF BUSINESS
                            OPPORTUNITY, INDIRECT DAMAGES, PUNITIVE DAMAGES, OR CONSEQUENTIAL
                            DAMAGES OR SPECIAL LOSSES, WHETHER BASED UPON A CLAIM FOR BREACH
                            OF WARRANTY, CONTRACT, TORT OR ANY OTHER LEGAL OR EQUITABLE CLAIM
                            RELATING TO THIS AGREEMENT, THE RELEVANT GOODS OR SERVICES OR
                            PERFORMANCE HEREUNDER.
                        </li><br>
                        <li>
                            <strong>Relationship of Parties:</strong> Subject to the terms of this Agreement, Service
                            Provider shall be solely responsible for determining the manner and method by which it shall
                            perform the work and/or services, and the setting and ultimate collection of its compensation that
                            it charges a Consumer for the services, subject to the terms and conditions of its service contract
                            with the Consumer. Quote Masters is not a general contractor, provider of services, or merchant
                            of record and is acting solely in its capacity as a system administrator for Service Provider and
                            Consumer for the purpose of enabling superior service and for marketing and advertising the
                            services on Service Provider’s behalf. Nothing contained in this Agreement shall be deemed to
                            constitute either party a partner, joint venturer or employee of the other party for any purpose.
                        </li><br>
                        <li>
                            <strong>Confidentiality:</strong> Service Provider agrees that the terms and conditions of
                            this Agreement (“Confidential Information”) shall be held in strict confidence, for the mutual
                            benefit of Service Provider and Quote Masters, and Service Provider shall not disclose any
                            Confidential Information without the prior written consent of Quote Masters. Notwithstanding
                            the foregoing, Service Provider may disclose Confidential Information only to the extent strictly
                            necessary to comply with any order of a court of competent jurisdiction or as may otherwise
                            required by applicable law.
                        </li><br>
                        <li>
                            <strong>Remedies:</strong> Service Provider agrees that the Confidential Information is
                            important, material, confidential and gravely affects the effective and successful conduct of
                            Quote Masters’ business and affects its value, reputation and goodwill. If Service Provider,
                            including its employees and/or agents, should breach any provision of this Agreement, Quote
                            Masters shall be entitled to obtain temporary and permanent injunctions, specific performances,
                            costs, and reasonable attorney’s fees at all levels, including but not limited to appeals. Service
                            Provider agrees that if it breaches any provision of this Agreement, it shall be conclusively
                            presumed that irreparable injury would result to Quote Masters and there would be no adequate
                            remedy at law. Notwithstanding the foregoing, this Agreement shall not limit the rights and
                            remedies that Quote Masters otherwise has by law, equity or statute, including an action for
                            damages, in which the prevailing party thereto shall be entitled to recover its reasonable
                            attorney’s fees at all levels, including but not limited to appeals.
                        </li><br>
                        <li>
                            <strong>Assignment:</strong> This Agreement may not be assigned or otherwise transferred without prior written consent of Quote Masters.
                        </li><br>
                        <li><strong>Severability:</strong> If a court finds any provision of this Agreement invalid or
                            unenforceable, the remainder of this Agreement shall be interpreted so as best to effect the intent
                            of the parties.</li><br>
                        <li>
                            <strong>Integration:</strong> This Agreement expresses the complete understanding of the
                            parties with respect to the subject matter and supersedes all prior proposals, agreements,
                            representations, and understandings. This Agreement may not be amended except in a writing
                            signed by both parties.
                        </li><br>
                        <li>
                            <strong>Waiver:</strong> The failure by any party to insist upon or enforce any of its rights
                            shall not constitute a waiver thereof by the party; and nothing shall constitute waiver of the
                            party’s right to insist upon strict compliance with the provisions hereof.
                        </li><br>
                        <li><strong>Provisions Remaining in Effect:</strong> This Agreement and the terms herein survive any termination of the Agreement.</li><br>
                        <li><strong>Binding Effect:</strong> This Agreement and the rights and obligations created
                            hereunder shall be binding upon and inure solely to the benefit of the parties and their respective
                            successors and permitted assigns, and no other person shall acquire or have any right under or by
                            virtue of this Agreement.</li><br>
                        <li><strong>Effective Date:</strong> The Effective Date of this Agreement shall be the date of execution by the last party whose signature is required hereto.</li><br>
                        <li><strong>Counterpart Execution:</strong> This Agreement may be executed in two or more
                            counterparts, each of which shall be deemed an original, but all of which together shall constitute
                            one and the same instrument.</li><br>
                        <li>
                            <strong>Notices:</strong> Any notice expressly provided for or permitted under this
                            Agreement shall be in writing, and shall be given personally or by U.S. certified mail (return
                            receipt requested), and, if mailed, shall be deemed received by the party to be notified five (5)
                            days after being deposited with the United States Postal Service, at its address set forth below:
                            <br><strong>As to Quote Masters:</strong><br>
                            <!-- Michael J. Chartrand <br> -->
                            8875 Hidden River Parkway, <br>
                            Tampa FL 33637 <br>
                            <br><strong>As to Service Provider:</strong>
                        </li><br>
                        ------------------------------------<br>
                        ------------------------------------<br>

                        <li>
                            <strong>Governing Law:</strong> This Agreement shall be construed and enforced in
                            accordance with, and governed by, the laws of the State of Florida. Exclusive venue for any
                            litigation arising under or in connection with this Agreement shall be Polk County, Florida, and
                            the parties hereby waive their rights to assert venue in any other jurisdiction.
                        </li><br>
                        <li><strong>Headings:</strong> The headings of the paragraphs of the Agreement are inserted for convenience only and shall not be deemed to constitute a party hereof.</li>
                        <li><strong>Refunds / Credits:</strong> Refunds / Credits can take from 5 to 7 business days.Credits are issued in accordance with the Preauthorization for Debit agreement.</li>
                    </ol>
                    <p>IN WITNESS WHEREOF, the parties have executed this Agreement indicated below.</p>
                    <div class="col-md-6 col-12">
                        <p><strong>QUOTE MASTERS LLC</strong>, a Florida limited liability company</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <strong>SERVICE PROVIDER</strong>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>

<!-- ALTER TABLE `qm20231221`.`service_providers`
ADD COLUMN `vBankName` varchar(255) NULL DEFAULT NULL;
SHOW CREATE TABLE `qm20231221`.`service_providers`;
SELECT * FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='qm20231221' AND `TABLE_NAME` IN ('service_providers');
ALTER TABLE `qm20231221`.`service_providers`
ADD COLUMN `vAcctNum` varchar(255) NULL DEFAULT NULL;
SHOW CREATE TABLE `qm20231221`.`service_providers`; -->