<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
//ini_set('display_startup_errors', 1);

include "../includes/common.php";
include "../includes/thumbnail.php";
// define('GOVTID_UPLOAD', DOCROOT . 'uploads/govtid/');
// define('GOVTID_PATH', SITE_ADDRESS . 'uploads/govtid/');

$ALL_ARRY = array('png', 'pjpeg', 'jpeg', 'jpg', 'JPG', 'txt', 'doc', 'docx', 'pdf', 'xls', 'xlsx');
$FILE_ARR2 = array('png', 'jpeg', 'jpg');

$PAGE_TITLE2 = 'Profile';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'v_profile.php';
$edit_url = 'v_profile.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_SESSION[PROJ_SESSION_ID]->user_id)) $txtid = $_SESSION[PROJ_SESSION_ID]->user_id;
else $mode = 'E';


$VP = GetDataFromID("service_providers", "id", $txtid);
//DFA($VP);
$CUSERTYPE = db_output2($VP[0]->cUsertype);
$RefreshToken = db_output2($VP[0]->vRefreshToken);
$PlatinumAgreement = db_output2($VP[0]->cPlatinumAgreement);

if ($CUSERTYPE == 'P') {
    //step 1
    if (empty($RefreshToken)) {
        header('location:authtoken.php');
        exit;
    }

    if($PlatinumAgreement!='Y')
    {
        header('location:platinum_agreement.php');
        exit;
    }

    //step 2
    $AUTH_GUID = GetXFromYID("select payment_id from transaction2 where payment_status='A' and pid='$txtid'");
    if (empty($AUTH_GUID)) {
        //header('location:void_payment.php');
        //exit;
    }
}



$cond = '';
$PROPERTY_ARR = array();
$USER_REF_ID = array();
$valid_modes = array("A", "I", "E", "U", "D", "DELLIC", "DELINS", "DELBROCHURE", "DELCERTIFICATE1", "DELCERTIFICATE2", "DELCERTIFICATE3", "DELGOVTID");
$mode = EnsureValidMode($mode, $valid_modes, "A");

