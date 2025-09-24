<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

include "../includes/common.php";
include '../phpmailer.php';

$PAGE_TITLE2 = 'Booking Edit';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'booking_admin.php';
$edit_url = 'booking_edit.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
//else $mode = 'E';
$valid_modes = array("E","U");
$mode = EnsureValidMode($mode, $valid_modes, "E");

if ($mode == 'E') {
    $dataArr = GetDataFromID("booking", "iBookingID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    $BID = db_output2($dataArr[0]->iBookingID);
    $AREA_ID = db_output2($dataArr[0]->iAreaID);
    $CUSTOMERID = db_output2($dataArr[0]->iCustomerID);
    $NOQ = db_output2($dataArr[0]->iNo_of_quotes);
    //$company_name = db_output2($dataArr[0]->cSelf_schedule);
    $CUSTOMER_DATA = GetDataFromCOND('customers', " and iCustomerID=$CUSTOMERID");
    $txtfname = db_output2($CUSTOMER_DATA[0]->vFirstname);
    $txtlname = db_output2($CUSTOMER_DATA[0]->vLastname);
    $txtnameofcompany = db_output2($CUSTOMER_DATA[0]->vName_of_comapny);
    $txtaddress = db_output2($CUSTOMER_DATA[0]->vAddress);
    $txtposition = db_output2($CUSTOMER_DATA[0]->vPosition);
    $txtemail = db_output2($CUSTOMER_DATA[0]->vEmail);
    $txtphone = db_output2($CUSTOMER_DATA[0]->vPhone);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    $dataArr = GetDataFromID("booking", "iBookingID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    $CUSTOMERID = db_output2($dataArr[0]->iCustomerID);
    $txtfname = db_input2($_POST['txtfname']);
    $txtlname = db_input2($_POST['txtlname']);
    $txtnameofcompany = db_input2($_POST['txtcompanyname']);
    $txtaddress = db_input2($_POST['txtaddress']);
    $txtposition = db_input2($_POST['txtposition']);
    //$license_number = db_input($_POST['license_number);
    $txtemail = db_input2($_POST['txtemail']);
    $txtphone = db_input($_POST['txtphone']);

    $values = "vFirstname='$txtfname',vLastname='$txtlname',vName_of_comapny='$txtnameofcompany',vAddress='$txtaddress',vPosition='$txtposition',vEmail='$txtemail',vPhone='$txtphone' ";
    $QUERY = UpdataData('customers', $values, "iCustomerID=$CUSTOMERID");

    //$desc_str = 'Updated: '.db_input($values);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $_SESSION[PROJ_SESSION_ID]->success_info = "Booking Details Successfully Updated";
}

if ($mode == "U") {
    //    DFA($_FILES);
    //    exit;
    header("location: $edit_url?mode=E&id=$txtid");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
</head>
<?php include '_include_form.php' ?>

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
                                <div class="card-header"><h3 class="text-success">Lead #<?php echo $txtid; ?></h3></div>
                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>

                                    <form action="<?php echo $edit_url; ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode ?>">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">First Name</label>
                                                    <input type="text" name="txtfname" id="txtfname" class="form-control" value="<?php echo $txtfname; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Last Name</label>
                                                    <input type="text" name="txtlname" id="txtlname" class="form-control" value="<?php echo $txtlname; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Company Name</label>
                                                    <input type="text" name="txtcompanyname" id="txtcompanyname" class="form-control" value="<?php echo $txtnameofcompany; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Address</label>
                                                    <input type="text" name="txtaddress" id="txtaddress" class="form-control" value="<?php echo $txtaddress; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Position</label>
                                                    <input type="text" name="txtposition" id="txtposition" class="form-control" value="<?php echo $txtposition; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="text" name="txtemail" id="txtemail" class="form-control" value="<?php echo $txtemail; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Phone</label>
                                                    <input type="text" name="txtphone" id="txtphone" class="form-control" value="<?php echo $txtphone; ?>">
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group row mt-4">
                                            <div class="col-sm-10">
                                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>

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
    <script src="../scripts/jquery.blockUI.js"></script>
    <script>
        $(document).ready(function() {
            // $.blockUI({
            //     css: {
            //         border: 'none',
            //         backgroundColor: 'none'
            //     },
            //     message: '<img src="../Images/loading.gif">'
            // });

            //setTimeout($.unblockUI, 2000);
            $(document).on('change', '#state_adr', function() {
                let state = $('#state_adr').val();
                $.ajax({
                    url: '../api/get_countys.php',
                    method: 'POST',
                    data: {
                        state: state,
                        type: 2

                    },
                    success: function(res) {
                        console.log(res);
                        var dataObj = res;
                        $('#county_name_adr').empty();

                        for (i = 0; i < dataObj.length; i++) {
                            var x = document.getElementById("county_name_adr");
                            var option = document.createElement("option");
                            option.text = dataObj[i].county_name;
                            option.value = dataObj[i].county_name;
                            x.add(option);
                        }
                        //$('.duallistbox').bootstrapDualListbox('refresh', true);

                    },
                    error: function(err) {
                        console.log(err);

                    }
                });
            });
            $(document).on('change', '#county_name_adr', function() {
                let county_name = $('#county_name_adr').val();
                let state = $('#state_adr').val();
                //console.log(county_name);
                $.ajax({
                    url: '../api/get_citys2.php',
                    method: 'POST',
                    data: {
                        county_name: county_name,
                        state: state

                    },
                    success: function(res) {
                        // console.log(res);
                        var dataObj = res;
                        $('#city_adr').empty();

                        for (i = 0; i < dataObj.length; i++) {
                            var x = document.getElementById("city_adr");
                            var option = document.createElement("option");
                            option.text = dataObj[i].city;
                            option.value = dataObj[i].city;
                            x.add(option);

                        }
                        //$('.duallistbox').bootstrapDualListbox('refresh', true);

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