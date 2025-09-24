<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../phpmailer.php';

sql_close();
exit;

$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A'  ", '3');
$Q1=GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' and  iQuesID not in ('3','8','7','5')  ", '3');
$Q2=GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' and  iQuesID  in ('3')  ", '3');
//Get all zips and SP associations
$ZIP_SP_ARR=$BUYED_APPT_ARR=$SP_DETAILS=array();
//VERIFIED SPs
$RESPONSE_IDS = GetIDString2("select id from service_providers where 1 and cAdmin_approval='A' and cStatus='A'");
if (empty($RESPONSE_IDS) || $RESPONSE_IDS == '-1')
    $RESPONSE_IDS = '0';

$ZSQ = "SELECT * FROM service_providers_areas where service_providers_id in (" . $RESPONSE_IDS . ")  order by zip ASC ";
$ZSQR = sql_query($ZSQ);
if (sql_num_rows($ZSQR)) {
    while ($row = sql_fetch_object($ZSQR)) {
        if (!isset($ZIP_SP_ARR[$row->zip][$row->service_providers_id])) {
            $ZIP_SP_ARR[$row->zip][$row->service_providers_id] = array();
        }
        array_push($ZIP_SP_ARR[$row->zip][$row->service_providers_id],array($row));
    }
}

$SP_DATAQ = "select * from service_providers where 1 and cAdmin_approval='A' and cStatus='A' ";
$SP_DATAQR = sql_query($SP_DATAQ);
if(sql_num_rows($SP_DATAQR))
{
while($row=sql_fetch_object($SP_DATAQR))
{
        if (!isset($SP_DETAILS[$row->id])) {
            $SP_DETAILS[$row->id] = $row;
        }
        
}
}

//GET ALL BUYED APPOINTMENTS ARRAY
// $GEN_DAT_DETAILS = GetDataFromCOND('gen_competitive_examsdat', " and cStatus != 'X' and iAYrID='$acyr_id' order by iGCEID, iRank");
// $GEN_DAT_ARR = array();
// if (!empty($GEN_DAT_DETAILS)) {
//     foreach ($GEN_DAT_DETAILS as  $cat_f) {
//         if (!isset($GEN_DAT_ARR[$cat_f->iGCEID][$cat_f->iAppStageID])) $GEN_DAT_ARR[$cat_f->iGCEID][$cat_f->iAppStageID] = array();
//         array_push($GEN_DAT_ARR[$cat_f->iGCEID][$cat_f->iAppStageID], array('iGCEDatID' => $cat_f->iGCEDatID, 'vName' => $cat_f->vName));
//     }
// }
$BAQ = "select * from buyed_leads order by ibooking_id,iApptID,ivendor_id ";
$BAQR = sql_query($BAQ);
if(sql_num_rows($BAQR))
{
while($row=sql_fetch_object($BAQR))
{
        if (!isset($BUYED_APPT_ARR[$row->ibooking_id][$row->ivendor_id])) $BUYED_APPT_ARR[$row->ibooking_id][$row->ivendor_id] = array();
        array_push($BUYED_APPT_ARR[$row->ibooking_id][$row->ivendor_id], array('ibooking_id'=>$row->ibooking_id, 'iApptID'=> $row->iApptID, 'ivendor_id'=> $row->ivendor_id));
}
}

$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$Lead_price = 0;

$ALL_LEADS_ANS1 =$ALL_LEADS_ANS2= $COVERAGE_AREA_DETAILS=array();
//Getting All answers of QUes1 
$q_L_Ans = "select * from leads_answersheet where 1 and  iQuesID not in ('3','8','7','5') order by iResponseID,iQuesID";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        if (!isset($ALL_LEADS_ANS1[$row->iResponseID][$row->iQuesID])) {
            $ALL_LEADS_ANS1[$row->iResponseID][$row->iQuesID] = $row;
        }
    }
}

//Getting all the answers of  all the booking Quest2
$_q_ans = "select * from leads_answersheet where 1 and  iQuesID  in ('3') order by iResponseID,iQuesID";
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        if (!isset($ALL_LEADS_ANS2[$row->iResponseID][$row->iQuesID])) {
            $ALL_LEADS_ANS2[$row->iResponseID][$row->iQuesID] = $row;
        }
    }
}

