<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');

$COUNTRIES = GetXArrFromYID("SELECT country_id, country_name FROM countries WHERE 1 ", '3');
$STATE_ARR = GetXArrFromYID("SELECT state_id, state_name FROM states WHERE 1 ", '3');
$CITY_ARR = GetXArrFromYID("SELECT city_id, city_name FROM cities WHERE 1 ", '3');
$COUNTY_ARR = GetXArrFromYID("SELECT county_id, county_name FROM counties WHERE 1", '3');
$result = 0;
$html = '<table class="table table-bordered" id="coveragetable">
                      <tr style="position: sticky;top: 0;background: #315292;color: #fff;z-index: 1;">
                        <th class="text-left">Country</th>
                        <th class="text-left">State</th>
                        <th class="text-left wp_100">Counties <span class="text-warning">(you cover)</span></th>
                        <th class="text-left wp_100">Cities <span class="text-warning">(you cover)</span></th>
                        <th style="width: 50px;">Action</th>
                      </tr>
                      <tbody>';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if ($mode == 'ADD') {
    $country = $_POST['country'];
    $state = $_POST['state'];
    $county = (isset($_POST['county'])) ? $_POST['county'] : '';
    $city = (isset($_POST['city'])) ? $_POST['city'] : '';
    $itemArray = array($country => array($state => array('county' => $county, 'city' => $city)));
    if (!empty($_SESSION[COVERAGE])) {
        if (isset($_SESSION[COVERAGE][$country]) && in_array($state, array_keys($_SESSION[COVERAGE][$country]))) {
            $result = 2; // if the same state is added under the same country
        } else {
            $_SESSION[COVERAGE][$country][$state] = array('county' => $county, 'city' => $city);
            $result = 1;
        }
    } else {
        $_SESSION[COVERAGE] = $itemArray;
        $result = 1;
    }
    if (isset($_SESSION[COVERAGE])) {
        foreach ($_SESSION[COVERAGE] as $countryKey => $states) {
            foreach ($states as $stateKey => $value) {
                $html .= '<tr><td>' . $COUNTRIES[$countryKey] . '</td>';
                $html .= '<td>' . $STATE_ARR[$stateKey] . '</td>';
                $html .= '<td style="text-align:left; max-width: 50px;">';
                if (isset($value['county']) && $value['county'] != '') {
                    $countyNames = array_map(function ($countyId) use ($COUNTY_ARR) {
                        return $COUNTY_ARR[$countyId];
                    }, $value['county']);
                    $html .= implode(",  ", $countyNames);
                }
                $html .= '</td><td style="text-align:left; max-width: 50px;">';
                if (isset($value['city']) && $value['city'] != '') {
                    $cityNames = array_map(function ($cityId) use ($CITY_ARR) {
                        return $CITY_ARR[$cityId];
                    }, $value['city']);
                    $html .= implode(",  ", $cityNames);
                }
                $html .= '</td>
                          <td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove(' . "'" . $countryKey . "', '" . $stateKey . "'" . ')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>
                        </tr>';
            }
        }
    }
} elseif ($mode == 'REMOVE') {
    $country = $_POST['country'];
    $state = $_POST['state'];
    if (!empty($_SESSION[COVERAGE])) {
        if (isset($_SESSION[COVERAGE][$country][$state])) {
            unset($_SESSION[COVERAGE][$country][$state]);
            if (empty($_SESSION[COVERAGE][$country])) {
                unset($_SESSION[COVERAGE][$country]);
            }
        }
    }
    if (isset($_SESSION[COVERAGE])) {
        foreach ($_SESSION[COVERAGE] as $countryKey => $states) {
            foreach ($states as $stateKey => $value) {
                $html .= '<tr><td>' . $COUNTRIES[$countryKey] . '</td>';
                $html .= '<td>' . $STATE_ARR[$stateKey] . '</td>';
                $html .= '<td style="text-align:left; max-width: 50px;">';
                if (isset($value['county']) && $value['county'] != '') {
                    $countyNames = array_map(function ($countyId) use ($COUNTY_ARR) {
                        return $COUNTY_ARR[$countyId];
                    }, $value['county']);
                    $html .= implode(",  ", $countyNames);
                }
                $html .= '</td><td style="text-align:left; max-width: 50px;">';
                if (isset($value['city']) && $value['city'] != '') {
                    $cityNames = array_map(function ($cityId) use ($CITY_ARR) {
                        return $CITY_ARR[$cityId];
                    }, $value['city']);
                    $html .= implode(",  ", $cityNames);
                }
                $html .= '</td>
                          <td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove(' . "'" . $countryKey . "', '" . $stateKey . "'" . ')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>
                        </tr>';
            }
        }
    }
    $result = 1;
}

// Render table: one row per country/state
// if (isset($_SESSION[COVERAGE])) {
//     foreach ($_SESSION[COVERAGE] as $countryKey => $states) {
//         foreach ($states as $stateKey => $value) {
//             $countyNames = [];
//             if (!empty($value['counties'])) {
//                 foreach ($value['counties'] as $countyId) {
//                     if (isset($COUNTY_ARR[$countyId])) {
//                         $countyNames[] = $COUNTY_ARR[$countyId];
//                     }
//                 }
//             }
//             $cityNames = [];
//             if (!empty($value['city'])) {
//                 foreach ($value['city'] as $cityId) {
//                     if (isset($CITY_ARR[$cityId])) {
//                         $cityNames[] = $CITY_ARR[$cityId];
//                     }
//                 }
//             }
//             $html .= '<tr>';
//             $html .= '<td>' . $COUNTRIES[$countryKey] . '</td>';
//             $html .= '<td>' . $STATE_ARR[$stateKey] . '</td>';
//             $html .= '<td>' . implode(", ", $countyNames) . '</td>';
//             $html .= '<td style="text-align:left; max-width: 50px;">' . implode(", ", $cityNames) . '</td>';
//             $html .= '<td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove(\'' . $countryKey . '\', \'' . $stateKey . '\')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>';
//             $html .= '</tr>';
//         }
//     }
// }
$html .= '</tbody></table>';
echo $result . '~~' . $html;
exit;
?>
