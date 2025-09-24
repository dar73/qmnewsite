<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');



$data = array('CUST_NBR' => '9001', 'MERCH_NBR' => '900300', 'DBA_NBR' => '2', 'TERMINAL_NBR' => '21', 'TRAN_TYPE' => 'CCE9', 'AMOUNT' => '77.00', 'BATCH_ID' => '1', 'TRAN_NBR' => '5', 'ORIG_AUTH_GUID' => '0A1LLGWKLHGE8VX4JUM', 'INDUSTRY_TYPE' => 'E');

$data_string = http_build_query($data);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://secure.epxuap.com',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$xmlObject = simplexml_load_string($response);
$json = json_encode($xmlObject);
$array = json_decode($json, true);

//DFA($array);
$keyValuePairs = array();
foreach ($xmlObject->FIELDS->FIELD as $field) {
    $key = (string)$field['KEY'];
    $value = (string)$field;
    //echo "Key: $key, Value: $value\n";
    $keyValuePairs[] = array($key => $value);
}

DFA($keyValuePairs);



?>
<!-- Array
(
[0] => Array
(
[MSG_VERSION] => 003
)

[1] => Array
(
[CUST_NBR] => 9001
)

[2] => Array
(
[MERCH_NBR] => 900300
)

[3] => Array
(
[DBA_NBR] => 2
)

[4] => Array
(
[TERMINAL_NBR] => 21
)

[5] => Array
(
[TRAN_TYPE] => CCE9
)

[6] => Array
(
[BATCH_ID] => 1
)

[7] => Array
(
[TRAN_NBR] => 5
)

[8] => Array
(
[LOCAL_DATE] => 081324
)

[9] => Array
(
[LOCAL_TIME] => 113923
)

[10] => Array
(
[AUTH_GUID] => 09LLM2BQUD0YFK88EEJ
)

[11] => Array
(
[AUTH_RESP] => 00
)

[12] => Array
(
[AUTH_CODE] => 051427
)

[13] => Array
(
[AUTH_RESP_TEXT] => APPROVAL 051427
)

[14] => Array
(
[AUTH_CARD_TYPE] => V
)

[15] => Array
(
[AUTH_TRAN_DATE_GMT] => 08/13/2024 03:39:23 PM
)

[16] => Array
(
[AUTH_AMOUNT_REQUESTED] => 77.00
)

[17] => Array
(
[AUTH_AMOUNT] => 77.00
)

[18] => Array
(
[AUTH_CURRENCY_CODE] => 840
)

[19] => Array
(
[NETWORK_RESPONSE] => 00
)

[20] => Array
(
[AUTH_CARD_COUNTRY_CODE] => 840
)

[21] => Array
(
[AUTH_CARD_CURRENCY_CODE] => 840
)

[22] => Array
(
[AUTH_CARD_B] => D
)

[23] => Array
(
[AUTH_CARD_C] => F
)

[24] => Array
(
[AUTH_CARD_E] => N
)

[25] => Array
(
[AUTH_CARD_F] => Y
)

[26] => Array
(
[AUTH_CARD_G] => N
)

[27] => Array
(
[AUTH_CARD_I] => Y
)

[28] => Array
(
[AUTH_MASKED_ACCOUNT_NBR] => ************0002
)

[29] => Array
(
[AUTH_CARD_L] => P
)

[30] => Array
(
[ORIG_TRAN_TYPE] => CCE9
)

[31] => Array
(
[AUTH_TRAN_IDENT] => 354226563636829
)

[32] => Array
(
[AUTH_PAR] => V40000000028FAB8191EEC1C39808
)

) -->