<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$Lead_price = 0;

$_q = "select iBookingID,iAreaID,dDate from booking where 1 and cStatus='A' and iBookingID='11' ";
// $_q = "select iBookingID,iAreaID,dDate from booking where cService_status='P' and DATE_FORMAT(dDate,'%Y-%m-%d') >=NOW() and cStatus='A' ";
$_r = sql_query($_q, "Select Pending Appointments");
while (list($BID, $iAreaID, $date) = sql_fetch_row($_r)) {

    $Leads_Ans = $Leads_Ans2 = array();
    //$BID = GetXFromYID("select iBookingID from appointments where iApptID='$iApptID' ");
    $Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A'  ", '3');
    $Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
    $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5') order by iQuesID";
    $q_r_L_Ans = sql_query($q_L_Ans, '');
    if (sql_num_rows($q_r_L_Ans)) {
        while ($row = sql_fetch_object($q_r_L_Ans)) {
            $Leads_Ans[] = $row;
        }
    }


    $_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3')";
    $_q_ans_r = sql_query($_q_ans, '');
    if (sql_num_rows($_q_ans_r)) {
        while ($row = sql_fetch_object($_q_ans_r)) {
            $Leads_Ans2[] = $row;
        }
    }


    // echo $iApptID;
    // exit;
    $AREAD_DETAILS = GetDataFromID('areas', 'id', $iAreaID);
    $q1 = "SELECT zip FROM areas WHERE id='$iAreaID'";
    $r1 = sql_query($q1);
    list($zip) = sql_fetch_row($r1);
    $q2 = "SELECT service_providers_id FROM service_providers_areas WHERE zip='$zip'";
    $r2 = sql_query($q2);
    while (list($providersID) = sql_fetch_row($r2)) {

        //check if he has already purchase the lead from that booking ID
        $spcheck_q = "SELECT * FROM buyed_leads where  ivendor_id='$providersID' and ibooking_id='$BID' ";
        //echo $spcheck_q;
        $spcheck_q_r = sql_query($spcheck_q, 'ERR.Checksp');
        if (!sql_num_rows($spcheck_q_r)) {

            $q3 = "SELECT email_address,First_name FROM service_providers WHERE id='$providersID' and cAdmin_approval='A' and cStatus='A' ";
            echo $q3;
            echo '<br>';
            $r3 = sql_query($q3);
            list($email, $SPNAME) = sql_fetch_row($r3);
            $to = $email;
            $subject = "Leads Alert";

            //$mail_content = "<p>City, State: " . $AREAD_DETAILS[0]->city . "," . $AREAD_DETAILS[0]->state . "</p>";
            $mail_content = "";
            $LEAD_ADD = " " . $AREAD_DETAILS[0]->city . " , " . $AREAD_DETAILS[0]->state . " , " . $AREAD_DETAILS[0]->zip;

            $mail_content .= '<p class"mt-2"><strong>Lead ID:' . $BID . '</strong></p>';

            //DFA($Leads_Ans);
            if ($Leads_Ans[0]->iAnswerID == '101' || $Leads_Ans[0]->iAnswerID == '102') {
                $Lead_price = 85;
            } else {
                $Lead_price = 125;
            }

            if (!empty($Leads_Ans)) {
                for ($i = 0; $i < count($Leads_Ans); $i++) {
                    $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$Leads_Ans[$i]->iQuesID] . '</strong></p>';
                    $mail_content .= '<p class="mb-2">  ' .  $Ans_ARR[$Leads_Ans[$i]->iAnswerID] . '</p>';
                }
            }

            if (!empty($Leads_Ans2)) {
                $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$Leads_Ans2[0]->iQuesID] . '</strong></p>';
                $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
                $mail_content .= '<ul>';
                foreach ($Ansarr as  $value) {
                    $mail_content .= '<li>' .  $Ans_ARR[$value] . '</li>';
                    //echo $Ans_ARR[$value] . ',';
                }
                $mail_content .= '</ul>';
            }

            //Get all pending appointments
            $_q4 = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >=NOW() and iBookingID='$BID' order by dDateTime DESC";
            echo $_q4;
            echo '<br>';
            $_r4 = sql_query($_q4, "ERR.566");
            if (sql_num_rows($_r4)) {
                $i = 1;
                $mail_content .= '<p><strong>Contact is available these times:</strong></p>';
                while (list($iApptID, $iAreaID, $date, $TIMEID) = sql_fetch_row($_r4)) {
                    $mail_content .= "<p>Date: " . date('m/d/Y', strtotime($date)) . "  @ " . $TIMEPICKER_ARR[$TIMEID] . "</p>";
                    $i++;
                }
                $MAIL_BODY = '';
                $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'api/email_template_leads.php');
                $MAIL_BODY = str_replace('<PNAME>', $SPNAME, $MAIL_BODY);
                $MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
                $MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);
                if (!is_null($to)) {
                    //Send_mail('', '', "$to", '', "", "darshankubal1@gmail.com,vik@thequotemasters.com,gemma@teamleadgeneration.onmicrosoft.com", $subject, $MAIL_BODY, '');
                    //$MID = NextID('Id', 'mailsent');
                    //sql_query("insert into mailsent values ($MID,$BID,'Y',NOW(),$providersID)", "mailsent table insert");
                    echo $MAIL_BODY;
                    echo '<br>';
                    echo 'Success';
                }
            }

            //exit;

        }
    }
}
