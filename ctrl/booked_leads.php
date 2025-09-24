<?php
include "../includes/common.php";
$PAGE_TITLE2 = 'Booked Leads';
$MEMORY_TAG = "BOOKED_LEADS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'booked_leads.php';
$edit_url = 'leads_edit.php';


$execute_query = $is_query = true;
$txtkeyword = $cond = $params = $params2 = '';
$srch_style = 'display:none;';


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
    $cond .= " and (vName LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}

//if($execute_query)
//$srch_style = '';

//$cond .= " and cRefType='A' and cStatus!='X'";
$CUSTOMER_ARR = GetXArrFromYID("SELECT iCustomerID,CONCAT(vFirstname, ' ', vLastname,' | ',vName_of_comapny) AS full_name FROM customers", '3');
$VENDOR_ARR = GetXArrFromYID("SELECT id,CONCAT(First_name, ' ', Last_name,' | ',company_name) AS full_name FROM service_providers", '3');
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3"); //Time array

$GET_AREA_ARRAY = array();
$Q = "SELECT id, zip, zipcode_name, city, state, County_name FROM areas";
$R = sql_query($Q);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($R)) {
    if (!isset($GET_AREA_ARRAY[$id]))
        $GET_AREA_ARRAY[$id] = array('id' => $id, 'zip' => $zip, 'zipcodename' => $zipcode_name, 'state' => $state, 'city' => $city, 'county_name' => $County_name);
}
$dataArr = GetDataFromCOND("appointments", $cond . ' order by iApptID DESC');
$_q = "SELECT t1.iApptID,t1.iBookingID,t2.ivendor_id,t1.iCustomerID,t1.dDateTime,t1.iAppTimeID
FROM appointments t1 INNER JOIN buyed_leads t2 ON t1.iApptID=t2.iApptID
WHERE 1 order by dDate desc ";
$_r = sql_query($_q, "appointment query");

$FEED_BACK_ARRAY = GetXArrFromYID("select iApptID from feedback where cStatus='A' ");



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
                                                        <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="Keywords" class="form-control" />
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
                                                <th>Lead ID</th>
                                                <th>Customer Name</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Provider Name</th>
                                                <!-- <th>No of Quotes</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SELECT `iBookingID`, `iAreaID`, `iCustomerID`, `vAns1`, `vAns2`, `vAns3`, `vAns4`, `iNo_of_quotes`, `cSelf_schedule` FROM `booking` WHERE 1
                                            if (sql_num_rows($_r)) {
                                                for ($u = 0; $o = sql_fetch_object($_r); $u++) {
                                                    // DFA($o);
                                                    // exit;   
                                                    $i = $u + 1;
                                                    $x_id = db_output($o->iApptID);
                                                    $x_iBookingID = db_output($o->iBookingID);
                                                    $x_ivendor_id = db_output($o->ivendor_id);
                                                    $x_iCustomerID = db_output($o->iCustomerID);
                                                    $x_dDateTime = db_output($o->dDateTime);
                                                    $x_iAppTimeID = db_output($o->iAppTimeID);
                                                    $x_customer_name = $CUSTOMER_ARR[$x_iCustomerID];
                                                    $x_provider_name = (isset($VENDOR_ARR[$x_ivendor_id])) ? $VENDOR_ARR[$x_ivendor_id] : 'NA';
                                                    $x_app_date = date('m-d-Y', strtotime($x_dDateTime));
                                                    $x_app_time = $TIMEPICKER_ARR[$x_iAppTimeID];

                                                    //$status_str = GetStatusImageString('PACKAGES', $stat, $x_id, true);
                                                    $url = $edit_url . '?id=' . $x_id;
                                                    $view_feedback = '';
                                                    if (in_array($x_id, $FEED_BACK_ARRAY)) {
                                                        $view_feedback = '<a href="view_feedback.php?appid=' . $x_id . '" target="_blank">view Feedback</a>';
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo 'QM-' . $x_id; ?></a></td>
                                                        <td><?php echo $x_customer_name; ?></td>
                                                        <td><?php echo $x_app_date; ?></td>
                                                        <td><?php echo $x_app_time; ?></td>
                                                        <td><?php echo $x_provider_name; ?></td>
                                                        <td>
                                                            <div><button class="btn btn-success" onclick="sendFeedback('<?php echo $x_id; ?>');" data-booking_id="">Send survey link</button> <?php echo $view_feedback; ?> </div>
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
    <?php include 'load.scripts.php' ?>
    <script>
        function sendFeedback(appid) {
            $.ajax({
                url: '../send_feedback.php',
                method: 'POST',
                data: {
                    appid: appid
                },
                success: function(res) {
                    alert(res.message);
                },
                error: function(err) {
                    console.log(err);
                }
            });

        }

        $(document).ready(function() {

            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                "pageLength": 100

            });

            $(document).on('click', '.send_leads', function() {
                alert($(this).data('booking_id'));
                $.ajax({
                    url: '../api/send_leads.php',
                    method: 'POST',
                    data: {
                        bid: $(this).data('booking_id'),
                    },
                    success: function(res) {
                        console.log(res);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });


            });



        });
    </script>
</body>

</html>