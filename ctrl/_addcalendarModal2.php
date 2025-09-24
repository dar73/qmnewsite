<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$edit_url = '_add_appointment2.php';
$ACTIVE_LEADS = GetXArrFromYID("SELECT b.iBookingID
FROM booking b
JOIN appointments a ON b.iBookingID = a.iBookingID
WHERE a.cStatus != 'X' and a.cService_status='P'
GROUP BY b.iBookingID
HAVING COUNT(CASE WHEN DATE_FORMAT(a.dDateTime, '%Y-%m-%d') >= NOW() THEN 1 END) > 0 ");

$AREAS_ZIPS = GetXArrFromYID("select id,zip from areas ", '3');

$APP_TIME_ARR = GetXArrFromYID("select Id,time from apptime", '3');

$APP_ID = $_POST['id'];

$APPT_DATA = GetDataFromCOND('appointments', " and iApptID='$APP_ID' ");
$ZIP = $AREAS_ZIPS[$APPT_DATA[0]->iAreaID];
$APP_DATE = $APPT_DATA[0]->dDateTime;
$APP_TIME_ID = $APPT_DATA[0]->iAppTimeID;

$APP_TIME = $APP_TIME_ARR[$APP_TIME_ID];

$date = $APP_DATE;
$dayOfWeek = date('l', strtotime($date));  // 'l' (lowercase 'L') gives the full textual representation of the day
//echo $dayOfWeek;

$WEEKINDEX = $WEEKDAY_ARR_REVERSE[$dayOfWeek];

$PREMIUM_SP = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cPlatinumAgreement='Y' and cUsertype='P' and id in (SELECT service_providers_id FROM service_providers_areas WHERE zip='$ZIP') and id not in (select iSPID from app_availability where cAvailable='Y' 
AND ('$APP_TIME' BETWEEN tStartTime AND tEndTime)) ", '3');

$ACTIVE_APPT = array();
$MODAL_HEADING = $MODAL_BODY = '';
$MODAL_HEADING = 'Add Appointment to SP Calendar';

$MODAL_BODY .= '<div class="row">';
$MODAL_BODY .= '<div class="col-md-12">';
$MODAL_BODY .= '<form method="post" action="' . $edit_url . '" id="ADD_CALENDAR_FORM" class="form">';
$MODAL_BODY .= '<input type="hidden" name="mode" value="U">';
$MODAL_BODY .= '<input type="hidden" name="APPID" value="' . $APP_ID . '">';
$MODAL_BODY .= '<div class="form-group">' . FillCombo2022('txtspID', '', $PREMIUM_SP, 'SP', 'form-control', '') . '</div>';
$MODAL_BODY .= '<div class="form-group">
                <label>Event Title</label>
                <input type="text" class="form-control" id="title" name="title" value="">
                </div>';
$MODAL_BODY .= ' <div class="form-group">
                    <label>Event Description</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>';
$MODAL_BODY .= ' <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" id="location" class="form-control" value="">
                </div>';

$MODAL_BODY .= '<div class="form-group">
                  <input type="button" class="form-control  btn-primary" onclick="InitiatePayment2();"  value="Add Event" />
                </div>';

$MODAL_BODY .= '</form>';
$MODAL_BODY .= '</div>';
$MODAL_BODY .= '</div>';

echo $MODAL_HEADING . '~~*~~' . $MODAL_BODY;
exit;
