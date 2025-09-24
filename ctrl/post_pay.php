<?php
include "../includes/common.php";
include "../phpmailer.php";
$PAGE_TITLE2 = 'Post Payments';
$MEMORY_TAG = "POST_PAYMENTS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'post_pay.php';
$edit_url = 'sp_edit.php';


$execute_query = $is_query = true;
$txtkeyword = $txtphone = $cmbcleaningstatus = $cond = $params = $params2 = $paid = '';
$srch_style = '';

$PREMIUM_SP = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where 1  ", "3");
$APPPINTMENTS_TIMING = GetXArrFromYID("SELECT
B.iApptID,
  concat(date_format(B.dDateTime,'%m-%d-%Y'),' @ ',A.title)
FROM
  appointments B
  INNER JOIN apptime A ON A.Id= B.iAppTimeID", '3');
//DFA($APPPINTMENTS_TIMING);

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $paid = $_POST['paid'];


    $params = '&paid=' . $paid;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['paid'])) $paid = $_GET['paid'];

    $params2 =  '&paid=' . $paid;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);



if ($paid) {
    $cond .= " and cPaid='$paid'";
    $execute_query = true;
}



$EMAIL_VERIFY = array('0' => '<span class="badge badge-danger">No</span>', '1' => '<span class="badge badge-success">Yes</span>');

$SOURCE = array('EM' => 'Email Marketing', 'T' => 'Telemarketing', 'TT' => 'Telemarketing - Text / Animation', 'SF' => 'Social Media FaceBook', 'SL' => 'Social Media LinkedIn', 'SI' => 'Social Media Instagram');

//if($execute_query)
//$srch_style = '';

//$cond .= " and cRefType='A' and cStatus!='X'";
$dataArr = GetDataFromCOND("platinum_purchase_leads", $cond . " and cStatus!='X' order by dDate desc");


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
                                                        <?php echo FillCombo2022('paid', $paid, $YES_ARR, 'Paid/Unpaid') ?>
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
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                                        <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button> -->
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>SP</th>
                                                <th>APPID</th>
                                                <th>Schedule</th>
                                                <th>Amt</th>
                                                <th>Paid(Y/N)</th>
                                                <th>Date Paid</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $Date = db_output2($dataArr[$u]->dDate);
                                                    $SPID = db_output2($dataArr[$u]->ivendor_id);
                                                    $APPID = db_output2($dataArr[$u]->iApptID);
                                                    $AMT = db_output2($dataArr[$u]->fAmt);
                                                    $PAID = db_output2($dataArr[$u]->cPaid);
                                                    $DATE_PAID = db_output2($dataArr[$u]->dtPaid);
                                                    $SP_NAME = isset($PREMIUM_SP[$SPID]) ? $PREMIUM_SP[$SPID] : 'NA';
                                                    $APP_SCHEDULE = isset($APPPINTMENTS_TIMING[$APPID]) ? $APPPINTMENTS_TIMING[$APPID] : 'NA';
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><?php echo date('m-d-Y', strtotime($Date)); ?></td>
                                                        <td><?php echo $SP_NAME; ?></td>
                                                        <td><?php echo $APPID; ?></td>
                                                        <td><?php echo $APP_SCHEDULE; ?></td>
                                                        <td><?php echo $AMT; ?></td>
                                                        <td><?php echo $PAID; ?></td>
                                                        <td><?php echo date('m-d-Y', strtotime($DATE_PAID)); ?></td>
                                                        <td style="text-align:center;">
                                                            <div>
                                                                <?php if ($PAID == 'N') { ?>
                                                                    <button type="button" onclick="InitiatePayment2('<?php echo $APPID; ?>','<?php echo $SPID; ?>');" class="btn btn-danger">INITIATE PAYMENT</button>&nbsp;&nbsp;
                                                                    <button type="button" onclick="CANCEL('<?php echo $APPID; ?>','<?php echo $SPID; ?>');" class="btn btn-warning">CANCEL</button>
                                                                <?php } else {
                                                                    echo '<span class="badge badge-success">Paid</span>';
                                                                } ?>
                                                            </div>
                                                        </td>

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
    <?php include 'load.scripts.php'; ?>
    <script>
        function InitiatePayment(appid, spid) {
            let text = "Are you sure you want to initiate $125 debit?";
            var data = "spid=" + spid + "&appid=" + appid;
            if (confirm(text) == true) {
                $.ajax({
                    url: '_debitamt.php',
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        var RES_ARR = res.split("~");
                        alert(RES_ARR[1]);

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }

        function InitiatePayment2(appid, spid) {
            let text = "Are you sure you want to initiate $125 debit?";
            var data = "spid=" + spid + "&appid=" + appid;
            if (confirm(text) == true) {
                $.ajax({
                    url: '_debitamtModal.php',
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        var ARR = res.split("~~*~~");
                        //console.log(ARR);
                        $('#Modal').modal('show');
                        $('#MODAL_TITLE').html(ARR[0]);
                        $('#MODAL_BODY').html(ARR[1]);


                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }


        function CANCEL(appid, spid) {
            let text = "Are you sure you want to cancel this appointment ?";
            var data = "spid=" + spid + "&appid=" + appid;
            if (confirm(text) == true) {
                $.ajax({
                    url: '_cancelbooking.php',
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        var ARR = res.split("~~*~~");
                        //console.log(ARR);
                        alert(ARR[1]);
                        location.reload();

                    }

                });

                //form.submit();
            } else {
                text = "You canceled!";
            }

        }



        $(document).ready(function() {
            // $(document).Toasts('create', {
            //     class: 'bg-success',
            //     title: 'Success',
            //     subtitle: '',
            //     body: 'You can add multiple states .',
            //     delay: 8000, // 3 seconds
            //     autohide: true
            // })



            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                pageLength: 100

            });



        });
    </script>
    <div class="modal fade" id="Modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-danger" id="MODAL_TITLE">Leads Info</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="MODAL_BODY">
                    Modal body..
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>