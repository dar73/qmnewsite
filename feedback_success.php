<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Feedback success';
$TITLE = SITE_NAME . ' | ' . $page_title;


?>
<?php include '_loginBtnLogic.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <?php include 'load.link.php'; ?>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row  justify-content-center">
                        <div class="col-md-6 feedback">
                            <div class="card card-info my-5">
                                <div class="card-header border-0">
                                    <div id="LBL_INFO"></div>
                                    <div class="d-flex">
                                        <h3 class="text-left">Submited successfully</h3>
                                        <img class="w-50 ml-auto" src="Images/logo.png" alt="">
                                    </div>

                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form class="form-horizontal"  method="POST">
                                    <input type="hidden" value="" name="">
                                    <div class="card-body text-center">
                                        <h6>Thank you for your feedback</h6>

                                       
                                    <!-- /.card-footer -->
                                </form>
                            </div>
                            <!-- /.card -->

                        </div>

                        <!-- /.card -->
                    </div>
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
        // Show the reasons section if "No" is selected
        document.querySelectorAll('input[name="meeting_status"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('reasons').style.display = this.value === '2' ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>