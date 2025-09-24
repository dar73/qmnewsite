<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);

include "../includes/common.php";
include '../phpmailer.php';

$PAGE_TITLE2 = 'Send Alert';

$PAGE_TITLE .= $PAGE_TITLE2;

$edit_url = 'send_alert.php';
$unsubscribe_url = SITE_ADDRESS . 'unsubscribe.php';
$pay_url = SITE_ADDRESS . 'ctrl/sp_paylogin.php';

if (isset($_GET['mode'])) $mode = $_GET['mode'];
elseif (isset($_POST['mode'])) $mode = $_POST['mode'];
else $mode = 'E';

if (isset($_GET['id'])) $txtid = $_GET['id'];
elseif (isset($_POST['txtid'])) $txtid = $_POST['txtid'];
//else $mode = 'E';
$valid_modes = array("E", "U");
$mode = EnsureValidMode($mode, $valid_modes, "E");

$today=TODAY;

$LEAD_QUES = GetXArrFromYID("select iQuesID,vQuestion from leads_question where iQuesID='9'", '3');
$LEAD_ANS = GetXArrFromYID("select iAnsID,vAnswer from leads_answer where iQuesID='9'", '3');
$ACTIVE_LEADS = GetXArrFromYID("SELECT b.iBookingID
FROM booking b
JOIN appointments a ON b.iBookingID = a.iBookingID
WHERE a.cStatus != 'X'
GROUP BY b.iBookingID
HAVING COUNT(CASE WHEN DATE_FORMAT(a.dDateTime,'%Y-%m-%d') >='$today' THEN 1 END) = COUNT(*)");
$SP_ARR = GetXArrFromYID("SELECT id,concat(email_address,' | ',First_name,'',Last_name) as spname FROM service_providers WHERE 1 and cAdmin_approval='A' and cStatus='A' order by First_name,Last_name", '3');

$cmbspid = $cmbleadid = '';


if ($mode == "U") {
    //DFA($_POST);
    //exit;
    $LEAD_ID = isset($_POST['cmbleadid']) ? $_POST['cmbleadid'] : '0';
    $SP_ID = isset($_POST['cmbspid']) ? $_POST['cmbspid'] : '0';
    if (empty($LEAD_ID)) {
        header("location: $edit_url");
        exit;
    }

    $TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
    $Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A'  ", '3');
    $Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
    $UNSUBSCRIBED_LEADS_ARR = array();
    $qb = "select iSPID,iBookingID from unsubscribe_leads where cStatus='A' ";
    $qbr = sql_query($qb);
    if (sql_num_rows($qbr)) {
        while ($a = sql_fetch_assoc($qbr)) {
            if (!isset($UNSUBSCRIBED_LEADS_ARR[$a['iSPID']]))
                $UNSUBSCRIBED_LEADS_ARR[$a['iSPID']] = array();
            $UNSUBSCRIBED_LEADS_ARR[$a['iSPID']][] = $a['iBookingID'];
        }
    }

    $Lead_price = 0;

    $_q = "select distinct B.iBookingID,B.iAreaID,B.dDate,B.vNotes
from appointments A  join booking B on A.iBookingID = B.iBookingID
where  A.cService_status='P' and DATE_FORMAT(A.dDateTime,'%Y-%m-%d') >=NOW()  and A.cStatus!='X' and B.iBookingID='$LEAD_ID'  and B.cStatus='A' and B.bverified='1' ";
    // $_q = "select iBookingID,iAreaID,dDate from booking where cService_status='P' and DATE_FORMAT(dDate,'%Y-%m-%d') >=NOW() and cStatus='A' ";
    $_r = sql_query($_q, "Select Pending Appointments");
    while (list($BID, $iAreaID, $date, $txtnotes) = sql_fetch_row($_r)) {
        $txtnotes = db_output2($txtnotes);

        $Leads_Ans = $Leads_Ans2 = array();
        //$BID = GetXFromYID("select iBookingID from appointments where iApptID='$iApptID' ");

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

        $AREAD_DETAILS = GetDataFromID('areas', 'id', $iAreaID);
        $q1 = "SELECT zip FROM areas WHERE id='$iAreaID'";
        $r1 = sql_query($q1);
        list($zip) = sql_fetch_row($r1);
        $q2 = "SELECT service_providers_id FROM service_providers_areas WHERE zip='$zip' and service_providers_id='$SP_ID' ";
        $r2 = sql_query($q2);
        while (list($providersID) = sql_fetch_row($r2)) {

            //check if he has already purchase the lead from that booking ID
            $spcheck_q = "SELECT * FROM buyed_leads where  ivendor_id='$providersID' and ibooking_id='$BID' ";
            $spcheck_q_r = sql_query($spcheck_q, 'ERR.Checksp');
            if (!sql_num_rows($spcheck_q_r)) {

                $q3 = "SELECT email_address,First_name FROM service_providers WHERE id='$providersID' and cAdmin_approval='A' and cStatus='A' ";
                $r3 = sql_query($q3);
                list($email, $SPNAME) = sql_fetch_row($r3);
                $to = $email;

                $subject = "New Leads Alert";
                if (sql_num_rows(sql_query("SELECT * FROM mailsent WHERE service_providers_id='$providersID' and iApptID='$BID'  "))) {
                    $subject = "Leads Alert";
                }



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
                $_q4 = "select iApptID,iAreaID,dDateTime,iAppTimeID from appointments where cService_status='P' and DATE_FORMAT(dDateTime,'%Y-%m-%d') >=NOW() and iBookingID='$BID' and cStatus!='X' order by dDateTime DESC";

                $_r4 = sql_query($_q4, "ERR.566");
                if (sql_num_rows($_r4)) {
                    $i = 1;
                    $mail_content .= '<p><strong>Contact is available these times:</strong></p>';
                    while (list($iApptID, $iAreaID, $date, $TIMEID) = sql_fetch_row($_r4)) {
                        $mail_content .= "<p>Date: " . date('m/d/Y', strtotime($date)) . "  @ " . $TIMEPICKER_ARR[$TIMEID] . "</p>";
                        $i++;
                    }
                    $MAIL_BODY = '';
                    $NOTES = '';
                    if (!empty($txtnotes)) {
                        $NOTES = $txtnotes;
                    }
                    $mail_content .= $NOTES;

                    $mail_content .= '<br><p><strong>Are you interested in this lead?</strong></p>';
                    $mail_content .= '<p><a href="' . $pay_url . '?p=' . EncodeParam($providersID) . '&b=' . EncodeParam($BID) . '">Yes</a> <a href="' . $unsubscribe_url . '?p=' . EncodeParam($providersID) . '&ch=N&b=' . EncodeParam($BID) . '">No</a> </p>';
                    // $mail_content .= '<p>Are you interested in this lead?</p>';
                    $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'api/email_template_leads.php');
                    $MAIL_BODY = str_replace('<PNAME>', $SPNAME, $MAIL_BODY);
                    $MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
                    $MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);
                    //$MAIL_BODY = str_replace('<EXTAANOTES>', $NOTES, $MAIL_BODY);
                    if (!is_null($to)) {

                        if (isset($UNSUBSCRIBED_LEADS_ARR[$providersID])) {
                            if (!in_array($BID, $UNSUBSCRIBED_LEADS_ARR[$providersID])) {
                                //echo $MAIL_BODY;
                                //$to = 'darshankubal1@gmail.com'; //comment when online
                                SendInBlueMail($subject, $to, $MAIL_BODY, '', '', '', "kvikrantrao1@gmail.com,gemma@teamleadgeneration.onmicrosoft.com,service@thequotemasters.com");
                                //exit;
                                $MID = NextID('Id', 'mailsent');
                                //sql_query("insert into mailsent values ($MID,$BID,'Y',NOW(),$providersID)", "mailsent table insert");
                                //echo 'Success';
                                $_SESSION[PROJ_SESSION_ID]->success_info = "Mail sent successfuly";
                                //exit;
                            } elseif (in_array($BID, $UNSUBSCRIBED_LEADS_ARR[$providersID])) {
                                $_SESSION[PROJ_SESSION_ID]->alert_info = "SP has unsubscribed this lead";
                            }
                        } else {
                            //echo $MAIL_BODY;
                            //$to = 'darshankubal1@gmail.com'; //comment when online
                            SendInBlueMail($subject, $to, $MAIL_BODY, '', '', '', "kvikrantrao1@gmail.com,gemma@teamleadgeneration.onmicrosoft.com,service@thequotemasters.com");

                            $_SESSION[PROJ_SESSION_ID]->success_info = "Mail sent successfuly";
                            //exit;
                        }
                    }
                }

                //exit;

            }
        }
    }


    header("location: $edit_url");
    exit;


    // [mode] => U
    // [cmbleadid] => 0
    // [cmbspid] => 0
    // [submit] => submit
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.links.php' ?>
</head>
<?php include '_include_form.php' ?>

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
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="text-primary">Send Lead Alert</h3>
                                </div>
                                <div class="card-body">
                                    <h4 style="color: red;font-weight: bold;">Instructions:</h4>
                                    <ul>
                                        <li style="color: red;font-weight: bold;">If there is change in the email of the SP,then please go to sp edit and change the email and then trigger the mail.</li>
                                        <li style="color: red;font-weight: bold;">Make sure the SP covers the zip i.e zip is added to his coveragaes.</li>

                                    </ul>
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>

                                    <form action="<?php echo $edit_url; ?>" onsubmit="return ValidateForm();" method="POST" enctype="multipart/form-data">
                                        <!-- <input type="hidden" name="txtid" id="txtid" value="<?php //echo $txtid; 
                                                                                                    ?>"> -->
                                        <input type="hidden" name="mode" id="mode" value="U">

                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Lead</label>
                                                    <?php echo FillCombo2022('cmbleadid', $cmbleadid, $ACTIVE_LEADS, 'Lead'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">SP</label>
                                                    <?php echo FillCombo2022('cmbspid', $cmbspid, $SP_ARR, 'SP'); ?>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group row mt-4">
                                            <div class="col-sm-10">
                                                <button type="submit" name="submit" value="submit" class="btn btn-primary">Send</button>
                                            </div>
                                        </div>
                                    </form>

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
    <script src="../scripts/jquery.blockUI.js"></script>
    <script type="text/javascript" src="../scripts/common.js"></script>
    <script>
        function ValidateForm() {
            var err = 0;
            var ret_val = true;

            var cmbleadid = $('#cmbleadid');
            var cmbspid = $('#cmbspid');
            if ($.trim(cmbleadid.val()) == '0') {
                ShowError(cmbleadid, "Please select Lead");
                ret_val = false;
            } else {
                HideError(cmbleadid);
            }

            if ($.trim(cmbspid.val()) == '0') {
                ShowError(cmbspid, "Please select SP");
                ret_val = false;
            } else {
                HideError(cmbspid);
            }




            return ret_val;
        }

        $(document).ready(function() {



        });
    </script>
</body>

</html>

<?php
function SendInBlueMail($subject, $email, $contents, $attachment, $cc = '', $site_title = '', $bcc = '')
{
    if (empty($site_title))
        $site_title = 'Quote Masters';

    if (!empty($contents))
        //$contents .= '<br /><img src="'.SITE_ADDRESS.'img/mail_signature-latest.jpg" alt="Mail Signature" />';

        $cc = '';
    // if ($subject != 'OTP for Login') //empty($bcc) && 
    // $bcc = 'ops@thequotemasters.com';

    $config = array();
    $config['api_key'] = "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";
    $config['api_url'] = "https://api.sendinblue.com/v3/smtp/email";

    $message = array();
    $message['sender'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");
    $message['to'][] = array("email" => "$email");
    $message['replyTo'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");

    if (!empty($cc)) {
        $cc_arr = explode(",", $cc);
        for ($c = 0; $c < sizeof($cc_arr); $c++)
            $message['cc'][] = array("email" => "$cc_arr[$c]");
    }

    if (!empty($bcc)) {
        $bcc_arr = explode(",", $bcc);
        if (!in_array('ops@thequotemasters.com', $bcc_arr)) {
            $bcc_arr[] = 'ops@thequotemasters.com';
            $bcc = implode(",", $bcc_arr);
        }
        for ($b = 0; $b < sizeof($bcc_arr); $b++)
            $message['bcc'][] = array("email" => "$bcc_arr[$b]");
    } else {
        $bcc = 'ops@thequotemasters.com';
    }

    $message['subject'] = $subject;
    $message['htmlContent'] = $contents;

    if (!empty($attachment)) {
        if (is_array($attachment)) {
            $attachment_item[] = array('url' => $attachment);
            $attachment_list = array($attachment_item);

            // Ends pdf wrapper
            $message['attachment'] = $attachment_list;
        } else {
            $attachment_item = array('url' => $attachment);
            $attachment_list = array($attachment_item);
            // Ends pdf wrapper

            $message['attachment'] = $attachment_list;
        }
    }

    $message_json = json_encode($message);

    $ch = curl_init();
    curl_setopt(
        $ch,
        CURLOPT_URL,
        $config['api_url']
    );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: application/json',
        'api-key: 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',
        'content-type: application/json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}


?>