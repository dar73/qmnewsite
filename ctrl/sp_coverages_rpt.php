<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
include "../includes/common.php";
// Include autoloader 
require_once '../dompdf/autoload.inc.php';
// Reference the Dompdf namespace 
use Dompdf\Dompdf;

$txtkeyword = $state = $county = $cond   = '';

if (isset($_GET['keyword'])) $txtkeyword = $_GET['keyword'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['county'])) $county = $_GET['county'];

if (!empty($state)) {
  $cond .= " and vStates='$state' ";
  $execute_query = true;
  $COUNTY_ARR = GetXArrFromYID("SELECT DISTINCT(County_name) FROM areas WHERE state='$state' order by County_name", "");
}
if (!empty($county)) {
  $cond .= " and FIND_IN_SET('$county', vCounties) > 0 ";
  
}

$SP_IDS_ARR = GetXArrFromYID("select iproviderID from coverages where 1 $cond and iproviderID in (SELECT pid from transaction2 WHERE payment_status='A') ");


$file_name = "SP_COVERAGES_RPT.pdf";

// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

$html = '<table style="border: 1px solid black;border-collapse: collapse;">';
$SERVICE_PROVIDERS = GetXArrFromYID("select id,concat(First_name,' ',Last_name,' | ',company_name) from service_providers where cStatus='A' and id in ('" . implode("','", $SP_IDS_ARR) . "') order by id ", "3");
foreach ($SERVICE_PROVIDERS as $key => $value) {
    $html .= ' 
  <tr>
    <th colspan="3" style="border: 1px solid black;border-collapse: collapse;">'.$value.'</th>
  </tr>
  
 ';
    $COVERAGES_ARR = GetDataFromID('coverages', 'iproviderID', $key);
    if (!empty($COVERAGES_ARR)) {
        for ($u = 0; $u < sizeof($COVERAGES_ARR); $u++) {
            $i = $u + 1;
            $x_id = db_output($COVERAGES_ARR[$u]->iCoverageId);
            $x_state = db_output($COVERAGES_ARR[$u]->vStates);
            $x_counties = db_output($COVERAGES_ARR[$u]->vCounties);
            $X_counties_arr = explode(",", $x_counties);
            $x_county_str = implode(' , ', $X_counties_arr);
            $x_cities = db_output($COVERAGES_ARR[$u]->vCities);
            $X_cities_arr = explode(",", $x_cities);
            $x_city_str = implode(' , ', $X_cities_arr);
            $html .= '<tr>
    <td style="border: 1px solid black;border-collapse: collapse;">'. $x_state. '</td>
    <td style="border: 1px solid black;border-collapse: collapse;">'. $x_county_str. '</td>
    <td style="border: 1px solid black;border-collapse: collapse;">'. $x_city_str.'</td>
  </tr>';
        }
    }
}
$html .= '</table>';
//echo $html;
//echo $output;
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'portrait');
//$dompdf->set_paper(array(30, -30, 400, 1000));

//$dompdf->set_option('dpi', 62);

// Render the HTML as PDF 
$dompdf->render();

// ht$html the generated PDF to Browser 
$dompdf->stream($file_name, array("Attachment" => 0));
?>