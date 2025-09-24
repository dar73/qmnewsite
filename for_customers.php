<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'For Customers';
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
                <img class="mr-md-5" src="Images/location.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">PROVIDE YOUR ZIP CODE</h2>
                        <p>Provide the zip code where service is required and schedule your convenient time(s) to meet with each service provider!</p>
                        <a href="index.php" class="btn primary-btn">Get a Quote</a>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/search.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">WE GO TO WORK FOR YOU</h2>
                        <p>We have searched all local providers and will match you with the best ones!</p>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/text.png" alt="">
                <div class="col-12 border-md-bottom">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">GET QUOTES FROM QUALIFIED PROFESSIONALS</h2>
                        <p> You can view each service provider's details like their profile, website, qualifications and reviews before you even meet with them.</p>
                    </div>
                </div>
                </li>
            </div>

            <div class="row infor">
                <li class="d-md-flex d-block w-100 text-center text-md-left">
                <img class="mr-md-5" src="Images/review.png" alt="">
                <div class="col-12">
                    <div class="list_main_div pb-4">
                        <h2 class="mx-0 my-3">WE LOVE REVIEWS</h2>
                        <p>Please let us know how our team can better meet your needs.</p>
                    </div>
                </div>
                </li>
            </div>
        </ul>
        
        <!-- /.row -->
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