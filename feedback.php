<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Feedback';
$TITLE = SITE_NAME . ' | ' . $page_title;
$appid = (isset($_GET['appid'])) ? $_GET['appid'] : '';
if (empty($appid)) {
    echo 'Invalid access detected!!';
    exit;
}
$is_feedback_submitted = GetXFromYID("select count(*) from  feedback where iApptID='$appid' ");
$message = 'Feed back for this appointment is already submitted';
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array
$APP_DATA = GetDataFromID('appointments', 'iApptID', $appid, '');
$BUYED_LEADS = GetDataFromID('buyed_leads', 'iApptID', $appid, '');
$SP_ID = $BUYED_LEADS[0]->ivendor_id;
$SP_DATA = GetDataFromID('service_providers', 'id', $SP_ID, '');
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
                                        <h3 class="text-left">FEEDBACK</h3>
                                        <img class="w-50 ml-auto" src="Images/logo.png" alt="">
                                    </div>

                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <?php
                                if ($is_feedback_submitted > 0) { ?>
                                    <p>Feedback for this appointment is already submitted!!</p>

                                <?php  } else { ?>

                                    <form class="form-horizontal" action="save_feedback.php" method="POST">
                                        <input type="hidden" value="<?php echo $appid; ?>" name="appid">
                                        <div class="card-body">
                                            <label class="form-check">We wanted to do a quick survey to see if you were able to meet with <?php echo  $SP_DATA[0]->company_name; ?> at <?php echo  $TIMEPICKER_ARR[$APP_DATA[0]->iAppTimeID]; ?> ?</label>

                                            <label class="form-check-label"><input type="radio" name="meeting_status" value="1"> Yes</label><br>
                                            <label class="form-check-label"><input type="radio" name="meeting_status" value="2"> No</label>

                                            <div id="reasons" style="display: none;">
                                                <p></p>

                                                <label class="form-check-label"><input type="radio" name="no_reason" value="3"> I needed to reschedule</label><br>
                                                <label class="form-check-label"><input type="radio" name="no_reason" value="4"> <?php echo  $SP_DATA[0]->company_name; ?> needed to reschedule</label><br>
                                                <label class="form-check-label"><input type="radio" name="no_reason" value="5"> We rescheduled</label><br>
                                                <label class="form-check-label"><input type="radio" name="no_reason" value="6"> I was not able to attend</label><br>
                                                <label class="form-check-label"><input type="radio" name="no_reason" value="7"> They did not show</label><br>
                                            </div>
                                            
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="reset" class="btn secondary-btn order-sm-1 order-2">Cancel</button>
                                            <button type="submit" class="btn primary-btn float-right">Submit</button>
                                        </div>
                                        <!-- /.card-footer -->
                                    </form>
                                <?php  }

                                ?>
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