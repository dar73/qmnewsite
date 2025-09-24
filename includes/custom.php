<?php

function GetDataFromID($table, $pk_field, $pk_id, $cond = "")
{
    $q = "select * from " . $table . " where " . $pk_field . " = '" . $pk_id . "' " . $cond;
    $r = sql_query($q, "CUSTOM.05");

    if (sql_num_rows($r)) {
        while ($row = sql_fetch_object($r)) {
            $arr[] = $row;
        }

        return $arr;
    }
}

function FillCombo2022($name, $selected, $dataArr, $sel_name, $class = "form-control", $change = "", $readonly = '')
{
    $str = '';
    $str .= '<select name="' . $name . '" id="' . $name . '" class="' . $class . '" onchange="' . $change . '" ' . $readonly . '>';
    $str .= '<option value="0">-Select ' . $sel_name . '-</option>';
    foreach ($dataArr as $id => $name) {
        $sel = '';
        if ($id == $selected) {
            $sel = 'selected';
        }
        $str .= '<option value="' . $id . '" ' . $sel . ' >' . $name . '</option>';
    }
    $str .= '</select>';

    return $str;
}

function GetDataFromQuery($query)
{
    $q = $query;
    $r = sql_query($q, "CUSTOM.05");

    if (sql_num_rows($r)) {
        while ($row = sql_fetch_object($r)) {
            $arr[] = $row;
        }

        return $arr;
    }
}



function GetDataFromCOND($table, $cond = "")
{
    $q = "select * from " . $table . " where 1 " . $cond;
    $r = sql_query($q, "CUSTOM.21");

    if (sql_num_rows($r)) {
        while ($row = sql_fetch_object($r)) {
            $arr[] = $row;
        }

        return $arr;
    }
}

function InsertData($table, $values)
{
    $str = '';

    if (!empty($values)) {
        $q = "insert into $table values(" . $values . ")";
        $r = sql_query($q, "CUSTOM.37");
    }

    //$str = $q.'<br />'; 
    $str = $r;

    return $str;
}

function UpdataData($table, $values, $cond)
{
    $str = '';

    if (!empty($values)) {
        $q = "update $table set $values where $cond";
        $r = sql_query($q, "CUSTOM.56");
    }

    $str = $q;

    return $str;
}

function UpdateData($table, $values, $cond)
{
    $str = '';

    if (!empty($values)) {
        $q = "update $table set $values where $cond";
        $r = sql_query($q, "CUSTOM.56");
    }

    $str = $q;

    return $str;
}

function DeleteData($table, $field, $pk, $cond = "")
{
    $str = '';

    $q = "delete from $table where $field=$pk and 1 $cond";
    $r = sql_query($q, "CUSTOM.56");

    $str = $q;

    return $str;
}

function UpdateField($table, $field, $field_val, $cond = "")
{
    $q = "update $table set $field='" . $field_val . "' where 1 and $cond";
    $r = sql_query($q, 'CUSTOM.351');
    $count = sql_affected_rows($r);

    return $count;
}

function HelpIcon($mesg)
{
    $str = '<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="' . $mesg . '"></i>';

    return $str;
}

function GetCountFromTable($table, $cond = "")
{
    $count = GetXFromYID("select count(*) from " . $table . " where 1 " . $cond);

    return $count;
}

function GetXArrFromYID2($table, $values, $cond = '', $mode = "1")
{
    $q  = "select $values from $table where 1 $cond";
    $arr = array();
    $r = sql_query($q, 'COM39');

    if (sql_num_rows($r)) {
        if ($mode == "2")
            for ($i = 0; list($x) = sql_fetch_row($r); $i++)
                $arr[$i] = $x;
        else if ($mode == "3")
            for ($i = 0; list($x, $y) = sql_fetch_row($r); $i++)
                $arr[$x] = $y;
        else if ($mode == "4")
            while ($a = sql_fetch_assoc($r))
                $arr[$a['I']] = $a;
        else
            while (list($x) = sql_fetch_row($r))
                $arr[$x] = $x;
    }

    return $arr;
}

function GetCounts($table, $cond)
{
    $q = GetXFromYID("select count(*) from $table where 1 $cond");

    return $q;
}

function GetStatusPills($status = "", $status_arr = "")
{
    $pill_str = $bd_col = "";
    $text = isset($status_arr[$status]) ? $status_arr[$status] : '';

    if (!empty($text)) {
        if ($status == 'U') $bd_col = 'badge-primary';
        else if ($status == 'D') $bd_col = 'badge-danger';
        else if ($status == 'A') $bd_col =  'badge-success';

        $pill_str = '<div class="mb-2 mr-2 badge ' . $bd_col . '">' . $text . '</div>';
    }

    return $pill_str;
}

