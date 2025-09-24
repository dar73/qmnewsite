<?php
//set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
// ini_set('memory_limit', -1);
//ini_set('display_startup_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common_front.php';
include '../includes/ti-salt.php';

$token = (isset($_POST['token']))?db_input2($_POST['token']):'';//d7c9918c7c21e17qm@12340f7b6543f1
$email = (isset($_POST['email']))?db_input2($_POST['email']):'';
$password =(isset($_POST['password']))? db_input2($_POST['password']):'';
$returnArr = array();
$data = array();
if($token== 'd7c9918c7c21e17qm@12340f7b6543f1')
{
    $txtpassword = htmlspecialchars_decode(db_input($password));
    $salt_obj = new SaltIT;
    $txtpassword = $salt_obj->EnCode($txtpassword);
    //$q1 = "select iCustomerID,vFirstname,vPassword from customers where vEmail='$email' and cStatus='A'  "; //customers table
    $q2 = "select id,First_name,password from service_providers where email_address='$email' and cStatus='A'   "; //vendor table
    $r = sql_query($q2, 'AUTH.61');

    if (sql_num_rows($r)) {
        list($u_id, $u_name, $u_pass) = sql_fetch_row($r);
        $ret = ($u_pass == ($txtpassword)) ? 1 : -1;    // 1 - txtpassword Matches ::  -1 - txtpassword MisMatch
    } else
        $ret = -2;    //No User Found

    //$ret = 1;

    if ($ret == -1 || $ret == -2) 
    {
        
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid credentials!!");
        header('Content-Type: application/json');
        echo json_encode($returnArr);
        sql_close();
        exit;

       
    } elseif ($ret == 1)
     {
        $_q1 = "select id, dDate, First_name, Last_name, company_name, phone, email_address, license_number, vLicence_file, dDate_Licence_expiry, vInsurance_file, dDate_insurance_expiry, email_verify_key, email_verify, street, state, county, city, vBrochure, vCertificate1, vCertificate2, vCertificate3, vFblink, vInstalink, cStatus, vLinkedInlink, fGratings, iInsurance, iBrochure, iLicence, iCertificate1, iCertificate2, iAwards, iFacebook, iInstagram, iLikendn, vGovtID, vWebsite, cAdmin_approval, cMailsent, cSource, cHaveBI,vFirebaseAuthToken,cUsertype  from service_providers where email_address='$email' and cStatus='A' ";
        $_r1 = sql_query($_q1);
        if (sql_num_rows($_r1)) {
            $a = sql_fetch_assoc($_r1);
            $returnArr = array("ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "login success!", "data" => $a);
            header('Content-Type: application/json');
            echo json_encode($returnArr);
            sql_close();
            exit;
        }
    }

}else{
    $returnArr= array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Invalid token!!");
    header('Content-Type: application/json');
    echo json_encode($returnArr);
    sql_close();
    exit;
}

?>