<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";

$disp_url = 'sp_availability.php';
$edit_url = 'sp_availability.php';

$PAGE_TITLE2 = 'Additional Parameters';

$PAGE_TITLE .= $PAGE_TITLE2;

$mode = '';
if (isset($_POST['mode']))
    $mode = $_POST['mode'];

if ($mode == 'U') {
    // DFA($_POST);
    // exit;
    // day_1] => Y
    // [timefrom_1] => 08:00:00
    // [timeto_1] => 08:30:00
    sql_query("delete from app_availability where iSPID=$sess_user_id ");
    foreach ($WEEKDAY_ARR as $key => $value) {
        if ($key == 0)
            continue;
        LockTable('app_availability');
        $IS_AVAILABLE = (isset($_POST['day_' . $key])) ? $_POST['day_' . $key] : 'N';
        $TIME_FROM = $_POST['timefrom_' . $key];
        $TIME_TO = $_POST['timeto_' . $key];
        $ID = NextID('Id', 'app_availability');
        $q = "INSERT INTO app_availability(Id, iSPID, iWeekDayID, cAvailable, tStartTime, tEndTime, cStatus) VALUES ('$ID','$sess_user_id','$key','$IS_AVAILABLE','$TIME_FROM','$TIME_TO','A')";
        sql_query($q);
        UnlockTable();
    }

    // [cmbindustrylist] => Array
    //     (
    //         [0] => 2
    //         [1] => 3
    //     )

    // [txt_max_leadsPerweek] => 1
    // [txt_max_leadsPerMonth] => 2

    $NOTES = isset($_POST['cmbindustrylist']) ? db_input2(implode(",", $_POST['cmbindustrylist'])) : '';
    $LEADS_WEEK = isset($_POST['txt_max_leadsPerweek']) ? $_POST['txt_max_leadsPerweek'] : '0';
    $LEADS_MONTH = isset($_POST['txt_max_leadsPerMonth']) ? $_POST['txt_max_leadsPerMonth'] : '0';

    // if ($PLAN == 'P') {
    $PLATUNUM_VALUES = "vSnotes='$NOTES',vLeadPerWeek='$LEADS_WEEK',vLeadPerMonth='$LEADS_MONTH' ";
    $QUERY = UpdataData('service_providers', $PLATUNUM_VALUES, "id=$sess_user_id");
    //}


    $_SESSION[PROJ_SESSION_ID]->success_info = "Details updated successfuly";
    header("location: $disp_url");
    exit;
}

$TIME_DROPDOWN = GetXArrFromYID("select time,title from apptime", '3');

$TIME_FROM = GetXArrFromYID("select iWeekDayID,tStartTime from app_availability where iSPID=$sess_user_id ", '3');
$TIME_TO = GetXArrFromYID("select iWeekDayID,tEndTime from app_availability where iSPID=$sess_user_id ", '3');
$CHECKBOX = GetXArrFromYID("select iWeekDayID,cAvailable from app_availability where iSPID=$sess_user_id ", '3');
$SP_DATA = GetDataFromCOND('service_providers', " and id=$sess_user_id");
//DFA($SP_DATA);
$LEADS_WEEK = isset($SP_DATA[0]->vLeadPerWeek) ? (int)$SP_DATA[0]->vLeadPerWeek : '0';
$LEADS_MONTH = isset($SP_DATA[0]->vLeadPerMonth) ? (int)$SP_DATA[0]->vLeadPerMonth : '0';
$NOTES = isset($SP_DATA[0]->vSnotes) ? explode(",", db_output2($SP_DATA[0]->vSnotes)) : '';
if (!empty($NOTES))
    $NOTES = arrayValuesToKeys($NOTES);

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

        #calendar {

            margin: 0 auto;
        }

        hr {
            height: 2px;
            border-width: 0;
            color: gray;
            background-color: gray;
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
                        <div class="col-md-12">
                            <div class="card qmbgtheme shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Time Scheduling</h5>
                                </div>
                                <div class="col-md-2 mt-3">
                                    <a href="v_profile.php" class="btn btn-primary">Back</a>
                                    <a href="v_coverage.php" class="btn btn-danger">Update Coverage</a>
                                </div>
                                
                                <h5 class="m-3">Instructions</h5>
                                <ul>
                                    <li class="text-danger">Please mark consistent time you cannot receive appointments</li>
                                </ul>

                                <form action="<?php echo $edit_url; ?>" method="post">
                                    <input type="hidden" name="mode" value="U">
                                    <div class="card-body">
                                        <div id="LBL_INFO" class="alert alert-info">
                                            <?php echo $sess_info_str; ?>
                                        </div>
                                        <div id="alert_message"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- THE CALENDAR -->
                                                <ul class="list-unstyled">
                                                    <?php foreach ($WEEKDAY_ARR as $key => $value) {
                                                        if ($key == 0)
                                                            continue;
                                                        $T_FROM = (isset($TIME_FROM[$key])) ? $TIME_FROM[$key] : '';
                                                        $T_TO = (isset($TIME_TO[$key])) ? $TIME_TO[$key] : '';
                                                        $checkbox = (isset($CHECKBOX[$key]) && $CHECKBOX[$key] == 'Y') ? 'checked' : '';
                                                    ?>
                                                        <li class="d-flex align-items-center mb-3">
                                                            <span class="col-md-3 font-weight-bold"><?php echo $value; ?></span>
                                                            <span class="col-md-2">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" <?php echo $checkbox; ?> type="checkbox" name="day_<?php echo $key; ?>" value="Y" data-bootstrap-switch>
                                                                </div>
                                                            </span>
                                                            <span class="col-md-7 d-flex align-items-center">
                                                                <?php echo FillCombo2022('timefrom_' . $key, $T_FROM, $TIME_DROPDOWN, 'From'); ?>
                                                                <span class="mx-2">to</span>
                                                                <?php echo FillCombo2022('timeto_' . $key, $T_TO, $TIME_DROPDOWN, 'To'); ?>
                                                            </span>
                                                        </li>
                                                    <?php } ?>
                                                </ul>

                                                <hr>

                                                <div class="form-row mt-4" id="PLATINUM_EXTRAA_DETAILS" style="<?php echo $PLATINUM_DETAIL_STYLE ?>">
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
                                                            <label for="">Maximum Number of Leads per Week:</label>
                                                            <!-- <input type="text" name="txt_max_leadsPerweek" onkeypress="return numbersonly(event);" id="txt_max_leadsPerweek" class="form-control"> -->
                                                            <?php echo FillRadios($LEADS_WEEK, 'txt_max_leadsPerweek', $LEADS_PER_WEEK, '', 'form-control'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Maximum Number of Leads per Month:</label>
                                                            <!-- <input type="text" name="txt_max_leadsPerweek" onkeypress="return numbersonly(event);" id="txt_max_leadsPerweek" class="form-control"> -->
                                                            <?php echo FillRadios($LEADS_MONTH, 'txt_max_leadsPerMonth', $LEADS_PER_WEEK, '', 'form-control'); ?>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success btn-block mt-3">
                                                    <i class="fas fa-save"></i> Save
                                                </button>

                                            </div>
                                        </div>
                                    </div><!-- /.card-body -->
                                </form>
                            </div>
                        </div>
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
            //alert('hii');
            $('.in').multiselect({
                header: true,
                columns: 1,
                placeholder: 'Select industries',
                search: true,
                selectAll: false
            });


        });
    </script>
</body>

</html>