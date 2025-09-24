<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$PAGE_TITLE2 = 'Calendar';
$MEMORY_TAG = "CALENDAR";

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'calendar_admin.php';
$edit_url = 'addcalendar.php';

$execute_query = $is_query = false;
$txtspID = $txtappID = $cond = $params = $params2 = '';
$srch_style = '';
$json_events = $data = array();
$spname = '';
$PREMIUM_SP_ARR = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cUsertype='P' ", '3');


if (isset($_POST['srch_mode']) && $_POST['srch_mode'] == 'SUBMIT') {
    $txtspID = $_POST['txtspID'];

    $params = '&txtspID=' . $txtspID;
    header('location: ' . $disp_url . '?srch_mode=QUERY' . $params);
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'QUERY') {
    $is_query = true;

    if (isset($_GET['txtspID'])) $txtspID = $_GET['txtspID'];

    $params2 = '?txtspID=' . $txtspID;
} else if (isset($_GET['srch_mode']) && $_GET['srch_mode'] == 'MEMORY')
    SearchFromMemory($MEMORY_TAG, $disp_url);

if (!empty($txtspID)) {
    $txtspID = db_input2($txtspID);
    $cond .= " and (company_name LIKE '%" . $txtspID . "%')";
    $execute_query = true;
}


if ($execute_query)
    $srch_style = '';

if ($execute_query) {
    $access_token = GetXFromYID("select vAccessToken from service_providers where id=$txtspID ");
    $refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$txtspID ");
    if (!empty($access_token) && !empty($refresh_token)) {
        //$access_token = $access_token;
        //header('location:authtoken.php');
        //exit;
        $data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');
        if ($data['msg'] == 'token_expire') {
            $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
            $access_token = $ACCESS_RES['data']['access_token'];
            sql_query("update service_providers set vAccessToken='$access_token' where id='$txtspID' ");
        }
        $CALEN_EVENTS_RES = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');
        $data = $CALEN_EVENTS_RES['data'];
        //DFA($data);
        if (!empty($data)) {
            foreach ($data as $event) {
                //DFA($event);
                $title = isset($event['summary']) ? $event['summary'] : '';
                $formatted_events[] = array(
                    'id' => $event['id'],
                    'title' => $title,
                    'start' => $event['start']['dateTime'] ?? $event['start']['date'],
                    'end' => $event['end']['dateTime'] ?? $event['end']['date'],
                    'allDay' => isset($event['start']['date']) && !isset($event['start']['dateTime'])
                );
            }
            $json_events = $formatted_events;
        }
    }

    $spname = GetXFromYID("select concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cUsertype='P' and id='$txtspID' ");
}


// //DFA($data);


$_SESSION[PROJ_SESSION_ID]->srch_ctrl_arr[$MEMORY_TAG] = $_GET;

// $code = $_GET['code'];

//$access_token = '';
// echo $code;
// exit;
//$authcode = '4/0AcvDMrD9_jvSYJWVvoAEbVji_yZGRXLipd_TGmelckGStvPbVypd6Ua-OhHnqBHWJLYZVw';
// $_SESSION['google_access_token'] = '';
// exit;
// [access_token] => ya29.a0AXooCgvUMV6l31xtZ8mBCUQGvqjEYYZTOhaWdXZfEEd9lqt7t7Q3iUPRNmrSTOiy4wngPNngXl4Qu6DmdTNOubVYVVsuGTrq65avHpEa-_wnaytsYwVDGOhPqrRzae_iEEvKAXRARyHdtWuMdSuKk2YLFHEf64Le3v6EaCgYKAU8SARESFQHGX2MiE6qiP1078WtKPsZ7mT1x0g0171
//     [expires_in] => 3599
//     [refresh_token] => 1//0gDS9oFmMYqIzCgYIARAAGBASNwF-L9IrMEdL_NO_jwmTjkwN1WiUCVyUBTlKSpMwz7P1uJXnujXOz2ta-sRFgtA8-tkSNkNkhaw
//     [scope] => https://www.googleapis.com/auth/calendar
//     [token_type] => Bearer

// $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
// $access_token = $data['access_token'];
// $refresh_token = $data['refresh_token'];
// sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");

// $access_token = GetXFromYID("select vAccessToken from service_providers where id=$sess_user_id ");
// $refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$sess_user_id ");


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

                        <!-- /.col -->
                        <div class="col-md-12">
                            <div class="card qmbgtheme">
                                <div class="app-inner-layout__header-boxed p-0" id="SEARCH_RECORDS" style="<?php echo $srch_style; ?>">
                                    <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                                        <div class="app-page-title2">
                                            <div class="page-title-wrapper">
                                                <form class="form-inline p-3" name="frmSearch" id="frmSearch" action="<?php echo $disp_url; ?>" method="post">
                                                    <input type="hidden" name="srch_mode" id="srch_mode" value="SUBMIT" />
                                                    <input type="hidden" name="txtspID" id="txtspID" value="<?php echo $txtspID; ?>">
                                                    <!-- <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <?php //echo FillCombo2022('txtspID', $txtspID, $PREMIUM_SP_ARR, '') 
                                                        ?>
                                                    </div> -->
                                                    <div class="wm-100 mrm-50 position-relative form-group m-1">
                                                        <input type="text" name="spid" id="spid" value="<?php echo $spname; ?>" class="form-control">
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
                                <div class="card-header">Events

                                    <div class="float-right">

                                        <!-- <button type="button" class="btn btn-primary" onclick="GoToPage('<?php //echo $edit_url . '?spid=' . $txtspID; ?>');">Add New</button> -->

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <div class="row">

                                        <!-- /.col -->
                                        <div class="col-md-12">

                                            <!-- THE CALENDAR -->
                                            <div id="calendar"></div>

                                            <!-- /.card -->
                                        </div>
                                        <!-- /.col -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },

                events: <?php echo json_encode($json_events); ?>,

                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,


            });


            $("#spid").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    console.log(request);
                    $.ajax({
                        url: ajax_url2,
                        method: 'POST',
                        data: {
                            search: request.term,
                            'response': 'GET_PREMIUM_SP'
                        },
                        success: function(res) {
                            //console.log(res);
                            response($.map(res, function(item) {
                                return {
                                    label: item.label, // Displayed in the autocomplete suggestions
                                    value: item.label, // Displayed in the input field after selection
                                    id: item.value // 
                                };
                            }));
                            //$('#content').html(res);

                        }
                    });

                },
                select: function(event, ui) {
                    //console.log(ui.item.label);
                    // Set selection
                    //arr = ui.item.label.split("|");
                    // display the selected text
                    //$('#zipcode').val(ui.item.label); // save selected id to input
                    $('#txtspID').val(ui.item.id); // save selected id to input
                    //$('#zipid').val(ui.item.id); // save selected id to input
                    //$('#txtcustid').val(ui.item.id);
                    //window.location.href="choose_items.php?txtcustid="+ui.item.id;
                },
            });

        });
    </script>
</body>

</html>