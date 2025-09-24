<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/review.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Quote Master</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>


    <style>
        .searchCon {
            display: flex;
            width: 100%;
        }

        div#content {
            display: grid;
            padding: 10px;
            border-radius: 20px;
            /* box-shadow: rgb(204 219 232) 3px 3px 6px 0px inset, rgb(255 255 255 / 50%) -3px -3px 6px 1px inset; */
        }

        a.list-group.list-group-item-action.border.p-2 {
            color: black;
            list-style-type: none;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            color: dimgrey;
        }

        form.search_con {
            display: grid;
        }

        a.list-group.list-group-item-action.border.p-2 {
            background: #0e2435;
            padding: 10px;
            border-radius: 10px;
            margin: 3px;
            color: #00e7ff;
            /* width: 100%; */
            font-family: monospace;
            border-right: 5px solid #00c988;
            border-bottom: 3px solid #00c988;
        }

        .registera_button {
            display: none;
        }

        .Body {
            background-image: url("Images/index_banner.jpg");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100%;
        }

        .search-container {
            min-height: 500px;
            height: 100%;
        }

        form.search_con {
            display: grid;
            backdrop-filter: blur(10px);
            padding: 70px 0px 20px 0px;
            border-radius: 30px;
            box-shadow: rgb(0 0 0 / 35%) 0px 5px 15px;
            /* border-bottom: 1px solid white; */
        }

        div#Body {
            width: 106%;
            margin-left: -3%;
        }

        .search-container {
            display: flex;
            flex-direction: column;
        }

        h3.Main_header {
            color: white;
            font-weight: 900;
            backdrop-filter: brightness(0.5);
            border-radius: 20px;
            padding: 0px 20px 0px 20px;
            backdrop-filter: inherit;
            filter: drop-shadow(2px 4px 6px black);
        }

        p.small_quot {
            filter: drop-shadow(2px 4px 6px black);
            color: white;
            font-family: monospace;
            color: white;
        }

        /* h3.heading_p {
            margin-top: 60px;
            margin-bottom: 40px;
        } */
        form.search_con {
            position: absolute;
            margin-top: 500px;
            border: 2px solid #999494;
        }

        .two_item {
            margin-top: 90px;
            margin-top: 0px;
        }

        h3.heading_p {
            margin-top: 150px;
        }

        button.searchitem {
            color: black;
            font-weight: 300;
        }

        button.searchitem.desktop {
            display: block;

        }

        button.searchitem.mobile {
            display: none;

        }

        input#zipcode {
            background: black;
            font-size: 18px;
            font-family: monospace;
            color: #07fffc;
            outline: none;
        }

        /* form.search_con{
    margin-top:400px;
} */
        button.searchitem.desktop {
            font-family: monospace;
            outline: none;
            font-weight: 900;
        }

        /* Form css */
        form#regForm {
            padding: 0px;
            margin: 10px;
            width: 100%;
        }

        form#regForm {
            padding: 0px;
            margin: 10px;
            width: 100%;
            display: grid;
            padding-right: 20px;
        }

        button.btn.btn-primary {
            margin-top: 150px;
            width: 20%;
            border-radius: 20px;
            background: #00c988;
            border: none;
            font-weight: 600;
        }

        body {
            justify-items: center;
        }

        h1.regform_heading {
            /* text-align: center; */
            margin-bottom: 30px;
            letter-spacing: 0.5px;
            font-size: 30px;
        }

        button#nextBtn {
            background: #0c5e6c;
            border: none;
            padding: 10px;
            border-radius: 10px;
            color: white;
            font-family: sans-serif;
            font-weight: 700;
        }

        input.form_input_a {
            border: none;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            border-radius: 0px 20px 20px 0px;
        }

        button#prevBtn {
            padding: 10px;
            border-radius: 20px;
            background: #d0d0d0;
            border: none;
            color: black;
            font-family: sans-serif;
            font-weight: 600;
            border-right: 2px solid black;
        }

        button#prevBtn {

            box-shadow: rgb(0 0 0 / 15%) 1.95px 1.95px 2.6px;
            border: 1px solid #b5b5b5;
            background: #ff8c8c;
            border-radius: 5px;
        }

        @media (max-width:952px) {
            img.user_login {
                width: 50px;
                display: none;
            }

            button.login_button {
                margin-right: 0px;
                padding: 10px;
                width: 70px;
            }

            img.logo_img {
                width: 60px;
                margin-left: 200%;
            }

            .searchCon {
                display: flex;
                width: 100%;
            }

            .Register_btn {
                display: none;
            }

            h3.head_main {
                font-size: 18px;
            }

            li.backbutton {
                padding: 10px;
            }

            .registera_button {
                display: block;
            }

            ul.header-right {
                height: 250px;
            }

            form.search_con {
                box-shadow: none;
                padding: 10px;
                display: grid;
                /* grid-template-columns: ; */
            }

            .two_item {
                margin-top: 0px;
            }

            form.search_con {
                box-shadow: none;
            }

            h3.Main_header {
                color: #616161;
                filter: none;
                font-weight: 100;
                font-size: 24px;
                /* background: bisque; */
                backdrop-filter: blur(10px);
                text-align: left;
            }

            p.small_quot {
                color: #a1a1a1;
                filter: none;
                text-align: end;
                /* font-size: 14px; */
                /* margin-right: 20px; */
                width: 90%;
                font-family: sans-serif;
                letter-spacing: 1px;
            }

            form.search_con {
                backdrop-filter: none;
            }

            h3.Main_header {
                backdrop-filter: none;
            }

            div#Body {
                width: 100%;
                margin-left: 0%;
            }

            h3.Main_header {
                padding-left: 5px;
                font-weight: 600;
                font-family: monospace;
            }

            p.small_quot {
                width: 100%;
                font-weight: 500;
                letter-spacing: 0.5px;
                text-align: left;
                margin-left: 10px;
            }

            .search-container {
                margin-top: -40px;
            }

            form.search_con {
                display: initial;
            }

            div#Body {
                margin-top: 20px;
            }

            .search-container {
                background-image: url(Images/index_banner.jpg);
                background-repeat: no-repeat;
                background-position: center center;
                background-size: 100%;
            }

            form.search_con {
                margin-top: 300px;
                border-radius: 0px;
                border: none;
                width: 100%;
            }

            .searchResult {
                position: absolute;
                background: white;
                border-radius: 20px;
                width: 90%;
            }

            .search-container {
                margin-top: -100px;
            }

            input.search_input {
                width: 100%;
                margin-left: 0px;
                outline: none;
            }

            .search-container {
                margin-top: -111px;
            }

            h3.Main_header {
                color: white;
                filter: drop-shadow(2px 4px 6px black);
            }

            .two_item {
                backdrop-filter: blur(10px);
                margin: 20px;
                border-radius: 20px;
                /* border: 2px solid #515151; */
            }

            input.search_input {
                width: 100%;
                margin-left: 0px;
                outline: none;
                box-shadow: rgb(0 0 0 / 35%) 0px 5px 15px;
                font-family: sans-serif;
                letter-spacing: 0.5px;
                color: black;
                font-weight: 500;
                background: black;
                color: #00ffc7;
            }

            button.searchitem.desktop {
                display: none;
            }

            button.searchitem.mobile {
                display: block;
                width: 50px;
                color: #ffffff;
                border-radius: 50px;
            }

            div#Body {
                background: bisque;
            }

            .searchResult {
                background: bisque;
            }

            h3.heading_p {
                margin-top: 0px;
                margin-bottom: 30px;
                font-weight: 700;
                /* text-align: left; */
            }

            div#Body {
                box-shadow: none;
            }

            input#zipcode {
                font-size: 16px;
            }

            p.small_quot {
                color: white;
                font-size: 11px;
                text-align: right;
                letter-spacing: 1px;
            }


        }

        /* Style the form */
        #regForm {
            background-color: #ffffff;
            margin: 100px auto;
            padding: 40px;
            width: 70%;
            min-width: 300px;
        }

        /* Style the input fields */
        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: Raleway;
            border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        /* Mark the active step: */
        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }
        h1.mb-3.text-center {
            background: #f1f1f1;
            border-bottom: 2px solid #00c988;
            padding-bottom: 10px;
            color: #14144c;
            font-weight: 400;
            letter-spacing: 1px;
        }
        input.form-control {
    box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
    font-family: monospace;
    letter-spacing: 1px;
    margin-left: 0px;
}input.form-control {
    border-bottom: 2px solid #c4c4c4;
    border-radius: 0px;
    box-shadow: none;
    color: black;
}
.form-group {
    margin-bottom: 0px;
}
small.errormsg {
    font-family: monospace;
}
.modal-body {
    padding-top: 0px;
}
form#regForm {
    margin-top: 0px;
}
.card-body {
    position: fixed;
}
.card-body {
    position: absolute;
    background: white;
    overflow-y: auto;
    /* height: 400px; */
}
form.search_con{
    padding-bottom:70px;
}

        @media (max-width:952px) {
            button.btn.btn-primary {
                margin-top: 0px;
                margin-bottom: 40px;
            }

            button.btn.btn-primary {
                margin-top: 0px;
                margin-bottom: 40px;
                width: 40%;
                background: #136972;
            }
        }

        .errormsg {

            color: red;
            text-align: center;
            padding: 10px;
        }
        

        /* proffectional */
        button.login_button.Register_btn {
            background: #212121;
            width: 40px;
            border-radius: 70px;
            height: 40px;
        }
        .hide {
            /* border-radius: 30px; */
            position: absolute;
            background: white;
            color: #a8a8a8;
            padding: 8px;
            margin-left: -50px;
            margin: 30px;
            margin-top: 10px;
            margin-left: 0px;
            width: 8%;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            letter-spacing: 1px;
            display: none;
        }
        .Register_btn:hover+.hide {
            display: block;
        }
        div#Header {
            margin-left: -10px;
        }
        button.login_button.Register_btn {
            cursor: pointer;
        }
        /* button.login_button.Register_btn {
    padding-top: 10px;
} */
        
        @media (max-width:952px) {
            button.login_button.hidebutton {
                display: none;
            }
            .card-body {
                background: white;
            }
            button.login_button.Register_btn {
                background: #212121;
                width: 40px;
                border-radius: 70px;
                height: 40px;
            }
            div#Header {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
            }
            div#Header {
                margin-left: -10px;
            }
            nav {
                display: flex;
                width: 100%;
                justify-content: center;
            }
            button.login_button.Register_btn {
                margin-right: 10px;
            }
            .bar_butt {
                display: flex;
            }
            label.checkbars {
                padding-left: 20px;
                padding: 20px;
            }
            img.logo_img {
    margin-left: 0px;
}
button.login_button.Register_btn {
    display: block;
}
div#Body {
    background: white;
}
.searchCon {
    margin-left: 10px;
}
form.search_con {
    padding-bottom: 0px;
}
a.textd {
    color: white;
}
.Register_btn:hover+.hide {
    display: none;
}
h3.heading_p {
    margin-top: -60px;
}
        }
    </style>