######################## IMA related functions ##########################

function GetPatientCode($pat_id)
{
    $pat_code = 'invalid';

    if (!empty($pat_id) && is_numeric($pat_id)) {
        $c_str = '#PAT';
        $c_str .= str_pad($pat_id, 5, '0', STR_PAD_LEFT);

        $pat_code = $c_str;
    }

    return $pat_code;
}

function getPatientCurrentStatus($pat_id)
{
    // awaiting result , positive, home quarantine, hospitalized and Recovered
    global $PATIENT_STAGE_ARR, $PATIENT_STAGE_CSS_ARR; // = array('I'=>'Isolated/Qurantined', 'H'=>'Hospitalised', 'D'=>'Deceased', 'C'=>'Cured');
    global $TEST_STATUS_ARR, $TEST_STATUS_CSS_ARR;
    /*$TEST_STATUS_ARR = array('A'=>'Awaiting', 'Y'=>'Positive', 'N'=>'Negative');
    $TEST_STATUS_CSS_ARR = array('A'=>'warning', 'Y'=>'danger', 'N'=>'success');

    $PATIENT_STAGE_ARR = array('I'=>'Isolated/Qurantined', 'H'=>'Hospitalised', 'D'=>'Deceased', 'C'=>'Cured');
    $PATIENT_STAGE_CSS_ARR = array('I'=>'alternate', 'H'=>'warning', 'D'=>'danger', 'C'=>'success');*/

    $status_arr = array();
    $text = "Avaiting Result";
    $color = "bg-warning";
    if (is_numeric($pat_id)) {
        $q = "select cPositive, cStage from patient where iPatID=$pat_id";
        $r = sql_query($q, "");

        if (sql_num_rows($r)) {
            list($_positive, $_stage) = sql_fetch_row($r, "");

            if (!empty($_positive)) {
                $text = $TEST_STATUS_ARR[$_positive];
                $color = $TEST_STATUS_CSS_ARR[$_positive];
            }

            if (!empty($_stage)) {
                $text .= '/' . $PATIENT_STAGE_ARR[$_stage];
            }

            /*if($_positive=='N') 
            {
                $text 
                $text = "Negative";
                $color = "bg-default";
            }
            else if($_positive=='Y')
            {
                $text = "Positive";
                $color = "bg-danger";

                if(isset($PATIENT_STAGE_ARR[$_stage]))
                {
                    $text = $PATIENT_STAGE_ARR[$_stage];
                    $color = 'bg-'.$PATIENT_STAGE_CSS_ARR[$_stage];                    
                }
            }*/
        }
    }

    $status_arr['TEXT'] = $text;
    $status_arr['COLOR'] = "progress-bar " . $color;
    $status_arr['ELEM'] = '<div className="progress-bar ' . $color . '" role="progressbar" style="width:100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">' . $text . '</div>';

    return $status_arr;
}

function updateWebPusherId($pat_id, $pusher_id = "")
{
    $notif_id = "";
    if (is_numeric($pat_id)) {
        $notif_id = GetXFromYID("SELECT vWebPushrID FROM patient WHERE iPatID=$pat_id");

        if ($notif_id != $pusher_id)
            UpdateField('patient', 'vWebPushrID', $pusher_id, "iPatID=$pat_id");
    }
}

function getVolunteerDetails($vid)
{
    $v_details = array();
    if (is_numeric($vid)) {
        $q = "select vName, vMobile from volunteer where iVolunteerID=$vid";
        $r = sql_query($q, "");

        $_name = $_phone = "";
        if (sql_num_rows($r)) {
            list($_name, $_phone) = sql_fetch_row($r);
        }

        $v_details['NAME'] = $_name;
        $v_details['PHONE'] = $_phone;
    }

    return $v_details;
}

function getPatientOTP($patid)
{
    $otp = "";
    if (is_numeric($patid)) {
        $otp = GetXFromYID("select cOTP from patinvite where iPatInviteID=$patid");
    }

    return $otp;
}

