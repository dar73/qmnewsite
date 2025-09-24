<?php
include "../includes/common.php";
$PAGE_TITLE2 = 'Leads';
$MEMORY_TAG = "LEADS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'leads.php';
$edit_url = 'leads_edit.php';
$back_url = 'booking.php';
$sp_info = 'sp_edit.php';
///sp_edit.php?mode=E&id=312


$execute_query = $is_query = true;
$txtkeyword = $cond = $params = $params2 = '';
$srch_style = 'display:none;';

$BID = (isset($_GET['id'])) ? $_GET['id'] : '';

if (empty($BID)) {
    header('location:' . $back_url);
    exit;
}

$BZIP=GetXFromYID("select vZip from booking where iBookingID='$BID'");

$BUYED_LEADS = GetXArrFromYID("select iApptID,ivendor_id from buyed_leads where 1", '3');

//if($execute_query)
//$srch_style = '';

//$cond .= " and cRefType='A' and cStatus!='X'";
$CUSTOMER_ARR = GetXArrFromYID('SELECT iCustomerID,vFirstname FROM customers', '3');
$GET_AREA_ARRAY = $zipCodeArray = array();
$_qa = " SELECT 
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
        countries c ON ci.country_id = c.country_id where z.zip_code='$BZIP' ";
$_qr = sql_query($_qa);
while ($row = sql_fetch_assoc($_qr)) {
    $zipCode = $row['zip_code'];
    $zipCodeArray[$zipCode] = [
        'country' => $row['country_name'],
        'state' => $row['state_name'],
        'city' => $row['city_name'],
    ];
}

$GET_AREA_ARRAY = array();

