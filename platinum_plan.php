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
      font-size: 2.5em;
      margin-bottom: 30px;
      font-weight: 700;
      animation: fadeInDown 1s ease-in-out;
    }

    .benefit {
      margin-bottom: 30px;
      padding: 20px;
      border-radius: 10px;
      background: #e5e4e2;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      animation: slideUp 0.8s ease-in-out;
    }

    .benefit:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .benefit h2 {
      color: #007bff;
      font-size: 1.75em;
      margin-bottom: 10px;
      border-left: 5px solid #007bff;
      padding-left: 10px;
      font-weight: 600;
      animation: fadeInLeft 1s ease-in-out;
    }

    .benefit p,
    .benefit ul {
      margin-left: 20px;
      font-size: 1.1em;
      line-height: 1.6;
      animation: fadeInRight 1.2s ease-in-out;
    }

    .benefit ul {
      list-style-type: disc;
    }

    /* Keyframe Animations */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(20px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Backdrop Blur Effect */
    .benefit::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(10px);
      z-index: -1;
      border-radius: 10px;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include 'header.php'; ?>
    <!-- /.navbar -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Main content -->
      <div class="content">
        <div class="container py-2">
          <h1>Benefits of the Platinum Program</h1>

          <div class="benefit">
            <h2>1. Guaranteed Leads</h2>
            <ul>
              <li>Receive up to 5 to 8 qualified leads per month.</li>
              <li>Customize your lead preferences based on Volume, Coverage Area, and Industry.</li>
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

    <!-- Footer -->
    <?php include 'footer.php'; ?>
  </div>
  <?php include 'load.scripts.php'; ?>
</body>

</html>