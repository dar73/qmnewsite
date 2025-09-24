<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Platinum Program';
$TITLE = SITE_NAME . ' | ' . $page_title;
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'load.link.php'; ?>
  <style>
    h1 {
      text-align: center;
      color: #007bff;
      /* Theme color */
      font-size: 2.5em;
      margin-bottom: 30px;
      font-weight: 700;
    }

    .benefit {
      margin-bottom: 30px;
      transition: transform 0.3s ease;
    }

    .benefit:hover {
      transform: translateY(-5px);
    }

    .benefit h2 {
      color: #007bff;
      /* Theme color */
      font-size: 1.75em;
      margin-bottom: 10px;
      border-left: 5px solid #007bff;
      padding-left: 10px;
      font-weight: 600;
    }

    .benefit p,
    .benefit ul {
      margin-left: 20px;
      font-size: 1.1em;
      line-height: 1.6;
    }

    .benefit ul {
      list-style-type: disc;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include 'header.php'; ?>
    <!-- /.navbar -->
    <!-- /.content-wrapper -->
    <div class="content-wrapper">
      <!-- Main content -->
      <div class="content">
        <div class="container py-2">
          <!-- <div class="row justify-content-lg-end">
            <div class="col col-lg-8 col-12 text-right">
                <img class="logo_banner_image mt-sm-5 mt-3 mr-md-5" src="Images/logo.png" alt="">
            </div>
        </div> -->

          <h1>Benefits of  Platinum Program</h1>

          <div class="benefit">
            <h2>1. Guaranteed Leads</h2>
            <ul>
              <li>Receive up to 6 qualified leads per month.</li>
              <li>Customize your lead preferences based on frequency, industry, and more.</li>
            </ul>
          </div>

          <div class="benefit">
            <h2>2. Automated Scheduling</h2>
            <ul>
              <li>Convenient billing keeps pay-per-lead structure.</li>
              <li>Sync your calendar for easy scheduling.</li>
            </ul>
          </div>

          <div class="benefit">
            <h2>3. VIP Mobile App Upgrade</h2>
            <ul>
              <li>Access a mobile app with a calendar that syncs with your existing one.</li>
              <li>Enjoy seamless appointment scheduling on the go.</li>
            </ul>
          </div>

          <div class="benefit">
            <h2>4. VIP Platinum Concierge Desk</h2>
            <ul>
              <li><strong>Dedicated Support:</strong> Call our 800 number for immediate connection to live support.</li>
              <li><strong>No Subscription Fees:</strong> Pay only when you receive leads.</li>
              <li><strong>Expedited Credit Reviews:</strong> Get faster credit assessments.</li>
            </ul>
          </div>


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