<?php
require_once('../includes/common.php');
require_once('../includes/thumbnail.php');

if (isset($_GET["mode"])) $mode = $_GET["mode"];
else if (isset($_POST["mode"])) $mode = $_POST["mode"];
else $mode = "A";

if ($mode == 'E')
	$is_menu_closed = true;

if (isset($_GET["id"])) $txtid = $_GET["id"];
else if (isset($_POST["txtid"])) $txtid = $_POST["txtid"];
else $txtid = "0";

$disp_url = "vendor_disp.php";
$edit_url = "vendor_edit.php";

if ((!empty($_GET['id']) && !is_numeric($_GET['id'])) || (!empty($_POST['txtid']) && !is_numeric($_POST['txtid']))) {
	header('location:' . $disp_url);
	exit;
}

if ($mode == 'I' || $mode == 'U') {
	$user_token = (isset($_POST['user_token'])) ? $_POST['user_token'] : '';
	if (empty($user_token) || $user_token != $sess_user_token) {
		header('location:' . $disp_url);
		exit;
	}
}

$DELIVERY_AREA_ARR = GetXArrFromYID('select iLocID,vName from gen_delivery where cStatus="A"', '3');
//$CATEGORY_ARR = GetXArrFromYID('select iCatID,vName from gen_category where cStatus="A"','3');
//$CUISINE_ARR = GetXArrFromYID('select iCuisineID,vName from gen_cuisine where cStatus="A"','3');
$LOCATION_ARR = array();
$cmblocation = '';

