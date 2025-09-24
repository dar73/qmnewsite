<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['state'])) {
    //$county_name = $_POST['county_name'];
    $state = $_POST['state'];
    $str = '';
    $title = "In the County or Counties, you selected are there any cities you do not cover?";
    // $q = "SELECT DISTINCT city FROM areas WHERE state IN ('" . implode("','", $state) . "') AND County_name IN ('" . implode("','", $county_name) . "') order by city ";

    // $r = sql_query($q);
    // $data = array();
    // array_push($data, array('city' => $R['city']));
    $str .= '<select name="county_name[]" onchange="getcitydropdown();" id="county_name" class="form-control select2" multiple="multiple" data-placeholder="Select a County">';
    foreach ($state as  $value) {
        $Q = "SELECT DISTINCT County_name FROM areas WHERE state='$value' order by County_name ";
        $r = sql_query($Q);
        $str .= ' <optgroup label="' . $value . '">';
        while ($R = sql_fetch_assoc($r)) {
            $str .= '<option value="' . $R['County_name'] . '">' . $R['County_name'] . '</option>';
        }
        $str .= '</optgroup>';
    }
    $str .= ' </select>';

    $results = $title . '~~**~~' . $str;
}
echo $results;
exit;
?>