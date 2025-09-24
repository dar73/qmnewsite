<?php
include "../includes/common.php";
$PAGE_TITLE2 = 'Service providers Coverage report';
$MEMORY_TAG = "SERVICE_PROVIDERS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'sp_coverage_rpt2.php';
$edit_url = 'sp_coverage_rpt2.php';
$excel_url = 'download_sp_coverage_rpt2.php';
$detail_url = 'sp_coverages_rpt.php';


$execute_query = $is_query = false;
$perc = 11.11;
$txtkeyword = $state = $county = $cond = $params = $params2 = '';
$COUNTY_ARR = array();
$SP_IDS_ARR = array();
$srch_style = 'display:none;';


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
    //$cond .= " and (First_name LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}
if (!empty($state)) {
    $cond .= " and vStates='$state' ";
    $execute_query = true;
    $COUNTY_ARR = GetXArrFromYID("SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name", "");
}
if (!empty($county)) {
    $cond .= " and FIND_IN_SET('$county', vCounties) > 0 ";
    $execute_query = true;
}

if ($execute_query)
    $srch_style = '';
if ($execute_query) {
    # code...
    $SP_IDS_ARR = GetXArrFromYID("select iproviderID from coverages where 1 $cond ");
}
//DFA($SP_IDS_ARR);




$EMAIL_VERIFY = array('0' => 'No', '1' => 'Yes');
if (!empty($SP_IDS_ARR)) {
    $dataArr = GetDataFromCOND("service_providers", " and cStatus!='X' and id in ('" . implode("','", $SP_IDS_ARR) . "') order by id");
}

$STATE_ARR = GetXArrFromYID("select DISTINCT state from areas order by state", '2'); // All states
//$COUNTY_ARR = GetXArrFromYID("select DISTINCT County_name from areas  order by County_name ", '2'); // All countys


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
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="">
                                    <div class="app-inner-layout__header page-title-icon-rounded  bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <div class="wm-100 mrm-50  form-group m-1">
                                                        <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="Keywords" class="form-control" />
                                                    </div>
                                                    <div class="wm-300 mrm-50  form-group m-1">


                                                        <label for="state">State</label>
                                                        <select name="state" id="state" class="form-control" onclick="getCountys();">
                                                            <option value="">--select the state--</option>
                                                            <?php
                                                            foreach ($STATE_ARR as $key => $value) {
                                                                $selected = ($state == $value) ? 'selected' : '';

                                                            ?>
                                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                                                            <?php }


                                                            ?>

                                                        </select>

                                                    </div>
                                                    <div class="wm-100 mrm-50  form-group m-1">
                                                        <label for="state">County</label>
                                                        <select id="county" name="county" class="form-control">
                                                            <option value="">--select county--</option>
                                                            <?php foreach ($COUNTY_ARR as $key => $value) {
                                                                $selected = ($county == $value) ? 'selected' : '';

                                                            ?>
                                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                                                            <?php }


                                                            ?>

                                                        </select>
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
                                        <a href="<?php echo $excel_url . $params2; ?>"><button class="btn btn-success">Export to excel</button></a>
                                        <a href="<?php echo $detail_url . $params2; ?>"><button class="btn btn-success">SP All countys PDF</button></a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <h4></h4>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Company Name</th>
                                                <!-- <th>State</th> -->
                                                <!-- <th>County</th> -->
                                                <th>Phone</th>
                                                <th>Email Address</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output($dataArr[$u]->id);
                                                    $x_fname = db_output($dataArr[$u]->First_name);
                                                    $x_lname = db_output($dataArr[$u]->Last_name);
                                                    $x_Company_Name = db_output($dataArr[$u]->company_name);
                                                    $x_phone = db_output($dataArr[$u]->phone);
                                                    $x_email = db_output($dataArr[$u]->email_address);
                                                    $x_emailVerify = db_output($dataArr[$u]->email_verify);
                                                    $stat = $dataArr[$u]->cStatus;
                                                    $x_state = $dataArr[$u]->state;
                                                    $x_county = $dataArr[$u]->county;
                                                    $status_str = GetStatusImageString('SERVICEPROVIDERS', $stat, $x_id, true);
                                                    $url = $edit_url . '?mode=E&id=' . $x_id;
                                                    $PERCENTAGE = calculateProfilePer($x_id);
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo $x_fname; ?></a></td>
                                                        <td><?php echo $x_lname; ?></td>
                                                        <td><?php echo $x_Company_Name; ?></td>
                                                        <!-- <td><?php echo $x_state; ?></td>
                                                        <td><?php echo $x_county; ?></td> -->
                                                        <td><?php echo $x_phone; ?></td>
                                                        <td><?php echo $x_email; ?></td>

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
        function getCountys() {
            var state = $('#state').val();
            $.ajax({
                url: '../api/get_countys.php',
                method: 'POST',
                data: {
                    state: state,
                    type: 2

                },
                success: function(res) {
                    console.log(res);
                    var data = res;
                    $('#county').empty();
                    $('#county').append(`<option value="">--select county---</option>`);
                    for (let index = 0; index < data.length; index++) {
                        $('#county').append(`<option value="${data[index].county_name}">
                                       ${data[index].county_name}
                                  </option>`);

                    }

                }

            })

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