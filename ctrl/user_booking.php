<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'My Booking';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'user_booking.php';
$edit_url = 'user_booking.php';
$sp_info = 'view_sp_details.php';

$execute_query = $is_query = true;
$txtFromD = $txtFromT = $cond = $params = $params2 = '';
$srch_style = 'display:none;';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_SESSION[PROJ_SESSION_ID]->user_id)) $txtid = $_SESSION[PROJ_SESSION_ID]->user_id;
else $mode = 'E';

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtFromD = $_POST['txtFromD'];
    $txtFromT = $_POST['txtFromT'];
    $params = '&txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['txtFromD'])) $txtFromD = $_GET['txtFromD'];
    if (isset($_GET['txtFromT'])) $txtFromT = $_GET['txtFromT'];
    $params2 = '?txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT;
}

if (!empty($txtFromD)) {
    $cond .= " and t1.dDateTime>='$txtFromD' ";
    $execute_query = true;
}
if (!empty($txtFromT)) {
    $cond .= " and t1.dDateTime<='$txtFromT' ";
    $execute_query = true;
}

if (!empty($cond)) $srch_style = '';

$_qp = "";

$q = "select t1.*,t2.ivendor_id as vid from appointments t1 left join buyed_leads t2 on t1.iApptID=t2.iApptID where 1 " . $cond . "and t1.iCustomerID='$sess_user_id' order by t1.iApptID DESC ";
$r = sql_query($q);




$CUSTOMER_ARR = $ADDRESS_ARR = array();

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
        countries c ON ci.country_id = c.country_id where 1 ";
$_qr = sql_query($_qa);
while ($row = sql_fetch_assoc($_qr)) {
    $zipCode = $row['zip_code'];
    $zipCodeArray[$zipCode] = [
        'country' => $row['country_name'],
        'state' => $row['state_name'],
        'city' => $row['city_name'],
    ];
}



// $_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
// $_qr = sql_query($_qa);
// while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
//     if (!isset($ADDRESS_ARR[$id])) {
//         $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
//     }
// }


