<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Home';
$TITLE = SITE_NAME . ' | ' . $page_title;
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
    <link rel="stylesheet" href="css/multistep_form.css">
    <style>
        dl {
            display: block;
            margin-top: 1em;
            margin-bottom: 1em;
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header quote_header">
                <div class="container">
                    <div class="row py-5 no-gutters slider-text js-fullheight align-items-center justify-content-center" id="get_quote_sec" data-scrollax-parent="true">
                        <div class="col-md-12 ftco-animate">
                            <h2 class="subheading">We Will Make Your Place As Good As New</h2>
                            <h1 class="mb-4">Do You Need Cleaning Quotes For Janitorial Service?</h1>
                        </div>
                        <div class="col-md-12">
                            <div class="wrap-appointment bg-white d-md-flex pb-5 pb-md-0">
                                <form action="#" class="appointment w-100">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 d-flex align-items-center pt-4 pt-md-0">
                                            <div class="col-12 form-group py-md-4 py-2 px-4 px-md-0">
                                                <input type="text" class="form-control zip_form" id="zipcode" placeholder="Enter ZIP Code To Register As Customer" autocomplete="off">
                                                <!-- floating ginnie -->
                                                <div class="hide_ginnie col-lg-4">
                                                    <div class="card">
                                                        <div class="card-body d-flex">
                                                            <div class="col-6 my-auto">
                                                                <img src="Images/prati/guide_ginnie.png" alt="">
                                                            </div>
                                                            <div class="col-6 my-auto">
                                                                <p class="card-title">Let's Start!!<br> Please enter the zip code where service is required</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- floating ginnie -->
                                                <input type="hidden" id="zipid">
                                                <!-- search container class start -->
                                                <div class="search_cont">
                                                    <div class="card p-0 m-0 rounded-0">
                                                        <div class="list-group list-group-item-action" id="content">

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- search container class ends -->

                                            </div>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center">
                                            <div class="form-group py-md-4 py-2 px-4 px-md-0 d-flex">
                                                <input type="button" id="btn_search" value="Get a Quote" class="btn py-3 px-4">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
                <div class="container my-5">
                    <h2 class="text-center">Popular Services</h2>
                    <div class="row justify-content-center my-5">

                        <div class="col-md-4 text-center px-lg-5 card m-3 services_card">
                                <div class="card-body">
                                    <h4 class="float-none pb-3">Janitorial</h4>
                                    <div><a href="index.php"><img src="Images/home/aa.png" alt="" class="card-img p-2"></a></div>
                                    <div class="service_card_overlay">
                                        <div class="overlay_text">
                                            <h4>Commercial Office Cleaning </h4>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="col-md-4 text-center px-lg-5 card m-3 services_card">
                                <div class="card-body">
                                    <h4 class="float-none pb-3">Coming Soon</h4>
                                    <div><img src="Images/home/dd.png" alt="" class="card-img p-2"></div>
                                    <div class="service_card_overlay">
                                        <div class="overlay_text">
                                            <h4>Electrical</h4>
                                            <h4>Plumbing</h4>
                                            <h4>Paintings</h4>
                                            <h4>Home Improvements</h4>
                                        </div>
                                    </div>
                                </div>
                        </div>

                    </div>

                    <!-- review -->
                    <div class="container review my-5">
                        <h2 class="text-center">What Customers Say About Our Services</h2>

                        <div class="row justify-content-center my-5">

                            <div class="col-12 col-md-4 text-center">
                                <img src="Images/groupimage.png" class="review_img" alt="">
                                <img src="Images/pulse.gif" class="review_img" alt="">
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="card">
                                    <div class="card-body row d-flex">
                                        <div class="col-12 col-sm-3  text-center">
                                            <img class="w-100" src="Images/user/user.png" alt="">
                                        </div>
                                        <div class="col-12 col-sm-8 text-center text-sm-left py-2">
                                            <h5>Bruce Hardie</h5>
                                            <p>"Quick and easy service. Got responses instantly and the next day the job had
                                                been completed"</p>
                                            <img src="Images/rating1.png" class="c_rating" alt="">
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body row d-flex">
                                        <div class="col-12 col-sm-3 text-center">
                                            <img class="w-100" src="Images/user/user.png" alt="">
                                        </div>
                                        <div class="col-12 col-sm-8 text-center text-sm-left py-2">
                                            <h5>Bruce Hardie</h5>
                                            <p>"Quick and easy service. Got responses instantly and the next day the job had
                                                been completed"</p>
                                            <img src="Images/rating1.png" class="c_rating" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- <div class="welcome_gennie">
            <img src="Images/prati/welcom_ginnie.gif" alt="">
        </div> -->
        <div class="popup">
            <div class="quote_btn" onclick="myFunction()"><img class="my-float" src="Images/prati/chirag.png" alt=""></div>
            <!-- floating ginnie -->
            <div class="hide_ginnie">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="col-6 my-auto">
                            <img src="Images/prati/guide_ginnie.png" alt="">
                        </div>
                        <div class="col-6 my-auto">
                            <p class="card-title">Hello! Ask me anything!</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- floating ginnie -->
            <div class="popuptext" id="myPopup">
                <div class="row justify-content-md-center">
                    <div>
                        <!--start code-->
                        <div class="card">
                            <div class="card-body messages-box">
                                <ul class="list-unstyled messages-list">


                                </ul>
                            </div>
                            <div class="card-header">
                                <div class="input-group">
                                    <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type message here..." />
                                    <span class="input-group-append">
                                        <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end code-->
                    </div>
                </div>
            </div>

        </div>


        <!-- <input type="checkbox" id="check"> 
    <label class="chat-btn" for="check"> 
        <i class="fa fa-commenting-o comment"></i> 
        <i class="fa fa-close close"></i> 
    </label> -->


        <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
    <script>
        // When the user clicks on div, open the popup
        function myFunction() {
            var popup = document.getElementById("myPopup");
            popup.classList.toggle("show");

        }

        function getCurrentTime() {
            var now = new Date();
            var hh = now.getHours();
            var min = now.getMinutes();
            var ampm = (hh >= 12) ? 'PM' : 'AM';
            hh = hh % 12;
            hh = hh ? hh : 12;
            hh = hh < 10 ? '0' + hh : hh;
            min = min < 10 ? '0' + min : min;
            var time = hh + ":" + min + " " + ampm;
            return time;
        }

        function send_msg() {
            jQuery('.start_chat').hide();
            var txt = jQuery('#input-me').val();
            var html = '<li class="messages-me clearfix"><span class="message-img"><img src="Images/prati/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p">' + txt + '</p></div></li>';
            jQuery('.messages-list').append(html);
            jQuery('#input-me').val('');
            if (txt) {
                jQuery.ajax({
                    url: 'get_bot_message.php',
                    type: 'post',
                    data: 'txt=' + txt,
                    success: function(result) {
                        var html = '<li class="messages-you clearfix"><span class="message-img"><img src="Images/prati/ginnie_avatar.png" class="avatar-sm"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Ginnie</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p">' + result + '</p></div></li>';
                        jQuery('.messages-list').append(html);
                        jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);
                    }
                });
            }
        }

        $(document).ready(function() {
            const form = document.getElementById('regForm');
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

            $(document).on('click', '#btn_search,#Btn_icon', function() {
                let search = $('#zipid').val();
                //let search = $('#zipcode').val();
                $('#areaid').val(search);
                if (search != "") {
                    $('#GetQ_modal').modal('show');


                    //$('#customer_regsiter_modal').modal('toggle');
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please enter your zipcode'
                    })
                    $('#zipcode').focus();
                }

            });

            $(document).on('click', 'a', function() {
                $('#zipcode').val($(this).text());
                $('#content').html('');
                $('#zipid').val($(this).data("id"));
            });

            $(document).on('click', '#confirm_booking', function() {
                //$('#exampleModal').modal('toggle');
                let search = $('#zipid').val();
                $('#areaid').val(search);
                if (search != "") {
                    //$('#confirm_modal').modal('toggle');
                    let text = "We are going to send a verification code to the email address you have provided.";
                    if (confirm(text) == true) {
                        text = "You pressed OK!";
                        console.log(text);
                        form.submit();
                    } else {
                        text = "You canceled!";
                    }
                    // form.submit();


                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please search for your zipcode'
                    })
                }

            });

        });
    </script>
    <?php include '_getQuotes_modal.php'; ?>
</body>

</html>