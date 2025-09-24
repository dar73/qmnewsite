<?php include "../includes/common.php";


$Q = "SELECT id, zip, zipcode_name, city, state, County_name FROM areas";
$R = sql_query($Q);
while (list($id, $zip, $zipcode_name, $city, $state, $County_name) = sql_fetch_row($R)) {
    if (!isset($GET_AREA_ARRAY[$id]))
        $GET_AREA_ARRAY[$id] = array('id' => $id, 'zip' => $zip, 'zipcodename' => $zipcode_name, 'state' => $state, 'city' => $city, 'county_name' => $County_name);
}
$_q = "SELECT dDate, ivendor_id, ibooking_id, fAmt, vTransactionID FROM buyed_leads WHERE ivendor_id='$sess_user_id' ";
$_r = sql_query($_q, '');
while (list($dDate, $ivendor_id, $ibooking_id, $fAmt, $vTransactionID) = sql_fetch_row($_r)) {
    if (!isset($LEADS_ARR[$ibooking_id]))
        $LEADS_ARR[$ibooking_id] = array('bid' => $ibooking_id, 'Amt' => $fAmt, 'vTransactionID' => $vTransactionID, 'dDate' => $dDate);
}

$TIME_ARR = GetXArrFromYID("select Id,title from apptime where 1", "3");
$CUSTOMER_ARR=array();
// SELECT `iCustomerID`, `vFirstname`, `vLastname`, `vName_of_comapny`, `vAddress`, `vPosition`, `vEmail`, `vPassword`, `vPhone`, `dtRegistration`, `cMailsent`, `cStatus` FROM `customers` WHERE 1
$customer_q= "select iCustomerID,vFirstname,vLastname,vName_of_comapny,vAddress,vPosition,vEmail,vPhone from customers where 1 and cStatus!='X' ";
$customer_r=sql_query($customer_q);
while(list($iCustomerID,$vFirstname,$vLastname,$vName_of_comapny,$vAddress,$vPosition,$vEmail,$vPhone)=sql_fetch_row($customer_r)){
    if(!isset($CUSTOMER_ARR[$iCustomerID]))
        $CUSTOMER_ARR[$iCustomerID]=array('iCustomerID'=>$iCustomerID,'vFirstname'=>$vFirstname,'vLastname'=>$vLastname,'vName_of_comapny'=>$vName_of_comapny,'vAddress'=>$vAddress,'vPosition'=>$vPosition,'vEmail'=>$vEmail,'vPhone'=>$vPhone);
}
$CUSTOMER_ARR2 = GetXArrFromYID('SELECT iCustomerID,vFirstname FROM customers', '3');

$dataArr = GetDataFromQuery("select t1.* from appointments t1 inner join buyed_leads t2 on t2.iApptID=t1.iApptID where t2.ivendor_id='$sess_user_id' order by iApptID DESC");
// //<th>#</th>
//                                                 <th>Appointment ID</th>
//                                                 <th>Zip</th>
//                                                 <th>state</th>
//                                                 <th>Customer Name</th>
//                                                 <th>Charge</th>
//                                                 <th>Schedule</th>
$EXCEL_DATA = array(array('SrNo', 'Appointment ID', 'Zip', 'State','City', 'Customer','Address','Email','Phone', 'Charge', 'Schedule'));
if (!empty($dataArr)) {
    for ($u = 0; $u < sizeof($dataArr); $u++) {
        $i = $u + 1;
        $x_id = db_output($dataArr[$u]->iApptID);
        $BID = db_output($dataArr[$u]->iBookingID);
        $x_areaID = db_output($dataArr[$u]->iAreaID);
        $x_zip = $GET_AREA_ARRAY[$x_areaID]['zip'];
        $x_state = $GET_AREA_ARRAY[$x_areaID]['state'];
        $city = $GET_AREA_ARRAY[$x_areaID]['city'];
        // $x_num_of_quotes = db_output($dataArr[$u]->iNo_of_quotes);
        $x_customer_name = $CUSTOMER_ARR2[$dataArr[$u]->iCustomerID];
        $Adddress=isset($CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vAddress'])?$CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vAddress']:'';
        $Email=isset($CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vEmail'])?$CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vEmail']:'';
        $Phone=isset($CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vPhone'])?$CUSTOMER_ARR[$dataArr[$u]->iCustomerID]['vPhone']:'';
        //$x_selfs = db_output($dataArr[0]->cSelf_schedule);
        $x_service_status = $dataArr[$u]->cService_status;
        $APP_DATE = db_output($dataArr[$u]->dDateTime);
        $TIME_ID = db_output($dataArr[$u]->iAppTimeID);

        $EXCEL_DATA[] = [
            $i,
            'QM-' . $x_id,
            $x_zip,
            $x_state,
            $city,
            $x_customer_name,
            $Adddress,
            $Email,
            $Phone,
            $LEADS_ARR[$BID]['Amt'],
            date('m-d-Y', strtotime($APP_DATE)) . ' @ ' . $TIME_ARR[$TIME_ID]
        ];

        //$status_str = GetStatusImageString('PACKAGES', $stat, $x_id, true);
        
    }
}
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
header('Content-Disposition: attachment; filename="Bookings.csv";');

// Output the contents of the file to the browser
fpassthru($fp);

// Close file handle
fclose($fp);
sql_close();
?>