<?php

error_reporting(E_ALL);

ini_set('display_errors', 1);

$NO_PRELOAD = $NO_REDIRECT = '1';



include "includes/common.php";



//DFA($_POST);

$SCHEDULES_ARR = GetXArrFromYID("select Id,title from apptime ", "3");



$Question_Arr = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question", "3");



$Ans_Arr = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer ", "3");



$areaID = $_POST['areaid'];



$q1 = (isset($_POST['q1'])) ? $_POST['q1'] : '';



$q2 = (isset($_POST['q2'])) ? $_POST['q2'] : '';



$q7 = (isset($_POST['q7'])) ? $_POST['q7'] : '';



$q5 = (isset($_POST['q5'])) ? $_POST['q5'] : '';



$num_of_Q = $_POST['num_of_Q'];



$q3 = (isset($_POST['q3'])) ? $_POST['q3'] : '';



$q4 = (isset($_POST['q4'])) ? $_POST['q4'] : '';



$name_of_company = db_input($_POST['name_of_company']);



$c_address = db_input($_POST['c_address']);



$first_name = db_input($_POST['first_name']);



$last_name = db_input($_POST['last_name']);



$position = db_input($_POST['position']);



$phone = db_input($_POST['phone']);



$email = db_input($_POST['email']);



//$area_q = "SELECT zip,city,state FROM areas WHERE id='$areaID'";

$area_q = "SELECT 

        z.zip_code,

        ci.city_name,

        s.state_name,

        c.country_name

    FROM 

        zip_codes z

    JOIN 

        cities ci ON z.city_id = ci.city_id

    JOIN 

        states s ON ci.state_id = s.state_id

    JOIN 

        countries c ON ci.country_id = c.country_id where  z.zip_code = '$areaID'";

       // echo $area_q;



$area_r = sql_query($area_q, "");



list($zip, $city, $state,$country) = sql_fetch_row($area_r);



$html = '<dl class="conf_dl">';



$html .= '<div class="card p-3"><div class="row"><div class="col-12"><h5>Company Details</h5></div><dt class="dt_place col-lg-6 col-12">Selected Zip</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . str_pad($zip, 5, '0', STR_PAD_LEFT) . ', ' . $city . ', ' . $state . ',' . $country . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">Name of Company</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $name_of_company . '</dd>';





$html .= '<dt class="dt_place col-lg-6 col-12">Company Address</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $c_address . '</dd>';







$html .= '<dt class="dt_place col-lg-6 col-12">First Name</dt>';

$html .= '<dd class="dd_place col-lg-6 col-12">' . $first_name . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">Last Name</dt>';

$html .= '<dd class="dd_place col-lg-6 col-12">' . $last_name . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">Position</dt>';

$html .= '<dd class="dd_place col-lg-6 col-12">' . $position . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">Phone</dt>';

$html .= '<dd class="dd_place col-lg-6 col-12">' . $phone . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">Email</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $email . '</dd></div></div>';



$html .= '<div class="card p-3"><div class="row"><div class="col-12"><h5>Appointment Details</h5></div><dt class="dt_place col-lg-6 col-12">' . $Question_Arr[1] . '</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $Ans_Arr[$q1] . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">' . $Question_Arr[2] . '</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $Ans_Arr[$q2] . '</dd>';



$html .= '<dt class="dt_place col-lg-6 col-12">' . $Question_Arr[5] . '</dt>';



$html .= '<dd class="dd_place col-lg-6 col-12">' . $Ans_Arr[$q5] . '</dd>';

if (!empty($q7)) {

   

    $html .= '<dt class="dt_place col-lg-6 col-12">' . $Question_Arr[7] . '</dt>';

    

    $html .= '<dd class="dd_place col-lg-6 col-12">' . $Ans_Arr[$q7] . '</dd>';

}





for ($i = 0, $j = 1; $i < $num_of_Q; $i++, $j++) {

    $Ndate = $_POST['date' . $j];  // DateTime Object ( [date] => 2013-02-13 00:00:00.000000 [timezone_type] => 3 [timezone] => America/New_York )

    $dateArr = explode('-', $Ndate);



    $html .= '<dt class="dt_place col-lg-6 col-12">Time and date for appointment ' . $j . '</dt>';



    $html .= '<dd class="dd_place col-lg-6 col-12">' . date('l' . ', ' . 'm/d/Y', strtotime($dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1])).', ' .$SCHEDULES_ARR[$_POST['Time' . $j]]. '</dd>';



}



if (!empty($q3)) {



    $html .= '<dt class="dt_place col-lg-6 col-12">' . $Question_Arr[3] . '</dt><div class="col-lg-6 col-12">';

    

    foreach ($q3 as $key => $value) {

        $html .= '<dd class="dd_place">' . $Ans_Arr[$value] . '</dd>';

    }

    $html .= '</div>';

}

if (!empty($q4)) {

    $html .= '<dt class="dt_place col-lg-6 col-12">' . $Question_Arr[4] . '</dt>';

    $html .= '<dd class="dd_place col-lg-6 col-12">' . $Ans_Arr[$q4] . '</dd></div></div>';

}

$html .= '</dl>

                <div class="row mt-4">

                  <div class="col-md-8 mb-4">

                    <div class="icheck-primary">

                      <input type="checkbox" id="custAgreeTerms" name="terms" value="agree" required>

                      <label for="custAgreeTerms" class="text-dark">

                      I agree to the 

                      <button style="padding: 0;border: none;background: none;outline: none;color: #BE1E2D;" onclick="window.open(\'customers_terms.php\', \'_blank\')">Terms</button>

                      </label>

                    </div>

                  </div>

                </div>

                <p><strong>Please confirm if your appointment booking summary is correct.</strong></p>

                <div class="text-left">



                    <button type="button" id="confirm_booking" class="btn">Yes</button>

                    <button type="button" onclick="nextPrev(-1)" id="btnclose" class="btn">No</button>

                </div>';





echo $html;

?>

