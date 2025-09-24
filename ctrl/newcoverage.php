<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
$PAGE_TITLE2 = 'SP Coverages';

$PAGE_TITLE .= $PAGE_TITLE2;

$modalTITLE = 'Edit ' . $PAGE_TITLE2;

$disp_url = 'service_providers_disp.php';

$edit_url = 'sp_coverage_edit.php';


$STATE_ARR = GetXArrFromYID("select distinct state,state from areas ", '3');
$COUNTY_ARR = GetXArrFromYID("select distinct County_name,County_name from areas ", '3');
$CITY_ARR = GetXArrFromYID("select distinct city,city from areas ", '3');
$UNIQUE_ZIPS = GetXArrFromYID("select distinct zip,zip from areas ", '3');

//$txtid = $sess_user_id;
$txtid = '';
if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];

if (empty($txtid)) {
    header("location:" . $disp_url);
    exit;
}
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';

$q = "SELECT * FROM service_providers WHERE id='$txtid'";

$r = sql_query($q);

$row = sql_fetch_assoc($r);
//DFA($row);

$COVERAGES_ARR = GetDataFromID('coverages', 'iproviderID', $txtid);

//DFA($COVERAGES_ARR);

//DFA($SELECTED_CITYS);

$cartArr = isset($_SESSION['COVERAGE']) ? $_SESSION['COVERAGE'] : array();
if ($mode == 'E') {
    $state = $_POST['state'];
    $countyArr = $_POST['county_name'];
    $cityArr = (isset($_POST['city'])) ? $_POST['city'] : '';
    $str1 = $str2 = $str3 = '';
    $str3 = " AND state IN ('" . implode("','", $state) . "')  ";
    $str1 .= "  AND  County_name IN ('" . implode("','", $countyArr) . "')";
    $str2 = '';
    if (!empty($cityArr)) {
        ///$cityarray = explode(',', $cityarr);
        $str2 .= "  AND  city NOT IN ('" . implode("','", $cityArr) . "')";
    }
    $Getzipq = "SELECT zip,id FROM areas WHERE 1 " . $str3 . $str1 . $str2;
    $GetzipqR = sql_query($Getzipq);
    sql_query("delete from service_providers_areas where service_providers_id='$txtid' ");
    while ($R = sql_fetch_assoc($GetzipqR)) {
        //array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
        sql_query("INSERT INTO service_providers_areas( service_providers_id, zip) VALUES ('$txtid','" . $R['zip'] . "')");
    }
    //$_SESSION['message'] = "Coverage Successfully Updated";
    $_SESSION[PROJ_SESSION_ID]->success_info = "Coverage Details Successfully Updated";
    header("location:" . $disp_url);
    exit;
} elseif ($mode == 'UPDATE_COVERAGE') {
    $cid = (isset($_POST['cid'])) ? $_POST['cid'] : '';
    $state = (isset($_POST['state'])) ? $_POST['state'] : '';
    $county = (isset($_POST['county_name'])) ? $_POST['county_name'] : '';
    $city = (isset($_POST['city'])) ? $_POST['city'] : '';
    $zips = (isset($_POST['zips'])) ? $_POST['zips'] : '';

    if (!empty($zips)) {
        $zips = implode(",", $zips);
    }

    if (!empty($county)) {
        $county = implode(",", $county);
    }

    if (!empty($city)) {
        $city = implode(",", $city);
    }
    sql_query("update coverages set vCounties='$county',vCities='$city',vZips='$zips' where iCoverageId='$cid' ", 'Update coverage');
    $_SESSION[PROJ_SESSION_ID]->success_info = "Coverage Details Successfully Updated";
    UpdateCoverages($txtid); //update coverages function
    header("location: $edit_url?mode=E&id=$txtid");
    exit;
} elseif ($mode == 'I') {
    // DFA($_POST);
    // exit;
    $state = (isset($_POST['state'])) ? $_POST['state'] : '';
    $county = (isset($_POST['county_name'])) ? $_POST['county_name'] : '';
    $city = (isset($_POST['city'])) ? $_POST['city'] : '';
    $zips = (isset($_POST['zips'])) ? $_POST['zips'] : '';
    LockTable('coverages');
    $icoverageID = NextID('iCoverageId', 'coverages'); //icrementing the ID of coverages table
    if (!empty($zips)) {
        $zips = implode(",", $zips);
    }

    if (!empty($county)) {
        $county = implode(",", $county);
    }
    // echo $zips;
    // exit;

    if (!empty($city)) {
        $city = implode(",", $city);
    }
    sql_query("INSERT INTO coverages VALUES ('$icoverageID','$txtid','$state','$county','$city','$zips')");
    UpdateCoverages($txtid); //update coverages function
    $_SESSION[PROJ_SESSION_ID]->success_info = "Coverage Details Successfully Added";
    header("location: $edit_url?mode=E&id=$txtid");
    exit;
}

//DFA($_SESSION);

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="https://querybuilder.js.org/assets/css/style.css" rel="stylesheet" />
    <!-- <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder/dist/css/query-builder.default.min.css" rel="stylesheet" />


