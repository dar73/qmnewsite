<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Customer Login';
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
                        <a class="dropdown-item" href="clogin.php">Customer</a>
                    </div>
                </li>';

$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    $LOGINBTN = '<a class="login_button hidebutton" href="logout.php">LOG OUT</a>';
    if ($_SESSION['udat_DC']->user_level == 2) {
        header('location:ctrl/v_profile.php');
    } else if ($_SESSION['udat_DC']->user_level == 3) {
        header('location:ctrl/user_booking.php');
    }
}
// $customer_id = $_SESSION['udat_DC']->user_id;
// $customer_name = $_SESSION['udat_DC']->user_name;
//$customer_email = $_SESSION['udat_DC']->user_email;
//$customer_email = GetXFromYID("select vEmail from customer where iCustID = $customer_id");

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
                        <div class="col-md-6 clogin">
                            <div class="card card-info">
                                <div class="card-header border-0">
                                    <div id="LBL_INFO"></div>
                                    <div class="d-flex">
                                        <h3 class="text-left">CUSTOMER LOGIN</h3>
                                        <img class="w-50 ml-auto" src="Images/logo.png" alt="">
                                    </div>

                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form class="form-horizontal" action="_customer_registration.php" id="Customer_form" method="POST">
                                    <input type="hidden" name="mode" value="LOGIN">
                                    <input type="hidden" name="usertype" value="3">
                                    <div class="card-body">
                                        <div class="form-group row justify-content-center">
                                            <div class="col-sm-10">
                                                <span id="result"></span>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row justify-content-center">
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="passwd" id="textpasswd" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group row justify-content-center">
                                            <div class="col-sm-10">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" onclick="showpassword1();" id="exampleCheck2">
                                                    <label class="form-check-label" for="exampleCheck2">Show Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mb-1 text-center">
                                            <em><a href="forgot_password_c.php">Forgot Password?</a></strong>
                                        </p>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="reset" class="btn secondary-btn order-sm-1 order-2">Cancel</button>
                                        <button type="submit" class="btn primary-btn float-right">Sign in</button>
                                    </div>
                                    <!-- /.card-footer -->
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
        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        const validate = () => {
            const $result = $('#result');
            const email = $('#email').val();
            $result.text('');

            if (validateEmail(email)) {
                $result.text(email + ' is valid 😎');
                $result.css('color', 'green');
            } else {
                $result.text(email + ' is not valid 😧');
                $result.css('color', 'red');
            }
            return false;
        }




        function showpassword1() {
            var x = document.getElementById("textpasswd");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

        }

        $(document).ready(function() {
            done = "<?php echo $done; ?>";
            if (done != '')
                $('#LBL_INFO').html(NotifyThis('Invalid Username or Password', 'error'));

            $('#email').on('keyup', validate);
            $('#Customer_form').submit(function() {
                err = 0;
                ret_val = true;

                // var first_name = $('#first_name');
                // var last_name = $('#last_name');
                // var company_name = $('#cname');
                // var phone = $('#phone');
                // var street = $('#street');
                // var state_addr = $('#state_adr');
                // var county_name_adr = $('#county_name_adr');
                // var city_adr = $('#city_adr');
                var passwd1 = $('#textpasswd');
                // var passwd2 = $('#passwd2');
                // var state = $('#state');
                // var county_name = $('#county_name');
                // var city = $('#city');



                if ($.trim(passwd1.val()) == '') {
                    ShowError(passwd1, "Please enter your password");
                    ret_val = false;
                } else {
                    HideError(passwd1);
                    var p_str = b64_md5(passwd1.val());
                    passwd1.val(p_str);
                }

                // if ($.trim(state.val()) == '') {
                //     ShowError(state, "Please enter your First name");
                //     ret_val = false;
                // } else {
                //     HideError(state);
                // }

                // if ($.trim(county_name.val()) == '') {
                //     ShowError(county_name, "Please select your County name");
                //     ret_val = false;
                // } else {
                //     HideError(county_name);
                // }

                // if ($.trim(city.val()) == '') {
                //     ShowError(city, "Please select your City");
                //     ret_val = false;
                // } else {
                //     HideError(city);
                // }

                // if ($.trim(county_name_adr.val()) == '') {
                //     ShowError(county_name_adr, "Please select your County");
                //     ret_val = false;
                // } else {
                //     HideError(county_name_adr);
                // }

                // if ($.trim(city_adr.val()) == '') {
                //     ShowError(city_adr, "Please select your City");
                //     ret_val = false;
                // } else {
                //     HideError(city_adr);
                // }

                // if ($.trim(state_addr.val()) == '') {
                //     ShowError(state_addr, "Please select your state");
                //     ret_val = false;
                // } else {
                //     HideError(state_addr);
                // }
                // if ($.trim(state_addr.val()) == '') {
                //     ShowError(state_addr, "Please select your state");
                //     ret_val = false;
                // } else {
                //     HideError(state_addr);
                // }


                // if ($.trim(street.val()) == '') {
                //     ShowError(street, "Please enter your street name");
                //     ret_val = false;
                // } else {
                //     HideError(street);
                // }
                // if ($.trim(last_name.val()) == '') {
                //     ShowError(last_name, "Please enter your Last name");
                //     ret_val = false;
                // } else {
                //     HideError(last_name);
                // }
                // if ($.trim(company_name.val()) == '') {
                //     ShowError(company_name, "Please enter your Company name");
                //     ret_val = false;
                // } else {
                //     HideError(company_name);
                // }
                // if ($.trim(phone.val()) == '') {
                //     ShowError(phone, "Please enter your Phone");
                //     ret_val = false;
                // } else {
                //     HideError(phone);
                // }

                return ret_val;
            });



        });
    </script>
</body>

</html>