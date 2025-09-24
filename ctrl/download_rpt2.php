<?php
include "../includes/common.php";

$txtkeyword = $state = $county = $cond = $params = $params2 = '';

$EMAIL_VERIFY = array('0' => 'No', '1' => 'Yes');

$txtkeyword=isset($_GET['keyword'])? $_GET['keyword']:'';
$state=isset($_GET['state'])? $_GET['state']:'';
$county=isset($_GET['county'])? $_GET['county']:'';

if (!empty($txtkeyword)) {
    $cond .= " and (First_name LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}
if (!empty($state)) {
    $cond .= " and state='$state' ";
    $execute_query = true;
    $COUNTY_ARR = GetXArrFromYID("SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name", "");
}
if (!empty($county)) {
    $cond .= " and county='$county' ";
    $execute_query = true;
}

$dataArr = GetDataFromCOND("service_providers", $cond . " and cStatus!='X' and (vLicence_file='' or vInsurance_file='' or vGovtID='') order by id");
$EXCEL_DATA=array(array('Id', 'First Name', 'Last Name', 'Company Name','State','County', 'Phone', 'Email Address', 'Profile Status', 'Email Verify', 'Timestamp', 'Have BI'));

if (!empty($dataArr)) {
    for ($u = 0; $u < sizeof($dataArr); $u++) {
        $i = $u + 1;
        $x_id = db_output2($dataArr[$u]->id);
        $x_timestamp = db_output2($dataArr[$u]->dDate);
        $x_fname = db_output2($dataArr[$u]->First_name);
        $x_lname = db_output2($dataArr[$u]->Last_name);
        $x_Company_Name = db_output2($dataArr[$u]->company_name);
        $x_phone = db_output2($dataArr[$u]->phone);
        $rdISBI = db_output2($dataArr[$u]->cHaveBI); //$dataArr[$u]->rdISBI;
        $rdISBI = isset($YES_ARR[$rdISBI]) ? $YES_ARR[$rdISBI] : 'NA';
        $x_state = $dataArr[$u]->state;
        $x_county = $dataArr[$u]->county;
        $x_email = db_output2($dataArr[$u]->email_address);
        $x_emailVerify = db_output2($dataArr[$u]->email_verify);
        $stat = $dataArr[$u]->cStatus;
        $status_str = GetStatusImageString('SERVICEPROVIDERS', $stat, $x_id, true);
       // $url = $edit_url . '?mode=E&id=' . $x_id;
        $PERCENTAGE = calculateProfilePer($x_id);
        array_push($EXCEL_DATA,array($x_id, $x_fname, $x_lname, $x_Company_Name,$x_state,$x_county,$x_phone,$x_email,$PERCENTAGE,$EMAIL_VERIFY[$x_emailVerify], date('m/d/Y' . ', ' . 'h:i A', strtotime($x_timestamp)),$rdISBI));
    }
}

// Sample data
$data = array(
    array('John', 'Doe', 28),
    array('Jane', 'Smith', 32),
    array('Bob', 'Johnson', 45),
);

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
header('Content-Disposition: attachment; filename="data.csv";');

// Output the contents of the file to the browser
fpassthru($fp);

// Close file handle
fclose($fp);
?>