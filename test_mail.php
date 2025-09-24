<?php
include 'includes/class.phpmailer.php';
$to = 'darshankubal1@gmail.com';
$subject = "SP alerts";

$message = '
<html>
<head>
  <title>Welcome to Quote Masters</title>
</head>
<body>
<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">
  <p>Hi {Company name},</p>
  <p>Are you tired of Lead Gen companies who provide leads that waste your time and money? Ready for a company that sends you real opportunities?</p>
  <p>Quote Masters provides:</p>
  <ol>
    <li>Leads that are verified.</li>
    <li>Leads with the current cleaning details.</li>
    <li>Leads which have a confirmed date and time to meet.</li>
    <li>Leads which provide you the opportunity to grow your monthly recurring revenue!</li>
  </ol>
  <p><strong>Sign-up is simple and free!</strong></p>
  <p>Visit <a href="https://www.thequotemasters.com">Quote Masters</a> for more details!</p>
  <br>
  <p>Sincerely,</p>
  <p>Q</p>
  <p>Quote Masters Sales Team</p>
  <p>Quote Masters LLC</p>
  <p>8875 Hidden River Parkway Tampa FL 33637</p>
  <p>&copy; 2023 <a href="mailto:unsubscribe@example.com">Opt-out here</a></p>
  <div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>
</body>
</html>
';

// // Send the email
// if (mail($to, $subject, $message, $headers)) {
//     echo 'Email sent successfully!';
// } else {
//     echo 'Error sending email.';
// }
$from= 'darshankubal1@gmail.com';
$Mail = new PHPMailer();
$Mail->From = 'darshankubal1@gmail.com';   // website@teaminertia.com Sender's mail id
$Mail->FromName = 'Quote Masters'; // Sender's name
$Mail->AddAddress($to);   //  Receipient's email id pinto.aaron@ymail.com
//$Mail->AddReplyTo($from); // Sender's mail Id
$Mail->WordWrap = 50; // set word wrap
$Mail->IsHTML(true);
$Mail->Subject  = $subject;  // subject of the mail
$Mail->MsgHTML($message);

if ($Mail->Send()) {
  echo 'Mail sent successfuly';
  //###### If mail Successful the destination page ##########//
  // $done = 1;
  // header("location:index.php?done=$done");
  // exit;
} else {
  echo 'Mail not sent';
  //###### If mail failed the destination page ##########//
  // $done = 2;
  // header("location:index.php?done=$done");
  // exit;
}


?>