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
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
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
$cartArr = isset($_SESSION[COVERAGE]) ? $_SESSION[COVERAGE] : array();
//unset($_SESSION['COVERAGE']);
// DFA($_SESSION);
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
                <h3 class="text-center">Register As a Provider</h3>


              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <div class="row">
                  <div class="col-md-12">

                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" placeholder="Enter First Name" name="first_name">
                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" class="form-control" placeholder="Enter Last Name" name="last_name">
                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="cname">Company Name:</label>
                        <input type="text" class="form-control" id="cname" placeholder="Enter Company Name" name="cname">
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="phone">Phone:</label>
                        <input type="number" class="form-control" name="phone" id="phone" placeholder="Enter your Phone">
                      </div>
                      <div class="col-md-4 mb-4">
                        <span id="result"></span>
                        <label for="phone">Email:</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your Email">
                      </div>
                    </div>
                    <p for="comment" class="mt-2">Company Address (Kindly provide actual address and not a post box address )</p>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="streetADR">Please Enter your street</label>
                        <input type="text" name="street" id="street" class="form-control">
                      </div>
                      <div class="col-md-2 mb-4">
                        <label for="stateADR"> State</label>
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
                        <label for="countyADR">County</label>
                        <select name="county_name_adr" class="form-control select2" data-placeholder="Select a County" id="county_name_adr">
                          <option value="">--select--</option>

                        </select>
                      </div>
                      <div class="col-md-3 mb-4">
                        <label for="cityADR">City</label>
                        <select name="city_adr" class="form-control select2" data-placeholder="Select a City" id="city_adr">

                          <option value="">--select--</option>
                        </select>
                      </div>



                    </div>
                    <p for="comment" class="mt-2">Create New Password</p>
                    <div class="form-row">
                      <div class="col-md-4 mb-4">
                        <label for="passwd1">New Password:</label>
                        <input type="password" name="passwd1" id="passwd1" class="form-control">

                        <i class="fa fa-eye" onclick="showpassword1()" aria-hidden="true"><input id="checkbo" type="checkbox"></i>

                      </div>
                      <div class="col-md-4 mb-4">
                        <label for="passwd2">Confirm Password:</label>
                        <input type="password" name="passwd2" onclick="showpassword2();" id="passwd2" class="form-control">
                        <i class="fa fa-eye" onclick="showpassword2()" aria-hidden="true"><input id="checkbo" type="checkbox"></i>
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
                    <div class="coverage_cart mb-4 p-0" id="coverageDiv" style="max-width: 500px; max-height: 350px; overflow: scroll;">
                      <table class="table table-bordered" id="coveragetable">

                        <tr style="position: sticky;top: 0;background: #315292;color: #fff;z-index: 1;">

                          <th class="text-left">State </th>
                          <th class="text-left wp_100">Counties </th>
                          <th class="text-left wp_100">Cities <span class="text-warning">(you do not cover)</span></th>
                          <th style="width:50px;">Action</th>


                        </tr>
                        <tbody>
                          <?php
                          if (isset($_SESSION['COVERAGE'])) {
                            foreach ($_SESSION['COVERAGE'] as $key => $value) {
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
                        I agree to the <a href="#">terms</a>
                      </label>
                    </div>
                  </div>

                </div>
                <div class="row mt-4">
                  <button  type="submit" class="btn btn-primary btn-lg">Register</button>
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
    <?php include 'footer.php'; ?>
  </div>
  <!-- /.content-wrapper -->
  <?php include 'load.scripts.php'; ?>
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

    function Add_coverage() {
      var state = $('#state').val();
      var county_name = $('#county_name').val();
      var city = $('#city').val();
      console.log(state);
      console.log(county_name);
      console.log(city);
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
            console.log(myArr[1]);
            $('#coverageDiv').html(myArr[1]);
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
          console.log();
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
          console.log(res);
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
          console.log(res);
          var dataObj = res;
          $('#county_name_adr').empty();

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
          console.log(err);

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
          console.log(res);
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
          console.log(err);

        }
      });
    }
    $(document).ready(function() {
      GetCountys();
      $('#email').on('keyup', validate);
      $('#vendorFORM').submit(function() {
        err = 0;
        ret_val = true;
        var countarr = 1;

        var first_name = $('#first_name');
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
        console.log(agree.is(":checked"));

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
        } else
          HideError(passwd2);


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

        // if ($.trim(state.val()) == '') {
        //   ShowError(state, "Please enter your First name");
        //   ret_val = false;
        // } else {
        //   HideError(state);
        // }

        // if ($.trim(county_name.val()) == '') {
        //   ShowError(county_name, "Please select your County name");
        //   ret_val = false;
        // } else {
        //   HideError(county_name);
        // }

        // if ($.trim(city.val()) == '') {
        //   ShowError(city, "Please select your City");
        //   ret_val = false;
        // } else {
        //   HideError(city);
        // }

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
            console.log(err);

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