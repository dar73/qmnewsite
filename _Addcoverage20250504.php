<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$result=0;
$html= '<table class="table table-bordered" id="coveragetable">

                      <tr style="position: sticky;top: 0;background: #315292;color: #fff;z-index: 1;">

                        <th class="text-left">State </th>
                        <th class="text-left wp_100">Counties </th>
                        <th class="text-left wp_100">Cities <span class="text-warning">(you  cover)</span></th>
                        <th style="width: 50px;">Action</th>


                      </tr>
                      <tbody>';
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if ($mode == 'ADD') {
    $state = $_POST['state'];
    $county = $_POST['county'];
    $city = (isset($_POST['city']))? $_POST['city']:'';
    $itemArray = array($state => array('county' => $county, 'city' => $city));
    if (!empty($_SESSION[COVERAGE])) {
        if (in_array($state, array_keys($_SESSION[COVERAGE]))) {
            foreach ($_SESSION[COVERAGE] as $k => $v) {
                if ($state == $k) {
                    $result=2;//if same state is added
                }
            }
        } else {
            $_SESSION[COVERAGE][$state] = array('county' => $county, 'city' => $city);
            $result = 1;
        }
    } else {
        $_SESSION[COVERAGE] = $itemArray;
        $result = 1;
    }
    //$_SESSION[KOT]=  $_SESSION["cart_item"];
    if (isset($_SESSION[COVERAGE])) {
        foreach ($_SESSION[COVERAGE] as $key => $value) {
                              $html.='<tr><td>'.$key.'</td>';
                              $html.='<td style="text-align:left; max-width: 50px;">'.(implode(",  ", $value['county'])).'</td>';
                              $html.='<td style="text-align:left; max-width: 50px;">';
                              if (isset($value['city'])&& $value['city']!='') {
                                
                                  $html.= implode(",  ", $value['city']);
                              }
                              $html.='</td>
                              <td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove('."'".$key."'".')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>
                            </tr>';

        }
    }
   
} elseif ($mode == 'REMOVE') {
    $state = $_POST['state'];
    if (!empty($_SESSION[COVERAGE])) {
        foreach ($_SESSION[COVERAGE] as $k => $v) {
            if ($state == $k)
                unset($_SESSION[COVERAGE][$k]);
            if (empty($_SESSION[COVERAGE]))
                unset($_SESSION[COVERAGE]);
        }
    }

    if (isset($_SESSION[COVERAGE])) {
        foreach ($_SESSION[COVERAGE] as $key => $value) {
            $html .= '<tr><td>' . $key . '</td>';
            $html .= '<td style="text-align:left; max-width: 50px;">' . (implode(",  ", $value['county'])) . '</td>';
            $html .= '<td style="text-align:left; max-width: 50px;">';
            if (isset($value['city'])&& $value['city'] != '') {

                $html .= implode(",  ", $value['city']);
            }
            $html .= '</td>
                              <td style="text-align:center; width: 50px;"><a href="javascript:void(0)" onclick="remove('."'". $key ."'".')" class="btnRemoveAction"><i class="fa fa-remove"></i></a></td>
                            </tr>';
        }
    }

    $result = 1;
} 
$html.='</tbody></table>';
echo $result.'~~'.$html;
exit;
?>