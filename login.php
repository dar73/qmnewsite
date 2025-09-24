<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QM | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">

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
    <style>
        .register_main {
            background: white;
            border-radius: 20px;
            /* width: 500px; */
        }

        img.reg_img {
            width: 350px;
            border-radius: 20px;
        }

        .register_main {
            display: flex;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        }

        img.reg_img {
            width: 520px;
            height: 100%;
            padding: 10px;
            border-radius: 20px;
        }

        .reg_divone {
            order: 2;
        }

        .register-logo {
            font-family: sans-serif;
            margin-top: 30px;
            /* background: red; */
            margin: 40px;
            background: #000000;
            color: white;
            border-radius: 20px;
        }

        b.Qstyle {
            color: #00ffaf;
            font-size: 35px;
        }

        .login-logo a,
        .register-logo a {
            color: #495057;
            font-size: 30px;
            color: #a5a5a5;
            letter-spacing: 2px;
        }

        .card {
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
            margin-bottom: 1rem;
            margin: 20px;
            box-shadow: none;
        }

        input.form-control {
            border-radius: 10px;
            box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
            font-family: sans-serif;
        }

        select.select2 {
            border-radius: 20px;
            /* border: none; */
            box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
            /* background: beige; */
            border: 1px solid #bfbfbf;
        }

        .login-box {
            display: grid;
            align-items: center;
        }

        a.text-center {
            color: #0f4966;
            font-weight: 600;
        }

        form.three_inputs {
            display: grid;
            width: 100%;
        }

        body.login-page {
            background-image: url("Images/footer/login_back.jpg");
            background-color: #cccccc;
            background-repeat: no-repeat, no-repeat;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .card {
            margin-top: 0px;
        }

        .register-logo {
            margin-bottom: 0px;
        }

        input.form-control {
            box-shadow: none;
            border: none;
            border-bottom: 2px solid #bebebe;
            border-radius: 0px;
        }

        button.btn.btn-primary.btn-block {
            margin-top: 40px;
            width: 100%;
            background: black;
            font-family: sans-serif;
        }

        .col-4 {
            max-width: inherit;
            flex: inherit;
        }

        .input-group-text {
            box-shadow: none;
            border: none;
            border-bottom: 2px solid #bebebe;

        }

        a.text-center {
            font-family: system-ui;
            font-size: 14px;
        }

        img.log_logo {
            width: 70px;
        }

        .register-logo {
            background: none;
        }

        img.log_logo {
            width: 100px;
            padding: 10px;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            border-radius: 50px;
            cursor: pointer;
        }

        p.mb-0.mt-2 {
            text-align: center;
        }

        input.form-control {
            font-family: monospace;
        }

        @media (max-width:952px) {
            .register_main {
                margin: 25px;
                margin-top: 100px;
            }

            .reg_divone {
                display: none;
            }

            .register-logo {
                background: none;
                margin-top: 0px;
                padding-bottom: 10px;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                margin: 0px;
            }

            .login-box {
                width: 100%;
            }

        }
    </style>
</head>

<body class="hold-transition login-page">


    <!-- <div id="Header" class="Header">
        <label for="check" class="checkbars"><i class="fa fa-bars" style="font-size:24px"></i></label>
        <input type="checkbox" onclick="mysidebar()" id="check">
        <i class="fa fa-address-book" aria-hidden="true"></i>

        <div class="header_con">
            <nav>
                <img class="logo_img" src="../Images/logo.png" alt="">
            </nav>
        </div>
        <ul class="header-right" id="header-right">
            <li class="backbutton active"><a href="index.php" class="textd">Home</a></li>
            <li class="backbutton"><a href="about.php" class="textd">Contact</a></li>
            <li class="backbutton"><a href="about.php" class="textd">About</a></li>
            <li class="backbutton registera_button"><a href="about.php" class="textd">Register</a></li>
        </ul>
        <div class="left_items">
            <div class="hover_button">
                <button class="login_button Register_btn" onclick="location.href='new_vendor.php'"><i class="fa fa-briefcase" aria-hidden="true"></i></button>
                <div class="hide">Login as a Proffectional.</div>
            </div>
            <button class="login_button" onclick="location.href='ctrl/vendor_login.php'">Login</button>
        </div>
    </div> -->

    <div class="register_main">
        <div class="reg_divone">
            <img src="Images/Register/banner.jpg" alt="" class="reg_img">
        </div>

        <div class="login-box">
            <div class="register-logo">
                <a href="index.php"><img src="Images/logo.png" alt="" class="log_logo"></a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg"><strong>Vendor Login</strong> </p>

                    <form action="" class="three_inputs" method="post">
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" value="login" name="submit" class="btn btn-primary btn-block">Log In</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <p class="mb-0 mt-2">
                        <a href="../registerv.php" class="text-center">Register a new membership</a>
                    </p>

                </div>
                <!-- /.login-card-body -->
            </div>
            <div>


            </div>
            <!-- /.login-box -->

            <!-- jQuery -->
            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Bootstrap 4 -->
            <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- AdminLTE App -->
            <script src="dist/js/adminlte.min.js"></script>
            <script>
                const mysidebar = () => {
                    var displayblock = document.getElementById("header-right");
                    var check = document.getElementById("check");
                    if (check.checked == true) {
                        displayblock.style.left = 0;
                    } else {
                        displayblock.style.left = "-100%";
                    }
                }
            </script>

</body>

</html>