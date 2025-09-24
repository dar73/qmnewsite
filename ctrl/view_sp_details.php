<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Service Provider Info';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'view_sp_details.php';
$edit_url = 'view_sp_details.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['spid'])) $txtid = $_GET['spid'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
//else $mode = 'E';


$cond = '';
$PROPERTY_ARR = array();
$USER_REF_ID = array();
$valid_modes = array("A", "I", "E", "U", "D", "DELLIC", "DELINS");
$RATINGS_ARR = array('5' => 5, '45' => 4.5, '4' => 4.0, '35' => 3.5, '3' => 3.0, '25' => 2.5, '2' => 2.0, '15' => 1.5, '1' => 1.0, '05' => 0.5);
$ratings = array(
    array('value' => '5', 'class' => 'ratingControl-stars ratingControl-stars--5', 'label' => '5'),
    array('value' => '4.5', 'class' => 'ratingControl-stars ratingControl-stars--45 ratingControl-stars--half', 'label' => '45'),
    array('value' => '4', 'class' => 'ratingControl-stars ratingControl-stars--4', 'label' => '4'),
    array('value' => '3.5', 'class' => 'ratingControl-stars ratingControl-stars--35 ratingControl-stars--half', 'label' => '35'),
    array('value' => '3', 'class' => 'ratingControl-stars ratingControl-stars--3', 'label' => '3'),
    array('value' => '2.5', 'class' => 'ratingControl-stars ratingControl-stars--25 ratingControl-stars--half', 'label' => '25'),
    array('value' => '2', 'class' => 'ratingControl-stars ratingControl-stars--2', 'label' => '2'),
    array('value' => '1.5', 'class' => 'ratingControl-stars ratingControl-stars--15 ratingControl-stars--half', 'label' => '15'),
    array('value' => '1', 'class' => 'ratingControl-stars ratingControl-stars--1', 'label' => '1'),
    array('value' => '0.5', 'class' => 'ratingControl-stars ratingControl-stars--05 ratingControl-stars--half', 'label' => '05'),
);
// DFA($ratings);
// foreach ($ratings as $key => $value) {
//     echo $key;
//     echo ($value['value']);
//     # code...
// }
// exit;
// foreach ($ratings as $rating) {
//     $input_attrs = 'type="radio" id="rating-' . str_replace('.', '-', $rating['value']) . '" name="rating" value="' . $rating['value'] . '"';
//     if (isset($rating['checked'])) {
//         $input_attrs .= ' checked';
//     }

//     echo '<input ' . $input_attrs . '>';
//     echo '<label class="' . $rating['class'] . '" for="rating-' . str_replace('.', '-', $rating['value']) . '">' . $rating['label'] . '</label>';
// }
$mode = EnsureValidMode($mode, $valid_modes, "E");

