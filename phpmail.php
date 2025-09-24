<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$to = 'darshankubal1@gmail.com';
$subject = "Welcome Email";
$msg = '<html>
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
</html>';


$mail = new PHPMailer(true);

//try {
    //Server settings
    //$mail->SMTPDebug = 2;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.thequotemasters.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'ops@thequotemasters.com';                     //SMTP username
    $mail->Password   = 'Haddock1212!';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('ops@thequotemasters.com', 'Quote Master');
    $mail->addAddress($to);     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('ops@thequotemasters.com', 'Quote Master');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $msg;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    

   if ($mail->send()) {
    echo 'Message has been sent';
   }else {
    echo $mail->ErrorInfo;
   } 
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }
?>
