<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Forgot Password';
$TITLE = SITE_NAME . ' | ' . $page_title;
$done = '';
$LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'why_to_join.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    <!-- <a href="index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item dropdown m-2">
                    <button class="btn nav-link dropdown-toggle px-2 float-right" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="plogin.php">Service Provider</a>
                        
                    </div>
                </li>';

$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    if ($_SESSION['udat_DC']->user_level == 2) {
        header('location:ctrl/v_profile.php');
    } else if ($_SESSION['udat_DC']->user_level == 3) {
        header('location:ctrl/c_profile.php');
    }
}

if (isset($_GET['err']) && is_numeric($_GET['err'])) {
    $done = $_GET['err'];
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'load.link.php'; ?>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->


            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-4">
                            <div id="LBL_INFO"></div>
                            <div class="card-body login-card-body">
                                <p class="login-box-msg">Please eneter your registered email, we will send you a link to reset your pasword.</p>

                                <form action="recover_p.php" method="post" id="recover_p_form">
                                    <input type="hidden" name="type" value="V">
                                    <div class="form-row justify-content-center">
                                        <div class="col-12 col-sm-8 col-md-5">
                                            <div class="input-group mb-3">
                                                <input type="text" id="txtemail" name="email" class="form-control" placeholder="Email">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-envelope"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-center">
                                        <div class="col-12 col-sm-8 col-md-5">
                                            <button type="submit" class="btn btn-primary btn-block w-auto">Request new password</button>
                                        </div>
                                    </div>
                                    <div class="form-row justify-content-center">
                                        <div class="col-12 col-sm-8 col-md-5">
                                            <p class="mt-3 mb-1">
                                                <a href="plogin.php">Login</a>
                                            </p>
                                            <p class="mb-0">
                                                <a href="vendor_register.php" class="text-center">Register a new membership</a>
                                            </p>

                                        </div>
                                    </div>




                                    <!-- /.col -->

                                </form>

                            </div>
                            <!-- /.login-card-body -->
                        </div>
                    </div>
                </div>
        </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content -->
    <?php include 'footer.php'; ?>
    </div>
    <!-- /.content-wrapper -->
    <?php include 'load.scripts.php'; ?>
    <script type="text/javascript" src="scripts/ajax.js"></script>
    <script type="text/javascript" src="scripts/common.js"></script>
    <script>
        function validateEmail(email) {
            // Regular expression for email validation
            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            return emailRegex.test(email);
        }



        $('#recover_p_form').submit(function() {
            var err = 0;
            var ret_val = true;
            var email = $('#txtemail');

            if (!validateEmail($.trim(email.val()))) {
                ShowError(email, "Please enter your valid email");
                err++;
            } else {
                HideError(email);
            }

            if (err > 0) {
                ret_val = false;
            }

            return ret_val;



        });

        $(document).ready(function() {
            done = "<?php echo $done; ?>";
            if (done == '1') {
                $('#LBL_INFO').html(NotifyThis('Your email is not registered with Quote Master', 'error'));

            } else if (done == '2') {
                $('#LBL_INFO').html(NotifyThis('Please check your email. We have sent you link to reset your password', 'success'));
            }
        });
    </script>
</body>

</html>