if ($mode == 'E') {
    $dataArr = GetDataFromID("service_providers", "id", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }

    $licence_check = db_output2($dataArr[0]->iLicence);
    $rdISBI = db_output2($dataArr[0]->cHaveBI);
    $insurance_check = db_output2($dataArr[0]->iInsurance);
    $brochure_check = db_output2($dataArr[0]->iBrochure);
    $certificate1_check = db_output2($dataArr[0]->iCertificate1);
    $certificate2_check = db_output2($dataArr[0]->iCertificate2);
    $awards_check = db_output2($dataArr[0]->iAwards);
    $fblink_check = db_output2($dataArr[0]->iFacebook);
    $instagram_check = db_output2($dataArr[0]->iInstagram);
    $linkdn_check = db_output2($dataArr[0]->iLikendn);

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
    $vWebsite = db_output2($dataArr[0]->vWebsite);
    $vGovtID = db_output2($dataArr[0]->vGovtID);
    $cleaningstatus = db_output2($dataArr[0]->cCleaningStatus);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    // DFA($_POST);
    // exit;
    $licence_check = (isset($_POST['licence_check'])) ? db_input($_POST['licence_check']) : '0';
    $rdISBI = (isset($_POST['rdISBI'])) ? db_input2($_POST['rdISBI']) : '';
    $insurance_check = (isset($_POST['insurance_check'])) ? db_input($_POST['insurance_check']) : '0';
    $brochure_check = (isset($_POST['brochure_check'])) ? db_input($_POST['brochure_check']) : '0';
    $certificate1_check = (isset($_POST['certificate1_check'])) ? db_input($_POST['certificate1_check']) : '0';
    $certificate2_check = (isset($_POST['certificate2_check'])) ? db_input($_POST['certificate2_check']) : '0';
    $awards_check = (isset($_POST['awards_check'])) ? db_input($_POST['awards_check']) : '0';
    $fblink_check = (isset($_POST['fblink_check'])) ? db_input($_POST['fblink_check']) : '0';
    $instagram_check = (isset($_POST['instagram_check'])) ? db_input($_POST['instagram_check']) : '0';
    $linkdn_check = (isset($_POST['linkdn_check'])) ? db_input($_POST['linkdn_check']) : '0';
    $cleaningstatus = isset($_POST['cleaningstatus']) ? db_input($_POST['cleaningstatus']) : 'N';


    $First_name = db_input($_POST['First_name']);
    $Last_name = db_input($_POST['Last_name']);
    $company_name = db_input($_POST['company_name']);
    $phone = db_input($_POST['phone']);
    $email_address = db_input($_POST['email_address']);
    //$license_number = db_input($_POST['license_number);
    $dDate_Licence_expiry = '';
    $dDate_insurance_expiry = db_input($_POST['dDate_insurance_expiry']);
    $street = db_input($_POST['street']);
    $state = db_input($_POST['state_adr']);
    $county = db_input($_POST['county_name_adr']);
    $city = db_input($_POST['city_adr']);
    $vFblink = db_input($_POST['fblink']);
    $vInstalink = db_input($_POST['inlink']);
    $vLinkedInlink = db_input($_POST['lilink']);
    $vWebsite = db_input($_POST['website']);



    $values = "First_name='$First_name',Last_name='$Last_name',company_name='$company_name',phone='$phone',email_address='$email_address',dDate_Licence_expiry='$dDate_Licence_expiry',dDate_insurance_expiry='$dDate_insurance_expiry',street='$street',state='$state',county='$county',city='$city',vFblink='$vFblink',vInstalink='$vInstalink' ,vLinkedInlink='$vLinkedInlink',iInsurance='$insurance_check',iBrochure='$brochure_check',iLicence='$licence_check',iCertificate1='$certificate1_check',iCertificate2='$certificate2_check',iAwards='$awards_check',iFacebook='$fblink_check',iInstagram='$instagram_check',iLikendn='$linkdn_check',vWebsite='$vWebsite',cHaveBI='$rdISBI',cCleaningStatus='$cleaningstatus' ";
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

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
} elseif ($mode == 'DELINS') {
    $file_name = GetXFromYID("select vInsurance_file from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, INSURANCE_UPLOAD);

    UpdateField('service_providers', 'vInsurance_file', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

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
} elseif ($mode == 'DELBROCHURE') {
    $file_name = GetXFromYID("select vBrochure from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, BROCHURE_UPLOAD);

    UpdateField('service_providers', 'vBrochure', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
} elseif ($mode == 'DELCERTIFICATE1') {
    $file_name = GetXFromYID("select vCertificate1 from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, CERTIFICATE1_UPLOAD);

    UpdateField('service_providers', 'vCertificate1', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
} elseif ($mode == 'DELCERTIFICATE2') {
    $file_name = GetXFromYID("select vCertificate2 from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, CERTIFICATE2_UPLOAD);

    UpdateField('service_providers', 'vCertificate2', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
} elseif ($mode == 'DELCERTIFICATE3') {
    $file_name = GetXFromYID("select vCertificate3 from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, CERTIFICATE3_UPLOAD);

    UpdateField('service_providers', 'vCertificate3', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
} elseif ($mode == 'DELGOVTID') {
    $file_name = GetXFromYID("select vGovtID from service_providers where id=$txtid");
    if (!empty($file_name))
        DeleteFile($file_name, GOVTID_UPLOAD);

    UpdateField('service_providers', 'vGovtID', '', "id=$txtid");

    // $txtname = GetXFromYID('select vName from users where iUserID=' . $txtid);
    // //$desc_str = 'Deleted: User Signature';
    // //LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    // $desc_str = 'Deleted: User Signature';
    // LogMasterEdit($txtid, 'USR', $mode, $txtname, $desc_str);

    $_SESSION[PROJ_SESSION_ID]->alert_info = "File Deleted Successfully";

    header("location: $disp_url");
    exit;
}


//Upload files
if ($mode == "U") {
    // DFA($_FILES);
    // exit;
    if (isset($_FILES['vLicence_file'])) {
        if (is_uploaded_file($_FILES["vLicence_file"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vLicence_file"]["name"];
            $name = basename($_FILES['vLicence_file']['name']);
            $file_type = $_FILES['vLicence_file']['type'];
            $size = $_FILES['vLicence_file']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);

            //$location = '../uploads/Licence/';
            // echo $extension;
            // echo 'response=>'.in_array($extension,$ALL_ARRY);
            // exit;
            if (in_array($extension, $ALL_ARRY) && $size <= 500000) {
                $file_name = GetXFromYID('select vLicence_file from service_providers where id=' . $txtid);

                if (!empty($file_name))
                    DeleteFile($file_name, LICENCE_UPLOAD);

                $file_name = $txtid . '_Profile' . uniqidReal() . '.' . $extension;
                move_uploaded_file($_FILES['vLicence_file']['tmp_name'], LICENCE_UPLOAD . $file_name);
                //$dir = opendir(LICENCE_UPLOAD);
                //copy($_FILES['vLicence_file']['tmp_name'], LICENCE_UPLOAD . $file_name);
                convertJpegToWebpThumbnail(LICENCE_UPLOAD . $file_name, LICENCE_UPLOAD . $file_name, 160, 160, $extension); //create webp thumbnails
                //chmod(LICENCE_UPLOAD . $pic_name, 0644);
                //DeleteFile($tmp_name, LICENCE_UPLOAD);
                //DeleteFile($tmp_name2, VENUECOVER_UPLOAD);
                //closedir($dir);   // close the directory
                $q = "update service_providers set vLicence_file='$file_name' where id=$txtid";
                $r = sql_query($q, 'User.E.126');
            } else {

                if ($size > 500000)
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Profile Pic Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $FILE_ARR2))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $FILE_ARR2) . ". Please select a new file to upload and submit again.";
            }
        }
    }


    if (isset($_FILES['vInsurance_file'])) {
        if (is_uploaded_file($_FILES["vInsurance_file"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vInsurance_file"]["name"];
            $name = basename($_FILES['vInsurance_file']['name']);
            $file_type = $_FILES['vInsurance_file']['type'];
            $size = $_FILES['vInsurance_file']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/vInsurance_files/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
                $file_name = GetXFromYID('select vInsurance_file from service_providers where id=' . $txtid);

                if (!empty($file_name))
                    DeleteFile($file_name, INSURANCE_UPLOAD);


                $file_name = $txtid . '_INSURANCE' . uniqidReal() . '.' . $extension;
                move_uploaded_file($_FILES["vInsurance_file"]["tmp_name"], INSURANCE_UPLOAD . $file_name);


                $q = "update service_providers set vInsurance_file='$file_name' where id=$txtid";
                $r = sql_query($q, 'User.E.126');
            } else {
                if ($size > 3000000)
                    $_SESSION[PROJ_SESSION_ID]->error_info = "File  Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $IMG_TYPE))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }


    if (isset($_FILES['vBrochure'])) {
        if (is_uploaded_file($_FILES["vBrochure"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vBrochure"]["name"];
            $name = basename($_FILES['vBrochure']['name']);
            $file_type = $_FILES['vBrochure']['type'];
            $size = $_FILES['vBrochure']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/brochure/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
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
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }


    if (isset($_FILES['vCertificate1'])) {
        if (is_uploaded_file($_FILES["vCertificate1"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vCertificate1"]["name"];
            $name = basename($_FILES['vCertificate1']['name']);
            $file_type = $_FILES['vCertificate1']['type'];
            $size = $_FILES['vCertificate1']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/brochure/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
                $file_name = GetXFromYID('select vCertificate1 from service_providers where id=' . $txtid);

                if (!empty($file_name))
                    DeleteFile($file_name, CERTIFICATE1_UPLOAD);


                $file_name = $txtid . '_CERTIFICATE1' . uniqidReal() . '.' . $extension;
                move_uploaded_file($_FILES["vCertificate1"]["tmp_name"], CERTIFICATE1_UPLOAD . $file_name);


                $q = "update service_providers set vCertificate1='$file_name' where id=$txtid";
                $r = sql_query($q, 'User.E.126');
            } else {
                if ($size > 3000000)
                    $_SESSION[PROJ_SESSION_ID]->error_info = "File Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $IMG_TYPE))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }


    if (isset($_FILES['vCertificate2'])) {

        if (is_uploaded_file($_FILES["vCertificate2"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vCertificate2"]["name"];
            $name = basename($_FILES['vCertificate2']['name']);
            $file_type = $_FILES['vCertificate2']['type'];
            $size = $_FILES['vCertificate2']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/brochure/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
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
                    $_SESSION[PROJ_SESSION_ID]->error_info = "File  Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $IMG_TYPE))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }


    if (isset($_FILES['vCertificate3'])) {
        if (is_uploaded_file($_FILES["vCertificate3"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vCertificate3"]["name"];
            $name = basename($_FILES['vCertificate3']['name']);
            $file_type = $_FILES['vCertificate3']['type'];
            $size = $_FILES['vCertificate3']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/brochure/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
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
                    $_SESSION[PROJ_SESSION_ID]->error_info = "File Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $IMG_TYPE))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }

    if (isset($_FILES['vGovtID'])) {
        if (is_uploaded_file($_FILES["vGovtID"]["tmp_name"])) {
            $uploaded_pic = $_FILES["vGovtID"]["name"];
            $name = basename($_FILES['vGovtID']['name']);
            $file_type = $_FILES['vGovtID']['type'];
            $size = $_FILES['vGovtID']['size'];
            $extension = substr($name, strrpos($name, '.') + 1);
            //$location = '../uploads/brochure/';

            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
                $file_name = GetXFromYID('select vGovtID from service_providers where id=' . $txtid);

                if (!empty($file_name))
                    DeleteFile($file_name, GOVTID_UPLOAD);


                $file_name = $txtid . '_GOVTID' . uniqidReal() . '.' . $extension;
                move_uploaded_file($_FILES["vGovtID"]["tmp_name"], GOVTID_UPLOAD . $file_name);


                $q = "update service_providers set vGovtID='$file_name' where id=$txtid";
                $r = sql_query($q, 'User.E.126');
            } else {
                if (
                    $size > 3000000
                )
                    $_SESSION[PROJ_SESSION_ID]->error_info = "File Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array($extension, $IMG_TYPE))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
        }
    }



    header("location: $disp_url");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php'; ?>
    <style>
        .qmbgtheme {
            background-image: url("../Images/faded-logo-large.png");
            background-repeat: no-repeat;
            background-size: contain;
        }
    </style>
</head>
<?php include '_include_form.php'; ?>

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
                            <div class="card qmbgtheme">
                                <div class="card-header"><?php echo $modalTITLE ?></div>
                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>

                                    <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">

                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode ?>">
                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputName" class=" col-form-label">First Name</label>
                                                    <input type="text" class="form-control" name="First_name" value="<?php echo $First_name; ?>" placeholder="First Name">

                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="col-form-label">Last Name</label>
                                                    <input type="text" class="form-control" name="Last_name" value="<?php echo $Last_name; ?>" placeholder="Last Name">

                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group ">
                                                    <label for="inputName" class=" col-form-label">Email</label>
                                                    <input type="text" class="form-control" name="email_address" value="<?php echo $email_address; ?>" placeholder="Email">

                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="inputName2" class=" col-form-label">Company Name</label>
                                                    <input type="text" class="form-control" name="company_name" value="<?php echo $company_name; ?>" placeholder="Company Name">

                                                </div>

                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="comment">Company Address (Kindly provide actual address and not a post box address )</label>
                                            <input type="text" name="street" id="street" placeholder="Enter your street" value="<?php echo $street ?>" class="form-control">
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="form-group">

                                                    <label for="comment">Please select State</label>
                                                    <select name="state_adr" class="form-control select2" data-placeholder="Select a State" id="state_adr">
                                                        <option value="">--select--</option>

                                                        <?php
                                                        $q = "SELECT DISTINCT(state) FROM `areas`";
                                                        $r = sql_query($q);
                                                        while ($a = sql_fetch_assoc($r)) {
                                                            $selected = ($a['state'] == $state) ? 'selected' : '';
                                                            echo '<option value="' . $a['state'] . '"' . $selected . ' >' . $a['state'] . '</option>';
                                                        }

                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="state">Please select the county </label>
                                                    <select name="county_name_adr" class="form-control select2" data-placeholder="Select a County" id="county_name_adr">
                                                        <option value="">--select--</option>

                                                        <?php
                                                        $q = "SELECT DISTINCT(County_name) FROM `areas` where state='$state'";
                                                        $r = sql_query($q);
                                                        while ($a = sql_fetch_assoc($r)) {
                                                            $selected = ($a['County_name'] == $county) ? 'selected' : '';
                                                            echo '<option value="' . $a['County_name'] . '"' . $selected . ' >' . $a['County_name'] . '</option>';
                                                        }

                                                        ?>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="state">Please select the city</label>
                                                    <select name="city_adr" class="form-control select2" data-placeholder="Select a City" id="city_adr">
                                                        <option value="">--select--</option>

                                                        <?php
                                                        $q = "SELECT DISTINCT(city) FROM areas where state='$state' and County_name='$county' ";
                                                        $r = sql_query($q);
                                                        while ($a = sql_fetch_assoc($r)) {
                                                            $selected = ($a['city'] == $city) ? 'selected' : '';
                                                            echo '<option value="' . $a['city'] . '"' . $selected . ' >' . $a['city'] . '</option>';
                                                        }

                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="inputSkills" class=" col-12 col-form-label">Phone</label>
                                                    <input type="tel" class="form-control w-auto" value="<?php echo $phone; ?>" name="phone" placeholder="Phone">

                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Do you carry business insurance for your janitorial company?<span class="text-danger">*</span></label><br>
                                                    <?php echo FillRadios($rdISBI, 'rdISBI', $YES_ARR, '', 'form-control'); ?>
                                                </div>

                                            </div>

                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="">Do you provide cleaning services during business hours?<span class="text-danger">*</span></label><br>
                                                <?php echo FillRadios($cleaningstatus, 'cleaningstatus', $YES_ARR, '', 'form-control'); ?>

                                            </div>
                                        </div>


                                        <div class="form-group row border-top pt-3" style="display: ;">
                                            <label for="inputSkills" class="col-md-4  col-form-label">Profile Pic (160 * 160)<span style="color:#ff0000"> Max.(500 KB)</span></label>
                                            <div class="col-md-9  d-flex">
                                                <?php
                                                if (IsExistFile($vLicence_file, LICENCE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo LICENCE_PATH . $vLicence_file ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELLIC','<?php echo $txtid; ?>','Profile Pic');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>

                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vLicence_file" id="vLicence_file">
                                                <?php  }
                                                ?>
                                            </div>
                                        </div>



                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="licence_check" value="1" <?php echo ($licence_check == '1') ? 'checked' : ''; ?>>
                                                <label for="licence_check" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row border-top pt-3" style="display: none;">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Insurance<span style="color:#ff0000"> Max.(3 MB)</span></label>
                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vInsurance_file, INSURANCE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo INSURANCE_PATH . $vInsurance_file ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELINS','<?php echo $txtid; ?>','Insurance File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>
                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vInsurance_file" id="vInsurance_file">

                                                <?php }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        if (IsExistFile($vInsurance_file, INSURANCE_UPLOAD)) {
                                        ?>
                                            <div class="form-group row insurance_div">
                                                <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Date Of Expiry</label>
                                                <div class="col-md-9 col-12">
                                                    <input type="date" class="form-control w-auto" value="<?php echo $dDate_insurance_expiry; ?>" name="dDate_insurance_expiry" id="dDate_insurance_expiry">
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group row insurance_div" style="display: none;">
                                                <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Date Of Expiry</label>
                                                <div class="col-md-9 col-12">
                                                    <input type="date" class="form-control w-auto" value="<?php echo $dDate_insurance_expiry; ?>" name="dDate_insurance_expiry" id="dDate_insurance_expiry">
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="insurance_check" value="1" <?php echo ($insurance_check == '1') ? 'checked' : ''; ?>>
                                                <label for="insurance_check" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>

                                        <!-- <div class="form-row">
                                            <div class="col-md-6"> -->
                                        <div class="form-group row border-top pt-3" style="display: none;">
                                            <label class="col-md-3 col-xl-2 col-12 col-form-label" for="">Drivers License/Govt ID<span style="color:#ff0000"> Max.(3 MB)</span></label>
                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vGovtID, GOVTID_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo GOVTID_PATH . $vGovtID; ?>" class="mx-5 " target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELGOVTID','<?php echo $txtid; ?>',' File');" class="my-2 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>
                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vGovtID" id="vGovtID">

                                                <?php   }
                                                ?>
                                            </div>
                                        </div>
                                        <!-- </div> -->

                                        <div class="form-group row border-top pt-3">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Brochure<span style="color:#ff0000"> Max.(3 MB)</span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vBrochure, BROCHURE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo BROCHURE_PATH . $vBrochure; ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELBROCHURE','<?php echo $txtid; ?>','Brochure File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>

                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vBrochure">
                                                <?php  }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="brochure_check" value="1" <?php echo ($brochure_check == '1') ? 'checked' : ''; ?>>
                                                <label for="" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row border-top pt-3">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Cerification<span style="color:#ff0000"> Max.(3 MB)</span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate1, CERTIFICATE1_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE1_PATH . $vCertificate1 ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE1','<?php echo $txtid; ?>','Certificate File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>

                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate1" value="1">
                                                <?php   }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="certificate1_check" value="1" <?php echo ($certificate1_check == '1') ? 'checked' : ''; ?>>
                                                <label for="" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row border-top pt-3">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Certification<span style="color:#ff0000"> Max.(3 MB)</span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate2, CERTIFICATE2_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE2_PATH . $vCertificate2 ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE2','<?php echo $txtid; ?>','Certificate File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>
                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate2">

                                                <?php  }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="certificate2_check" value="1" <?php echo ($certificate2_check == '1') ? 'checked' : ''; ?>>
                                                <label for="" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row border-top pt-3">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Awards<span style="color:#ff0000"> Max.(3 MB)</span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate3, CERTIFICATE3_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE3_PATH . $vCertificate3; ?>" class="btn btn-dark mr-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE3','<?php echo $txtid; ?>','Awards File');" class="my-2 btn btn-danger">Remove</button>
                                                <?php
                                                } else { ?>
                                                    <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate3">

                                                <?php   }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-4" style="display: none;">
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="awards_check" value="1" <?php echo ($awards_check == '1') ? 'checked' : ''; ?>>
                                                <label for="" class="text-primary">
                                                    Allow Customer to view file
                                                </label>
                                            </div>
                                        </div>



                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fblink">Facebook</label>
                                                    <input type="text" class="form-control" name="fblink" placeholder="Put a Facebook link" value="<?php echo $vFblink; ?>" id="fblink">
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <input type="checkbox" name="fblink_check" value="1" <?php echo ($fblink_check == '1') ? 'checked' : ''; ?>>
                                                    <label for="" class="text-primary">
                                                        Allow Customer to view
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inlink">Instagram</label>
                                                    <input type="text" class="form-control" name="inlink" placeholder="Put a Instagram link" value="<?php echo $vInstalink; ?>" id="inlink">
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <input type="checkbox" name="instagram_check" value="1" <?php echo ($instagram_check == '1') ? 'checked' : ''; ?>>
                                                    <label for="" class="text-primary">
                                                        Allow Customer to view
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lilink">LinkedIn</label>
                                                    <input type="text" class="form-control" name="lilink" placeholder="Put a LinkedIn link" value="<?php echo $vLinkedInlink; ?>" id="lilink">
                                                </div>
                                                <div class="form-group" style="display: none;">
                                                    <input type="checkbox" name="linkdn_check" value="1" <?php echo ($linkdn_check == '1') ? 'checked' : ''; ?>>
                                                    <label for="" class="text-primary">
                                                        Allow Customer to view
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="text" class="form-control" name="website" placeholder="Put a website link" value="<?php echo $vWebsite; ?>" id="website">
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
    <script>
        $(document).ready(function() {
            $('#vLicence_file').change(function() {
                if ($(this).val()) {
                    $('.licence_div').css("display", "block");
                    $("#dDate_Licence_expiry").attr("required", "true");
                    console.log('File selected');
                }
            });
            $('#vInsurance_file').change(function() {
                if ($(this).val()) {
                    $('.insurance_div').css("display", "block");
                    $("#dDate_insurance_expiry").attr("required", "true");
                    console.log('File selected');
                }
            });


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