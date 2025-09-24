<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');


// ALTER TABLE `quote_master`.`service_providers`
//   ADD COLUMN `cSource` char(4) NULL DEFAULT NULL;


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
$cartArr = isset($_SESSION['COVERAGE']) ? $_SESSION['COVERAGE'] : $_SESSION['COVERAGE'] = array();
$source = '';
//unset($_SESSION['COVERAGE']);
//DFA($_SESSION);
?>
<!DOCTYPE html>
<html>

<head>
  <?php include 'load.link.php'; ?>
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
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
              <div class="card-header">
                <h3 class="text-center">Register as a Provider</h3>


              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-md-12">

                    <div class="form-row">
                      <div class="form-group">
                        <label for="">How did you come to know about us? <span class="text-danger">*</span></label><br>
                        <?php echo FillRadios($source, 'source', $SOURCE, '', 'form-control'); ?>

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
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your Email">
                      </div>
                    </div>
                    <p for="comment" class="mt-2">Company Address (Kindly provide actual address and not a post box address)</p>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="streetADR">Please Enter your street<span class="text-danger">*</span></label>
                        <input type="text" name="street" id="street" class="form-control">
                      </div>
                      <div class="col-md-2 mb-4">
                        <label for="stateADR"> State<span class="text-danger">*</span></label>
                        <select name="state_adr" onchange="Getcounties();" class="form-control select2" data-placeholder="Select a State" id="state_adr">
                          <option value="">--select--</option>

                          <?php
                          $q = "SELECT DISTINCT(state) FROM `areas` order by state";
                          $r = sql_query($q);
                          while ($a = sql_fetch_assoc($r)) {
                            echo '<option value="' . $a['state'] . '">' . $a['state'] . '</option>';
                          }

                          ?>

                        </select>
                      </div>
                      <div class="col-md-3 mb-4">
                        <label for="countyADR">County<span class="text-danger">*</span></label>
                        <select name="county_name_adr" onchange="Update_cities();" class="form-control select2" data-placeholder="Select a County" id="county_name_adr">
                          <option value="">--select--</option>

                        </select>
                      </div>
                      <div class="col-md-3 mb-4">
                        <label for="cityADR">City<span class="text-danger">*</span></label>
                        <select name="city_adr" class="form-control select2" data-placeholder="Select a City" id="city_adr">

                          <option value="">--select--</option>
                        </select>
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


                  </div>
                </div>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->

            <div class="card mb-4">
              <div class="card-header">
                <h3 class="text-center">Select The Areas To Receive Your Leads</h3>


              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-7 mb-4">

                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label>Please select the state from the dropdown. </label>
                        <select name="state" onchange="GetCountys();" class="form-control select2" data-placeholder="Select a State" id="state">

                          <?php
                          $q = "SELECT DISTINCT(state) FROM `areas` order by state";
                          $r = sql_query($q);
                          while ($a = sql_fetch_assoc($r)) {
                            echo '<option value="' . $a['state'] . '">' . $a['state'] . '</option>';
                          }

                          ?>

                        </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label>Click on counties you want to include in your coverage </label>
                        <select name="county_name[]" id="county_name" onchange="getcitydropdown();" class="form-control " multiple="multiple" data-placeholder="Select a County">
                        </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-10 mb-4">
                        <label for="">In the County or Counties you selected, are there any cities you <strong style="text-decoration: underline;">DO NOT</strong> cover?</label>
                        <div id="c_replace">
                          <select name="city[]" class="form-control " multiple="multiple" data-placeholder="Select a City" id="city">
                          </select>

                        </div>
                      </div>
                    </div>
                    <button type="button" onclick="Add_coverage();" id="addToCart_btn" class="btn btn-secondary btn-md">Add Coverage</button>

                  </div>
                  <div class="col-lg-5">
                    <label>Defined Coverage</label>
                    <div class="coverage_cart mb-4 p-0" id="coverageDiv">
                      <table class="table table-bordered" id="coveragetable">

                        <tr style="position: sticky;top: 0;background: #315292;color: #fff;z-index: 1;">

                          <th class="text-left">State </th>
                          <th class="text-left wp_100">Counties </th>
                          <th class="text-left wp_100">Cities <span class="text-warning">(you do not cover)</span></th>
                          <th style="width:50px;">Action</th>


                        </tr>
                        <tbody>
                          <?php
                          if (isset($_SESSION['COVERAGE']->cart)) {
                            foreach ($_SESSION['COVERAGE']->cart as $key => $value) {
                          ?>
                              <tr>
                                <td><?php echo $key; ?></td>
                                <td style="text-align:left; max-width: 50px;"><?php echo (implode(",  ", $value['county'])); ?></td>
                                <td style="text-align:left; max-width: 50px;"><?php echo (isset($value['city']) && $value['city'] != '') ? (implode(",  ", $value['city'])) : ''; ?></td>
                                <td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove('<?php echo $key; ?>')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>
                              </tr>

                          <?php    }
                          } else
                            echo '<td>No coverage added</td>';
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
                  <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
                <br>
                <a href="plogin.php" class="text-center">I already have a membership? Login</a>
              </div>
            </div>

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
                <strong>Lead Fee:</strong> Service Provider hereby agrees that it will be required to pay
                Quote Masters a flat fee (“Fee”) for each Lead (defined herein below) purchased. The Fee shall be determined by how many appointments are scheduled. A fee of $125.00 will be due to Quote
                Masters if the Consumer schedules recurring services. A fee of $85.00 will be due to Quote
                Masters if the Consumer schedules a one-time appointment with Service Provider. The Fee shall
                be paid to Quote Masters on or before the date of the first appointment between Consumer and
                Service Provider. The Fee is non-refundable unless Consumer cancels any scheduled
                appointment with Service Provider.
              </li><br>
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
                <!-- Michael J. Chartrand <br>
                6621 Tula Lane <br>
                Lakeland, FL 33809 <br> -->
                <br><strong>As to Service Provider:</strong>
              </li><br>
              <li>
                <strong>Governing Law:</strong> This Agreement shall be construed and enforced in
                accordance with, and governed by, the laws of the State of Florida. Exclusive venue for any
                litigation arising under or in connection with this Agreement shall be Polk County, Florida, and
                the parties hereby waive their rights to assert venue in any other jurisdiction.
              </li><br>
              <li><strong>Headings:</strong> The headings of the paragraphs of the Agreement are inserted for convenience only and shall not be deemed to constitute a party hereof.</li>
            </ol>
            <p>IN WITNESS WHEREOF, the parties have executed this Agreement indicated below.</p>
            <div class="col-md-6 col-12">
              <p><strong>QUOTE MASTERS LLC</strong>
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
  <script>
    const validateEmail = (email) => {
      return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    };

    const validate = () => {
      const $result = $('#result');
      const email = $('#email').val();
      $result.text('');

      if (validateEmail(email)) {
        $result.text(email + ' is valid 😎');
        $result.css('color', 'green');
      } else {
        $result.text(email + ' is not valid 😧');
        $result.css('color', 'red');
      }
      return false;
    }

    $('#county_name').multiselect({
      header: true,
      columns: 1,
      placeholder: 'Select Counties',
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
      var state = $('#state').val();
      var county_name = $('#county_name').val();
      var city = $('#city').val();
      // console.log(state);
      // console.log(county_name);
      // console.log(city);
      if (county_name.length < 1) {
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
            county: county_name,
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

    function remove(state) {
      $.ajax({
        url: '_Addcoverage.php',
        method: 'POST',
        data: {
          state: state,
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

    function Getcounties() {
      let state = $('#state_adr').val();
      $.ajax({
        url: 'api/get_countys.php',
        method: 'POST',
        data: {
          state: state,
          type: 2

        },
        success: function(res) {
          //console.log(res);
          var dataObj = res;
          $('#county_name_adr').empty();
          var x = document.getElementById("county_name_adr");
          var option = document.createElement("option");
          option.text = '--select--';
          option.value = "";
          x.add(option);

          for (i = 0; i < dataObj.length; i++) {
            var x = document.getElementById("county_name_adr");
            var option = document.createElement("option");
            option.text = dataObj[i].county_name;
            option.value = dataObj[i].county_name;
            x.add(option);
          }
          //$('.duallistbox').bootstrapDualListbox('refresh', true);

        },
        error: function(err) {
          // console.log(err);

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
    $(document).ready(function() {

      GetCountys();
      $('#email').on('keyup', validate);
      $('#vendorFORM').submit(function() {
        var err = 0;
        var ret_val = true;
        var countarr = 1;

        var first_name = $('#first_name');
        var source = $('input[name="source"]:checked');
        var last_name = $('#last_name');
        var company_name = $('#cname');
        var phone = $('#phone');
        var street = $('#street');
        var state_addr = $('#state_adr');
        var county_name_adr = $('#county_name_adr');
        var city_adr = $('#city_adr');
        var passwd1 = $('#passwd1');
        var passwd2 = $('#passwd2');
        var state = $('#state');
        var county_name = $('#county_name');
        var city = $('#city');
        var agree = $('#agreeTerms');
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


        if ($.trim(county_name_adr.val()) == '') {
          ShowError(county_name_adr, "Please select your County");
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
        if ($.trim(state_addr.val()) == '') {
          ShowError(state_addr, "Please select your state");
          ret_val = false;
        } else {
          HideError(state_addr);
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

        if (ret_val) {
          var p_str = b64_md5(passwd2.val());
          passwd2.val(p_str);
        }

        return ret_val;
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
    });
  </script>
</body>

</html>