$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
}
//DFA($CUSTOMER_ARR);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
    <link rel="stylesheet" href="../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .show_data {
            display: none;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php' ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $PAGE_TITLE2; ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                                <li class="breadcrumb-item active"><?php echo $PAGE_TITLE2; ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                <!-- <form action="checkout.php" id="payout" name="payout" method="POST">
                    <input type="hidden" id="BID" name="Bid" value="">
                    <input type="hidden" id="PID" name="pID" value="">
                    <input type="hidden" id="AMT" name="amt" value="">
                    <input type="hidden" name="createCheckoutSession" value="1">
                </form> -->
                <!-- Default box -->
                <div class="card card-solid">
                    <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                        <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                            <div class="app-page-title2">
                                <div class="page-title-wrapper">
                                    <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                        <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromD" id="txtFromD" value="<?php echo $txtFromD; ?>" placeholder="Keywords" class="form-control" />
                                        </div>
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromT" id="txtFromT" value="<?php echo $txtFromT; ?>" placeholder="Keywords" class="form-control" />
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
                    <div class="card-body pb-0">
                        <!-- <button class="stripe-button" id="payButton">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="buttonText">Pay Now</span>
                        </button> -->
                        <table class="table " id="buy_leadsTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th> #</th>
                                    <th>
                                        ID
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Address
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (sql_num_rows($r)) {
                                    for ($i = 1; $o = sql_fetch_object($r); $i++) {
                                        $customerID = $o->iCustomerID;
                                        $Bid = $o->iBookingID;
                                        $x_appID = $o->iApptID;
                                        $x_ZIP = $o->vZip;
                                        $x_vendorID = $o->vid;
                                        $status = $o->cService_status;
                                        $statusStr = $REQUEST_STATUS_ARR[$status];
                                        $Bdate = $o->dDateTime;
                                        $AreaID = $o->iAreaID;
                                        $BookingDate = FormatDate($Bdate, "22");
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo 'QM-' . $x_appID; ?></td>
                                            <td><?php echo $statusStr; ?></td>
                                            <td><?php echo $BookingDate; ?></td>
                                            <td><?php echo str_pad($x_ZIP, 5, '0', STR_PAD_LEFT) . ', ' . $zipCodeArray[$x_ZIP]['city'] . ', ' . $zipCodeArray[$x_ZIP]['state']; ?></td>
                                            <td>
                                                <div>
                                                    <?php

                                                    if ($status == 'X') { ?>
                                                        <button onclick="ShowInfo('<?php echo $x_appID; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button>
                                                    <?php } elseif ($status == 'O') {

                                                    ?>
                                                        <button onclick="ShowInfo('<?php echo $x_appID; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button> <button class="btn btn-block btn-outline-warning btn-sm" onclick="Reschedule('<?php echo $x_appID; ?>');">Reschedule</button> <button class="btn btn-block btn-outline-danger btn-sm" onclick="CancelB('<?php echo $x_appID; ?>');">Cancel</button> <br>
                                                        <a href="<?php echo $sp_info . '?spid=' . $x_vendorID; ?>"><button class="btn btn-block btn-outline-danger btn-sm">View Provider Info</button></a>

                                                    <?php  } else { ?>
                                                        <button onclick="ShowInfo('<?php echo $x_appID; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button> <button class="btn btn-block btn-outline-warning btn-sm" onclick="Reschedule('<?php echo $x_appID; ?>');">Reschedule</button> <button class="btn btn-block btn-outline-danger btn-sm" onclick="CancelB('<?php echo $x_appID; ?>');">Cancel</button>
                                                    <?php }

                                                    ?>

                                                </div>
                                            </td>
                                        </tr>

                                <?php  }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <!-- The Modal -->
        <div class="modal fade" id="Modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="MODAL_TITLE">Leads Info</h4>
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

        <?php include 'load.footer.php' ?>
    </div>
    <?php include 'load.scripts.php' ?>
    <script src="../plugins/bootstrap-material-datetimepicker/js/material.min.js"></script>
    <script src="../plugins/bootstrap-material-datetimepicker/js/moment-with-locales.min.js"></script>
    <script src="../plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
    <script src="../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
    <script>
        function Reschedule(id) {
            //alert(id);
            $.ajax({
                url: '_reschedule.php?mode=view',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(res) {
                    //console.log(res);
                    var ARR = res.split("~~*~~");
                    console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                    $('.datetime').bootstrapMaterialDatePicker({
                        format: 'MM-DD-YYYY',
                        time: false,
                        disabledDays: [6, 7],
                        minDate: '<?php echo GetDaysToblock();  ?>',
                        maxDate: '<?php echo date("m-d-Y", strtotime("+1 month")); ?>',
                    });

                },
                error: function(err) {
                    console.log(err);

                }
            })

        }

        function CancelB(id) {
            if (confirm("Are you sure you want to cancel this request?")) {
                // user clicked OK
                // perform delete operation
                $.ajax({
                    url: '_cancelReschedule.php',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function(res) {
                        console.log(res);
                        if (res == 1) {
                            alert('Your request is cancelled');
                            location.reload();
                        }
                    }

                });
            } else {
                // user clicked Cancel or closed the dialog box
                // do nothing
            }
        }

        function ShowInfo(id) {
            $.ajax({
                url: '_ShowInfomodal.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(res) {
                    console.log(res);
                    var ARR = res.split("~~*~~");
                    console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                }
            });
        }



        $(document).ready(function() {

            $('#buy_leadsTable').DataTable({
                responsive: true
            });

            $(document).on('click', '#Btnupdate', function() {
                $.ajax({
                    url: '_reschedule.php?mode=update',
                    method: 'POST',
                    data: $('#RescheduleFrm').serialize(),
                    success: function(res) {
                        console.log(res);
                        if (res == 1) {
                            alert('Appointments schedule changed successfuly');
                            $('#Modal').modal('hide');
                        }


                    },
                    error: function(err) {
                        console.log(err);

                    }
                })
                //console.log($('#RescheduleFrm').serialize());

            });


        });
    </script>
</body>

</html>