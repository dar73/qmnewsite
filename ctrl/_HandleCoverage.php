<?php
include '../includes/common.php';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
$cid = (isset($_POST['cid'])) ? $_POST['cid'] : '';
$city = (isset($_POST['city'])) ? $_POST['city'] : '';
$spid = (isset($_POST['spid'])) ? $_POST['spid'] : '';
$state = (isset($_POST['state'])) ? $_POST['state'] : '';
$county = (isset($_POST['county'])) ? $_POST['county'] : '';
$html = '';
if ($mode == 'GETCOUNTYS') {
    $COUNTY_ARR = GetXArrFromYID("select DISTINCT County_name from areas where state='$state' order by County_name ", '2'); // All countys
    $html .= ' <select name="county_name[]" onchange="GetCities(),GET_REALTIME_ZIPS();" id="county_name" class="form-control  SlectBox" multiple="multiple" data-placeholder="Select a County">';
    foreach ($COUNTY_ARR as $value) {
        //$selected = (in_array($value, $SELECTED_COUNTYS)) ? 'selected' : '';
        $html .= '<option value="' . $value . '" >' . $value . '</option>';
    }
    $html .= '</select>';
    echo $html;
    exit;
} elseif ($mode == 'GETCITIES') {
    $html = '<select name="city[]" id="city" onchange="GET_REALTIME_ZIPS();" class="form-control select2" multiple="multiple" data-placeholder="Select a City">';
    if (!empty($county)) {
        foreach ($county as  $value) {
            $Q = "SELECT DISTINCT city FROM areas WHERE state='$state' AND County_name IN ('" . implode("','", $county) . "') order by city ";
            $r = sql_query($Q);
            $html .= ' <optgroup label="' . $value . '">';
            while ($R = sql_fetch_assoc($r)) {
                $html .= '<option value="' . $R['city'] . '">' . $R['city'] . '</option>';
            }
            $html .= '</optgroup>';
        }
    }
    $html .= '</select>';
    echo $html;
    exit;
} elseif ($mode == 'REALTIME_ZIPS') {
    $cond = '';


    if (!empty($state)) {
        $cond .= " and state='$state'";
    }

    if (!empty($county)) {
        $cond .= " and County_name in ('" . implode("','", $county) . "')  ";
    }

    if (!empty($city)) {
        //$cond .= " and city  in ('" . implode("','", $city) . "')";
        $cond .= "AND (city IN ('" . implode("','", $city) . "') 
                    OR County_name NOT IN (
                        SELECT DISTINCT County_name 
                        FROM areas 
                        WHERE state = '$state' 
                        AND city IN ('" . implode("','", $city) . "')
                    ))";
    }


    $q = "SELECT zip  FROM areas WHERE  1  $cond ";
    $r = sql_query($q);
    if (sql_num_rows($r)) {
        $html = '<select name="zips[]" id="zips" class="form-control SlectBox" multiple="multiple" data-placeholder="Select a zips">';
        while (list($zip) = sql_fetch_row($r)) {
            $html .= '<option value="' . $zip . '">' . $zip . '</option>';
        }
        $html .= '</select>';
    }
    echo $html;
    exit;
} elseif ($mode == 'DELETE') {
    if (sql_query("delete from coverages where iCoverageId='$cid' ", 'Delete Coverage')) {
        UpdateCoverages3($spid);
        echo 1;
    } else
        echo 0;
}