</head>

<body>
    <!-- Header -->
    <div id="Header" class="Header">
        <label for="check" class="checkbars"><i class="fa fa-bars" style="font-size:24px"></i></label>
        <input type="checkbox" onclick="myFunction()" id="check">

        <div class="header_con">
            <nav>
                <img class="logo_img" src="Images/logo.png" alt="">
            </nav>
        </div>
        <ul class="header-right" id="header-right">
            <li class="backbutton active"><a href="index.php" class="textd">Home</a></li>
            <li class="backbutton"><a href="about.php" class="textd">Contact</a></li>
            <li class="backbutton"><a href="about.php" class="textd">About</a></li>
            <li class="backbutton registera_button"><a href="about.php" class="textd">Login</a></li>
        </ul>
        <div class="left_items">
            <div class="hover_button">
                <button class="login_button Register_btn" onclick="location.href='new_vendor.php'"><i class="fa fa-briefcase" aria-hidden="true"></i></button>
                <div class="hide">Login as a Proffectional.</div>
            </div>
            <button class="login_button hidebutton" onclick="location.href='ctrl/vendor_login.php'">Login</button>
        </div>
    </div>

    <!-- Header -->
    <!-- main -->



    <div id="Body" class="Body">
        <div class="search-container">
            <div class="two_item">
                <h3 class="Main_header">Find the Perfect Professional For You</h3>
                <p class="small_quot">Quote Master where you are the <br> MASTER of any quotes YOU want!</p>
            </div>


            <form class="search_con">
                <div class="searchCon">
                    <input class="search_input" type="text" placeholder="Please enter the zip code where service is required" name="search" id="zipcode">
                    <input type="hidden" id="zipid">
                    <button class="searchitem desktop" type="button" id="btn_search">Get Quotes</button>
                    <button class="searchitem mobile" type="button"><i class="fa fa-search" id="Btn_icon" style="font-size:24px"></i></button>
                </div>

                <!-- Result -->
                <div class="searchResult">
                    <div class="">
                        <div class="card-body">
                            <div class="list-group list-group-item-action" id="content">

                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
        <!-- <p class="label_a">Popular: House Cleaning, Web Design, Personal Trainers</p> -->

    </div>
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Open modal
    </button> -->

    <!-- The Modal -->
    <div class="modal" id="customer_regsiter_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Get Quotes</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="regForm" action="register_cus.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="schedule" id="" value="">
                                <input type="hidden" name="areaid" id="areaid" value="">

                                <!-- <h1 class="mb-3">Register With Quote Master:</h1> -->

                                <!-- One "tab" for each step in the form: -->
                                <div class="tab">
                                    <h1 class="mb-3 text-center">Register</h1>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <input type="text" class="form-control" required id="name_of_company" name="name_of_company" placeholder="Enter Company Name">
                                                <small class="errormsg"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter full Name" required>
                                                <small class="errormsg"></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <input type="text" id="position" name="position" class="form-control" placeholder="Position in the Company" required>
                                                <small class="errormsg"></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone" required>
                                                <small class="errormsg"></small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-12 ">
                                            <div class="form-group">

                                                <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                                                <small class="errormsg"></small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab">
                                    <h1 class="mb-3 text-center">Service Details</h1>

                                    <div class="form-group">
                                        <label for="">How Often Do You Want Service Per Week ?</label>
                                        <select name="how_often" id="how_often" class="form-control form-control-sm">
                                            <option value="">---Please select---</option>
                                            <option value="1x">1x</option>
                                            <option value="2x">2x</option>
                                            <option value="3x">3x</option>
                                            <option value="4x">4x</option>
                                            <option value="5x">5x</option>
                                            <option value="6x">6x</option>
                                            <option value="7x">7x</option>
                                        </select>
                                        <small class="errormsg"></small>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-weight: 700 !important" for="selectbox">What describes your current cleaning situation?</label>

                                        <select name="cleaning_situation" id="cleaning_situation" class="form-control form-control-sm">
                                            <option value="">---Please select---</option>
                                            <option value="In house we clean our own office">In house we clean our own office</option>
                                            <option value="We have an outside cleaner">We have an outside cleaner</option>
                                        </select>
                                        <small class="errormsg"></small>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-weight: 700 !important" for="selectbox">Current cleaners are?</label><br>
                                    </div>

                                    <!-- <option value="Current cleaners are not dusting well">Current cleaners are not dusting well</option>
                                        <option value="Current cleaners are not cleaning restrooms well">Current cleaners are not cleaning restrooms well</option>
                                        <option value="Current cleaners are not following their cleaning schedule">Current cleaners are not following their cleaning schedule</option>
                                        <option value="Current cleaners are missing a few things">Current cleaners are missing a few things</option>
                                        <option value="I am doing some comparison shopping">I am doing some comparison shopping</option> -->
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" class="form-check-input" name="cleaning_cleaners[]" value="1">Not dusting well
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">

                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="2"> Not cleaning restrooms well
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="3">
                                            Missing some obvious things
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="4">
                                            Not following their cleaning schedule
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cl-*eaners[]" class="form-check-input" value="5">
                                            I am doing some comparison shopping
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="6">
                                            The entryway could look better
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="7">
                                            They are missing trash in some offices
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="8">
                                            They may be or are retiring soon / moving away
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="9">
                                            Not showing up when scheduled
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="check2">
                                            <input type="checkbox" name="cleaning_cleaners[]" class="form-check-input" value="10">
                                            Looking for a back-up in case I need one
                                        </label>
                                    </div>



                                    <small class="errormsg"></small>


                                    <div class="form-group">

                                        <label style="font-weight: 700 !important" for="selectbox">Current rating 1-5?</label>
                                        <select name="rating" id="rating" class="form-control form-control-sm">
                                            <option value="">---Please select---</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <small class="errormsg"></small>
                                    </div>

                                </div>

                                <div class="tab">
                                    <h1 class="mb-3 text-center">Schedule Your Meeting</h1>
                                    <div class="form-group">
                                        <label style="font-weight: 700 !important" for="selectbox">How many
                                            quotes do you need?</label>
                                        <select name="No_of_quotes" id="No_of_quotes" class="form-control">
                                            <option value="">---Please select---</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <small class="errormsg"></small>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-weight: 700 !important" for="selectbox">Do you
                                            want to self schedule?</label>
                                        <select name="self_schedule" id="self_schedule" class="form-control">

                                            <option value="">Please select Your choice</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>

                                        </select>
                                        <small class="errormsg"></small>
                                    </div>
                                    <div class="schedule_date">

                                    </div>

                                    <div class="form-group">
                                        <label for="">Are you looking to change your current company?</label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="yes" name="change_in_company">Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="no" name="change_in_company">No
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab" id="last_tab">
                                    <p class="mb-3 text-center">Confirm Your Booking With Quote Master?</p>
                                    <div class="text-center">
                                        <button type="button" id="confirm_booking" class="btn btn-success">Yes</button>
                                        <button type="button" id="btnclose" class="btn btn-danger">No</button>
                                    </div>
                                </div>

                                <div style="overflow:auto;">
                                    <div style="float:right;">
                                        <button type="button" id="prevBtn" class="btn btn-info" onclick="nextPrev(-1)">Back</button>
                                        <button type="button" class="btn btn-success" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                    </div>
                                </div>

                                <!-- Circles which indicates the steps of the form: -->
                                <div style="text-align:center;margin-top:40px;">
                                    <span class="step"></span>
                                    <span class="step"></span>
                                    <span class="step"></span>
                                    <span class="step"></span>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <h3 class="heading_p">Popular Services</h3>
    <div class="container">

        <div class="cona1">
            <div class="headingb">Janitorial</div>
            <div class="imagec"><a href="index.php"><img src="Images/home/aa.png" alt="" class="bannerimg"></a></div>
            <div class="last_con">

            </div>
        </div>
        <div class="cona1">
            <div class="headingb">Office Cleaning</div>
            <div class="imagec"><img src="Images/home/bb.png" alt="" class="bannerimg"></div>
        </div>
        <div class="cona1">
            <div class="headingb">Coming Soon</div>
            <div class="imagec"><img src="Images/home/dd.png" alt="" class="bannerimg"></div>
        </div>
    </div>

    <!-- review -->
    <div class="containera">
        <h1 class="top_heading">What They Say About Our Services</h1>

        <div class="twogroup">

            <div class="itema fitem">
                <img src="Images/groupimage.png" class="group_img" alt="">
                <img src="Images/pulse.gif" class="pulsegif" alt="">
            </div>
            <div class="itema sitem">
                <div class="userOne">
                    <div class="userdetail firsta">
                        <img src="Images/user/1.jpeg" class="smallavtar" alt="">
                    </div>
                    <div class="Imgdesc firsta">
                        <h2 class="top_name">Bruce Hardie</h2>
                        <p class="para">"Quick and easy service. Got responses instantly and the next day the job had
                            been completed"</p>
                        <img src="Images/rating1.png" class="starRating" alt="">
                    </div>
                </div>

                <div class="userOne">
                    <div class="userdetail seconda">
                        <img src="Images/user/2.jpeg" class="smallavtar" alt="">
                    </div>
                    <div class="Imgdesc seconda">
                        <h2 class="top_name">Bruce Hardie</h2>
                        <p class="para">"Quick and easy service. Got responses instantly and the next day the job had
                            been completed"</p>
                        <img src="Images/rating1.png" class="starRating" alt="">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- review -->

    <!-- Footer -->
    <div id="maina" class="maina">
        <div id="footer" class="footer">
            <div class="firstblock">

                <div class="threeblock">
                    <h3 class="head_main">For Customers</h3>
                    <li class="nexttitle">Find a Professional</li>
                    <li class="nexttitle">How it works</li>
                    <li class="nexttitle">Login</li>
                </div>
                <div class="threeblock">
                    <h3 class="head_main">For Customers</h3>
                    <li class="nexttitle">Find a Professional</li>
                    <li class="nexttitle">How it works</li>
                    <li class="nexttitle">Login</li>
                </div>
                <div class="threeblock">
                    <h3 class="head_main">About</h3>
                    <li class="nexttitle">About Quotmaster</li>
                    <li class="nexttitle">Team</li>
                    <li class="nexttitle">Carrior</li>
                    <li class="nexttitle">Blog</li>
                </div>
            </div>

            <div class="secondblock">
                <p>quotemasters@.com</p>
                <p class="nexttitlea">+91 80372 44341</p>
                <p class="nexttitlea">(Mon-Fri 8:30am-7pm)</p>
            </div>
        </div>

        <div class="bottom_footer">
            <!-- Logo -->
            <div class="three_logo">
                <div class="bottomlogo first_logo"><img class="small_log" src="Images/footer/fb.png" alt=""></div>
                <div class="bottomlogo second_logo"><img class="small_log" src="Images/footer/insta.png" alt="">
                </div>
                <div class="bottomlogo Third_logo"><img class="small_log" src="Images/footer/tweet.png" alt=""></div>
            </div>
            <!-- horizontal -->
            <div class="bottomcon">
                <hr class="new4">

                <div class="lastblock">
                    <div class="firstblock">
                        <p class="textb last_text">@ 2022 quotemaster.com</p>
                    </div>
                    <div class="secondblock">
                        <p class=" textb secont_text">All rights reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->

