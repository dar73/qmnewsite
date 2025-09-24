<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$Leads_Ans = $Leads_Ans2 = array();
$BID=GetXFromYID("select iBookingID from appointments where iApptID='$id' ");
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
//DFA($Question_ARR);
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7')";
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
//DFA($Leads_Ans2);


$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$SCHEDULE_ARR = GetDataFromID("appointments", "iApptID", $id);
//DFA($SCHEDULE_ARR);
$BOOKING_ARR = GetDataFromID("appointments", "iApptID", $id);
$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);
$MODAL_HEADING = $MODAL_BODY = '';
$MODAL_HEADING='Leads Info';
 $MODAL_BODY.='<div class="row">
                            <div class="col-12 col-lg-8 order-2 order-lg-1 my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-muted">Lead Number</span>
                                                <span class="info-box-number text-muted mb-0">QM-'.$id. '</span>
                                            </div>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-muted">Date of Submission</span>
                                                <span class="info-box-number text-muted mb-0">'. date('l' . ', ' . 'm/d/Y' . ', ' .'h:i A', strtotime($Leads_Ans[0]->dtAnswer)).'</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">';

                                       
                                        if (!empty($Leads_Ans)) {
                                            for ($i = 0; $i < count($Leads_Ans); $i++) {  

                                                $MODAL_BODY.='<div class="post clearfix pb-0">
                                                    <div class="user-block">
                                                        <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                        <span class="username">
                                                            <a href="#">'. $Question_ARR[$Leads_Ans[$i]->iQuesID].'</a>
                                                        </span>
                                                        <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p class="ml-5">
                                                    '. $Ans_ARR[$Leads_Ans[$i]->iAnswerID].'
                                                    </p>
                                                    <p>
                                                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                    </p>
                                                </div>';

                                           }
                                        }

                                       
                                        if (!empty($Leads_Ans2)) {

                                            $MODAL_BODY.='<div class="post clearfix pb-0">
                                                <div class="user-block">
                                                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                    <span class="username">
                                                        <a href="#">'.$Question_ARR[$Leads_Ans2[0]->iQuesID].'</a>
                                                    </span>
                                                    <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                </div>
                                                <!-- /.user-block -->
                                                <p class="ml-5">
                                            ';
                                                        $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
                                                        foreach ($Ansarr as  $value) {
                                                            $MODAL_BODY.= (isset($Ans_ARR[$value]))? $Ans_ARR[$value] . '<br>':'NA';
                                                        }
                                                $MODAL_BODY .= '</p>
                                                    <p>
                                                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                    </p>
                                                </div>';
                                        }
                                                 
                                                  $MODAL_BODY.= '</div>';
                                                  $MODAL_BODY .='  </div>';
                                                  $MODAL_BODY .='  </div>';
                                        






                            $MODAL_BODY.='<div class="col-12 col-md-12 col-lg-4 order-1 order-lg-2">
                                <h3 class="text-primary"><i class="fas fa-paint-brush"></i>Company Details</h3>

                                <br>
                                <div class="text-muted">
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Client Company</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'.$CUSTOMER_DET_ARR[0]->vName_of_comapny.'</b>
                                    </p>
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">First Name</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'. $CUSTOMER_DET_ARR[0]->vFirstname.'</b>
                                    </p>
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Last Name</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'. $CUSTOMER_DET_ARR[0]->vLastname.'</b>
                                    </p>
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Position</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'. $CUSTOMER_DET_ARR[0]->vPosition.'</b>
                                    </p>
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Email</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'. $CUSTOMER_DET_ARR[0]->vEmail.'</b>
                                    </p>
                                    <p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Phone</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'. $CUSTOMER_DET_ARR[0]->vPhone.'</b>
                                    </p>';
                                    $MODAL_BODY.= '<p class="text-sm d-lg-block d-flex"><span class="col-5 col-lg-12">Time</span>
                                        <b class="d-block ml-lg-0 col-7 col-lg-12">'.$TIMEPICKER_ARR[$SCHEDULE_ARR[0]->iAppTimeID].'</b>
                                    </p>';

                                   
                                    if (!empty($SCHEDULE_ARR)) {
                                        for ($i = 0,$j=1; $i < count($SCHEDULE_ARR); $i++,$j++) { 
                                            $MODAL_BODY.= '<hr>';
                                            $MODAL_BODY.= '<b class="d-block ml-lg-0 col-7 col-lg-12">Appointment Date</b>';

                                         //$MODAL_BODY.= date('l jS \of F Y h:i:s A', strtotime($SCHEDULE_ARR[$j]->dDateTime));
                                         $MODAL_BODY.= date('l' . ', ' . 'm/d/Y', strtotime($SCHEDULE_ARR[$i]->dDateTime));
                                        }
                                     
                                    }
                                            //DFA($SCHEDULE_ARR);
                                    
                                    
                               $MODAL_BODY.=' </div>
                            </div>
                        </div>';
echo $MODAL_HEADING . '~~*~~' . $MODAL_BODY;
?>