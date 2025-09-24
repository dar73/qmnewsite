<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
//ini_set('display_startup_errors', 1);

include "../includes/common.php";
include "../includes/thumbnail.php";
include '../phpmailer.php';

$SOURCE = array('EM' => 'Email Marketing', 'T' => 'Telemarketing', 'TT' => 'Telemarketing - Text / Animation', 'SF' => 'Social Media FaceBook', 'SL' => 'Social Media LinkedIn', 'SI' => 'Social Media Instagram');

// define('GOVTID_UPLOAD', DOCROOT . 'uploads/govtid/');
// define('GOVTID_PATH', SITE_ADDRESS . 'uploads/govtid/');

$PAGE_TITLE2 = 'Profile';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'service_providers_disp.php';
$edit_url = 'sp_edit.php';
$availability = 'sp_availability_view.php';
$Agreementview = 'platinum_agreementview.php';
if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
//else $mode = 'E';

if (empty($txtid)) {
    header('location:' . $disp_url);
    exit;
}


$VP = GetDataFromID("service_providers", "id", $txtid);
$CUSERTYPE = db_output2($VP[0]->cUsertype);
$RefreshToken = db_output2($VP[0]->vRefreshToken);
$PlatinumAgreement = db_output2($VP[0]->cPlatinumAgreement);

$COUNTRY_ARR = GetXArrFromYID("select country_id,country_name from countries where 1 and cStatus='A' ", '3');
$STATE_ARR = $CITY_ARR = array();
$STATE_ARR = GetXArrFromYID("select state_id,state_name from states where 1  order by state_name ", '3');



$ALL_ARRY = array('png', 'pjpeg', 'jpeg', 'jpg', 'JPG', 'txt', 'doc', 'docx', 'pdf', 'xls', 'xlsx');

$cond = '';
$PROPERTY_ARR = array();
$USER_REF_ID = array();
$APPROVAL_STATUS_ARR = array("A" => "Active", "I" => "Inactive");
$valid_modes = array("A", "I", "E", "U", "D", "DELLIC", "DELINS", "DELBROCHURE", "DELCERTIFICATE1", "DELCERTIFICATE2", "DELCERTIFICATE3", "DELGOVTID");
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

$mode = EnsureValidMode($mode, $valid_modes, "E");

