<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
$PAGE_TITLE2 = 'Service providers with no coverages';
$MEMORY_TAG = "SERVICE_PROVIDERS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'incomplete_coverage.php';
$edit_url = 'incomplete_coverage.php';
//sp_coverage_edit.php?mode=E&id=380
$edit_coverage_url = 'sp_coverage_edit.php';
$excel_url = 'download_sp_coverage_rpt2.php';


$execute_query = $is_query = false;
$perc = 11.11;
$txtkeyword = $state = $county = $cond = $params = $params2 = '';
$COUNTY_ARR = array();
$SP_IDS_ARR = array();
$srch_style = 'display:none;';

$SP_DETAILS_ARR = array();
$_q1 = "select id,concat(First_name,' ',Last_name) as fullname,company_name,phone,email_address from service_providers where cStatus!='X' ";
$_r1 = sql_query($_q1, "sp_data");
while (list($id, $fullname, $cname, $phone, $email) = sql_fetch_row($_r1)) {
    if (!isset($SP_DETAILS_ARR[$id])) {
        $SP_DETAILS_ARR[$id] = array('id' => $id, 'fullname' => $fullname, 'cname' => $cname, 'phone' => $phone, 'email' => $email);
    }
}


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtkeyword = $_POST['txtkeyword'];
    $params = '&keyword=' . $txtkeyword;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
    $params2 = '?keyword=' . $txtkeyword;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtkeyword)) {
    $cond .= " and zip=$txtkeyword";
    $execute_query = true;
}


if ($execute_query)
    $srch_style = '';
if ($execute_query) {
    # code...
    //$SP_IDS_ARR = GetXArrFromYID("select iproviderID from coverages where 1 $cond ");
    $dataArr = GetDataFromCOND("service_providers_areas", "$cond");
}
//DFA($SP_IDS_ARR);
$q = "select  id,concat(First_name,' ',Last_name) as fullname,company_name,phone,email_address from service_providers where cStatus!='X' and id not in (select distinct(service_providers_id) from service_providers_areas) ";
$r = sql_query($q);
$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;
//DFA($dataArr);
//DFA($_SESSION);
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
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="display: none;">
                                    <div class="app-inner-layout__header page-title-icon-rounded  bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <div class="wm-100 mrm-50  form-group m-1">
                                                        <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="search zip.." class="form-control" />
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
                                        <!-- <a href="<?php //echo $excel_url . $params2; 
                                                        ?>"><button class="btn btn-success">Export to excel</button></a> -->
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <h4></h4>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>

                                                <th>Full Name</th>
                                                <!-- <th>Last Name</th> -->
                                                <th>Company Name</th>
                                                <!-- <th>State</th> -->
                                                <!-- <th>County</th> -->
                                                <th>Phone</th>
                                                <th>Email Address</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (sql_num_rows($r)) {
                                                $i = 1;
                                                while (list($id, $fname, $company, $phone, $email) = sql_fetch_row($r)) {
                                                    $URL = $edit_coverage_url.'?mode=E&id='.$id;
                                            ?>
                                                    <tr>

                                                        <td><a href="<?php echo $URL;?>"><?php echo $fname; ?></a></td>
                                                        <td><?php echo $company; ?></td>

                                                        <td><?php echo $phone; ?></td>
                                                        <td><?php echo $email; ?></td>

                                                    </tr>
                                            <?php
                                                    $i++;
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
            $('#frmSearch').submit(function() {

            });
            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                pageLength: 100

            });



        });
    </script>
</body>

</html>