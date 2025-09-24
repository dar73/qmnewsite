<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';

$token = (isset($_POST['token'])) ? db_input2($_POST['token']) : ''; //d7c9918c7c21e17qm@12340f7b6543f1
$bid = (isset($_POST['bid'])) ? db_input2($_POST['bid']) : '0'; //d7c9918c7c21e17qm@12340f7b6543f1
$spid = (isset($_POST['spid'])) ? db_input2($_POST['spid']) : '0'; //d7c9918c7c21e17qm@12340f7b6543f1

$CUSTOMER_ARR = $ADDRESS_ARR = array();
$Leads_Ans = $Leads_Ans2 = array();
$LEAD_D=array();

$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');

$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $bid . " and  iQuesID not in ('3','8','7','5')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}


$_q_ans = "select * from leads_answersheet where iResponseID=" . $bid . " and  iQuesID  in ('3')";
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}

$cond2 = '';
$_q_c = "SELECT iCustomerID, vFirstname, vLastname, vName_of_comapny, vPosition, vEmail, vPhone FROM customers";
$_qc_r = sql_query($_q_c, '');
if(sql_num_rows($_qc_r))
{

    while (list($iCustomerID, $vFirstname, $vLastname, $vName_of_comapny, $vPosition, $vEmail, $vPhone) = sql_fetch_row($_qc_r))
     {
        if (!isset($CUSTOMER_ARR[$iCustomerID]))
            $CUSTOMER_ARR[$iCustomerID] = array('iCustomerID' => $iCustomerID, 'vFirstname' => $vFirstname, 'vLastname' => $vLastname, 'vName_of_comapny' => $vName_of_comapny, 'vPosition' => $vPosition, 'vEmail' => $vEmail, 'vPhone' => $vPhone);
    }
}
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$_qa = "SELECT id,zip,zipcode_name,city,state,County_name FROM areas ";
$_qr = sql_query($_qa);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($_qr)) {
    if (!isset($ADDRESS_ARR[$id])) {
        $ADDRESS_ARR[$id] = array('id' => $id, 'zip' => $zip, 'zipcode_name' => $zipcode_name, 'city' => $city, 'state' => $state, 'County_name' => $County_name);
    }
}

$returnArr = array();
$data =$APPOIT= array();
if ($token == 'd7c9918c7c21e17qm@12340f7b6543f1') {
    $BUYED_BOOKING_ID = GetXArrFromYID("select ibooking_id from buyed_leads where ivendor_id='$spid' ");
    //DFA($BUYED_BOOKING_ID);
    if (!empty($BUYED_BOOKING_ID)) {
        $cond2 .= " and iBookingID not in(" . implode(",", $BUYED_BOOKING_ID) . ")";
    }

    $q = "select * from appointments where 1 and cService_status='P' and iAreaID in (SELECT DISTINCT t1.id FROM areas t1 INNER JOIN service_providers_areas t2 ON t1.zip=t2.zip WHERE 1 AND  t2.service_providers_id='$spid') " . $cond2 . "and cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >='" . TODAY . "' and iBookingID='$bid'  order by dDateTime DESC ";
    $r = sql_query($q);

    if (sql_num_rows($r)) {
        for ($i = 1; $o = sql_fetch_object($r); $i++) {
            //DFA($o);
            $AMOUNT = 0;
            $Leads_Ans = $Leads_Ans2 = array();
            $customerID = $o->iCustomerID;
            $Bid = $o->iBookingID;
            $appID = $o->iApptID;
            $date = db_output2($o->dDateTime);
            $TIME_ID = db_output2($o->iAppTimeID);
            $areaID = $o->iAreaID;
            $zip = $ADDRESS_ARR[$areaID]['zip'];
            $state = $ADDRESS_ARR[$areaID]['state'];
            $county = $ADDRESS_ARR[$areaID]['County_name'];
            $city = $ADDRESS_ARR[$areaID]['city'];
            $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $Bid . " and  iQuesID not in ('3','8','7','5')";
            $q_r_L_Ans = sql_query($q_L_Ans, '');
            if (sql_num_rows($q_r_L_Ans)) {
                while ($row = sql_fetch_object($q_r_L_Ans)) {
                    $Leads_Ans[] = $row;
                }
            }
            if ($Leads_Ans[0]->iAnswerID == '101' || $Leads_Ans[0]->iAnswerID == '102') {
                $AMOUNT = 85;
            } else {
                $AMOUNT = 125;
            }
            $APPOIT[] = array('ZIP'=>$zip,'APPID'=>$appID,'DATE & TIME'=> date('m/d/Y', strtotime($date)) . ' @ ' . $TIMEPICKER_ARR[$TIME_ID],'STATE'=>$state,'CITY'=>$city,'COUNTY'=>$county,'PRICE'=>$AMOUNT);

        }
    }

    if (!empty($Leads_Ans)) 
    {
        for ($i = 0; $i < count($Leads_Ans); $i++) 
        {

            $LEAD_D[] = array('Q'=> $Question_ARR[$Leads_Ans[$i]->iQuesID],'A'=> $Ans_ARR[$Leads_Ans[$i]->iAnswerID]);

        }
    }

    if (!empty($Leads_Ans2)) 
    {
        
        $ans='';
        $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
        foreach ($Ansarr as  $value) {
            $ans .= (isset($Ans_ARR[$value])) ? $Ans_ARR[$value] : 'NA';
        }
        $LEAD_D[]=array('Q'=> $Question_ARR[$Leads_Ans2[0]->iQuesID],'A'=>$ans);

    }


    // DFA($_SESSION);
    $data = array('APPOITMENTS' => $APPOIT, 'LEAD_DETAILS' => $LEAD_D);
    $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "success!", "data" => $data);
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
} else {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}
?>
