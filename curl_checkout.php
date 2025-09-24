<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');


$curl = curl_init();

$data = array('CUST_NBR' => '3001', 'MERCH_NBR' => '3130034428641', 'DBA_NBR' => '1', 'TERMINAL_NBR' => '3', 'TRAN_TYPE' => 'CCE1', 'AMOUNT' => '0.00', 'BATCH_ID' => '1', 'TRAN_NBR' => '5', 'ORIG_AUTH_GUID' => '088LM50Z604LNEEDUM3', 'INDUSTRY_TYPE' => 'E', 'FIRST_NAME' => 'Jerry', 'LAST_NAME' => 'Garcia', 'ADDRESS' => 'TEST', 'CITY' => 'Lubbock', 'STATE' => 'TX', 'ZIP_CODE' => '12345', 'ACI_EXT' => 'RB');

$data_string = http_build_query($data);

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://secure.epx.com',
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

// Array
// (
//     [0] => Array
//         (
//             [MSG_VERSION] => 003
//         )

//     [1] => Array
//         (
//             [CUST_NBR] => 3001
//         )

//     [2] => Array
//         (
//             [MERCH_NBR] => 3130034428641
//         )

//     [3] => Array
//         (
//             [DBA_NBR] => 1
//         )

//     [4] => Array
//         (
//             [TERMINAL_NBR] => 3
//         )

//     [5] => Array
//         (
//             [TRAN_TYPE] => CCE1
//         )

//     [6] => Array
//         (
//             [BATCH_ID] => 1
//         )

//     [7] => Array
//         (
//             [TRAN_NBR] => 5
//         )

//     [8] => Array
//         (
//             [LOCAL_DATE] => 081424
//         )

//     [9] => Array
//         (
//             [LOCAL_TIME] => 123907
//         )

//     [10] => Array
//         (
//             [AUTH_GUID] => 07XLM51LBEPG8PXV94K
//         )

//     [11] => Array
//         (
//             [AUTH_RESP] => 00
//         )

//     [12] => Array
//         (
//             [AUTH_CODE] => 07218Z
//         )

//     [13] => Array
//         (
//             [AUTH_AVS] => N
//         )

//     [14] => Array
//         (
//             [AUTH_RESP_TEXT] => NO MATCH
//         )

//     [15] => Array
//         (
//             [AUTH_CARD_TYPE] => M
//         )

//     [16] => Array
//         (
//             [AUTH_TRAN_DATE_GMT] => 08/14/2024 04:39:06 PM
//         )

//     [17] => Array
//         (
//             [AUTH_AMOUNT_REQUESTED] => 1.00
//         )

//     [18] => Array
//         (
//             [AUTH_AMOUNT] => 1.00
//         )

//     [19] => Array
//         (
//             [AUTH_CURRENCY_CODE] => 840
//         )

//     [20] => Array
//         (
//             [NETWORK_RESPONSE] => 00
//         )

//     [21] => Array
//         (
//             [AUTH_CARD_COUNTRY_CODE] => 840
//         )

//     [22] => Array
//         (
//             [AUTH_CARD_COUNTRY_NAME] => USA
//         )

//     [23] => Array
//         (
//             [AUTH_CARD_CURRENCY_CODE] => 840
//         )

//     [24] => Array
//         (
//             [AUTH_CARD_CURRENCY_NAME] => USD
//         )

//     [25] => Array
//         (
//             [AUTH_CARD_B] => MCC
//         )

//     [26] => Array
//         (
//             [AUTH_CARD_A] => MWE
//         )

//     [27] => Array
//         (
//             [AUTH_CARD_C] => MWE
//         )

//     [28] => Array
//         (
//             [AUTH_MASKED_ACCOUNT_NBR] => ************0793
//         )

//     [29] => Array
//         (
//             [AUTH_CARD_K] => N
//         )

//     [30] => Array
//         (
//             [AUTH_CARD_L] => C
//         )

//     [31] => Array
//         (
//             [ORIG_TRAN_TYPE] => CCE1
//         )

//     [32] => Array
//         (
//             [AUTH_TRAN_IDENT] => 0814MWEI3GKIW
//         )

//     [33] => Array
//         (
//             [AUTH_PAR] => 500160QBV7MEU26SJ7DCDZT2JJNP2
//         )

// )


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
[TRAN_TYPE] => CCE1
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
[LOCAL_TIME] => 111003
)

[10] => Array
(
[AUTH_GUID] => 09LLM2A5J087XZ2L67Z
)

[11] => Array
(
[AUTH_RESP] => 00
)

[12] => Array
(
[AUTH_CODE] => 051049
)

[13] => Array
(
[AUTH_AVS] => Z
)

[14] => Array
(
[AUTH_RESP_TEXT] => ZIP MATCH
)

[15] => Array
(
[AUTH_CARD_TYPE] => V
)

[16] => Array
(
[AUTH_TRAN_DATE_GMT] => 08/13/2024 03:10:03 PM
)

[17] => Array
(
[AUTH_AMOUNT_REQUESTED] => 77.00
)

[18] => Array
(
[AUTH_AMOUNT] => 77.00
)

[19] => Array
(
[AUTH_CURRENCY_CODE] => 840
)

[20] => Array
(
[NETWORK_RESPONSE] => 00
)

[21] => Array
(
[AUTH_CARD_COUNTRY_CODE] => 840
)

[22] => Array
(
[AUTH_CARD_CURRENCY_CODE] => 840
)

[23] => Array
(
[AUTH_CARD_B] => D
)

[24] => Array
(
[AUTH_CARD_C] => F
)

[25] => Array
(
[AUTH_CARD_E] => N
)

[26] => Array
(
[AUTH_CARD_F] => Y
)

[27] => Array
(
[AUTH_CARD_G] => N
)

[28] => Array
(
[AUTH_CARD_I] => Y
)

[29] => Array
(
[AUTH_MASKED_ACCOUNT_NBR] => ************0002
)

[30] => Array
(
[AUTH_CARD_L] => P
)

[31] => Array
(
[ORIG_TRAN_TYPE] => CCE1
)

[32] => Array
(
[AUTH_TRAN_IDENT] => 354219580146718
)

[33] => Array
(
[AUTH_PAR] => V40000000028FAB8191EEC1C39808
)

) -->