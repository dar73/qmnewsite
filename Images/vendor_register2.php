<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');

$page_title = 'Vendor Register';
$TITLE = SITE_NAME . ' | ' . $page_title;
$LOGINBTN = '<li class="nav-item m-2">
                    <button class="btn nav-link px-2 float-right" onclick="window.location.href=\'vendor_register.php\'"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
                    <!-- <a href="index3.html" class="nav-link">Home</a> -->
                </li>
                <li class="nav-item dropdown m-2">
                    <button class="btn nav-link dropdown-toggle px-2 float-right" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Login</button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="plogin.php">Provider <i class="fa fa-briefcase" aria-hidden="true"></i></a>
                        
                    </div>
                </li>';

$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
  if ($_SESSION['udat_DC']->user_level == 2) {
    header('location:ctrl/v_profile.php');
  } else if ($_SESSION['udat_DC']->user_level == 3) {
    header('location:ctrl/c_profile.php');
  }
}
$done = '';
if (isset($_GET['err']) && is_numeric($_GET['err'])) {
  $done = $_GET['err'];
}
$cartArr = isset($_SESSION['COVERAGE']) ? $_SESSION['COVERAGE'] : $_SESSION['COVERAGE'] = array();

// DFA($cartArr);

$source = '';
$rdISBI = '';
$code_flag = '0';
$cleaningstatus = '';
$PLAN = 'S';
$BASIC_CHECK = '';
$PLATINUM_cHECK = 'checked';
if (isset($_GET['plan'])) {
  $PLAN = 'P';
  $BASIC_CHECK = '';
  $PLATINUM_cHECK = 'checked';
}

$COUNTRIES = GetXArrFromYID("SELECT country_id, country_name FROM countries WHERE 1 and cStatus='A'", '3');
$COUNTY_ARR = GetXArrFromYID("SELECT county_id, county_name FROM counties WHERE 1", '3');
$STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 ", '3');
$CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE 1 ", '3');
?>
<!DOCTYPE html>
<html>

