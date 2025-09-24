<?php
include "../includes/common.php";
include "../phpmailer.php";
$PAGE_TITLE2 = 'Service Providers';
$MEMORY_TAG = "SERVICE PROVIDERS";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'service_providers_disp.php';
$edit_url = 'sp_edit.php';


$execute_query = $is_query = true;
$txtkeyword = $txtphone = $cmbcleaningstatus = $cond = $params = $params2 = '';
$srch_style = '';


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtkeyword = $_POST['txtkeyword'];
    $txtphone = $_POST['txtphone'];
    $cmbcleaningstatus = $_POST['cmbcleaningstatus'];

    $params = '&keyword=' . $txtkeyword . '&txtphone=' . $txtphone. '&cmbcleaningstatus='. $cmbcleaningstatus;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
    if (isset($_GET['txtphone'])) $txtphone = $_GET['txtphone'];
    if (isset($_GET['cmbcleaningstatus'])) $cmbcleaningstatus = $_GET['cmbcleaningstatus'];

    $params2 = '?keyword=' . $txtkeyword . '&txtphone=' . $txtphone.'&cmbcleaningstatus=' . $cmbcleaningstatus;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtkeyword)) {
    $txtkeyword = db_input2($txtkeyword);
    $cond .= " and (company_name LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}

if (!empty($txtphone)) {
    $cond .= " and (phone LIKE '%" . $txtphone . "%')";
    $execute_query = true;
}

if (!empty($cmbcleaningstatus)) {
    $cond .= " and cCleaningStatus='$cmbcleaningstatus' ";
    $execute_query = true;
}

$EMAIL_VERIFY = array('0' => '<span class="badge badge-danger">No</span>', '1' => '<span class="badge badge-success">Yes</span>');

$SOURCE = array('EM' => 'Email Marketing', 'T' => 'Telemarketing', 'TT' => 'Telemarketing - Text / Animation', 'SF' => 'Social Media FaceBook', 'SL' => 'Social Media LinkedIn', 'SI' => 'Social Media Instagram');

//if($execute_query)
//$srch_style = '';

//$cond .= " and cRefType='A' and cStatus!='X'";
$dataArr = GetDataFromCOND("service_providers", $cond . " and cStatus!='X' order by id desc");


$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
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

                    <div class="row">

                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                                    <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="search by name" class="form-control" />
                                                    </div>
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <input type="text" name="txtphone" id="txtphone" value="<?php echo $txtphone; ?>" placeholder="search by phone" class="form-control" />
                                                    </div>
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <?php echo FillCombo2022('cmbcleaningstatus',$cmbcleaningstatus,$YES_ARR,'cleaning service status') ?>
                                                        </div>

                                                        <div class="page-title-actions mb-2" style="width:100%;">
                                                            <div class="d-inline-block dropdown">
                                                                <button type="submit" class="btn btn-warning"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-danger" onClick="GoToPage('<?php echo $disp_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-times fa-w-20"></i> </span> Reset </button>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header-tab card-header">
                                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal"> <i class="header-icon pe-7s-culture mr-3 text-muted opacity-6"> </i></div>
                                    <div class="btn-actions-pane-right actions-icon-btn float-right">
                                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-info" onClick="ToggleVisibility('SEARCH_RECORDS');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-search fa-w-20"></i> </span> Search </button>
                                        <!-- <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button> -->
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Company Name</th>
                                                <th>Phone</th>
                                                <th>Email Address</th>
                                                <th>Email Verify</th>
                                                <th>Source</th>
                                                <th>Approve</th>
                                                <th>Timestamp</th>
                                                <th>Approved by admin</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($dataArr)) {
                                                for ($u = 0; $u < sizeof($dataArr); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output2($dataArr[$u]->id);
                                                    $x_fname = db_output2($dataArr[$u]->First_name);
                                                    $x_timestamp = db_output2($dataArr[$u]->dDate);
                                                    $x_lname = db_output2($dataArr[$u]->Last_name);
                                                    $x_Company_Name = db_output2($dataArr[$u]->company_name);
                                                    $x_phone = db_output2($dataArr[$u]->phone);
                                                    $x_source = db_output2($dataArr[$u]->cSource);
                                                    $x_email = db_output2($dataArr[$u]->email_address);
                                                    $x_emailVerify = db_output2($dataArr[$u]->email_verify);
                                                    $X_admin_status_approval = db_output2($dataArr[$u]->cAdmin_approval);
                                                    $stat = $dataArr[$u]->cStatus;
                                                    $source_str = (isset($SOURCE[$x_source])) ? $SOURCE[$x_source] : 'NA';
                                                    $status_str = GetStatusImageString('SERVICEPROVIDERS', $stat, $x_id, true);
                                                    $admin_approval_status = GetStatusImageString('ADMIN_APPROVAL_STATUS', $X_admin_status_approval, $x_id, true);
                                                    $url = $edit_url . '?mode=E&id=' . $x_id;
                                                    $edit_coverage_url = "sp_coverage_edit.php?mode=E&id=" . $x_id;
                                            ?>
                                                    <tr>
                                                        <td><?php echo $i . '.' ?></td>
                                                        <td><a href="<?php echo $url; ?>"><?php echo $x_fname; ?></a></td>
                                                        <td><?php echo $x_lname; ?></td>
                                                        <td><?php echo $x_Company_Name; ?></td>
                                                        <td><?php echo $x_phone; ?></td>
                                                        <td><?php echo $x_email; ?></td>
                                                        <td><?php echo $EMAIL_VERIFY[$x_emailVerify]; ?></td>
                                                        <td><?php echo $source_str; ?></td>
                                                        <td style="text-align:center;"><?php echo $status_str; ?></td>
                                                        <td><?php echo date('m/d/Y' . ', ' . 'h:i A', strtotime($x_timestamp)); ?></td>
                                                        <td style="text-align:center;"><?php echo $admin_approval_status; ?></td>
                                                        <td style="text-align:center;">
                                                            <button class="btn btn-danger" onclick="DeleteUser('<?php echo $x_id; ?>');"><i class="fa fa-trash"></i></button>
                                                            <?php if ($x_emailVerify == '0') { ?>
                                                                <button class="btn btn-warning" onclick="SendVerificationLink('<?php echo $x_id; ?>');"><i class='fas fa-envelope-open-text'></i></button>

                                                            <?php } ?>
                                                            &nbsp;<button class="btn btn-success"><a href="<?php echo $edit_coverage_url; ?>" target="_blank" rel="noopener noreferrer"><i class='far fa-compass' style='color:#000'>&nbsp;coverage</i></a></button>

                                                        </td>

                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
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
    <?php include 'load.scripts.php'; ?>
    <script>
        function DeleteUser(id) {
            let text = "Are you sure you want to delete this user?";
            if (confirm(text) == true) {
                $.ajax({
                    url: '_DeleteUser.php',
                    method: 'POST',
                    data: {
                        mode: 'D',
                        id: id,
                        type: 'SP'
                    },
                    success: function(res) {
                        // console.log(res);
                        if (res == 1) {
                            alert('User deleted successfuly ');
                            location.reload();

                        }

                    }

                });
                //form.submit();
            } else {
                text = "You canceled!";
            }

        }

        function SendVerificationLink(spid) {
            $.ajax({
                url: ajax_url2,
                method: 'POST',
                data: {
                    response: 'SEND_VERIFICATION_LINK',
                    spid: spid
                },
                success: function(res) {
                    if (res) {
                        $('#LBL_INFO').html(NotifyThis('Mail has been sent successfuly......', 'success'));
                    }
                }
            });

        }

        $(document).ready(function() {
            // $(document).Toasts('create', {
            //     class: 'bg-success',
            //     title: 'Success',
            //     subtitle: '',
            //     body: 'You can add multiple states .',
            //     delay: 8000, // 3 seconds
            //     autohide: true
            // })



            //fetch_data();
            $('#cTable').DataTable({
                responsive: true,
                pageLength: 100

            });



        });
    </script>
</body>

</html>