$valid_modes = array("A", "I", "E", "U", "DELLOGO", "DELLISTINGPIC", "D", "ADD_LOCATION", "ADD_FILTERS");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
	$txtid = '0';
	$txtname = '';
	$txturlname = '';
	$file_logo = '';
	$txtnotes = '';
	$txtphone = '';
	$txtemail = '';
	$txtopsemail = '';
	$txtopsmobile = '';
	$txtusername = '';
	$txtpassword = '';
	$txtgstncode = '';
	$txtaddress = '';
	$txtstreet = '';
	$txtvillage = '';
	$txtlandmark = '';
	$rdtype = '';
	$txtlatitude = '15.497970936084865';
	$txtlongitude = '73.82720232009888';
	$cmblocid = '0';
	$txtwebsite = '';
	$txtfacebook = '';
	$txtyoutube = '';
	$txtminorder = '';
	$rdgstinclusive = 'N';
	$txtbalance = '';
	$file_listingpic = '';
	$txthits = '';
	$txtcomments = '';
	$txtrating = '';
	$txtvotes = '';
	$txtseotitle = '';
	$txtseokeywords = '';
	$txtseodesc = '';
	$rdfeatured = '';
	$rdstatus = '';
	$cmbpreorder = '';
	$cmbpreordermindays = '';
	$txtthreshold = '';
	$txtthreshold2 = '';

	$cmbdelarea = '';
	//$cmbcategory = '';
	//$cmbcuisine = '';
	$txtstrap = '';

	$form_mode = "I";
} else if ($mode == 'I') {
	$txtid = NextID("iVendorID", "party_vendor");
	$txtname = db_input2($_POST["txtname"]);
	$txturlname = strtolower(GetUrlName($txtname));
	$txtnotes = db_input2($_POST["txtnotes"]);
	$txtphone = db_input2($_POST["txtphone"]);
	$txtemail = db_input2($_POST["txtemail"]);
	$txtopsemail = db_input2($_POST["txtopsemail"]);
	$txtopsmobile = db_input2($_POST["txtopsmobile"]);
	$txtusername = db_input2($_POST["txtusername"]);

	$code_flag = IsUniqueEntry('iVendorID', $txtid, 'vUserName', $txtusername, 'party_vendor');
	if (!$code_flag) $txtusername = SetCode($txtname, 'B');

	$txtpassword = htmlspecialchars_decode(db_input($_POST["txtpassword"]));
	$txtgstncode = db_input2($_POST["txtgstncode"]);
	$txtaddress = db_input2($_POST["txtaddress"]);
	$txtstreet = db_input2($_POST["txtstreet"]);
	$txtvillage = db_input2($_POST["txtvillage"]);
	$txtlandmark = db_input2($_POST["txtlandmark"]);
	$rdtype = (isset($_POST["rdtype"]) && array_key_exists($_POST["rdtype"], $TYPE_ARR)) ? db_input2($_POST["rdtype"]) : 'V';
	$txtlatitude = db_input2($_POST["txtlatitude"]);
	$txtlongitude = db_input2($_POST["txtlongitude"]);
	$cmblocid = db_input2($_POST["cmblocid"]);
	$txtwebsite = db_input2($_POST["txtwebsite"]);
	$txtfacebook = db_input2($_POST["txtfacebook"]);
	$txtyoutube = db_input2($_POST["txtyoutube"]);
	$txtminorder = db_input2($_POST["txtminorder"]);
	$rdgstinclusive = (isset($_POST["rdgstinclusive"]) && array_key_exists($_POST["rdgstinclusive"], $YES_ARR)) ? db_input2($_POST["rdgstinclusive"]) : 'N';
	$txtbalance = '0'; //db_input2($_POST["txtbalance"]);
	$txthits = db_input2($_POST["txthits"]);
	$txtcomments = db_input2($_POST["txtcomments"]);
	$txtrating = db_input2($_POST["txtrating"]);
	$txtvotes = db_input2($_POST["txtvotes"]);
	$txtseotitle = db_input2($_POST["txtseotitle"]);
	$txtseokeywords = db_input2($_POST["txtseokeywords"]);
	$txtseodesc = db_input2($_POST["txtseodesc"]);
	$rdfeatured = (isset($_POST["rdfeatured"]) && array_key_exists($_POST["rdfeatured"], $YES_ARR)) ? db_input2($_POST["rdfeatured"]) : 'N';
	$rdstatus = (isset($_POST["rdstatus"]) && array_key_exists($_POST["rdstatus"], $STATUS_ARR)) ? db_input2($_POST["rdstatus"]) : 'I';
	$cmbpreorder = db_input2($_POST["cmbpreorder"]);
	$cmbpreordermindays = db_input2($_POST["cmbpreordermindays"]);
	$txtthreshold = db_input2($_POST["txtthreshold"]);
	$txtthreshold2 = db_input2($_POST["txtthreshold2"]);
	$txtstrap = db_input2($_POST["txtstrap"]);

	if (!$is_admin && !$is_super_admin) {
		$rdstatus = 'I';
		$rdfeatured = 'N';
	}

	$q = "insert into party_vendor values('$txtid', '$txtname', '$txturlname', '', '$txtnotes', '$txtphone', '$txtemail', '$txtopsemail', '$txtopsmobile', '$txtusername', '$txtpassword', '$txtgstncode', '$txtaddress', '$txtstreet', '$txtvillage', '$txtlandmark', '$rdtype', '$txtlatitude', '$txtlongitude', '$cmblocid', '$txtwebsite', '$txtfacebook', '$txtyoutube', '$txtminorder', '$rdgstinclusive', '$txtbalance', '', '$txthits', '$txtcomments', '$txtrating', '$txtvotes', '" . NOW . "', '$sess_user_id', '', '', '$txtseotitle', '$txtseokeywords', '$txtseodesc', '$rdfeatured', '', '', '', 'N', '$rdstatus', '$cmbpreorder', '$cmbpreordermindays', '$txtthreshold', '$txtthreshold2', '$txtstrap')";
	$r = sql_query($q, 'PARTY.VENDOR.I.106');

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$_SESSION[PROJ_SESSION_ID]->success_info = "Details Successfully Inserted";
} else if ($mode == 'E') {
	$q = "select * from party_vendor where iVendorID=$txtid";
	$r = sql_query($q, 'PARTY.VENDOR.E.112');

	if (!sql_num_rows($r)) {
		header("location: $edit_url");
		exit;
	}

	$o = sql_fetch_object($r);

	$txtname = db_output($o->vName);
	$file_logo = db_output($o->vLogo);
	$txtnotes = $o->vNotes;
	$txtphone = db_output($o->vPhone);
	$txtemail = db_output($o->vEmail);
	$txtopsemail = db_output($o->vOpsEmail);
	$txtopsmobile = db_output($o->vOpsMobile);
	$txtusername = db_output($o->vUserName);
	$txtpassword = db_output($o->vPassword);
	$txtgstncode = db_output($o->cGSTNCode);
	$txtaddress = db_output($o->vAddress);
	$txtstreet = db_output($o->vStreet);
	$txtvillage = db_output($o->vVillage);
	$txtlandmark = db_output($o->vLandmark);
	$rdtype = db_output($o->cNonVeg);

	$txtlatitude = db_output($o->vLatitude);
	if (empty($txtlatitude)) $txtlatitude = '15.497970936084865';

	$txtlongitude = db_output($o->vLongitude);
	if (empty($txtlongitude)) $txtlongitude = '73.82720232009888';

	$cmblocid = db_output($o->iLocLocalityID);
	$txtwebsite = db_output($o->vWebsite);
	$txtfacebook = db_output($o->vFacebook);
	$txtyoutube = db_output($o->vYoutube);
	$txtminorder = db_output($o->fMinOrder);
	$rdgstinclusive = db_output($o->cGSTInclusive);
	$txtbalance = db_output($o->fBalance);
	$file_listingpic = db_output($o->vListingPic);
	$txthits = db_output($o->iHits);
	$txtcomments = db_output($o->iComments);
	$txtrating = db_output($o->iRating);
	$txtvotes = db_output($o->iVotes);
	$txtseotitle = db_output($o->vSEOTitle);
	$txtseokeywords = db_output($o->vSEOKeywords);
	$txtseodesc = db_output($o->vSEODesc);
	$rdfeatured = db_output($o->cFeatured);
	$rdstatus = db_output($o->cStatus);
	$cmbpreorder = db_output($o->iPreOrder);
	$cmbpreordermindays = db_output($o->iPreOrderMinDays);
	$txtthreshold = db_output($o->fMinThresholdDelWithinCircle);
	$txtthreshold2 = db_output($o->fMinThresholdDelOutsideCircle);
	$txtstrap = db_output($o->vStrap);

	$cmbdelarea = GetXArrFromYID('select iLocID from party_vendor_location where iVendorID=' . $txtid);

	$form_mode = "U";
} else if ($mode == 'U') {
	$txtname = db_input2($_POST["txtname"]);
	$txturlname = strtolower(GetUrlName($txtname));
	$txtnotes = db_input2($_POST["txtnotes"]);
	$txtphone = db_input2($_POST["txtphone"]);
	$txtemail = db_input2($_POST["txtemail"]);
	$txtopsemail = db_input2($_POST["txtopsemail"]);
	$txtopsmobile = db_input2($_POST["txtopsmobile"]);
	$txtusername = db_input2($_POST["txtusername"]);

	$code_flag = IsUniqueEntry('iVendorID', $txtid, 'vUserName', $txtusername, 'party_vendor');
	if (!$code_flag) $txtusername = SetCode($txtname, 'B');

	$txtpassword = $_POST["txtpassword"];
	$txtgstncode = db_input2($_POST["txtgstncode"]);
	$txtaddress = db_input2($_POST["txtaddress"]);
	$txtstreet = db_input2($_POST["txtstreet"]);
	$txtvillage = db_input2($_POST["txtvillage"]);
	$txtlandmark = db_input2($_POST["txtlandmark"]);
	$rdtype = (isset($_POST["rdtype"]) && array_key_exists($_POST["rdtype"], $TYPE_ARR)) ? db_input2($_POST["rdtype"]) : 'V';
	$txtlatitude = db_input2($_POST["txtlatitude"]);
	$txtlongitude = db_input2($_POST["txtlongitude"]);
	$cmblocid = db_input2($_POST["cmblocid"]);
	$txtwebsite = db_input2($_POST["txtwebsite"]);
	$txtfacebook = db_input2($_POST["txtfacebook"]);
	$txtyoutube = db_input2($_POST["txtyoutube"]);
	$txtminorder = db_input2($_POST["txtminorder"]);
	$rdgstinclusive = (isset($_POST["rdgstinclusive"]) && array_key_exists($_POST["rdgstinclusive"], $YES_ARR)) ? db_input2($_POST["rdgstinclusive"]) : 'N';
	$txtbalance = db_input2($_POST["txtbalance"]);
	$txthits = (isset($_POST["txthits"])) ? db_input2($_POST["txthits"]) : '';
	$txtcomments = (isset($_POST["txtcomments"])) ? db_input2($_POST["txtcomments"]) : '';
	$txtrating = (isset($_POST["txtrating"])) ? db_input2($_POST["txtrating"]) : '';
	$txtvotes = (isset($_POST["txtvotes"])) ? db_input2($_POST["txtvotes"]) : '';
	$txtseotitle = db_input2($_POST["txtseotitle"]);
	$txtseokeywords = db_input2($_POST["txtseokeywords"]);
	$txtseodesc = db_input2($_POST["txtseodesc"]);
	$rdfeatured = (isset($_POST["rdfeatured"]) && array_key_exists($_POST["rdfeatured"], $YES_ARR)) ? db_input2($_POST["rdfeatured"]) : 'N';
	$rdstatus = (isset($_POST["rdstatus"]) && array_key_exists($_POST["rdstatus"], $STATUS_ARR)) ? db_input2($_POST["rdstatus"]) : 'I';
	$cmbpreorder = db_input2($_POST["cmbpreorder"]);
	$cmbpreordermindays = db_input2($_POST["cmbpreordermindays"]);
	$txtthreshold = db_input2($_POST["txtthreshold"]);
	$txtthreshold2 = db_input2($_POST["txtthreshold2"]);
	$txtstrap = db_input2($_POST["txtstrap"]);

	$qSTR = '';
	if (!empty($txtpassword))
		$qSTR = "vPassword='" . htmlspecialchars_decode($txtpassword) . "', ";

	if ($is_admin || $is_super_admin) $qSTR .= "cFeatured='$rdfeatured', cStatus='$rdstatus',  ";

	$q = "update party_vendor set $qSTR vName='$txtname', vUrlName='$txturlname', vNotes='$txtnotes', vPhone='$txtphone', vEmail='$txtemail', vOpsEmail='$txtopsemail', vOpsMobile='$txtopsmobile', vUserName='$txtusername', cGSTNCode='$txtgstncode', vAddress='$txtaddress', vStreet='$txtstreet', vVillage='$txtvillage', vLandmark='$txtlandmark', cNonVeg='$rdtype', vLatitude='$txtlatitude', vLongitude='$txtlongitude', iLocLocalityID='$cmblocid', vWebsite='$txtwebsite', vFacebook='$txtfacebook', vYoutube='$txtyoutube', fMinOrder='$txtminorder', cGSTInclusive='$rdgstinclusive', dtEdit='" . NOW . "', iUID_edit='$sess_user_id', vSEOTitle='$txtseotitle', vSEOKeywords='$txtseokeywords', vSEODesc='$txtseodesc', iPreOrder='$cmbpreorder', iPreOrderMinDays='$cmbpreordermindays', fMinThresholdDelWithinCircle='$txtthreshold', fMinThresholdDelOutsideCircle='$txtthreshold2', vStrap='$txtstrap' where iVendorID=$txtid";
	$r = sql_query($q, 'PARTY.VENDOR.U.195');

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$_SESSION[PROJ_SESSION_ID]->success_info = "Details Successfully Updated";
} elseif ($mode == "DELLOGO") {
	$file_logo = GetXFromYID("select vLogo from party_vendor where iVendorID=$txtid");
	if (!empty($file_logo))
		DeleteFile($file_logo, PARTY_VENDOR_LOGO_UPLOAD);

	$q = "update party_vendor set vLogo='' where iVendorID=$txtid";
	$r = sql_query($q, 'PARTY.VENDOR.DLP.205');
	$_SESSION[PROJ_SESSION_ID]->success_info = "Logo Picture Successfully Deleted";

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$loc_str = $edit_url . "?mode=E&id=$txtid";
	header("location:" . $loc_str);
	exit;
} elseif ($mode == "DELLISTINGPIC") {
	$file_listingpic = GetXFromYID("select vListingPic from party_vendor where iVendorID=$txtid");
	if (!empty($file_listingpic))
		DeleteFile($file_listingpic, PARTY_VENDOR_LISTINGPIC_UPLOAD);

	$q = "update party_vendor set vListingPic='' where iVendorID=$txtid";
	$r = sql_query($q, 'PARTY.VENDOR.DLP.205');
	$_SESSION[PROJ_SESSION_ID]->success_info = "Listing Picture Successfully Deleted";

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$loc_str = $edit_url . "?mode=E&id=$txtid";
	header("location:" . $loc_str);
	exit;
} elseif ($mode == "D") {
	$disp_flag = (isset($_GET["disp"]) && $_GET["disp"] == "Y") ? true : false;
	$loc_str = $edit_url . "?mode=E&id=$txtid";

	$chk_arr['Location'] = GetXFromYID('select count(*) from party_vendor_location where iVendorID=' . $txtid);
	$chk_arr['Contact No'] = GetXFromYID('select count(*) from party_vendor_contactno where iVendorID=' . $txtid);
	$chk_arr['Delivery Settings'] = GetXFromYID('select count(*) from party_vendor_delivery_setting where iVendorID=' . $txtid);
	$chk_arr['Off Days'] = GetXFromYID('select count(*) from party_vendor_offdays where iVendorID=' . $txtid);
	$chk_arr['Pictures'] = GetXFromYID('select count(*) from party_vendor_pics where iVendorID=' . $txtid);
	$chk_arr['Items'] = GetXFromYID('select count(*) from party_item where iVendorID=' . $txtid);
	$chk_arr['Filters'] = GetXFromYID('select count(*) from party_filter_assoc where iVendorID=' . $txtid);
	$chk = array_sum($chk_arr);

	if (!$chk) {
		$file_logo = GetXFromYID("select vLogo from party_vendor where iVendorID=$txtid");
		if (!empty($file_logo))
			DeleteFile($file_logo, PARTY_VENDOR_LOGO_UPLOAD);

		$file_listingpic = GetXFromYID("select vListingPic from party_vendor where iVendorID=$txtid");
		if (!empty($file_listingpic))
			DeleteFile($file_listingpic, PARTY_VENDOR_LISTINGPIC_UPLOAD);

		$q = "delete from party_vendor where iVendorID=$txtid";
		$r = sql_query($q, 'PARTY.VENDOR.D.233');
		$_SESSION[PROJ_SESSION_ID]->success_info = "Details Successfully Deleted";

		LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

		$loc_str = $disp_url;
	} else
		$_SESSION[PROJ_SESSION_ID]->alert_info = "Details Could Not Be Deleted Because of Existing " . (CHK_ARR2Str($chk_arr)) . " Dependencies";

	header("location:" . $loc_str);
	exit;
} elseif ($mode == "ADD_LOCATION") {
	$checkbox_loc = (isset($_POST['checkbox_loc'])) ? $_POST['checkbox_loc'] : '';
	$q = "delete from party_vendor_location where iVendorID='$txtid'";
	$r = sql_query($q, "VENDOR.LOCATION.D.249");
	if (!empty($checkbox_loc) && is_array($checkbox_loc))
		foreach ($_POST['checkbox_loc'] as $checkbox_locid) {
			list($loc_id, $area_id) = explode('~', $checkbox_locid);

			$q = "insert into party_vendor_location values ($txtid, $loc_id, $area_id)";
			$r = sql_query($q, "PARTY.VENDOR.LOCATION.I.254");
		}

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$_SESSION[PROJ_SESSION_ID]->success_info = "Location Details Successfully Updated";
	header("location:" . $edit_url . "?mode=E&id=$txtid");
	exit;
} elseif ($mode == "ADD_FILTERS") {
	$checkbox_filter = (isset($_POST['checkbox_filter'])) ? $_POST['checkbox_filter'] : '';
	$q = "delete from party_filter_assoc where iVendorID='$txtid'";
	$r = sql_query($q, "VENDOR.LOCATION.D.249");
	if (!empty($checkbox_filter) && is_array($checkbox_filter))
		foreach ($_POST['checkbox_filter'] as $checkbox_filterid) {
			$filter_id = $checkbox_filterid;

			$cat_id = GetXFromYID('select iMCatID from module_category_filter where iMFID=' . $filter_id);

			$q = "insert into party_filter_assoc values ('$txtid', '$cat_id', '$filter_id')";
			$r = sql_query($q, "PARTY.VENDOR.LOCATION.I.254");
		}

	LogAdminUpdates($sess_user_id, $mode, 'party_vendor', $txtid);

	$_SESSION[PROJ_SESSION_ID]->success_info = "Filter Details Successfully Updated";
	header("location:" . $edit_url . "?mode=E&id=$txtid");
	exit;
}