function PostRequest($url, $referer, $_data)
{
    // convert variables array to string:
    $data = array();
    while (list($n, $v) =
        each($_data)
    ) {
        $data[] = "$n=$v";
    }

    $data = implode('&', $data);

    $url = parse_url($url);
    if ($url['scheme'] != 'http') {
        die('Only HTTP request are supported !');
    }
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];

    // open a socket connection on port 80
    $fp = fsockopen($host, 80);

    // send the request headers:
    fputs($fp, "POST $path HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp, "Referer: $referer\r\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($data) . "\r\n");
    fputs($fp, "Connection: close\r\n\r\n");
    fputs($fp, $data);

    $result = '';

    while (!feof($fp)) {
        // receive the results of the request
        $result .= fgets($fp, 128);
    }

    // close the socket connection:
    fclose($fp);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    // return as array:
    return array($header, $content);
}

function updateSymptoms($POST = array(), $patid = "", $date = "")
{
    $txtchkcough = isset($_POST['chkCough']) ? $_POST['chkCough'] : 'N';
    $txtchktiredness = isset($_POST['chkTiredness']) ? $_POST['chkTiredness'] : 'N';
    $txtchkshortb = isset($_POST['chkShortB']) ? $_POST['chkShortB'] : 'N';
    $txtchkheadache = isset($_POST['chkHeadache']) ? $_POST['chkHeadache'] : 'N';
    $txtchkdrosiness = isset($_POST['chkDrosiness']) ? $_POST['chkDrosiness'] : 'N';
    $txtchkchestpain = isset($_POST['chkChestPain']) ? $_POST['chkChestPain'] : 'N';
    $txtnotes = isset($_POST['txtnotes']) ? db_input2($_POST['txtnotes']) : '';

    if (is_numeric($patid)) {
        $med_Id = GetXFromYID("select iMedLogID from pat_medlog where dDate='$date' and iPatID=$patid order by dtEntry desc, FIELD(cType,'N','A','M') limit 1");

        if (!empty($med_Id) && is_numeric($med_Id)) {
            $q = "UPDATE pat_medlog SET cCough='$txtchkcough', cHeadAche='$txtchkheadache', cShortnessBreath='$txtchkshortb', cTiredness='$txtchktiredness', cChestPain='$txtchkchestpain', cDrowsiness='$txtchkdrosiness', vNotes='$txtnotes' WHERE iMedLogid=$med_Id";
            $r = sql_query($q, "");
        }
    }
}

function UpdateCoverages($SPID)
{ //updates the coverages zip
    $_q = "SELECT * FROM coverages where iproviderID='$SPID' ";
    $COVERAGES_ARR = GetDataFromCOND("coverages", "and iproviderID='$SPID'");
    sql_query("lock table service_providers_areas write,areas write");
    sql_query("delete from service_providers_areas where service_providers_id='$SPID' ", "DELETE COVERAGES");
    if (!empty($COVERAGES_ARR)) {
        for ($u = 0; $u < sizeof($COVERAGES_ARR); $u++) {
            $x_id = db_output($COVERAGES_ARR[$u]->iCoverageId);
            $x_state = db_output($COVERAGES_ARR[$u]->vStates);
            $x_counties = db_output($COVERAGES_ARR[$u]->vCounties);
            $x_cities = db_output($COVERAGES_ARR[$u]->vCities);
            $x_zips = db_output($COVERAGES_ARR[$u]->vZips);
            $str1 = '';
            $state = " state='$x_state' ";
            //DFA(explode(",", $x_counties));

            $str1 .= "  AND  County_name IN ('" . implode("','", explode(",", $x_counties)) . "')";

            $str2 = '';
            if (!empty($x_cities)) {
                //$cityarray = explode(',', $cityarr);
                $str2 .= "  AND  city NOT IN ('" . implode("','", explode(",", $x_cities)) . "')";
            }

            if (!empty($x_zips)) {
                $str2 .= "  AND  zip NOT IN ('" . implode("','", explode(",", $x_zips)) . "')";
            }

            $Getzipq = "SELECT zip,id FROM areas WHERE " . $state . $str1 . $str2;
            // echo $Getzipq;
            // exit;

            $GetzipqR = sql_query($Getzipq);
            while ($R = sql_fetch_assoc($GetzipqR)) {
                //array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
                sql_query("INSERT INTO service_providers_areas( service_providers_id, zip) VALUES ('$SPID','" . $R['zip'] . "')");
            }
        }
    }
    UnlockTable();
}


