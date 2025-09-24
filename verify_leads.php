<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$EncryptID = (isset($_GET['key'])) ? $_GET['key'] : '';
$page_title = 'Verify Leads';
$TITLE = SITE_NAME . ' | ' . $page_title;

$LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'vendor_register.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    <!-- <a href="index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item dropdown m-2">
                    <button class="btn nav-link dropdown-toggle px-2 float-right" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="plogin.php">Provider <i class="fa fa-briefcase" aria-hidden="true"></i></a>
                        <a class="dropdown-item" href="clogin.php">Customer</a>
                    </div>
                </li>';

if (empty($EncryptID)) {
    echo 'Invalid Access';
    exit;
} else {
    $bid = DecryptStr($EncryptID);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row pt-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="LBL_INFO"></div>
                                    <form>
                                        <input type="hidden" name="bid" id="bid" value="<?php echo  $bid; ?>">
                                        <h3>Verify Your Lead</h3>
                                        <label for="opt">Please enter the verification code you received on your email or phone  from Quote Masters</label>
                                        <input type="text" class="form-control" placeholder="Enter the verification code" name="otp" id="otp">
                                        <p class="text-sm mt-3">**Please check your SPAM folder if you haven't received the verification code & still having problems, you may contact this number.</p>
                                        <button type="button" onclick="verify();" class=" btn btn-primary mt-4">confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
        <?php include 'load.scripts.php'; ?>
        <script>
            function verify() {
                var ret = true;
                var bid = $('#bid').val();
                var otp = $('#otp');
                if ($.trim(otp.val()) == '') {
                    ShowError(otp, "Please enter your verification code");
                    ret = false;
                } else {
                    HideError(otp);
                }

                if (ret) {
                    $.ajax({
                        url: '_confirm_lead.php',
                        method: 'POST',
                        data: {
                            bid: $('#bid').val(),
                            otp: otp.val()
                        },
                        success: function(res) {
                            console.log(res);
                            if (res == 1) {
                                alert('Booking successful. Thank you for submitting your inquiry, Our team will contact you via email you have provided.');
                                window.location.href = "clogin.php";

                            } else {
                                $('#LBL_INFO').html(NotifyThis('Please check your verification code', 'error'));
                            }
                        }

                    });


                }

            }
            $(document).ready(function() {

            });
        </script>
</body>

</html>