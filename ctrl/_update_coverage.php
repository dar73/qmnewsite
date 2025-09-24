<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
include '../includes/common.php';
$result=0;
$mode = (isset($_POST['mode'])) ? $_POST['mode'] : '';
if ($mode == 'UPDATE') {
$_q1= "delete from service_providers_areas where service_providers_id='$sess_user_id' ";
$_r1=sql_query($_q1,'DELETE SERVICE PROVIDERS');
if ($_r1) {
        if (isset($_SESSION['COVERAGE'])) {
            foreach ($_SESSION['COVERAGE'] as $key => $value) {
                $str1 = '';
                $state = " state='$key' ";

                $str1 .= "  AND  County_name IN ('" . implode("','", $value['county']) . "')";

                $str2 = '';
                if (!empty($value['city'])) {
                    //$cityarray = explode(',', $cityarr);
                    $str2 .= "  AND  city NOT IN ('" . implode("','", $value['city']) . "')";
                }

                $Getzipq = "SELECT zip,id FROM areas WHERE " . $state . $str1 . $str2;

                $GetzipqR = sql_query($Getzipq);
                while ($R = sql_fetch_assoc($GetzipqR)) {
                    //array_push($data, array('zip' => $R['zip'], 'id' => $R['id']));
                    sql_query("INSERT INTO service_providers_areas( service_providers_id, zip) VALUES ('$sess_user_id','" . $R['zip'] . "')");
                }
            }
        }
        unset($_SESSION['COVERAGE']);
        $result=1;
    
}
}
echo $result;
exit;
?>