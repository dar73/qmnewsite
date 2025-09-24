<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Why to Join';
$TITLE = SITE_NAME . ' | ' . $page_title;
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'load.link.php'; ?>
  <style>
    .comparison-heading {
      font-size: 3rem;
      margin-bottom: 40px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #333;
    }

    .comparison-card {
      border: none;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: linear-gradient(135deg, #ffffff, #f7f7f7);
    }

    .comparison-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }

    .basic-plan {
      background: linear-gradient(135deg, #f2f2f2, #e6e6e6);
    }

    .platinum-plan {
      background: linear-gradient(135deg, #BE1E2D, #ff4c61);
      color: white;
    }

    .platinum-plan h2 {
      color: #fff;
    }

    .platinum-plan ul li strong {
      color: #ffedf0;
    }

    .comparison-card h2 {
      font-size: 2rem;
      margin-bottom: 25px;
      font-weight: 600;
    }

    .comparison-card ul {
      list-style-type: none;
      padding: 0;
      font-size: 1.1rem;
    }

    .comparison-card ul li {
      margin-bottom: 15px;
      font-size: 1rem;
      line-height: 1.6;
    }

    .comparison-card ul li::before {
      content: "✔";
      color: #BE1E2D;
      font-weight: bold;
      margin-right: 10px;
    }

    .platinum-plan ul li::before {
      color: #fff;
    }

    .platinum-plan ul li ul {
      padding-left: 20px;
      list-style-type: disc;
    }

    .platinum-plan ul li ul li {
      font-size: 0.9rem;
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .comparison-card {
        margin-bottom: 20px;
      }
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php include 'header.php'; ?>
    <div class="content-wrapper">
      <div class="content why_to_join">
        <div class="container-fluid welcome_container">
          <div class="row py-5">
            <div class="col-12 py-5 text-center">
              <h1>Welcome to QUOTE MASTERS!</h1>
              <h4>We are looking for top service providers to partner with. Are you one of them?</h4>
              <a href="vendor_register.php" class="btn btn-primary mt-5">Join as Provider</a>
            </div>
          </div>
        </div>


        <div class="container py-5">
          <div class="row justify-content-center">
            <div class="col-12 text-center">
              <h1 class="comparison-heading">Choose Your Plan</h1>
            </div>
          </div>
          <div class="row justify-content-center">
            <!-- Basic Plan -->
            <!-- <div class="col-md-4 col-sm-6 col-12 mb-4">
              <div class="comparison-card basic-plan">
                <div class="card-body">
                  <h2 class="text-center">Basic Plan</h2>
                  <ul>
                    <li><strong>Leads:</strong> Limited Lead Filters.</li>
                    <li><strong>Scheduling:</strong> Select Leads Manually.</li>
                    <li><strong>Support:</strong> Standard customer support.</li>
                    <li><strong>Mobile Access:</strong> Basic Mobile App.</li>
                  </ul>
                  <a href="vendor_register.php" class="btn btn-primary">Sign Up</a>
                </div>
              </div>
            </div> -->
            <!-- Platinum Plan -->
            <div class="col-md-4 col-sm-6 col-12 mb-4">
              <div class="comparison-card platinum-plan">
                <div class="card-body">
                  <h2 class="text-center">Platinum Plan</h2>
                  <ul>
                    <li><strong>Guaranteed Leads:</strong> Receive up to 8 qualified leads per month. Customize your lead preferences based on frequency, industry, and more.</li>
                    <li><strong>Automated Scheduling:</strong> Convenient billing keeps pay-per-lead structure. Sync your calendar for easy scheduling.</li>
                    <li><strong>VIP Mobile App Upgrade:</strong> Access a mobile app with a calendar that syncs with your existing one. Enjoy seamless appointment scheduling on the go.</li>
                    <li><strong>VIP Platinum Concierge Desk:</strong>
                      <ul>
                        <li><strong>Dedicated Support:</strong> Call our 800 number for immediate connection to live support.</li>
                        <li><strong>No Subscription Fees:</strong> Pay only when you receive leads.</li>
                        <li><strong>Expedited Credit Reviews:</strong> Get faster credit assessments.</li>
                      </ul>
                    </li>
                  </ul>
                  <a href="vendor_register.php?plan=P" class="btn btn-primary">Sign Up</a>
                </div>
              </div>
            </div>
          </div>
        </div>





        <div class="container">
          <div class="row justify-content-center py-5">
            <div class="col-12">
              <h1>Quote Masters will deliver to you:</h1>
            </div>
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-briefcase fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Businesses that want to get a quote for their cleaning needs</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-calendar-check fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Set day and time they will meet with you, verified with calendar invites.</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-check-square fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Code verified customer information, so you know the lead is REAL!</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Complete details of the customer’s needs, or current problems they share</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-flag-checkered fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">The opportunity to provide your company info before you ever arrive!</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-address-card fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Leads in areas where you are looking to grow your business</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-registered fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">A simple, straight-forward sign-up process</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">A fast and efficient lead-buying process</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
            <!-- /.col-md-4 -->
            <div class="col-md-3 col-sm-5 col-12 m-3 card">
              <div class="SP_card">
                <div class="card-body">
                  <div class="card-text text-center">
                    <i class="fa fa-user fa-2x" aria-hidden="true"></i>
                    <h6 class="mt-3">Your own profile page to view activity and keep us updated!</h6>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.col-md-4 -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
        <!-- container -->
        <div class="container need_container">
          <div class="row py-5">
            <div class="col-md-6 col-12 my-auto pb-5">
              <h1>What we need <br>to get you signed up:</h1>
            </div>
            <div class="col-md-6 col-12">
              <div class="small-box bg-license">
                <div class="inner text-center text-white">
                  <p>
                  <h4>Business License</h4> For your cleaning company</p>
                </div>
              </div>
              <div class="small-box bg-insurance">
                <div class="inner text-center text-white">
                  <p>
                  <h4>Set up your coverage area</h4> For the services you provide</p>
                </div>
              </div>
              <div class="small-box bg-arrive">
                <div class="inner text-center text-white">
                  <p>
                  <h4>Before you arrive!</h4> Anything you want the customer to know about you</p>
                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- container -->
        <!-- container -->
        <div class="container py-5">
          <div class="row py-3">
            <div class="col-12 text-center">
              <h1>Cost?</h1>
            </div>
          </div>

          <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="row mb-5">
                <div class="col-md-6 right_text py-3 text-md-left text-center px-5">
                  <p class="mx-0 my-3">
                    <!-- <p class="mx-0 my-3">Every verified lead <br>that is a frequency of <br>
                  <h4>1x to 7x per week </h4> -->
                  <h4 style="color:#000">For questions about Pricing or more details about becoming a Quote Masters Service Provider contact us here</h4>
                  <a target="_blank" href="https://calendly.com/michael2-thequotemasters/30min?month" class="btn btn-primary mt-5">Get info</a>
                  </p>
                </div>
                <!-- <div class="col-md-6 mt-md-auto pb-md-3 mr-md-auto mt-3">
                  <h2 class="cost mx-auto">$125</h2>
                </div> -->
              </div>

              <!-- <div class="row my-5">
                <div class="col-md-6 right_text py-3 text-md-left text-center px-5">
                  <p class="mx-0 my-3">Every verified lead <br>that is a frequency of <br>
                  <h4>1x per week </h4> <br>is only</p>
                </div>
                <div class="col-md-6 mt-md-auto pb-md-3 mr-md-auto mt-3">
                  <h2 class="cost mx-auto">$100</h2>
                </div>
              </div> -->
            </div>

          </div>
          <section>
            <div class="container">
              <div class="row justify-content-center py-5">
                <div class="col-12 text-center">
                  <h2>READY to get great leads? </h2>
                  <h4>Click for our simple sign-up process where we provide full support!</h4>
                  <a href="vendor_register.php" class="btn btn-primary mt-5">Join as Provider</a>
                </div>
              </div>
            </div>
          </section>


        </div>
      </div>
      <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
</body>

</html>