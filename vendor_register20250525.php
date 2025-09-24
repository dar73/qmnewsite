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
                        <a class="dropdown-item" href="clogin.php">Customer</a>
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

//DFA($cartArr);

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

$COUNTRIES = GetXArrFromYID("SELECT country_id, country_name FROM countries WHERE 1 ", '3');
$STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 ", '3');
$CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE 1 ", '3');
?>
<!DOCTYPE html>
<html>

<head>
  <?php include 'load.link.php'; ?>
  <style>
    /* Custom styles for the form */
    .form-title {
      font-weight: bold;
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 20px;
    }

    .required {
      color: #ff4c4c;
    }

    /* Custom Premium Card Styling */
    .custom-premium-card {
      background-color: #e5e4e2;
      color: white;
      border: none;
      border-radius: 10px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .custom-premium-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Custom Basic Card Styling */
    .custom-basic-card {
      background-color: #f8f9fa;
      color: #333;
      border: none;
      border-radius: 10px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .custom-basic-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Custom Card Title Styling */
    .custom-card-title {
      font-size: 1.25rem;
      font-weight: bold;
    }

    /* Custom Card Text Styling */
    .custom-card-text {
      font-size: 1rem;
      margin-bottom: 15px;
    }

    /* Custom Details Link Styling */
    .custom-details-link {
      display: inline-block;
      margin-top: 15px;
      color: white;
      background-color: #333;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .custom-details-link:hover {
      background-color: #ff4c4c;
    }

    /* Custom Form Check Label Styling */
    .custom-form-check-label {
      margin-left: 5px;
      cursor: pointer;
    }

    /* Styling for selected radio button label */
    .form-check-input:checked+.custom-form-check-label {
      font-weight: bold;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">
    <!-- Navbar -->
    <!-- Navbar -->
    <?php include 'header.php'; ?>
    <!-- /.navbar -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <form action="register.php" id="vendorFORM" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="code_flag" id="code_flag" value="<?php echo $code_flag; ?>">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="text-center">Register as a Provider</h3>


              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-md-12">
                    <div id="LBL_INFO"></div>

                    <div class="form-row">
                      <div class="form-group">
                        <label for="">How did you come to know about us? <span class="text-danger">*</span></label><br>
                        <?php echo FillRadios($source, 'source', $SOURCE, '', 'form-control'); ?>

                      </div>
                    </div>
                    <hr>
                    <div class="form-row">
                      <div class="form-group">
                        <label for="">Do you carry business insurance for your janitorial company?<span class="text-danger">*</span></label><br>
                        <?php echo FillRadios($rdISBI, 'rdISBI', $YES_ARR, '', 'form-control'); ?>

                      </div>
                    </div>
                    <hr>
                    <div class="form-row">
                      <div class="form-group">
                        <label for="">Do you provide cleaning services during business hours?<span class="text-danger">*</span></label><br>
                        <?php echo FillRadios($cleaningstatus, 'cleaningstatus', $YES_ARR, '', 'form-control'); ?>

                      </div>
                    </div>
                    <hr>

                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="first_name">First Name:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="first_name" placeholder="Enter First Name" name="first_name">
                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="last_name">Last Name:<span class="text-danger">*</span></label>
                        <input type="text" id="last_name" class="form-control" placeholder="Enter Last Name" name="last_name">
                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="cname">Company Name:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cname" placeholder="Enter Company Name" name="cname">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="phone">Phone:<span class="text-danger">*</span></label>
                        <input type="text" onkeypress="return numbersonly(event);" class="form-control" name="phone" id="phone" placeholder="Enter your Phone">
                      </div>
                      <div class="col-md-4 mb-4">
                        <span id="result"></span>
                        <label for="phone">Email:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="email" onKeyUp="IsCodeUnique('0', this, 'USER_EMAIL');" onBlur="IsCodeUnique('0', this, 'USER_EMAIL');" autocomplete="off" id="email" placeholder="Enter your Email">
                        <span id="EMAIL_EXISTS" style="color: red;" class="em"></span>
                      </div>
                    </div>
                    <p for="comment" class="mt-2">Company Address (Kindly provide actual address and not a post box address)</p>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="streetADR">Please Enter your street<span class="text-danger">*</span></label>
                        <input type="text" name="street" id="street" class="form-control">
                      </div>
                      <div class="col-md-2 mb-4">
                        <label for="stateADR"> Country<span class="text-danger">*</span></label>
                        <?php echo FillCombo2022('countryid', '', $COUNTRIES, 'Country', 'form-control', 'GetStates(this.value);'); ?>
                      </div>
                      <div class="col-md-2 mb-4">
                        <label for="stateADR"> State<span class="text-danger">*</span></label>
                        <span id="STATE_DIV">
                          <?php echo FillCombo2022('stateid', '', array(), 'state', 'form-control', 'GetCity2(this.value);'); ?>
                        </span>
                      </div>

                      <div class="col-md-3 mb-4">
                        <label for="cityADR">City<span class="text-danger">*</span></label>
                        <span id="CITY_DIV">
                          <?php echo FillCombo2022('cityid', '', array(), 'city', 'form-control', ''); ?>
                        </span>
                      </div>



                    </div>
                    <p for="comment" class="mt-2">Create New Password</p>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="passwd1">New Password:<span class="text-danger">*</span></label>
                        <input type="password" name="passwd1" id="passwd1" class="form-control">

                        <i class="fa fa-eye" onclick="showpassword1()" aria-hidden="true"></i>

                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="passwd2">Confirm Password:<span class="text-danger">*</span></label>
                        <input type="password" name="passwd2" id="passwd2" class="form-control">
                        <i class="fa fa-eye" onclick="showpassword2()" aria-hidden="true"></i>
                      </div>
                    </div>
                    <hr>
                    <p for="comment" class="form-title">Select Your Plan <span class="required">*</span></p>
                    <div class="form-row">
                      <div class="col-md-12 mb-4">
                        <div class="card custom-premium-card">
                          <div class="card-body">
                            <h5 class="custom-card-title">Platinum Plan ( No additional cost - Currently Free )</h5>
                            <p class="custom-card-text">This plan includes all features and priority support.</p>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" name="plan" id="plan_premium" value="P" <?php echo $PLATINUM_cHECK; ?>>
                              <label class="form-check-label custom-form-check-label" for="plan_premium">Select Platinum Plan</label>
                            </div>
                            <a href="platinum_plan.php" target="_blank" rel="noopener noreferrer" class="custom-details-link">More Details</a>


                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="">List Industries <span class="text-danger">You DO NOT Service</span> (ex. Restaurants, Auto Repair, Learning Centers - change to Restaurants, Auto Repair, theatres)</label>
                          <!-- <input type="text" name="txtbox1" id="txtbox1" class="form-control"> -->
                          <?php echo FillMultiCombo('', 'cmbindustrylist', 'COMBO', 'Y', $INDUSTRY_EXC, '', 'in'); ?>
                        </div>
                      </div>


                      <hr>
                      <label for=""><span class="text-success"> <strong>Note : This is not a guarantee of volume</strong> </span></label>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="">Maximum Number of Leads per month: <span class="text-danger">*</span></label>
                          <!-- <input type="text" name="txt_max_leadsPerweek" onkeypress="return numbersonly(event);" id="txt_max_leadsPerweek" class="form-control"> -->
                          <?php echo FillRadios('', 'txt_max_leadsPerMonth', $LEADS_PER_WEEK, '', 'form-control'); ?>
                        </div>
                      </div>


                      <hr>

                      <!-- <div class="col-md-12 mb-4">
                        <div class="form-group">
                          <label for="">Maximum Leads per Month</label>
                          <?php //echo FillRadios('', 'txt_max_leadsPerMonth', $LEADS_PER_MONTH, '', 'form-control'); 
                          ?>
                        </div>
                      </div>
                      <hr> -->
                      <!-- <div class="col-md-12 mb-4">
                        <div class="card custom-basic-card">
                          <div class="card-body">
                            <h5 class="custom-card-title">Continue with Quote Masters Basic</h5>
                            <p class="custom-card-text">This plan includes basic features.</p>
                            <div class="form-check">
                              <input type="radio" class="form-check-input" name="plan" id="plan_standard" value="S" <?php echo $BASIC_CHECK; ?>>
                              <label class="form-check-label custom-form-check-label" for="plan_standard">Select Basic Plan</label>
                            </div>
                          </div>
                        </div>
                      </div> -->
                    </div>

                    <!-- <p class="form-title"> </p> -->




                  </div>


                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card mb-4">
              <div class="card-header">
                <h3 class="text-center">Select The Areas To Receive Your Leads</h3>


              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-7 mb-4">
                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label for="stateADR"> Country<span class="text-danger">*</span></label>
                        <?php echo FillCombo2022('countryid2', '', $COUNTRIES, 'Country', 'form-control', 'GetStates2(this.value);'); ?>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label>Please select the state from the dropdown. </label>
                        <span id="STATE_DIV2">
                          <?php echo FillCombo2022('state2', '', array(), 'state', 'form-control mul', 'GetMultipleCities(this.value);'); ?>
                        </span>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label for="">In the State you selected, which cities you cover?</label>
                        <span id="CITY_DIV2">
                          <?php echo FillComboMultiSelect('city', '', array(), 'cities'); ?>

                        </span>
                      </div>
                    </div>
                    <button type="button" onclick="Add_coverage();" id="addToCart_btn" class="btn btn-secondary btn-md">Add Coverage</button>

                  </div>
                  <div class="col-lg-5">
                    <label>Defined Coverage</label>
                    <div class="coverage_cart mb-4 p-0" id="coverageDiv">
                      <table class="table table-bordered" id="coveragetable">

                        <tr style="position: sticky;top: 0;background: #315292;color: #fff;z-index: 1;">
                          <th class="text-left">Country</th>
                          <th class="text-left">State </th>
                          <!-- <th class="text-left wp_100">Counties </th> -->
                          <th class="text-left wp_100">Cities <span class="text-warning">(you cover)</span></th>
                          <th style="width:50px;">Action</th>


                        </tr>
                        <tbody>
                          <?php
                          //DFA($_SESSION['COVERAGE']);
                          // Updated display logic for coverage table
                          if (isset($_SESSION['COVERAGE'])) {
                            foreach ($_SESSION['COVERAGE'] as $countryKey => $states) {
                              foreach ($states as $stateKey => $value) {
                                echo '<tr>';
                                echo '<td>' . $COUNTRIES[$countryKey] . '</td>'; // Display country name
                                echo '<td>' . $STATE_ARR[$stateKey] . '</td>'; // Display state name
                                echo '<td style="text-align:left; max-width: 50px;">';
                                if (isset($value['city']) && $value['city'] != '') {
                                  $cityNames = array_map(function ($cityId) use ($CITY_ARR) {
                                    return $CITY_ARR[$cityId];
                                  }, $value['city']);
                                  echo implode(",  ", $cityNames); // Display city names
                                }
                                echo '</td>';
                                echo '<td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove(\'' . $countryKey . '\', \'' . $stateKey . '\')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>';
                                echo '</tr>';
                              }
                            }
                          } else {
                            echo '<td>No coverage added</td>';
                          }

                          ?>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-8 mb-4">
                    <div class="icheck-primary">
                      <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                      <label for="agreeTerms" class="text-dark">
                        I agree to the <a href="javascript:void()" data-toggle="modal" data-target="#SP-terms-modal">terms</a>
                      </label>
                    </div>
                  </div>

                </div>
                <div class="row mt-4">
                  <!-- <button type="submit" class="btn btn-primary btn-lg g-recaptcha">Register</button> -->
                  <button class="btn btn-primary btn-lg g-recaptcha" data-sitekey="6Lf_pJInAAAAANtUgwkJ4V3unOz3SCzP-NENNz-M" data-callback='onSubmit' data-action='submit'>Submit</button>

                </div>
                <br>
                <a href="plogin.php" class="text-center">Already registered? Login</a>
              </div>
            </div>
        </div>
        <!-- /.card -->


        <!-- /.row -->
        </form>
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content -->
  <!-- The Modal -->
  <div class="modal fade" id="SP-terms-modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">SERVICE PROVIDER AGREEMENT</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <p>This Service Agreement (the “Agreement”) is entered into by and between QUOTE
            MASTERS LLC, a Florida limited liability company (“Quote Masters”), and Service Provider.</p>
          <p>Quote Masters is an online company which provides a quick and easy way for Consumers
            to find a service provider who is interested in accepting a request for janitorial services. The goal
            is to connect the Consumer and Service Provider, who will quickly communicate with the
            Consumer their availability and acceptance of the project should the Consumer select them to
            perform the work.</p>
          <ol>
            <li>
              <strong>Role of Quote Masters:</strong> Quote Masters sole responsibility to Service
              Provider is to connect Service Provider and a consumer for the provision of services. Quote
              Masters makes no representation as to the consumer. Service Provider is solely responsible for
              all communications and negotiations between Service Provider and consumer, as Quote Masters
              acts only as a facilitator of the introductions between Service Provider and consumer. The
              provision of any services by the Service Provider will be subject to an entirely separate contract
              and/or agreement. If there are any issues with the performance of those services by Service
              Provider and/or consumer, then any claims and/or rights Service Provider may have will be
              solely against the consumer and not against Quote Masters. Quote Masters bears no
              responsibility with respect to the Consumer, work/services, or any related transaction.
            </li><br>
            <li>
              <strong>Lead Generation:</strong> Below is a description of how the Lead Generation will work:
              <ul>
                <li>A consumer will submit a proposed for work or janitorial related services
                  (“Services”) through Quote Masters’ website.</li>
                <li>Quote Masters will compile basic information, including Consumer’s
                  approximate location and the description of the Consumer’s Project (the
                  “Lead”), and send the Lead via email or Quote Masters’ website to a group of
                  eligible service providers that perform the type of services described in the
                  Lead in the area in which the Consumer is located. The number of service
                  providers to which Quote Masters will send the Lead may vary, in Quote
                  Masters’ sole discretion, depending on a number of factors.</li>
                <li>Service Provider will then purchase the Lead from Quote Masters based upon
                  the fee schedule outlined below in Paragraph 3 below (Lead Fee). The
                  payment for the Lead is for the scheduling of appointments with Consumer.</li>
                <li>The Lead will remain active and available for purchase for twenty-four (24) hours.</li>
              </ul>
            </li><br>
            <li>
              <strong>Lead Fee:</strong> Service Provider hereby agrees that it will be required to pay Quote Masters a flat fee (“Fee”) for each Lead (defined herein below) purchased. The Fee shall be determined by how many appointments are scheduled. A fee of $125.00 will be due to Quote Masters if the Consumer schedules recurring services for frequencies from 1 x per week up to frequencies of 7x per week. A fee of $85.00 will be due to Quote Masters if the Consumer schedules an appointment for 1x or 2x per month with Service Provider. The Fee shall be paid to Quote Masters on or before the date of the first appointment between Consumer and Service Provider.
            </li><br>
            <!-- <p style="font-style: italic;"> -->
            <ul>
              <li>The Fee is non-refundable unless Consumer cancels any scheduled appointment with Service Provider.</li>
              <li>Also, if customer requests for a reschedule and offers a different date and time than what was previously requested and accepted by the Service Provider, and if the Service Provider is not able to accommodate the new suggested date and time, the Service Provider will be offered a credit for the payment made for the lead.</li>
              <li>If you attempt to contact the customer before the scheduled meeting you will not receive credit no matter the outcome of your contact.</li>
              <li>Finally, if an “Act of God” such as earthquake, tornado, hurricane or any natural disaster causes appointment to be cancelled and a new meeting time is found by the consumer, but the service provider is unable to attend then a credit will be awarded.</li>

            </ul>

            <!-- </p> -->
            <li>
              <strong>Eligibility Requirements:</strong> Service Provider hereby represents, warrants, covenants, and agrees that, at the time of Lead Generation, it:
              <ul>
                <li>Possesses all applicable state and local licensing, registration, insurance,
                  bonding, or other trade requirements to provide the work and/or services as
                  described in the Lead;</li>
                <li>Is willing and able to complete the work and/or services described in the Lead to Consumer’s satisfaction at the Consumer’s location;</li>
                <li>Will abide by all applicable federal, state, or local laws, rules, and regulations;</li>
                <li>Will maintain a completed Form W-9.</li>
              </ul>
            </li><br>
            <li>
              <strong>Provision of Services:</strong> Service Provider agrees to, at all times, perform the
              services obtained through the Lead Generation in a good and workmanlike manner, consistent
              with the best practices and highest level of service available in the relevant industry, and shall be
              solely and independently responsible for such performance. Service Provider shall commence
              performance of the work and/or services for a Consumer within the time frame agreed to
              between Service Provider and the Consumer. Notwithstanding the foregoing, all services
              performed for a Consumer shall be performed pursuant to a written contract between Service
              Provider and Consumer.
            </li><br>
            <li>
              <strong>Publication and Distribution of Content:</strong> Quote Masters does not guarantee
              the accuracy, integrity, quality or appropriateness of any content transmitted to or through its
              website. Service Provider acknowledges that Quote Masters simply acts as a passive conduit and
              an interactive computer service provider for the publication and distribution of content posted by
              Service Provider or Consumer. Service Provider understands that all content posted on,
              transmitted through, or linked through Quote Masters’ website, are the sole responsibility of the
              person from whom such content originated. Service Provider further acknowledges that Quote
              Masters has no obligation to screen, preview, monitor or approve any content published by
              Service Provider, Consumer, or third party.
            </li><br>
            <li>
              <strong>Representations by Service Provider:</strong> Service Provider hereby represents
              and warrants to Quote Masters that: (a) it has full power, authority, and legal capacity to execute
              and deliver this Agreement; (b) it is legally and properly licensed to and possesses all requisite
              licenses and permits to provide the work and/or services described in the Lead; and (c) none of its trademarks, service-marks, logo or other marks used in advertisements infringe or violate any
              other person’s or entity’s intellectual property rights.
            </li><br>
            <li>
              <strong>Indemnification by Service Provider:</strong> Service Provider hereby agrees to
              indemnify, defend and hold harmless Quote Masters and its respective directors, managers,
              officers, stockholders, employees, agents, and insurers from and against any and all claims,
              demands, actions, losses, expenses, damages, liabilities, costs (including, without limitation,
              interest, penalties and attorneys’ fees) and/or judgments incurred or suffered by any of the
              indemnitees that result from or arise out of, directly or indirectly, (i) any breach of any
              representation and warranty made by Service Provider in this Agreement; (ii) any breach by
              Service Provider of any covenant or agreement under this Agreement; (iii) the failure to perform
              services for any Consumer of Quote Masters or any other persons; (iv) failure or refusal to honor
              any quote made to a Consumer; or (v) any negligence or willful misconduct by Service Provider.
            </li><br>
            <li>
              <strong>Quote Masters’ Limitation of Liability:</strong> IN NO EVENT SHALL QUOTE
              MASTERS BE LIABLE TO YOU FOR LOSS OF PROFITS, LOSS OF BUSINESS
              OPPORTUNITY, INDIRECT DAMAGES, PUNITIVE DAMAGES, OR CONSEQUENTIAL
              DAMAGES OR SPECIAL LOSSES, WHETHER BASED UPON A CLAIM FOR BREACH
              OF WARRANTY, CONTRACT, TORT OR ANY OTHER LEGAL OR EQUITABLE CLAIM
              RELATING TO THIS AGREEMENT, THE RELEVANT GOODS OR SERVICES OR
              PERFORMANCE HEREUNDER.
            </li><br>
            <li>
              <strong>Relationship of Parties:</strong> Subject to the terms of this Agreement, Service
              Provider shall be solely responsible for determining the manner and method by which it shall
              perform the work and/or services, and the setting and ultimate collection of its compensation that
              it charges a Consumer for the services, subject to the terms and conditions of its service contract
              with the Consumer. Quote Masters is not a general contractor, provider of services, or merchant
              of record and is acting solely in its capacity as a system administrator for Service Provider and
              Consumer for the purpose of enabling superior service and for marketing and advertising the
              services on Service Provider’s behalf. Nothing contained in this Agreement shall be deemed to
              constitute either party a partner, joint venturer or employee of the other party for any purpose.
            </li><br>
            <li>
              <strong>Confidentiality:</strong> Service Provider agrees that the terms and conditions of
              this Agreement (“Confidential Information”) shall be held in strict confidence, for the mutual
              benefit of Service Provider and Quote Masters, and Service Provider shall not disclose any
              Confidential Information without the prior written consent of Quote Masters. Notwithstanding
              the foregoing, Service Provider may disclose Confidential Information only to the extent strictly
              necessary to comply with any order of a court of competent jurisdiction or as may otherwise
              required by applicable law.
            </li><br>
            <li>
              <strong>Remedies:</strong> Service Provider agrees that the Confidential Information is
              important, material, confidential and gravely affects the effective and successful conduct of
              Quote Masters’ business and affects its value, reputation and goodwill. If Service Provider,
              including its employees and/or agents, should breach any provision of this Agreement, Quote
              Masters shall be entitled to obtain temporary and permanent injunctions, specific performances,
              costs, and reasonable attorney’s fees at all levels, including but not limited to appeals. Service
              Provider agrees that if it breaches any provision of this Agreement, it shall be conclusively
              presumed that irreparable injury would result to Quote Masters and there would be no adequate
              remedy at law. Notwithstanding the foregoing, this Agreement shall not limit the rights and
              remedies that Quote Masters otherwise has by law, equity or statute, including an action for
              damages, in which the prevailing party thereto shall be entitled to recover its reasonable
              attorney’s fees at all levels, including but not limited to appeals.
            </li><br>
            <li>
              <strong>Assignment:</strong> This Agreement may not be assigned or otherwise transferred without prior written consent of Quote Masters.
            </li><br>
            <li><strong>Severability:</strong> If a court finds any provision of this Agreement invalid or
              unenforceable, the remainder of this Agreement shall be interpreted so as best to effect the intent
              of the parties.</li><br>
            <li>
              <strong>Integration:</strong> This Agreement expresses the complete understanding of the
              parties with respect to the subject matter and supersedes all prior proposals, agreements,
              representations, and understandings. This Agreement may not be amended except in a writing
              signed by both parties.
            </li><br>
            <li>
              <strong>Waiver:</strong> The failure by any party to insist upon or enforce any of its rights
              shall not constitute a waiver thereof by the party; and nothing shall constitute waiver of the
              party’s right to insist upon strict compliance with the provisions hereof.
            </li><br>
            <li><strong>Provisions Remaining in Effect:</strong> This Agreement and the terms herein survive any termination of the Agreement.</li><br>
            <li><strong>Binding Effect:</strong> This Agreement and the rights and obligations created
              hereunder shall be binding upon and inure solely to the benefit of the parties and their respective
              successors and permitted assigns, and no other person shall acquire or have any right under or by
              virtue of this Agreement.</li><br>
            <li><strong>Effective Date:</strong> The Effective Date of this Agreement shall be the date of execution by the last party whose signature is required hereto.</li><br>
            <li><strong>Counterpart Execution:</strong> This Agreement may be executed in two or more
              counterparts, each of which shall be deemed an original, but all of which together shall constitute
              one and the same instrument.</li><br>
            <li>
              <strong>Notices:</strong> Any notice expressly provided for or permitted under this
              Agreement shall be in writing, and shall be given personally or by U.S. certified mail (return
              receipt requested), and, if mailed, shall be deemed received by the party to be notified five (5)
              days after being deposited with the United States Postal Service, at its address set forth below:
              <br><strong>As to Quote Masters:</strong><br>
              <!-- Michael J. Chartrand <br> -->
              8875 Hidden River Parkway, <br>
              Tampa FL 33637 <br>
              <br><strong>As to Service Provider:</strong>
            </li><br>
            ------------------------------------<br>
            ------------------------------------<br>

            <li>
              <strong>Governing Law:</strong> This Agreement shall be construed and enforced in
              accordance with, and governed by, the laws of the State of Florida. Exclusive venue for any
              litigation arising under or in connection with this Agreement shall be Polk County, Florida, and
              the parties hereby waive their rights to assert venue in any other jurisdiction.
            </li><br>
            <li><strong>Headings:</strong> The headings of the paragraphs of the Agreement are inserted for convenience only and shall not be deemed to constitute a party hereof.</li>
            <li><strong>Refunds / Credits:</strong> Refunds / Credits can take from 5 to 7 business days.</li>
          </ol>
          <p>IN WITNESS WHEREOF, the parties have executed this Agreement indicated below.</p>
          <div class="col-md-6 col-12">
            <p><strong>QUOTE MASTERS LLC</strong>, a Florida limited liability company</p>
          </div>
          <div class="col-md-6 col-12">
            <strong>SERVICE PROVIDER</strong>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
  </div>
  <!-- /.content-wrapper -->
  <?php include 'load.scripts.php'; ?>
  <script type="text/javascript" src="scripts/ajax.js"></script>
  <script type="text/javascript" src="scripts/common.js"></script>
  <script type="text/javascript" src="scripts/md5.js"></script>
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <!-- <script src="https://www.google.com/recaptcha/api.js?render=6Lf_pJInAAAAANtUgwkJ4V3unOz3SCzP-NENNz-M"></script> -->

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



    $('#county_name').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select Counties',
      search: true,
      selectAll: true
    });


    $('.mul').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select',
      search: true,
      selectAll: true
    });


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
      // console.log(state);
      // console.log(county_name);
      // console.log(city);
      if (city.length < 1) {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          subtitle: '',
          body: 'Please  select the cities .. .',
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
            city: city,
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


    function GetMultipleCities(stateid) {
      //alert(countyid);
      var countryID = $('#countryid2').val();
      var countyid = $('#stateid').val();
      var data = "response=GET_CITY_MULTIPLE" + "&countryid=" + countryID + "&stateid=" + stateid;

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

      GetCountys();
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
</body>

</html>
<?php sql_close(); ?>