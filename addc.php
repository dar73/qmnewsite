<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$result=0;
$cartArr = isset($_SESSION['COVERAGE']->cart) ? $_SESSION['COVERAGE']->cart : array();
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if ($mode == 'ADD') {
    $state = $_POST['state'];
    $county = $_POST['county'];
    $city = $_POST['city'];
    $itemArray = array($state => array('county' => $county, 'city' => $city));
    if (!empty($_SESSION['COVERAGE']->cart)) {
        if (in_array($state, array_keys($_SESSION['COVERAGE']->cart))) {
            foreach ($_SESSION['COVERAGE']->cart as $k => $v) {
                if ($state == $k) {
                    
                }
            }
        } else {
            $_SESSION['COVERAGE']->cart[$state] = array('county' => $county, 'city' => $city);
        }
    } else {
        $_SESSION['COVERAGE']->cart = $itemArray;
    }
    //$_SESSION[KOT]->cart=  $_SESSION["cart_item"];
    $result = 1;
} elseif ($mode == 'REMOVE') {
    $state = $_POST['state'];
    if (!empty($_SESSION['COVERAGE']->cart)) {
        foreach ($_SESSION['COVERAGE']->cart as $k => $v) {
            if ($state == $k)
                unset($_SESSION['COVERAGE']->cart[$k]);
            if (empty($_SESSION['COVERAGE']->cart))
                unset($_SESSION['COVERAGE']->cart);
        }
    }

    $result = 1;
} 
echo $result;
exit;
