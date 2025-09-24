<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../phpmailer.php";
$PAGE_TITLE2 = 'Credit Requests';
$MEMORY_TAG = "CREDIT";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'credit_requests.php';
$edit_url = '';


$execute_query = $is_query = true;
$txtkeyword = $txtdate = $pid = $cond = $params = $params2 = $cmbplan = '';
$srch_style = '';


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    // $txtkeyword = $_POST['txtkeyword'];
    $txtdate = $_POST['txtdate'];
    // $cmbplan = $_POST['cmbplan'];
    $pid = $_POST['pid'];

    $params = '&txtdate=' . $txtdate . '&pid=' . $pid;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    // if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
    if (isset($_GET['txtdate'])) $txtdate = $_GET['txtdate'];
    if (isset($_GET['pid'])) $pid = $_GET['pid'];
    // if (isset($_GET['cmbplan'])) $cmbplan = $_GET['cmbplan'];

    $params2 = '&txtdate=' . $txtdate . '&pid=' . $pid;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);



if (!empty($txtdate)) {
    $cond .= " and dDate='$txtdate' ";
    $execute_query = true;
}

if (!empty($pid)) {
    $cond .= " and iPID='$pid' ";
    $execute_query = true;
}


//$cond .= " and cRefType='A' and cStatus!='X'";
$SERVICE_PROVIDERS = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers where cStatus='A'  order by id ", "3");

$dataArr = GetDataFromCOND("credit_request", $cond . " and cStatus!='X' order by iRequestID desc");

//DFA($REQUEST_STATUS_ARR);


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
                                                        <input type="date" name="txtdate" id="txtdate" value="<?php echo $txtdate; ?>" class="form-control" />
                                                    </div>
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <?php echo FillCombo2022('pid', $pid, $SERVICE_PROVIDERS, 'SP') ?>
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
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Code</th>
                                                <th>APP ID</th>
                                                <th>Approve Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $AGRE=$CALENDAR_A=$AUTH='<span class="text-danger">No</span>';
                                                    $x_id = db_output2($dataArr[$u]->iRequestID);
                                                    $x_PID = db_output2($dataArr[$u]->iPID);
                                                    $x_timestamp = db_output2($dataArr[$u]->dDate);
                                                    $x_APPTID = db_output2($dataArr[$u]->iApptID);
                                                    $x_Code = db_output2($dataArr[$u]->vCode);
                                                    $x_APPROV_STATUS = db_output2($dataArr[$u]->cApprovalStatus);
                                                    $SP_NAME=isset($SERVICE_PROVIDERS[$x_PID])?$SERVICE_PROVIDERS[$x_PID]:'NA';
                                                    $class='';
                                                    if ($x_APPROV_STATUS=='P') {
                                                        $class='badge badge-secondary';
                                                    }elseif ($x_APPROV_STATUS=='A') {
                                                        $class='badge badge-success';
                                                    }elseif ($x_APPROV_STATUS=='C') {
                                                        $class='badge badge-danger';
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><?php echo $SP_NAME; ?></td>
                                                        <td><?php echo date('m/d/Y' . ', ' . 'h:i A', strtotime($x_timestamp)); ?></td>
                                                        <td><?php echo $x_Code; ?></td>
                                                        <td><?php echo $x_APPTID; ?></td>
                                                        <td style="text-align:center;"><span class="<?php echo $class; ?>"><?php echo $REQUEST_STATUS_ARR[$x_APPROV_STATUS]; ?></span></td>
                                                        <td><div><button onclick="APPROVE('<?php echo $x_id; ?>');" class="btn btn-success" type="button">Approve</button></div></td>
                                                        

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
    <script src="../scripts/common.js"></script>
    <script>
        function APPROVE(id) {
            let text = "Are you sure you want to approve this request?";
            if (confirm(text) == true) {
                var data="response=ASSIGN_APPT&REQUEST_ID="+id;
                $.ajax({
                    url: ajax_url2,
                    method: 'POST',
                    data: data,
                    success: function(res) {
                         var RES=res.split("~");
                         alert(RES[1]);
                         location.reload();
                        // if (res == 1) {
                        //     alert('User deleted successfuly ');
                        //     location.reload();

                        // }

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
</body>

</html>