<?php
include '../includes/common.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$txtid = (isset($_POST['spid'])) ? $_POST['spid'] : '';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
$edit_url = 'v_coverage.php';

$SELECTED_ZIP_IDS = $SELECTED_STATES = $S_SELECTED_STATES = $SELECTED_COUNTYS = $SELECTED_COUNTYS_ZIPS = $S_COUNTY_ZIPS = $S_CITY_ZIPS = $SELECTED_CITYS = $COUNTY_ARR = $CITY_ARR = $STATE_ARR = array();

$COUNTRIES = GetXArrFromYID("SELECT country_id, country_name FROM countries WHERE 1 and cStatus='A' ", '3');
$STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 ", '3');
$CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE 1 ", '3');

if ($mode == 'UPDATE_COVERAGE') {
    $COVERAGE_DATA = GetDataFromID('coverages', 'iCoverageId', $id);
    //SELECT `iCoverageId`, `iproviderID`, `iCountryID`, `vStates`, `vCounties`, `vCities`, `vZips` FROM `coverages` WHERE 1
    $SELECTED_STATE = $COVERAGE_DATA[0]->vStates; //states from DB
    $SELECTED_COUNTY = $COVERAGE_DATA[0]->vCounties; //countys from DB (fixed typo: was iCountryID)
    $SELECTED_CITY = $COVERAGE_DATA[0]->vCities; //cities from DB
    $SELECTED_ZIPS = $COVERAGE_DATA[0]->vZips; //zips from DB
    $SELECTED_COUNTRY = $COVERAGE_DATA[0]->iCountryID; //zips from DB

    $SELECTED_ZIPS = explode(',', $SELECTED_ZIPS);

    $STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 and country_id='$SELECTED_COUNTRY' ", '3');

    $SELECTED_STATES = $SELECTED_STATE;

    $SELECTED_COUNTYS = explode(',', $SELECTED_COUNTY); //Array

    // Fetch counties for the selected state(s)
    $COUNTY_ARR = GetXArrFromYID("SELECT county_id, county_name FROM counties WHERE state_id='$SELECTED_STATES' ORDER BY county_name", '3');

    // Fetch cities for the selected county(ies)
    $CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE state_id='$SELECTED_STATES' AND county_id IN ('" . implode("','", $SELECTED_COUNTYS) . "') ORDER BY city_name", '3');

    $SELECTED_CITYS = explode(',', $SELECTED_CITY); //selected city Array

    $body = '<form action="' . $edit_url . '" method="POST"  id="FRM_COVERAGE" name="FRM_COVERAGE">';
    $body .= '<input type="hidden" name="cid" id="cid" value="' . $id . '">';
    $body .= '<input type="hidden" name="txtid" id="txtid" value="' . $txtid . '">';
    $body .= '<input type="hidden" name="mode" value="' . $mode . '">';

    $body .= '
    <div><h5 class="text-danger ">* only allowed to changed the counties,city and zips in edit mode </h5></div>
    <div class="form-group">
                <label for="countryid">Country <span class="text-danger">*</span></label>';
    $body .=  FillCombo2022('countryid', $SELECTED_COUNTRY, $COUNTRIES, 'Country', 'form-control', 'GetStates2(this.value);');
    $body .= '</div>';

    $body .= '<div class="form-group">
                <label for="stateid">State <span class="text-danger">*</span></label><span id="STATE_DIV">';
    $body .= FillCombo2022('stateid', $SELECTED_STATE, $STATE_ARR, 'state', 'form-control', 'GetCounties2(this.value);');
    $body .= '</span></div>';

    // New: County multiselect before city
    $body .= '<div class="form-group">
                <label for="countyid"><span class="text-danger">Counties you cover:</span></label>';
    $body .= '<span id="COUNTY_DIV">';
    $body .= FillComboMultiSelect('countyid', $SELECTED_COUNTYS, $COUNTY_ARR, 'county', 'form-control mul', 'GetCities2(this.value);');
    $body .= '</span></div>';

    $body .= '<div class="form-group">
                <label for="city"><span class="text-danger">Cities you cover:</span></label>';
    $body .= '<span id="CITY_DIV">';
    //$body .= FillComboMultiSelect('city', $SELECTED_CITYS, $CITY_ARR, 'city', 'form-control mul', 'GetMultipleZips(this.value);');
    $body .= '<select name="city[]" id="city" class="form-control mul" onchange="GetMultipleZips(this.value);" multiple="multiple" data-placeholder="Select cities">';
    if (!empty($COUNTY_ARR) && !empty($CITY_ARR)) {
        foreach ($COUNTY_ARR as $county_id => $county_name) {
            if (!in_array($county_id, $SELECTED_COUNTYS)) {
                continue;
            }
            $body .= '<optgroup label="' . htmlspecialchars($county_name) . '">';
            foreach ($CITY_ARR as $city_id => $city_name) {
                // Assuming CITY_ARR keys are city_id and values are city_name
                // If you have city->county_id mapping, filter here
                $body .= '<option value="' . htmlspecialchars($city_id) . '"'
                    . (in_array($city_id, $SELECTED_CITYS) ? ' selected' : '') . '>'
                    . htmlspecialchars($city_name) . '</option>';
            }
            $body .= '</optgroup>';
        }
    }
    //$body .= FillComboMultiSelect('city', $SELECTED_CITYS, $CITY_ARR, 'city', 'form-control mul', 'GetMultipleZips(this.value);');
    $body .= '</select>';
    $body .= '</span></div>';

    //Zips exclusion
    $body .= '<div class="form-group">
                <label for="zip"><span class="text-danger">Zips you cover:</span></label>';
    $body .= '<span id="ZIP_DIV">';
    $ZIPS_ARR = GetXArrFromYID("SELECT zip_code, zip_code FROM zip_codes WHERE city_id IN (" . implode(',', array_map('intval', $SELECTED_CITYS)) . ") ORDER BY zip_code", '3');
    $body .= FillComboMultiSelect('zipid', array(), $ZIPS_ARR, 'Zip', 'form-control mul', '');
    $body .= '</span></div>';

    $body .= '<br><br><button type="button" onclick="VALIDATE_CFORM();"  class="btn btn-primary">Update</button>';
    $body .= '</form>';
    $title = 'Edit Coverages';

    echo $title . '~~' . $body;
} elseif ($mode == 'I') {
    $body = '<form action="' . $edit_url . '" method="POST" id="FRM_COVERAGE"  name="FRM_COVERAGE">';
    $body .= '<input type="hidden" name="mode" value="' . $mode . '">';
    $body .= '<input type="hidden" name="txtid" id="txtid" value="' . $txtid . '">';

    $body .= '<div class="form-group">
                <label for="countryid">Country <span class="text-danger">*</span></label>';
    $body .=  FillCombo2022('countryid', '', $COUNTRIES, 'Country', 'form-control', 'GetStates2(this.value);');
    $body .= '</div>';

    $body .= '<div class="form-group">
                <label for="stateid">State <span class="text-danger">*</span></label><span id="STATE_DIV">';
    $body .= FillCombo2022('stateid', '', array(), 'state', 'form-control', 'GetCounties2(this.value);');
    $body .= '</span></div>';

    // New: County multiselect before city
    $body .= '<div class="form-group">
                <label for="countyid"><span class="text-danger">Counties you cover *</span></label><span id="COUNTY_DIV">';
    $body .= FillComboMultiSelect('countyid', '', array(), 'County', 'form-control SlectBox', 'GetCities2(this.value);');
    $body .= '</span></div>';

    $body .= '<div class="form-group">
                <label for="city"><span class="text-danger">Cities you cover *</span></label><span id="CITY_DIV">';
    $body .= FillComboMultiSelect('city', '', array(), 'City', 'form-control SlectBox', 'GET_REALTIME_ZIPS();');
    $body .= '</span></div>';

    //Zips exclusion
    $body .= '<div class="form-group">
                <label for="zip"><span class="text-danger">Zips you cover </span></label>';
    $body .= '<span id="ZIP_DIV"><select name="zips[]" id="zips" class="form-control SlectBox" multiple="multiple" data-placeholder="Select a zips">';
    $body .= '</select></span></div>';

    $body .= '<br><br><button type="button" onclick="VALIDATE_CFORM();" class="btn btn-primary">Save</button>';
    $body .= '</form>';
    $title = 'Add Coverages';

    echo $title . '~~' . $body;
}
?>
