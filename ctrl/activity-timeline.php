<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
//ini_set('display_startup_errors', 1);

include "../includes/common.php";
$PAGE_TITLE2 = 'Activity Timeline';
$disp_url = "service_providers_disp.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $txtid = $_GET['id'];
} else {
    $txtid = 0;
}

if (empty($txtid)) {
    header("Location: $disp_url");
    exit;
}


$timeline_q = "select * from app_sp_logs where iSPID = '$txtid' and cStatus = 'A' order by dtDate desc";
$timeline_res = sql_query($timeline_q);
$timelineArr = sql_num_rows($timeline_res);

$q= "SELECT concat(First_name,' ',Last_name) as spname,company_name FROM service_providers WHERE id= '$txtid'";
$sp_res = sql_query($q);
if (sql_num_rows($sp_res) > 0) {
    $sp_row = sql_fetch_object($sp_res);
    $PAGE_TITLE2 .= ' - ' . $sp_row->spname . ' - ' . $sp_row->company_name;
} else {
    $PAGE_TITLE2 .= ' - Service Provider Not Found';
}


$PAGE_TITLE .= $PAGE_TITLE2;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
</head>
<?php include '_include_form.php' ?>

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
                        <div class="col-md-12">
                            
                            <!-- The time line -->
                            <div class="timeline">
                                <?php

                                //DFA($dataArr);

                                if (!empty($timelineArr)) {
                                    $disp_date = '';
                                    for ($i = 1; $u = sql_fetch_object($timeline_res); $i++) {
                                        $x_log = $u->iLogID;
                                        $x_date = $u->dtDate;
                                        $x_ref = $u->iRefID;
                                        $x_desc = $u->vDesc;
                                        $x_stat = $u->cStatus;

                                        $x_date1 = FormatDate($x_date, "B");
                                        $x_time = FormatDate($x_date, "4");

                                        if ($disp_date != $x_date1) {
                                            echo '<div class="time-label">
											<span class="bg-red">' . $x_date1 . '</span></div>';
                                        }
                                ?>
                                        <!-- timeline time label -->
                                        <!-- <div class="time-label">
                                            <span class="bg-red">10 Feb. 2014</span>
                                        </div> -->
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->

                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                            <div class="timeline-item">
                                                <span class="text-danger"><i class="fas fa-clock"></i>&nbsp;<?php echo $x_time; ?></span>
                                                <h3 class="timeline-header no-border"><a href="#"><?php echo $ACTIVITY_TIMELINE_ARR[$x_ref]; ?></a> <?php echo htmlspecialchars_decode($x_desc); ?></h3>
                                            </div>
                                        </div>

                                <?php
                                        $disp_date = $x_date1;
                                    }
                                }

                                ?>
                                <!-- END timeline item -->
                                <!-- timeline item -->

                                <!-- END timeline item -->
                                <!-- timeline time label -->

                                <!-- END timeline item -->
                                <!-- <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div> -->
                            </div>
                        </div>

                        <!-- /.col -->

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

</body>

</html>