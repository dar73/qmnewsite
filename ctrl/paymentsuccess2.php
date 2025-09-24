<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';
// DFA($_POST);

// [TAC] => edA2fYKdBRhgNRjT9uyN4g==|NdXtJSfCZz//oW0MEQGLkktn6I6NXNQFpTLvFiR0u8H+pmvkxwZUfrHY4ZSONtErL2uzY4Ijscr8GMlLB9jjatNPTn6G6pv0ppPkOYYmZ+PozZW24PUo3Rn5uewOjlBylsekmwjCKNdZpWoiFpMXvVIoC0CV6OA5w8de8b4Gp4vEBdo1j8HJXDs4/m9p09QctgmRylGREDE+axvk+R8T1w==
//     [TRAN_CODE] => SALE
//     [BATCH_ID] => 9834389335
//     [USER_DATA_1] => 139
//     [USER_DATA_2] => 625
//     [CUST_NBR] => 3001
//     [MERCH_NBR] => 3130034428641
//     [DBA_NBR] => 1
//     [TERMINAL_NBR] => 3
//     [AMOUNT] => 0.00
//     [INDUSTRY_TYPE] => E
//     [REDIRECT_URL] => https://thequotemasters.com/ctrl/paymentsuccess.php
//     [ACCOUNT_NBR] => 123123
//     [EXP_DATE] => 1111
//     [CVV2] => 12111
//     [promocode] => Credit100
//     [check_agree] => on

$success_url = 'psuccess2.php';
$failure_url = 'pfail.php';
$TRANS_ID = $_POST['USER_DATA_2'];
$PROMOCODE = $_POST['promocode'];
///$TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");
$_q1 = "select pid FROM platinum_fee where id='$TRANS_ID'  ";
$_r1 = sql_query($_q1, "");
list($pid) = sql_fetch_row($_r1);

$_q2 = "update platinum_fee set payment_id='FULL DISCOUNT',payment_status='S' where id='$TRANS_ID' ";
sql_query($_q2, "");

$SP_ID = GetXFromYID("select pid from platinum_fee where id='$TRANS_ID'");
if (!empty($SP_ID)) sql_query("update service_providers set cRFeePaid='Y' where id='$SP_ID'");

$status = 'success';
$statusMsg = 'Your Payment has been Successful!';
//$_SESSION[PROJ_SESSION_ID]->success_info = $statusMsg;
header('location:' . $success_url);
exit;

// $_q1 = "select *  from platinumfee_dat where ibooking_id='$TRANS_ID' and iApptID='$iApptID' ";
// $_r1 = sql_query($_q1);
// if (sql_num_rows($_r1)) {
//     $statusMsg = 'Your Payment has failed!';
//     ///$_SESSION[PROJ_SESSION_ID]->error_info = $statusMsg;
//     header('location:' . $failure_url);
//     exit;
// }

// LockTable('credit_request');
// $DATE = NOW;
// $REQUEST_ID = NextID('iRequestID', 'credit_request');
// $credit_q = "INSERT INTO credit_request(iRequestID, dDate, vCode, iApptID, iPID, iTransactionID, cApprovalStatus, cStatus) VALUES ('$REQUEST_ID','$DATE','$PROMOCODE','$iApptID','$pid','$TRANS_ID','P','A')";
// sql_query($credit_q);
// UnlockTable();


// $_q2 = "update transaction set payment_id='$PROMOCODE',amount='0.00',payment_status='S' where id='$TRANS_ID' ";
// sql_query($_q2, "");

// $updatebookingdat = "update buyed_leads_dat set cStatus='A' where iTransID='$TRANS_ID' "; //update dat table
// sql_query($updatebookingdat);

// $_q3 = "INSERT INTO buyed_leads VALUES (NOW(),'$pid','$bookingID','$iApptID','0.00','$PROMOCODE')";
// sql_query($_q3, "");
// $_q4 = "UPDATE booking SET cService_status='O' WHERE iBookingID='$bookingID' ";
// sql_query($_q4, "");
// $_q5 = "UPDATE appointments SET cService_status='O' WHERE iBookingID='$bookingID' and iApptID='$iApptID' ";
// sql_query($_q5, "");