if ($mode == "I" || $mode == "U") {
	if (is_uploaded_file($_FILES["file_logo"]["tmp_name"])) {
		$uploaded_pic = $_FILES["file_logo"]["name"];
		$name = basename($_FILES['file_logo']['name']);
		$file_type = $_FILES['file_logo']['type'];
		$size = $_FILES['file_logo']['size'];
		$extension = substr($name, strrpos($name, '.') + 1);

		if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
			$pic_name = GetXFromYID('select vLogo from party_vendor where iVendorID=' . $txtid);
			if (!empty($pic_name))
				DeleteFile($pic_name, PARTY_VENDOR_LOGO_UPLOAD);

			if (RANDOMIZE_FILENAME == 0) {
				$newname = NormalizeFilename($uploaded_pic); // normalize the file name
				$pic_name = $txtid . "_vendorlogopic_" . $newname;
			} else
				$pic_name = $txturlname . '_logopic_' . $txtid . NOW3 . '.' . $extension;

			$dir = opendir(PARTY_VENDOR_LOGO_UPLOAD);
			copy($_FILES["file_logo"]["tmp_name"], PARTY_VENDOR_LOGO_UPLOAD . $pic_name);
			closedir($dir);		// close the directory

			$q = "update party_vendor set vLogo='$pic_name' where iVendorID=$txtid";
			$r = sql_query($q, 'PARTY.VENDOR.ULPIC.289');

			LogAdminUpdates($sess_user_id, 'UPLOAD_LOGO', 'party_vendor', $txtid);
		} else {
			if ($size > 3000000)
				$_SESSION[PROJ_SESSION_ID]->error_info = "Logo Picture Could Not Be Uploaded as the File Size is greate then 3MB";
			elseif (!in_array($extension, $IMG_TYPE))
				$_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $IMG_TYPE) . ". Please select a new file to upload and submit again.";
		}
	}

	if (is_uploaded_file($_FILES["file_listingpic"]["tmp_name"])) {
		$uploaded_pic = $_FILES["file_listingpic"]["name"];
		$name = basename($_FILES['file_listingpic']['name']);
		$file_type = $_FILES['file_listingpic']['type'];
		$size = $_FILES['file_listingpic']['size'];
		$extension = substr($name, strrpos($name, '.') + 1);

		if (IsValidFile($file_type, $extension, 'P') && $size <= 3000000) {
			$pic_name = GetXFromYID('select vListingPic from party_vendor where iVendorID=' . $txtid);
			if (!empty($pic_name))
				DeleteFile($pic_name, PARTY_VENDOR_LISTINGPIC_UPLOAD);

			if (RANDOMIZE_FILENAME == 0) {
				$newname = NormalizeFilename($uploaded_pic); // normalize the file name
				$pic_name = $txtid . "_vendorlistingpic_" . $newname;
			} else
				$pic_name = $txturlname . '_listingpic_' . $txtid . NOW3 . '.' . $extension;

			$tmp_name = 'TMP_' . $pic_name;

			$w = '';
			$h = '1';

			$dir = opendir(PARTY_VENDOR_LISTINGPIC_UPLOAD);
			copy($_FILES["file_listingpic"]["tmp_name"], PARTY_VENDOR_LISTINGPIC_UPLOAD . $tmp_name);
			ThumbnailImage($tmp_name, $pic_name, PARTY_VENDOR_LISTINGPIC_UPLOAD, 400, 300, $w, $h); // thumbnail
			DeleteFile($tmp_name, PARTY_VENDOR_LISTINGPIC_UPLOAD);
			closedir($dir);		// close the directory

			$q = "update party_vendor set vListingPic='$pic_name' where iVendorID=$txtid";
			$r = sql_query($q, 'PARTY.VENDOR.ULPIC.289');

			LogAdminUpdates($sess_user_id, 'UPLOAD_LISTINGPIC', 'party_vendor', $txtid);
		} else {
			if ($size > 3000000)
				$_SESSION[PROJ_SESSION_ID]->error_info = "Listing Picture Could Not Be Uploaded as the File Size is greate then 3MB";
			elseif (!in_array($extension, $IMG_TYPE))
				$_SESSION[PROJ_SESSION_ID]->error_info = "Please only upload files that end in types: " . implode(',', $IMG_TYPE) . ". Please select a new file to upload and submit again.";
		}
	}

	header("location:" . $edit_url . "?mode=E&id=$txtid");
	exit;
}