function UpdateCoverages3_20250102($SPID)
{ //updates the coverages zip
    $_q = "SELECT * FROM coverages where iproviderID='$SPID' ";
    $COVERAGES_ARR = GetDataFromCOND("coverages", "and iproviderID='$SPID'");
    sql_query("lock table service_providers_areas write,areas write");
    sql_query("DELETE FROM service_providers_areas WHERE service_providers_id='$SPID'", "DELETE COVERAGES");   
    UnlockTable(); 
    if (!empty($COVERAGES_ARR)) {
        for ($u = 0; $u < sizeof($COVERAGES_ARR); $u++) {
            $x_id = db_output($COVERAGES_ARR[$u]->iCoverageId);
            $x_state = db_output($COVERAGES_ARR[$u]->vStates);
            $x_counties = db_output($COVERAGES_ARR[$u]->vCounties);
            $x_cities = db_output($COVERAGES_ARR[$u]->vCities);
            $x_zips = db_output($COVERAGES_ARR[$u]->vZips);

            $stateCondition = "state = '$x_state'";
            $countyCondition = "AND County_name IN ('" . implode("','", explode(",", $x_counties)) . "')";

            // Build city condition dynamically
            $cityCondition = '';
            if (!empty($x_cities)) {
                $cityCondition = "AND (city IN ('" . implode("','", explode(",", $x_cities)) . "') 
                                 OR County_name NOT IN (
                                     SELECT DISTINCT County_name 
                                     FROM areas 
                                     WHERE state = '$x_state' 
                                     AND city IN ('" . implode("','", explode(",", $x_cities)) . "')
                                 ))";
            }

            // Build ZIP condition
            $zipCondition = '';
            if (!empty($x_zips)) {
                $zipCondition .= " AND zip IN ('" . implode("','", explode(",", $x_zips)) . "')";
            }

            // Query to fetch ZIP codes
            $Getzipq = "SELECT zip, id FROM areas WHERE $stateCondition $countyCondition $cityCondition $zipCondition";
            

            // Execute query
            $GetzipqR = sql_query($Getzipq);
            
            while ($R = sql_fetch_assoc($GetzipqR)) {
                LockTable('service_providers_areas');
                sql_query("INSERT INTO service_providers_areas(service_providers_id, zip) VALUES ('$SPID', '" . $R['zip'] . "')");
                UnlockTable();
            }
        }
    }

   
}



function UpdateCoverages3($SPID)
{ 
    // Updates the coverages ZIP
    $_q = "SELECT * FROM coverages WHERE iproviderID='$SPID'";
    $COVERAGES_ARR = GetDataFromCOND("coverages", " and iproviderID='$SPID'");
    
    
    sql_query("lock table service_providers_areas write,areas write");
    sql_query("DELETE FROM service_providers_areas WHERE service_providers_id='$SPID'", "DELETE COVERAGES");   
    UnlockTable(); 
    if (!empty($COVERAGES_ARR)) {
        for ($u = 0; $u < sizeof($COVERAGES_ARR); $u++) {
            $x_id = db_output($COVERAGES_ARR[$u]->iCoverageId);
            $x_state = db_output($COVERAGES_ARR[$u]->vStates);
            $x_counties = db_output($COVERAGES_ARR[$u]->vCounties);
            $x_cities = db_output($COVERAGES_ARR[$u]->vCities);
            $x_zips = db_output($COVERAGES_ARR[$u]->vZips);

            $stateCondition = "state = '$x_state'";
            $countyCondition = "AND County_name IN ('" . implode("','", explode(",", $x_counties)) . "')";

            // Build city condition dynamically
            $cityCondition = '';
            if (!empty($x_cities)) {
                $cityCondition = "AND (city IN ('" . implode("','", explode(",", $x_cities)) . "') 
                                 OR County_name NOT IN (
                                     SELECT DISTINCT County_name 
                                     FROM areas 
                                     WHERE state = '$x_state' 
                                     AND city IN ('" . implode("','", explode(",", $x_cities)) . "')
                                 ))";
            }

            // Build ZIP condition
            $zipCondition = '';
            if (!empty($x_zips)) {
                $zipCondition .= " AND zip IN ('" . implode("','", explode(",", $x_zips)) . "')";
            }

            // Query to fetch ZIP codes
            $Getzipq = "SELECT zip, id FROM areas WHERE $stateCondition $countyCondition $cityCondition $zipCondition";
            

            // Execute query
            $GetzipqR = sql_query($Getzipq);
            
            while ($R = sql_fetch_assoc($GetzipqR)) {
                LockTable('service_providers_areas');
                sql_query("INSERT INTO service_providers_areas(service_providers_id, zip) VALUES ('$SPID', '" . $R['zip'] . "')");
                UnlockTable();
            }
        }
    }

   
}

