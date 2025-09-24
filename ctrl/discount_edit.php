<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../includes/common.php";


$PAGE_TITLE2 = 'Discounts';

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'discount_disp.php';
$edit_url = 'discount_edit.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'A';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
else $mode = 'A';

if ($mode == 'I' || $mode == 'U' || $mode == 'D' || $mode == 'DELLOGO') {
    $user_token = (isset($_POST['user_token'])) ? $_POST['user_token'] : '';
    if (empty($user_token) || $user_token != $sess_user_token) {
        header('location:' . $disp_url);
        exit;
    }
}

$valid_modes = array("A", "I", "E", "U", "D", "DELPIC", "ADD_CLUB_PICS", "UPDATE_IMG");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
    $txtid = '0';
    $txtname = '';
    $txtcode = '';
    $txtperc = '';
    $txtdateFrom = '';
    $txtdateTo = '';
    $txtrank = '';
    $rdstatus = 'A';
    $modalTITLE = 'New ' . $PAGE_TITLE2;
    $form_mode = 'I';
} elseif ($mode == 'I') {
    // DFA($_POST);
    // exit;
    $txtname = db_input2($_POST['txtname']);
    $txtdateFrom = db_input2($_POST['txtdateFrom']);
    $txtdateTo = db_input2($_POST['txtdateTo']);
    // [txtdateFrom] => 10/16/2024
    // [txtdateTo] => 10/30/2024
    $txtdateFrom=explode("/",$txtdateFrom);
    $txtdateFrom=$txtdateFrom[2].'-'.$txtdateFrom[0].'-'.$txtdateFrom[1];
    $txtdateTo=explode("/",$txtdateTo);
    $txtdateTo=$txtdateTo[2].'-'.$txtdateTo[0].'-'.$txtdateTo[1];
    $txtcode = db_input2($_POST['txtcode']);
    $txtperc = db_input2($_POST['txtperc']);
    $rdstatus =  db_input2($_POST['rdstatus']);
    LockTable('discounts');
    $txtid = NextID('iDiscountID', 'discounts');
    $txtrank = GetMaxRank('discounts');
    $q = "INSERT INTO discounts(iDiscountID, vName, vCode,dtFrom,dtTo,fPercentage,iRank,cStatus) VALUES ('$txtid','$txtname','$txtcode','$txtdateFrom','$txtdateTo','$txtperc','$txtrank','$rdstatus')";
    $r = sql_query($q, "USERS.123");
    UnLockTable();

    $desc_str = 'Newly Created: ' . db_input($q);
    LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
    $_SESSION[PROJ_SESSION_ID]->success_info = "Discount Details Successfully Inserted";

    // header("location: $disp_url");
    // exit;
} elseif ($mode == 'E') {
    $dataArr = GetDataFromID("discounts", "iDiscountID", $txtid, " and cStatus!='X'");
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    $txtname = (isset($dataArr[0]->vName)) ? db_output2($dataArr[0]->vName) : '';
    $txtcode = (isset($dataArr[0]->vCode)) ? db_output2($dataArr[0]->vCode) : '';
    $txtdateFrom = (isset($dataArr[0]->dtFrom)) ? db_output2($dataArr[0]->dtFrom) : '';
    $txtdateTo = (isset($dataArr[0]->dtTo)) ? db_output2($dataArr[0]->dtTo) : '';
    $txtperc = (isset($dataArr[0]->fPercentage)) ? db_output2($dataArr[0]->fPercentage) : '';
    $rdstatus = (isset($dataArr[0]->cStatus)) ? db_output2($dataArr[0]->cStatus) : '';
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
} elseif ($mode == 'U') {
    $txtname = db_input2($_POST['txtname']);
    $txtcode = db_input2($_POST['txtcode']);
    $txtdateFrom = db_input2($_POST['txtdateFrom']);
    $txtdateTo = db_input2($_POST['txtdateTo']);
    $txtdateFrom=explode("/",$txtdateFrom);
    $txtdateFrom=$txtdateFrom[2].'-'.$txtdateFrom[0].'-'.$txtdateFrom[1];
    $txtdateTo=explode("/",$txtdateTo);
    $txtdateTo=$txtdateTo[2].'-'.$txtdateTo[0].'-'.$txtdateTo[1];
    $txtperc = db_input2($_POST['txtperc']);
    $rdstatus =  db_input2($_POST['rdstatus']);
    $values = " vName='$txtname', vCode='$txtcode',fPercentage='$txtperc',cStatus='$rdstatus',dtFrom='$txtdateFrom',dtTo='$txtdateTo' ";
    $QUERY = UpdataData('discounts', $values, "iDiscountID=$txtid");

    $_SESSION[PROJ_SESSION_ID]->success_info = "Discount Details Successfully Updated";

    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
    // header("location: $disp_url");
    // exit;
} elseif ($mode == 'D') {
    $disp_flag = (isset($_GET["disp"]) && $_GET["disp"] == "Y") ? true : false;
    $loc_str = $disp_url;
    $chk_arr = array();

    // $chk_arr['Requests'] = GetXFromYID('select count(*) from concrequest where iUserID_request=' . $txtid);
    // $chk_arr['Bookings'] = GetXFromYID('select count(*) from concbooking where iUserID_booking=' . $txtid);
    // $chk_arr['Bookings Details'] = GetXFromYID('select count(*) from concbooking_dat where iUserID_booking=' . $txtid);
    // $chk_arr['Property'] = GetXFromYID('select count(*) from users_property_assoc where iUserID=' . $txtid);
    $chk = array_sum($chk_arr);

    if (!$chk) {
        // $file_name = GetXFromYID("select vLogo from nl_club where iClubID=$txtid");
        // if (!empty($file_name))
        //     DeleteFile($file_name, CLUB_UPLOAD);

        //$txtname = GetXFromYID('select vName from discounts where iDiscountID=' . $txtid);
        //$desc_str = 'Deleted: '.db_input($txtname);
        //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
        $q = "update discounts set cStatus='X' where iDiscountID='$txtid' ";
        sql_query($q);
        //LogMasterEdit($txtid, 'USR', $mode, $txtname);

        //DeleteData('chatbot_hints', 'id', $txtid);
        $_SESSION[PROJ_SESSION_ID]->success_info = "Discount  Deleted Successfully";
    } else
        $_SESSION[PROJ_SESSION_ID]->alert_info = "Discount  Details Could Not Be Deleted Because of Existing " . (CHK_ARR2Str($chk_arr)) . " Dependencies";

    header("location: $edit_url?mode=E&id=$txtid");
    exit;
}

