<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Notification';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'notification_disp.php';
$edit_url = 'notification_edit.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'A';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
else $mode = 'A';

// if ($mode == 'I' || $mode == 'U' || $mode == 'D' || $mode == 'DELLOGO') {
//     $user_token = (isset($_POST['user_token'])) ? $_POST['user_token'] : '';
//     if (empty($user_token) || $user_token != $sess_user_token) {
//         header('location:' . $disp_url);
//         exit;
//     }
// }


// $Q1 = "select iVendorID from vendor_users where iUserID='$sess_user_id'";
// $R1 = sql_query($Q1);
// list($Vendor_id) = sql_fetch_row($R1);

$valid_modes = array("A", "I", "E", "U", "D", "DELPIC");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
    $txtid = '0';
    $txtitle = '';
    $rdstatus = 'A';
    $txtmessage = '';
    $modalTITLE = 'New ' . $PAGE_TITLE2;
    $form_mode = 'I';
} elseif ($mode == 'I') {
    // DFA($_POST);
    // exit;
    //     CREATE TABLE `notifications` (
    //   `iMessageID` int(11) NOT NULL AUTO_INCREMENT,
    //   `vTitle` varchar(255) DEFAULT NULL,
    //   `vMessage` text DEFAULT NULL,
    //   `iSPID` bigint(20) DEFAULT NULL,
    //   `cStatus` char(1) NOT NULL DEFAULT 'D',
    //   PRIMARY KEY (`iMessageID`)
    // )
    $txtitle = db_input2($_POST['txtitle']);
    $txtmessage = db_input2($_POST['txtmessage']);
    $rdstatus =  db_input2($_POST['rdstatus']);

    LockTable('notifications');
    $txtid = NextID('iMessageID', 'notifications');
    $q = "INSERT INTO notifications(iMessageID, vTitle, vMessage,cStatus) VALUES ('$txtid','$txtitle','$txtmessage','$rdstatus')";
    $r = sql_query($q, "USERS.123");
    UnLockTable();

    // $desc_str = 'Newly Created: '.db_input($q);
    // LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
    $_SESSION[PROJ_SESSION_ID]->success_info = "notification Details Successfully Inserted";

    // header("location: $disp_url");
    // exit;
} elseif ($mode == 'E') {
    $dataArr = GetDataFromID("notifications", "iMessageID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    $txtitle = db_output2($dataArr[0]->vTitle);
    $txtmessage = db_output2($dataArr[0]->vMessage);
    $rdstatus = db_output2($dataArr[0]->cStatus);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
} elseif ($mode == 'U') {
    $txtitle = db_input2($_POST['txtitle']);
    $txtmessage = db_input2($_POST['txtmessage']);
    $rdstatus =  db_input2($_POST['rdstatus']);
    //INSERT INTO `packages`(`iPackageID`, `vPackageName`, `iNum_Days`, `fPrice`, `cStatus`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')
    $values = " vTitle='$txtitle', vMessage='$txtmessage',cStatus='$rdstatus' ";
    $QUERY = UpdataData('notifications', $values, "iMessageID=$txtid");

    $_SESSION[PROJ_SESSION_ID]->success_info = "message Details Successfully Updated";

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

        $txtname = GetXFromYID('select question from chatbot_hints where id=' . $txtid);
        //$desc_str = 'Deleted: '.db_input($txtname);
        //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

        LogMasterEdit($txtid, 'USR', $mode, $txtname);

        DeleteData('chatbot_hints', 'id', $txtid);
        $_SESSION[PROJ_SESSION_ID]->success_info = "Chat  Deleted Successfully";
    } else
        $_SESSION[PROJ_SESSION_ID]->alert_info = "Chat  Details Could Not Be Deleted Because of Existing " . (CHK_ARR2Str($chk_arr)) . " Dependencies";

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
                                    <form class="" id="usersForm" name="usersForm" method="post" action="<?php echo $edit_url; ?>" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode; ?>">
                                        <input type="hidden" name="add_mode" id="add_mode" value="N">
                                        <input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>">
                                        <!-- TRACK CHANGES -->
                                        <div class="col-md-12">

                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtitle" class="">Title <span class="text-danger">*</span></label>
                                                        <input name="txtitle" id="txtitle" type="text" value="<?php echo $txtitle; ?>" class="form-control form-control-sm radius-30">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="txtmessage" class="">Message</label>
                                                        <textarea class="form-control" rows="3" id="txtmessage" name="txtmessage"><?php echo $txtmessage; ?></textarea>

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
                                            <!-- <button type="button" class="mt-2 btn btn-info" onClick="AddAnother(this.form);">Save & Add Another</button> -->
                                            <?php
                                            if ($mode == 'E' && $txtid) {
                                            ?>
                                                <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','D','<?php echo $txtid; ?>','Chat message');" class="mt-2 btn btn-danger">Delete</button>
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
            $('#txtmessage').summernote();
            $("#usersForm").submit(function() {
                err = 0;
                err_arr = new Array();
                ret_val = true;


                var txtquestion = $(this).find('#txtquestion');
                var txtreply = $(this).find('#txtreply');

                // var amount = $(this).find('#amount');
                //var txtpassword = $(this).find('#txtpassword');


                // if ($.trim(txtquestion.val()) == 0 || $.trim(txtquestion.val()) == '') {
                //     ShowError(txtquestion, "Please enter question");
                //     err_arr[err] = txtquestion;
                //     err++;
                // } else
                //     HideError(txtquestion);

                // if ($.trim(txtreply.val()) == 0 || $.trim(txtreply.val()) == '') {
                //     ShowError(txtreply, "Please enter reply for chatbot");
                //     err_arr[err] = txtreply;
                //     err++;
                // } else
                //     HideError(txtreply);

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