<head>
  <?php include 'load.link.php'; ?>
  <style>
    /* Base Styles */


    .wrapper {
      background: transparent;
    }

    /* Form Container */
    .form-container {
      max-width: 1000px;
      margin: 30px auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    /* Form Header */
    .form-header {
      background: #BC202E;
      color: white;
      padding: 30px;
      text-align: center;
    }

    .form-header h3 {
      font-weight: 600;
      margin: 0;
      font-size: 28px;
      color: white;
    }

    /* Progress Steps */
    .progress-steps {
      display: flex;
      justify-content: space-between;
      padding: 20px 50px;
      background: #f8f9fa;
      position: relative;
    }

    .progress-steps:before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50px;
      right: 50px;
      height: 2px;
      background: #e9ecef;
      z-index: 1;
      transform: translateY(-50%);
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 2;
    }

    .step-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 10px;
      color: #6c757d;
      font-weight: bold;
      font-size: 20px;
      transition: all 0.3s ease;
      border: 3px solid white;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .step.active .step-icon {
      background: #667eea;
      color: white;
      transform: scale(1.1);
    }

    .step.completed .step-icon {
      background: #28a745;
      color: white;
    }

    .step-label {
      font-size: 14px;
      color: #6c757d;
      font-weight: 500;
    }

    .step.active .step-label {
      color: #343a40;
      font-weight: 600;
    }

    /* Form Content */
    .form-content {
      padding: 30px 40px;
    }

    .form-step {
      display: none;
      animation: fadeIn 0.5s ease;
    }

    .form-step.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #495057;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #ced4da;
      transition: all 0.3s ease;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Radio and Checkbox */
    .custom-radio,
    .custom-checkbox {
      margin-right: 15px;
      margin-bottom: 10px;
    }

    /* Buttons */
    .btn {
      border-radius: 8px;
      padding: 12px 25px;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background: #5a6268;
      color: white;
    }

    /* Navigation Buttons */
    .form-navigation {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e9ecef;
    }

    /* Coverage Table */
    .coverage-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .coverage-table th {
      background: #667eea;
      color: white;
      padding: 12px 15px;
      text-align: left;
    }

    .coverage-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #e9ecef;
      background: white;
    }

    .coverage-table tr:last-child td {
      border-bottom: none;
    }

    /* Plan Cards */
    .plan-card {
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s ease;
      border: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .plan-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .plan-card-header {
      padding: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .plan-card-body {
      padding: 20px;
      background: white;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .progress-steps {
        padding: 15px 20px;
      }

      .step-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
      }

      .form-content {
        padding: 20px;
      }
    }

    /* Password Toggle */
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 42px;
      cursor: pointer;
      color: #6c757d;
    }

    .password-toggle:hover {
      color: #495057;
    }

    /* Custom Checkbox */
    .custom-control-input:checked~.custom-control-label::before {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-color: #667eea;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <?php include 'header.php'; ?>

    <div class="content-wrapper" style="background: transparent;">
      <section class="content">
        <div class="container-fluid">
          <form action="register2.php" id="vendorFORM" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="code_flag" id="code_flag" value="<?php echo $code_flag; ?>">

            <div class="form-container">
              <!-- Form Header -->
              <div class="form-header">
                <h3>Join as a Service Provider</h3>
              </div>
              <div class="text-center mb-6">
                <img src="Images/logo.png" style="height: auto; max-width: 100%;" alt="Company Logo" class="h-8 sm:h-10 md:h-12 lg:h-14 xl:h-16 w-auto mx-auto mb-2">

              </div>

              <!-- Progress Steps -->
              <div class="progress-steps">
                <div class="step active" data-step="1">
                  <div class="step-icon">1</div>
                  <div class="step-label">Basic Info</div>
                </div>
                <div class="step" data-step="2">
                  <div class="step-icon">2</div>
                  <div class="step-label">Company Details</div>
                </div>
                <div class="step" data-step="3">
                  <div class="step-icon">3</div>
                  <div class="step-label">Coverage Areas</div>
                </div>
                <div class="step" data-step="4">
                  <div class="step-icon">4</div>
                  <div class="step-label">Plan Selection</div>
                </div>
              </div>

              <!-- Form Content -->
              <div class="form-content">
                <div id="LBL_INFO"></div>

                <!-- Step 1: Basic Information -->
                <div class="form-step active" id="step-1">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>How did you come to know about us? <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                          <?php echo FillRadios($source, 'source', $SOURCE, '', 'custom-radio'); ?>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Do you carry business insurance for your janitorial company? <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                          <?php echo FillRadios($rdISBI, 'rdISBI', $YES_ARR, '', 'custom-radio'); ?>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Do you provide cleaning services during business hours? <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                          <?php echo FillRadios($cleaningstatus, 'cleaningstatus', $YES_ARR, '', 'custom-radio'); ?>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Your first name">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Your last name">
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="your.email@example.com"
                          onKeyUp="IsCodeUnique('0', this, 'USER_EMAIL');" onBlur="IsCodeUnique('0', this, 'USER_EMAIL');">
                        <span id="EMAIL_EXISTS" style="color: red;" class="em"></span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="(123) 456-7890"
                          onkeypress="return numbersonly(event);">
                      </div>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <div></div>
                    <button type="button" class="btn btn-primary next-step" data-next="2">Continue <i class="fas fa-arrow-right ml-2"></i></button>
                  </div>
                </div>

                <!-- Step 2: Company Details -->
                <div class="form-step" id="step-2">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="cname">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cname" name="cname" placeholder="Your company name">
                      </div>

                      <div class="form-group">
                        <label>What is your calendar account type? <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap">
                          <?php echo FillRadios('G', 'rdactype', $CALENDAR_AC_TYPE_ARR, '', 'custom-radio'); ?>
                        </div>
                      </div>

                      <h5 class="mt-4 mb-3">Company Address</h5>
                      <div class="form-group">
                        <label for="street">Street Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="street" name="street" placeholder="123 Main St">
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="countryid">Country <span class="text-danger">*</span></label>
                            <?php echo FillCombo2022('countryid', '', $COUNTRIES, 'Select Country', 'form-control', 'GetStates(this.value);'); ?>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="stateid">State <span class="text-danger">*</span></label>
                            <span id="STATE_DIV">
                              <?php echo FillCombo2022('stateid', '', array(), 'Select State', 'form-control', 'GetCity2(this.value);'); ?>
                            </span>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="cityid">City <span class="text-danger">*</span></label>
                            <span id="CITY_DIV">
                              <?php echo FillCombo2022('cityid', '', array(), 'Select City', 'form-control', ''); ?>
                            </span>
                          </div>
                        </div>
                      </div>

                      <h5 class="mt-4 mb-3">Create Password</h5>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="passwd1">New Password <span class="text-danger">*</span></label>
                            <div class="position-relative">
                              <input type="password" class="form-control" id="passwd1" name="passwd1">
                              <i class="fas fa-eye password-toggle" onclick="showpassword1()"></i>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="passwd2">Confirm Password <span class="text-danger">*</span></label>
                            <div class="position-relative">
                              <input type="password" class="form-control" id="passwd2" name="passwd2">
                              <i class="fas fa-eye password-toggle" onclick="showpassword2()"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn btn-secondary prev-step" data-prev="1"><i class="fas fa-arrow-left mr-2"></i> Back</button>
                    <button type="button" class="btn btn-primary next-step" data-next="3">Continue <i class="fas fa-arrow-right ml-2"></i></button>
                  </div>
                </div>

                <!-- Step 3: Coverage Areas -->
                <div class="form-step" id="step-3">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="countryid2">Country <span class="text-danger">*</span></label>
                        <?php echo FillCombo2022('countryid2', '', $COUNTRIES, 'Select Country', 'form-control', 'GetStates2(this.value);'); ?>
                      </div>

                      <div class="form-group">
                        <label for="state2">State <span class="text-danger">*</span></label>
                        <span id="STATE_DIV2">
                          <?php echo FillCombo2022('state2', '', array(), 'Select State', 'form-control mul', 'GetMultipleCounties(this.value)'); ?>
                        </span>
                      </div>

                      <div class="form-group">
                        <label for="countyid">Counties You Cover</label>
                        <span id="COUNTY_DIV">
                          <?php echo FillComboMultiSelect('countyid', '', array(), 'Select Counties', 'form-control mul', 'GetCities2(this.value);'); ?>
                        </span>
                      </div>

                      <div class="form-group">
                        <label for="city">Cities You Cover</label>
                        <span id="CITY_DIV2">
                          <?php echo FillComboMultiSelect('city', '', array(), 'Select Cities', 'form-control mul'); ?>
                        </span>
                      </div>

                      <button type="button" onclick="Add_coverage();" id="addToCart_btn" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Add Coverage Area
                      </button>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Your Coverage Areas</label>
                        <div class="coverage_cart" id="coverageDiv">
                          <table class="coverage-table">
                            <thead>
                              <tr>
                                <th>Country</th>
                                <th>State</th>
                                <th>Counties</th>
                                <th>Cities</th>
                                <th style="width:50px;">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              if (isset($_SESSION['COVERAGE'])) {
                                foreach ($_SESSION['COVERAGE'] as $countryKey => $states) {
                                  foreach ($states as $stateKey => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $COUNTRIES[$countryKey] . '</td>';
                                    echo '<td>' . $STATE_ARR[$stateKey] . '</td>';
                                    echo '<td>';
                                    if (isset($value['county']) && $value['county'] != '') {
                                      $countyNames = array_map(function ($countyId) use ($COUNTY_ARR) {
                                        return $COUNTY_ARR[$countyId];
                                      }, $value['county']);
                                      echo implode(", ", $countyNames);
                                    }
                                    echo '</td>';
                                    echo '<td>';
                                    if (isset($value['city']) && $value['city'] != '') {
                                      $cityNames = array_map(function ($cityId) use ($CITY_ARR) {
                                        return $CITY_ARR[$cityId];
                                      }, $value['city']);
                                      echo implode(", ", $cityNames);
                                    }
                                    echo '</td>';
                                    echo '<td class="text-center"><a href="javascript:void(0)" onclick="remove(\'' . $countryKey . '\', \'' . $stateKey . '\')" class="text-danger"><i class="fas fa-times"></i></a></td>';
                                    echo '</tr>';
                                  }
                                }
                              } else {
                                echo '<tr><td colspan="5" class="text-center py-4 text-muted">No coverage areas added yet</td></tr>';
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn btn-secondary prev-step" data-prev="2"><i class="fas fa-arrow-left mr-2"></i> Back</button>
                    <button type="button" class="btn btn-primary next-step" data-next="4">Continue <i class="fas fa-arrow-right ml-2"></i></button>
                  </div>
                </div>

                <!-- Step 4: Plan Selection -->
                <div class="form-step" id="step-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="plan-card">
                        <div class="plan-card-header">
                          <h4 class="mb-0">Platinum Plan</h4>
                          <p class="mb-0">No additional cost - Currently Free</p>
                        </div>
                        <div class="plan-card-body">
                          <p>This plan includes all features and priority support.</p>
                          <div class="form-check">
                            <input type="radio" class="form-check-input" name="plan" id="plan_premium" value="P" <?php echo $PLATINUM_cHECK; ?>>
                            <label class="form-check-label font-weight-bold" for="plan_premium">Select Platinum Plan</label>
                          </div>
                          <a href="platinum_plan.php" target="_blank" class="btn btn-outline-primary btn-sm mt-3">
                            <i class="fas fa-info-circle mr-2"></i> More Details
                          </a>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>List Industries You DO NOT Service</label>
                        <small class="text-muted d-block mb-2">(e.g., Restaurants, Auto Repair, Theaters)</small>
                        <?php echo FillMultiCombo('', 'cmbindustrylist', 'COMBO', 'Y', $INDUSTRY_ARR, '', 'form-control in'); ?>
                      </div>

                      <div class="form-group">
                        <label>Maximum Number of Leads per Month <span class="text-danger">*</span></label>
                        <small class="text-success d-block mb-2"><strong>Note: This is not a guarantee of volume</strong></small>
                        <div class="d-flex flex-wrap">
                          <?php echo FillRadios('', 'txt_max_leadsPerMonth', $LEADS_PER_WEEK, '', 'custom-radio'); ?>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="agreeTerms" name="terms" value="agree">
                          <label class="custom-control-label" for="agreeTerms">
                            I agree to the <a href="javascript:void()" data-toggle="modal" data-target="#SP-terms-modal">terms and conditions</a>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn btn-secondary prev-step" data-prev="3"><i class="fas fa-arrow-left mr-2"></i> Back</button>
                    <button class="btn btn-success g-recaptcha" data-sitekey="6Lf_pJInAAAAANtUgwkJ4V3unOz3SCzP-NENNz-M" data-callback='onSubmit' data-action='submit'>
                      <i class="fas fa-check-circle mr-2"></i> Complete Registration
                    </button>
                  </div>

                  <div class="text-center mt-4">
                    <p>Already have an account? <a href="plogin.php">Sign in here</a></p>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>

    <!-- Keep your existing modal and footer includes -->
    <?php include 'footer.php'; ?>
  </div>

  <?php include 'load.scripts.php'; ?>
  <script type="text/javascript" src="scripts/ajax.js"></script>
  <script type="text/javascript" src="scripts/common.js"></script>
  <script type="text/javascript" src="scripts/md5.js"></script>
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script>
    function onSubmit(token) {

      if (ValidateForm()) {
        document.getElementById("vendorFORM").submit();

      }
      //e.preventDefault();
    }

    function GetStates(id) {
      var data = "response=GET_STATES&countryid=" + id;
      $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function(response) {
          $('#STATE_DIV').html(response);
        }
      });

    }

    function GetStates2(id) {
      var data = "response=GET_STATES_MULTIPLE&countryid=" + id;
      $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function(response) {
          $('#STATE_DIV2').html(response);
          $('.mul').multiselect({
            header: true,
            columns: 1,
            search: true,
            selectAll: true
          });
        }
      });

    }


    function ValidateForm() {
      var err = 0;
      var ret_val = true;
      var countarr = 1;
      var codeflag = $('#code_flag');

      var first_name = $('#first_name');
      var email = $('#email');
      var source = $('input[name="source"]:checked');
      var rdISBI = $('input[name="rdISBI"]:checked');
      var cleaningstatus = $('input[name="cleaningstatus"]:checked');
      var last_name = $('#last_name');
      var company_name = $('#cname');
      var phone = $('#phone');
      var street = $('#street');
      var state_addr = $('#stateid');
      var county_name_adr = $('#countryid');
      var city_adr = $('#cityid');
      var passwd1 = $('#passwd1');
      var passwd2 = $('#passwd2');
      var state = $('#state');
      var county_name = $('#county_name');
      var cmbindustrylist = $('#cmbindustrylist');
      var txt_max_leadsPerMonth = $('#txt_max_leadsPerMonth');
      var city = $('#city');
      var agree = $('#agreeTerms');
      var PLAN = $('input[name="plan"]:checked').val();
      // console.log(agree.is(":checked"));

      if (countarr < 1) {
        ret_val = false;
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          subtitle: '',
          body: 'Please  select the coverage.',
          delay: 8000, // 3 seconds
          autohide: true
        })
      }

      if ($.trim(passwd1.val()) != $.trim(passwd2.val())) {
        ShowError(passwd2, "Password not matching");
        passwd2.val('');
        ret_val = false;
      } else if ($.trim(passwd1.val()) == '' && $.trim(passwd2.val()) == '') {
        ShowError(passwd2, "Please enter the passwords");
        ret_val = false;
      } else {
        HideError(passwd2);

      }


      if ($.trim(first_name.val()) == '') {
        ShowError(first_name, "Please enter your First name");
        ret_val = false;
      } else {
        HideError(first_name);
      }

      if (!(agree.is(":checked"))) {
        ret_val = false;
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          subtitle: '',
          body: 'Please  agree to the terms .',
          delay: 8000, // 3 seconds
          autohide: true
        })
      }

      if (source.length < 1) {
        //ShowError(source, "Please select your choice");
        ret_val = false;
        $(document).Toasts('create', {
          class: 'bg-warning',
          title: 'Hi,user',
          subtitle: '',
          body: 'Please select how did you come to know about us?...'
        })

      } else {
        //HideError(source);
      }

      if (rdISBI.length < 1) {
        //ShowError(source, "Please select your choice");
        ret_val = false;
        $(document).Toasts('create', {
          class: 'bg-info',
          title: 'Hi,user',
          subtitle: '',
          body: 'Please select, do you carry business insurance for your janitorial company?...'
        })

      } else {
        //HideError(source);
      }

      if (cleaningstatus.length < 1) {
        //ShowError(source, "Please select your choice");
        ret_val = false;
        $(document).Toasts('create', {
          class: 'bg-info',
          title: 'Hi,user',
          subtitle: '',
          body: 'Do you provide cleaning services during business hours?'
        })

      }


      if ($.trim(county_name_adr.val()) == '') {
        ShowError(county_name_adr, "Please select your Country");
        ret_val = false;
      } else {
        HideError(county_name_adr);
      }

      if ($.trim(city_adr.val()) == '') {
        ShowError(city_adr, "Please select your City");
        ret_val = false;
      } else {
        HideError(city_adr);
      }


      if ($.trim(state_addr.val()) == '') {
        ShowError(state_addr, "Please select your state");
        ret_val = false;
      } else {
        HideError(state_addr);
      }

      if (!validateEmail($.trim(email.val()))) {
        ShowError(email, "Please enter the valid email");
        err++;
      } else {
        HideError(email);
      }


      if ($.trim(street.val()) == '') {
        ShowError(street, "Please enter your street name");
        ret_val = false;
      } else {
        HideError(street);
      }
      if ($.trim(last_name.val()) == '') {
        ShowError(last_name, "Please enter your Last name");
        ret_val = false;
      } else {
        HideError(last_name);
      }
      if ($.trim(company_name.val()) == '') {
        ShowError(company_name, "Please enter your Company name");
        ret_val = false;
      } else {
        HideError(company_name);
      }
      if ($.trim(phone.val()) == '') {
        ShowError(phone, "Please enter your Phone");
        ret_val = false;
      } else {
        HideError(phone);
      }

      if (PLAN == 'P') {
        if ($('input[name="txt_max_leadsPerMonth"]:checked').length === 0) {
          alert("Please select the maximum number of leads per month.");
          ret_val = false; // Prevent form submission
        }

      }


      if (ret_val) {
        if ((codeflag.val()) == '0') {
          alert('Email is already regsistered !!');
          ret_val = false;
        }
      }

      if (ret_val) {
        var p_str = b64_md5(passwd2.val());
        passwd2.val(p_str);
      }

      //ret_val = true;

      return ret_val;
    }



    $('#countyid').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select Counties',
      search: true,
      selectAll: true
    });


    // $('.mul').multiselect({
    //   header: true,
    //   columns: 1,
    //   placeholder: 'Select',
    //   search: true,
    //   selectAll: true
    // });


    $('#city').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select Cities',
      search: true,
      selectAll: true
    });

    $('.in').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select industries',
      search: true,
    });


    function Update_cities() {
      let county_name = $('#county_name_adr').val();
      let state = $('#state_adr').val();
      //console.log(county_name);
      $.ajax({
        url: 'api/get_citys2.php',
        method: 'POST',
        data: {
          county_name: county_name,
          state: state

        },
        success: function(res) {
          // console.log(res);
          var dataObj = res;
          $('#city_adr').empty();

          for (i = 0; i < dataObj.length; i++) {
            var x = document.getElementById("city_adr");
            var option = document.createElement("option");
            option.text = dataObj[i].city;
            option.value = dataObj[i].city;
            x.add(option);

          }
          //$('.duallistbox').bootstrapDualListbox('refresh', true);

        },
        error: function(err) {
          //console.log(err);

        }
      });
    }

    function Add_coverage() {
      var state = $('#state2').val();
      var country = $('#countryid2').val();
      var city = $('#city').val();
      var county = $('#countyid').val();
      // console.log(state);
      // console.log(county_name);
      // console.log(city);
      if (county.length < 1) {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          subtitle: '',
          body: 'Please  select the counties .. .',
          delay: 8000, // 3 seconds
          autohide: true
        })
      } else {
        $.ajax({
          url: '_Addcoverage.php',
          method: 'POST',
          data: {
            state: state,
            country: country,
            county: county,
            city: city,
            county: county,
            mode: 'ADD'
          },
          success: function(res) {
            var myArr = res.split("~~");
            // console.log(myArr[0]);
            if (myArr[0] == 2) {
              $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                subtitle: '',
                body: 'Please delete the state from cart to make the changes in the current state',
                delay: 8000, // 3 seconds
                autohide: true
              })
            }
            $('#coverageDiv').html(myArr[1]);
            if (myArr[0] == 1) {
              $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Success',
                subtitle: '',
                body: 'You can add multiple states .',
                delay: 8000, // 3 seconds
                autohide: true
              })
              $("#addToCart_btn").text("Add More Coverage");
              // location.reload();
            }
          }
        });

      }
    }

    function validateEmail(email) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailPattern.test(email);
    }

    function remove(country, state) {
      $.ajax({
        url: '_Addcoverage.php',
        method: 'POST',
        data: {
          state: state,
          country: country,
          mode: 'REMOVE'
        },
        success: function(res) {
          //location.reload();
          // console.log();
          const myArr = res.split("~~");
          $('#coverageDiv').html(myArr[1]);
        }
      });

    }

    function showpassword1() {
      var x = document.getElementById("passwd1");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }

    }

    function showpassword2() {
      var x = document.getElementById("passwd2");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }

    }

    function getcitydropdown() {
      let state = $('#state').val();
      let county = $('#county_name').val();

      $.ajax({
        url: 'api/gethtml.php',
        method: 'POST',
        data: {
          state: state,
          county_name: county
        },
        success: function(res) {
          //  console.log(res);
          var result = res.split('~~**~~');
          var title = result[0];
          var body = result[1];

          //$('#cityselectorTITLE').html(title);
          $('#c_replace').html(body);
          // $('#city').multiselect();
          $('#city').multiselect({
            header: true,
            columns: 1,
            placeholder: 'Select Cities',
            search: true,
            selectAll: true
          });
          //$('#cityselector').modal('show');
        }
      })


      //toastr.info('Please select the Zip codes that you want to exclude');
      //$('#cityselector').modal('toggle');
      //console.log($('#zips option:not(:selected)'));

      // let str1 = '<ul>';
      // $('#state option:selected').each(function() {
      //   str1 += `<tr><td><li>${this.text}</li></td></tr>`;
      // });
      // str1 += '</ul>';
      // $('#statetable tbody').html(str1);
      // let str2 = '<ul>';
      // $('#county_name option:selected').each(function() {
      //   str2 += `<tr><td><li>${this.text}</li></td></tr>`;
      // });
      // str2 += '</ul>';
      // $('#countytable tbody').html(str2);
      // let str3 = '<ul>';
      // $('#city option:selected').each(function() {
      //   console.log(this.text);
      //   str3 += `<tr><td><li>${this.text}</li></td></tr>`;
      // });
      // str3 += '</ul>'
      // $('#citytable tbody').html(str3);
    }

    function Getcounties(stateid) {
      var data = "response=GET_COUNTIES&stateid=" + stateid;
      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#COUNTY_DIV').html(res);
        }
      });

    }

    function Getcounties2(stateid) {
      var data = "response=GET_COUNTIES_MULTIPLE&stateid=" + stateid;
      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#COUNTY_DIV').html(res);
        }
      });

    }

    function GetCity(countyid) {
      //alert(countyid);
      var countryID = $('#countryid').val();
      var stateid = $('#stateid').val();
      var data = "response=GET_CITY&countyid=" + countyid + "&countryid=" + countryID + "&stateid=" + stateid;

      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#CITY_DIV').html(res);
        }
      });

    }

    function GetCity2(stateid) {
      //alert(countyid);
      var countryID = $('#countryid').val();
      var countyid = $('#stateid').val();
      var data = "response=GET_CITY&countyid=0" + "&countryid=" + countryID + "&stateid=" + stateid;

      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#CITY_DIV').html(res);
        }
      });

    }


    function GetMultipleCities(county) {
      var counties = $('#countyid').val();
      //alert(countyid);
      //console.log(counties);
      var countryID = $('#countryid2').val();
      var countyid = $('#stateid').val();
      var data = "response=GET_CITY_MULTIPLE" + "&countryid=" + countryID + "&counties=" + counties;
      console.log(data);

      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#CITY_DIV2').html(res);
          $('.mul').multiselect({
            header: true,
            columns: 1,
            placeholder: 'Select Cities',
            search: true,
            selectAll: true
          });
        }
      });

    }

    function GetMultipleCounties(stateid) {
      //alert(countyid);
      var countryID = $('#countryid').val();
      // var countyid = $('#stateid').val();
      var data = "response=GET_COUNTIES" + "&countryid=" + countryID + "&stateid=" + stateid;

      $.ajax({
        url: ajax_url,
        method: 'POST',
        data: data,
        success: function(res) {
          $('#COUNTY_DIV').html(res);
          $('.mul').multiselect({
            header: true,
            columns: 1,
            placeholder: 'Select Counties',
            search: true,
            selectAll: true
          });
        }
      });

    }

    function GetMultipleZips(id) {

    }

    // function GetMultipleCities(county) {
    //   var counties = $('#countyid').val();
    //   //alert(countyid);
    //   //console.log(counties);
    //   var countryID = $('#countryid').val();
    //   var countyid = $('#stateid').val();
    //   var data = "response=GET_CITY_MULTIPLE" + "&countryid=" + countryID + "&counties=" + counties;
    //   console.log(data);

    //   $.ajax({
    //     url: ajax_url,
    //     method: 'POST',
    //     data: data,
    //     success: function(res) {
    //       $('#CITY_DIV').html(res);
    //       $('.mul').multiselect({
    //         header: true,
    //         columns: 1,
    //         placeholder: 'Select Cities',
    //         search: true,
    //         selectAll: true
    //       });
    //     }
    //   });

    // }


    //   $.ajax({
    //     url: ajax_url,
    //     method: 'POST',
    //     data: data,
    //     success: function(res) {
    //       $('#CITY_DIV').html(res);
    //       $('.mul').multiselect({
    //         header: true,
    //         columns: 1,
    //         placeholder: 'Select Cities',
    //         search: true,
    //         selectAll: true
    //       });
    //     }
    //   });

    // }

    function GetCountys() {
      let state = $('#state').val();
      $.ajax({
        url: '_getdropdown.php',
        method: 'POST',
        data: {
          state: state,
          type: 1

        },
        success: function(res) {
          //  console.log(res);
          var dataObj = res;
          $('#county_name').empty();
          for (i = 0; i < dataObj.length; i++) {
            var x = document.getElementById("county_name");
            var option = document.createElement("option");
            option.text = dataObj[i].county_name;
            option.value = dataObj[i].county_name;
            x.add(option);
          }
          $('#county_name').multiselect('reload');
        },
        error: function(err) {
          // console.log(err);

        }
      });
    }

    function GetCounty2(stateid) {


    }



    $(document).ready(function() {
      var done = "<?php echo $done; ?>";
      if (done != '' && done == '23') {
        $('#LBL_INFO').html(NotifyThis('The reCAPTCHA verification failed, please try again.', 'error'));
      } else if (done != '' && done == '32') {
        $('#LBL_INFO').html(NotifyThis('Something went wrong, please try again.', 'error'));
      }

      //GetCountys();
      //$('#email').on('keyup', validate);

    });





    // $(document).on('change', '#state_adr', function() {
    //   let state = $('#state_adr').val();
    //   $.ajax({
    //     url: 'api/get_countys.php',
    //     method: 'POST',
    //     data: {
    //       state: state

    //     },
    //     success: function(res) {
    //       console.log(res);
    //       var dataObj = res;
    //       $('#county_name_adr').empty();

    //       for (i = 0; i < dataObj.length; i++) {
    //         var x = document.getElementById("county_name_adr");
    //         var option = document.createElement("option");
    //         option.text = dataObj[i].county_name;
    //         option.value = dataObj[i].county_name;
    //         x.add(option);
    //       }
    //       //$('.duallistbox').bootstrapDualListbox('refresh', true);

    //     },
    //     error: function(err) {
    //       console.log(err);

    //     }
    //   });
    // });
    $(document).on('change', '#county_name_adr', function() {
      let county_name = $('#county_name_adr').val();
      let state = $('#state_adr').val();
      //console.log(county_name);
      $.ajax({
        url: 'api/get_citys2.php',
        method: 'POST',
        data: {
          county_name: county_name,
          state: state

        },
        success: function(res) {
          // console.log(res);
          var dataObj = res;
          $('#city_adr').empty();

          for (i = 0; i < dataObj.length; i++) {
            var x = document.getElementById("city_adr");
            var option = document.createElement("option");
            option.text = dataObj[i].city;
            option.value = dataObj[i].city;
            x.add(option);

          }
          //$('.duallistbox').bootstrapDualListbox('refresh', true);

        },
        error: function(err) {
          //console.log(err);

        }
      });
    });

    // $(document).on('change', '#state', function() {
    //   let state = $('#state').val();
    //   $.ajax({
    //     url: 'api/get_countys.php',
    //     method: 'POST',
    //     data: {
    //       state: state

    //     },
    //     success: function(res) {
    //       console.log(res);
    //       var dataObj = res;
    //       $('#county_name').empty();



    //       for (i = 0; i < dataObj.length; i++) {
    //         var x = document.getElementById("county_name");
    //         var option = document.createElement("option");
    //         option.text = dataObj[i].county_name;
    //         option.value = dataObj[i].county_name;
    //         x.add(option);
    //       }
    //       $('#county_name').multiselect('reload');
    //     },
    //     error: function(err) {
    //       console.log(err);

    //     }
    //   });
    // });
    // });
  </script>
  <script>
    // Enhanced Multi-Step Form Functionality
    $(document).ready(function() {
      // Initialize steps
      const steps = $('.form-step');
      const stepIndicators = $('.step');
      let currentStep = 1;

      // Show current step
      showStep(currentStep);

      // Next step button
      $('.next-step').click(function() {
        const nextStep = parseInt($(this).data('next'));
        if (validateStep(currentStep)) {
          currentStep = nextStep;
          showStep(currentStep);
          updateStepIndicators();
          scrollToTop();
        }
      });

      // Previous step button
      $('.prev-step').click(function() {
        const prevStep = parseInt($(this).data('prev'));
        currentStep = prevStep;
        showStep(currentStep);
        updateStepIndicators();
        scrollToTop();
      });

      // Click on step indicator
      $('.step').click(function() {
        const stepNumber = parseInt($(this).data('step'));
        if (stepNumber < currentStep) {
          currentStep = stepNumber;
          showStep(currentStep);
          updateStepIndicators();
          scrollToTop();
        }
      });

      // Show specific step
      function showStep(stepNumber) {
        steps.removeClass('active').hide();
        $(`#step-${stepNumber}`).addClass('active').show();
      }

      // Update step indicators
      function updateStepIndicators() {
        stepIndicators.removeClass('active completed');

        stepIndicators.each(function() {
          const stepNumber = parseInt($(this).data('step'));

          if (stepNumber < currentStep) {
            $(this).addClass('completed');
          } else if (stepNumber === currentStep) {
            $(this).addClass('active');
          }
        });
      }

      // Validate step before proceeding
      function validateStep(stepNumber) {
        let isValid = true;

        if (stepNumber === 1) {
          // Validate step 1 fields
          if ($.trim($('#first_name').val()) === '') {
            showError($('#first_name'), 'Please enter your first name');
            isValid = false;
          }

          if ($.trim($('#last_name').val()) === '') {
            showError($('#last_name'), 'Please enter your last name');
            isValid = false;
          }

          if ($.trim($('#email').val()) === '' || !validateEmail($.trim($('#email').val()))) {
            showError($('#email'), 'Please enter a valid email address');
            isValid = false;
          }

          if ($.trim($('#phone').val()) === '') {
            showError($('#phone'), 'Please enter your phone number');
            isValid = false;
          }

          if ($('input[name="source"]:checked').length === 0) {
            showToast('Please select how you heard about us', 'warning');
            isValid = false;
          }

          if ($('input[name="rdISBI"]:checked').length === 0) {
            showToast('Please select if you carry business insurance', 'warning');
            isValid = false;
          }

          if ($('input[name="cleaningstatus"]:checked').length === 0) {
            showToast('Please select if you provide cleaning services during business hours', 'warning');
            isValid = false;
          }
        } else if (stepNumber === 2) {
          // Validate step 2 fields
          if ($.trim($('#cname').val()) === '') {
            showError($('#cname'), 'Please enter your company name');
            isValid = false;
          }

          if ($.trim($('#street').val()) === '') {
            showError($('#street'), 'Please enter your street address');
            isValid = false;
          }

          if ($.trim($('#countryid').val()) === '') {
            showError($('#countryid'), 'Please select your country');
            isValid = false;
          }

          if ($.trim($('#stateid').val()) === '') {
            showError($('#stateid'), 'Please select your state');
            isValid = false;
          }

          if ($.trim($('#cityid').val()) === '') {
            showError($('#cityid'), 'Please select your city');
            isValid = false;
          }

          if ($.trim($('#passwd1').val()) === '' || $.trim($('#passwd2').val()) === '') {
            showError($('#passwd2'), 'Please enter and confirm your password');
            isValid = false;
          } else if ($.trim($('#passwd1').val()) !== $.trim($('#passwd2').val())) {
            showError($('#passwd2'), 'Passwords do not match');
            isValid = false;
          }

          if ($('input[name="rdactype"]:checked').length === 0) {
            showToast('Please select your calendar account type', 'warning');
            isValid = false;
          }
        } else if (stepNumber === 3) {
          // Validate step 3 fields
          if ($('#coverageDiv tbody tr').length <= 1) {
            showToast('Please add at least one coverage area', 'warning');
            isValid = false;
          }
        } else if (stepNumber === 4) {
          // Validate step 4 fields
          if ($('input[name="plan"]:checked').length === 0) {
            showToast('Please select a plan', 'warning');
            isValid = false;
          }

          if ($('input[name="txt_max_leadsPerMonth"]:checked').length === 0) {
            showToast('Please select maximum number of leads per month', 'warning');
            isValid = false;
          }

          if (!$('#agreeTerms').is(':checked')) {
            showToast('Please agree to the terms and conditions', 'warning');
            isValid = false;
          }
        }

        return isValid;
      }

      // Helper function to show error
      function showError(element, message) {
        const formGroup = element.closest('.form-group');
        formGroup.addClass('has-error');

        let errorElement = formGroup.find('.error-message');
        if (errorElement.length === 0) {
          errorElement = $(`<small class="text-danger error-message">${message}</small>`);
          formGroup.append(errorElement);
        } else {
          errorElement.text(message);
        }

        // Scroll to the error
        $('html, body').animate({
          scrollTop: formGroup.offset().top - 100
        }, 500);
      }

      // Helper function to show toast
      function showToast(message, type) {
        $(document).Toasts('create', {
          class: `bg-${type}`,
          title: type === 'warning' ? 'Please check' : 'Error',
          body: message,
          autohide: true,
          delay: 5000
        });
      }

      // Scroll to top of form
      function scrollToTop() {
        $('html, body').animate({
          scrollTop: $('.form-container').offset().top - 20
        }, 500);
      }

      // Email validation
      function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
      }

      // [Keep all your existing functions like Add_coverage, remove, etc.]
    });

    // [Keep all your existing JavaScript functions]
  </script>
</body>

</html>
<?php sql_close(); ?>