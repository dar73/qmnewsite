<?php
include "../includes/common.php";
include "../includes/thumbnail.php";

$PAGE_TITLE2 = 'SP Coverages';
$MEMORY_TAG = "SERVICE_PROVIDER_COVERAGE";

$PAGE_TITLE .= $PAGE_TITLE2;
$modalTITLE = 'Edit ' . $PAGE_TITLE2;
$disp_url = 'sp_coverages_disp.php';
$edit_url = 'sp_coverages_disp.php';



$execute_query = $is_query = true;
$txtkeyword = $cmb_spID = $cond = $params = $params2 = '';
$srch_style = 'display:none;';

$SERVICE_PROVIDERS = GetXArrFromYID("select id,concat(First_name,' ',Last_name) from service_providers where cStatus='A' ", "3");

if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $cmb_spID = $_POST['cmb_spID'];

    $params = '&cmb_spID=' . $cmb_spID;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['cmb_spID'])) $cmb_spID = $_GET['cmb_spID'];

    $params2 = '?cmb_spID=' . $cmb_spID;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($cmb_spID)) {
    $cond .= " and (vName LIKE '%" . $cmb_spID . "%')";
    $execute_query = true;

}
if ($execute_query) {
    $srch_style='';
    $COVERAGES_ARR = GetDataFromID('coverages', 'iproviderID', $cmb_spID);
}
//DFA($SERVICE_PROVIDERS);




//DFA($row);

//DFA($COVERAGES_ARR);



