<?php
include "../includes/common.php";

$txtkeyword = $txtphone = $cmbcleaningstatus = $cond = $params = $params2 = $cmbplan = '';

if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
if (isset($_GET['txtphone'])) $txtphone = $_GET['txtphone'];
if (isset($_GET['cmbcleaningstatus'])) $cmbcleaningstatus = $_GET['cmbcleaningstatus'];
if (isset($_GET['cmbplan'])) $cmbplan = $_GET['cmbplan'];



if (!empty($txtkeyword)) {
    $txtkeyword = db_input2($txtkeyword);
    $cond .= " and (company_name LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}

if (!empty($txtphone)) {
    $cond .= " and (phone LIKE '%" . $txtphone . "%')";
    $execute_query = true;
}

if (!empty($cmbcleaningstatus)) {
    $cond .= " and cCleaningStatus='$cmbcleaningstatus' ";
    $execute_query = true;
}

if ($cmbplan) {
    $cond .= " and cUsertype='$cmbplan'";
    $execute_query = true;
}


$dataArr = GetDataFromCOND("service_providers", $cond . " and cStatus!='X' order by id desc");
$EXCEL_DATA = array(array('Id', 'First Name', 'Last Name', 'Company Name','Phone', 'Email Address'));

if (!empty($dataArr)) {
    for ($u = 0; $u < sizeof($dataArr); $u++) {
        $i = $u + 1;
        $x_id = db_output2($dataArr[$u]->id);
        $x_timestamp = db_output2($dataArr[$u]->dDate);
        $x_fname = db_output2($dataArr[$u]->First_name);
        $x_lname = db_output2($dataArr[$u]->Last_name);
        $x_Company_Name = db_output2($dataArr[$u]->company_name);
        $x_phone = db_output2($dataArr[$u]->phone);
        $x_email = db_output2($dataArr[$u]->email_address);
        array_push($EXCEL_DATA, array($x_id, $x_fname, $x_lname, $x_Company_Name, $x_phone, $x_email));
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
header('Content-Disposition: attachment; filename="spdata.csv";');

// Output the contents of the file to the browser
fpassthru($fp);

// Close file handle
fclose($fp);
