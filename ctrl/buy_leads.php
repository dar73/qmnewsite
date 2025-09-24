<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";

$back_url = 'booking.php';

$BID = (isset($_GET['bid'])) ? $_GET['bid'] : '';

if (empty($BID)) {
    header('location:' . $back_url);
    exit;
}

// function IsSPAdminApproved($spID)
// {
//     $q = "select * from service_providers where id='$spID' and cStatus='A' and cAdmin_approval='A' ";
//     $r = sql_query($q, "SP.101");
//     $response = '';
//     if (sql_num_rows($r)) {
//         $response = true;
//     } else {
//         $response = false;
//     }
//     return $response;
// }

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


$CUSTOMER_ARR = $ADDRESS_ARR = array();


$BOOKING_DATA = GetDataFromCOND('booking', " and iBookingID='$BID'");
$ZIPCODE = $BOOKING_DATA[0]->vZip;

$_qp = "";

$BUYED_BOOKING_ID = GetXArrFromYID("select ibooking_id from buyed_leads where ivendor_id='$sess_user_id' ");
//DFA($BUYED_BOOKING_ID);
if (!empty($BUYED_BOOKING_ID)) {
    $cond2 .= " and iBookingID not in(" . implode(",", $BUYED_BOOKING_ID) . ")";
}

$q = "select * from appointments where 1 and cService_status='P' and vZip in (SELECT DISTINCT t1.zip_code FROM zip_codes t1 INNER JOIN service_providers_areas t2 ON t1.zip_code=t2.zip WHERE 1 AND  t2.service_providers_id='$sess_user_id') " . $cond . $cond2 . "and cStatus='A' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >='" . TODAY . "' and iBookingID='$BID'  order by dDateTime DESC ";
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

$Leads_Ans = $Leads_Ans2 = array();
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
        countries c ON ci.country_id = c.country_id where  z.zip_code='$ZIPCODE' ";
$_qr = sql_query($_qa);
while ($row = sql_fetch_assoc($_qr)) {
    $zipCode = $row['zip_code'];
    $ADDRESS_ARR[$zipCode] = [
        'country' => $row['country_name'],
        'state' => $row['state_name'],
        'city' => $row['city_name'],
    ];
}



//$BID = GetXFromYID("select iBookingID from appointments where iApptID='$Appid' ");
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}


$_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3')";
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}
// DFA($Leads_Ans2);
// DFA($Leads_Ans);
// DFA($Question_ARR);


$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$SCHEDULE_ARR = GetDataFromID("appointments", "iBookingID", $BID);

$zip = $ZIPCODE;
$state = $ADDRESS_ARR[$SCHEDULE_ARR[0]->vZip]['state'];
$county = $ADDRESS_ARR[$SCHEDULE_ARR[0]->vZip]['country'];
$city = $ADDRESS_ARR[$SCHEDULE_ARR[0]->vZip]['city'];

//DFA($SCHEDULE_ARR);
$BOOKING_ARR = GetDataFromID("booking", "iBookingID", $BID);
$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);

$MODAL_BODY = '<div class="row">
                            <div class="col-12 col-lg-8 order-2 order-lg-1 my-3">
                                
                                <div class="row">
                                    <div class="col-12">';


if (!empty($Leads_Ans)) {
    for ($i = 0; $i < count($Leads_Ans); $i++) {

        $MODAL_BODY .= '<div class="post clearfix pb-0">
                                                    <div class="user-block">
                                                        <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                        <span class="username">
                                                            <a href="#">' . $Question_ARR[$Leads_Ans[$i]->iQuesID] . '</a>
                                                        </span>
                                                        <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p class="ml-5">
                                                    ' . $Ans_ARR[$Leads_Ans[$i]->iAnswerID] . '
                                                    </p>
                                                    <p>
                                                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                    </p>
                                                </div>';
    }
}


