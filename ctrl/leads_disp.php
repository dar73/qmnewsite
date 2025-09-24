<?php include "../includes/common.php";
$PAGE_TITLE2 = 'Leads';
$MEMORY_TAG = "LEADS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'leads.php';
$edit_url = 'leads_edit.php';
$download_url = 'leads_download.php';


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
$CUSTOMER_ARR = GetXArrFromYID('SELECT iCustomerID,vFirstname FROM customers', '3');
$LEADS_ARR = array();
$GET_AREA_ARRAY = array();
$BUYED_BOOKING_ID = GetIDString2("select ibooking_id from buyed_leads where ivendor_id='$sess_user_id' ");
if (empty($BUYED_BOOKING_ID)) $BUYED_BOOKING_ID = '0';
//DFA($BUYED_BOOKING_ID);
$BUYED_ZIPS = '0';
if (!empty($BUYED_BOOKING_ID)) {
    $BUYED_ZIPS = GetIDString2("select vZip from booking where iBookingID in ($BUYED_BOOKING_ID) ");
}



if(!empty($BUYED_ZIPS)) {
    $_qa = "SELECT 
            z.zip_code,
            c.country_name,
            s.state_name,
            ci.city_name
        FROM 
            zip_codes z
        JOIN 
            cities ci ON z.city_id = ci.city_id
        JOIN 
            states s ON ci.state_id = s.state_id
        JOIN 
            countries c ON ci.country_id = c.country_id where  z.zip_code in ($BUYED_ZIPS) ";
    
    $_qr = sql_query($_qa);
    while ($row = sql_fetch_assoc($_qr)) {
        $zipCode = $row['zip_code'];
        $GET_AREA_ARRAY[$zipCode] = [
            'country' => $row['country_name'],
            'state' => $row['state_name'],
            'city' => $row['city_name'],
        ];
    }
} 


$_q = "SELECT dDate, ivendor_id, ibooking_id, fAmt, vTransactionID FROM buyed_leads WHERE ivendor_id='$sess_user_id' ";
$_r = sql_query($_q, '');
while (list($dDate, $ivendor_id, $ibooking_id, $fAmt, $vTransactionID) = sql_fetch_row($_r)) {
    if (!isset($LEADS_ARR[$ibooking_id]))
        $LEADS_ARR[$ibooking_id] = array('bid' => $ibooking_id, 'Amt' => $fAmt, 'vTransactionID' => $vTransactionID, 'dDate' => $dDate);
}
$dataArr = GetDataFromQuery("select t1.* from appointments t1 inner join buyed_leads t2 on t2.iApptID=t1.iApptID where t2.ivendor_id='$sess_user_id' order by iApptID DESC");

$TIME_ARR = GetXArrFromYID("select Id,title from apptime where 1", "3");

$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
        }
    </style>
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
                            <div class="card qmbgtheme">
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
                                        <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button> -->
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $download_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Download </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Appointment ID</th>
                                                <th>Zip</th>
                                                <th>Country</th>
                                                <th>state</th>
                                                <th>Customer Name</th>
                                                <th>Charge</th>
                                                <th>Schedule</th>
                                                <!-- <th>No of Quotes</th> -->

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SELECT `iBookingID`, `iAreaID`, `iCustomerID`, `vAns1`, `vAns2`, `vAns3`, `vAns4`, `iNo_of_quotes`, `cSelf_schedule` FROM `booking` WHERE 1
                                            if (!empty($dataArr) && !empty($BUYED_ZIPS)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output($dataArr[$u]->iApptID);
                                                    $BID = db_output($dataArr[$u]->iBookingID);
                                                    // $x_areaID = db_output($dataArr[$u]->vZip);
                                                    $x_zip = $dataArr[$u]->vZip;
                                                    $x_state = $GET_AREA_ARRAY[$x_zip]['state'];
                                                    $x_country = $GET_AREA_ARRAY[$x_zip]['country'];

                                                    // $x_num_of_quotes = db_output($dataArr[$u]->iNo_of_quotes);
                                                    $x_customer_name = $CUSTOMER_ARR[$dataArr[$u]->iCustomerID];
                                                    //$x_selfs = db_output($dataArr[0]->cSelf_schedule);
                                                    $x_service_status = $dataArr[$u]->cService_status;
                                                    $APP_DATE = db_output($dataArr[$u]->dDateTime);
                                                    $TIME_ID = db_output($dataArr[$u]->iAppTimeID);

                                                    //$status_str = GetStatusImageString('PACKAGES', $stat, $x_id, true);
                                                    $url = $edit_url . '?id=' . $x_id;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo 'QM-' . $x_id; ?></a></td>
                                                        <td><?php echo $x_zip; ?></td>
                                                        <td><?php echo $x_country; ?></td>
                                                        <td><?php echo $x_state; ?></td>
                                                        <td><?php echo $x_customer_name; ?></td>
                                                        <td>$ <?php echo $LEADS_ARR[$BID]['Amt']; ?></td>
                                                        <td><?php echo date('m-d-Y', strtotime($APP_DATE)) . ' @ ' . $TIME_ARR[$TIME_ID] ?></td>
                                                        <!-- <td><?php //echo $x_num_of_quotes; 
                                                                    ?></td> -->

                                                        <td>
                                                            <a class="btn btn-sm btn-primary" href="<?php echo $url; ?>"> Details</a>
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
        $(document).ready(function() {

            //fetch_data();
            $('#cTable').DataTable({
                responsive: true

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