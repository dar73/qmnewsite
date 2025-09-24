<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Home';
$TITLE = SITE_NAME . ' | ' . $page_title;
$targetDate = '2024-12-03 00:00:00';
date_default_timezone_set('America/New_York');
$targetTimestamp = strtotime($targetDate);
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
    <meta name="description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta name="keywords" content="cleaning quotes, service providers, compare prices, house cleaning, office cleaning">
    <meta property="og:title" content="Your Cleaning Service Quotes | Quote Masters">
    <meta property="og:description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta property="og:image" content="https://thequotemasters.com/Images/logo.png">
    <meta property="og:url" content="https://thequotemasters.com/">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Quote Masters" />
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Your Cleaning Service Quotes | Quote Masters">
    <meta name="twitter:description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta name="twitter:image" content="https://thequotemasters.com/Images/logo.png">
    <meta name="twitter:url" content="https://thequotemasters.com/">
    <link rel="canonical" href="https://thequotemasters.com/" />
    <link rel="stylesheet" href="css/multistep_form.css">
    <style>
        dl {
            display: block;
            margin-top: 1em;
            margin-bottom: 1em;
            margin-left: 0;
            margin-right: 0;
        }

        img {
            max-width: 100%;
            /* Ensures the image scales within the parent container */
            height: auto;
            /* Maintains the aspect ratio */
            display: block;
            /* Removes inline-block spacing */
            margin: 0 auto;
            /* Centers the image horizontally */
        }

        /* Optional: Adjust the container for better responsiveness */
        .container-fluid {
            padding: 0;
            /* Removes extra padding for a cleaner layout */
        }

        /* Optional: Add spacing or alignment for larger screens */
        @media (min-width: 768px) {
            .mt-sm-5 {
                margin-top: 3rem;
                /* Top margin adjustment for larger screens */
            }

            .mr-md-5 {
                margin-right: 3rem;
                /* Right margin adjustment for larger screens */
            }
        }

         /* Add CSS for styling */
         #countdown-container {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20px;
        }
        #countdown {
            font-size: 24px;
            font-weight: bold;
            animation: flash 1s infinite;
        }
        .offer-text {
            font-size: 18px;
            margin-right: 10px;
        }

        /* Flashing effect */
        @keyframes flash {
            0%, 50%, 100% {
                opacity: 1;
            }
            25%, 75% {
                opacity: 0;
            }
        }
    </style>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Quote Masters",
            "description": "Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.",
            "url": "https://thequotemasters.com/",
            "logo": "https://thequotemasters.com/Images/logo.png",
            "alternateName": "The Quote Masters",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Florida limited liability company",
                "addressLocality": "Southeastern region",
                "addressRegion": "Southeastern region ",
                "postalCode": "32013",
                "addressCountry": "US"
            },

            "areaServed": {
                "@type": "State",
                "name": "Florida"
            },
            "contactPoint": [{
                "@type": "ContactPoint",
                "telephone": "(866)958-8773",
                "contactType": "customer service"

            }]
        }
    </script>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- logo banner -->
            <div class="logo_banner">
                <div class="container-fluid">
                    <div class="row justify-content-lg-end">
                        <div class="col col-lg-8 col-12 text-right">
                            <img class="logo_banner_image mt-sm-5 mt-3 mr-md-5" src="Images/logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Header (Page header) -->
            <div class="content-header quote_header">
                <div class="container">
                    <div class="row pb-5 no-gutters slider-text js-fullheight align-items-center justify-content-center" id="get_quote_sec" data-scrollax-parent="true">
                        <div class="col-md-12 ftco-animate">
                            <!-- <h6 class="subheading">We Will Make Your Place As Good As New</h6> -->
                            <h1 class="mb-4">NEED CLEANING QUOTES FOR JANITORIAL SERVICE?</h1>
                        </div>
                        <div class="col-md-12">
                            <div class="wrap-appointment d-md-flex pb-5 pb-md-0">
                                <form action="#" class="appointment w-100">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 d-flex align-items-center pt-4 pt-md-0">
                                            <div class="col-12 form-group px-4 px-md-0">
                                                <input type="text" class="form-control zip_form" id="zipcode" placeholder="Enter zip code where quotes are needed" autocomplete="off">
                                                <!-- floating ginnie -->
                                                <div class="hide_ginnie col-lg-4">
                                                    <div class="card">
                                                        <div class="card-body d-flex">
                                                            <div class="col-5 my-auto">
                                                                <img src="Images/prati/guide_ginnie.png" alt="">
                                                            </div>
                                                            <div class="col-7 my-auto">
                                                                <h6 class="card-title">Let's Start!!</h6>
                                                                <p class="card-title">Please enter the zip code where service is required</p>
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
                                            <div class="form-group px-4 px-md-0 d-flex">
                                                <!-- <input type="button" id="btn_search" value="Get a Quote" class="btn py-3 px-4"> -->
                                                <button type="button" id="btn_search" class="btn btn-block">GET FREE QUOTES</button>
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
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col col-lg-8 col-12 text-center">
                            <div id="countdown-container">
                                <span class="offer-text">Offer ends in</span>
                                <span class="text-danger" id="countdown"></span>
                            </div>
                            <img class=" mt-sm-5 mt-3 mr-md-5" src="Images/offer_themed.webp" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container py-3">
                    <h2 class="section_heading text-center">Popular Services</h2>
                    <div class="row justify-content-center my-5">

                        <div class="col-md-3 col-sm-4 text-center card m-3 services_card">
                            <div class="services_img_div1"></div>
                            <h4 class="float-none pt-3">Janitorial</h4>
                            <div class="service_card_overlay">
                                <div class="overlay_text">
                                    <h4>Commercial Office Cleaning </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 text-center card m-3 services_card">
                            <div class="ribbon-2">Coming Soon</div>
                            <div class="services_img_div2"></div>
                            <h4 class="float-none pt-3">Upcoming Services</h4>
                            <div class="service_card_overlay">
                                <div class="overlay_text">
                                    <h4>Electrical</h4>
                                    <h4>Plumbing</h4>
                                    <h4>Painting</h4>
                                    <h4>Home Improvements</h4>
                                    <h4>Lawn Maintenance</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                    <h2 class="section_heading text-center">Why Choose Us</h2>
                    <div class="row justify-content-center my-5">
                        <div class="col-md-8 text-center  p-4">
                            <div class="embed-responsive embed-responsive-16by9 mb-4">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SFTj7wn3Xvs?loop=1&controls=0&playlist=SFTj7wn3Xvs" allowfullscreen></iframe>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> 100% verified leads of local customers with "desire to meet (and engage)"</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Unique messaging system generating codes which ensures all meeting requests are genuine</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Appointments with specific day and time to meet with the customer</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Initiating calendar invitations as reminders coupled with 3-step follow up process to ensure minimal "no-shows"</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Company details shared with the customers prior to appointments so they are aware of your company benefits</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Simple lead purchase experience along with specific credit guidelines for cancelled appointments</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Gold Standard QM Support Team providing Phone & Chat Support Services</li>
                            </ul>
                        </div>
                    </div>

                    <!-- review -->
                    <div class="container homepage_review py-5 border-top">
                        <h1 class="text-center">WHAT CUSTOMERS SAY ABOUT OUR SERVICES</h1>

                        <div class="row justify-content-center my-5">
                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">My goodness the website was so easy to use! I think it took me about 2 minutes to set up meetings for 5 companies. The meeting all happened as scheduled and I like the quality of cleaners I have to choose from!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #6D6E71;">M</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Mureen Parker</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@parker...7 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">Quote Masters is different than other services I have used like them. They sent me great companies and it was hard to pick. Then they followed through to make sure all went well and if I found what I was looking for. Very refreshing that I never felt on my own!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #00AEEF;">T</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Tony Brown</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@brown...1 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">I must say seldom do you find a service like this who matches you up with qualified providers and lets you get to know them before they ever visit your office. It made the process of a new provider so EASY! Thanks Quote Masters for doing all the leg work!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #EF4136;">R</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Robert Kim</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@kim...3 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">Website was clear and easy to navigate. The QM customer service answered quickly and walked me through the only question I had. I will use these guys again!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #92278F;">S</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Scott Lars</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@lars...5 months ago</p>
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
            <div class="quote_btn" onclick="myFunction()"><img class="my-float" src="Images/prati/chat.png" alt=""></div>
            <!-- floating ginnie -->
            <div class="hide_ginnie" style="min-width: 200px;">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="col-6 my-auto">
                            <img class="card-img" src="Images/prati/guide_ginnie.png" alt="">
                        </div>
                        <div class="col-6 my-auto">
                            <p class="card-text">Hello! Ask me anything!</p>
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
                            <div class="chat-logoimage-bg">
                                <div class="card-header chat-card-header p-0">
                                    <div class="p-2 chat-card-header-img">
                                        <img src="Images/prati/chat.png" alt="">
                                    </div>
                                    <h6>Chat Support</h6>
                                    <div style="margin-left: auto; margin-right: 5px;">
                                        <i class="fa fa-minus m-1 min-chat-bot" aria-hidden="true"></i>
                                        <i class="fa fa-times m-1 close-chat-bot" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="card-body messages-box">
                                    <ul class="list-unstyled messages-list">


                                    </ul>
                                </div>
                                <div class="card-header message-input-send">
                                    <div class="input-group type-message-input">
                                        <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type message here..." />
                                        <span class="input-group-append input-send-btn">
                                            <button type="button" class="btn" value="Send" onclick="send_msg()"><img src="Images/prati/message-send.png" alt=""></button>
                                            <!-- <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()"> -->
                                        </span>
                                    </div>
                                    <p class="guide_para">Click the send button to respond</p>
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
            var html = '<li class="messages-me clearfix"><span class="message-img"><img src="Images/prati/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p message-p-right">' + txt + '</p></div></li>';
            jQuery('.messages-list').append(html);
            jQuery('#input-me').val('');
            if (txt) {
                jQuery.ajax({
                    url: 'get_bot_message.php',
                    type: 'post',
                    data: 'txt=' + txt,
                    success: function(result) {
                        var html = '<li class="messages-you clearfix"><span class="message-img"><img src="Images/prati/q_avatar.png" class="avatar-sm"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Ginnie</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p message-p-left">' + result + '</p></div></li>';
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
                            //console.log(res);
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
                    //$('#service_modal').modal('show');


                    //$('#customer_regsiter_modal').modal('toggle');
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please select your ZIP code from dropdown'
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
                    let text = "We are going to send a verification code to the email address and cell phone number you have provided";
                    if (!$('#custAgreeTerms').is(':checked')) {
                        alert('Please agree to the terms.');
                        return false;
                    } else {
                        if (confirm(text) == true) {
                            text = "You pressed OK!";
                            //console.log(text);
                            form.submit();
                        } else {
                            text = "You canceled!";
                        }
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

            //chatbot close button
            $(".close-chat-bot").on("click", function() {
                $("#myPopup").removeClass("show");
            });
            $(".min-chat-bot").on("click", function() {
                var mesgBoxElement = $(".messages-box");
                var mesgInputElement = $(".message-input-send");
                // Toggle the "active" class on the target element
                mesgBoxElement.toggleClass("hide");
                mesgInputElement.toggleClass("hide");
            });
        });
    </script>

<script>
        // Get the target timestamp from PHP
        var targetTimestamp = <?php echo $targetTimestamp; ?>*1000;
        targetTimestamp = Date.parse("<?php echo date("F j, Y, H:i:s", strtotime($targetDate)); ?>");

        // Update the countdown every second
        var countdownInterval = setInterval(function() {
            // Get the current timestamp
            var now = new Date().getTime();

            // Calculate the difference between the target and current timestamps
            var distance = targetTimestamp - now;

            // Calculate days, hours, minutes, and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";

            // If the countdown is over, display a message
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>
    <?php include '_getQuotes_modal.php'; ?>
</body>

</html>