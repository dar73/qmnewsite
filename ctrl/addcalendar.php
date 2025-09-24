<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../includes/common.php";
include "../includes/GoogleCalendarApi.class.php";

$GoogleCalendarApi = new GoogleCalendarApi();

$PAGE_TITLE2 = 'Add Events';

$PAGE_TITLE .= $PAGE_TITLE2;

$disp_url = 'calendar_admin.php';
$edit_url = 'addcalendar.php';

$DEBIT_AMT = $AMOUNT= '0';

if (isset($_GET['spid']))
    $SPID = $_GET['spid'];
elseif (isset($_POST['spid']))
    $SPID = $_POST['spid'];
else $SPID = '0';

if (empty($SPID)) {
    header("location: $disp_url");
    exit;
}

$SP_DATA = GetDataFromCOND('service_providers', " and id=$SPID ");

$SP_FIRST_NAME=$SP_DATA[0]->First_name;
$SP_LAST_NAME=$SP_DATA[0]->Last_name;
$SP_ADDRESS=$SP_DATA[0]->street;
$SP_STATE=$SP_DATA[0]->state;
$SP_CITY=$SP_DATA[0]->city;


$access_token = $TRANS_REF= $TRANS_ID= '';
$access_token = GetXFromYID("select vAccessToken from service_providers where id=$SPID ");
$refresh_token = GetXFromYID("select vRefreshToken from service_providers where id=$SPID ");

if (empty($access_token)) {
    header('location:calendar_admin.php');
    exit;
}

