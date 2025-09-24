<?php
include "../includes/common.php";
include "../includes/thumbnail.php";
include "../includes/ti-salt.php";

$PAGE_TITLE2 = 'Change Password';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'change_password.php';
$edit_url = 'change_password.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'A';

if ($mode == 'U') {
	$user_token = (isset($_POST['user_token'])) ? $_POST['user_token'] : '';
	if (empty($user_token) || $user_token != $sess_user_token) {
		header('location:' . $disp_url);
		exit;
	}
}

$USER_REF_ID = array();
$valid_modes = array("U");
$mode = EnsureValidMode($mode, $valid_modes, "A");
if ($mode == 'A') {
	$modalTITLE = $PAGE_TITLE2;
	$form_mode = 'U';
} else if ($mode == 'U') {

	$txtpassword = htmlspecialchars_decode($_POST['txtpassword']);
	$salt_obj = new SaltIT;
	$txtpassword = $salt_obj->EnCode($txtpassword);

	$txtnewpassword = htmlspecialchars_decode($_POST['txtnewpassword']);
	$salt_obj = new SaltIT;
	$txtnewpassword = $salt_obj->EnCode($txtnewpassword);

	$txtnewpassword2 = htmlspecialchars_decode($_POST['txtnewpassword2']);
	$salt_obj = new SaltIT;
	$txtnewpassword2 = $salt_obj->EnCode($txtnewpassword2);

	if ($sess_user_level == 0 || $sess_user_level == 1) {

		$currentPassword = GetXFromYID('select vPassword from users where iUserID=' . $sess_user_id);
		if (htmlspecialchars_decode($currentPassword) != $txtpassword)
			$_SESSION[PROJ_SESSION_ID]->success_info = "Incorrect Current Password Entered";
		else {
			$values = " vPassword='$txtnewpassword'";
			$QUERY = UpdataData('users', $values, "iUserID=$sess_user_id");
			$_SESSION[PROJ_SESSION_ID]->success_info = "Password Successfully Updated";
		}
	} elseif ($sess_user_level == 2) {
		$currentPassword = GetXFromYID('select password from service_providers where id=' . $sess_user_id);
		if (htmlspecialchars_decode($currentPassword) != $txtpassword)
			$_SESSION[PROJ_SESSION_ID]->success_info = "Incorrect Current Password Entered";
		else {
			$values = " password='$txtnewpassword'";
			$QUERY = UpdataData('service_providers', $values, "id=$sess_user_id");
			$_SESSION[PROJ_SESSION_ID]->success_info = "Password Successfully Updated";
		}
	} elseif ($sess_user_level == 3) {
		$currentPassword = GetXFromYID('select vPassword from customers where iCustomerID=' . $sess_user_id);
		if (htmlspecialchars_decode($currentPassword) != $txtpassword)
			$_SESSION[PROJ_SESSION_ID]->success_info = "Incorrect Current Password Entered";
		else {
			$values = " vPassword='$txtnewpassword'";
			$QUERY = UpdataData('customers', $values, "iCustomerID=$sess_user_id");
			$_SESSION[PROJ_SESSION_ID]->success_info = "Password Successfully Updated";
		}
	}

	$loc_str = $disp_url;

	header("location: $loc_str");
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
								<li class="breadcrumb-item"><a href="#">Home</a></li>
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


								<div class="card-body">
									<div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
									<div id="alert_message"></div>
									<form class="" id="passwordForm" name="passwordForm" method="post" action="<?php echo $edit_url; ?>" enctype="multipart/form-data">
										<input type="hidden" name="mode" id="mode" value="<?php echo $form_mode; ?>">
										<input type="hidden" name="user_token" id="user_token" value="<?php echo $sess_user_token; ?>">
										<div class="col-md-12">
											<div class="form-row">
												<div class="col-md-6">
													<div class="position-relative form-group">
														<label for="txtpassword" class="">Current Password <span class="text-danger">*</span></label>
														<input name="txtpassword" id="txtpassword" type="password" value="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-row">
												<div class="col-md-6">
													<div class="position-relative form-group">
														<label for="txtnewpassword" class="">New Password <span class="text-danger">*</span></label>
														<input name="txtnewpassword" id="txtnewpassword" type="password" value="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-row">
												<div class="col-md-6">
													<div class="position-relative form-group">
														<label for="txtnewpassword2" class="">Re-enter New Password <span class="text-danger">*</span></label>
														<input name="txtnewpassword2" id="txtnewpassword2" type="password" value="" class="form-control">
													</div>
												</div>
											</div>
											<button type="submit" class="mt-2 btn btn-success">Save</button>
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
	<script type="text/javascript" src="../scripts/ajax.js"></script>
	<script type="text/javascript" src="../scripts/common.js"></script>
	<script type="text/javascript" src="../scripts/md5.js"></script>
	<script>
		$(document).ready(function() {

			//fetch_data();
			$("#passwordForm").submit(function() {
				err = 0;
				err_arr = new Array();
				ret_val = true;

				var txtpassword = $(this).find('#txtpassword');
				var txtnewpassword = $(this).find('#txtnewpassword');
				var txtnewpassword2 = $(this).find('#txtnewpassword2');

				if ($.trim(txtpassword.val()) == '') {
					ShowError(txtpassword, "Please enter password");
					err_arr[err] = txtpassword;
					err++;
				} else
					HideError(txtpassword);

				if ($.trim(txtnewpassword.val()) == '') {
					ShowError(txtnewpassword, "Please enter new password");
					err_arr[err] = txtnewpassword;
					err++;
				} else
					HideError(txtnewpassword);

				if ($.trim(txtnewpassword2.val()) == '') {
					ShowError(txtnewpassword2, "Please re-enter new password");
					err_arr[err] = txtnewpassword2;
					err++;
				} else
					HideError(txtnewpassword2);

				if ($.trim(txtnewpassword.val()) != $.trim(txtnewpassword2.val())) {
					ShowError(txtnewpassword2, "Password not matching");
					txtnewpassword2.val('');
					err_arr[err] = txtnewpassword2;
					err++;
				} else {
					HideError(txtnewpassword2);
					// var p_str = b64_md5(txtnewpassword2.val());
					// txtnewpassword2.val(p_str);
				}


				if ($.trim(txtpassword.val()) != '') {
					var p_str = b64_md5(txtpassword.val());
					txtpassword.val(p_str);
				}

				if ($.trim(txtnewpassword.val()) != '') {
					var p_str = b64_md5(txtnewpassword.val());
					txtnewpassword.val(p_str);
				}

				if ($.trim(txtnewpassword2.val()) != '') {
					var p_str = b64_md5(txtnewpassword2.val());
					txtnewpassword2.val(p_str);
				}


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