</head>
<?php include '_include_form.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'load.header.php'; ?>
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

                            <div id="builder"></div>
                        </div>

                    </div>
                    <!-- <button class="btn btn-success" id="btn-set-sql">Set Rules</button> -->
                    <button class="btn btn-primary" id="btn-get-sql">Save Rules</button>
                    <button class="btn btn-warning" id="btn-reset">Reset</button>
                    <!-- /.container-fluid -->

            </section>

            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->

        <?php include 'load.footer.php' ?>
    </div>

    <?php include 'load.scripts.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/jQuery-QueryBuilder@2.5.2/dist/js/query-builder.standalone.js"></script>
    <script src="https://querybuilder.js.org/node_modules/sql-parser-mistic/browser/sql-parser.min.js"></script>
    <script src="../scripts/jquery.blockUI.js"></script>
    <script>
        var sql_import_export = 'name LIKE "%Johnny%" AND (category = 2 OR in_stock = 1)';
        $('#builder').queryBuilder({
            // plugins: [
            //     'bt-tooltip-errors',
            //     'not-group'
            // ],

            filters: [{
                id: 'state',
                label: 'States',
                type: 'integer',
                input: 'select',
                multiple: true,

                values: <?php echo json_encode($STATE_ARR); ?>,
                operators: ['in', 'not_in']
            }, {
                id: 'county',
                label: 'Counties',
                type: 'integer',
                input: 'select',
                multiple: true,

                values: <?php echo json_encode($COUNTY_ARR); ?>,
                operators: ['in', 'not_in']

            }, {
                id: 'city',
                label: 'Cities',
                type: 'integer',
                input: 'select',
                multiple: true,

                values: <?php echo json_encode($CITY_ARR); ?>,
                operators: ['in', 'not_in']

            }, {
                id: 'zip',
                label: 'Zips',
                type: 'integer',
                input: 'select',
                multiple: true,

                values: <?php echo json_encode($UNIQUE_ZIPS); ?>,
                operators: ['in', 'not_in']

            }]
        });

        $('#btn-reset').on('click', function() {
            $('#builder').queryBuilder('reset');
        });

        $('#btn-set-sql').on('click', function() {
            $('#builder').queryBuilder('setRulesFromSQL', sql_import_export);
        });


        $('#btn-get-sql').on('click', function() {
            var result = $('#builder').queryBuilder('getRules');
            var sql_query = $('#builder').queryBuilder('getSQL');
            console.log(JSON.stringify(result));
            console.log(sql_query);

            // if (result.sql.length) {
            //     console.log(result.sql + '\n\n' + JSON.stringify(result.params, null, 2));
            // }
        });





        function PageBlock(msg) {
            // blockUI code with custom message
            $.blockUI({

                // blockUI code with custom 
                // message and styling
                message: '<img src="loading.gif">',
                css: {
                    border: 'none',
                    backgroundColor: 'none'
                }
            });
        }

        function PageUnBlock(msg) {
            $.unblockUI();
        }


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
                    //console.log(res);
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

        function GET_REALTIME_ZIPS() {
            var city = $('#city').val();
            var state = $('#state').val();
            var county_name = $('#county_name').val();
            //var data = 'city=' + city + '&state=' + state + '&county=' + county_name + '&mode=REALTIME_ZIPS';
            //console.log(data);
            $.ajax({
                url: '_HandleCoverage.php',
                method: 'POST',
                data: {
                    city: city,
                    county: county_name,
                    state: state,
                    mode: 'REALTIME_ZIPS'
                },
                success: function(res) {
                    //console.log(res);
                    // var Data = res.split("~~");
                    $('#zipdiv').html(res);
                    // $('#MODAL_BODY').html(Data[1]);
                    $('.select2').select2()
                },
                error: function(err) {
                    console.log(err);
                }

            });

        }



        function EditCoverage(id, spid) {
            PageBlock();
            $.ajax({
                url: '_ShowCoverageModal2.php',
                method: 'POST',
                data: {
                    id: id,
                    spid: spid,
                    mode: 'UPDATE_COVERAGE'
                },
                success: function(res) {
                    //console.log(res);
                    PageUnBlock();
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
            PageBlock();
            $.ajax({
                url: '_ShowCoverageModal2.php',
                method: 'POST',
                data: {
                    spid: spid,
                    mode: 'I'
                },
                success: function(res) {
                    PageUnBlock();
                    //console.log(res);
                    var Data = res.split("~~");
                    $('#MODAL_TITLE').html(Data[0]);
                    $('#MODAL_BODY').html(Data[1]);
                    $('.select2').select2()
                },
                error: function(err) {
                    //console.log(err);

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
                    //console.log(res);
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
            } else {}
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
                    $('.select2').select2();

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


        function VALIDATE_CFORM() {
            var ret = true;
            var frm = document.FRM_COVERAGE;
            var state = $('#state').val();
            var county_name = $('#county_name').val();
            if (state.length == 0) {
                //$('#LBL_INFO').html(NotifyThis('Please select the states', 'error'));
                alert('Please select the states');
                //ret = false;
                return false;
            }

            if (county_name.length == 0) {
                //$('#LBL_INFO').html(NotifyThis('Please select the counties', 'error'));
                alert('Please select the counties');
                return false;
            }

            frm.submit();
            // console.log($('#state').val());

            // console.log($('#county_name').val());

            // console.log($('#city').val());

            if (ret) {}

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

                // console.log($('#state').val());

                // console.log($('#county_name').val());

                // console.log($('#city').val());

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