if (empty($txtlatitude)) $txtlatitude = '15.497970936084865';
if (empty($txtlongitude)) $txtlongitude = '73.82720232009888';

$mc_name = 'Vendor';
$PAGE_TITLE = $mc_name; //'Vendor';
?>
<?php include('load.header.php'); ?>
<link href="../assets/global/plugins/bootstrap-summernote/summernote.css" rel="stylesheet" type="text/css" />
<link href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
<link href="../assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link href="../assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<link href="../assets/global/plugins/jquery-multi-select/css/multi-select.css" rel="stylesheet" type="text/css" />
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&key=AIzaSyBJkC3aBmymDBuIhLoO7dB9-j89QeSibrQ" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
	var geocoder;
	var map;
	var marker;

	function initialize() {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(<?php echo $txtlatitude; ?>, <?php echo $txtlongitude; ?>);
		var myOptions = {
			zoom: 15,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		marker = new google.maps.Marker({
			map: map,
			draggable: true,
			position: latlng
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			var point = marker.getPosition();
			res = String(point);
			res = res.replace("(", "");
			res = res.replace(")", "");
			resarr = res.split(", ");
			document.getElementById('txtlatitude').value = resarr[0];
			document.getElementById('txtlongitude').value = resarr[1];
		});
	}

	function showAddress(address) {
		geocoder.geocode({
			'address': address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				res = String(results[0].geometry.location);
				res = res.replace("(", "");
				res = res.replace(")", "");
				resarr = res.split(", ");
				document.getElementById('txtlatitude').value = resarr[0];
				document.getElementById('txtlongitude').value = resarr[1];
				map.setCenter(results[0].geometry.location);
				marker = new google.maps.Marker({
					map: map,
					draggable: true,
					position: results[0].geometry.location
				});
			} else
				alert("Geocode was not successful for the following reason: " + status);
		});
	}