if (!empty($Leads_Ans2)) {

    $MODAL_BODY .= '<div class="post clearfix pb-0">
                                            <div class="user-block">
                                                <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                <span class="username">
                                                    <a href="#">' . $Question_ARR[$Leads_Ans2[0]->iQuesID] . '</a>
                                                </span>
                                                <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                            </div>
                                            <!-- /.user-block -->
                                                <p class="ml-5">
                                            ';
    $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
    foreach ($Ansarr as  $value) {
        $MODAL_BODY .= (isset($Ans_ARR[$value])) ? $Ans_ARR[$value] . '<br>' : 'NA';
    }
    $MODAL_BODY .= '</p>
                                            <p>
                                                <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                            </p>
                                        </div>';
}
$MODAL_BODY .= '</div>';
$MODAL_BODY .= '  </div>';
$MODAL_BODY .= '  </div>';








$MODAL_BODY .= '<div class="col-12 col-md-12 col-lg-4 order-1 order-lg-2">';
$MODAL_BODY .= ' <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12"><b>Zip</b></span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">' . $zip . '</b>
                                    </p>';
$MODAL_BODY .= ' <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12"><b>Country</b></span>
                                                                            <b class="d-block ml-lg-0 col-7 col-lg-12">' . $county . '</b>
                                                                        </p>';
$MODAL_BODY .= ' <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12"><b>State</b></span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">' . $state . '</b>
                                    </p>';
$MODAL_BODY .= ' <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12"><b>City</b></span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">' . $city . '</b>
                                    </p>';




$MODAL_BODY .= '</div>
                        </div>';


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

                    <div class="card-header-tab card-header">
                        <h3>Lead Details #<?php echo $BID; ?></h3>
                    </div>
                    <div class="card-body pb-0">
                        <!-- <button class="stripe-button" id="payButton">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="buttonText">Pay Now</span>
                        </button> -->
                        <?php if (true) {

                            if (sql_num_rows($r)) {
                                echo $MODAL_BODY; ?>
                                <table class="table " id="buy_leadsTable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th> #</th>
                                            <th>Date and Time</th>
                                            <th>Price</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($i = 0, $j = 1; $i < sizeof($o); $i++, $j++) { //DFA($o); $AMOUNT=0; $Leads_Ans=$Leads_Ans2=array(); $customerID=$o[$i]->iCustomerID;
                                            $Bid = $o[$i]->iBookingID;
                                            $appID = $o[$i]->iApptID;
                                            $date = db_output2($o[$i]->dDateTime);
                                            $TIME_ID = db_output2($o[$i]->iAppTimeID);
                                            $ZIP_CODE = $o[$i]->vZip;
                                            // $zip = $ADDRESS_ARR[$ZIP_CODE]['zip'];
                                            $state = $ADDRESS_ARR[$ZIP_CODE]['state'];
                                            $county = $ADDRESS_ARR[$ZIP_CODE]['country'];
                                            $city = $ADDRESS_ARR[$ZIP_CODE]['city'];
                                            $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $Bid . " and iQuesID not in ('3','8','7','5') order by iQuesID";
                                            $q_r_L_Ans = sql_query($q_L_Ans, '');
                                            if (sql_num_rows($q_r_L_Ans)) {
                                                while ($row = sql_fetch_object($q_r_L_Ans)) {
                                                    $Leads_Ans[] = $row;
                                                }
                                            }
                                            if ($Leads_Ans[0]->iAnswerID == '101' || $Leads_Ans[0]->iAnswerID == '102') {
                                                $AMOUNT = 85;
                                            } elseif ($Leads_Ans[0]->iAnswerID == '103') {
                                                $AMOUNT = 125;
                                            } else {
                                                $AMOUNT = 125;
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $j; ?></td>

                                                <td><?php echo date('m/d/Y', strtotime($date)) . ' @ ' . $TIMEPICKER_ARR[$TIME_ID]; ?></td>

                                                <td>$<?php echo $AMOUNT; ?></td>
                                              
                                            </tr>



                                        <?php } ?>
                                    </tbody>
                                </table>

                        <?php   }
                        }
                        ?>

                        <?php //echo $MODAL_BODY; 
                        ?>


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

            // $('#buy_leadsTable').DataTable({
            //     responsive: true,
            //     pageLength: 100
            // });

            $("#buy_lead").click(function() {

            });
        });
    </script>
</body>

</html>