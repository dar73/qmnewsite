<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$Leads_Ans = $Leads_Ans2 = array();
$Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' ", '3');
$Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
$q_L_Ans = "select * from leads_answersheet where iResponseID=" . $id . " and  iQuesID not in ('3','8')";
$q_r_L_Ans = sql_query($q_L_Ans, '');
if (sql_num_rows($q_r_L_Ans)) {
    while ($row = sql_fetch_object($q_r_L_Ans)) {
        $Leads_Ans[] = $row;
    }
}
$_q_ans = "select * from leads_answersheet where iResponseID=" . $id . " and  iQuesID  in ('3','8')";
$_q_ans_r = sql_query($_q_ans, '');
if (sql_num_rows($_q_ans_r)) {
    while ($row = sql_fetch_object($_q_ans_r)) {
        $Leads_Ans2[] = $row;
    }
}
//DFA($Leads_Ans2);

$SCHEDULE_ARR = GetDataFromID("schedule", "iBookingID", $id);
$BOOKING_ARR = GetDataFromID("booking", "iBookingID", $id);
$customerid = $BOOKING_ARR[0]->iCustomerID;
$CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);
$MODAL_HEADING = $MODAL_BODY = '';
$MODAL_HEADING='Leads Info';
 $MODAL_BODY.='<div class="row">
                            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Lead Number</span>
                                                <span class="info-box-number text-center text-muted mb-0">'.$id.'</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="info-box bg-light">
                                            <div class="info-box-content">
                                                <span class="info-box-text text-center text-muted">Date Of Enquiry</span>
                                                <span class="info-box-number text-center text-muted mb-0">'. date('l jS \of F Y h:i:s A', strtotime($Leads_Ans[0]->dtAnswer)).'</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-12">';

                                       
                                        if (!empty($Leads_Ans)) {
                                            for ($i = 0; $i < count($Leads_Ans); $i++) {  

                                                $MODAL_BODY.='<div class="post clearfix">
                                                    <div class="user-block">
                                                        <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                        <span class="username">
                                                            <a href="#">'. $Question_ARR[$Leads_Ans[$i]->iQuesID].'</a>
                                                        </span>
                                                        <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p>
                                                        Ans:'. $Ans_ARR[$Leads_Ans[$i]->iAnswerID].'
                                                    </p>
                                                    <p>
                                                        <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                                    </p>
                                                </div>';

                                           }
                                        }


                                        
                                        $MODAL_BODY.='<div class="post clearfix">
                                            <div class="user-block">
                                                <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                <span class="username">
                                                    <a href="#">'.$Question_ARR[$Leads_Ans2[0]->iQuesID].'</a>
                                                </span>
                                                <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Ans:';
                                                    $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
                                                    foreach ($Ansarr as  $value) {
                                                        $MODAL_BODY.= (isset($Ans_ARR[$value]))? $Ans_ARR[$value] . ',':'NA';
                                                    }

                                                
                                            $MODAL_BODY.='</p>
                                            <p>
                                                <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                            </p>
                                        </div>
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image"> -->
                                                <span class="username">
                                                    <a href="#">'.(isset($Question_ARR[$Leads_Ans2[1]->iQuesID]))? $Question_ARR[$Leads_Ans2[1]->iQuesID]:'NA'.'</a>
                                                </span>
                                                <!-- <span class="description">Sent you a message - 3 days ago</span> -->
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Ans:';
                                                $MODAL_BODY.= $Leads_Ans2[1]->vAns;
                                                    
                                            $MODAL_BODY.='</p>
                                            <p>
                                                <!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a> -->
                                            </p>
                                        </div>



                                    </div>
                                </div>
                            </div>;
                           
                        </div>';
echo $MODAL_HEADING . '~~*~~' . $MODAL_BODY;
?>