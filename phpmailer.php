<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


function Send_mail($from, $fromName, $to, $replyto, $CC_str = "", $BCC_str = "", $subject = "", $str = "", $subject_user = "", $replystr = "", $page = "", $FILES = "")
{
    $Mail = new PHPMailer(true);
    $Mail->From = 'ops@thequotemasters.com';
    $Mail->FromName = 'Quote Masters';
    $Mail->AddAddress($to);
    $Mail->isSMTP();
    $Mail->SMTPDebug = false;
    $Mail->Host = 'mail.thequotemasters.com';
    $Mail->Port = 465;
    $Mail->SMTPSecure = 'ssl';
    $Mail->SMTPAuth = true;
    $Mail->Username = 'ops@thequotemasters.com';
    $Mail->Password = 'Haddock4214!!';

    if ($CC_str != "") {
        $CC = explode(',', $CC_str);
        if (!empty($CC)) {
            foreach ($CC as $values) {
                $Mail->AddCC($values);
            }
        }
    }

    if ($BCC_str != "") {
        $BCC = explode(',', $BCC_str);
        if (!empty($BCC)) {
            foreach ($BCC as $value) {
                $Mail->AddBCC($value);
            }
        }
    }

    /*if(!empty($FILES)) 
		{
			//echo BROUCHER_PATH.'=>'.BROUCHER_FILENAME;
    		//$Mail->AddAttachment($FILES['myfiles']['tmp_name'],$FILES['myfiles']['name']);
    		$AutoMail->AddAttachment(BROUCHER_PATH.BROUCHER_FILENAME);
		}		*/

    $Mail->addReplyTo('ops@thequotemasters.com', 'Quote Master');
    $Mail->WordWrap = 50;
    $Mail->IsHTML(true);
    $Mail->Subject = $subject;
    $Mail->MsgHTML($str);

    if (!empty($replystr)) {
        $AutoMail = new PHPMailer(true);
        $AutoMail->From = 'ops@thequotemasters.com';
        $AutoMail->FromName = 'Quote Masters';
        $AutoMail->AddAddress($to);
        $AutoMail->isSMTP();
        $AutoMail->SMTPDebug = false;
        $AutoMail->Host = 'mail.thequotemasters.com';
        $AutoMail->Port = 465;
        $AutoMail->SMTPSecure = 'ssl';
        $AutoMail->SMTPAuth = true;
        $AutoMail->Username = 'ops@thequotemasters.com';
        $AutoMail->Password = 'Haddock4214!!';
        $AutoMail->AddReplyTo($to);
        $AutoMail->WordWrap = 50;
        $AutoMail->IsHTML(true);
        $AutoMail->Subject = $subject_user;
        $AutoMail->MsgHTML($replystr);

        if (!empty($FILES) && $FILES == 'detailed-curriculum') {
            //echo BROUCHER_PATH.'=>'.BROUCHER_FILENAME;
            //$Mail->AddAttachment($FILES['myfiles']['tmp_name'],$FILES['myfiles']['name']);
            //$AutoMail->AddAttachment(BROUCHER_PATH . BROUCHER_FILENAME);
        }

        $AutoMail->Send();
    }

    if ($Mail->Send()) {
        return 1;
    } else {
        return 0;
    }

}
// if(Send_mail('','','darshankubal1@gmail.com','','','','Test',"hiiii",'')){
//     echo 'Mail sent';
// }
?>