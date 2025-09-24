<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
// DFA($_POST);
// exit;

$coverage_id=$_POST['cid'];
$result='';
if (isset($_POST['countys'])) {
    $s_counties=$_POST['countys'];//new coverage selection

    $COVERAGE_DATA = GetDataFromID('coverages', 'iCoverageId', $coverage_id);
    $SELECTED_STATE = $COVERAGE_DATA[0]->vStates; //states from DB
   // $SELECTED_COUNTY = $COVERAGE_DATA[0]->vCounties; //countys from DB
    $SELECTED_CITY = $COVERAGE_DATA[0]->vCities; //cities from DB


    $SELECTED_CITYS = explode(',', $SELECTED_CITY);//selected city array

    //$county_name = $_POST['county_name'];
    //$SELECTED_STATES=GetXFromYID("select vStates from coverages where iCoverageId='$coverage_id' ");

    $COUNTY_ARR = GetXArrFromYID("select DISTINCT County_name from areas where state='$SELECTED_STATE' order by County_name ", '2'); // All countys
   
    $result .= '<select name="city[]" id="city" class="form-control select2" onchange="GET_REALTIME_ZIPS();" multiple="multiple" data-placeholder="Select a City">';
    foreach ($s_counties as  $value) {
        $Q = "SELECT DISTINCT city FROM areas WHERE state='$SELECTED_STATE' AND County_name='$value' order by city ";
        $r = sql_query($Q);
        $result .= ' <optgroup label="' . $value . '">';
        while ($R = sql_fetch_assoc($r)) {
            $selected = (in_array($R['city'], $SELECTED_CITYS)) ? 'selected' : '';
            $result .= '<option value="' . $R['city'] . '"' . $selected . '>' . $R['city'] . '</option>';
        }
        $result .= '</optgroup>';
    }

    $result .= '</select>';
   

    //$results 
}
echo $result;
exit;