function UpdateCoverages4($SPID)
{
    // Get all coverage records for this provider
    $COVERAGES_ARR = GetDataFromCOND("coverages", " AND iproviderID='$SPID'");

    // Clear existing coverage
    LockTable('service_providers_areas');
    sql_query("DELETE FROM service_providers_areas WHERE service_providers_id='$SPID'");
    UnlockTable();

    if (!empty($COVERAGES_ARR)) {
        foreach ($COVERAGES_ARR as $coverage) {
            $countryId = db_output($coverage->iCountryID);
            $stateId = db_output($coverage->vStates);
            $counties = db_output($coverage->vCounties);
            $cities = db_output($coverage->vCities);
            $zips = db_output($coverage->vZips);

            // Process each county individually
            $countyArray = explode(",", $counties);
            $cityArray = !empty($cities) ? explode(",", $cities) : [];

            foreach ($countyArray as $countyId) {
                processCounty($SPID, $countryId, $stateId, $countyId, $cityArray, $zips);
            }
        }
    }
}

function processCounty($SPID, $countryId, $stateId, $countyId, $cityArray, $zips)
{
    // First get all valid cities for this county
    $validCities = getValidCitiesForCounty($countryId, $stateId, $countyId, $cityArray);

    if (empty($validCities)) {
        return; // No valid cities to process for this county
    }

    // Build zip code query
    $query = "SELECT DISTINCT z.zip_code 
              FROM zip_codes z
              WHERE z.city_id IN ($validCities)";

    // Add zip code filter if specified
    if (!empty($zips)) {
        $zipList = "'" . implode("','", explode(",", $zips)) . "'";
        $query .= " AND z.zip_code IN ($zipList)";
    }

    // Execute and insert results
    $result = sql_query($query);
    $inserts = [];
    while ($row = sql_fetch_assoc($result)) {
        $inserts[] = "('$SPID', '" . $row['zip_code'] . "')";

        // Insert in batches of 100 for better performance
        if (count($inserts) >= 100) {
            batchInsertZips($SPID, $inserts);
            $inserts = [];
        }
    }

    // Insert any remaining records
    if (!empty($inserts)) {
        batchInsertZips($SPID, $inserts);
    }
}

function getValidCitiesForCounty($countryId, $stateId, $countyId, $cityArray)
{
    // Base condition for cities in this county
    $countyCondition = "county_id = '$countyId' AND state_id = '$stateId' AND country_id = '$countryId'";

    // If cities are specified, verify they belong to this county
    if (!empty($cityArray)) {
        $cityList = implode(",", $cityArray);
        $validCities = GetIDString2("SELECT city_id FROM cities WHERE $countyCondition AND city_id IN ($cityList)");

        // If no specified cities belong here, fall back to all cities in county
        if (!empty($validCities)) {
            return $validCities;
        }
    }

    // Default: get all cities in this county
    return GetIDString2("SELECT city_id FROM cities WHERE $countyCondition");
}

function batchInsertZips($SPID, $inserts)
{
    $values = implode(",", $inserts);
    LockTable('service_providers_areas');
    sql_query("INSERT INTO service_providers_areas (service_providers_id, zip) VALUES $values");
    UnlockTable();
}





function calculateProfilePer($SPID)
{
    $_q = "select vLicence_file,vInsurance_file,vBrochure,vCertificate1,vCertificate3,vFblink,vInstalink,vLinkedInlink from service_providers where id='$SPID' ";
    $_r = sql_query($_q);
    $c = 0;
    $p = 12.5;
    list($vLicence_file, $vInsurance_file, $vBrochure, $vCertificate1, $vCertificate3, $vFblink, $vInstalink, $vLinkedInlink) = sql_fetch_row($_r);
    if (IsExistFile($vLicence_file, LICENCE_UPLOAD)) {
        $c++;
    }
    if (IsExistFile($vInsurance_file, INSURANCE_UPLOAD)) {
        $c++;
    }
    if (IsExistFile($vBrochure, BROCHURE_UPLOAD)) {
        $c++;
    }
    if (IsExistFile($vCertificate1, CERTIFICATE1_UPLOAD)) {
        $c++;
    }

    if (IsExistFile($vCertificate3, CERTIFICATE3_UPLOAD)) {
        $c++;
    }

    if (!empty($vFblink)) {
        $c++;
    }

    if (!empty($vInstalink)) {
        $c++;
    }

    if (!empty($vLinkedInlink)) {
        $c++;
    }

    return $c * $p;
}

