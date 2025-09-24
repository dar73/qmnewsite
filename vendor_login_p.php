
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Provider Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="css/prati.css">
  <style>
    .logo_img {
    max-width: 100px;
    height: auto;
}
.btn {
    border: 1px solid #315292;
    background: #315292;
    color: #fff;
}
.navbar-light .navbar-nav .nav-link {
    color: #fff;
}
.review img {
    max-width: 100%;
}
img.c_avatar {
    width: 100%;
    border-radius: 20px;
}
img.c_rating {
    max-width: 50%;
}
 .card {
    box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
    border-radius: 20px;
}
.form-control {
    border: 1px solid #315292;
}
span.disabled {
    font-weight: 400;
    color: #4e4e4edb;
}
.ag_TC .form-check-label {
    margin-bottom: 0;
    color: #315292;
}
  </style>
</head>
<body class="hold-transition">
<div class="wrapper">
  <!-- Navbar -->
   <!-- Navbar -->
   <nav class="navbar navbar-expand-md navbar-light navbar-white border-bottom">
    <div class="container">
      <a href="index3.html" class="navbar-brand">
        <img class="logo_img" src="images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
      </a>
      
      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item m-2">
            <button class="btn nav-link px-2 float-right"><i class="fa fa-briefcase" aria-hidden="true"></i>  Join as Provider</button>
            <!-- <a href="index3.html" class="nav-link">Home</a> -->
          </li>
          <li class="nav-item m-2">
            <button class="btn nav-link px-2 float-right">Check Your Quotes</button>
            <!-- <a href="index3.html" class="nav-link">Home</a> -->
          </li>
          
          
        </ul>

       
       
      </div>

      <!-- Right navbar links -->
      
    </div>
  </nav>
  <!-- /.navbar -->


  <!-- Content Wrapper. Contains page content -->
  <div class="">
    <!-- Main content -->
    <section class="content my-5">
      <div class="container-fluid">
        <div class="row  justify-content-center">
          <div class="col-md-8">
          <form role="form">
          <div class="card card-warning">
              <div class="card-header">
                <h4 class="text-center">Provider Registration</h4>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="First Name">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Last Name">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- textarea -->
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Company Name">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                            <input type="phone" class="form-control" placeholder="Phone">
                      </div>
                    </div>
                  </div>

                  <!-- input states -->
                  <div class="form-group">
                    <label class="col-form-label" for="inputSuccess"><i class="fas fa-building"></i> Company Address <span class="disabled">(Kindly provide actual address and not a post box address )</span></label>
                    <input type="text" class="form-control" id="inputSuccess" placeholder="Enter your Street">
                  </div>
                  <!-- <div class="form-group">
                    <label class="col-form-label" for="inputWarning"><i class="far fa-bell"></i> Input with
                      warning</label>
                    <input type="text" class="form-control is-warning" id="inputWarning" placeholder="Enter ...">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label" for="inputError"><i class="far fa-times-circle"></i> Input with
                      error</label>
                    <input type="text" class="form-control is-invalid" id="inputError" placeholder="Enter ...">
                  </div> -->

                  <div class="row">
                    <div class="col-md-4">
                      <!-- select -->
                      <div class="form-group">
                        <label>State</label>
                        <select class="form-control">
                          <option>option 1</option>
                          <option>option 2</option>
                          <option>option 3</option>
                          <option>option 4</option>
                          <option>option 5</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>County</label>
                        <select class="form-control">
                          <option>option 1</option>
                          <option>option 2</option>
                          <option>option 3</option>
                          <option>option 4</option>
                          <option>option 5</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>City</label>
                        <select class="form-control">
                          <option>option 1</option>
                          <option>option 2</option>
                          <option>option 3</option>
                          <option>option 4</option>
                          <option>option 5</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <input type="email" class="form-control" id="inputSuccess" placeholder="Email">
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- checkbox -->
                      <div class="form-group">
                        <input type="password" class="form-control" id="inputSuccess" placeholder="Password">
                      </div>
                    </div> 
                    <div class="col-sm-6">
                      <!-- radio -->
                      <div class="form-group">
                        <input type="password" class="form-control" id="inputSuccess" placeholder="Confirm Password">
                      </div>
                    </div>
                  </div>

                  

                  
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card card-warning">
              <div class="card-header">
                <h4 class="text-center">Define Coverage</h4>
              </div>
              <div class="card-body">
                
                <div class="row">
                    <div class="col-sm-6">
                      <!-- Select multiple-->
                      <div class="form-group">
                        <label>State</label>
                        <select class="form-control">
                          <option>option 1</option>
                          <option>option 2</option>
                          <option>option 3</option>
                          <option>option 4</option>
                          <option>option 5</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Choose Countries</label>
                        <select multiple class="form-control">
                          <option>option 1</option>
                          <option>option 2</option>
                          <option>option 3</option>
                          <option>option 4</option>
                          <option>option 5</option>
                        </select>
                      </div>
                    </div>
                  </div>
                <div class="col-12">
                <div class="form-group ag_TC">
                <input type="checkbox" class="form-check-input" id="exampleCheck2">
                    <label class="form-check-label" for="exampleCheck2">By clicking here, I state that I have read and understood the terms and conditions.</label>
                </div>
                </div>
                </div>
                
            </div> 
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Sign in</button>
                <button type="submit" class="btn btn-default float-right">Cancel</button>
            </div> 
          </div>

          
          </form>
            <!-- /.card -->
              
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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
          <div class="col-8 col-md-4 d-inline-flex">
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

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