if ($mode == 'E') {
    $dataArr = GetDataFromID("service_providers", "id", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    // DFA($dataArr);
    // exit;

    $First_name = db_output2($dataArr[0]->First_name);
    $Last_name = db_output2($dataArr[0]->Last_name);
    $company_name = db_output2($dataArr[0]->company_name);
    $phone = db_output2($dataArr[0]->phone);
    $email_address = db_output2($dataArr[0]->email_address);
    $license_number = db_output2($dataArr[0]->license_number);
    $vLicence_file = db_output2($dataArr[0]->vLicence_file);
    $dDate_Licence_expiry = db_output2($dataArr[0]->dDate_Licence_expiry);
    $vInsurance_file = db_output2($dataArr[0]->vInsurance_file);
    $dDate_insurance_expiry = db_output2($dataArr[0]->dDate_insurance_expiry);
    $street = db_output2($dataArr[0]->street);
    $state = db_output2($dataArr[0]->state);
    $county = db_output2($dataArr[0]->county);
    $city = db_output2($dataArr[0]->city);
    $vBrochure = db_output2($dataArr[0]->vBrochure);
    $vCertificate1 = db_output2($dataArr[0]->vCertificate1);
    $vCertificate2 = db_output2($dataArr[0]->vCertificate2);
    $vCertificate3 = db_output2($dataArr[0]->vCertificate3);
    $vFblink = db_output2($dataArr[0]->vFblink);
    $vInstalink = db_output2($dataArr[0]->vInstalink);
    $vLinkedInlink = db_output2($dataArr[0]->vLinkedInlink);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $rating = db_output2($dataArr[0]->fGratings);
    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    $First_name = db_input($_POST['First_name']);
    $Last_name = db_input($_POST['Last_name']);
    $company_name = db_input($_POST['company_name']);
    $phone = db_input($_POST['phone']);
    $email_address = db_input($_POST['email_address']);
    //$license_number = db_input($_POST['license_number);
    $dDate_Licence_expiry = db_input($_POST['dDate_Licence_expiry']);
    $dDate_insurance_expiry = db_input($_POST['dDate_insurance_expiry']);
    $street = db_input($_POST['street']);
    $state = db_input($_POST['state_adr']);
    $county = db_input($_POST['county_name_adr']);
    $city = db_input($_POST['city_adr']);
    $vFblink = db_input($_POST['fblink']);
    $vInstalink = db_input($_POST['inlink']);
    $vLinkedInlink = db_input($_POST['lilink']);
    $rating = db_input($_POST['rating']);


    $values = "First_name='$First_name',Last_name='$Last_name',company_name='$company_name',phone='$phone',email_address='$email_address',dDate_Licence_expiry='$dDate_Licence_expiry',dDate_insurance_expiry='$dDate_insurance_expiry',street='$street',state='$state',county='$county',city='$city',vFblink='$vFblink',vInstalink='$vInstalink' ,vLinkedInlink='$vLinkedInlink',fGratings='$rating' ";
    $QUERY = UpdataData('service_providers', $values, "id=$txtid");

    //$desc_str = 'Updated: '.db_input($values);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $_SESSION[PROJ_SESSION_ID]->success_info = "User Details Successfully Updated";
} elseif ($mode == 'DELLIC') {
    $file_name = GetXFromYID("select vLicence_file from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, LICENCE_UPLOAD);

    UpdateField('service_providers', 'vLicence_file', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Image';
    // //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Image';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->success_info = "File Deleted Successfully";

    header("location: $edit_url?mode=E&id=$txtid");
    exit;
} elseif ($mode == 'DELINS') {
    $file_name = GetXFromYID("select vInsurance_file from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, INSURANCE_UPLOAD);

    // UpdateField('service_providers', 'insurance_file', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->success_info = "File Deleted Successfully";

    header("location: $edit_url?mode=E&id=$txtid");
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

if ($mode == "U") {
    //    DFA($_FILES);
    //    exit;
    if (is_uploaded_file($_FILES["vLicence_file"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vLicence_file"]["name"];
        $name = basename($_FILES['vLicence_file']['name']);
        $file_type = $_FILES['vLicence_file']['type'];
        $size = $_FILES['vLicence_file']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/Licence/';
        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vLicence_file from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile($file_name, LICENCE_UPLOAD);

            $file_name = $txtid . '_Licence' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES['vLicence_file']['tmp_name'], LICENCE_UPLOAD . $file_name);
            $q = "update service_providers set vLicence_file='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION['message'] = "Licence File Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $DOC_TYPE))
                $_SESSION['message'] = "Please only upload files that end in types: " . implode(',', $IMG_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["vInsurance_file"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vInsurance_file"]["name"];
        $name = basename($_FILES['vInsurance_file']['name']);
        $file_type = $_FILES['vInsurance_file']['type'];
        $size = $_FILES['vInsurance_file']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/vInsurance_files/';

        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vInsurance_file from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile($file_name, INSURANCE_UPLOAD);


            $file_name = $txtid . '_INSURANCE' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES["vInsurance_file"]["tmp_name"], INSURANCE_UPLOAD . $file_name);


            $q = "update service_providers set vInsurance_file='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $DOC_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["vBrochure"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vBrochure"]["name"];
        $name = basename($_FILES['vBrochure']['name']);
        $file_type = $_FILES['vBrochure']['type'];
        $size = $_FILES['vBrochure']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/brochure/';

        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vBrochure from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile($file_name, BROCHURE_UPLOAD);


            $file_name = $txtid . '_BROCHURE' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES["vBrochure"]["tmp_name"], BROCHURE_UPLOAD . $file_name);


            $q = "update service_providers set vBrochure='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $DOC_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["vCertificate1"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vCertificate1"]["name"];
        $name = basename($_FILES['vCertificate1']['name']);
        $file_type = $_FILES['vCertificate1']['type'];
        $size = $_FILES['vCertificate1']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/brochure/';

        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vCertificate1 from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile($file_name, CERTIFICATE1_UPLOAD);


            $file_name = $txtid . '_CERTIFICATE1' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES["vCertificate1"]["tmp_name"], CERTIFICATE1_UPLOAD . $file_name);


            $q = "update service_providers set vCertificate1='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if ($size > 3000000)
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $DOC_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["vCertificate2"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vCertificate2"]["name"];
        $name = basename($_FILES['vCertificate2']['name']);
        $file_type = $_FILES['vCertificate2']['type'];
        $size = $_FILES['vCertificate2']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/brochure/';

        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vCertificate2 from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile(
                    $file_name,
                    CERTIFICATE2_UPLOAD
                );


            $file_name = $txtid . '_CERTIFICATE2' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES["vCertificate2"]["tmp_name"], CERTIFICATE2_UPLOAD . $file_name);


            $q = "update service_providers set vCertificate2='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if (
                $size > 3000000
            )
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $DOC_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }

    if (is_uploaded_file($_FILES["vCertificate3"]["tmp_name"])) {
        $uploaded_pic = $_FILES["vCertificate3"]["name"];
        $name = basename($_FILES['vCertificate3']['name']);
        $file_type = $_FILES['vCertificate3']['type'];
        $size = $_FILES['vCertificate3']['size'];
        $extension = substr($name, strrpos($name, '.') + 1);
        //$location = '../uploads/brochure/';

        if (IsValidFile($file_type, $extension, 'D') && $size <= 3000000) {
            $file_name = GetXFromYID('select vCertificate3 from service_providers where id=' . $txtid);

            if (!empty($file_name))
                DeleteFile(
                    $file_name,
                    CERTIFICATE3_UPLOAD
                );


            $file_name = $txtid . '_CERTIFICATE3' . uniqidReal() . '.' . $extension;
            move_uploaded_file($_FILES["vCertificate3"]["tmp_name"], CERTIFICATE3_UPLOAD . $file_name);


            $q = "update service_providers set vCertificate3='$file_name' where id=$txtid";
            $r = sql_query($q, 'User.E.126');
        } else {
            if (
                $size > 3000000
            )
                $_SESSION[PROJ_SESSION_ID]->error_info = "Signature Image Could Not Be Uploaded as the File Size is greate then 3MB";
            elseif (!in_array($extension, $IMG_TYPE))
                $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $DOC_TYPE) . ". Please select a new file to upload and submit again.";
        }
    }



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
                                <!-- <div class="card-header"></div> -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>First Name</h6>
                                            <b><?php echo $First_name; ?></b>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>Last Name</h6>
                                            <b><?php echo $Last_name; ?></b>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>Email</h6>
                                            <b><?php echo $email_address; ?></b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>Company Name</h6>
                                            <b><?php echo $company_name; ?></b>
                                        </div>
                                        <div class="col-md-8 col-12 my-2">
                                            <h6>Company Address</h6>
                                            <b><?php echo $street ?></b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>State</h6>
                                            <b><?php echo $state; ?></b>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>County</h6>
                                            <b><?php echo $county; ?></b>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>City</h6>
                                            <b><?php echo $city; ?></b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12 my-2">
                                            <h6>Phone</h6>
                                            <b><?php echo $phone; ?></b>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12 my-2">
                                            <h6><a href="<?php echo $vFblink; ?>">Facebook</a></h6>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6><a href="<?php echo $vInstalink; ?>">Instagram</a></h6>
                                        </div>
                                        <div class="col-md-4 col-12 my-2">
                                            <h6><a href="<?php echo $vLinkedInlink; ?>">LinkedIn</a></h6>
                                        </div>
                                    </div>
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