function GetDaysToblock()
{
    $dayofweek = date('w');
    $newDate = '';
    if ($dayofweek == '5') {
        $newDate = date("m-d-Y", strtotime("+6 day"));
    } elseif ($dayofweek == '6') {
        $newDate = date("m-d-Y", strtotime("+5 day"));
    } elseif ($dayofweek == '7') {
        $newDate = date("m-d-Y", strtotime("+4 day"));
    } elseif ($dayofweek == '0') {
        $newDate = date("m-d-Y", strtotime("+4 day"));
    } elseif ($dayofweek == '4') {
        $newDate = date("m-d-Y", strtotime("+5 day"));
    } else {
        $newDate = date("m-d-Y", strtotime("+3 day"));
    }
    return $newDate;
}

function convertJpegToWebpThumbnail($jpegImagePath, $webpThumbnailPath, $thumbnailWidth, $thumbnailHeight, $extension)
{
    // Create GD image from the JPEG
    $jpegImage = imagecreatefromjpeg($jpegImagePath);

    if ($extension == 'png') {
        $jpegImage = imagecreatefrompng($jpegImagePath);
    }


    // Create a new GD image for the thumbnail
    $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

    // Resize and copy the JPEG image to the thumbnail
    imagecopyresampled($thumbnail, $jpegImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, imagesx($jpegImage), imagesy($jpegImage));

    // Save the thumbnail as WebP
    imagewebp($thumbnail, $webpThumbnailPath);

    // Free up memory
    imagedestroy($jpegImage);
    imagedestroy($thumbnail);

    //echo "Thumbnail created and saved as WebP.";
}

function arrayValuesToKeys($array)
{
    $result = [];
    foreach ($array as $value) {
        $result[$value] = $value;
    }
    return $result;
}

function GET_EMAILCOUNT()
{
    $config = array();
    $config['api_key'] = "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";
    $config['api_url'] = "https://api.brevo.com/v3/account";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['api_url']);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: application/json',
        'api-key: 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',
        'content-type: application/json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}


function FillComboMultiSelect($name, $selectedArr, $dataArr, $sel_name, $class = "form-control", $change = "", $readonly = '')
{
    // Start the select element with multiple attribute
    $readonlyAttr = $readonly ? 'disabled' : '';
    $html = "<select name='{$name}[]' id='{$name}' class='{$class}' onchange='{$change}' multiple {$readonlyAttr}>";

    // Iterate over the data array to populate options
    if (!empty($dataArr)) {
        foreach ($dataArr as $key => $value) {
            // Check if the current option should be selected
            $selected = in_array($key, $selectedArr) ? 'selected' : '';
            $html .= "<option value='{$key}' {$selected}>{$value}</option>";
        }
    }

    // Close the select element
    $html .= "</select>";

    return $html;
}



