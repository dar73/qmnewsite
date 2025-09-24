<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../includes/common.php";
include "../includes/thumbnail.php";



$back_url = 'booking.php';

$BID = (isset($_GET['bid'])) ? $_GET['bid'] : '';

if(empty($BID))
{
    header('location:'.$back_url);
    exit;
}

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

$PAGE_TITLE2 = 'Buy Leads';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'buy_leads.php';
$edit_url = 'buy_leads.php';

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

$_qp = "";

$BUYED_BOOKING_ID = GetXArrFromYID("select ibooking_id from buyed_leads where ivendor_id='$sess_user_id' ");
//DFA($BUYED_BOOKING_ID);
if (!empty($BUYED_BOOKING_ID)) {
    $cond2 .= " and iBookingID not in(" . implode(",", $BUYED_BOOKING_ID) . ")";
}

$q = "select * from appointments where 1 and cService_status='P' and iAreaID in (SELECT DISTINCT t1.id FROM areas t1 INNER JOIN service_providers_areas t2 ON t1.zip=t2.zip WHERE 1 AND  t2.service_providers_id='$sess_user_id') " . $cond . $cond2 . "and cStatus='A' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >='".TODAY."' and iBookingID='$BID'  order by dDateTime DESC ";
$r = sql_query($q);
$o = GetDataFromQuery($q);


$CUSTOMER_ARR = array();
$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r)) {
    if (!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
}
// DFA($_SESSION);
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

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
                <form action="payanywhere_checkout.php" id="payout" name="payout" method="POST">
                    <input type="hidden" id="BID" name="Bid" value="">
                    <input type="hidden" id="PID" name="pID" value="">
                    <input type="hidden" id="AMT" name="amt" value="">
                    <input type="hidden" id="APPID" name="APPID" value="">
                    <input type="hidden" name="createCheckoutSession" value="1">
                </form>
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
                        <table class="table " id="buy_leadsTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th> #</th>
                                    <th>
                                        Zip
                                    </th>
                                    <th>Date and Time</th>
                                    <th>
                                        State
                                    </th>
                                    <th>
                                        City
                                    </th>
                                    <th>
                                        County
                                    </th>
                                    <th>Price</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (IsSPAdminApproved($sess_user_id)) {

                                    if (sql_num_rows($r)) {
                                        for ($i = 0,$j=1; $i< sizeof($o); $i++,$j++) {
                                            //DFA($o);
                                            $AMOUNT = 0;
                                            $Leads_Ans = $Leads_Ans2 = array();
                                            $customerID = $o[$i]->iCustomerID;
                                            $Bid = $o[$i]->iBookingID;
                                            $appID = $o[$i]->iApptID;
                                            $date = db_output2($o[$i]->dDateTime);
                                            $TIME_ID = db_output2($o[$i]->iAppTimeID);
                                            $areaID = $o[$i]->iAreaID;
                                            $zip = $ADDRESS_ARR[$areaID]['zip'];
                                            $state = $ADDRESS_ARR[$areaID]['state'];
                                            $county = $ADDRESS_ARR[$areaID]['County_name'];
                                            $city = $ADDRESS_ARR[$areaID]['city'];
                                            $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $Bid . " and  iQuesID not in ('3','8','7','5') order by iQuesID";
                                            $q_r_L_Ans = sql_query($q_L_Ans, '');
                                            if (sql_num_rows($q_r_L_Ans)) {
                                                while ($row = sql_fetch_object($q_r_L_Ans)) {
                                                    $Leads_Ans[] = $row;
                                                }
                                            }
                                            if ($Leads_Ans[0]->iAnswerID == '101' || $Leads_Ans[0]->iAnswerID == '102') {
                                                $AMOUNT = 85;
                                            } elseif ($Leads_Ans[0]->iAnswerID == '103') {
                                                $AMOUNT = 99;
                                            } else {
                                                $AMOUNT = 125;
                                            }
                                            
                                           
                                ?>
                                            <tr>
                                                <td><?php echo $j; ?></td>
                                                <td><?php echo str_pad($zip, 5, '0', STR_PAD_LEFT); ?></td>
                                                <td><?php echo date('m/d/Y', strtotime($date)) . ' @ ' . $TIMEPICKER_ARR[$TIME_ID]; ?></td>
                                                <td><?php echo $state; ?></td>
                                                <td><?php echo $city; ?></td>
                                                <td><?php echo $county; ?></td>
                                                <td>$<?php echo $AMOUNT; ?></td>
                                                <td><button type="button" onclick="ShowInfo('<?php echo $Bid; ?>','<?php echo $appID; ?>');" class="btn btn-sm btn-info">View Info</button> <button type="button" onclick="req('<?php echo $Bid; ?>','<?php echo $sess_user_id; ?>','125','<?php echo $appID; ?>');" data-amt="125" data-Bid="<?php echo $Bid; ?>" data-vID="<?php echo $sess_user_id; ?>" class="stripe-button btn btn-sm btn-primary" id="payButton">
                                                        <i class="fa fa-shopping-cart"></i> Buy Lead
                                                    </button> </td>
                                            </tr>

                                <?php  }
                                    }
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