<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');

$done = '';

if (isset($_GET['err']) && is_numeric($_GET['err'])) {
    $done = $_GET['err'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QM | Forgot Password </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../index.php"><b>Quote </b>Master</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p><a href="index.php"><strong>Back To Home</strong></a></p>
                <div id="LBL_INFO"></div>
                <p class="login-box-msg">You forgot your password? Here you can easily reset a new password.</p>

                <form action="reset_password.php" id="login" method="post">
                    <label for="usertype">Please select the usertype</label>
                    <br>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input usertype" value="3" name="usertype">Customer
                        </label>
                    </div>
                    <div class="form-check-inline mb-3">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input usertype" value="2" name="usertype">Vendor
                        </label>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" id="txtemail" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="./ctrl/clogin.php">Login</a>
                </p>
                <!-- <p class="mb-0">
                    <a href="register.html" class="text-center">Register a new membership</a>
                </p> -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <script type="text/javascript" src="scripts/common.js"></script>
    <script type="text/javascript" src="scripts/md5.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#txtemail').focus();
            done = "<?php echo $done; ?>";

            if (done != '')
                $('#LBL_INFO').html(NotifyThis('Invalid Email or Password', 'error'));


            $('#login').submit(function() {
                err = 0;
                ret_val = true;

                var u = $(this).find('#txtemail');
                if ($.trim(u.val()) == '') {
                    ShowError(u, "Email cannot be empty");
                    err++;
                }


                var usertype = $(this).find('.usertype');
                console.log($('.usertype').is(':checked'));
                if (!($('.usertype').is(':checked'))) {
                    //alert('Please select the user type');
                    $('#LBL_INFO').html(NotifyThis('Please select the usertype?', 'error'));
                    err++;

                }





                if (err > 0) {
                    ret_val = false;
                }

                return ret_val;
            });
        });
    </script>
</body>

</html>