<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Buy Leads';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'booking.php';
$edit_url = 'booking.php';

$execute_query = $is_query = false;
$txtFromD = $txtFromT = $cond = $params = $params2 = $cond2 = '';
$srch_style = 'display:none;';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_SESSION[PROJ_SESSION_ID]->user_id)) $txtid = $_SESSION[PROJ_SESSION_ID]->user_id;
else $mode = 'E';

$COUNTRY_ARR = GetXArrFromYID("select country_id,country_name from countries where 1", '3');
$STATE_ARR = $CITY_ARR = array();

$country = $stateid = 0;

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtFromD = $_POST['txtFromD'];
    $txtFromT = $_POST['txtFromT'];
    $country = $_POST['country'];
    $stateid = $_POST['stateid'];
    $params = '&txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT . '&country=' . $country . '&stateid=' . $stateid;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['txtFromD'])) $txtFromD = $_GET['txtFromD'];
    if (isset($_GET['txtFromT'])) $txtFromT = $_GET['txtFromT'];
    if (isset($_GET['country'])) $country = $_GET['country'];
    if (isset($_GET['stateid'])) $stateid = $_GET['stateid'];
    $params2 = '?txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT . '&country=' . $country . '&stateid=' . $stateid;
}

if (!empty($txtFromD)) {
    $cond .= " and dDate>='$txtFromD' ";
    $execute_query = true;
}
if (!empty($txtFromT)) {
    $cond .= " and dDate<='$txtFromT' ";
    $execute_query = true;
}


if (!empty($country) && !empty($stateid)) {
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
        countries c ON ci.country_id = c.country_id where c.country_id='$country' and s.state_id='$stateid' ";
    $_qr = sql_query($_qa);
    while ($row = sql_fetch_assoc($_qr)) {
        $zipCode = $row['zip_code'];
        $zipCodeArray[$zipCode] = [
            'country' => $row['country_name'],
            'state' => $row['state_name'],
            'city' => $row['city_name'],
        ];
    }

    $STATE_ARR = GetXArrFromYID("SELECT state_id,state_name FROM states where country_id='$country' order by state_name", '3');

    $execute_query=true;
}

if (!empty($cond)) $srch_style = '';

$CUSTOMER_ARR = $ADDRESS_ARR = array();


$CUSTOMER_ARR = array();
$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
}
// DFA($_SESSION);
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$_qp = "";

$BUYED_BOOKING_ID = GetXFromYID("select ibooking_id from buyed_leads where ivendor_id='$sess_user_id' ");

//DFA($BUYED_BOOKING_ID);
if (!empty($BUYED_BOOKING_ID)) {
    $cond2 .= " and iBookingID not in($BUYED_BOOKING_ID)";
}

//echo $cond2;
//Booking ids to show
$BIDS_ARR = GetIDString2("select distinct(iBookingID) from appointments where 1 and cService_status='P' and vZip in (SELECT  zip FROM  service_providers_areas  WHERE 1 AND service_providers_id='$sess_user_id') and cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >='" . TODAY . "' order by dDateTime DESC ");
//DFA($BIDS_ARR);
// echo $BIDS_ARR;
// exit;
if (empty($BIDS_ARR) || $BIDS_ARR == '-1')
    $BIDS_ARR = '0';

$per_page = 4;
$start = 0;
$current_page = 1;
if (isset($_GET['start'])) {
    $start = $_GET['start'];
    if ($start <= 0) {
        $start = 0;
        $current_page = 1;
    } else {
        $current_page = $start;
        $start--;
        $start = $start * $per_page;
    }
}
$record = GetXFromYID("select count(*) from booking where 1  and iBookingID  in(" .  $BIDS_ARR . ") and cStatus='A' and bverified='1' $cond2 ");
$pagi = ceil($record / $per_page);

