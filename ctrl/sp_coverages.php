<?php
include "../includes/common.php";
$PAGE_TITLE2 = 'Service Providers Coverage';
$MEMORY_TAG = "SERVICE_PROVIDER_C";

$PAGE_TITLE .= $PAGE_TITLE2;
$disp_url = 'sp_coverages.php';
$edit_url = 'sp_edit.php';
$excel_url = 'spdownload.php';
$stateid = $county = '0';
$cond = '';


$COUNTY_ARR = GetXArrFromYID("SELECT county_id, county_name FROM counties WHERE 1  and country_id=1 ", '3');
$STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 and country_id=1 order by state_name", '3');
//SELECT `city_id`, `county_id`, `city_name`, `state_id`, `country_id` FROM `cities` WHERE 1
$CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE 1 and country_id=1 order by city_name", '3');

$SP_DATA = array();
//SELECT `id`, `dDate`, `First_name`, `Last_name`, `company_name`, `phone`, `email_address`, `password`, `license_number`, `vLicence_file`, `dDate_Licence_expiry`, `vInsurance_file`, `dDate_insurance_expiry`, `email_verify_key`, `email_verify`, `street`, `state`, `county`, `city`, `vBrochure`, `vCertificate1`, `vCertificate2`, `vCertificate3`, `vFblink`, `vInstalink`, `cStatus`, `vLinkedInlink`, `fGratings`, `iInsurance`, `iBrochure`, `iLicence`, `iCertificate1`, `iCertificate2`, `iAwards`, `iFacebook`, `iInstagram`, `iLikendn`, `vGovtID`, `vWebsite`, `cAdmin_approval`, `cMailsent`, `cSource`, `cHaveBI`, `vFirebaseAuthToken`, `cCleaningStatus`, `cUsertype`, `vRefreshToken`, `vAccessToken`, `vSnotes`, `vLeadPerWeek`, `vLeadPerMonth`, `cPlatinumAgreement`, `dtAgreement`, `vSign`, `vBankName`, `vAcctNum` FROM `service_providers` WHERE 1
$SP_DATA = GetXArrFromYID("SELECT id, company_name FROM service_providers WHERE cStatus = 'A' and cUsertype='P' and id in (SELECT id FROM service_providers WHERE vRefreshToken IS NOT NULL
  AND vAccessToken IS NOT NULL and cStatus='A') and id in (SELECT pid FROM transaction2 where payment_status='A') order by id", '3');
$SP_NAMES = GetXArrFromYID("SELECT id, concat(First_name,' ',Last_name) as spname FROM service_providers WHERE cStatus = 'A' order by id", '3');

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $stateid = $_POST['stateid'];
    $county = $_POST['countyid'];
    $params = '&stateid=' . $stateid . '&countyid=' . $county;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;
    if (isset($_GET['stateid'])) $stateid = $_GET['stateid'];
    if (isset($_GET['countyid'])) $county = $_GET['countyid'];

    $params2 = '?stateid=' . $stateid . '&countyid=' . $county;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($stateid) && $stateid != '0') {
    $cond .= " and iCountryID = 1 and vStates='$stateid' ";
    # code...
}

if (!empty($county) && $county != '0') {
    $cond .= " and iCountryID = 1 and vCounties like '%$county%' ";
    # code...
}

if(!empty($SP_DATA))
{
    $cond .= " and iproviderID IN (" . implode(',', array_keys($SP_DATA)) . ") ";
}


