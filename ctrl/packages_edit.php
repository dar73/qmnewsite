<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Packages';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'packages_disp.php';
$edit_url = 'packages_edit.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'A';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
else $mode = 'A';


// $Q1 = "select iVendorID from vendor_users where iUserID='$sess_user_id'";
// $R1 = sql_query($Q1);
// list($Vendor_id) = sql_fetch_row($R1);

$valid_modes = array("A", "I", "E", "U", "D", "DELPIC", "ADD_CLUB_PICS", "UPDATE_IMG");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
    $txtid = '0';
    $txtname = '';
    $rdstatus = 'A';
    $num_of_days = '';
    $amount = '';
    $modalTITLE = 'New ' . $PAGE_TITLE2;
    $form_mode = 'I';
} else if ($mode == 'I') {
    $txtname = db_input($_POST['txtname']);
    $numofdays = db_input($_POST['numofdays']);
    $amount = db_input($_POST['amount']);
    $rdstatus =  db_input($_POST['rdstatus']);

    LockTable('packages');
    $txtid = NextID('iPackageID', 'packages');
    $q = "INSERT INTO packages(iPackageID, vPackageName, iNum_Days, fPrice, cStatus) VALUES ('$txtid','$txtname','$num_of_days','$amount','$rdstatus')";
    $r = sql_query($q, "USERS.123");
    UnLockTable();

    // $desc_str = 'Newly Created: '.db_input($q);
    // LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
    $_SESSION[PROJ_SESSION_ID]->success_info = "Package Details Successfully Inserted";
    header("location: $disp_url");
    exit;
} else if ($mode == 'E') {
    $dataArr = GetDataFromID("packages", "iPackageID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }

    $txtname = db_output2($dataArr[0]->vPackageName);
    $num_of_days = db_input2($dataArr[0]->iNum_Days);
    $amount = db_input2($dataArr[0]->fPrice);
    $rdstatus = db_output2($dataArr[0]->cStatus);
    // $seo_title = db_output2($dataArr[0]->vSEO_title);
    // $seo_keywords = db_output2($dataArr[0]->vSEO_keywords);
    // $seo_descr = db_output2($dataArr[0]->vSEO_metadesc);

    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
} else if ($mode == 'U') {

    //$iVendorID = $Vendor_id;
    $txtname = db_input($_POST['txtname']);
    $num_of_days = db_input($_POST['numofdays']);
    $amount = db_input($_POST['amount']);
    $rdstatus =  db_input($_POST['rdstatus']);
    //INSERT INTO `packages`(`iPackageID`, `vPackageName`, `iNum_Days`, `fPrice`, `cStatus`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')


    $values = " iPackageID='$txtid', vPackageName='$txtname', iNum_Days='$num_of_days',  fPrice='$amount', cStatus='$rdstatus' ";
    $QUERY = UpdataData('packages', $values, "iPackageID=$txtid");

    $_SESSION[PROJ_SESSION_ID]->success_info = "Package Details Successfully Updated";
    header("location: $disp_url");
    exit;
} elseif ($mode == 'D') {
    $disp_flag = (isset($_GET["disp"]) && $_GET["disp"] == "Y") ? true : false;
    $loc_str = $disp_url;

    // $chk_arr['Requests'] = GetXFromYID('select count(*) from concrequest where iUserID_request=' . $txtid);
    // $chk_arr['Bookings'] = GetXFromYID('select count(*) from concbooking where iUserID_booking=' . $txtid);
    // $chk_arr['Bookings Details'] = GetXFromYID('select count(*) from concbooking_dat where iUserID_booking=' . $txtid);
    // $chk_arr['Property'] = GetXFromYID('select count(*) from users_property_assoc where iUserID=' . $txtid);
    $chk = array_sum($chk_arr);

    if (!$chk) {
        $file_name = GetXFromYID("select vLogo from nl_club where iClubID=$txtid");
        if (!empty($file_name))
            DeleteFile($file_name, CLUB_UPLOAD);

        $txtname = GetXFromYID('select vName from nl_club where iClubID=' . $txtid);
        //$desc_str = 'Deleted: '.db_input($txtname);
        //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

        LogMasterEdit($txtid, 'USR', $mode, $txtname);

        DeleteData('nl_club', 'iClubID', $txtid);
        $_SESSION[PROJ_SESSION_ID]->success_info = "User Deleted Successfully";
    } else
        $_SESSION[PROJ_SESSION_ID]->alert_info = "User Details Could Not Be Deleted Because of Existing " . (CHK_ARR2Str($chk_arr)) . " Dependencies";

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
                                    <form class="" id="usersForm" name="usersForm" method="post" action="<?php echo $edit_url; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">

                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode; ?>">

                                        <input type="hidden" name="add_mode" id="add_mode" value="N">
                                        <input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>">
                                        <!-- TRACK CHANGES -->

                                        <input type="hidden" name="cmblevel_title" value="Level" />
                                        <input type="hidden" name="cmblevel_arr" value="USER_LEVEL_ARR" />
                                        <input type="hidden" name="rdstatus_old" value="<?php echo $rdstatus; ?>" />
                                        <input type="hidden" name="rdstatus_title" value="Status" />
                                        <input type="hidden" name="rdstatus_arr" value="STATUS_ARR" />
                                        <div class="col-md-12">

                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtname" class="">Name <span class="text-danger">*</span></label>
                                                        <input name="txtname" id="txtname" type="text" value="<?php echo $txtname; ?>" class="form-control form-control-sm radius-30">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">

                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="rdstatus" class="">Num of Days</label>
                                                        <input name="numofdays" id="numofdays" type="number" value="<?php echo $num_of_days; ?>" class="form-control form-control-sm ">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">

                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="rdstatus" class="">Amount</label>
                                                        <input name="amount" id="amount" type="number" value="<?php echo $amount; ?>" class="form-control form-control-sm ">

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-row">

                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="rdstatus" class="">Status</label>
                                                        <?php echo FillRadios($rdstatus, 'rdstatus', $STATUS_ARR); ?>
                                                    </div>
                                                </div>
                                            </div>



                                            <button type="button" onClick="location.href='<?php echo $disp_url; ?>?srch_mode=MEMORY';" class="mt-2 btn btn-warning ">Back</button>
                                            <button type="submit" class="mt-2 btn btn-success">Save</button>
                                            <button type="button" class="mt-2 btn btn-info" onClick="AddAnother(this.form);">Save & Add Another</button>
                                            <?php
                                            if ($mode == 'E' && $txtid) {
                                            ?>
                                                <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','D','<?php echo $txtid; ?>','User');" class="mt-2 btn btn-danger">Delete</button>
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
            //fetch_data();
            $("#usersForm").submit(function() {
                err = 0;
                err_arr = new Array();
                ret_val = true;

                var md = "<?php echo $mode ?>";
                var txtname = $(this).find('#txtname');
                var numofdays = $(this).find('#numofdays');

                var amount = $(this).find('#amount');
                //var txtpassword = $(this).find('#txtpassword');


                if ($.trim(txtname.val()) == 0 || $.trim(txtname.val()) == '') {
                    ShowError(txtname, "Please enter name");
                    err_arr[err] = txtname;
                    err++;
                } else
                    HideError(txtname);

                if ($.trim(numofdays.val()) == 0 || $.trim(numofdays.val()) == '') {
                    ShowError(numofdays, "Please enter Num of days");
                    err_arr[err] = numofdays;
                    err++;
                } else
                    HideError(numofdays);

                if ($.trim(amount.val()) == 0 || $.trim(amount.val()) == '') {
                    ShowError(amount, "Please enter Amount for the package");
                    err_arr[err] = amount;
                    err++;
                } else
                    HideError(amount);


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