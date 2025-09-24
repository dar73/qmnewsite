<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include "../includes/common.php";

$txtkeyword = $txtphone = $state = $cond = $city = $params2 = $cmbplan = '';

if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['city'])) $city = $_GET['city'];
if (isset($_GET['cmbplan'])) $cmbplan = $_GET['cmbplan'];


if (!empty($txtkeyword)) {
    //$cond .= " and (vName LIKE '%" . $txtkeyword . "%')";
    $execute_query = true;
}


if (!empty($state)) {
    $cond .= " and a.state='$state'";
    $execute_query = true;
}

if (!empty($city)) {
    $cond .= " and a.city='$city'";
    $execute_query = true;
}

$q = "SELECT
  b.iCustomerID,
  b.iAreaID,
  c.vFirstname,
  c.vLastname,
  c.vName_of_comapny,
  c.vAddress,
  c.vPosition,
  c.vEmail,
  c.vPhone,
  c.cStatus,
  c.dtRegistration,
  b.dDate,
  a.zip,
  a.state,
  a.city
FROM
  booking b
  INNER JOIN customers c ON b.iCustomerID = c.iCustomerID inner join areas a on a.id=b.iAreaID where 1 $cond ";

$r = sql_query($q);

$EXCEL_DATA = array(array('SrNo', 'First Name', 'Last Name', 'Company Name', 'Phone', 'Email Address', 'Position', 'Registered Date', 'State','City','Zip'));
//  <th>#</th>
//                                                 <th>First Name</th>
//                                                 <th>Last Name</th>
//                                                 <th>Company Name</th>
//                                                 <th>Phone</th>
//                                                 <th>Email Address</th>
//                                                 <th>Position</th>
//                                                 <th>Registered Date</th>
//                                                 <th>State</th>
//                                                 <th>city</th>
//                                                 <th>Zip</th>
if (sql_num_rows($r)) {
    $i = 1;
    while ($a = sql_fetch_assoc($r)) {
        $x_id = $a['iCustomerID'];
        $x_fname = $a['vFirstname'];
        $x_lname = $a['vLastname'];
        $x_Company_Name = $a['vName_of_comapny'];
        $x_phone = $a['vPhone'];
        $x_email = $a['vEmail'];
        $x_position = $a['vPosition'];
        $state = $a['state'];
        $city = $a['city'];
        $zip = $a['zip'];
        $EXCEL_DATA[] = [
            $i,
            $x_fname,
            $x_lname,
            $x_Company_Name,
            $x_phone,
            $x_email,
            $x_position,
            date('m-d-Y', strtotime($a['dtRegistration'])),
            $state,
            $city,
            $zip
        ];
        $i++;
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
header('Content-Disposition: attachment; filename="customers.csv";');

// Output the contents of the file to the browser
fpassthru($fp);

// Close file handle
fclose($fp);