//Getting all the area details
$CQ = "select * from areas ";
$CQR = sql_query($CQ);
if(sql_num_rows($CQR))
{
    while ($row = sql_fetch_object($CQR)) {
        if (!isset($COVERAGE_AREA_DETAILS[$row->id])) {
            $COVERAGE_AREA_DETAILS[$row->id] = $row;
        }
    }
}



//Get all sent mails of appointments from today 
$SEND_APPOINTMENTS_ARR=array();
$MQ = "select * from mailsent_test where date_format(dDateTime,'%Y-%m-%d')='".TODAY. "' order by iBookingID ";
$MQR = sql_query($MQ,"");
if(sql_num_rows($MQR))
{
    while ($row = sql_fetch_object($MQR)) {
        if (!isset($SEND_APPOINTMENTS_ARR[$row->iBookingID])) {
            $SEND_APPOINTMENTS_ARR[$row->iBookingID][$row->service_providers_id] = array($row);
        } elseif (isset($SEND_APPOINTMENTS_ARR[$row->iBookingID])) {
            $SEND_APPOINTMENTS_ARR[$row->iBookingID][$row->service_providers_id] = $row;
        }
    }
}
// DFA($BUYED_APPT_ARR);
// exit;
//$zip=00501;

//DFA($ALL_LEADS_ANS1);
//DFA($COVERAGE_AREA_DETAILS);
// DFA($ALL_LEADS_ANS2);
//exit;
$ALL_BOOKING_APPT = array();
$_q = "select AP.* from booking B right join appointments AP on B.iBookingID=AP.iBookingID where B.cStatus='A' and B.bverified='1' and AP.cService_status='P' and DATE_FORMAT(AP.dDateTime,'%Y-%m-%d') >=NOW()  and AP.cStatus!='X' ";
// $_q = "select AP.* from booking B right join appointments AP on B.iBookingID=AP.iBookingID where B.cStatus='A' and B.bverified='1' and AP.cService_status='P' and DATE_FORMAT(AP.dDateTime,'%Y-%m-%d') >=NOW()  and AP.cStatus!='X' ";
// $_q = "select iBookingID,iAreaID,dDate from booking where cService_status='P' and DATE_FORMAT(dDate,'%Y-%m-%d') >=NOW() and cStatus='A' ";
//iApptID,iAreaID,dDateTime,iAppTimeID
$_r = sql_query($_q, "Select Pending Appointments");
if (sql_num_rows($_r)) {
    while ($row = sql_fetch_object($_r)) {
        $BID = $row->iBookingID;
        //$iAreaID = $row->iAreaID;
        $APP_ID = $row->iApptID;
        if (!isset($ALL_BOOKING_APPT[$BID][$APP_ID])) $ALL_BOOKING_APPT[$BID][$APP_ID] = array();
        array_push($ALL_BOOKING_APPT[$BID][$APP_ID], array($row));
    }
}
//DFA($ALL_BOOKING_APPT);
// if(!empty($ALL_BOOKING_APPT) && isset($ALL_BOOKING_APPT))
// {
//     foreach ($ALL_BOOKING_APPT as $B_AR)
//      {
//         foreach ($B_AR as $AP_OBJ)
//          {
//             DFA($AP_OBJ); 

//         }
//     }
// }
// $BID = '1';
// if (!empty($ALL_BOOKING_APPT[$BID]) && isset($ALL_BOOKING_APPT[$BID])) {
//     foreach ($ALL_BOOKING_APPT[$BID] as $B_AR) {
//         foreach ($B_AR as $AP_OBJ) {
//             DFA($AP_OBJ[0]->dDateTime); 

