<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common_front.php');
// var_dump($_POST);
// exit;
//   'schedule' => string '' (length=0)
//   'areaid' => string '8857' (length=4)
//   'name_of_company' => string 'Darshan' (length=7)
//   'full_name' => string 'sfa' (length=3)
//   'position' => string 'sgqjg' (length=5)
//   'phone' => string 'sha' (length=3)
//   'email' => string 'darshankubal1@gmail.com' (length=23)
//   'how_often' => string '1x' (length=2)
//   'cleaning_situation' => string 'In house we clean our own office' (length=32)
//   'cleaning_status' => string 'Current cleaners are not dusting well' (length=37)
//   'rating' => string '1' (length=1)
//   'No_of_quotes' => string '1' (length=1)
//   'self_schedule' => string '1' (length=1)
if (isset(
    $_POST['name_of_company'],
    $_POST['full_name'],
    $_POST['position'],
    $_POST['phone'],
    $_POST['email'],
    $_POST['how_often'],
    $_POST['cleaning_situation'],
    // $_POST['cleaning_status'],
    $_POST['rating'],
    $_POST['No_of_quotes'],
    $_POST['self_schedule'],
    $_POST['areaid'],
    $_POST['change_in_company']
)) {
    $name_of_company = db_input($_POST['name_of_company']);
    $full_name = db_input($_POST['full_name']);
    $position = db_input($_POST['position']);
    $phone = db_input($_POST['phone']);
    $email = db_input($_POST['email']);
    $how_often = db_input($_POST['how_often']);
    $cleaning_situation = db_input($_POST['cleaning_situation']);
    $cleaning_status = db_input($_POST['cleaning_status']);
    $rating = db_input($_POST['rating']);
    $num_of_q = db_input($_POST['No_of_quotes']);
    $self_schedulr = db_input($_POST['self_schedule']);
    $areaid = db_input($_POST['areaid']);
    $self_schedule = ($self_schedulr == '1') ? 'Yes' : 'No';
    $to = $email;
    $subject = "Email Verification";
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <ops@janitorialquotemasters.com>' . "\r\n";
    $headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";
    $mail_content .= "<html>";
    $mail_content .= "<body>";
    $mail_content .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;"><p>Hi,</p>';
    $mail_content .= "<p>Welcome to Quote Masters where “YOU are the MASTER of the Janitorial Quotes you get!”</p>";
    $mail_content .= "<p>You have completed the request form and now you will receive  bids for your janitorial needs. Each cleaning company that will visit your office has been selected because they have some of the highest ratings on Google / Yelp. </p>";
    $mail_content .= "<p>Shortly once our Customer Service Team selects your cleaners you will receive the Quote Master Report for each of them.</p>";
    $mail_content .= "<p>You will know the following:</p>";
    $mail_content .= "<ol>
                            <li>Company name and contact information</li>
                            <li>Years of expertise</li>
                            <li>Current star rating</li>
                            <li>Name of the manager you will be meeting with</li>
                            <li>Day and time you selected to meet</li>
                           
                            </ol>  ";
    $mail_content .= '<p>Thanks for choosing to be the Master of your janitorial needs and THANKS for choosing to allow QUOTE MASTERS serve YOU at this time!!</p>';

    LockTable('customers');
    $customerID = NextID('iCustomerID', 'customers');
    $q = "INSERT INTO customers(iCustomerID, vFullname,vName_of_comapny, vPosition, vEmail,vPassword, vPhone, cStatus) VALUES ('$customerID','$full_name','$name_of_company','$position','$email','efa4cbe081b2bf16c4b78c4c016f5a8b','$phone','A')";
    $r = sql_query($q, "CUSTOMER.123");
    UnLockTable();
    LockTable('booking');
    $BID = NextID('iBookingID', 'booking');
    $q2 = "INSERT INTO booking(iBookingID,iAreaID, iCustomerID, vAns1, vAns2, vAns3, vAns4, iNo_of_quotes, cSelf_schedule) VALUES ('$BID','$areaid','$customerID','$how_often','$cleaning_situation','$cleaning_status','$rating','$num_of_q','$self_schedule')";
    $r2 = sql_query($q2, "BOOKING.123");
    UnLockTable();
    for ($i = 0, $j = 1; $i < $num_of_q; $i++, $j++) {
        $Ddatetime = (isset($_POST['date' . $j])) ? $_POST['date' . $j] : '';
        sql_query("INSERT INTO booking_dat(iBookingID, Ddatetime) VALUES ('$BID','$Ddatetime')");
    }
    $mail_content .= "<p>You can Login to your account using your email and password:qm#1234 from below link </p>";
    $mail_content .= '<p><a href="https://thequotemasters.com/clogin.php">visit QuoteMaster.com to connect </a></p>';
    $mail_content .= "<p>Questions? Need help? Please</p>";
    $mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
    $mail_content .= "<p>Quote Masters</p>";
    $mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
    $mail_content .= "</body>";
    $mail_content .= "</html>";
    //mail($to, $subject, $mail_content, $headers);
    header('location: Thank.php');
} else {
    header('location: index.php');
}
