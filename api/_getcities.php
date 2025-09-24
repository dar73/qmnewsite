<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['county_name'], $_POST['state'])) {
    $county_name = $_POST['county_name'];
    $state = $_POST['state'];
    $str = '';
    $title = "In the County or Counties, you selected are there any cities you do not cover?";
    $q = "SELECT DISTINCT city FROM areas WHERE state IN ('" . implode("','", $state) . "') AND County_name IN ('" . implode("','", $county_name) . "') order by city ";

    $r = sql_query($q);
    $data = array();
    // array_push($data, array('city' => $R['city']));
    $str .= '<select name="city[]" class="form-control select2" multiple="multiple" data-placeholder="Select a City" id="city">';
    foreach ($county_name as  $value) {
        $Q = "SELECT DISTINCT city FROM areas WHERE state IN ('" . implode("','", $state) . "') AND County_name='$value' order by city ";
        $r = sql_query($Q);
        $str .= ' <optgroup label="' . $value . '">';
        while ($R = sql_fetch_assoc($r)) {
            $str .= '<option value="' . $R['city'] . '">' . $R['city'] . '</option>';
        }
        $str .= '</optgroup>';
    }
    $str .= ' </select>';

    $results = $title . '~~**~~' . $str;
}
echo $results;
exit;
?>