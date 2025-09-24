<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Vendor Register';
$TITLE = SITE_NAME . ' | ' . $page_title;
include '_loginBtnLogic.php';
//https://thequotemasters.com/leads_schedule.php?spid=' . $SP_ID . '&appid=' . $APP_ID . '&c=N
$spid = (isset($_GET['spid'])) ? $_GET['spid'] : '';
$appid = (isset($_GET['appid'])) ? $_GET['appid'] : '';
$c = (isset($_GET['c'])) ? $_GET['c'] : '';
$message='';
if (!empty($spid) && !empty($appid) && !empty($c)) {
    if ($c=='N') {
        sql_query("insert  into buyed_leads_history select *,NOW() from buyed_leads where iApptID='$appid' and ivendor_id='$spid' ","insert into leads history");
        sql_query("delete from buyed_leads where iApptID='$appid' and ivendor_id='$spid' ","delete from buyed leads");
        sql_query("update appointments set cService_status='P' where  iApptID='$appid' ");
        $message = 'Your appointment has been successfuly cancelled ';
    }elseif ($c=='Y') {
        $message='Your appointment has been successfuly accepted ';
    }
}else{
    echo 'Invalid access detected !!';
    exit;
}
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

                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="text-center">Lead confirmation</h3>


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>Info!</strong> <?php echo $message;?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!-- /.card -->



                    <!-- /.row -->

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

    </script>
</body>

</html>