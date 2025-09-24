<?php include "../includes/common.php";
$PAGE_TITLE2 = 'Feedback Details';
$MEMORY_TAG = "FEEDBACK_DETAIL";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'view_feedback.php';
$edit_url = 'view_feedback.php';


$appid = (isset($_GET['appid'])) ? $_GET['appid'] : '';
if (empty($appid)) {
    echo 'Invalid access detected!!';
    exit;
}

$APP_DATA = GetDataFromID('appointments', 'iApptID', $appid, '');
$BUYED_LEADS = GetDataFromID('buyed_leads', 'iApptID', $appid, '');
$SP_ID = $BUYED_LEADS[0]->ivendor_id;
$SP_DATA = GetDataFromID('service_providers', 'id', $SP_ID, '');
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array
$feedback_id = GetXFromYID("select id from feedback where iApptID=$appid and cStatus='A' ");

$FEED_BACK_RESPONCE = GetDataFromQuery("select * from feedback_response where Id=$feedback_id and cStatus='A' ");
$MEETING_STATUS = array('1' => 'Yes', '2' => 'No');
$MEETING_STATUS2 = GetXArrFromYID("select Id,vAns from feedback_ans where Id in(3,4,5,6,7) and cStatus='A' ", '3');

//  [0] => stdClass Object
//         (
//             [Id] => 7
//             [ifd_responseID] => 7
//             [iApptID] => 68
//             [iQuestionID] => 1
//             [meeting_status] => 2
//             [no_reason] => 4
//             [cStatus] => A
//         )
$meeting_status1 = $FEED_BACK_RESPONCE[0]->meeting_status;
$no_reason = $FEED_BACK_RESPONCE[0]->no_reason;
 //echo $meeting_status1;

$display_style = 'display: none;';
if ($meeting_status1 == 2) {
    $display_style = '';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php' ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $PAGE_TITLE2 ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $PAGE_TITLE2 ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="row">

                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <label class="form-check">We wanted to do a quick survey to see if you were able to meet with <?php echo  $SP_DATA[0]->company_name; ?> at <?php echo  $TIMEPICKER_ARR[$APP_DATA[0]->iAppTimeID]; ?> ?</label>

                                    <?php
                                    foreach ($MEETING_STATUS as $key => $value) {
                                        //echo $key.'='. $meeting_status1;
                                        $selected = ($key == $meeting_status1) ? 'checked' : '';
                                        echo '<label class="form-check-label"><input type="radio" name="meeting_status" value="' . $key . '" ' . $selected . '>' . $value . '</label><br>';
                                    }

                                    ?>

                                    <div id="reasons" style="<?php echo $display_style; ?>">
                                        <p></p>

                                        <label class="form-check-label"><input type="radio" name="no_reason" value="3" <?php echo ($no_reason == '3') ? 'checked' : ''; ?>> I needed to reschedule</label><br>
                                        <label class="form-check-label"><input type="radio" name="no_reason" value="4" <?php echo ($no_reason == '4') ? 'checked' : ''; ?>> <?php echo  $SP_DATA[0]->company_name; ?> needed to reschedule</label><br>
                                        <label class="form-check-label"><input type="radio" name="no_reason" value="5" <?php echo ($no_reason == '5') ? 'checked' : ''; ?>> We rescheduled</label><br>
                                        <label class="form-check-label"><input type="radio" name="no_reason" value="6" <?php echo ($no_reason == '6') ? 'checked' : ''; ?>> I was not able to attend</label><br>
                                        <label class="form-check-label"><input type="radio" name="no_reason" value="7" <?php echo ($no_reason == '7') ? 'checked' : ''; ?>> They did not show</label><br>
                                    </div>

                                    <!-- <label class="form-check-label"><input type="radio" name="meeting_status" value="1"> Yes</label><br>
                                    <label class="form-check-label"><input type="radio" name="meeting_status" value="2"> No</label> -->
                                </div>
                            </div>

                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>


                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?php include 'load.footer.php' ?>


    </div>
    <?php include 'load.scripts.php' ?>
    <script>

    </script>
</body>

</html>