</script>
<!-- BEGIN CONTAINER -->
<div class="page-content-wrapper">
	<!-- BEGIN CONTENT BODY -->
	<div class="page-content">
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li> <a href="home.php">Home</a> <i class="fa fa-circle"></i> </li>
				<li> <span><?php echo $PAGE_TITLE; ?></span> </li>
			</ul>
		</div>
		<br />
		<div class="clearfix"></div>
		<div class="row">
			<div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
			<div class="col-md-<?php echo ($mode == 'E') ? '8' : '12'; ?>">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet light portlet-fit portlet-form bordered">
					<div class="portlet-title">
						<div class="caption"> <i class="fa fa-cutlery font-green"></i> <span class="caption-subject font-green sbold uppercase"><?php echo $PAGE_TITLE; ?></span> </div>
						<?php
						if ($mode == 'E' && $txtid) {
						?>
							<div class="actions">
								<div class="btn-group"> <a class="btn btn-sm yellow dropdown-toggle" href="vendor_listings_disp.php?v_id=<?php echo $txtid; ?>"> Listings </a> </div>
								<div class="btn-group"> <a class="btn btn-sm red dropdown-toggle" href="party_vendor_image_dropbox.php?v_id=<?php echo $txtid; ?>"> More Images </a> </div>
								<div class="btn-group"> <a class="btn btn-sm blue dropdown-toggle" href="party_vendor_menu_manager.php?v_id=<?php echo $txtid; ?>"> Menu</a> </div>
							</div>
						<?php
						}
						?>
					</div>
					<!--ValidateFileUpload(file_name,upload_type)-->
					<div class="portlet-body">
						<!-- BEGIN FORM-->
						<?php
						if ($sess_user_level != 2) { ?>
							<form name="frmVendor" id="frmVendor" enctype="multipart/form-data" action="<?php echo $edit_url; ?>" class="form-horizontal form-bordered" method="post">
							<?php } ?>
							<input type="hidden" name="add_mode" value="N" />
							<input type="hidden" name="mode" value="<?php echo $form_mode; ?>" />
							<input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>" />
							<input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>" />
							<div class="form-body">
								<div class="alert alert-danger display-hide">
									<button class="close" data-close="alert"></button>
									You have some form errors. Please check below.
								</div>
								<div class="alert alert-success display-hide">
									<button class="close" data-close="alert"></button>
									Your form validation is successful!
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtname">Title <span class="required">*</span> </label>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="" name="txtname" id="txtname" value="<?php echo $txtname; ?>" required>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter title</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtstrap">Strap Line <span class="required">*</span> </label>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="" name="txtstrap" id="txtstrap" value="<?php echo $txtstrap; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter title</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtnotes">Notes </label>
									<div class="col-md-9">
										<textarea class="form-control summernote" name="txtnotes" id="txtnotes"><?php echo $txtnotes; ?></textarea>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter notes</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtphone">Phone </label>
									<div class="col-md-3">
										<input type="text" class="form-control" name="txtphone" id="txtphone" value="<?php echo $txtphone; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter phone</span>
									</div>
									<label class="col-md-3 control-label" for="txtemail">Email</label>
									<div class="col-md-3">
										<input type="email" class="form-control" placeholder="" name="txtemail" id="txtemail" value="<?php echo $txtemail; ?>" />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter email</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtopsemail">Operational Email</label>
									<div class="col-md-3">
										<input type="email" class="form-control" placeholder="" name="txtopsemail" id="txtopsemail" value="<?php echo $txtopsemail; ?>" />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter operational email</span>
									</div>
									<label class="col-md-3 control-label" for="txtopsmobile">Operational Mobile No</label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtopsmobile" id="txtopsmobile" value="<?php echo $txtopsmobile; ?>" />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter operational mobile no</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtusername">UserName</label>
									<div class="col-md-3">
										<input type="text" name="txtusername" id="txtusername" class="form-control" value="<?php echo $txtusername; ?>" onkeyup="IsCodeUnique(<?php echo $txtid; ?>, this, 'VENDOR');" onBlur="IsCodeUnique(<?php echo $txtid; ?>, this, 'VENDOR');" />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter username</span>
									</div>
									<label class="col-md-3 control-label" for="txtpassword">Password</label>
									<div class="col-md-3">
										<input type="password" class="form-control" placeholder="" name="txtpassword" id="txtpassword" value="" />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter password</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtaddress">Address</label>
									<div class="col-md-7">
										<textarea class="form-control" placeholder="" name="txtaddress" id="txtaddress"><?php echo $txtaddress; ?></textarea>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter address</span>
									</div>
									<div class="col-md-2">
										<input type="button" value="Search" onClick="showAddress(document.getElementById('txtaddress').value);">
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtprofile">Location On Map </label>
									<div class="col-md-9">
										<!--name="txtprofile" id="txtprofile"-->
										<div id="map_canvas" style="width:100%; height:500px;"> </div>
										<div class="form-control-focus"> </div>
										<span class="help-block"></span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtlatitude">Latitude </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtlatitude" id="txtlatitude" value="<?php echo $txtlatitude; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter location latitude</span>
									</div>
									<label class="col-md-3 control-label" for="txtlongitude">Longitude </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtlongitude" id="txtlongitude" value="<?php echo $txtlongitude; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter location longitude</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtstreet">Street </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtstreet" id="txtstreet" value="<?php echo $txtstreet; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter street</span>
									</div>
									<label class="col-md-3 control-label" for="txtvillage">Village </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtvillage" id="txtvillage" value="<?php echo $txtvillage; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter village</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtlandmark">Landmark </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtlandmark" id="txtlandmark" value="<?php echo $txtlandmark; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter landmark</span>
									</div>
									<label class="col-md-3 control-label" for="cmblocid">Location </label>
									<div class="col-md-3">
										<div class="md-radio-inline"> <?php echo FillTreeData($cmblocid, 'cmblocid', 'COMBO2', '0', 'iLocLocalityID,vName', 'gen_location_locality', 'N'); ?> </div>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter type</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtlongitude">Type </label>
									<div class="col-md-3">
										<div class="md-radio-inline"> <?php echo FillRadios2($rdtype, 'rdtype', $TYPE_ARR); ?> </div>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter type</span>
									</div>
									<label class="col-md-3 control-label" for="txtgstncode">GSTN Code </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtgstncode" id="txtgstncode" value="<?php echo $txtgstncode; ?>" maxlength="20">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter GSTN code</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtwebsite">Website </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtwebsite" id="txtwebsite" value="<?php echo $txtwebsite; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter website</span>
									</div>
									<label class="col-md-3 control-label" for="txtfacebook">Facebook </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtfacebook" id="txtfacebook" value="<?php echo $txtfacebook; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter facebook</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtyoutube">Youtube </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtyoutube" id="txtyoutube" value="<?php echo $txtyoutube; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter youtube</span>
									</div>
									<label class="col-md-3 control-label" for="txtbalance">Balance </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtbalance" id="txtbalance" value="<?php echo $txtbalance; ?>" readonly />
										<div class="form-control-focus"> </div>
										<span class="help-block">enter balance</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtminorder">Minimum Order </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtminorder" id="txtminorder" value="<?php echo $txtminorder; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter minimum order value</span>
									</div>
									<label class="col-md-3 control-label" for="rdgstinclusive">Rates Inclusive of GST </label>
									<div class="col-md-3">
										<div class="md-radio-inline"> <?php echo FillRadios2($rdgstinclusive, 'rdgstinclusive', $YES_ARR); ?> </div>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label for="file_pic" class="col-md-3 control-label">Logo (width:300px, height:90px)</label>
									<div class="col-lg-5">
										<div class="col-md-9">
											<?php
											$image = '<img src="../images/no-image.png" alt="" />';
											if (IsExistFile($file_logo, PARTY_VENDOR_LOGO_UPLOAD))
												$image = '<img src="' . PARTY_VENDOR_LOGO_PATH . $file_logo . '" />';

											echo '<div class="fileinput fileinput-new" data-provides="fileinput">';
											echo '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">' . $image . '</div>';
											echo '<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>';

											echo '<div> <span class="btn default btn-file">';
											echo '<span class="fileinput-new"> Select image </span>';
											echo '<span class="fileinput-exists"> Change </span>';
											echo '<input type="file" name="file_logo" id="file_logo" class="default" />';
											echo '</span>';
											echo '</div>';
											echo '</div>';
											if (IsExistFile($file_logo, PARTY_VENDOR_LOGO_UPLOAD)) {
												$del_pic = $edit_url . '?mode=DELLOGO&id=' . $txtid;
												echo '<a onClick="ConfirmDelete(\'Logo\',\'' . $del_pic . '\');" class="btn red fileinput-exists" data-dismiss="fileinput">Remove</a>';
											}
											?>
										</div>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label for="file_pic" class="col-md-3 control-label">Listing Picture (width:400px, height:300px)</label>
									<div class="col-lg-5">
										<div class="col-md-9">
											<?php
											$image = '<img src="../images/no-image.png" alt="" />';
											if (IsExistFile($file_listingpic, PARTY_VENDOR_LISTINGPIC_UPLOAD))
												$image = '<img src="' . PARTY_VENDOR_LISTINGPIC_PATH . $file_listingpic . '" />';

											echo '<div class="fileinput fileinput-new" data-provides="fileinput">';
											echo '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">' . $image . '</div>';
											echo '<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>';

											echo '<div> <span class="btn default btn-file">';
											echo '<span class="fileinput-new"> Select image </span>';
											echo '<span class="fileinput-exists"> Change </span>';
											echo '<input type="file" name="file_listingpic" id="file_listingpic" class="default" />';
											echo '</span>';
											echo '</div>';
											echo '</div>';
											if (IsExistFile($file_listingpic, PARTY_VENDOR_LISTINGPIC_UPLOAD)) {
												$del_pic = $edit_url . '?mode=DELLISTINGPIC&id=' . $txtid;
												echo '<a onClick="ConfirmDelete(\'Listing Picture\',\'' . $del_pic . '\');" class="btn red fileinput-exists" data-dismiss="fileinput">Remove</a>';
											}
											?>
										</div>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtseotitle">SEO Title </label>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="" name="txtseotitle" id="txtseotitle" value="<?php echo $txtseotitle; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter seo title</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtseokeywords">SEO Keywords </label>
									<div class="col-md-9">
										<textarea class="form-control" name="txtseokeywords" id="txtseokeywords" rows="3"><?php echo $txtseokeywords; ?></textarea>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter seo keywords</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtseodesc">SEO Description </label>
									<div class="col-md-9">
										<textarea class="form-control" name="txtseodesc" id="txtseodesc" rows="3"><?php echo $txtseodesc; ?></textarea>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter seo description</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="cmbpreorder">PreOrder Days </label>
									<div class="col-md-3">
										<?php echo FillCombo($cmbpreorder, 'cmbpreorder', 'COMBO', '0', $PRE_ORDER_DAY_ARR); ?>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter preorder days</span>
									</div>
									<label class="col-md-3 control-label" for="cmbpreordermindays">PreOrder Cut-Off Days</label>
									<div class="col-md-3">
										<?php echo FillCombo($cmbpreordermindays, 'cmbpreordermindays', 'COMBO', '0', $PRE_ORDER_MIN_DAY_ARR); ?>
										<div class="form-control-focus"> </div>
										<span class="help-block">enter preorder cut-off days</span>
									</div>
								</div>
								<div class="form-group form-md-line-input">
									<label class="col-md-3 control-label" for="txtthreshold">Minimum Threshold Inside Delivery Circle </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtthreshold" id="txtthreshold" value="<?php echo $txtthreshold; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter minimum threshold inside delivery circle</span>
									</div>
									<label class="col-md-3 control-label" for="txtthreshold2">Minimum Threshold Outside Delivery Circle </label>
									<div class="col-md-3">
										<input type="text" class="form-control" placeholder="" name="txtthreshold2" id="txtthreshold2" value="<?php echo $txtthreshold2; ?>">
										<div class="form-control-focus"> </div>
										<span class="help-block">enter minimum threshold outside delivery circle</span>
									</div>
								</div>
								<div class="form-group form-md-radios">
									<label class="col-md-3 control-label" for="rdfeatured">Featured</label>
									<div class="col-md-9">
										<div class="md-radio-inline"> <?php echo FillRadios2($rdfeatured, 'rdfeatured', $YES_ARR); ?> </div>
									</div>
								</div>
								<div class="form-group form-md-radios">
									<label class="col-md-3 control-label" for="rdstatus">Status</label>
									<div class="col-md-9">
										<div class="md-radio-inline"> <?php echo FillRadios2($rdstatus, 'rdstatus', $STATUS_ARR); ?> </div>
									</div>
								</div>
							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button name="btnBack" type="reset" class="btn default" onClick="GoToPage('<?php echo $disp_url; ?>?srch_mode=MEMORY');">Back</button>
										<button name="btnSave" type="submit" class="btn green">Save</button>
										<?php
										if ($mode == 'E' && $txtid) {
										?>
											<button name="btnDelete" type="button" class="btn red" onClick="ConfirmDelete('Vendor', '<?php echo $edit_url . '?mode=D&id=' . $txtid; ?>');">Delete</button>
										<?php
										}
										?>
									</div>
								</div>
							</div>
							<?php if ($sess_user_level != 2) { ?>
							</form>
						<?php } ?>
						<!-- END FORM-->
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
			<?php
			if ($mode == 'E' && $txtid) {
			?>
				<!--<div class="col-md-4" id="LOCATION_HTML"> </div>-->
				<div class="col-md-4" id="FILTER_HTML"> </div>
				<!--div class="col-md-4" id="DELIVERYCHARGES_HTML"> </div>
      <div class="col-md-4" id="KITCHEN_TIMINGS"> </div-->
				<div class="col-md-4" id="OFF_DAYS"> </div>
				<div class="clearfix"></div>
				<div class="col-md-4" id="CONTACT_NOS"> </div>
				<div class="col-md-4" id="VENDOR_IMAGES"> </div>
				<div class="clearfix"></div>
			<?php
			}
			?>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- END CONTENT BODY -->