if ($mode == 'I' || $mode == 'U') {
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
                                <div class="card-header"><?php echo $modalTITLE ?></div>
                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <form class="" id="discountForm" name="discountForm" method="post" action="<?php echo $edit_url; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode; ?>">
                                        <input type="hidden" name="add_mode" id="add_mode" value="N">
                                        <input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>">
                                        <!-- TRACK CHANGES -->
                                        <div class="col-md-12">

                                            <div class="form-row">
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="txtname" class="">Name <span class="text-danger">*</span></label>
                                                        <input name="txtname" id="txtname" type="text" value="<?php echo $txtname; ?>" class="form-control form-control-sm radius-30">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="txtcode" class="">Code <span class="text-danger">*</span></label>
                                                        <input name="txtcode" id="txtcode" type="text" value="<?php echo $txtcode; ?>" class="form-control form-control-sm radius-30">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="txtperc" class="">Discount(%) <span class="text-danger">*</span></label>
                                                        <input name="txtperc" onkeypress="return numbersonly(event);" id="txtperc" type="text" value="<?php echo $txtperc; ?>" class="form-control form-control-sm radius-30">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="txtperc" class="">Date From <span class="text-danger">*</span></label>
                                                        <input name="txtdateFrom" id="txtdateFrom" type="text" value="<?php echo date('m/d/Y',strtotime($txtdateFrom)); ?>" class="form-control form-control-sm radius-30" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="position-relative form-group">
                                                        <label for="txtperc" class="">Date To<span class="text-danger">*</span></label>
                                                        <input name="txtdateTo" id="txtdateTo" type="text" value="<?php echo date('m/d/Y',strtotime($txtdateTo)); ?>" class="form-control form-control-sm radius-30" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                                                    </div>
                                                </div>



                                            </div>


                                            <div class="form-row">


                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="rdstatus" class="">Status</label>
                                                    <?php echo FillRadios($rdstatus, 'rdstatus', $STATUS_ARR); ?>
                                                </div>
                                            </div>


                                            <button type="button" onClick="location.href='<?php echo $disp_url; ?>?srch_mode=MEMORY';" class="mt-2 btn btn-warning ">Back</button>
                                            <button type="submit" class="mt-2 btn btn-success">Save</button>
                                            <!-- <button type="button" class="mt-2 btn btn-info" onClick="AddAnother(this.form);">Save & Add Another</button> -->
                                            <?php
                                            if ($mode == 'E' && $txtid) {
                                            ?>
                                                <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','D','<?php echo $txtid; ?>','Discount coupon');" class="mt-2 btn btn-danger">Delete</button>
                                            <?php
                                            }
                                            ?>
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
    <script>
        $(document).ready(function() {
            $('#txtdateFrom').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
            $('#txtdateTo').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
            //fetch_data();
            $("#discountForm").submit(function() {
                err = 0;
                err_arr = new Array();
                ret_val = true;
                var txtname = $('#txtname');
                var txtcode = $('#txtcode');
                var txtperc = $('#txtperc');
                var txtdateFrom = $('#txtdateFrom');
                var txtdateTo = $('#txtdateTo');

                // var amount = $(this).find('#amount');
                //var txtpassword = $(this).find('#txtpassword');


                if ($.trim(txtname.val()) == '') {
                    ShowError(txtname, "Please enter name");
                    err_arr[err] = txtname;
                    err++;
                } else
                    HideError(txtname);

                if ($.trim(txtcode.val()) == '') {
                    ShowError(txtcode, "Please enter code");
                    err_arr[err] = txtcode;
                    err++;
                } else
                    HideError(txtcode);

                if ($.trim(txtperc.val()) == '') {
                    ShowError(txtperc, "Please enter discount percentage");
                    err_arr[err] = txtperc;
                    err++;
                } else
                    HideError(txtperc);

                if (!(txtdateFrom.val())) {
                    ShowError(txtdateFrom, "Please select from date");
                    err_arr[err] = txtdateFrom;
                    err++;
                } else
                    HideError(txtdateFrom);

                if (!(txtdateTo.val())) {
                    ShowError(txtdateTo, "Please select to date");
                    err_arr[err] = txtdateTo;
                    err++;
                } else
                    HideError(txtdateTo);



                // if ($.trim(amount.val()) == 0 || $.trim(amount.val()) == '') {
                //     ShowError(amount, "Please enter Amount for the package");
                //     err_arr[err] = amount;
                //     err++;
                // } else
                //     HideError(amount);


                if (err > 0) {
                    err_arr[0].focus();
                    ret_val = false;
                }

                return ret_val;
            });



        });
    </script>
</body>

</html>