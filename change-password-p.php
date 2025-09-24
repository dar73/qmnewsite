<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Change Password';
$TITLE = SITE_NAME . ' | ' . $page_title;
$done = '';

$LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'why_to_join.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    <!-- <a href="index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item dropdown m-2">
                    <button class="btn nav-link dropdown-toggle px-2 float-right" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="plogin.php">Provider <i class="fa fa-briefcase" aria-hidden="true"></i></a>
                        <a class="dropdown-item" href="clogin.php">Customer</a>
                    </div>
                </li>';

$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    $LOGINBTN = '<a class="login_button hidebutton" href="logout.php">LOG OUT</a>';
    if ($_SESSION['udat_DC']->user_level == 2) {
        header('location:ctrl/v_profile.php');
    } else if ($_SESSION['udat_DC']->user_level == 3) {
        header('location:ctrl/c_profile.php');
    }
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
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row  justify-content-center">
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <div id="LBL_INFO"></div>
                                    <h6 class="text-center">You are only one step away from your new password <br> Recover your password now.</h6>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form class="form-horizontal" action="_changepasswd.php" id="change_passwd" method="POST">
                                    <input type="hidden" name="type" value="<?php echo $_GET['utype']; ?>">
                                    <input type="hidden" name="txtuserid" value="<?php echo $_GET['Q']; ?>">
                                    <input type="hidden" name="mode" value="U">

                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="password" id="passwd1" name="passwd1" class="form-control" placeholder="Password">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="password" id="passwd2" name="passwd2" class="form-control" placeholder="Confirm Password">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-lock"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" name="changepwd" class="btn btn-primary btn-block">Change password</button>
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                    </div>

                                </form>
                            </div>
                            <!-- /.card -->

                        </div>

                        <!-- /.card -->
                    </div>
                </div><!-- /.container-fluid -->
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
    <script type="text/javascript" src="scripts/md5.js"></script>
    <script>
        function showpassword1() {
            var x = document.getElementById("textpasswd");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

        }

        $(document).ready(function() {
            $('#change_passwd').submit(function() {
                ret_val = true;
                err = 0;
                err_arr = new Array();
                var passwd1 = $('#passwd1');
                var passwd2 = $('#passwd2');
                if ($.trim(passwd1.val()) == '') {
                    ShowError(passwd1, "Please enter password");
                    err_arr[err] = passwd1;
                    err++;
                } else
                    HideError(passwd1);

                if ($.trim(passwd2.val()) == '') {
                    ShowError(passwd2, "Please enter new password");
                    err_arr[err] = passwd2;
                    err++;
                } else
                    HideError(passwd2);


                if ($.trim(passwd1.val()) != $.trim(passwd2.val())) {
                    ShowError(passwd2, "Password not matching");
                    passwd2.val('');
                    err_arr[err] = passwd2;
                    err++;
                } else if ($.trim(passwd1.val()) == '' && $.trim(passwd2.val()) == '') {
                    ShowError(passwd1, "Please enter the password");
                    //passwd1.val('');
                    err_arr[err] = passwd1;
                    err++;

                } else {
                    HideError(passwd2);
                    var p_str = b64_md5(passwd2.val());
                    passwd2.val(p_str);

                }

                if (err > 0) {
                    err_arr[0].focus();
                    ret_val = false;
                }

                return ret_val;
            });
        });
    </script>
</body>

</html>