$q = "select * from booking where 1  and iBookingID  in(" .  $BIDS_ARR . ") and cStatus='A' and bverified='1' $cond2 limit $start,$per_page ";
$r = sql_query($q, "ERR.88");
$BOOOKING_DATA = GetDataFromQuery($q);
//DFA($BOOOKING_DATA);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .show_data {
            display: none;
        }

        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
        }


        .blinking-text {
            font-size: 14px !important;
            font-weight: bold;
            color: #BE1E2D;
            /* Use your theme color */
            animation: blink 1s step-start infinite;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
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
                <!-- <form action="payanywhere_checkout.php" id="payout" name="payout" method="POST">
                    <input type="hidden" id="BID" name="Bid" value="">
                    <input type="hidden" id="PID" name="pID" value="">
                    <input type="hidden" id="AMT" name="amt" value="">
                    <input type="hidden" id="APPID" name="APPID" value="">
                    <input type="hidden" name="createCheckoutSession" value="1">
                </form> -->
                <!-- Default box -->
                <div class="card card-solid qmbgtheme">
                    <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="">
                        <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                            <div class="app-page-title2">
                                <div class="page-title-wrapper">
                                    <form class="form-inline p-3" name="frmSearch" id="frmSearch" onsubmit="return VALIDATE_FORM();" action="<?php echo $disp_url; ?>" method="post">
                                        <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromD" id="txtFromD" value="<?php echo $txtFromD; ?>" placeholder="Keywords" class="form-control" />
                                        </div>
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="date" name="txtFromT" id="txtFromT" value="<?php echo $txtFromT; ?>" placeholder="Keywords" class="form-control" />
                                        </div>
                                        <div class="wm-100 mrm-50  form-group m-1">

                                            <?php echo FillCombo2022('country', $country, $COUNTRY_ARR, 'Country', 'form-control', 'GetStates(this.value);',''); ?>
                                        </div>
                                        <div class="wm-100 mrm-50  form-group m-1">

                                            <span id="STATE_DIV">
                                                <?php echo FillCombo2022('stateid', $stateid, $STATE_ARR, 'state', 'form-control', 'GetCity2(this.value);', ''); ?>
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

                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <!-- <button class="stripe-button" id="payButton">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="buttonText">Pay Now</span>
                        </button> -->

                        <h3 class=" m-3">Instructions:</h3>
                        <ul>
                            <li class="blinking-text">Please upgrade to platinum plan to purchase the Leads.</li>
                            <li class="blinking-text">Go to profile section to upgrade to platinum program.</li>
                        </ul>

                        <?php
                        if ($execute_query) {
                            if (!empty($BOOOKING_DATA)) {
                                for ($i = 0; $i < sizeof($BOOOKING_DATA); $i++) {
                                    $Booking_no = $BOOOKING_DATA[$i]->iBookingID;
                                    $iNo_of_quotes = $BOOOKING_DATA[$i]->iNo_of_quotes;
                                    $zip = $BOOOKING_DATA[$i]->vZip;
                                    // $zip = $ADDRESS_ARR[$iAreaID]['zip'];
                                    $state = isset($zipCodeArray[$zip]['state'])? $zipCodeArray[$zip]['state']:'NA';
                                    // $county = $zipCodeArray[$zip]['County_name'];
                                    $city = isset($zipCodeArray[$zip]['city'])? $zipCodeArray[$zip]['city']:'NA';

                        ?>
                                    <div class="row">

                                        <!-- /.col -->
                                        <div class="col-md-4">
                                            <!-- Widget: user widget style 1 -->
                                            <div class="card card-widget widget-user">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header bg-info">
                                                    <h3 class="widget-user-username">Lead ID: <?php echo $Booking_no; ?> </h3>
                                                    <h5 class="widget-user-desc">Zip: <?php echo str_pad($zip, 5, '0', STR_PAD_LEFT); ?></h5>
                                                </div>
                                                <div class="widget-user-image">
                                                    <!-- <img class="img-circle elevation-2" src="../dist/img/user1-128x128.jpg" alt="User Avatar"> -->
                                                </div>
                                                <div class="card-footer">
                                                    <div class="row">
                                                        <div class="col-sm-4 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><span class="info-box-icon"><i class="far fa-thumbs-up"></i></span></h5>
                                                                <span class="description-text">Verified</span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-8 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"></h5>
                                                                <span class="description-text"><strong>Available Appointment Times</strong></span>
                                                                <a href="buy_leads.php?bid=<?php echo $Booking_no; ?>" class="nav-link">
                                                                    <span class="float-right "><button class="btn btn-success">Click here for more details</button></span>
                                                                </a>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>


                                                        <!-- /.col -->

                                                        <!-- /.col -->
                                                    </div>
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                Country <span class="float-right badge bg-info"><?php echo $COUNTRY_ARR[$country]; ?></span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                State <span class="float-right badge bg-primary"><?php echo $state; ?></span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                City <span class="float-right badge bg-success"><?php echo $city; ?></span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <!-- <a href="buy_leads.php?bid=<?php //echo $Booking_no; 
                                                                                            ?>" class="nav-link">
                                                                <span class="float-right "><button class="btn btn-success">Click here for more details</button></span>
                                                            </a> -->
                                                        </li>

                                                    </ul>


                                                    <!-- /.row -->
                                                </div>

                                            </div>
                                            <!-- /.widget-user -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- /.col -->
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-5">
                                            <!-- <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div> -->
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                                <ul class="pagination">
                                                    <?php
                                                    for ($i = 1; $i <= $pagi; $i++) {
                                                        $class = '';
                                                        if ($current_page == $i) { ?>
                                                            <li class="page-item active"><a class="page-link" href="javascript:void(0)"><?php echo $i ?></a></li><?php
                                                                                                                                                                } else { ?>
                                                            <li class="page-item"><a class="page-link" href="?start=<?php echo $i ?>"><?php echo $i ?></a></li>
                                                    <?php }
                                                                                                                                                            } ?>
                                                    <!-- <li class="paginate_button page-item previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
                                                        <li class="paginate_button page-item active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                                        <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                                        <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
                                                        <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
                                                        <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
                                                        <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0" class="page-link">6</a></li>
                                                        <li class="paginate_button page-item next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li> -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                        <?php }
                            }
                        } ?>


                    </div>
                    <!-- /.card-body -->

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
        </div>
        <?php include 'load.footer.php' ?>
    </div>
    <?php include 'load.scripts.php' ?>
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

    <script>
        // Payment request handler
        function req(Bid, providerID, amt, appID) {
            //setLoading(true);
            $('#BID').val(Bid);
            $('#PID').val(providerID);
            $('#AMT').val(amt);
            $('#APPID').val(appID);
            var frm = document.payout;
            frm.submit();


        };

        function GetStates(id) {
            var data = "response=GET_STATES&countryid=" + id;
            $.ajax({
                type: "POST",
                url: ajax_url2,
                data: data,
                success: function(response) {
                    $('#STATE_DIV').html(response);
                }
            });

        }

        function ShowInfo(id, appID) {
            $.ajax({
                url: '_showlead.php',
                method: 'POST',
                data: {
                    id: id,
                    appID: appID
                },
                success: function(res) {
                    //console.log(res);
                    var ARR = res.split("~~*~~");
                    //console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                }
            });
        }

        function VALIDATE_FORM()
        {
            var txtFromD = $('#txtFromD').val();
            var txtFromT = $('#txtFromT').val();
            var country = $('#country').val();
            var stateid = $('#stateid').val();

            if (country == '0') {
                alert('Please select country');
                return false;
            }
            if (stateid == '0') {
                alert('Please select state');
                return false;
            }
            // if (txtFromD == '') {
            //     alert('Please select From Date');
            //     return false;
            // }
            // if (txtFromT == '') {
            //     alert('Please select To Date');
            //     return false;
            // }
            // if (txtFromD > txtFromT) {
            //     alert('To date should be greater than from date');
            //     return false;
            // }


            return true;    
        }



        $(document).ready(function() {

            $('#buy_leadsTable').DataTable({
                responsive: true,
                pageLength: 100
            });

            $("#buy_lead").click(function() {

            });
        });
    </script>
</body>

</html>