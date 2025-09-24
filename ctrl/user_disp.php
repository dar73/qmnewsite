<?php
require_once('../includes/common.php');

$PAGE_TITLE2 = 'Users';
$MEMORY_TAG = 'USER';

$PAGE_TITLE .= $PAGE_TITLE2;

$modalTITLE =  $PAGE_TITLE2;

$disp_url = "user_disp.php";
$edit_url = "user_edit.php";

if (!$is_super_admin) {
  header('location:' . $edit_url . '?mode=E&id=' . $sess_user_id);
  exit;
}
/////////////////////////////////////////////////////////
$execute_query = $is_query = false;
$txtkeyword = $params2 = '';
$cmbstatus = $cmbfeature = '';
$record_count = 0;

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
  $cmbstatus = $_POST['cmbstatus'];

  $params = '&status=' . $cmbstatus;
  header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
  $is_query = true;

  if (isset($_GET['status'])) $cmbstatus = $_GET['status'];

  $params2 = '?status=' . $cmbstatus;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
  SearchFromMemory($MEMORY_TAG, $disp_url);

$srch_style = $criteria = $cond = '';
$srch_style = 'display:none;';

if (isset($STATUS_ARR[$cmbstatus])) {
  $execute_query = true;
  $cond .= " and cStatus = '$cmbstatus' ";
  $criteria .= ', Status: ' . $STATUS_ARR[$cmbstatus];
}

if ($execute_query) {
  $criteria = substr($criteria, 1);
  if ($is_query) $srch_style = 'display:none;';
} else
  $criteria = 'Search Filters:';


$dataArr = GetDataFromQuery('select * from users where 1 ' . $cond . ' order by iLevel');
// $r = sql_query($q, 'C.DET.51');
// $record_count = sql_num_rows($r);

$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;

// DFA($_SESSION);

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
                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                  <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                    <div class="app-page-title2">
                      <div class="page-title-wrapper">
                        <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                          <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                          <div class="wm-100 mrm-50 position-relative form-group m-1">
                            <input type="text" name="txtkeyword" id="txtkeyword" value="<?php echo $txtkeyword; ?>" placeholder="Keywords" class="form-control" />
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
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-success" onClick="GoToPage('<?php echo $edit_url; ?>');"> <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-plus fa-w-20"></i> </span> Add New </button>
                  </div>
                </div>
                <div class="card-body">
                  <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                  <div id="alert_message"></div>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th width="5%">#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Level</th>
                          <th>Last Login</th>
                          <th style="text-align:center;" width="5%">Access</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (!empty($dataArr)) {
                          for ($u = 0; $u < sizeof($dataArr); $u++) {
                            $i = $u + 1;
                            $x_id = db_output($dataArr[$u]->iUserID);
                            $x_name = db_output($dataArr[$u]->vName);
                            $x_email = db_output($dataArr[$u]->vEmail);
                            $x_phone = db_output($dataArr[$u]->vPhone);
                            $x_level = $dataArr[$u]->iLevel;
                            $x_reftype = $dataArr[$u]->cRefType;
                            $x_refid = $dataArr[$u]->iRefID;
                            $dt_login =  $dataArr[$u]->dtLastLogin;
                            $ses_stat = db_output($dataArr[$u]->cActive);
                            $stat = $dataArr[$u]->cStatus;

                            $x_level_str = $USER_LEVEL_ARR[$x_level];
                            $ajax_flag = ($x_id == '1') ? false : true;
                            $status_str = GetStatusImageString('USERS', $stat, $x_id,true);

                            //	$x_property_str = (isset($PROPERTY_ARR[$x_id]) && !empty($PROPERTY_ARR[$x_id]))?implode(',',$PROPERTY_ARR[$x_id]):'NA';

                            $url = $edit_url . '?mode=E&id=' . $x_id;
                        ?>
                            <tr>
                              <td><?php echo $i . '.' ?></td>
                              <td><a href="<?php echo $url; ?>"><?php echo $x_name; ?></a></td>
                              <td><?php echo $x_email; ?></td>
                              <td><?php echo $x_phone; ?></td>
                              <td><?php echo $x_level_str; ?></td>

                              <td><?php echo FormatDate($dt_login,'16'); ?></td>
                              <td style="text-align:center;"><?php echo $status_str; ?></td>
                            </tr>
                        <?php
                          }
                        }
                        ?>
                      </tbody>

                    </table>
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
      //Default data table
      $('#example').DataTable();
      var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
      });
      table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
  </script>
</body>

</html>