//         }
//     }
// }
// exit;
$_q = "select iBookingID,iAreaID,dDate from booking where 1 and cStatus='A' and bverified='1' ";
// $_q = "select iBookingID,iAreaID,dDate from booking where cService_status='P' and DATE_FORMAT(dDate,'%Y-%m-%d') >=NOW() and cStatus='A' ";
//iApptID,iAreaID,dDateTime,iAppTimeID
$_r = sql_query($_q, "Select Pending Appointments");
if(sql_num_rows($_r))
{
    while ($row = sql_fetch_object($_r)) {
        $BID = $row->iBookingID;
        $iAreaID = $row->iAreaID;
        // $APP_ID = $row->iApptID;
        // $TIMEID = $row->iAppTimeID;
        // $DATE = $row->dDateTime;
        // echo $iApptID;
        // exit;
        //$BID, $iAreaID, $date
        //$AREAD_DETAILS = GetDataFromID('areas', 'id', $iAreaID);
        // $q1 = "SELECT zip FROM areas WHERE id='$iAreaID'";
        // $r1 = sql_query($q1);
        // list($zip) = sql_fetch_row($r1);
        $zip = $COVERAGE_AREA_DETAILS[$iAreaID]->zip;
        if (isset($ZIP_SP_ARR[$zip])) {
            foreach ($ZIP_SP_ARR[$zip] as  $SP_AR) {
                foreach ($SP_AR as  $SP_OBJ) {
                    $providersID = $SP_OBJ[0]->service_providers_id;
                    //$spcheck_q = "SELECT * FROM buyed_leads where  ivendor_id='$providersID' and ibooking_id='$BID' ";
                    //$spcheck_q_r = sql_query($spcheck_q, 'ERR.Checksp');
                    if (!isset($BUYED_APPT_ARR[$BID][$providersID])) {
                        // $q3 = "SELECT email_address,First_name FROM service_providers WHERE id='$providersID' and cAdmin_approval='A' and cStatus='A' ";
                        // $r3 = sql_query($q3);
                        $email = $SP_DETAILS[$providersID]->email_address;
                        $SPNAME = $SP_DETAILS[$providersID]->First_name;
                        //list($email, $SPNAME) = sql_fetch_row($r3);
                        $to = $email;
                        $subject = "Leads Alert TEST CRON JOB";

                        //$mail_content = "<p>City, State: " . $AREAD_DETAILS[0]->city . "," . $AREAD_DETAILS[0]->state . "</p>";
                        $mail_content = "";
                        //echo 'id=>' . $iAreaID;
                        //DFA($COVERAGE_AREA_DETAILS[$iAreaID]->city)
                        if (isset($COVERAGE_AREA_DETAILS[$iAreaID])) {
                            $LEAD_ADD = " " . $COVERAGE_AREA_DETAILS[$iAreaID]->city . " , " . $COVERAGE_AREA_DETAILS[$iAreaID]->state . " , " . $COVERAGE_AREA_DETAILS[$iAreaID]->zip;
                        }

                        $mail_content .= '<p class"mt-2"><strong>Lead ID:' . $BID . '</strong></p>';

                        //DFA($Leads_Ans);
                        if ($ALL_LEADS_ANS1[$BID][1]->iAnswerID == '101' || $ALL_LEADS_ANS1[$BID][1]->iAnswerID == '102') {
                            $Lead_price = 85;
                        } else {
                            $Lead_price = 125;
                        }

                        if (!empty($ALL_LEADS_ANS1[$BID])) {
                            foreach ($Q1 as $KEY => $VALUE) {
                                if (isset($ALL_LEADS_ANS1[$BID][$KEY]->iAnswerID)) {
                                    $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$ALL_LEADS_ANS1[$BID][$KEY]->iQuesID] . '</strong></p>';
                                    $mail_content .= '<p class="mb-2">  ' .  $Ans_ARR[$ALL_LEADS_ANS1[$BID][$KEY]->iAnswerID] . '</p>';
                                }
                            }
                            //for ($i = 0; $i < count($ALL_LEADS_ANS1[$BID]); $i++) {
                            //}
                        }

                        if (!empty($ALL_LEADS_ANS2[$BID])) {
                            foreach ($Q2 as $KEY => $VALUE) {
                                if (isset($ALL_LEADS_ANS2[$BID][$KEY]->iAnswerID)) {
                                    $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$ALL_LEADS_ANS2[$BID][$KEY]->iQuesID] . '</strong></p>';
                                    $Ansarr = explode(',', $ALL_LEADS_ANS2[$BID][$KEY]->iAnswerID);
                                    $mail_content .= '<ul>';
                                    foreach ($Ansarr as  $value) {
                                        $mail_content .= '<li>' .  $Ans_ARR[$value] . '</li>';
                                        //echo $Ans_ARR[$value] . ',';
                                    }
                                    $mail_content .= '</ul>';
                                }
                            }
                        }

                        //Get all pending appointments
                        // $_q4 = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >=NOW() and iBookingID='$BID' and cStatus!='X' order by dDateTime DESC";
                        // $_r4 = sql_query($_q4, "ERR.566");
                        // if (sql_num_rows($_r4)) {
                            $i = 1;
                            $mail_content .= '<p><strong>Contact is available these times:</strong></p>';
                            // while (list($iApptID, $iAreaID, $date, $TIMEID) = sql_fetch_row($_r4)) {
                            //     $mail_content .= "<p>Date: " . date('m/d/Y', strtotime($date)) . "  @ " . $TIMEPICKER_ARR[$TIMEID] . "</p>";
                            //     $i++;
                            // }
                        if (!empty($ALL_BOOKING_APPT[$BID]) && isset($ALL_BOOKING_APPT[$BID]))
                            {
                                foreach ($ALL_BOOKING_APPT[$BID] as $B_AR)
                                {
                                    foreach ($B_AR as $AP_OBJ)
                                    {
                                    //DFA($AP_OBJ); 
                                    //DFA($AP_OBJ[0]->dDateTime); 
                                    $DATE = $AP_OBJ[0]->dDateTime;
                                    $TIMEID = $AP_OBJ[0]->iAppTimeID;
                                    $mail_content .= "<p>Date: " . date('m/d/Y', strtotime($DATE)) . "  @ " . $TIMEPICKER_ARR[$TIMEID] . "</p>";
                                    $i++;

                                    }
                                }
                                $MAIL_BODY = '';
                                $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'api/email_template_leads.php');
                                $MAIL_BODY = str_replace('<PNAME>', $SPNAME, $MAIL_BODY);
                                $MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
                                $MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);
                                //echo $MAIL_BODY;
                                //exit;
                                if (!is_null($to) && !isset($SEND_APPOINTMENTS_ARR[$BID][$providersID])) {
                                    //Send_mail('', '', "darshankubal1@gmail.com", '', "", "darshankubal1@gmail.com", $subject, $MAIL_BODY, '');
                                    //echo $MAIL_BODY;
                                    //exit;
                                    $MID = NextID('Id', 'mailsent_test');
                                    sql_query("insert into mailsent_test values ($MID,$BID,'Y',NOW(),$providersID)", "mailsent table insert");
                                    echo ' <br>';
                                }
                            }
                        //}

                        //exit;

                    }
                }
            }
        }
        // while (list($providersID) = sql_fetch_row($r2)) {
        //     //check if he has already purchase the lead from that booking ID
        //     $spcheck_q = "SELECT * FROM buyed_leads where  ivendor_id='$providersID' and ibooking_id='$BID' ";
        //     $spcheck_q_r = sql_query($spcheck_q, 'ERR.Checksp');
        //     if (!sql_num_rows($spcheck_q_r)) {
    
        //         $q3 = "SELECT email_address,First_name FROM service_providers WHERE id='$providersID' and cAdmin_approval='A' and cStatus='A' ";
        //         $r3 = sql_query($q3);
        //         list($email, $SPNAME) = sql_fetch_row($r3);
        //         $to = $email;
        //         $subject = "Leads Alert";
    
        //         //$mail_content = "<p>City, State: " . $AREAD_DETAILS[0]->city . "," . $AREAD_DETAILS[0]->state . "</p>";
        //         $mail_content = "";
        //         //echo 'id=>' . $iAreaID;
        //         //DFA($COVERAGE_AREA_DETAILS[$iAreaID]->city)
        //         if(isset($COVERAGE_AREA_DETAILS[$iAreaID]))
        //         {
        //             $LEAD_ADD = " " . $COVERAGE_AREA_DETAILS[$iAreaID]->city . " , " . $COVERAGE_AREA_DETAILS[$iAreaID]->state . " , " . $COVERAGE_AREA_DETAILS[$iAreaID]->zip;
        //         }
    
        //         $mail_content .= '<p class"mt-2"><strong>Lead ID:' . $BID . '</strong></p>';
    
        //         //DFA($Leads_Ans);
        //         if ($ALL_LEADS_ANS1[$BID][1]->iAnswerID == '101' || $ALL_LEADS_ANS1[$BID][1]->iAnswerID == '102') {
        //             $Lead_price = 85;
        //         } else {
        //             $Lead_price = 125;
        //         }
    
        //         if (!empty($ALL_LEADS_ANS1[$BID])) {
        //             foreach ($Q1 as $KEY => $VALUE) {
        //                 if(isset($ALL_LEADS_ANS1[$BID][$KEY]->iAnswerID))
        //                 {
        //                     $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$ALL_LEADS_ANS1[$BID][$KEY]->iQuesID] . '</strong></p>';
        //                     $mail_content .= '<p class="mb-2">  ' .  $Ans_ARR[$ALL_LEADS_ANS1[$BID][$KEY]->iAnswerID] . '</p>';
    
        //                 }
                        
        //             }
        //             //for ($i = 0; $i < count($ALL_LEADS_ANS1[$BID]); $i++) {
        //             //}
        //         }
    
        //         if (!empty($ALL_LEADS_ANS2[$BID])) {
        //             foreach ($Q2 as $KEY => $VALUE) {
        //                 if(isset($ALL_LEADS_ANS2[$BID][$KEY]->iAnswerID))
        //                 {
        //                     $mail_content .= '<p class"mt-2"><strong>' . $Question_ARR[$ALL_LEADS_ANS2[$BID][$KEY]->iQuesID] . '</strong></p>';
        //                     $Ansarr = explode(',', $ALL_LEADS_ANS2[$BID][$KEY]->iAnswerID);
        //                     $mail_content .= '<ul>';
        //                     foreach ($Ansarr as  $value) {
        //                         $mail_content .= '<li>' .  $Ans_ARR[$value] . '</li>';
        //                         //echo $Ans_ARR[$value] . ',';
        //                     }
        //                     $mail_content .= '</ul>';
    
        //                 }
    
    
        //             }
        //         }
    
        //         //Get all pending appointments
        //         $_q4 = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >=NOW() and iBookingID='$BID' and cStatus!='X' order by dDateTime DESC";
        //         $_r4 = sql_query($_q4, "ERR.566");
        //         if (sql_num_rows($_r4)) {
        //             $i = 1;
        //             $mail_content .= '<p><strong>Contact is available these times:</strong></p>';
        //             while (list($iApptID, $iAreaID, $date, $TIMEID) = sql_fetch_row($_r4)) {
        //                 $mail_content .= "<p>Date: " . date('m/d/Y', strtotime($date)) . "  @ " . $TIMEPICKER_ARR[$TIMEID] . "</p>";
        //                 $i++;
        //             }
        //             $MAIL_BODY = '';
        //             $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'api/email_template_leads.php');
        //             $MAIL_BODY = str_replace('<PNAME>', $SPNAME, $MAIL_BODY);
        //             $MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
        //             $MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);
        //             //echo $MAIL_BODY;
        //             //exit;
        //             if (!is_null($to)) {
        //                  Send_mail('', '', "darshankubal1@gmail.com", '', "", "darshankubal1@gmail.com", $subject, $MAIL_BODY, '');
        //                 //echo $MAIL_BODY;
        //                 //exit;
        //                 $MID = NextID('Id', 'mailsent');
        //                 sql_query("insert into mailsent values ($MID,$BID,'Y',NOW(),$providersID)", "mailsent table insert");
        //                 echo 'success !!!!! <br>';
        //             }
        //         }
    
        //         //exit;
    
        //     }
        // }
    }

}

?>