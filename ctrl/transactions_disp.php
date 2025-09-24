<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../includes/common.php";
$PAGE_TITLE2 = 'Transactions';
$MEMORY_TAG = "TRANSACTIONS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'transactions_disp.php';
$edit_url = 'tran_edit.php';


$execute_query = $is_query = true;
$txtkeyword = $cond = $params = $params2 = '';
$srch_style = '';
$cmbPayStatus = 'S';


$PAY_MODE = array("P"=>"Pending","S"=>"Success");


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $cmbPayStatus = $_POST['cmbPayStatus'];

    $params = '&cmbPayStatus=' . $cmbPayStatus;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['cmbPayStatus'])) $cmbPayStatus = $_GET['cmbPayStatus'];

    $params2 = '?cmbPayStatus=' . $cmbPayStatus;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($cmbPayStatus)) {
    $cond .= " and payment_status='$cmbPayStatus' ";
    $execute_query = true;
}

//if($execute_query)
//$srch_style = '';

//$cond .= " and cRefType='A' and cStatus!='X'";
$dataArr = GetDataFromCOND("transaction", $cond . ' order by pdate DESC');

// DFA($dataArr);
// exit;
$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;
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
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                                    <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <!-- <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="Keywords" class="form-control" /> -->
                                                        <?php echo FillCombo($cmbPayStatus, 'cmbPayStatus','COMBO','0',$PAY_MODE,'');?>
                                                    </div>
                                                    <div class="page-title-actions mb-2" style="width:100%;">
                                                        <div class="d-inline-block dropdown">
                                                            <button type="submit" class="btn btn-warning"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                                                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-danger" onClick="GoToPage('<?php echo $disp_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-times fa-w-20"></i> </span> Reset </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header-tab card-header">
                                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal"> <i class="header-icon pe-7s-culture mr-3 text-muted opacity-6"> </i></div>
                                    <div class="btn-actions-pane-right actions-icon-btn float-right">
                                        <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button> -->
                                        <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php //echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button> -->
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Booking ID</th>
                                                <th>Appointment ID</th>
                                                <th>Payment ID</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                <th>Payment Type</th>
                                                <th>Payment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                               
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output($dataArr[$u]->id);
                                                    $x_bId = db_output($dataArr[$u]->booking_id);
                                                    $x_AID = db_output($dataArr[$u]->iApptID);
                                                    $x_payment_id = db_output($dataArr[$u]->payment_id);
                                                    $x_amount = db_output($dataArr[$u]->amount);
                                                    $x_pDate = db_output($dataArr[$u]->pdate);
                                                    $x_paymentType = db_output($dataArr[$u]->payment_type);
                                                    $stat = $dataArr[$u]->payment_status;
                                                    $status_str = Show_serviceStatus($stat);
                                                    $url = $edit_url . '?mode=E&id=' . $x_id;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="javascript:void(0);"><?php echo $x_bId; ?></a></td>
                                                        <td style="color: brown;font-weight: bold;">QM-<?php echo $x_AID; ?></td>
                                                        <td><?php echo $x_payment_id; ?></td>
                                                        <td><?php echo $x_amount; ?></td>
                                                        <td><?php echo $x_pDate; ?></td>
                                                        <td><?php echo $x_paymentType; ?></td>
                                                        <td style="text-align:center;"><?php echo $status_str; ?></td>

                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div><!-- /.card-body -->
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
        $(document).ready(function() {
            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                "pageLength": 100

            });



        });
    </script>
</body>

</html>