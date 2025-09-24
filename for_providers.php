<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'For Providers';
$TITLE = SITE_NAME . ' | ' . $page_title;
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'load.link.php'; ?>
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->
<!-- /.content-wrapper -->
<div class="content-wrapper">
          <!-- Main content -->
    <div class="content how_it_works">
      <div class="container py-2">
        <!-- <div class="row justify-content-lg-end">
            <div class="col col-lg-8 col-12 text-right">
                <img class="logo_banner_image mt-sm-5 mt-3 mr-md-5" src="Images/logo.png" alt="">
            </div>
        </div> -->

        <ul class="p-0">
            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/inform.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">BUSINESSES INFORM US OF THEIR NEEDS</h2>
                        <p>We evaluate exactly what the customer’s current needs are and collect the key details of their current situation.
                            We then coordinate the opportunity for you to meet them so you can present how your company is the solution
                            they’re looking for.</p>
                        <a href="vendor_register.php" class="btn primary-btn">Join as Provider</a>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/details.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">GET LEADS FROM QUOTE MASTERS</h2>
                        <p>Customers trust us to match them with pre-qualified providers who are ready to service them. You will receive a comprehensive report
                            detailing their current cleaning situation and a day and time they are prepared to meet with you. These are code verified leads, so you
                            know you have a real opportunity delivered directly into your hands</p>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/growth.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">GROW YOUR COMPANY WHERE YOU WANT</h2>
                        <p>With Quote Masters in your corner YOU define your coverage area, and we bring you solid and verified opportunities from businesses
                            who need your services. You enjoy brand-new prospects, and we present those potential customers with all the pertinent information
                            you want them to know about your company before you even meet with them!</p>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/benefits.png" alt="">
                <div class="col-12">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">SO MANY MORE BENEFITS</h2>
                        <p>Quote Masters provides you with a profile page that will showcase your companies extensive qualifications. We will represent you as one
                            of our highly qualified professionals who are ready and able to service their needs. Our support team will gladly help you build and polish
                            your profile.</p>
                        <a href="vendor_register.php" class="btn primary-btn">Join as Provider</a>
                    </div>
                </div>
                </li>
            </div>
        </ul>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  

  <!-- Main Footer -->

        <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
</body>
</html>