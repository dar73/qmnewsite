<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/thumbnail.php";

function IsSPAdminApproved($spID)
{
    $q = "select * from service_providers where id='$spID' and cStatus='A' and cAdmin_approval='A' ";
    $r = sql_query($q, "SP.101");
    $response = '';
    if (sql_num_rows($r)) {
        $response = true;
    } else {
        $response = false;
    }
    return $response;
}

$PAGE_TITLE2 = ' Leads';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'booking_admin.php';
$edit_url = 'booking_admin.php';

$execute_query = $is_query = true;
$txtFromD = $txtFromT = $cond = $params = $params2 = $cond2 = '';
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
    $cond .= " and dDate>='$txtFromD' ";
    $execute_query = true;
}
if (!empty($txtFromT)) {
    $cond .= " and dDate<='$txtFromT' ";
    $execute_query = true;
}

if (!empty($cond)) $srch_style = '';

$CUSTOMER_ARR = $ADDRESS_ARR = array();
$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}




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
$record = GetXFromYID("select count(*) from booking where 1   and cStatus='A' ");
$pagi = ceil($record / $per_page);

$q = "select * from booking where 1   and cStatus='A' $cond order by dDate desc limit $start,$per_page ";
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
                <!-- <form action="payanywhere_checkout.php" id="payout" name="payout" method="POST">
                    <input type="hidden" id="BID" name="Bid" value="">
                    <input type="hidden" id="PID" name="pID" value="">
                    <input type="hidden" id="AMT" name="amt" value="">
                    <input type="hidden" id="APPID" name="APPID" value="">
                    <input type="hidden" name="createCheckoutSession" value="1">
                </form> -->
                <!-- Default box -->
                <div class="card card-solid qmbgtheme">
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

                        <div class="row">
                            <?php
                            //if (IsSPAdminApproved($sess_user_id)) {

                                if (sql_num_rows($r)) {
                                    for ($i = 1; $o = sql_fetch_object($r); $i++) {
                                        $Booking_no = $o->iBookingID;
                                        $iNo_of_quotes = $o->iNo_of_quotes;
                                        $iAreaID = $o->iAreaID;
                                        $VERIFY_STATUS = $o->bverified;
                                        $zip = $ADDRESS_ARR[$iAreaID]['zip'];
                                        $state = $ADDRESS_ARR[$iAreaID]['state'];
                                        $county = $ADDRESS_ARR[$iAreaID]['County_name'];
                                        $city = $ADDRESS_ARR[$iAreaID]['city'];
                                        $VERSTR = '';
                                        if($VERIFY_STATUS=='0')
                                        {
                                        $VERSTR= '<span class="text-danger">Not Verified</span>';
                                        }elseif ($VERIFY_STATUS=='1') {
                                        $VERSTR= '<span class="text-success"> Verified</span>';
                                        }

                            ?>

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
                                                                <span class="description-text"><?php echo $VERSTR;?></span>
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


                                                        <!-- /.col -->

                                                        <!-- /.col -->
                                                    </div>
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                State <span class="float-right badge bg-primary"><?php echo $state; ?></span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="#" class="nav-link">
                                                                County <span class="float-right badge bg-info"><?php echo $county; ?></span>
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
                             ?>

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