$ACTIVE_LEADS = GetXArrFromYID("SELECT b.iBookingID
FROM booking b
JOIN appointments a ON b.iBookingID = a.iBookingID
WHERE a.cStatus != 'X' and a.cService_status='P'
GROUP BY b.iBookingID
HAVING COUNT(CASE WHEN DATE_FORMAT(a.dDateTime, '%Y-%m-%d') >= NOW() THEN 1 END) > 0 ");
$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

$ACTIVE_APPT = array();

$mode = '';
if (isset($_POST['mode']))
    $mode = $_POST['mode'];

if ($mode == 'U') {
    $title = db_input2($_POST['title']);
    $desc = db_input2($_POST['description']);
    $location = db_input2($_POST['location']);
    $APPID = db_input2($_POST['APPID']);
    $BID = db_input2($_POST['BID']);
    $date = ''; //db_input2($_POST['date']);
    $start_time = ''; //db_input2($_POST['time_from']);
    $end_time = ''; //db_input2($_POST['time_to']);

    $Q1ANS = GetXFromYID("select iAnswerID from leads_answersheet where iResponseID='$BID' and  iQuesID='1' ");

    if ($Q1ANS == '101' || $Q1ANS == '102') {
        $AMOUNT = 85;
    } elseif ($Q1ANS == '103') {
        $AMOUNT = 99;
    } else {
        $AMOUNT = 125;
    }

    $CFEE = $AMOUNT * 0.03;
    $DEBIT_AMT = $AMOUNT + $CFEE;
    $DEBIT_AMT = number_format($DEBIT_AMT, 2);

    //check if already sold
    $_q1 = "select * from appointments  where cService_status='O' and cStatus='A' and iApptID='$APPID' ";
    $_r1 = sql_query($_q1);
    // echo sql_num_rows($_r1);
    // exit;
    if(sql_num_rows($_r1))
    {
        $_SESSION[PROJ_SESSION_ID]->error_info = "Appointment slot has been sold already.";
        header("location: $disp_url");
        exit;

    }

    $AUTH_GUID = GetXFromYID("select payment_id from transaction2 where pid='$SPID' and payment_status='A' ");
    if(empty($AUTH_GUID))
    {
        $_SESSION[PROJ_SESSION_ID]->error_info = "SP's AUTH_GUID DOES NOT EXIST CANNOT PROCEED WITH DEBIT .";
        header("location: $disp_url");
        exit;
        
    }

   //STEP 1 STARTS 
    sql_query("LOCK TABLES transaction WRITE, buyed_leads WRITE,appointments WRITE");

    $ID = NextID('id', 'transaction');
    $_q = "insert into transaction values ('$ID','$BID','$APPID','$SPID','','','$DEBIT_AMT','" . NOW . "','online','P')";
    $_r = sql_query($_q, "");

    $curl = curl_init();

    $BATCH_ID = generateRandomNumber();

    $data = array('CUST_NBR' => '3001', 'MERCH_NBR' => '3130034428641', 'DBA_NBR' => '1', 'TERMINAL_NBR' => '3', 'TRAN_TYPE' => 'CCE1', 'AMOUNT' => $DEBIT_AMT, 'BATCH_ID' => $BATCH_ID, 'TRAN_NBR' => $ID, 'ORIG_AUTH_GUID' => $AUTH_GUID, 'INDUSTRY_TYPE' => 'E', 'FIRST_NAME' => $SP_FIRST_NAME, 'LAST_NAME' => $SP_LAST_NAME, 'ADDRESS' => $SP_ADDRESS, 'CITY' => $SP_CITY, 'STATE' => $SP_STATE, 'ZIP_CODE' => '12345', 'ACI_EXT' => 'RB');

    $data_string = http_build_query($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://secure.epx.com',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $xmlObject = simplexml_load_string($response);
    $json = json_encode($xmlObject);
    $array = json_decode($json, true);

    //DFA($array);
    $RESPONSE_ARR = array();
    foreach ($xmlObject->FIELDS->FIELD as $field) {
        $key = (string)$field['KEY'];
        $value = (string)$field;
        //echo "Key: $key, Value: $value\n";
        $RESPONSE_ARR[] = array($key => $value);
    }

    $PAYMENT_STR = json_encode($RESPONSE_ARR);
    SendInBlueMail("Payment Ping FROM CURL", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');

    if($RESPONSE_ARR['11']['AUTH_RESP']=='00')
    {
        $TRANS_ID = $RESPONSE_ARR['7']['TRAN_NBR'];
        $TRANS_REF = $RESPONSE_ARR['10']['AUTH_GUID'];

        $_q1 = "select  booking_id, pid,iApptID FROM transaction where id='$TRANS_ID'  ";
        $_r1 = sql_query($_q1, "");
        list($bookingID, $pid, $iApptID) = sql_fetch_row($_r1);
        $_q2 = "update transaction set payment_id='$TRANS_REF',payment_status='S' where id='$TRANS_ID' ";
        sql_query($_q2, "");

        $updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$TRANS_ID' "; //update dat table
        sql_query($updatebookingdat);

        $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','$TRANS_AMT','$TRANS_REF')";
        sql_query($_q3, "");
        $_q4 = "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
        sql_query($_q4, "");
        $_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
        sql_query($_q5, "");

        $_q6 = "select iCustomerID,dDateTime,iAppTimeID from appointments where iApptID='$iApptID' ";
        $_q6r = sql_query($_q6, "");
        list($CUSTID, $DATEB, $TIMEID) = sql_fetch_row($_q6r);

        $Customer_name = GetXFromYID("select  CONCAT(vFirstname, ' ', vLastname) as full_name from customers where iCustomerID='$CUSTID' ");
        $ADATE = date('m-d-Y', strtotime($DATEB));
        $ATIME = $TIMEPICKER_ARR[$TIMEID];

        //send mail alert to customers
        $email = GetXFromYID("select vEmail from customers where iCustomerID='$CUSTID' ");
        $company_name = GetXFromYID("select company_name from service_providers where id='$pid' ");
        $Cleaners_name = GetXFromYID("select  CONCAT(First_name, ' ', Last_name) as full_name from service_providers where id='$pid' ");
        $to = db_output2($email);
        $subject = "Appointment Update";
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
        $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
        $mail_content = '';
        $mail_content .= "<html>";
        $mail_content .= "<body>";
        $mail_content .= "<p>Hello $Customer_name,</p>";
        $mail_content .= "<p>Great news! </p>";
        $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
        $mail_content .= "<p>$company_name, will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links $Cleaners_name that you may want to view before your scheduled meeting.</p>";

        $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid=' . $pid . '">Click here to see the cleaners profile</a></p>';
        //$mail_content .= '<ol type="1">';
        $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
        $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
        $mail_content .= "<p>Happy bid collecting!<br>The Quote Master's Team</p>";
        $mail_content .= "</body>";
        $mail_content .= "</html>";
        //mail($to, $subject, $mail_content, $headers);
        //Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
        //Send_mail('', '','darshankubal1@gmail.com', '', '', '', "Payment Ping", $PAYMENT_STR, '');
        SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com');
        SendInBlueMail("Payment Ping", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');

        //STEP 2 START

        $q = "SELECT date_format(A.dDateTime,'%Y-%m-%d'),time_format(T.time,'%H:%i') FROM appointments A INNER JOIN apptime T ON T.Id = A.iAppTimeID where A.cService_status='P' and A.cStatus='A' and A.iApptID='$APPID'  ";
        $r = sql_query($q);
        if (sql_num_rows($r)) {
            list($date, $start_time) = sql_fetch_row($r);
            $end_time = date("H:i", strtotime("$start_time +30 minutes"));
        }

        $calendar_event = array(
            'summary' => $title,
            'location' => $location,
            'description' => $desc
        );

        $event_datetime = array(
            'event_date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time
        );


        // Get the user's calendar timezone 
        $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
        //list($timezone,$msg) = $user_timezone;
        if ($user_timezone['msg'] == 'fail') {
            $ACCESS_RES = $GoogleCalendarApi->RefreshAccessToken(GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, $refresh_token);
            $access_token = $ACCESS_RES['data']['access_token'];
            sql_query("update service_providers set vAccessToken='$access_token' where id='$SPID' ");
        }
        $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token);
        $timezone = $user_timezone['data'];
        //$data = $GoogleCalendarApi->GetCalendarEvents($access_token, 'primary');

        //DFA($data);

        // Create an event on the primary calendar 
        $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $timezone);

        $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$SPID','$BID','$APPID','100','TEST')";
        sql_query($_q3, "");
        $_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$BID' and iApptID='$APPID' ";
        sql_query($_q5, "");
        
        $_SESSION[PROJ_SESSION_ID]->success_info = "Event Details Successfully Inserted";
    }else{
        $_SESSION[PROJ_SESSION_ID]->error_info = "Event Details process  failed !!";
    }

    //DFA($keyValuePairs);
    

    header("location: $disp_url");
    exit;
}

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
                                <div class="card-header">Events</div>

                                <div class="card-body">
                                    <div id="LBL_INFO"><?php echo $sess_info_str; ?></div>
                                    <div id="alert_message"></div>
                                    <div class="row">

                                        <!-- /.col -->
                                        <div class="col-md-12">

                                            <form method="post" onsubmit="return ValidateForm();" action="<?php echo $edit_url; ?>" class="form">
                                                <input type="hidden" name="mode" value="U">
                                                <input type="hidden" name="spid" value="<?php echo $SPID; ?>">
                                                <div class="form-group">
                                                    <label>Event Title</label>
                                                    <input type="text" class="form-control" id="title" name="title" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Event Description</label>
                                                    <textarea name="description" id="description" class="form-control"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Location</label>
                                                    <input type="text" name="location" id="location" class="form-control" value="">
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="date" class="form-control" value="" required="">
                                                </div>
                                                <div class="form-group time">
                                                    <label>Time</label>
                                                    <input type="time" name="time_from" class="form-control" value="">
                                                    <span>TO</span>
                                                    <input type="time" name="time_to" class="form-control" value="">
                                                </div> -->
                                                <div class="form-group">
                                                    <?php echo FillCombo2022('BID', '', $ACTIVE_LEADS, 'Lead', 'form-control', 'GetAPP(this.value);') ?>
                                                </div>
                                                <div class="form-group">
                                                    <span id="APP_HTML">
                                                        <?php echo FillCombo2022('APPID', '', $ACTIVE_APPT, 'Appointment', 'form-control', '') ?>

                                                    </span>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" class="form-control btn-primary" name="submit" value="Add Event" />
                                                </div>
                                            </form>



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
        function GetAPP(id) {
            var data = "response=GET_APPOINTMENTS&id=" + id;
            $.post(ajax_url2, data, function(data) {
                $('#APP_HTML').html(data);
            });

        }

        function ValidateForm() {
            var err = 0;
            var ret_val = true;

            var title = $('#title');
            var description = $('#description');
            var location = $('#location');
            var BID = $('#BID');
            var APPID = $('#APPID');

            if ($.trim(title.val()) == '') {
                ShowError(title, "Please enter title");
                err++;
            } else {
                HideError(title);
            }

            if ($.trim(description.val()) == '') {
                ShowError(description, "Please enter description");
                err++;
            } else {
                HideError(description);
            }

            if ($.trim(location.val()) == '') {
                ShowError(location, "Please enter location");
                err++;
            } else {
                HideError(location);
            }

            if ($.trim(BID.val()) == '0') {
                ShowError(BID, "Please select Lead");
                err++;
            } else {
                HideError(BID);
            }

            if ($.trim(APPID.val()) == '0') {
                ShowError(APPID, "Please select appointment");
                err++;
            } else {
                HideError(APPID);
            }

            if (err > 0) {
                ret_val = false;
            }

            let text = "Are you sure you want to add the appointment to the SP calendar ? This process might take few minutes be patient dont reload the page!";
            if (confirm(text) == true) {
                
            } else {
                ret_val=false;
                //text = "You canceled!";
            }

            return ret_val;
        }

        $(document).ready(function() {

        });
    </script>
</body>

</html>

<?php
function generateRandomNumber()
{
    $min = 1;
    $max = 9999999999; // Maximum 10-digit number

    return mt_rand($min, $max);
}


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