function GET_LEAD_MAIL_CONTENT($txtid, $SPNAME = "")
{

    $SCHEDULE_ARR = GetDataFromID("appointments", "iApptID", $txtid);
    $GET_AREA_ARRAY = array();
    $BOOKING_ARR = GetDataFromID("appointments", "iApptID", $txtid);
    $areaId = $BOOKING_ARR[0]->iAreaID;
    $BID = $BOOKING_ARR[0]->iBookingID;
    //$AREAD_DETAILS = GetDataFromID('areas', 'id', $areaId);
    $ZIP=$BOOKING_ARR[0]->vZip;
    $_qa = "SELECT 
        z.zip_code,
        c.country_name,
        s.state_name,
        ci.city_name
    FROM 
        zip_codes z
    JOIN 
        cities ci ON z.city_id = ci.city_id
    JOIN 
        states s ON ci.state_id = s.state_id
    JOIN 
        countries c ON ci.country_id = c.country_id where 1 and z.zip_code='$ZIP' ";
    $_qr = sql_query($_qa);
    //DFA($BOOKING_ARR);

    if (sql_num_rows($_qr)) {
        list($ZIP, $COUNTRY, $STATE, $CITY) = sql_fetch_row($_qr);
    } else {
        $COUNTRY = $STATE = $CITY = $ZIP = '';
    }

    $Leads_Ans = $Leads_Ans2 = array();
    $Question_ARR = GetXArrFromYID("SELECT iQuesID,vQuestion FROM leads_question WHERE cStatus='A' and iQuesID not in ('8','7','5') ", '3');
    $Ans_ARR = GetXArrFromYID("SELECT iAnsID,vAnswer FROM leads_answer WHERE cStatus='A'", '3');
    $q_L_Ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID not in ('3','8','7','5')";
    $q_r_L_Ans = sql_query($q_L_Ans, '');
    if (sql_num_rows($q_r_L_Ans)) {
        while ($row = sql_fetch_object($q_r_L_Ans)) {
            $Leads_Ans[] = $row;
        }
    }
    $_q_ans = "select * from leads_answersheet where iResponseID=" . $BID . " and  iQuesID  in ('3') "; // multiple choice answer
    $_q_ans_r = sql_query($_q_ans, '');
    if (sql_num_rows($_q_ans_r)) {
        while ($row = sql_fetch_object($_q_ans_r)) {
            $Leads_Ans2[] = $row;
        }
    }

    $TIMEPICKER_ARR = GetXArrFromYID("select Id,title from apptime ", "3");

    $customerid = $BOOKING_ARR[0]->iCustomerID;
    $CUSTOMER_DET_ARR = GetDataFromID("customers", "iCustomerID", $customerid);


    $mail_content = '<h3>Lead Details</h3>';
    if (!empty($Leads_Ans)) {
        for ($i = 0; $i < count($Leads_Ans); $i++) {

            $mail_content .= '<div class="post clearfix pb-0">
            <div class="user-block">' . $Question_ARR[$Leads_Ans[$i]->iQuesID] . '</div>
            <p class="ml-5">
                ' . $Ans_ARR[$Leads_Ans[$i]->iAnswerID] . '
            </p>
        </div>';
        }
    }

    if (!empty($Leads_Ans2)) {
        $mail_content .= '<div class="post clearfix pb-0">
    <div class="user-block">' . $Question_ARR[$Leads_Ans2[0]->iQuesID] . '</div>
    
    <p class="ml-5">';
        $ANS_STR = '';
        $Ansarr = explode(',', $Leads_Ans2[0]->iAnswerID);
        foreach ($Ansarr as  $value) {
            $ANS_STR .= $Ans_ARR[$value] . ',';
        }


        $mail_content .= $ANS_STR . '</p>
    <p>
        
    </p>
</div>
<hr>';
    }


    $mail_content .= '<div class="col-12 col-md-4 order-1 order-md-2">
                                <h3 class="text-primary"><i class="fas fa-paint-brush"></i>Company Details</h3>
                                <div class="text-muted">
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Client Company</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vName_of_comapny . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">First Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vFirstname . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Last Name</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vLastname . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Position</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vPosition . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Email</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vEmail . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Phone</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vPhone . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Address</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CUSTOMER_DET_ARR[0]->vAddress . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">zip</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $ZIP. '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">country</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $COUNTRY . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">City</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $CITY. '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">State</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $STATE . '</b>
                                    </p>
                                    <p class="text-sm d-md-block d-flex"><span class="col-5 col-md-12">Time</span>
                                        <b class="d-block ml-md-0 col-7 col-md-12">' . $TIMEPICKER_ARR[$BOOKING_ARR[0]->iAppTimeID] . '</b>
                                    </p>';


    if (!empty($SCHEDULE_ARR)) {
        for ($i = 0, $j = 1; $i < count($SCHEDULE_ARR); $i++, $j++) {
            $mail_content .= '<hr>';
            $mail_content .= '<b class="d-block ml-md-0 col-7 col-md-12">Appointment </b>';

            $mail_content .= date('l' . ', ' . 'm/d/Y', strtotime($SCHEDULE_ARR[0]->dDateTime));
        }
    }



    $LEAD_ADD = " " . $CITY . " , " . $STATE . " , " . $ZIP;

    $MAIL_BODY = file_get_contents(SITE_ADDRESS . 'ctrl/email_template_pl.php');
    $MAIL_BODY = str_replace('<PNAME>', $SPNAME, $MAIL_BODY);
    $MAIL_BODY = str_replace('<LEAD_ADDRESS>', $LEAD_ADD, $MAIL_BODY);
    $MAIL_BODY = str_replace('<LEAD_CONTENT>', $mail_content, $MAIL_BODY);

    return $MAIL_BODY;
}

