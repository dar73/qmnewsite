<?php
$to = 'darshankubal1@gmail.com';
$subject = "SP alerts";
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: thequotemasters.com <ops@thequotemasters.com>' . "\r\n";
$headers .= 'Cc: darshankubal1@gmail.com' . "\r\n";

//mail($to, $subject, $message, $headers);
$mail_content = "<html>";
$mail_content .= '<body>';
$mail_content .= '<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">
<p> Hi {Company name},</p>';
$mail_content .= '<p>Do you need a reliable source for verified leads?  We have what you need at Quote Masters! </p>';
$mail_content .= "<p>We have received your requirements for a janitorial quote . </p>";
$mail_content .= '<p>Verification code for your requirement is : ' . $otp . '</p>';
$mail_content .= '<p>Enter this verification code at the following link : </p>';
$mail_content .= '<p><a href="https://thequotemasters.com/verify_leads.php?key=' . $EncryptID . '">Click here to verify your request</a></p>';
$mail_content .= "<p>Questions? Need help? Please</p>";
$mail_content .= '<p><a href="https://thequotemasters.com/">visit thequotemasters.com to connect with our agent</a></p>';
$mail_content .= "<p>At your service,<br>Quote Masters</p>";
$mail_content .= '<div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div>';
$mail_content .= "</body>";
$mail_content .= "</html>";
//mail($to, $subject, $mail_content, $headers);

$to = 'Mike@teamleadgeneration.onmicrosoft.com';
$subject = 'Welcome to Quote Masters';
$headers = "From: Quote Masters <ops@thequotemasters.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = '
<html>
<head>
  <title>Welcome to Quote Masters</title>
   <style>
    .center {
      text-align: center;
    }
  </style>
</head>
<body>
<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: cover;background-attachment: fixed;">
  <p>Hello {SP Name},</p>
  <p>Thanks for taking our call, Chris was happy to speak with you and tell you a little bit more about QM. She asked us to forward more information to you and a link to our website. </p>
  <p>We provide you with:</p>
  <ol>
    <li>Companies who want to receive a quote from you.</li>
    <li>Companies whose information is code verified.</li>
    <li>Companies whom you will have a confirmed meeting set up with.</li>
    <li>Companies whom you will know their current cleaning details before you meet them.</li>
  </ol>
  <p><strong>Sign-up is FREE!</strong></p>
  <p>Visit <a href="https://thequotemasters.com/why_to_join.php">Quote Masters</a> to find out more and get a steady flow of great leads!</p>
  <br>
  <p>Sincerely,</p>
  <p><img src="https://thequotemasters.com/Images/q_avatar.ico" alt="Q" style="max-width: 50px;"></p>
  <br>
  <p>Quote Masters Sales Team</p>
  <p><a href="https://www.thequotemasters.com">www.thequotemasters.com</a></p>
  <p>888-616-4125</p>
  <br>
  <div class="center">
  <p style="font-size: 11px;">Quote Masters LLC</p>
  <p style="font-size: 11px;">8875 Hidden River Parkway Tampa FL 33637</p>
  <p style="font-size: 11px;">&copy; 2023 <a href="mailto:unsubscribe@example.com">Opt-out here</a></p>
  <div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div></div>
</body>
</html>
';

// Send the email
if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully!';
} else {
    echo 'Error sending email.';
}



$to = 'darshankubal1@gmail.com';
$subject = 'Welcome to Quote Masters';
$headers = "From: Quote Masters <ops@thequotemasters.com>\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message2 = '
<html>
<head>
  <title>Welcome to Quote Masters</title>
   <style>
    .center {
      text-align: center;
    }
  </style>
</head>
<body>
<div style=" background-image: url(https://thequotemasters.com/Images/faded-logo-large.png);background-position: center;background-repeat: no-repeat;background-size: contain;background-attachment: fixed;">
  <p>Hi {Company name},</p>
  <p>Are you tired of Lead Gen companies who provide leads that waste your time and money? Ready for a company that sends you real opportunities?</p>
  <p>Quote Masters provides:</p>
  <ol>
    <li>Leads that are verified.</li>
    <li>Leads with the current cleaning details.</li>
    <li>Leads which have a confirmed data and time to meet.</li>
    <li>Lead which provides you the opportunity to grow your monthly recurring revenue!</li>
  </ol>
  <p><strong>Sign-up is simple and free!</strong></p>
  <br>
  <p>Visit <a href="https://thequotemasters.com/why_to_join.php">Quote Masters</a> for more details !</p>
  <br>
  <p>Sincerely,</p>
  <p><img src="https://thequotemasters.com/Images/q_avatar.ico" alt="Q" style="max-width: 50px;"></p>
  <br>
  <p>Quote Masters Sales Team</p>
  <p><a href="https://www.thequotemasters.com">www.thequotemasters.com</a></p>
  <p>888-616-4125</p>
  <br>
  <div class="center">
  <p style="font-size: 11px;">Quote Masters LLC</p>
  <p style="font-size: 11px;">8875 Hidden River Parkway Tampa FL 33637</p>
  <p style="font-size: 11px;">&copy; 2023 <a href="mailto:unsubscribe@example.com">Opt-out here</a></p>
  <div style="text-align: center;"><img style="max-width: 200px;width: 50%;" src="https://thequotemasters.com/Images/logo.png"></div></div></div>
</body>
</html>
';

// Send the email
if (mail($to, $subject, $message2, $headers)) {
  echo 'Email sent successfully!';
} else {
  echo 'Error sending email.';
}


///second mail




?>