//SELECT `iCoverageId`, `iproviderID`, `iCountryID`, `vStates`, `vCounties`, `vCities`, `vZips` FROM `coverages` WHERE 1
$q = "select * from coverages where 1 $cond";
$r = sql_query($q);
$providers = [];
while ($row = sql_fetch_array($r)) {
    $provider_id = $row['iproviderID'];
    if (!isset($providers[$provider_id])) {
        $providers[$provider_id] = [
            'name' => isset($SP_DATA[$provider_id]) ? $SP_DATA[$provider_id] : 'Unknown Provider',
            'states' => [],
            'counties' => [],
            'cities' => []
        ];
    }
    if (!empty($row['vStates'])) {
        $providers[$provider_id]['states'] = array_unique(array_merge($providers[$provider_id]['states'], explode(',', $row['vStates'])));
    }
    if (!empty($row['vCounties'])) {
        $providers[$provider_id]['counties'] = array_unique(array_merge($providers[$provider_id]['counties'], explode(',', $row['vCounties'])));
    }
    if (!empty($row['vCities'])) {
        $providers[$provider_id]['cities'] = array_unique(array_merge($providers[$provider_id]['cities'], explode(',', $row['vCities'])));
    }
}


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
                                <div class="patient-disp app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="">
                                    <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />

                                                    <div class="wm-300 mrm-50  form-group m-1">


                                                        <label for="state">State</label>
                                                        <?php echo FillCombo2022('stateid', $stateid, $STATE_ARR, 'state', 'form-control mul', 'GetCounties(this.value)'); ?>

                                                    </div>

                                                    <div class="wm-300 mrm-50  form-group m-1">
                                                        <label for="state">County</label>
                                                        <span id="COUNTY_DIV">
                                                            <?php echo FillCombo2022('countyid', $county, $COUNTY_ARR, 'county', 'form-control', ''); ?>

                                                        </span>

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
                                    <h2>The List includes Service providers who have completed the Calendar access and GUID details  </h2>

                                </div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <table class="table table-hover " id="cTable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <!-- <th>Provider ID</th> -->
                                                <th style="width: 20%;">Provider</th>
                                                <th style="width: 20%;">Company</th>
                                                <th style="width: 20%;">States</th>
                                                <th style="width: 20%;">Counties</th>
                                                <th style="width: 40%;">Cities</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($providers as $id => $provider): ?>
                                                <tr>
                                                    <td><?php echo $SP_NAMES[$id]; ?></td>
                                                    <td><?= htmlspecialchars($provider['name']) ?></td>
                                                    <td class="coverage" title="<?= htmlspecialchars(implode(', ', array_map(function($sid) use ($STATE_ARR) {
                                                        return isset($STATE_ARR[$sid]) ? $STATE_ARR[$sid] : $sid;
                                                    }, $provider['states']))) ?>">
                                                        <?= !empty($provider['states']) ? htmlspecialchars(implode(', ', array_map(function($sid) use ($STATE_ARR) {
                                                            return isset($STATE_ARR[$sid]) ? $STATE_ARR[$sid] : $sid;
                                                        }, $provider['states']))) : 'N/A' ?>
                                                    </td>
                                                    <td class="coverage" title="<?= htmlspecialchars(implode(', ', array_map(function($cid) use ($COUNTY_ARR) {
                                                        return isset($COUNTY_ARR[$cid]) ? $COUNTY_ARR[$cid] : $cid;
                                                    }, $provider['counties']))) ?>">
                                                        <?= !empty($provider['counties']) ? htmlspecialchars(implode(', ', array_map(function($cid) use ($COUNTY_ARR) {
                                                            return isset($COUNTY_ARR[$cid]) ? $COUNTY_ARR[$cid] : $cid;
                                                        }, $provider['counties']))) : 'N/A' ?>
                                                    </td>
                                                    <td class="coverage" title="<?= htmlspecialchars(implode(', ', array_map(function($cityid) use ($CITY_ARR) {
                                                        return isset($CITY_ARR[$cityid]) ? $CITY_ARR[$cityid] : $cityid;
                                                    }, $provider['cities']))) ?>">
                                                        <?= !empty($provider['cities']) ? htmlspecialchars(implode(', ', array_map(function($cityid) use ($CITY_ARR) {
                                                            return isset($CITY_ARR[$cityid]) ? $CITY_ARR[$cityid] : $cityid;
                                                        }, $provider['cities']))) : 'N/A' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

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
        function GetCounties(stateid) {
            var data = "response=GET_COUNTY&stateid=" + stateid;
            $.ajax({
                type: "POST",
                url: ajax_url2,
                data: data,
                success: function(data) {
                    $('#COUNTY_DIV').html(data);
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
                pageLength: 100,
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Download Excel',
                    title: 'Service Providers Coverage'
                }]


            });



        });
    </script>
</body>

</html>