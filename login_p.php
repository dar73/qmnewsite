<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login</title>
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
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light navbar-white border-bottom">
      <div class="container">
        <a href="index.php" class="navbar-brand">
          <img class="logo_img" src="images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <!-- Left navbar links -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item m-2">
              <button class="btn nav-link px-2 float-right"><i class="fa fa-briefcase" aria-hidden="true"></i> Join as Provider</button>
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
            <div class="col-md-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="text-center">Login</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal">
                  <div class="card-body">
                    <div class="form-group row justify-content-center">
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                      </div>
                    </div>
                    <div class="form-group row justify-content-center">
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                      </div>
                    </div>
                    <div class="form-group row justify-content-center">
                      <div class="offset-sm-2 col-sm-10">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" id="exampleCheck2">
                          <label class="form-check-label" for="exampleCheck2">Show Password</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-info">Sign in</button>
                    <button type="submit" class="btn btn-default float-right">Cancel</button>
                  </div>
                  <!-- /.card-footer -->
                </form>
              </div>
              <!-- /.card -->

            </div>

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
      <div class="col-sm-6 text-sm-left">
        <p class="">@ 2023 quotemaster.com</p>
      </div>
      <div class="col-sm-6 text-sm-right">
        <p class="">All rights reserved</p>
      </div>
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
    $(document).ready(function() {
      bsCustomFileInput.init();
    });
  </script>
</body>

</html>