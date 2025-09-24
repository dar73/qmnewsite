<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common.php');
$result=0;
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if ($mode == 'ADD') {
    $state = $_POST['state'];
    $county = $_POST['county'];
    $city = $_POST['city'];
    $itemArray = array($state => array('county' => $county, 'city' => $city));
    if (!empty($_SESSION['COVERAGE'])) {
        if (in_array($state, array_keys($_SESSION['COVERAGE']))) {
            foreach ($_SESSION['COVERAGE'] as $k => $v) {
                if ($state == $k) {
                    
                }
            }
        } else {
            $_SESSION['COVERAGE'][$state] = array('county' => $county, 'city' => $city);
        }
    } else {
        $_SESSION['COVERAGE'] = $itemArray;
    }
    //$_SESSION[KOT]=  $_SESSION["cart_item"];
    $result = 1;
} elseif ($mode == 'REMOVE') {
    $state = $_POST['state'];
    if (!empty($_SESSION['COVERAGE'])) {
        foreach ($_SESSION['COVERAGE'] as $k => $v) {
            if ($state == $k)
                unset($_SESSION['COVERAGE'][$k]);
            if (empty($_SESSION['COVERAGE']))
                unset($_SESSION['COVERAGE']);
        }
    }

    $result = 1;
} 
echo $result;
exit;
?>