<?php
include "../includes/common.php";
include "../phpmailer.php";
$PAGE_TITLE2 = 'Service Providers Logs';
$MEMORY_TAG = "SERVICE_PROVIDERS_LOGS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'splogs.php';
$edit_url = 'sp_edit.php';


$execute_query = $is_query = false;
$txtFrom = $txtdTo = TODAY;
$cond = $params = $params2 = '';
$srch_style = '';

$SP_ARRAY = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers where cStatus!='X' ", "3");


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtFrom = $_POST['txtFrom'];
    $txtdTo = $_POST['txtdTo'];

    $params = '&txtFrom=' . $txtFrom . '&txtdTo=' . $txtdTo;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['txtFrom'])) $txtFrom = $_GET['txtFrom'];
    if (isset($_GET['txtdTo'])) $txtdTo = $_GET['txtdTo'];

    $params2 = '?txtFrom=' . $txtFrom . '&txtdTo=' . $txtdTo;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtFrom) && !empty($txtdTo)) {

    $cond .= " and dDate between '$txtFrom' and '$txtdTo' ";
    $execute_query = true;
}

if ($execute_query) {
    $dataArr = GetDataFromCOND('log_signin', " and cRefType LIKE 'V' and iRefID!=0 $cond ");
}




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
                                                        <input type="date" name="txtFrom" id="txtFrom" value="<?php echo $txtFrom; ?>" class="form-control" />
                                                    </div>
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <input type="date" name="txtdTo" id="txtdTo" value="<?php echo $txtdTo; ?>" class="form-control" />
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
                                                <th>SP NAME</th>
                                                <th>LOG IN</th>
                                                <th>IPAddress</th>
                                                <th>Browser</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output2($dataArr[$u]->iLSID);
                                                    $SPid = db_output2($dataArr[$u]->iRefID);
                                                    $dtEntry = db_output2($dataArr[$u]->dtEntry);
                                                    $vIPAddress = db_output2($dataArr[$u]->vIPAddress);
                                                    $vBrowser = db_output2($dataArr[$u]->vBrowser);
                                                    $SP_NAME = isset($SP_ARRAY[$SPid]) ? $SP_ARRAY[$SPid] : 'NA';

                                                    $url = $edit_url . '?mode=E&id=' . $SPid;
                                                    //$edit_coverage_url = "sp_coverage_edit.php?mode=E&id=" . $SPid;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo $SP_NAME; ?></a></td>
                                                       
                                                        <td><?php echo $dtEntry; ?></td>
                                                        <td><?php echo $vIPAddress; ?></td>
                                                        <td><?php echo $vBrowser; ?></td>
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
        function DeleteUser(id) {
            let text = "Are you sure you want to delete this user?";
            if (confirm(text) == true) {
                $.ajax({
                    url: '_DeleteUser.php',
                    method: 'POST',
                    data: {
                        mode: 'D',
                        id: id,
                        type: 'SP'
                    },
                    success: function(res) {
                        // console.log(res);
                        if (res == 1) {
                            alert('User deleted successfuly ');
                            location.reload();

                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }

        function SendVerificationLink(spid) {
            $.ajax({
                url: ajax_url2,
                method: 'POST',
                data: {
                    response: 'SEND_VERIFICATION_LINK',
                    spid: spid
                },
                success: function(res) {
                    if (res) {
                        $('#LBL_INFO').html(NotifyThis('Mail has been sent successfuly......', 'success'));
                    }
                }
            });

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