$dataArr = GetDataFromCOND("appointments", $cond . " and cStatus!='X' and iBookingID='$BID' order by iApptID DESC");
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
    <link rel="stylesheet" href="../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
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
                                <div class=" p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
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
                                                <th>Date & Time</th>
                                                <th>Zip</th>
                                                <th>Country</th>
                                                <th>state</th>
                                                <th>Customer Name</th>
                                                <th>Status</th>
                                                <!-- <th>No of Quotes</th> -->

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // SELECT `iBookingID`, `iAreaID`, `iCustomerID`, `vAns1`, `vAns2`, `vAns3`, `vAns4`, `iNo_of_quotes`, `cSelf_schedule` FROM `booking` WHERE 1
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output($dataArr[$u]->iApptID);
                                                    $x_areaid = db_output($dataArr[$u]->iAreaID);
                                                    $x_zip = $dataArr[$u]->vZip;
                                                    $x_state = $zipCodeArray[$x_zip]['state'];
                                                    $country= $zipCodeArray[$x_zip]['country'];
                                                    $date = db_output2($dataArr[$u]->dDateTime);
                                                    $TIME_ID = db_output2($dataArr[$u]->iAppTimeID);
                                                    // $x_num_of_quotes = db_output($dataArr[$u]->iNo_of_quotes);
                                                    $x_customer_name = $CUSTOMER_ARR[$dataArr[$u]->iCustomerID];
                                                    //$x_selfs = db_output($dataArr[0]->cSelf_schedule);
                                                    $x_service_status = $dataArr[$u]->cService_status;
                                                    $statusStr = $REQUEST_STATUS_ARR[$x_service_status];
                                                    $x_vendorID = isset($BUYED_LEADS[$x_id]) ? $BUYED_LEADS[$x_id] : '0';

                                                    //$status_str = GetStatusImageString('PACKAGES', $stat, $x_id, true);
                                                    $url = $edit_url . '?id=' . $x_id;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo 'QM-' . $x_id; ?></a></td>
                                                        <td><?php echo date('m/d/Y', strtotime($date)) . ' @ ' . $TIMEPICKER_ARR[$TIME_ID]; ?></td>
                                                        <td><?php echo $x_zip; ?></td>
                                                        <td><?php echo $country; ?></td>
                                                        <td><?php echo $x_state; ?></td>
                                                        <td><?php echo $x_customer_name; ?></td>
                                                        <td><?php echo $statusStr; ?></td>
                                                        <td>
                                                            <div>
                                                                <button class="btn btn-success send_leads" data-booking_id="<?php echo $x_id; ?>">Send Leads</button>&nbsp;<button class="btn btn-danger" onclick="DeleteLead('<?php echo $x_id; ?>');"><i class="fa fa-trash"></i></button><br><br>

                                                                <?php

                                                                if ($x_service_status == 'X') { ?>
                                                                    <button onclick="ShowInfo('<?php echo $x_id; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button>
                                                                <?php } elseif ($x_service_status == 'B') {

                                                                ?>
                                                                    <button onclick="ShowInfo('<?php echo $x_id; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button> <button class="btn btn-block btn-outline-warning btn-sm" onclick="Reschedule('<?php echo $x_id; ?>');">Reschedule</button> <br>
                                                                    <a href="<?php echo $sp_info . '?mode=E&id=' . $x_vendorID; ?>"><button class="btn btn-block btn-outline-danger btn-sm">View Provider Info</button></a>

                                                                <?php  } else {
                                                                ?>
                                                                    <button onclick="ShowInfo('<?php echo $x_id; ?>');" class="btn btn-block btn-outline-success btn-sm">Info</button> <button class="btn btn-block btn-outline-warning btn-sm" onclick="Reschedule('<?php echo $x_id; ?>');">Reschedule</button>
                                                                    <?php if ($sess_user_level == '1' || $sess_user_level == '0' || $sess_user_level == '7') { ?>
                                                                        <!-- <button class="btn  btn-danger btn-sm mt-2" onclick="ADD_APP('<?php //echo $x_id; 
                                                                                                                                            ?>');">Assign App To SP</button> -->
                                                                        <button class="btn  btn-warning btn-sm mt-2" onclick="ADD_APP2('<?php echo $x_id; ?>');">Credit Lead</button>
                                                                        <button class="btn  btn-info btn-sm mt-2" onclick="ADD_APP3('<?php echo $x_id; ?>');">Assign App To SP</button>
                                                                    <?php } ?>
                                                                <?php }

                                                                ?>
                                                            </div>
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

        <!-- The Modal -->
        <div class="modal fade" id="Modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title text-danger" id="MODAL_TITLE">Leads Info</h4>
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

        <!-- /.content-wrapper -->
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
                    //console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                    $('.datetime').bootstrapMaterialDatePicker({
                        format: 'MM-DD-YYYY',
                        time: false,
                        disabledDays: [6, 7],
                        minDate: '<?php echo date("m-d-Y", strtotime("today"));  ?>',

                    });

                },
                error: function(err) {
                    console.log(err);

                }
            })

        }

        function ShowInfo(id) {
            $.ajax({
                url: '_ShowInfomodal.php',
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
                }
            });
        }

        function DeleteLead(id) {
            let text = "Are you sure you want to delete this Lead?";
            if (confirm(text)) {
                $.ajax({
                    url: '../includes/ajax.inc.php',
                    method: 'POST',
                    data: {
                        response: 'DELETE_LEAD',
                        id: id
                    },
                    success: function(res) {
                        // console.log(res);
                        if (res == 1) {
                            alert('Lead deleted successfuly ');
                            location.reload();

                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }
        }

        function InitiatePayment() {
            //$('#Modal').modal('hide');
            //PageBlock();

            var err = 0;
            var ret_val = true;

            var txtspID = $('#txtspID');
            var title = $('#title');
            var description = $('#description');
            var location = $('#location');

            var data = $('#ADD_CALENDAR_FORM').serialize();
            //console.log(data);

            var APPID = $('#APPID');

            if ($.trim(title.val()) == '') {
                ShowError(title, "Please enter title");
                err++;
            } else {
                HideError(title);
            }

            if ($.trim(txtspID.val()) == '0') {
                ShowError(txtspID, "Please select the SP");
                err++;
            } else {
                HideError(txtspID);
            }

            if ($.trim(description.val()) == '') {
                ShowError(description, "Please enter description");
                err++;
            } else {
                HideError(description);
            }

            if ($.trim(location.val()) == '') {
                ShowError(location, "Please enter location");
                err++;
            } else {
                HideError(location);
            }

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#Modal').modal('hide');
                let text = "Are you sure you want to add the appointment to the SP calendar ? This process might take few minutes be patient dont reload the page!";
                if (confirm(text) == true) {


                    //PageBlock();
                    $.ajax({
                        url: '_add_appointment.php',
                        method: 'POST',
                        async: false,
                        data: data,
                        success: function(res) {
                            console.log(res);
                            var TR_RES = res.split('~');
                            alert(TR_RES[1]);
                            //PageUnBlock();

                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }

            }


        }



        function InitiatePayment2() {
            //$('#Modal').modal('hide');
            //PageBlock();

            var err = 0;
            var ret_val = true;

            var txtspID = $('#txtspID');
            var title = $('#title');
            var description = $('#description');
            var location = $('#location');

            var data = $('#ADD_CALENDAR_FORM').serialize();
            //console.log(data);

            var APPID = $('#APPID');

            if ($.trim(title.val()) == '') {
                ShowError(title, "Please enter title");
                err++;
            } else {
                HideError(title);
            }

            if ($.trim(txtspID.val()) == '0') {
                ShowError(txtspID, "Please select the SP");
                err++;
            } else {
                HideError(txtspID);
            }

            if ($.trim(description.val()) == '') {
                ShowError(description, "Please enter description");
                err++;
            } else {
                HideError(description);
            }

            if ($.trim(location.val()) == '') {
                ShowError(location, "Please enter location");
                err++;
            } else {
                HideError(location);
            }

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#Modal').modal('hide');
                let text = "Are you sure you want to add the appointment to the SP calendar ? This process might take few minutes be patient dont reload the page!";
                if (confirm(text) == true) {


                    //PageBlock();
                    $.ajax({
                        url: '_add_appointment2.php',
                        method: 'POST',
                        async: false,
                        data: data,
                        success: function(res) {
                            console.log(res);
                            var TR_RES = res.split('~');
                            alert(TR_RES[1]);
                            //PageUnBlock();

                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }

            }


        }



        function InitiatePayment3() {
            //$('#Modal').modal('hide');
            //PageBlock();

            var err = 0;
            var ret_val = true;

            var txtspID = $('#txtspID');
            var title = $('#title');
            var description = $('#description');
            var location = $('#location');

            var data = $('#ADD_CALENDAR_FORM').serialize();
            //console.log(data);

            var APPID = $('#APPID');

            if ($.trim(title.val()) == '') {
                ShowError(title, "Please enter title");
                err++;
            } else {
                HideError(title);
            }

            if ($.trim(txtspID.val()) == '0') {
                ShowError(txtspID, "Please select the SP");
                err++;
            } else {
                HideError(txtspID);
            }

            if ($.trim(description.val()) == '') {
                ShowError(description, "Please enter description");
                err++;
            } else {
                HideError(description);
            }

            if ($.trim(location.val()) == '') {
                ShowError(location, "Please enter location");
                err++;
            } else {
                HideError(location);
            }

            if (err > 0) {
                ret_val = false;
            }

            if (ret_val) {
                $('#Modal').modal('hide');
                let text = "Are you sure you want to add the appointment to the SP calendar ? This process might take few minutes be patient dont reload the page!";
                if (confirm(text) == true) {


                    //PageBlock();
                    $.ajax({
                        url: '_add_post_pay_appointment.php',
                        method: 'POST',
                        async: false,
                        data: data,
                        success: function(res) {
                            console.log(res);
                            var TR_RES = res.split('~');
                            alert(TR_RES[1]);
                            //PageUnBlock();

                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }

            }


        }

        function ADD_APP(id) {

            $.ajax({
                url: '_addcalendarModal.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(res) {
                    //console.log(res);
                    var ARR = res.split("~~*~~");
                    //console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                },
                error: function(err) {
                    console.log(err);

                }
            })


        }



        function ADD_APP2(id) {
            $.ajax({
                url: '_addcalendarModal2.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(res) {
                    //console.log(res);
                    var ARR = res.split("~~*~~");
                    //console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                },
                error: function(err) {
                    console.log(err);

                }
            })


        }




        function ADD_APP3(id) {
            $.ajax({
                url: '_addcalendarModal3.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(res) {
                    //console.log(res);
                    var ARR = res.split("~~*~~");
                    //console.log(ARR);
                    $('#Modal').modal('show');
                    $('#MODAL_TITLE').html(ARR[0]);
                    $('#MODAL_BODY').html(ARR[1]);
                },
                error: function(err) {
                    console.log(err);

                }
            })
        }


        $(document).ready(function() {

            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                pageLength: 100


            });

            $(document).on('click', '.send_leads', function() {
                // alert($(this).data('booking_id'));
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

            $(document).on('click', '#Btnupdate', function() {
                $.ajax({
                    url: '_reschedule.php?mode=update',
                    method: 'POST',
                    data: $('#RescheduleFrm').serialize(),
                    success: function(res) {
                        //console.log(res);
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