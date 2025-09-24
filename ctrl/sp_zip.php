<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../includes/common.php";
$PAGE_TITLE2 = 'Service providers Coverage search by Zip';
$MEMORY_TAG = "SERVICE_PROVIDERS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'sp_zip.php';
$edit_url = 'sp_zip.php';
$excel_url = 'download_sp_coverage_rpt2.php';

$STATE_ARR = GetXArrFromYID("select distinct state,state from areas ", '3');
$COUNTYS_ARR = GetXArrFromYID("select distinct County_name,County_name from areas ", '3');


$execute_query = $is_query = false;
$perc = 11.11;
$txtkeyword = $state = $county = $cond = $params = $params2 = '';
//$COUNTY_ARR = array();
$SP_IDS_ARR = array();
$srch_style = 'display:none;';

$SP_DETAILS_ARR = array();
$_q1 = "select id,concat(First_name,' ',Last_name) as fullname,company_name,phone,email_address,cAdmin_approval,cUsertype from service_providers where cStatus!='X' ";
$_r1 = sql_query($_q1, "sp_data");
while (list($id, $fullname, $cname, $phone, $email, $cStatus, $cUsertype) = sql_fetch_row($_r1)) {
    if (!isset($SP_DETAILS_ARR[$id])) {
        $SP_DETAILS_ARR[$id] = array('id' => $id, 'fullname' => $fullname, 'cname' => $cname, 'phone' => $phone, 'email' => $email, 'cStatus' => $cStatus, 'cUsertype' => $cUsertype);
    }
}



if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtkeyword = $_POST['txtkeyword'];
    $state = $_POST['state'];
    $county = $_POST['county'];
    $params = '&keyword=' . $txtkeyword . '&state=' . $state . '&county=' . $county;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
    if (isset($_GET['state'])) $state = $_GET['state'];
    if (isset($_GET['county'])) $county = $_GET['county'];
    $params2 = '?keyword=' . $txtkeyword . '&state=' . $state . '&county=' . $county;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtkeyword)) {
    $cond .= " and zip=$txtkeyword";
    $execute_query = true;
}

if (!empty($state) && !empty($county)) {
    $ZIPS = GetIDString2("select zip from areas where state='$state' and County_name='$county' ");
    if (empty($ZIPS)) {
        $ZIPS = '0';
    }
    $cond = " and zip in ($ZIPS)";
    $execute_query = true;
}


if ($execute_query)
    $srch_style = '';
if ($execute_query) {
    # code...
    //$SP_IDS_ARR = GetXArrFromYID("select iproviderID from coverages where 1 $cond ");
    $dataArr = GetDataFromQuery("select distinct service_providers_id from  service_providers_areas where 1  $cond");
}
//DFA($SP_IDS_ARR);
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
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="">
                                    <div class="app-inner-layout__header page-title-icon-rounded  bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <div class="wm-100 mrm-50  form-group m-1">
                                                        <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="search zip.." class="form-control" />
                                                    </div>
                                                    <div class="wm-100 mrm-50  form-group m-1" style="display: none;">
                                                        <?php echo FillCombo2022('state', $state, $STATE_ARR, '', 'form-control', 'GET_COUNTY(this.value);') ?>
                                                    </div>
                                                    <div class="wm-100 mrm-50  form-group m-1" style="display: none;">
                                                        <span id="COUNTY_HTML">

                                                            <?php echo FillCombo2022('county', $county, $COUNTYS_ARR, '') ?>
                                                        </span>
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
                                                <th>MEM TYPE</th>
                                                <th>Phone</th>
                                                <th>Email Address</th>
                                                <th>Approve by Admin</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $x_id = db_output($dataArr[$u]->service_providers_id);

                                                    if (isset($SP_DETAILS_ARR[$x_id])) {
                                                        $x_fname = $SP_DETAILS_ARR[$x_id]['fullname'];
                                                        $x_Company_Name = $SP_DETAILS_ARR[$x_id]['cname'];
                                                        $x_phone = $SP_DETAILS_ARR[$x_id]['phone'];
                                                        $x_email = $SP_DETAILS_ARR[$x_id]['email'];
                                                        $cStatus = $SP_DETAILS_ARR[$x_id]['cStatus'];
                                                        $cUsertype = $SP_DETAILS_ARR[$x_id]['cUsertype'];
                                                        $admin_approval_status = GetStatusImageString('ADMIN_APPROVAL_STATUS', $cStatus, $x_id, true);

                                            ?>
                                                        <tr>

                                                            <td><?php echo $x_fname; ?></td>
                                                            <td><a href="sp_edit.php?mode=E&id=<?php echo $x_id; ?>" target="_blank" rel="noopener noreferrer"><?php echo $x_Company_Name; ?></a></td>
                                                            <td><?php echo $cUsertype; ?></td>
                                                            <td><?php echo $x_phone; ?></td>
                                                            <td><?php echo $x_email; ?></td>
                                                            <td><?php echo $admin_approval_status; ?></td>

                                                        </tr>
                                            <?php
                                                        $i++;
                                                    }
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
        function GET_COUNTY(id) {
            var data = "response=GET_COUNTYS_BY_STATE&id=" + id;
            $.post(ajax_url2, data,
                function(data, status) {
                    $('#COUNTY_HTML').html(data);
                });

        }
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