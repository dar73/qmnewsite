<?php

use Mpdf\Tag\I;

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$PAGE_TITLE2 = 'Calendar';

// $code = $_GET['code'];

$access_token = '';


$ACTYPE=GetXFromYID("select cCalendarAct from service_providers where id=$sess_user_id ");
if ($ACTYPE == 'M') {
    $_SESSION[PROJ_SESSION_ID]->alert_info = "Microsoft Calendar display coming soon..";
    header('location:v_profile.php');
    exit;
} 
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

$access_token = GetXFromYID("select vAccessToken from service_providers where id=$sess_user_id ");
$refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$sess_user_id ");


if (empty($access_token) && empty($refresh_token)) {
    //$access_token = $access_token;
    header('location:authtoken.php');
    exit;
} else {
    //$data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']);
    //$refresh_token = $data['refresh_token'];
    //sql_query("update service_providers set vRefreshToken='$refresh_token' ,vAccessToken='$access_token' where id='$sess_user_id' ");
    $ACCESS = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);

    if(empty($ACCESS['data']))
    {
        header('location:authtoken.php');
        exit;
    }
    $access_token = $ACCESS['data']['access_token'];
    // DFA($data);
    // exit;

    //$_SESSION['google_access_token'] = $access_token;
}
//echo $access_token;

//DFA($_SESSION);



$data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');
if ($data['msg'] == 'token_expire') {
    $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
    $access_token = $ACCESS_RES['data']['access_token'];
    sql_query("update service_providers set vAccessToken='$access_token' where id='$sess_user_id' ");
}
$CALEN_EVENTS_RES = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');
$data = $CALEN_EVENTS_RES['data'];
// if (empty($data)) {
//     $data = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
// }

// DFA($data);
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
$json_events = json_encode($formatted_events);


$user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
//echo $user_timezone;

// [title] => test Dahsa
//     [description] => sndsn,fm
//     [location] => test
//     [date] => 2024-07-29
//     [time_from] => 12:30
//     [time_to] => 14:00
//     [submit] => Add Event

$calendar_event = array(
    'summary' => 'test Darshan',
    'location' => 'Goa',
    'description' => 'test event'
);

$event_datetime = array(
    'event_date' => '2024-07-29',
    'start_time' => '12:30',
    'end_time' => '14:00'
);

//$google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $user_timezone);


// echo $user_timezone;


//AccessToken:ya29.a0AXooCgu3GaW43K8uwZw4B86dlHJlLOmADlDg018KOnRRDxjfEiLd4jN1cRBEscjF7F16TIDOkfJUFhjupVyPJVyR-I24UHZB7D1ND8qF7XenhYPdgaBXoGWhpKx5JvPIHfhWvpyH5ioXn-d0hgRu2KGu6Q3gxIpjRn4CaCgYKAXMSARESFQHGX2MiDMl2M_VODhOULebgn5vHiw0171 

//916018679775-d3hr1mdn3vi4os41il7e2b2jfdjg3k30.apps.googleusercontent.com

//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

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'calendar.php';
$edit_url = 'addcalendar.php';



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
                                <div class="card-header">Events


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

                events: <?php echo $json_events; ?>,

                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    //var title = prompt('Event Title:');

                    // if (title) {
                    //     var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    //     var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    //     $.ajax({
                    //         url: 'add_events.php',
                    //         data: 'title=' + title + '&start=' + start + '&end=' + end,
                    //         type: "POST",
                    //         success: function(json) {
                    //             alert('Added Successfully');
                    //         }
                    //     });
                    //     calendar.fullCalendar('renderEvent', {
                    //             title: title,
                    //             start: start,
                    //             end: end,
                    //             allDay: allDay
                    //         },
                    //         true
                    //     );
                    // }
                    calendar.fullCalendar('unselect');
                },

                editable: true,

            });

        });
    </script>
</body>

</html>