//DFA($_SESSION);



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
                                                        <?php echo FillCombo($cmb_spID, 'cmb_spID', 'COMBO', '0', $SERVICE_PROVIDERS, '', "form-control"); ?>
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
                                    <table class="table table-dark w-100" id="v_coverageTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>State</th>
                                                <th>Counties</th>
                                                <th>Cities (YOU DO NOT COVER)</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            if (!empty($COVERAGES_ARR)) {
                                                for ($u = 0; $u < sizeof($COVERAGES_ARR); $u++) {
                                                    $i = $u + 1;
                                                    $x_id = db_output($COVERAGES_ARR[$u]->iCoverageId);
                                                    $x_state = db_output($COVERAGES_ARR[$u]->vStates);
                                                    $x_counties = db_output($COVERAGES_ARR[$u]->vCounties);
                                                    $X_counties_arr = explode(",", $x_counties);
                                                    $x_county_str = implode(' , ', $X_counties_arr);
                                                    $x_cities = db_output($COVERAGES_ARR[$u]->vCities);
                                                    $X_cities_arr = explode(",", $x_cities);
                                                    $x_city_str = implode(' , ', $X_cities_arr);

                                            ?>

                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $x_state; ?></td>
                                                        <td style="max-width: 200px"><?php echo $x_county_str; ?></td>
                                                        <td style="max-width: 200px"><?php echo $x_city_str; ?></td>
                                                        

                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>


                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>


                    <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        <?php include 'load.footer.php' ?>


    </div>
    <?php include 'load.scripts.php' ?>
    <script>
        function GET_REALTIME_CITIES() {
            var cid = $('#cid').val();
            var s_counties = $('#county_name').val();
            $.ajax({
                url: '_Get_real_time_cities.php',
                method: 'POST',
                data: {
                    cid: cid,
                    countys: s_counties,
                    mode: 'REALTIME_CITIES'
                },
                success: function(res) {
                    console.log(res);

                    // var Data = res.split("~~");
                    $('#citydiv').html(res);
                    // $('#MODAL_BODY').html(Data[1]);
                    $('.select2').select2()
                },
                error: function(err) {
                    console.log(err);
                }
            });


        }

        function EditCoverage(id, spid) {
            $.ajax({
                url: '_ShowCoverageModal.php',
                method: 'POST',
                data: {
                    id: id,
                    spid: spid,
                    mode: 'UPDATE_COVERAGE'
                },
                success: function(res) {
                    console.log(res);
                    var Data = res.split("~~");
                    $('#MODAL_TITLE').html(Data[0]);
                    $('#MODAL_BODY').html(Data[1]);
                    $('.select2').select2()
                },
                error: function(err) {
                    console.log(err);
                }
            });
            $('#coverage_modal').modal('show');

        }

        function ADDNEWCOVERAGE(spid) {
            $.ajax({
                url: '_ShowCoverageModal.php',
                method: 'POST',
                data: {
                    spid: spid,
                    mode: 'I'
                },
                success: function(res) {
                    console.log(res);
                    var Data = res.split("~~");
                    $('#MODAL_TITLE').html(Data[0]);
                    $('#MODAL_BODY').html(Data[1]);
                    $('.select2').select2()
                },
                error: function(err) {
                    console.log(err);
                }
            });
            $('#coverage_modal').modal('show');

        }

        function Add_coverage() {
            var state = $('#cstate').val();
            var county_name = $('#counties').val();
            var city = $('#cities').val();
            $.ajax({
                url: '_Addcoverage.php',
                method: 'POST',
                data: {
                    state: state,
                    county: county_name,
                    city: city,
                    mode: 'ADD'
                },
                success: function(res) {
                    console.log(res);
                    location.reload();
                }
            });
        }

        function remove(state) {
            $.ajax({
                url: '_Addcoverage.php',
                method: 'POST',
                data: {
                    state: state,
                    mode: 'REMOVE'
                },
                success: function(res) {
                    location.reload();
                }
            });

        }

        function DeleteCoverage(id, spid) {
            if (confirm("Are you sure you want to delete this selection?")) {
                $.ajax({
                    url: '_HandleCoverage.php',
                    method: 'POST',
                    data: {
                        cid: id,
                        spid: spid,
                        mode: 'DELETE'

                    },
                    success: function(res) {
                        console.log(res);
                        if (res == 1) {
                            alert("Coverage deleted from your selection");
                            location.reload();
                        }

                    },
                    error: function(err) {
                        console.log(err);

                    }
                });

            } else {

            }



        }

        function UpdateCoverage() {
            var countarr = <?php echo (isset($_SESSION['COVERAGE'])) ? count($_SESSION['COVERAGE']) : 0; ?>;
            if (countarr < 1) {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    subtitle: 'Coverage',
                    body: 'Please add states,counties,city to the coverage list !!.'
                })
            } else {
                $.ajax({
                    url: '_update_coverage.php',
                    method: 'POST',
                    data: {
                        mode: 'UPDATE'
                    },
                    success: function(res) {
                        if (res == 1) {
                            $(document).Toasts('create', {
                                class: 'bg-success',
                                title: 'Success',
                                subtitle: 'coverage',
                                body: 'Successfuly updated the coverage.'
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 5000);
                        }

                    }
                });

            }
        }

        function getcitydropdown() {
            let state = $('#state').val();
            let county = $('#county_name').val();

            $.ajax({
                url: '../api/_getcities.php',
                method: 'POST',
                data: {
                    state: state,
                    county_name: county
                },
                success: function(res) {
                    //console.log(res);
                    var result = res.split('~~**~~');
                    var title = result[0];
                    var body = result[1];

                    //$('#cityselectorTITLE').html(title);
                    $('#c_replace').html(body);
                    $('.select2').select2()
                    // $('#city').multiselect();
                    // $('#cities').multiselect({
                    //     header: true,
                    //     columns: 1,
                    //     placeholder: 'Select Cities',
                    //     search: true,
                    //     selectAll: true
                    // });
                    //$('#cityselector').modal('show');
                }
            })
        }


        function GetCountys() {
            let state = $('#state').val();
            $.ajax({
                url: '_HandleCoverage.php',
                method: 'POST',
                data: {
                    state: state,
                    mode: 'GETCOUNTYS'
                },
                success: function(res) {
                    /// console.log(res);
                    $('#countydiv').html(res);

                    $('.select2').select2();
                    // $('#counties').multiselect('reload');
                },
                error: function(err) {
                    console.log(err);

                }
            });
        }

        function GetCities() {
            let county_name = $('#county_name').val();
            let state = $('#state').val();
            //console.log(county_name);
            $.ajax({
                url: '_HandleCoverage.php',
                method: 'POST',
                data: {
                    county: county_name,
                    state: state,
                    mode: 'GETCITIES'

                },
                success: function(res) {
                    // console.log(res);
                    var dataObj = res;
                    //$('#city').empty();
                    $('#citydiv').html(res);
                    $('.select2').select2();
                    //$('#city').multiselect('reload');
                    // $('.duallistbox').bootstrapDualListbox('refresh', true);

                },
                error: function(err) {
                    console.log(err);

                }
            });
        }

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

        })
        $(document).ready(function() {

            $('#v_coverageTable').DataTable({
                responsive: true
            });

            $('#FRM_COVERAGE').submit(function() {
                //alert('you clicked on submit');
                var ret = true;
                var state = $('#state').val();
                var county_name = $('#county_name').val();
                if (state.length == 0) {
                    $('#LBL_INFO').html(NotifyThis('Please select the states', 'error'));
                    ret = false;
                }
                if (county_name.length == 0) {
                    $('#LBL_INFO').html(NotifyThis('Please select the counties', 'error'));
                    ret = false;
                }
                console.log($('#state').val());
                console.log($('#county_name').val());
                console.log($('#city').val());
                return ret;
            });

            //fetch_data();
            $('#counties').multiselect({
                header: true,
                columns: 1,
                placeholder: 'Select Countys',
                search: true,
                selectAll: true
            });

            // $('#cstate').multiselect({
            //     header: true,
            //     columns: 1,
            //     placeholder: 'Select states',
            //     search: true,
            //     selectAll: true
            // });

            $('#cities').multiselect({
                header: true,
                columns: 1,
                placeholder: 'Select Cities',
                search: true,
                selectAll: true
            });
        });
    </script>
    <div class="modal fade" id="coverage_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" id="MODAL_TITLE">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="MODAL_BODY">
                    Modal body..
                </div>

                <!-- Modal footer -->
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->

            </div>
        </div>
    </div>
</body>

</html>