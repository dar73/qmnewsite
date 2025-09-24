<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$GetQuotesBtn = '<button class="searchitem desktop" type="button" onclick="opensigninmodal()" id="btn_search">Get Quotes</button>';
$GetQuotesBtn2 = '<button class="searchitem mobile" type="button" onclick="opensigninmodal()"><i class="fa fa-search" id="Btn_icon" style="font-size:24px"></i></button>';
//session_destroy();
//DFA($_SESSION);
$LOGINBTN = '<button class="login_button hidebutton" onclick="opensigninmodal();">Login</button>';
$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    $customer_id = $_SESSION['udat_DC']->user_id;
    $customer_name = $_SESSION['udat_DC']->user_name;
    //$customer_email = $_SESSION['udat_DC']->user_email;
    //$customer_email = GetXFromYID("select vEmail from customer where iCustID = $customer_id");
    $LOGINBTN = '<a class="login_button hidebutton" href="logout.php">Logout</a>';
    $GetQuotesBtn = '<button class="searchitem desktop" type="button" onclick="openGetQuotemodal()" id="btn_search">Get Quotes</button>';
    $GetQuotesBtn2 = '<button class="searchitem mobile" type="button" onclick="openGetQuotemodal()"><i class="fa fa-search" id="Btn_icon" style="font-size:24px"></i></button>';
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	  
    <link rel="stylesheet" href="css/prati.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <title>Master Quote</title>
  </head>
  <body>

  <nav class="navbar navbar-expand-md navbar-light bg-light py-0">
      <a class="navbar-brand" href="#"><img class="logo_img" src="Images/logo.png" alt=""></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarsExample04">
        <div class="col-12 col-lg-8 text-right ml-auto">
                <button type="button" class="btn bg-transparent me-2 font-weight-bold" id="log-btn">Login</button>
                
                <button type="button" class="btn btn-outline-success" id="cust-btn"><i class="fa fa-user-circle" aria-hidden="true"></i> Join as Customer</button>
                <button type="button" class="btn btn-success" id="prof-btn"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
            </div>
      </div>
    </nav>

    <div class="hero-wrap js-fullheight p-sm-5" style="background-image: url('images/prati/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row py-5 no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-12 ftco-animate">
          	<h2 class="subheading">We Are Making Your Place As Good As New</h2>
          	<h1 class="mb-4">Do You Need To Get Quotes For Janitorial Service??</h1>
             </div>
          <div class="col-md-12">
          <div class="wrap-appointment bg-white d-md-flex pb-5 pb-md-0">
	    				<form action="#" class="appointment w-100">
	    					<div class="row justify-content-center">
									<div class="col-md-8 d-flex align-items-center pt-4 pt-md-0">
										<div class="col-12 form-group py-md-4 py-2 px-4 px-md-0">
				              <input type="text" class="form-control" placeholder="Please enter the ZIP code">
				            </div>
									</div>
									<div class="col-md-2 d-flex align-items-center">
										<div class="form-group py-md-4 py-2 px-4 px-md-0 d-flex">
				              <input type="submit" value="Get a Quote" class="btn btn-success py-3 px-4">
				            </div>
									</div>
	    					</div>
		                </form>
		    		</div>
          </div>
        </div>
      </div>
    </div>

    

    <section class="ftco-section py-5">
    	<div class="container">
    		<div class="row justify-content-center pb-5 mb-3">
          <div class="col-md-7 heading-section text-center ftco-animate">
          	<span class="subheading">Services</span>
            <h2>How We Works</h2>
          </div>
        </div>
    		<div class="row">
          <div class="col-md-6 col-lg-4 services ftco-animate">
            <div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span><img src="images/prati/lawn-mower.png" alt=""></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">Green Cleaning</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 services ftco-animate">
          	<div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span><img src="images/prati/Janitor.png" alt=""></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">Janitorial Cleaning</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 services ftco-animate">
            <div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span><img src="images/prati/office.png" alt=""></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">Office Cleaning</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div> 
          </div>

          <div class="col-md-6 col-lg-4 services ftco-animate">
          	<div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span><img src="images/prati/cleaning-spray.png" alt=""></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">COVID-19 Cleaning Services</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div> 
          </div>

          <div class="col-md-6 col-lg-4 services ftco-animate">
            <div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-garden"></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">Garden Cleaning</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 services ftco-animate">
          	<div class="d-block d-flex">
              <div class="icon d-flex justify-content-center align-items-center">
            		<span class="flaticon-balcony"></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading">Window Cleaning</h3>
                <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic.</p>
                <p><a href="#" class="btn-custom">Read more</a></p>
              </div>
            </div>
          </div>
        </div>
    	</div>
    </section>

    <!-- footer -->
    <footer class="pt-4 my-md-5 pt-md-5 p-5 border-top">
        <div class="row">
          <!-- <div class="col-12 col-md">
            <img class="mb-2" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">
            <small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
          </div> -->
          <div class="col-10 col-sm-6 col-md-3">
            <h5>For Customers</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">Find a Professional</a></li>
              <li><a class="text-muted" href="#">How it works</a></li>
              <li><a class="text-muted" href="#">Login</a></li>
            </ul>
          </div>
          <div class="col-10 col-sm-6 col-md-3">
            <h5>For Provider</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">How it works</a></li>
              <li><a class="text-muted" href="#">Join as a Provider</a></li>
              <li><a class="text-muted" href="#">Help center</a></li>
            </ul>
          </div>
          <div class="col-10 col-sm-6 col-md-3">
            <h5>About</h5>
            <ul class="list-unstyled text-small">
              <li><a class="text-muted" href="#">About QM</a></li>
              <li><a class="text-muted" href="#">Team</a></li>
            </ul>
          </div>
          <div class="col-10 col-sm-6 col-md-3 footerInfo text-md-right">
            <p class=" font-weight-bold"><a href="mailto:quotemasters@.com">quotemasters@.com</a></p>
            <p><a href="tel:+91 1234567890">+91 1234567890</a></p>
            <p class="footerTime text-muted">(Mon-Fri 8:30am-7pm)</p>  
          </div>
        </div>

        <div class="row my-3 justify-content-sm-center footerSocial">
          <div class="col-4 d-inline-flex">
              <div class="col-3 text-center"><img src="Images/footer/fb.png" alt=""></div>
              <div class="col-3 text-center"><img src="Images/footer/insta.png" alt=""></div>
              <div class="col-3 text-center"><img src="Images/footer/tweet.png" alt=""></div>
          </div>
        </div>

        <div class="row mt-5">
              <div class="col-sm-6 text-sm-left"><p class="">@ 2023 quotemaster.com</p></div>
              <div class="col-sm-6 text-sm-right"><p class="">All rights reserved</p></div>
        </div>
        
      </footer>
<!-- Footer -->
   

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>