if ($mode == 'E') {
    $dataArr = GetDataFromID("service_providers", "id", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    // DFA($dataArr);
    // exit;
    $vWebsite = db_output2($dataArr[0]->vWebsite);
    $rdISBI = db_output2($dataArr[0]->cHaveBI);
    $vGovtID = db_output2($dataArr[0]->vGovtID);
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
    $country = db_output2($dataArr[0]->county);
    $city = db_output2($dataArr[0]->city);
    $vBrochure = db_output2($dataArr[0]->vBrochure);
    $vCertificate1 = db_output2($dataArr[0]->vCertificate1);
    $vCertificate2 = db_output2($dataArr[0]->vCertificate2);
    $vCertificate3 = db_output2($dataArr[0]->vCertificate3);
    $vFblink = db_output2($dataArr[0]->vFblink);
    $vInstalink = db_output2($dataArr[0]->vInstalink);
    $vLinkedInlink = db_output2($dataArr[0]->vLinkedInlink);
    $x_admin_status_approval = db_output($dataArr[0]->cAdmin_approval);
    $rdactype = db_output($dataArr[0]->cCalendarAct);
    $source = db_output($dataArr[0]->cSource);
    $CCEMAILS = GetXFromYID("select vEmail from sp_ccmails where iSPID='$txtid' ");

    $CITY_ARR = GetXArrFromYID("select city_id,city_name from cities where 1 and state_id='$state' order by city_name ", '3');

    $PLAN = db_output2($dataArr[0]->cUsertype);
    $PLATINUM_DETAIL_STYLE = 'display:none;';
    if ($PLAN == 'P')
        $PLATINUM_DETAIL_STYLE = '';
    $NOTES = isset($dataArr[0]->vSnotes) ? explode(",", db_output2($dataArr[0]->vSnotes)) : '';
    if (!empty($NOTES))
        $NOTES = arrayValuesToKeys($NOTES);
    $LEADS_WEEK = isset($dataArr[0]->vLeadPerWeek) ? (int)db_output2($dataArr[0]->vLeadPerWeek) : '';
    $LEADS_MONTH = isset($dataArr[0]->vLeadPerMonth) ? (int)db_output2($dataArr[0]->vLeadPerMonth) : '';

    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
    $rating = db_output2($dataArr[0]->fGratings);
    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    $First_name = db_input($_POST['First_name']);
    $rdISBI = (isset($_POST['rdISBI'])) ? db_input2($_POST['rdISBI']) : '';
    $Last_name = db_input($_POST['Last_name']);
    $company_name = db_input($_POST['company_name']);
    $phone = db_input($_POST['phone']);
    $email_address = db_input($_POST['email_address']);
    //$license_number = db_input($_POST['license_number);
    $dDate_Licence_expiry = db_input($_POST['dDate_Licence_expiry']);
    $dDate_insurance_expiry = db_input($_POST['dDate_insurance_expiry']);
    $street = db_input($_POST['street']);
    $state = db_input($_POST['stateid']);
    $county = db_input($_POST['country']);
    $city = db_input($_POST['cityid']);
    $vFblink = db_input($_POST['fblink']);
    $vInstalink = db_input($_POST['inlink']);
    $vLinkedInlink = db_input($_POST['lilink']);
    $rating = (isset($_POST['rating'])) ? db_input($_POST['rating']) : '';
    $x_admin_status_approval = db_input($_POST['adstatus']);
    $source = db_input($_POST['source']);
    $rdactype = (isset($_POST['rdactype'])) ? db_input2($_POST['rdactype']) : '';

    $PLAN = isset($_POST['rdplan']) ? $_POST['rdplan'] : 'S';

    $vWebsite = db_input($_POST['website']);
    //$vGovtID = db_input($_POST['vGovtID']);

    $Mail_sent = GetXFromYID("select cMailsent from service_providers where id='$txtid' ");

    if ($x_admin_status_approval == 'A' && $Mail_sent == 'I') {
        $to = $email_address;
        $subject = "Profile Approved";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";

        $mail_content2 = "<html>";
        $mail_content2 .= "<body>";
        $mail_content2 .= "<p>Hello,Darshan</p>";
        $mail_content2 .= "<p>Congratulations!</p>";
        $mail_content2 .= "<p>You are now an official member of the QM approved Service Provider Team. This means from this moment forward you will start to receive all leads that come to the selected coverage areas you selected! </p>";
        $mail_content2 .= "<p>What is the next most important thing to do? Make improvements and updates to make your profile more attractive and accessible! Do you have the following available on your service provider profile?</p>";
        $mail_content2 .= "<ol>
                            <li>Website link </li>
                            <li>Facebook, Instagram, LinkedIn links </li>
                            <li>Company brochures </li>
                            <li>Certifications and awards you have received </li>
                </ol>  ";
        $mail_content2 .= "<p>With every lead you buy that customer will receive a link to your QM profile. This will give you the chance to impress them before you ever shake their hand! This is your chance to shine before you ever greet them with your smile!</p>";

        $mail_content2 .= "<p>Follow this link to log into your account and start building a strong, impressive profile page!</p>";
        $mail_content2 .= "<p>Happy Bidding,</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/plogin.php">visit QuoteMaster.com to connect </a></p>';
        $mail_content2 .= "<p>Questions? Need help? Please</p>";
        $mail_content2 .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content2 .= "<p>Quote Master</p>";
        $mail_content2 .= "</body>";
        $mail_content2 .= "</html>";
        //mail($to, $subject, $mail_content2, $headers);

        sql_query("update service_providers set cMailsent='A' where id='$txtid' ");
    }


    $NOTES = isset($_POST['cmbindustrylist']) ? db_input2(implode(",", $_POST['cmbindustrylist'])) : array();
    $LEADS_MONTH = isset($_POST['txt_max_leadsPermonth']) ? $_POST['txt_max_leadsPermonth'] : '0';





    $values = "First_name='$First_name',Last_name='$Last_name',company_name='$company_name',phone='$phone',email_address='$email_address',street='$street',state='$state',county='$county',city='$city',vFblink='$vFblink',vInstalink='$vInstalink' ,vLinkedInlink='$vLinkedInlink',fGratings='$rating',cAdmin_approval='$x_admin_status_approval',vWebsite='$vWebsite',cSource='$source',cHaveBI='$rdISBI',cUsertype='$PLAN',cCalendarAct='$rdactype' ";
    $QUERY = UpdataData('service_providers', $values, "id=$txtid");

    if ($PLAN == 'P') {
        $PLATUNUM_VALUES = "vSnotes='$NOTES',vLeadPerMonth='$LEADS_MONTH' ";
        $QUERY = UpdataData('service_providers', $PLATUNUM_VALUES, "id=$txtid");
    }

    //INSERT INTO `sp_ccmails`(`iSPID`, `vEmail`) VALUES ('[value-1]','[value-2]')
    $ccemails = isset($_POST['ccemail']) ? $_POST['ccemail'] : '';
    if (empty($ccemails)) {
        sql_query("delete from sp_ccmails where iSPID='$txtid' ");
    }
    // } elseif (strpos($ccemails, ',') !== false) {
    //     $ccemails = str_replace(' ', '', $ccemails);
    // }
    if (!empty($ccemails)) {
        // $ccemails = explode(',', $ccemails);
        // $ccemails = array_map('trim', $ccemails);
        // $ccemails = array_unique($ccemails);
        sql_query("delete from sp_ccmails where iSPID='$txtid' ");
        sql_query("insert into sp_ccmails (iSPID,vEmail) values ('$txtid','$ccemails') ");
        // foreach ($ccemails as $email) {
        //     if (!empty($email)) {
        //     }
        // }
    }

    //$desc_str = 'Updated: '.db_input($values);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);
    $modalTITLE = 'Edit ' . $PAGE_TITLE2;
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

    header("location: $edit_url?mode=E&id=$txtid");
    exit;

    // header("location: $disp_url");
    // exit;
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

    header("location: $edit_url?mode=E&id=$txtid");
    exit;

    // header("location: $disp_url");
    // exit;
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

    header("location: $edit_url?mode=E&id=$txtid");
    exit;

    // header("location: $disp_url");
    // exit;
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

    header("location: $edit_url?mode=E&id=$txtid");
    exit;

    // header("location: $disp_url");
    // exit;
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

    // header("location: $disp_url");
    // exit;
    header("location: $edit_url?mode=E&id=$txtid");
    exit;
}

if ($mode == "U") {
    //    DFA($_FILES);
    //    exit;
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
            if (in_array($extension, $ALL_ARRY) && $size <= 3000000) {
                $file_name = GetXFromYID('select vLicence_file from service_providers where id=' . $txtid);

                if (!empty($file_name))
                    DeleteFile($file_name, LICENCE_UPLOAD);

                $file_name = $txtid . '_Licence' . uniqidReal() . '.' . $extension;
                move_uploaded_file($_FILES['vLicence_file']['tmp_name'], LICENCE_UPLOAD . $file_name);
                $q = "update service_providers set vLicence_file='$file_name' where id=$txtid";
                $r = sql_query($q, 'User.E.126');
            } else {

                if ($size > 3000000)
                    $_SESSION[PROJ_SESSION_ID]->success_info = "Licence File Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array(
                    $extension,
                    $DOC_TYPE
                ))
                    $_SESSION[PROJ_SESSION_ID]->success_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
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
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
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
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
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
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Certificate1 Image Could Not Be Uploaded as the File Size is greate then 3MB";
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
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
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
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
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
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
                elseif (!in_array(
                    $extension,
                    $IMG_TYPE
                ))
                    $_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $ALL_ARRY) . ". Please select a new file to upload and submit again.";
            }
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

                                <div class="card-header"><?php echo $modalTITLE ?>
                                    <div class="float-right">

                                        <?php if ($CUSERTYPE == 'P') { ?>
                                            <a class="btn btn-warning " href="<?php echo $availability . '?mode=E&id=' . $txtid; ?>">Additional Parameter</a>&nbsp; &nbsp;
                                        <?php } ?>
                                        <?php if ($CUSERTYPE == 'P') { ?>
                                            <a class="btn btn-info" href="<?php echo $Agreementview . '?mode=E&id=' . $txtid; ?>"> Agreement </a>
                                        <?php } ?>
                                    </div>



                                </div>
                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>

                                    <form class="form-horizontal" action="<?php echo $edit_url; ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">

                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode ?>">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="First_name" value="<?php echo $First_name; ?>" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="Last_name" value="<?php echo $Last_name; ?>" placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="email_address" value="<?php echo $email_address; ?>" placeholder="Email">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Company Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="company_name" value="<?php echo $company_name; ?>" placeholder="Company Name">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="comment">Company Address (Kindly provide actual address and not a post box address )</label>
                                            <input type="text" name="street" id="street" placeholder="Enter your street" value="<?php echo $street ?>" class="form-control">
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="state">Please select the country </label>
                                                    <?php echo FillCombo2022('country', $country, $COUNTRY_ARR, 'Country', 'form-control', 'GetStates(this.value);'); ?>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">

                                                    <label for="comment">Please select State</label>
                                                    <span id="STATE_DIV">
                                                        <?php echo FillCombo2022('stateid', $state, $STATE_ARR, 'state', 'form-control', 'GetCity2(this.value);'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                                <div class="form-group">
                                                    <label for="state">Please select the city</label>
                                                    <span id="CITY_DIV">
                                                        <?php echo FillCombo2022('cityid', $city, $CITY_ARR, 'city', 'form-control', ''); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group col-md-4">
                                            <label for="inputSkills">Phone</label>

                                            <input type="tel" class="form-control" value="<?php echo $phone; ?>" name="phone" placeholder="Phone">

                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label for="">Do you carry business insurance for your janitorial company?<span class="text-danger">*</span></label><br>
                                                <?php echo FillRadios($rdISBI, 'rdISBI', $YES_ARR, '', 'form-control'); ?>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="form-group row" style="display: none;">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Business Licence<span class="text-muted"></span></label>
                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vLicence_file, LICENCE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo LICENCE_PATH . $vLicence_file ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELLIC','<?php echo $txtid; ?>','Licence File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vLicence_file">
                                            </div>
                                        </div>


                                        <div class="form-group row" style="display: none;">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Date Of Expiry</label>
                                            <div class="col-md-9 col-12">
                                                <input type="date" class="form-control w-auto" value="<?php echo $dDate_Licence_expiry; ?>" name="dDate_Licence_expiry">
                                            </div>
                                        </div>


                                        <div class="form-group row" style="display: none;">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Business Insurance<span class="text-muted"></span></label>
                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vInsurance_file, INSURANCE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo INSURANCE_PATH . $vInsurance_file ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELINS','<?php echo $txtid; ?>','Insurance File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vInsurance_file">
                                            </div>
                                        </div>


                                        <div class="form-group row" style="display: none;">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Date Of Expiry</label>
                                            <div class="col-md-9 col-12">
                                                <input type="date" class="form-control w-auto" value="<?php echo $dDate_insurance_expiry; ?>" name="dDate_insurance_expiry">
                                            </div>
                                        </div>

                                        <div class="form-row" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Drivers License/Govt ID<span style="color:#ff0000"> Max.(3 MB)</span></label>
                                                    <?php
                                                    if (IsExistFile($vGovtID, GOVTID_UPLOAD)) {
                                                    ?>
                                                        <a href="<?php echo GOVTID_PATH . $vGovtID; ?>" class="mx-5 " target="_blank">View file</a>
                                                        <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELGOVTID','<?php echo $txtid; ?>',' File');" class="my-2 btn btn-danger">Remove</button>
                                                    <?php
                                                    } else { ?>
                                                        <input type="file" class="form-control" name="vGovtID" id="vGovtID">

                                                    <?php   }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Brochure<span class="text-muted"></span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vBrochure, BROCHURE_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo BROCHURE_PATH . $vBrochure; ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELBROCHURE','<?php echo $txtid; ?>','Brochure File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vBrochure">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Cerification<span class="text-muted"></span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate1, CERTIFICATE1_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE1_PATH . $vCertificate1 ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE1','<?php echo $txtid; ?>','Certificate File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate1">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Certification<span class="text-muted"></span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate2, CERTIFICATE2_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE2_PATH . $vCertificate2 ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE2','<?php echo $txtid; ?>','Certificate File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate2">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-md-3 col-xl-2 col-12 col-form-label">Awards<span class="text-muted"></span></label>


                                            <div class="col-md-9 col-12 d-flex">
                                                <?php
                                                if (IsExistFile($vCertificate3, CERTIFICATE3_UPLOAD)) {
                                                ?>
                                                    <a href="<?php echo CERTIFICATE3_PATH . $vCertificate3; ?>" class="btn btn-dark mx-3 my-auto" target="_blank">View file</a>
                                                    <button type="button" onClick="SubmitIncludeForm('<?php echo $edit_url; ?>','DELCERTIFICATE3','<?php echo $txtid; ?>','Certificate File');" class="my-2 mx-3 btn btn-danger">Remove</button>
                                                <?php
                                                }
                                                ?>
                                                <input type="file" class="form-control border-0 p-0 my-auto w-auto" name="vCertificate3">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="fblink">Facebook</label>
                                                <input type="text" class="form-control" name="fblink" placeholder="Put a Facebook link" value="<?php echo $vFblink; ?>" id="fblink">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="Inlink">Instagram</label>
                                                <input type="text" class="form-control" name="inlink" placeholder="Put a Instagram link" value="<?php echo $vInstalink; ?>" id="inlink">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="lilink">LinkedIn</label>
                                                <input type="text" class="form-control" name="lilink" placeholder="Put a LinkedIn link" value="<?php echo $vLinkedInlink; ?>" id="inlink">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-12 mt-3 d-flex">

                                                <label class="col-sm-2 col-form-label">Online Rating</label>
                                                <div class="demo">
                                                    <div class="ratingControl">
                                                        <?php
                                                        foreach ($ratings as $key => $value) {
                                                            $chek = ($value['value'] == $rating) ? 'checked' : '';

                                                            echo '<input type="radio"  name="rating" id="rating-' . $value['value'] . '" value="' . $value['value'] . '" ' . $chek . '>';
                                                            echo '<label class="' . $value['class'] . '" for="rating-' . $value['value'] . '">' . $value['value'] . '</label>';
                                                        }

                                                        ?>

                                                        <!-- <input type="radio" id="rating-5" name="rating" value="5">
                                                        <label class="ratingControl-stars ratingControl-stars--5" for="rating-5">5</label>

                                                        <input type="radio" id="rating-45" name="rating" value="4.5">
                                                        <label class="ratingControl-stars ratingControl-stars--45 ratingControl-stars--half" for="rating-45">45</label>

                                                        <input type="radio" id="rating-4" name="rating" value="4">
                                                        <label class="ratingControl-stars ratingControl-stars--4" for="rating-4">4</label>
                                                        <input type="radio" id="rating-35" name="rating" value="3.5">
                                                        <label class="ratingControl-stars ratingControl-stars--35 ratingControl-stars--half" for="rating-35">35</label>
                                                        <input type="radio" id="rating-3" name="rating" value="3">
                                                        <label class="ratingControl-stars ratingControl-stars--3" for="rating-3">3</label>
                                                        <input type="radio" id="rating-25" name="rating" value="2.5">
                                                        <label class="ratingControl-stars ratingControl-stars--25 ratingControl-stars--half" for="rating-25">25</label>
                                                        <input type="radio" id="rating-2" name="rating" value="2">
                                                        <label class="ratingControl-stars ratingControl-stars--2" for="rating-2">2</label>
                                                        <input type="radio" id="rating-15" name="rating" value="1.5">
                                                        <label class="ratingControl-stars ratingControl-stars--15 ratingControl-stars--half" for="rating-15">15</label>
                                                        <input type="radio" id="rating-1" name="rating" value="1">
                                                        <label class="ratingControl-stars ratingControl-stars--1" for="rating-1">1</label>
                                                        <input type="radio" id="rating-05" name="rating" value="0.5" checked>
                                                        <label class="ratingControl-stars ratingControl-stars--05 ratingControl-stars--half" for="rating-05">05</label> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="text" class="form-control" name="website" placeholder="Put a website link" value="<?php echo $vWebsite; ?>" id="website">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-row">

                                            <div class="col-md-6">
                                                <div class=" form-group">
                                                    <label for="adstatus" class="">Admin approval status</label>
                                                    <?php echo FillRadios($x_admin_status_approval, 'adstatus', $STATUS_ARR, '', 'form-control'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">How did you come to know about us? <span class="text-danger">*</span></label><br>
                                                    <?php echo FillRadios($source, 'source', $SOURCE, '', 'form-control'); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Plan</label>

                                                    <?php echo FillRadios($PLAN, 'rdplan', $PLANS_ARR, '', 'form-control'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row" id="PLATINUM_EXTRAA_DETAILS" style="<?php echo $PLATINUM_DETAIL_STYLE ?>">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">List Industries <span class="text-danger">You DO NOT Service</span> (ex. Restaurants, Auto Repair, Learning Centers - change to Restaurants, Auto Repair, theatres)</label>
                                                    <!-- <input type="text" name="txtbox1" id="txtbox1" class="form-control"> -->
                                                    <?php echo FillMultiCombo($NOTES, 'cmbindustrylist', 'COMBO', 'Y', $INDUSTRY_ARR, '', 'in'); ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <label for=""><span class="text-danger">Note : This is not a guarantee of volume</span></label>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Maximum Number of Leads per month:</label>
                                                    <!-- <input type="text" name="txt_max_leadsPerweek" onkeypress="return numbersonly(event);" id="txt_max_leadsPerweek" class="form-control"> -->
                                                    <?php echo FillRadios($LEADS_MONTH, 'txt_max_leadsPermonth', $LEADS_PER_WEEK, '', 'form-control'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for=""> <span class="text-danger">CC Email</span>:</label>
                                                    <input type="text" name="ccemail" value="<?php echo $CCEMAILS; ?>" id="ccemail" class="form-control">

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for=""> <span class="text-danger">Calendar account</span>:</label>
                                                    <?php echo FillRadios($rdactype, 'rdactype', $CALENDAR_AC_TYPE_ARR, '', 'custom-radio'); ?>

                                                </div>
                                            </div>



                                        </div>


                                        <hr>



                                        <div class="form-group row mt-4">
                                            <div class="col-sm-10">
                                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
                                                <?php if ($sess_user_level == '0' || $sess_user_level == '1') { ?>
                                                <?php } ?>
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
        function changeHandler(radio) {
            // console.log();
            var P = $(radio).val();
            if (P == 'P') {
                $('#PLATINUM_EXTRAA_DETAILS').css('display', '');

            }
            if (P == 'S') {
                $('#PLATINUM_EXTRAA_DETAILS').css('display', 'none');

            }
        }


        function GetStates(id) {
            var data = "response=GET_STATES&countryid=" + id;
            $.ajax({
                type: "POST",
                url: ajax_url2,
                data: data,
                success: function(response) {
                    $('#STATE_DIV').html(response);
                }
            });

        }

        function GetCity2(stateid) {
            //alert(countyid);
            var countryID = $('#country').val();
            var countyid = $('#stateid').val();
            var data = "response=GET_CITY&countyid=0" + "&countryid=" + countryID + "&stateid=" + stateid;

            $.ajax({
                url: ajax_url2,
                method: 'POST',
                data: data,
                success: function(res) {
                    $('#CITY_DIV').html(res);
                }
            });

        }


        $(document).ready(function() {
            $('.in').multiselect({
                header: true,
                columns: 1,
                placeholder: 'Select industries',
                search: true,
                selectAll: true
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