<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set("memory_limit", -1);
include "../includes/common.php";
include "../includes/thumbnail.php";


$PAGE_TITLE2 = 'Leads';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'booking_admin.php';
$edit_url = 'booking_admin.php';
$booking_edit_url = "booking_edit.php";

$COUNTRY_ARR = GetXArrFromYID("select country_id,country_name from countries where 1", '3');
$STATE_ARR = $CITY_ARR = array();

$STATE_ARR = GetXArrFromYID("SELECT state_id,state_name FROM states where 1 order by state_name", '3');
//SELECT `city_id`, `county_id`, `city_name`, `state_id`, `country_id` FROM `cities` WHERE 1
$CITY_ARR = GetXArrFromYID("SELECT city_id,city_name FROM cities where 1 order by city_name", '3');

$execute_query = $is_query = true;
$txtFromD = $txtFromT = $txtkeyword = $cmbstate  = $cond = $COND2 = $params = $params2 = $cond2 = '';
$country = $stateid = $cityid = '0';
$cmbstatus = 'D';
$params2 = "?start=";
$SP_IDS = array();
$srch_style = 'display:none;';


$FULL_BUYED = GetXArrFromYID("SELECT b.iBookingID
FROM booking b
JOIN appointments a ON b.iBookingID = a.iBookingID
WHERE a.cStatus != 'X'
GROUP BY b.iBookingID
HAVING COUNT(CASE WHEN a.cService_status = 'B' THEN 1 END) = COUNT(*)");

$EXPIRED_LEADS = GetXArrFromYID("SELECT b.iBookingID
FROM booking b
JOIN appointments a ON b.iBookingID = a.iBookingID
WHERE a.cStatus != 'X'
GROUP BY b.iBookingID
HAVING COUNT(CASE WHEN DATE_FORMAT(a.dDateTime,'%Y-%m-%d') <=NOW() THEN 1 END) = COUNT(*)");



if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtFromD = $_POST['txtFromD'];
    $txtFromT = $_POST['txtFromT'];
    $stateid = $_POST['stateid'];
    $country = $_POST['country'];
    $cityid = $_POST['cityid'];
    $cmbstatus = $_POST['cmbstatus'];
    $txtkeyword = $_POST['txtkeyword'];
    $params = '&txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT . '&txtkeyword=' . $txtkeyword . '&stateid=' . $stateid . '&country=' . $country . '&cityid=' . $cityid . '&cmbstatus=' . $cmbstatus;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['txtFromD'])) $txtFromD = $_GET['txtFromD'];
    if (isset($_GET['txtFromT'])) $txtFromT = $_GET['txtFromT'];
    if (isset($_GET['txtkeyword'])) $txtkeyword = $_GET['txtkeyword'];
    if (isset($_GET['stateid'])) $stateid = $_GET['stateid'];
    if (isset($_GET['country'])) $country = $_GET['country'];
    if (isset($_GET['cityid'])) $cityid = $_GET['cityid'];
    if (isset($_GET['cmbstatus'])) $cmbstatus = $_GET['cmbstatus'];
    $params2 = '?srch_mode=QUERY' . '&txtFromD=' . $txtFromD . '&txtFromT=' . $txtFromT . '&txtkeyword=' . $txtkeyword . '&stateid=' . $stateid . '&country=' . $country . '&cityid=' . $cityid . '&cmbstatus' . $cmbstatus . '&start=';
}

if (!empty($txtFromD)) {
    $cond .= " and date_format(dDate,'%Y-%m-%d')>='$txtFromD' ";
    $execute_query = true;
}
if (!empty($txtFromT)) {
    $cond .= " and date_format(dDate,'%Y-%m-%d')<='$txtFromT' ";
    $execute_query = true;
}





if (!empty($txtkeyword)) {
    $txtkeyword = db_input2($txtkeyword);
    $SP_IDS = GetIDString2("select iCustomerID from customers where 1 and vName_of_comapny LIKE '%" . $txtkeyword . "%'");
    // $R = "select iCustomerID from customers where 1 and vName_of_comapny LIKE '%" . $txtkeyword . "%'";
    // echo $R;
    if (empty($SP_IDS) || $SP_IDS == '-1')
        $SP_IDS = '0';

    $execute_query = true;
    $COND2 = " and iCustomerID  in (" . $SP_IDS . ")";
}

if (!empty($stateid) && !empty($country) && !empty($cityid)) {

    $AREA_IDs = GetIDString2("SELECT 
        z.zip_code
    FROM 
        zip_codes z
    JOIN 
        cities ci ON z.city_id = ci.city_id
    JOIN 
        states s ON ci.state_id = s.state_id
    JOIN 
        countries c ON ci.country_id = c.country_id WHERE c.country_id='$country' and s.state_id='$stateid' and ci.city_id='$cityid' ");
    if (!empty($AREA_IDs) && $AREA_IDs != '-1') {
        // Convert to array, trim, and wrap each in single quotes
        $zipArray = array_map(function ($zip) {
            return "'" . trim($zip) . "'";
        }, explode(',', $AREA_IDs));
        $AREA_IDs = implode(',', $zipArray);
    } else {
        $AREA_IDs = "'0'";
    }
    $cond .= " and vZip in (" . $AREA_IDs . ")";
    $execute_query = true;

    $STATE_ARR = GetXArrFromYID("SELECT state_id,state_name FROM states where country_id='$country' order by state_name", '3');
    //SELECT `city_id`, `county_id`, `city_name`, `state_id`, `country_id` FROM `cities` WHERE 1
    $CITY_ARR = GetXArrFromYID("SELECT city_id,city_name FROM cities where country_id='$country'  and state_id='$stateid' order by city_name", '3');
}

//var_dump($state);


if (!empty($cond)) $srch_style = '';

$CUSTOMER_ARR = $ADDRESS_ARR = array();
$zipCodeArray = [];
$CUSTOMER_ARR = GetXArrFromYID("SELECT iCustomerID,CONCAT(vFirstname, ' ', vLastname,' | ',vName_of_comapny) AS full_name FROM customers", '3');
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

//DFA($zipCodeArray);



// print_r($zipCodeArray);

$per_page = 6;
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
$record = GetXFromYID("select count(*) from booking where 1   and cStatus='A' $cond $COND2 ");
$pagi = ceil($record / $per_page);

$q = "select * from booking where 1   and cStatus='A' $cond $COND2 order by dDate desc limit $start,$per_page ";
//echo $q;
$r = sql_query($q, "ERR.88");
//$BOOOKING_DATA = GetDataFromQuery($q);
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

                <div class="card card-solid qmbgtheme">
                    <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="">
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
                                        <div class="wm-100 mrm-50 position-relative form-group m-1">
                                            <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="search by company.." class="form-control" />
                                        </div>
                                        <div class="wm-100 mrm-50  form-group m-1" style="display: none;">
                                            <?php echo FillCombo2022('cmbstatus', $cmbstatus, $REQUEST_STATUS_ARR, 'Status', 'form-control', ''); ?>
                                        </div>
                                        <div class="wm-100 mrm-50  form-group m-1">
                                            <?php echo FillCombo2022('country', $country, $COUNTRY_ARR, 'Country', 'form-control', 'GetStates(this.value);'); ?>
                                        </div>
                                        <div class="wm-100 mrm-50  form-group m-1">

                                            <span id="STATE_DIV">
                                                <?php echo FillCombo2022('stateid', $stateid, $STATE_ARR, 'state', 'form-control', 'GetCity2(this.value);'); ?>
                                            </span>
                                        </div>

                                        <div class="wm-100 mrm-50  form-group m-1">

                                            <span id="CITY_DIV">
                                                <?php echo FillCombo2022('ctyid', $cityid, $CITY_ARR, 'city', 'form-control', 'GetCity2(this.value);'); ?>
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
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                            <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button> -->
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <!-- <button class="stripe-button" id="payButton">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="buttonText">Pay Now</span>
                        </button> -->

                        <div class="row">
                            <?php
                            //if (IsSPAdminApproved($sess_user_id)) {
                            if ($execute_query) {
                                if (sql_num_rows($r)) {
                                    for ($i = 1; $o = sql_fetch_object($r); $i++) {
                                        $Booking_no = $o->iBookingID;
                                        $EURL = $booking_edit_url . '?mode=E&id=' . $Booking_no;
                                        $iNo_of_quotes = $o->iNo_of_quotes;
                                        $iAreaID = $o->iAreaID;
                                        $iCustomerID = $o->iCustomerID;
                                        $dDate = $o->dDate;
                                        $DOB = date('m-d-Y h:i:s A', strtotime($dDate));
                                        $VERIFY_STATUS = $o->bverified;
                                        $zip = $o->vZip;
                                        $state = $zipCodeArray[$zip]['state'];
                                        $country = $zipCodeArray[$zip]['country'];
                                        $city = $zipCodeArray[$zip]['city'];
                                        $VERSTR = '';
                                        $CLASS = "bg-info";
                                        if (in_array($Booking_no, array_keys($FULL_BUYED))) {
                                            $CLASS = "bg-danger";
                                        } elseif (in_array($Booking_no, array_keys($EXPIRED_LEADS))) {
                                            $CLASS = "bg-warning";
                                        }
                                        $CHK = 0;
                                        $checked = '';
                                        if ($VERIFY_STATUS == '0') {
                                            $VERSTR = '<span class="text-danger">Not Verified</span>';
                                            $CHK = 1;
                                        } elseif ($VERIFY_STATUS == '1') {
                                            $checked = 'checked';
                                            $CHK = 0;
                                            $VERSTR = '<span class="text-success"> Verified</span>';
                                        }

                            ?>

                                        <!-- /.col -->
                                        <div class="col-md-4">
                                            <!-- Widget: user widget style 1 -->
                                            <div class="card card-widget widget-user">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header <?php echo $CLASS; ?>">
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
                                                                <span class="description-text"><?php echo $VERSTR; ?></span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-5 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $iNo_of_quotes; ?></h5>
                                                                <span class="description-text"> Appointments</span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <div class="col-sm-5 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo (isset($CUSTOMER_ARR[$iCustomerID])) ? $CUSTOMER_ARR[$iCustomerID] : "NA"; ?></h5>
                                                                <span class="description-text"> COMPANY</span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>
                                                        <div class="col-sm-5 border-right">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $DOB; ?></h5>
                                                                <span class="description-text"> DOB</span>
                                                            </div>
                                                            <!-- /.description-block -->
                                                        </div>


                                                        <!-- /.col -->

                                                        <!-- /.col -->
                                                    </div>
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                Country <span class="float-right badge bg-info"><?php echo $country; ?></span>
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
                                                            <a href="leads.php?id=<?php echo $Booking_no; ?>" class="nav-link">
                                                                <span class="float-right "><button class="btn btn-success">View Details</button></span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item mt-2">
                                                            <button class="btn btn-danger " onclick="DeleteBooking('<?php echo $Booking_no; ?>');"><i class="fa fa-trash"></i></button>
                                                            <button class="btn btn-success " onclick="GoToPage('<?php echo $EURL; ?>');"><i class="fas fa-edit"></i></button>&nbsp;
                                                            <button class="btn btn-warning " onclick="SEND_CALENDAR_ALERT('<?php echo $Booking_no; ?>');"><i class="fas fa-calendar"></i></button>

                                                            <div class="icheck-primary d-inline float-right verifyops">
                                                                <input type="checkbox" onchange="ChangeVerify(this);" id="<?php echo $Booking_no; ?>" value="<?php echo $CHK; ?>" <?php echo $checked; ?>>
                                                                <label for="<?php echo $Booking_no; ?>">Verified/Unverified</label>
                                                            </div>
                                                        </li>


                                                    </ul>


                                                    <!-- /.row -->
                                                </div>

                                            </div>
                                            <!-- /.widget-user -->
                                        </div>
                                        <!-- /.col -->

                                        <!-- /.col -->
                            <?php }
                                }
                            }
                            ?>

                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <!-- <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div> -->
                            </div>
                            <?php if ($execute_query) { ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                        <ul class="pagination">
                                            <?php
                                            for ($i = 1; $i <= $pagi; $i++) {
                                                $class = '';
                                                if ($current_page == $i) { ?>
                                                    <li class="page-item active"><a class="page-link" href="javascript:void(0)"><?php echo $i ?></a></li><?php
                                                                                                                                                        } else { ?>
                                                    <li class="page-item"><a class="page-link" href="<?php echo $params2 . $i ?>"><?php echo $i ?></a></li>
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
                            <?php } ?>
                        </div>

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


        function GetCity2(stateid) {
            //alert(countyid);
            var countryID = $('#country').val();
            var countyid = $('#stateid').val();
            var data = "response=GET_CITY&countyid=0" + "&countryid=" + countryID + "&stateid=" + stateid;

            $.ajax({
                url: ajax_url2,
                method: 'POST',
                data: data,
                success: function(res) {
                    $('#CITY_DIV').html(res);
                }
            });

        }

        function ChangeVerify(obj) {
            console.log($(obj).prop('checked'));
            var M = "";
            if ($(obj).prop('checked')) {
                M = "verified";
            } else {
                M = "unverified";
            }
            var data = 'response=CHANGE_VER_STATUS&bid=' + obj.id + '&value=' + obj.value;
            let text = "Are you sure you want to change Lead status " + obj.id + "? this lead will be mark " + M + " ,you can undo the operation once the correction is done !!";
            if (confirm(text) == true) {
                $.ajax({
                    url: "../includes/ajax.inc.php",
                    method: 'POST',
                    data: data,
                    success: function(res) {
                        //console.log(res);
                        if (!res.error) {
                            alert(res.message);
                            location.reload();

                        } else {
                            alert(res.message);
                            location.reload();
                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }


        function DeleteBooking(id) {
            let text = "Are you sure you want to delete Lead " + id + " ?";
            if (confirm(text) == true) {
                $.ajax({
                    url: "../includes/ajax.inc.php",
                    method: 'POST',
                    data: {
                        response: 'DELETE_BOOKING',
                        id: id,
                    },
                    success: function(res) {
                        //console.log(res);
                        if (!res.error) {
                            alert(res.message);
                            location.reload();

                        } else {
                            alert(res.message);
                            location.reload();
                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }


        function SEND_CALENDAR_ALERT(id) {
            let text = "Are you sure you want to send calendar alert for booking " + id + " ?";
            if (confirm(text) == true) {
                $.ajax({
                    url: "send_calendar_alert.php",
                    method: 'POST',
                    data: {
                        bid: id,
                    },
                    success: function(res) {
                        //console.log(res);
                        if (!res.error) {
                            alert(res.message);
                            //location.reload();

                        } else {
                            alert(res.message);
                            //location.reload();
                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

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