</body>
<script src="scripts/common.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    const EMAIL_REQUIRED = "Please enter your email";
    const EMAIL_INVALID = "Please enter a correct email address format";

    function validateEmail(input, requiredMsg, invalidMsg) {
        // check if the value is not empty
        if (!hasValue(input, requiredMsg)) {
            return false;
        }
        // validate email format
        const emailRegex =
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        const email = input.value.trim();
        let v = 0;
        // $.ajax({
        //     url: 'email_verify_code.php',
        //     method: 'POST',
        //     async: false,
        //     data: {
        //         email: email
        //     },
        //     success: function(res) {
        //         //alert(res);
        //         v = res;

        //     }
        // });
        console.log(v);
        // if (v != 1) {
        //     return showError(input, invalidMsg);
        // }
        return true;
    }

    function showMessage(input, message, type) {
        // get the small element and set the message
        const msg = input.parentNode.querySelector("small");
        msg.innerText = message;
        // update the class for the input
        //input.className = type ? "success" : "error";
        return type;
    }

    function showError(input, message) {
        return showMessage(input, message, false);
    }

    function showSuccess(input) {
        return showMessage(input, "", true);
    }

    function hasValue(input, message) {
        if (input.value.trim() === "") {
            return showError(input, message);
        }
        return showSuccess(input);
    }

    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").style.display = "none";
        } else {
            document.getElementById("nextBtn").style.display = "inline";
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        // ... and run a function that displays the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form... :
        if (currentTab >= x.length) {
            //...the form gets submitted:
            //document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        // for (i = 0; i < y.length; i++) {
        //     // If a field is empty...
        //     if (y[i].value == "") {
        //         // add an "invalid" class to the field:
        //         y[i].className += " invalid";
        //         // and set the current valid status to false:
        //         valid = false;
        //     }
        // }

        if (currentTab == 0) {
            err = 0;
            err_arr = new Array();

            var name_of_company = document.getElementById('name_of_company');
            var full_name = document.getElementById('full_name');
            var position = document.getElementById('position');
            var phone = document.getElementById('phone');
            var email = document.getElementById('email');
            console.log(name_of_company);
            let nameValid = hasValue(name_of_company, "comapny name required");
            let fullNameValid = hasValue(full_name, "Full name required");
            let positionValid = hasValue(position, "Position required");
            let phoneValid = hasValue(phone, "Phone required");
            let emailValid = validateEmail(email, EMAIL_REQUIRED, EMAIL_INVALID);
            console.log(nameValid);
            console.log(emailValid);
            if (!nameValid) {
                name_of_company.focus();
                valid = false;
            }
            if (!emailValid) {
                email.focus();
                valid = false;

            }
            if (!fullNameValid) {
                full_name.focus();
                valid = false;

            }
            if (!positionValid) {
                position.focus();
                valid = false;

            }
            if (!phoneValid) {
                phone.focus();
                valid = false;

            }
        }
        if (currentTab == 1) {
            var q1 = document.getElementById("how_often");
            var q2 = document.getElementById("cleaning_situation");
            var q3 = document.getElementById("cleaning_status");
            var q4 = document.getElementById("rating");
            if (q1.value.trim() == '') {
                q1.focus();
                let msg = q1.parentNode.querySelector("small");
                msg.innerText = "Please select your option";
                valid = false;

            } else {
                let msg = q1.parentNode.querySelector("small");
                msg.innerText = "";
            }

            if (q2.value.trim() == '') {
                q2.focus();
                let msg = q2.parentNode.querySelector("small");
                msg.innerText = "Please select your option";
                valid = false;

            } else {
                let msg = q2.parentNode.querySelector("small");
                msg.innerText = "";
            }
            // if (q3.value.trim() == '') {
            //     q3.focus();
            //     let msg = q3.parentNode.querySelector("small");
            //     msg.innerText = "Please select your option";
            //     valid = false;

            // }
            if (q4.value.trim() == '') {
                q4.focus();
                let msg = q4.parentNode.querySelector("small");
                msg.innerText = "Please select your option";
                valid = false;

            }

        }

        if (currentTab == 2) {
            var No_of_q = document.getElementById("No_of_quotes");
            var self_shedule = document.getElementById("self_schedule");
            if (No_of_q.value.trim() == '') {
                No_of_q.focus();
                let msg = No_of_q.parentNode.querySelector("small");
                msg.innerText = "Please select your option";
                valid = false;

            } else {
                let msg = No_of_q.parentNode.querySelector("small");
                msg.innerText = "";
            }
            if (self_shedule.value.trim() == '') {
                self_shedule.focus();
                let msg = self_shedule.parentNode.querySelector("small");
                msg.innerText = "Please select your option";
                valid = false;

            } else {
                let msg = self_shedule.parentNode.querySelector("small");
                msg.innerText = "";
            }
        }




        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }

    function GoToPage(page) {
        window.document.location.href = page;
    }
    const myFunction = () => {
        var displayblock = document.getElementById("header-right");
        var check = document.getElementById("check");
        if (check.checked == true) {
            displayblock.style.left = 0;
        } else {
            displayblock.style.left = "-100%";
        }
    }
    $(document).ready(function() {
        const form = document.getElementById('regForm');
        $(document).on('click', '#btn_close,#btnclose', function() {
            form.reset();
            //document.getElementsByClassName("step")[currentTab].className += " finish";
            //document.getElementById("last_tab").style.display = "none";
            //showTab(0);
            // let search = $('#zipcode').val();
            $('#customer_regsiter_modal').modal('toggle');
            window.location.reload();

        });
        $(document).on('keyup', '#zipcode', function() {
            let search = $('#zipcode').val();
            if (search != "") {
                $.ajax({
                    url: 'api/search.php',
                    method: 'POST',
                    data: {
                        search: search
                    },
                    success: function(res) {
                        console.log(res);
                        $('#content').html(res);

                    }
                });

            }

        });

        $(document).on('click', 'a', function() {
            $('#zipcode').val($(this).text());
            $('#content').html('');
            $('#zipid').val($(this).data("id"));
        });

        $(document).on('click', '#btn_search,#Btn_icon', function() {
            let search = $('#zipid').val();
            //let search = $('#zipcode').val();
            if (search != "") {


                $('#customer_regsiter_modal').modal('toggle');
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Please search for zipcode'
                })
                $('#zipcode').focus();
            }

        });

        $(document).on('change', '#self_schedule,#No_of_quotes', function() {
            let choice = $('#self_schedule').val();
            let no_of_quotes = $('#No_of_quotes').val();
            let str = '';
            if (choice == '1') {
                for (let i = 0, j = 1; i < no_of_quotes; i++, j++) {
                    str += `<div class="form-group">
            <label style="font-weight: 700 !important"> Book Your Date & Time for booking ${j}</label>
                        <input type="datetime-local" id="date${j}" name="date${j}" class="form-control">
                              
                    </div> `;


                }
                $('.schedule_date').html(str);



            } else {
                $('.schedule_date').html('');
                ///alert('Please select your choice');

            }


        });

        $(document).on('click', '#confirm_booking', function() {
            //$('#exampleModal').modal('toggle');
            let search = $('#zipid').val();
            $('#areaid').val(search);
            if (search != "") {
                form.submit();
                console.log($('#regForm').serialize());

            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Please search for zipcode'
                })
            }

        });
    });
</script>

</html>