<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'Profile';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'c_profile.php';
$edit_url = 'c_profile.php';
$modalTITLE = 'Edit ' . $PAGE_TITLE2;
if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_SESSION['udat_DC']->user_id)) $txtid = $_SESSION['udat_DC']->user_id;
elseif (isset($_SESSION['udat_DC']->user_id)) $txtid = $_SESSION['udat_DC']->user_id;
else $mode = 'E';


$form_mode = '';
$cond = '';
$PROPERTY_ARR = array();
$USER_REF_ID = array();
$valid_modes = array("A", "I", "E", "U", "D", "DELLIC", "DELINS");
$mode = EnsureValidMode($mode, $valid_modes, "E");
if ($mode == 'I') {
    // var_dump($_POST);
    // exit;
    $txtid = NextID('iUserID', 'users');
    $txtfname = db_input($_POST['txtname']);
    $txtLname = db_input($_POST['txtname']);
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
    $dataArr = GetDataFromID("customers", "iCustomerID", $txtid);
    if (empty($dataArr)) {
        header("location: $disp_url");
        exit;
    }
    // DFA($dataArr);
    // exit;
    $txtid = db_output2($dataArr[0]->iCustomerID);
    $txtfirst_name = db_output2($dataArr[0]->vFirstname);
    $txtlast_name = db_output2($dataArr[0]->vLastname);
    $txtnameofcomapany = db_output2($dataArr[0]->vName_of_comapny);
    $position = db_output2($dataArr[0]->vPosition);
    $txtphone = db_output2($dataArr[0]->vPhone);
    $txtemail = db_output2($dataArr[0]->vEmail);


    $form_mode = 'U';
    $code_flag = '1';
} else if ($mode == 'U') {
    $txtfirst_name = db_input($_POST['first_name']);
    $txtlast_name = db_input($_POST['last_name']);
    $txtnameofcomapany = db_input($_POST['company_name']);
    $position = db_input($_POST['position']);
    $txtphone = db_input($_POST['phone']);

    //SELECT `iCustomerID`, `vFullname`, `vName_of_comapny`, `vPosition`, `vEmail`, `vPassword`, `vPhone`, `cStatus` FROM `customers
    $values = "vFirstname='$txtfirst_name',vLastname='$txtlast_name',vName_of_comapny='$txtnameofcomapany',vPosition='$position',vPhone='$txtphone' ";
    $QUERY = UpdataData('customers', $values, "iCustomerID=$txtid");

    //$desc_str = 'Updated: '.db_input($values);
    //LogMasterEdit2($txtid, 'USR', $mode, $txtname, $desc_str, $sess_user_id);

    $_SESSION[PROJ_SESSION_ID]->success_info = "User Details Successfully Updated";
}
if ($mode == "I" || $mode == "U") {
    $add_mode = (isset($_POST['add_mode'])) ? $_POST['add_mode'] : 'N';
    $loc_str = $edit_url;
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

                                    <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>">

                                        <input type="hidden" name="mode" id="mode" value="<?php echo $form_mode ?>">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="first_name" value="<?php echo $txtfirst_name ?>" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="last_name" value="<?php echo $txtlast_name; ?>" placeholder="First Name">
                                            </div>
                                        </div>


                                        <!-- <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                            </div>
                                        </div> -->
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Company Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="company_name" value="<?php echo $txtnameofcomapany ?>" placeholder="Company Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="comment" class="col-sm-2">Position in the Company</label>
                                            <div class="col-sm-10">

                                                <input type="text" name="position" id="position" placeholder="Position" value="<?php echo $position ?>" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Phone</label>
                                            <div class="col-sm-10">
                                                <input type="tel" class="form-control" value="<?php echo $txtphone; ?>" name="phone" placeholder="Phone">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" value="<?php echo $txtemail; ?>" name="email" readonly>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" name="submit" value="submit" class="btn btn-danger">Update</button>
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

        });
    </script>
</body>

</html>