// $_q6 = "select iCustomerID,dDateTime,iAppTimeID from appointments where iApptID='$iApptID' ";
// $_q6r = sql_query($_q6, "");
// list($CUSTID, $DATEB, $TIMEID) = sql_fetch_row($_q6r);

// $Customer_name = GetXFromYID("select  CONCAT(vFirstname, ' ', vLastname) as full_name from customers where iCustomerID='$CUSTID' ");
// $ADATE = date('m-d-Y', strtotime($DATEB));
// $ATIME = $TIMEPICKER_ARR[$TIMEID];

// //send mail alert to customers
// $email = GetXFromYID("select vEmail from customers where iCustomerID='$CUSTID' ");
// $company_name = GetXFromYID("select company_name from service_providers where id='$pid' ");
// $Cleaners_name = GetXFromYID("select  CONCAT(First_name, ' ', Last_name) as full_name from service_providers where id='$pid' ");
// $SP_EMAIL = GetXFromYID("select  email_address  from service_providers where id='$pid' ");
// $to = db_output2($email);
// $subject = "Appointment Update";
// // Always set content-type when sending HTML email
// $headers = "MIME-Version: 1.0" . "\r\n";
// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// // More headers
// $headers .= 'From: thequotemasters.com<ops@janitorialquotemasters.com>' . "\r\n";
// $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
// $mail_content = '';
// $mail_content .= "<html>";
// $mail_content .= "<body>";
// $mail_content .= "<p>Hello $Customer_name,</p>";
// $mail_content .= "<p>Great news! </p>";
// $mail_content .= "<p>The Quote Masters matching team has found a qualified cleaner to meet with you on $ADATE, $ATIME  </p>";
// $mail_content .= "<p>$company_name, will be meeting with you and below you will find all the details you can review before the meeting time. We have included links for their company details and also specific links $Cleaners_name that you may want to view before your scheduled meeting.</p>";

// $mail_content .= '<p><a href="https://thequotemasters.com/sp_details.php?spid=' . $pid . '">Click here to see the cleaners profile</a></p>';
// //$mail_content .= '<ol type="1">';
// $mail_content .= 'We hope you have a great meeting and will follow up with you to make sure all went well!';
// $mail_content .= '<p><a href="https://thequotemasters.com/">visit QuoteMaster.com to connect with our agent</a></p>';
// $mail_content .= "<p>Happy bid collecting!<br>The Quote Master's Team</p>";
// $mail_content .= "</body>";
// $mail_content .= "</html>";
// //mail($to, $subject, $mail_content, $headers);
// //Send_mail('', '', $to, '', '', 'darshankubal1@gmail.com', $subject, $mail_content, '');
// //Send_mail('', '','darshankubal1@gmail.com', '', '', '', "Payment Ping", $PAYMENT_STR, '');
// SendInBlueMail($subject, $to, $mail_content, '', '', '', 'darshankubal1@gmail.com');
// //SendInBlueMail("Payment Ping", 'darshankubal1@gmail.com', $PAYMENT_STR, '', 'michael2@thequotemasters.com', '', '');
// //sql_query("update customers set cMailsent='Y' where vEmail='$Email' ", "update email status");   

// //MODIFIED TO SENT ALERT TO SP REGARDING LEAD PURCHASED
// $MAIL_BODY = GET_LEAD_MAIL_CONTENT($iApptID, $Cleaners_name);
// SendInBlueMail("Lead Purchase Success", $SP_EMAIL, $MAIL_BODY, '', '', '', "darshankubal1@gmail.com,michael2@thequotemasters.com");


$status = 'success';
$statusMsg = 'Your Payment has been Successful!';
//$_SESSION[PROJ_SESSION_ID]->success_info = $statusMsg;
header('location:' . $success_url);
exit;


// CREATE TABLE `credit_request` (
//     `iRequestID` int(11) NOT NULL AUTO_INCREMENT,
//     `dDate` datetime DEFAULT NULL,
//     `vCode` varchar(255) DEFAULT NULL,
//     `iApptID` int(11) DEFAULT NULL,
//     `iPID` int(11) DEFAULT NULL,
//     `iTransactionID` int(11) DEFAULT NULL,
//     `cApprovalStatus` char(1) NOT NULL DEFAULT 'P',
//     `cStatus` char(1) NOT NULL DEFAULT 'A',
//     PRIMARY KEY (`iRequestID`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
