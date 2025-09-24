<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'User';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'user_disp.php';
$edit_url = 'user_edit.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'A';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
else $mode = 'A';

if ($mode == 'I' || $mode == 'U' || $mode == 'D' || $mode == 'DELPIC' || $mode == 'DELSIGNATURE') {
    $user_token = (isset($_POST['user_token'])) ? $_POST['user_token'] : '';
    if (empty($user_token) || $user_token != $sess_user_token) {
        header('location:' . $disp_url);
        exit;
    }
}

$cond = '';
///$PROPERTY_ARR = GetXArrFromYID('select iPropertyID, vName from property where 1' . $cond, '3');

$USER_REF_ID = array();
$valid_modes = array("A", "I", "E", "U", "D", "DELPIC", "DELSIGNATURE");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
    $txtid = '0';
    $txtname = '';
    $txtusername = '';
    $txtpassword = '';
    $txtcode = '';
    $txtemail = '';
    $txtphone = '';
    $file_pic = '';
    $file_signature = '';
    $cmblevel = '';
    $txtdtlogin = '';
    $txtiplogin = '';
    $cmbreftype = 'A';
    $cmbrefid = '0';
    $rdstatus = 'A';
    $txttoken = '';
    $rdactive = 'N';

    $modalTITLE = 'New ' . $PAGE_TITLE2;
    $form_mode = 'I';
    $code_flag = '0';
    $cmbproperty2 = array();
} else if ($mode == 'I') {
    $txtid = NextID('iUserID', 'users');
    $txtname = db_input($_POST['txtname']);
    $txtusername = db_input($_POST['txtusername']);
    $txtpassword = htmlspecialchars_decode($_POST['txtpassword']);
    $txtcode = db_input($_POST['txtcode']);
    $txtemail = db_input($_POST['txtemail']);
    $txtphone = db_input($_POST['txtphone']);
    $cmblevel = db_input($_POST['cmblevel']);
    $cmbreftype = 'A'; //db_input($_POST['cmbreftype']);  
    $cmbrefid = 0; //db_input($_POST['cmbrefid']);  
    $rdstatus =  db_input($_POST['rdstatus']);

    $code_flag = IsUniqueEntry('iUserID', $txtid, 'vUName', $txtusername, 'users');
    if (!$code_flag) $txtusername = SetCode($txtname, 'B');

    $code_flag2 = IsUniqueEntry('iUserID', $txtid, 'vCode', $txtcode, 'users');
    if (!$code_flag2) $txtcode = '';

    $q = "insert into users values ('$txtid', '$txtname', '$txtusername', '$txtpassword', '$txtcode', '$txtemail', '$txtphone', '', '', '$cmblevel', NULL, '', '$cmbreftype', '$cmbrefid', '$rdstatus', '', 'N')";
    $r = sql_query($q, "USERS.123");

    //$desc_str = 'Newly Created: '.db_input($q);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $_SESSION[PROJ_SESSION_ID]->success_info = "User Details Successfully Inserted";
} else if ($mode == 'E') {
    $dataArr = GetDataFromID("users", "iUserID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }

    $txtname = db_output2($dataArr[0]->vName);
    $txtusername = db_output2($dataArr[0]->vUName);
    $txtpassword = db_output2($dataArr[0]->vPassword);
    $txtcode = db_output2($dataArr[0]->vCode);
    $txtemail = db_output2($dataArr[0]->vEmail);
    $txtphone = db_output2($dataArr[0]->vPhone);
    $file_pic = db_output2($dataArr[0]->vPic);
    $file_signature = db_output2($dataArr[0]->vSignature);
    $cmblevel = db_output2($dataArr[0]->iLevel);
    $cmbreftype = db_output2($dataArr[0]->cRefType);
    $cmbrefid = db_output2($dataArr[0]->iRefID);
    $rdstatus = db_output2($dataArr[0]->cStatus);

    // if ($cmbreftype == 'H') $USER_REF_ID = GetXArrFromYID('select iHotelID, vName from gen_hotel', '3');

    // $cmbproperty2 = GetXArrFromYID('select iPropertyID from users_property_assoc where iUserID=' . $txtid);

    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    $txtname = db_input($_POST['txtname']);
    $txtusername = db_input($_POST['txtusername']);
    $txtpassword = htmlspecialchars_decode($_POST['txtpassword']);
    $txtcode = db_input($_POST['txtcode']);
    $txtemail = db_input($_POST['txtemail']);
    $txtphone = db_input($_POST['txtphone']);
    $cmblevel = db_input($_POST['cmblevel']);
    $cmbreftype = 'A'; //db_input($_POST['cmbreftype']);  
    $cmbrefid = 0; //db_input($_POST['cmbrefid']);  
    $rdstatus =  db_input($_POST['rdstatus']);

    $code_flag = IsUniqueEntry('iUserID', $txtid, 'vUName', $txtusername, 'users');
    if (!$code_flag) $txtusername = SetCode($txtname, 'B');

    $code_flag2 = IsUniqueEntry('iUserID', $txtid, 'vCode', $txtcode, 'users');
    if (!$code_flag2) $txtcode = '';

    $pass = '';
    if (!empty($txtpassword))
        $pass = " , vPassword='" . $txtpassword . "'";

    $values = " vName='$txtname', vUName='$txtusername', vEmail='$txtemail', vPhone='$txtphone', iLevel='$cmblevel', cRefType='$cmbreftype', iRefID='$cmbrefid', cStatus='$rdstatus' " . $pass;
    $QUERY = UpdataData('users', $values, "iUserID=$txtid");

    //$desc_str = 'Updated: '.db_input($values);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $_SESSION[PROJ_SESSION_ID]->success_info = "User Details Successfully Updated";
} elseif ($mode == 'DELPIC') {
    $file_name = GetXFromYID("select vPic from users where iUserID=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, USER_UPLOAD);

    UpdateField('users', 'vPic', '', "iUserID=$txtid");

    $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    //$desc_str = 'Deleted: User Image';
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $desc_str = 'Deleted: User Image';
    LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->success_info = "Image Deleted Successfully";

    header("location: $edit_url?mode=E&id=$txtid");
    exit;
} elseif ($mode == 'DELSIGNATURE') {
    $file_name = GetXFromYID("select vSignature from users where iUserID=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, USER_UPLOAD);

    UpdateField('users', 'vSignature', '', "iUserID=$txtid");

    $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    //$desc_str = 'Deleted: User Signature';
    //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $desc_str = 'Deleted: User Signature';
    LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->success_info = "Signature Deleted Successfully";

    header("location: $edit_url?mode=E&id=$txtid");
    exit;
} elseif ($mode == 'D') {
    $disp_flag = (isset($_GET["disp"]) && $_GET["disp"] == "Y") ? true : false;
    $loc_str = $disp_url;

    // $chk_arr['Property'] = GetXFromYID('select count(*) from users_property_assoc where iUserID=' . $txtid);
    // $chk = array_sum($chk_arr);
    $chk_arr=array();

    if (!$chk) {
        $file_name = GetXFromYID("select vPic from users where iUserID=$txtid");
        if (!empty($file_name))
            DeleteFile($file_name, USER_UPLOAD);

        $file_name = GetXFromYID("select vSignature from users where iUserID=$txtid");
        if (!empty($file_name))
            DeleteFile($file_name, USER_UPLOAD);

        $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
        //$desc_str = 'Deleted: '.db_input($txtname);
        //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

        LogMasterEdit($txtid, 'USR', $mode, $txtname);

        DeleteData('users', 'iUserID', $txtid);
        $_SESSION[PROJ_SESSION_ID]->success_info = "User Deleted Successfully";
    } else
        $_SESSION[PROJ_SESSION_ID]->alert_info = "User Details Could Not Be Deleted Because of Existing " . (CHK_ARR2Str($chk_arr)) . " Dependencies";

    header("location: $edit_url?mode=E&id=$txtid");
    exit;
}

if ($mode == "I" || $mode == "U") {
    if (is_uploaded_file($_FILES["file_pic"]["tmp_name"])) {
        $uploaded_pic = $_FILES["file_pic"]["name"];
        $name = basename($_FILES['file_pic']['name']);
        $file_type = $_FILES['file_pic']['type'];
        $size = $_FILES['file_pic']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);

        if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
            $pic_name = GetXFromYID('select vPic from users where iUserID=' . $txtid);

            if (!empty($pic_name))
                DeleteFile($pic_name, USER_UPLOAD);

            if (RANDOMIZE_FILENAME == 0) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name = $txtid . "_UserProfile" . $newname;
            } else
                $pic_name = $txtid . '_UserProfile' . NOW3 . '.' . $extension;

            $tmp_name = "TMP_" . $pic_name;

            $dir = opendir(USER_UPLOAD);
            copy($_FILES["file_pic"]["tmp_name"], USER_UPLOAD . $tmp_name);
            ThumbnailImage($tmp_name, $pic_name, USER_UPLOAD, 640, 480);
            DeleteFile($tmp_name, USER_UPLOAD);
            closedir($dir);   // close the directory

            $q = "update users set vPic='$pic_name' where iUserID=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION[PROJ_SESSION_ID]->error_info = "Profile Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $IMG_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["file_signature"]["tmp_name"])) {
        $uploaded_pic = $_FILES["file_signature"]["name"];
        $name = basename($_FILES['file_signature']['name']);
        $file_type = $_FILES['file_signature']['type'];
        $size = $_FILES['file_signature']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);

        if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
            $pic_name = GetXFromYID('select vSignature from users where iUserID=' . $txtid);

            if (!empty($pic_name))
                DeleteFile($pic_name, USER_UPLOAD);

            if (RANDOMIZE_FILENAME == 0) {
                $newname = NormalizeFilename($uploaded_pic); // normalize the file name
                $pic_name = $txtid . "_UserSignature" . $newname;
            } else
                $pic_name = $txtid . '_UserSignature' . NOW3 . '.' . $extension;

            $tmp_name = "TMP_" . $pic_name;

            $dir = opendir(USER_UPLOAD);
            copy($_FILES["file_signature"]["tmp_name"], USER_UPLOAD . $tmp_name);
            ThumbnailImage($tmp_name, $pic_name, USER_UPLOAD, 640, 480);
            DeleteFile($tmp_name, USER_UPLOAD);
            closedir($dir);   // close the directory

            $q = "update users set vSignature='$pic_name' where iUserID=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $IMG_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    LogMasterEdit($txtid, 'USR', $mode, $txtname);

    // $cmbproperty2 = (isset($_POST['cmbproperty2'])) ? $_POST['cmbproperty2'] : '';
    // sql_query('delete from users_property_assoc where iUserID=' . $txtid);
    // if (!empty($cmbproperty2)) {
    //     foreach ($cmbproperty2 as $p)
    //         sql_query("insert into users_property_assoc values ('$txtid','$p')");
    // }

    $add_mode = (isset($_POST['add_mode'])) ? $_POST['add_mode'] : 'N';
    $loc_str = $edit_url . '?mode=E&id=' . $txtid;
    if ($add_mode == 'Y') $loc_str = $edit_url;

    header("location: $loc_str");
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
                                        <input type="hidden" name="code_flag" id="code_flag" value="<?php echo $code_flag; ?>">
                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode; ?>">
                                        <input type="hidden" name="txtcode" id="txtcode" value="<?php echo $txtcode; ?>">
                                        <input type="hidden" name="add_mode" id="add_mode" value="N">
                                        <input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>">
                                        <!-- TRACK CHANGES -->

                                        <div class="col-md-12">
                                            <!--<div class="form-row">
                    <div class="col-md-6">
                      <div class="position-relative form-group">
                        <label for="cmbreftype" class="">Type <span class="text-danger">*</span></label>
                        <?php //echo FillCombo($cmbreftype, 'cmbreftype', 'COMBO', '0', $USER_REF_TYPE, 'onChange="GetRefDetails(this.value);"','form-control form-control-sm'); 
                        ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="position-relative form-group">
                        <label for="cmbrefid" class="">Ref <span class="text-danger">*</span></label>
                        <span id="REFID_HTML"><?php //echo FillCombo($cmbrefid, 'cmbrefid', 'COMBO', '0', $USER_REF_ID,'data-live-search="true"','form-control form-control-sm'); 
                                                ?></span>
                      </div>
                    </div>
                  </div>-->
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtname" class="">Name <span class="text-danger">*</span></label>
                                                        <input name="txtname" id="txtname" type="text" value="<?php echo $txtname; ?>" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="cmbproperty2" class="">Property <span class="text-danger">*</span></label>
                                                        <?php echo FillMultiCombo($cmbproperty2, 'cmbproperty2', 'COMBO', 'Y', $PROPERTY_ARR, '', 'form-control form-control-sm multiSELECT'); ?>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtphone" class="">Phone <span class="text-danger">*</span></label>
                                                        <input name="txtphone" id="txtphone" type="text" onKeyPress="return numbersonly(event);" value="<?php echo $txtphone; ?>" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtemail" class="">Email</label>
                                                        <input name="txtemail" id="txtemail" type="email" value="<?php echo $txtemail; ?>" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtusername" class="">Username <span class="text-danger">*</span></label>
                                                        <input name="txtusername" id="txtusername" onKeyUp="IsCodeUnique(<?php echo $txtid; ?>, this, 'USERS');" onBlur="IsCodeUnique(<?php echo $txtid; ?>, this, 'USERS');" type="text" value="<?php echo $txtusername; ?>" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="txtpassword" class="">Password <span class="text-danger">*</span></label>
                                                        <input name="txtpassword" id="txtpassword" type="password" value="" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="cmblevel" class="">Level</label>
                                                        <?php echo FillCombo($cmblevel, 'cmblevel', 'COMBO', 'Y', $USER_LEVEL_ARR, 'onchange="ShowReference(this.value);"', 'form-control'); ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="rdstatus" class="">Status</label>
                                                        <?php echo FillRadios($rdstatus, 'rdstatus', $STATUS_ARR); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="avatar-icon-wrapper btn-hover-shine mb-2">
                                                    <label for="file_pic" class="">Profile Image</label>
                                                    <div class="avatar-icon rounded" style="width: 200px; height: 200px;">
                                                        <?php
                                                        $src = NOIMAGE;
                                                        if (IsExistFile($file_pic, USER_UPLOAD))
                                                            $src = USER_PATH . $file_pic;
                                                        ?>
                                                        <img id="imgDiv" src="<?php echo $src; ?>" alt="Avatar">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                if (IsExistFile($file_pic, USER_UPLOAD)) {
                                                ?>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELPIC','<?php echo $txtid; ?>','User Image');" class="mt-2 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <label for="file_pic" class="custom-file-upload mt-3 btn btn-warning"> <i class="fa fa-cloud-upload"></i> Browse </label>
                                                <input id="file_pic" name="file_pic" type="file" class="file-upload form-control-file" onChange="ValidateFileUpload('file_pic','P'); PreviewImage(this)">
                                            </div>
                                            <div class="form-row">
                                                <div class="avatar-icon-wrapper btn-hover-shine mb-2">
                                                    <label for="file_pic" class="">User Signature</label>
                                                    <div class="avatar-icon rounded" style="width: 200px; height: 200px;">
                                                        <?php
                                                        $src = NOIMAGE;
                                                        if (IsExistFile($file_signature, USER_UPLOAD))
                                                            $src = USER_PATH . $file_signature;
                                                        ?>
                                                        <img id="imgDiv2" src="<?php echo $src; ?>" alt="Avatar">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <?php
                                                if (IsExistFile($file_signature, USER_UPLOAD)) {
                                                ?>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELSIGNATURE','<?php echo $txtid; ?>','User Signature');" class="mt-2 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <label for="file_signature" class="custom-file-upload mt-3 btn btn-warning"> <i class="fa fa-cloud-upload"></i> Browse </label>
                                                <input id="file_signature" name="file_signature" type="file" class="form-control-file" onChange="ValidateFileUpload('file_signature','P'); PreviewImage(this,'imgDiv2')">
                                            </div>
                                            <button type="button" onClick="location.href='<?php echo $disp_url; ?>?srch_mode=MEMORY';" class="mt-2 btn btn-secondary">Back</button>
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
                var txtphone = $(this).find('#txtphone');

                var txtusername = $(this).find('#txtusername');
                var txtpassword = $(this).find('#txtpassword');
                var code = $(this).find('#code_flag');

                if ($.trim(txtname.val()) == 0 || $.trim(txtname.val()) == '') {
                    ShowError(txtname, "Please enter name");
                    err_arr[err] = txtname;
                    err++;
                } else
                    HideError(txtname);

                if ($.trim(txtphone.val()) == 0 || $.trim(txtphone.val()) == '') {
                    ShowError(txtphone, "Please enter phone no");
                    err_arr[err] = txtphone;
                    err++;
                } else
                    HideError(txtphone);

                if ($.trim(txtusername.val()) == 0 || $.trim(txtusername.val()) == '') {
                    ShowError(txtusername, "Please enter username");
                    err_arr[err] = txtusername;
                    err++;
                } else
                    HideError(txtusername);

                if (code.val() == '0' && $.trim(txtusername.val()) != '') {
                    ShowError(u, "Username already taken, <br>Please select another username")
                    ret = false;
                }

                if (md != 'E') {
                    if ($.trim(txtpassword.val()) == '') {
                        ShowError(txtpassword, "Please enter password");
                        err_arr[err] = txtpassword;
                        err++;
                    } else
                        HideError(txtpassword);
                }

                if (err > 0) {
                    err_arr[0].focus();
                    ret_val = false;
                } else {
                    if ($.trim(txtpassword.val()) != '') {
                        p_str = GenerateNewPass(b64_md5(txtpassword.val()));
                        txtpassword.val(p_str);
                    }
                }

                return ret_val;
            });



        });
    </script>
</body>

</html>