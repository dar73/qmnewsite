<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
include '../phpmailer.php';
$SCHEDULES_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$id=(isset($_POST['id']))?$_POST['id']:'';
$mode=(isset($_GET['mode']))?$_GET['mode']:'';
$_q= "select * from appointments where iApptID='$id' ";
$return=0;
$MODAL_HEADING = $MODAL_BODY = '';
if ($mode=='view') {
    # code...
    $MODAL_HEADING='When would you like to reschedule your Appointment?';
    $_r=sql_query($_q,'');
    if (sql_num_rows($_r)) {
        $MODAL_BODY.='<form id="RescheduleFrm" >';
        $MODAL_BODY.= '<input type="hidden" name="mode" id="mode" value="RESCHEDULE">';
        $MODAL_BODY.= '<input type="hidden" name="Aid" id="Aid" value="'.$id.'">';
        $MODAL_BODY.= '<input type="hidden" name="noq" id="noq" value="'.sql_num_rows($_r).'">';
    
    
       for ($i=1;$o=sql_fetch_object($_r); $i++) { 
        $MODAL_BODY.= '<div class="form-group">
    
                        <label style="font-weight: 700 !important"> Book your NEW date  for Appointment '.$i.'</label>
                            <input type="text" id="date'.$i.'" data-scheduleid="'.$o->iApptID.'"  name="date'.$i.'" value="'.date('m-d-Y',strtotime($o->dDateTime)). '" class="form-control result datetime">
                            <input type="hidden" name="AppID'.$i. '" id="AppID" value="' . $o->iApptID . '">      
                        </div>';
            $MODAL_BODY .= '<div class="form-group">
    
                        <label style="font-weight: 700 !important"> Book your  time for Appointment ' . $i . '</label>
                           <select id="Time'.$i.'" name="Time'.$i.'" class="form-control mb-3 m-2">
                            <option selected>Click here to set up time</option>';
                           
                            foreach ($SCHEDULES_ARR as $key => $value) {
                                $selected=($key==$o->iAppTimeID)?'selected':'';
                                $MODAL_BODY .= '<option value='.$key.' '.$selected.'>'.$value.'</option>';
                            }


            $MODAL_BODY .=' </select>   
                        </div>'; 
    }
    $MODAL_BODY.= '<input type="button" id="Btnupdate" value="Update" class="btn btn-success">';
    $MODAL_BODY.='</form>';
    }
    echo $MODAL_HEADING . '~~*~~' . $MODAL_BODY;

} else if ($mode=='update') {
    $noq = (isset($_POST['noq'])) ? $_POST['noq'] : '';
    for ($i=1; $i<=$noq ; $i++) {
        $dDate = (isset($_POST['date'.$i])) ? $_POST['date'.$i] : '';
        // $Ndate = $_POST['date' . $j];
        $dateArr = explode('-', $dDate);
        $AppID = (isset($_POST['AppID'.$i])) ? $_POST['AppID'.$i] : '';
        $Time=(isset($_POST['Time' . $i])) ? $_POST['Time' . $i] : '';

        $q_r= "UPDATE appointments SET dDateTime='$dateArr[2]-$dateArr[0]-$dateArr[1]',iAppTimeID='$Time' WHERE iApptID='$AppID'";
       
        sql_query($q_r);

        //Send_Alert_To_SP_For_appt_change($AppID);
    }
    $return=1;
    echo $return;
    exit;
}

//send mail alert to SP
function Send_Alert_To_SP_For_appt_change($APP_ID)
{
    $SCHEDULES_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
    $APP_DATA = GetDataFromCOND("appointments", "and iApptID='$APP_ID' and cService_status='O' ");
    if (!empty($APP_DATA)) {

        $customerID = $APP_DATA[0]->iCustomerID;
        $dDateTime = $APP_DATA[0]->dDateTime;
        $timeID = $APP_DATA[0]->iAppTimeID;
        $CompanyName = GetXFromYID("select vName_of_comapny from customers where iCustomerID='$customerID' ");
        $SP_ID = GetXFromYID("select ivendor_id from buyed_leads where iApptID='$APP_ID' ");
        $email = GetXFromYID("select email_address from service_providers where id='$SP_ID' ");
        $full_name = GetXFromYID("select CONCAT(First_name, ' ', Last_name) AS full_name from service_providers where id='$SP_ID' ");

        $to = $email;
        $subject = "Appointment Date Change Notification";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content = "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Dear ," . $full_name . "</p>";
        $mail_content .= "<p>We would like to inform you that your appointment with " . $CompanyName . " has been changed by the customer.</p>";
        $mail_content .= "<p>The new appointment date is [" . date('m-d-Y', strtotime($dDateTime)) . ", " . $SCHEDULES_ARR[$timeID] . "].</p>";
        $mail_content .= '<p>You can accept the new appointment date, :<a href="https://thequotemasters.com/leads_schedule.php?spid=' . $SP_ID . '&appid=' . $APP_ID . '&c=Y">clicking here</a></p>';
        $mail_content .= '<p>You can reject the appointment date, <a href="https://thequotemasters.com/leads_schedule.php?spid=' . $SP_ID . '&appid=' . $APP_ID . '&c=N">click here</a></p>';
        $mail_content .= "<p>Please understand if you do not respond either way to this email within 12 hours the appt will automatically be cancelled and the customer will not accept a bid from you.</p>";
        $mail_content .= "Please DO NOT contact the customer directly as they have indicated to QM what new day and time works best for them. If you still contact them, you will receive NO CREDIT if they reject your suggested new appointment day and time.";
        $mail_content .= "<p>Best regards, </p>";
        $mail_content .= '<p>Quote masters </p>';
        $mail_content .= "</body>";
        $mail_content .= "</html>";
        //mail($to, $subject, $mail_content, $headers);
        Send_mail('', '', $to, '', '', '', $subject, $mail_content, '');
    }
}

?>
