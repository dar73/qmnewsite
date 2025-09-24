<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$edit_url = '_debitamt.php';
$APP_ID=$_POST['appid'];
$SP_ID=$_POST['spid'];

//$PREMIUM_SP=GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers as spname where cStatus='A' and cPlatinumAgreement='Y' and cUsertype='P' and id=578","3");

$ACTIVE_APPT = array();
$MODAL_HEADING = $MODAL_BODY = '';
$MODAL_HEADING = strtoupper('Post Payment Appointments');

$MODAL_BODY .= '<div class="row">';
$MODAL_BODY .= '<div class="col-md-12">';
$MODAL_BODY .= '<form method="post" action="' . $edit_url . '" id="ADD_CALENDAR_FORM" class="form">';
$MODAL_BODY .= '<input type="hidden" name="mode" value="U">';
$MODAL_BODY .= '<input type="hidden" name="appid" value="' . $APP_ID . '">';
$MODAL_BODY .= '<input type="hidden" name="spid" value="' . $SP_ID . '">';


$MODAL_BODY .= ' <div class="form-group">
                    <label>AMOUNT <span class="text-danger">(Put the new Amount to be deducted in case of discount)</span></label>
                    <input type="text" name="AMT" id="AMT" class="form-control" value="125" required>
                </div>';

$MODAL_BODY .= '<div class="form-group">
                  <input type="submit" class="form-control  btn-primary"   value="Continue" />
                </div>';

$MODAL_BODY .= '</form>';
$MODAL_BODY .= '</div>';
$MODAL_BODY .= '</div>';

echo $MODAL_HEADING . '~~*~~' . $MODAL_BODY;
exit;