</div>
<!-- END CONTAINER -->
<?php include_once("load.footer.php"); ?>
<script type="text/javascript" language="javascript">
	$(function() {
		initialize();
	});

	<?php
	if ($mode == 'E') {
	?>
		$(function() {
			//ShowVendorListings();
			//ShowVendorDeliveryLocation();
			ShowVendorFilters();
			ShowVendorOffDays();
			ShowVendorContactNo();
			ShowVendorImages();
		});

		function ShowVendorListings() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_vendor_listings.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#LISTINGS_HTML").html(results);
				FormInputMask.init();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function GetListingHomeFeaturedDetails(type) {
			if (type == 'Y')
				jQuery("#HOME_FEATURED").css('display', '');
			else
				jQuery("#HOME_FEATURED").css('display', 'none');
		}

		function GetListingCategoryFeaturedDetails(type) {
			if (type == 'Y')
				jQuery("#CATEGORY_FEATURED").css('display', '');
			else
				jQuery("#CATEGORY_FEATURED").css('display', 'none');
		}

		function AddVendorListing() {
			var mc_id = jQuery("#cmblistingid").val();
			if (str_trim(mc_id) == '0') {
				alert('Please choose category');
				return false;
			}

			var ldate = jQuery("#txtledate").val();
			if (str_trim(ldate) == '') {
				alert('Please enter end date');
				return false;
			}

			var lamt = jQuery("#txtlamt").val();

			var rd_hobj = document.getElementsByName('rdlfhome');
			var hfeat = GetRadioValue(rd_hobj);

			var hdate = hamt = '';
			if (hfeat == 'Y') {
				hdate = jQuery("#txtlfhedate").val();
				if (str_trim(ldate) == '') {
					alert('Please enter end date for featured on home page');
					return false;
				}

				hamt = jQuery("#txtlfhamt").val();
			}

			var rd_cobj = document.getElementsByName('rdlfcategory');
			var cfeat = GetRadioValue(rd_cobj);

			var cdate = camt = '';
			if (hfeat == 'Y') {
				cdate = jQuery("#txtlfcedate").val();
				if (str_trim(cdate) == '') {
					alert('Please enter end date for featured on category page');
					return false;
				}

				camt = jQuery("#txtlfcamt").val();
			}

			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get('../includes/ajax.inc.php', {
				response: 'ADD_VENDOR_LISTINGS',
				v_id: <?php echo $txtid; ?>,
				mc_id: mc_id,
				ldate: ldate,
				lamt: lamt,
				hfeat: hfeat,
				hdate: hdate,
				hamt: hamt,
				cfeat: cfeat,
				cdate: cdate,
				camt: camt,
				rand: myRandom
			}, function(results) {
				ShowVendorListings();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function ShowVendorDeliveryLocation() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_delivery_location.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#LOCATION_HTML").html(results);
				ComponentsBootstrapSelect.init();
				SelectLocationCheckbox();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function GetVendorLocality() {
			var delLOC = [];
			$.each($("#cmbdelarea option:selected"), function() {
				delLOC.push($(this).val());
			});

			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_location_locality.php", {
				mode: 'onloaddisplay',
				delLOC: delLOC,
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#LOCATION_HTML3").html(results);
				SelectLocationCheckbox();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function SelectLocationCheckbox() {
			$('li :checkbox').on('click', function() {
				var $chk = $(this),
					$li = $chk.closest('li'),
					$ul, $parent;
				if ($li.has('ul')) {
					$li.find(':checkbox').not(this).prop('checked', this.checked)
				}
				do {
					$ul = $li.parent();
					$parent = $ul.siblings(':checkbox');
					if ($chk.is(':checked')) {
						$parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
					} else {
						$parent.prop('checked', false)
					}
					$chk = $parent;
					$li = $chk.closest('li');
				} while ($ul.is(':not(.someclass)'));
			});
		}

		function ShowVendorFilters() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_filters.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#FILTER_HTML").html(results);
				ComponentsBootstrapSelect.init();
				SelectLocationCheckbox();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function ShowVendorOffDays() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_offdays.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#OFF_DAYS").html(results);
				FormInputMask.init();
				ComponentsDateTimePickers.init();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function AddVendorOffDays() {
			var oDAY = jQuery("#cmbowday").val();
			var oDATE = jQuery("#txtodate").val();
			var oDESC = jQuery("#txtodesc").val();
			var oFROM = ''; //jQuery("#txtofrom").val();
			var oTO = ''; //jQuery("#txtoto").val();

			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get('../includes/ajax.inc.php', {
				response: 'ADD_PARTY_VENDOR_OFFDAY',
				v_id: <?php echo $txtid; ?>,
				oDAY: oDAY,
				oDATE: oDATE,
				oDESC: oDESC,
				oFROM: oFROM,
				oTO: oTO,
				rand: myRandom
			}, function(results) {
				ShowVendorOffDays();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function DeleteVendorOffDay(vi_id, v_id) {
			if (confirm('You are about to delete this Off Day Entry. Continue?')) {
				$.get(ajax_url, {
					response: 'DELETE_PARTY_VENDOR_OFFDAY',
					vi_id: vi_id,
					v_id: v_id
				}, function(result) {
					results = result.split('~');
					text = results[1];
					if (results[0] == '0')
						$('#LBL_INFO2').html(NotifyThis(text, 'error'));
					else if (results[0] == '1')
						$('#LBL_INFO2').html(NotifyThis(text, 'success'));
					else if (results[0] == '2')
						$('#LBL_INFO2').html(NotifyThis(text, 'info'));
					ShowVendorOffDays();
				}).error(function(err) {
					alert('error ' + err.responseText);
				});
			}
		}

		function ShowVendorContactNo() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_contactno.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#CONTACT_NOS").html(results);
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function AddVendorContactNo() {
			var txtcmobile = jQuery("#txtcmobile").val();

			if (str_trim(txtcmobile) == '') {
				alert('Please enter Mobile No');
				return false;
			}

			if (str_trim(txtcmobile).length != '12') {
				alert('Please enter 12 digit Mobile No');
				return false;
			}

			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get('../includes/ajax.inc.php', {
				response: 'ADD_PARTY_VENDOR_CONTACTNO',
				v_id: <?php echo $txtid; ?>,
				txtcmobile: txtcmobile,
				rand: myRandom
			}, function(results) {
				ShowVendorContactNo();
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function DeleteVendorContactNo(vc_id, v_id) {
			if (confirm('You are about to delete this Contact No Entry. Continue?')) {
				$.get(ajax_url, {
					response: 'DELETE_PARTY_VENDOR_CONTACTNO',
					vc_id: vc_id,
					v_id: v_id
				}, function(result) {
					results = result.split('~');
					text = results[1];
					if (results[0] == '0')
						$('#LBL_INFO2').html(NotifyThis(text, 'error'));
					else if (results[0] == '1')
						$('#LBL_INFO2').html(NotifyThis(text, 'success'));
					else if (results[0] == '2')
						$('#LBL_INFO2').html(NotifyThis(text, 'info'));
					ShowVendorContactNo();
				}).error(function(err) {
					alert('error ' + err.responseText);
				});
			}
		}

		function ShowVendorImages() {
			var myRandom = parseInt(Math.random() * 99999999);
			jQuery.get("_party_vendor_images.php", {
				mode: 'onloaddisplay',
				v_id: <?php echo $txtid; ?>,
				rand: myRandom
			}, function(results) {
				jQuery("#VENDOR_IMAGES").html(results);
			}).error(function(errores) {
				alert(errores.responseText);
			});
		}

		function DeleteVendorImage(ri_id, v_id) {
			if (confirm('You are about to delete this Image. Continue?')) {
				$.get(ajax_url, {
					response: 'DELETE_PARTY_VENDOR_IMAGE',
					ri_id: ri_id,
					v_id: v_id
				}, function(result) {
					results = result.split('~');
					text = results[1];
					if (results[0] == '0')
						$('#LBL_INFO2').html(NotifyThis(text, 'error'));
					else if (results[0] == '1')
						$('#LBL_INFO2').html(NotifyThis(text, 'success'));
					else if (results[0] == '2')
						$('#LBL_INFO2').html(NotifyThis(text, 'info'));
					ShowVendorImages();
				}).error(function(err) {
					alert('error ' + err.responseText);
				});
			}
		}

	<?php
	}
	?>

	function GenerateNewPass(pass) {

		var url_str = 'password.inc.php?mode=GetPass&pass=' + pass;
		sd_obj = new serverData;
		var myRandom = parseInt(Math.random() * 99999999); // cache buster
		sd_response = sd_obj.send(url_str + '&rand=' + myRandom, "");

		return sd_response;
	}

	$().ready(function() {
		var e = $("#frmVendor"),
			r = $(".alert-danger", e),
			i = $(".alert-success", e);
		e.validate({
			errorElement: "span",
			errorClass: "help-block help-block-error",
			focusInvalid: !1,
			ignore: "",
			messages: {},
			rules: {
				cmbchainid: {
					required: !0
				},
				txtname: {
					minlength: 2,
					required: !0
				},
				rdstatus: {
					required: !0
				}
			},
			invalidHandler: function(e, t) {
				i.hide(), r.show(), App.scrollTo(r, -200)
			},
			errorPlacement: function(e, r) {
				r.is(":radio") ? e.insertAfter(r.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline")) : e.insertAfter(r)
			},
			highlight: function(e) {
				$(e).closest(".form-group").addClass("has-error")
			},
			unhighlight: function(e) {
				$(e).closest(".form-group").removeClass("has-error")
			},
			success: function(e) {
				e.closest(".form-group").removeClass("has-error")
			},
			submitHandler: function(e) {
				i.show(), r.hide();
				var p = $('#txtpassword').val();
				//alert(JSON.stringify(p, null, 4));
				if (p != '') {
					p_str = GenerateNewPass(hex_md5(p));
					$('#txtpassword').val(p_str);
				}
				e.submit();
			}
		});
	});

	var ComponentsDateTimePickers = function() {
		var e = function() {
			jQuery().timepicker && ($(".timepicker-24").timepicker({
				autoclose: !0,
				minuteStep: 5,
				showSeconds: 1,
				showMeridian: !1
			}))
		};
		return {
			init: function() {
				e()
			}
		}
	}();
	App.isAngularJsApp() === !1 && jQuery(document).ready(function() {
		ComponentsDateTimePickers.init()
	});

	var ComponentsBootstrapSelect = function() {
		var n = function() {
			$(".bs-select").selectpicker({
				iconBase: "fa",
				tickIcon: "fa-check"
			})
		};
		return {
			init: function() {
				n()
			}
		}
	}();
	App.isAngularJsApp() === !1 && jQuery(document).ready(function() {
		ComponentsBootstrapSelect.init()
	});

	var ComponentsBootstrapSelect2 = function() {
		var n = function() {
			$(".bs-select2").selectpicker({
				iconBase: "fa",
				tickIcon: "fa-check"
			})
		};
		return {
			init: function() {
				n()
			}
		}
	}();
	App.isAngularJsApp() === !1 && jQuery(document).ready(function() {
		ComponentsBootstrapSelect2.init()
	});

	var ComponentsEditors = function() {
		var t = function() {
				jQuery().wysihtml5 && $(".wysihtml5").size() > 0 && $(".wysihtml5").wysihtml5({
					stylesheets: ["../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
				})
			},
			s = function() {
				$(".summernote").summernote({
					height: 300,
					toolbar: [
						//['style', ['style']],
						['font', ['bold', 'italic', 'underline']] //,
						//['fontname', ['fontname']],
						//['color', ['color']],
						//['para', ['ul', 'ol', 'paragraph']],
						//['height', ['height']],
						//['table', ['table']],
						//['insert', ['link','hr']],
						//['codeview', ['codeview']]
					]
				})
			};
		return {
			init: function() {
				t(), s()
			}
		}
	}();
	jQuery(document).ready(function() {
		ComponentsEditors.init()
	});

	var FormInputMask = function() {
		var a = function() {
			$(".date").inputmask({
					mask: "99-99-9999"
				}),
				$("#txtodate").inputmask({
					mask: "99-99-9999"
				})
		};
		return {
			init: function() {
				a()
			}
		}
	}();
	App.isAngularJsApp() === !1 && jQuery(document).ready(function() {
		FormInputMask.init()
	});
</script>
<script src="../assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="../assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="../js/custom/common.js"></script>
<!--script language="JavaScript" type="text/javascript" src="../scripts/ajax.js"></script-->
<script language="JavaScript" type="text/javascript" src="../scripts/md5.js"></script>
<script src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
</body>

</html>