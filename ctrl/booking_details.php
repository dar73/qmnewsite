<?php
include "../includes/common.php";
include "../includes/thumbnail.php";


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
	$txtpassword = md5(htmlspecialchars_decode($_POST['txtpassword']));
	$txtnewpassword = md5(htmlspecialchars_decode($_POST['txtnewpassword']));
	$txtnewpassword2 = htmlspecialchars_decode($_POST['txtnewpassword2']);
	if ($sess_user_level == 0) {

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
	<?php include 'load.links.php' ?>
	<style>
		.display_details p {
            width: 50%;
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

					<div class="row display_details">

						
						<!-- /.col -->
						<div class="col-md-6 col-12">
							<div class="card">
								<div class="card-body">
									<h5>Details</h5>
                                    <div class="col-12 d-flex"><p class="text-primary">Your Address</p> <p>:xyz</p></div>
                                    <div class="col-12 d-flex"><p class="text-primary">Name of Company</p> <p>: lmno</p></div>
                                    <div class="col-12 d-flex"><p class="text-primary">How Often Do You Want Service Per Week ?</p> <p>: 2</p></div>
                                    <div class="col-12 d-flex"><p class="text-primary">How many quotes do you need?</p> <p>: 1</p></div>
                                    <div class="col-12 d-flex"><p class="text-primary">Your Date & Time for booking <span>: 7826</span></p> <p><time datetime="">Mon 30, 08:00 am</time></p></div>
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
				} else
					HideError(txtnewpassword2);

				
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