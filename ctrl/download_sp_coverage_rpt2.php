<?php
include "../includes/common.php";

$txtkeyword = $state = $county = $cond = $params = $params2 = '';

$EMAIL_VERIFY = array('0' => 'No', '1' => 'Yes');

$txtkeyword=isset($_GET['keyword'])? $_GET['keyword']:'';
$state=isset($_GET['state'])? $_GET['state']:'';
$county=isset($_GET['county'])? $_GET['county']:'';
$SP_IDS_ARR = array();
// Excel file name for download 
$fileName = "coverage-data_" . date('Y-m-d h:i A') . ".csv"; 

if (!empty($state)) {
    $cond .= " and vStates='$state' ";
    $execute_query = true;
    $COUNTY_ARR = GetXArrFromYID("SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name", "");
}
if (!empty($county)) {
    $cond .= " and FIND_IN_SET('$county', vCounties) > 0 ";
    $execute_query = true;
}

if ($execute_query) {
    # code...
    $SP_IDS_ARR = GetXArrFromYID("select iproviderID from coverages where 1 $cond ");
}

if (!empty($SP_IDS_ARR)) {
    $dataArr = GetDataFromCOND("service_providers", " and cStatus!='X' and id in ('" . implode("','", $SP_IDS_ARR) . "') order by id");
}
$EXCEL_DATA=array(array('Id', 'First Name', 'Last Name', 'Company Name', 'Phone', 'Email Address'));

if (!empty($dataArr)) {
    for ($u = 0; $u < sizeof($dataArr); $u++) {
        $i = $u + 1;
        $x_id = db_output($dataArr[$u]->id);
        $x_timestamp = db_output($dataArr[$u]->dDate);
        $x_fname = db_output($dataArr[$u]->First_name);
        $x_lname = db_output($dataArr[$u]->Last_name);
        $x_Company_Name = db_output($dataArr[$u]->company_name);
        $x_phone = db_output($dataArr[$u]->phone);
        $x_state = $dataArr[$u]->state;
        $x_county = $dataArr[$u]->county;
        $x_email = db_output($dataArr[$u]->email_address);
        $x_emailVerify = db_output($dataArr[$u]->email_verify);
        $stat = $dataArr[$u]->cStatus;
        $status_str = GetStatusImageString('SERVICEPROVIDERS', $stat, $x_id, true);
       // $url = $edit_url . '?mode=E&id=' . $x_id;
        $PERCENTAGE = calculateProfilePer($x_id);
        array_push($EXCEL_DATA,array($x_id, $x_fname, $x_lname, $x_Company_Name,$x_phone,$x_email));
    }
}

// Sample data
// $data = array(
//     array('John', 'Doe', 28),
//     array('Jane', 'Smith', 32),
//     array('Bob', 'Johnson', 45),
// );

// Open file handle
$fp = fopen('php://temp', 'w');

// Write data to file
foreach ($EXCEL_DATA as $row) {
    fputcsv($fp, $row);
}

// Reset file pointer to the beginning of the file
rewind($fp);

// Set headers to download the file as a CSV attachment
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$fileName\"");

// Output the contents of the file to the browser
fpassthru($fp);

// Close file handle
fclose($fp);