function SendInBlueMail3($subject, $email, $contents, $attachment, $cc = '', $site_title = '', $bcc = '')
{
    if (empty($site_title))
        $site_title = 'Quote Masters';

    if (!empty($contents))
        //$contents .= '<br /><img src="'.SITE_ADDRESS.'img/mail_signature-latest.jpg" alt="Mail Signature" />';

        $cc = '';
    // if ($subject != 'OTP for Login') //empty($bcc) && 
    // $bcc = 'ops@thequotemasters.com';

    $config = array();
    $config['api_key'] = "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";
    $config['api_url'] = "https://api.sendinblue.com/v3/smtp/email";

    $message = array();
    $message['sender'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");
    $message['to'][] = array("email" => "$email");
    $message['replyTo'] = array("name" => "$site_title", "email" => "ops@thequotemasters.com");

    if (!empty($cc)) {
        $cc_arr = explode(",", $cc);
        for ($c = 0; $c < sizeof($cc_arr); $c++)
            $message['cc'][] = array("email" => "$cc_arr[$c]");
    }

    if (!empty($bcc)) {
        $bcc_arr = explode(",", $bcc);
        if (!in_array('ops@thequotemasters.com', $bcc_arr)) {
            $bcc_arr[] = 'ops@thequotemasters.com';
            $bcc = implode(",", $bcc_arr);
        }
        for ($b = 0; $b < sizeof($bcc_arr); $b++)
            $message['bcc'][] = array("email" => "$bcc_arr[$b]");
    } else {
        $bcc = 'ops@thequotemasters.com';
    }

    $message['subject'] = $subject;
    $message['htmlContent'] = $contents;

    if (!empty($attachment)) {
        if (is_array($attachment)) {
            $attachment_item[] = array('url' => $attachment);
            $attachment_list = array($attachment_item);

            // Ends pdf wrapper
            $message['attachment'] = $attachment_list;
        } else {
            $attachment_item = array('url' => $attachment);
            $attachment_list = array($attachment_item);
            // Ends pdf wrapper

            $message['attachment'] = $attachment_list;
        }
    }

    $message_json = json_encode($message);

    $ch = curl_init();
    curl_setopt(
        $ch,
        CURLOPT_URL,
        $config['api_url']
    );
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'accept: application/json',
        'api-key: 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',
        'content-type: application/json'
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function AddSPActivity($lead, $ref_id, $ref_type, $ref_name, $desc, $mode, $now = '', $ip = '')
{

    $ref_name = db_input2($ref_name);
    $desc = db_input2(htmlspecialchars_decode($desc));

    // inserting data into app_candidate_logs table
    if (empty($now)) $now = NOW;
    LockTable('app_sp_logs');
    $txtid = NextID('iLogID', 'app_sp_logs');
    $q = "INSERT INTO app_sp_logs (iLogID, iSPID, dtDate, iRefID, cRefType, vRefName, vDesc, cMode, vIP, cStatus) values ('$txtid', '$lead', '$now', '$ref_id', '$ref_type', '$ref_name', '$desc', '$mode', '$ip', 'A')";
    $r = sql_query($q);
    UnLockTable();

    return 1;
}


function getNewAccessToken($refreshToken)
{
    $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

    $postFields = [
        "client_id" => MICROSOFT_CLIENT_ID,
        "scope" => "https://graph.microsoft.com/.default offline_access",
        "refresh_token" => $refreshToken,
        "grant_type" => "refresh_token",
        "client_secret" => MICROSOFT_CLIENT_SECRET
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // disable in dev only

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return ["error" => curl_error($ch)];
    }
    curl_close($ch);

    return json_decode($response, true);
}

function createMicrosoftEvent($access_token, $subject, $startDateTime, $endDateTime, $timeZone, $content, $location, $attendees = [])
{
    // Prepare event data
    $event_data = [
        "subject" => $subject,
        "start" => [
            "dateTime" => $startDateTime,
            "timeZone" => $timeZone
        ],
        "end" => [
            "dateTime" => $endDateTime,
            "timeZone" => $timeZone
        ],
        "body" => [
            "contentType" => "HTML",
            "content" => $content
        ],
        "location" => [
            "displayName" => $location
        ],
        "attendees" => []
    ];

    // Add attendees
    foreach ($attendees as $attendee) {
        $event_data["attendees"][] = [
            "emailAddress" => [
                "address" => $attendee["email"],
                "name" => $attendee["name"]
            ],
            "type" => isset($attendee["type"]) ? $attendee["type"] : "required"
        ];
    }

    // Initialize cURL
    $ch = curl_init("https://graph.microsoft.com/v1.0/me/events?sendUpdates=all");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event_data));

    $response = curl_exec($ch);

    // Handle cURL error
    if ($response === false) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return [
            "success" => false,
            "error" => "cURL Error: $error_msg"
        ];
    }

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded_response = json_decode($response, true);

    // Handle HTTP errors
    if ($httpcode !=201) {
        $error_message = isset($decoded_response["error"]["message"])
            ? $decoded_response["error"]["message"]
            : "Unknown error from Microsoft Graph.";
        return [
            "success" => false,
            "status_code" => $httpcode,
            "error" => $error_message,
            "full_response" => $decoded_response
        ];
    }

    // Success
    return [
        "success" => true,
        "status_code" => $httpcode,
        "data" => $decoded_response
    ];
}
