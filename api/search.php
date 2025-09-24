<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['search'])) {
    // $sql = "SELECT *  FROM areas WHERE zip LIKE '" . $_POST['search'] . "%' and  ( (state = 'AZ') OR (state = 'GA')  OR (state = 'FL' ) OR (state = 'TX' ) OR (state = 'PA' ) OR (state = 'MA' ) OR (state = 'MD' ) OR (state = 'CA' ) OR (state = 'NJ' ) OR (state = 'NY' ) OR (state = 'IL' ) OR (state='MN' and zip=55401) OR (state='VA' and zip in (22030,22015)) OR (state='DC' and zip in (20016,20003))  )   ";
    // $result = sql_query($sql);
    // $DATA = array();
    // if (sql_num_rows($result)) {
    //     while ($row = sql_fetch_assoc($result)) {
    //         $DATA[] = array('value' => $row['id'], 'label' => str_pad($row['zip'], 5, '0', STR_PAD_LEFT) . ',' . $row['city']. ' ,'.$row['state'], 'id' => $row['id']);
    //     }
    // } else {
    //     $DATA[] = array('value' => '0', 'label' => 'Not servicing in these area', 'id' => '');
    // }
    // sql_close();
    // header('Content-Type: application/json');
    // echo json_encode($DATA);
    // exit;


    //New Code
    $sql = "SELECT 
        z.zip_code,
        c.country_name,
        s.state_name,
        ci.city_name
    FROM 
        zip_codes z
    JOIN 
        cities ci ON z.city_id = ci.city_id
    JOIN 
        states s ON ci.state_id = s.state_id
    JOIN 
        countries c ON ci.country_id = c.country_id where c.country_id in (1,2) and 1 and ( (s.state_id = '44') OR (s.state_id = '17')  OR (s.state_id = '18' ) OR (s.state_id = '39' ) OR (s.state_id = '9' ) OR (s.state_id = '2' ) OR (s.state_id = '13' ) OR (s.state_id = '47' ) OR (s.state_id = '8' ) OR (s.state_id = '1' ) OR (s.state_id = '32' ) OR (s.state_id='28') OR (s.state_id='12' ) OR (s.state_id='15' ) OR (s.state_id='20' ) OR (s.state_id='46' ) OR (s.state_id='27' ) OR (s.state_id='11' ) OR (s.state_id='23' ) OR (s.state_id='25' ) OR (s.state_id='52' ) OR (s.state_id='49' ) OR (z.zip_code='66012' ) OR (s.state_id='34' and z.zip_code='66213'  ) OR (z.zip_code='98465' ) OR (z.zip_code='84057' ) OR (z.zip_code='22201' ) OR (z.zip_code='98409' ) OR (z.zip_code='66061' ) OR (z.zip_code='66112' ) OR (z.zip_code='98424' ) OR (z.zip_code='06825' ) OR (z.zip_code='64151' ) OR (z.zip_code='66062' )  ) and  z.zip_code LIKE '" . $_POST['search'] . "%' limit 100";
    $result = sql_query($sql);
    $DATA = array();
    if (sql_num_rows($result)) {
        while ($row = sql_fetch_assoc($result)) {
            $DATA[] = array('value' => $row['zip_code'], 'label' => str_pad($row['zip_code'], 5, '0', STR_PAD_LEFT) . ',' . $row['country_name'] . ', ' . $row['state_name'] . ',' . $row['city_name'], 'id' => $row['zip_code']);
        }
    } else {
        $DATA[] = array('value' => '0', 'label' => 'Not servicing in these area', 'id' => '');
    }
    sql_close();
    header('Content-Type: application/json');
    echo json_encode($DATA);
    exit;
}


//60440, IL, WILL,
// 

// $sql = "SELECT *  FROM areas WHERE zip LIKE '" . $_POST['search'] . "%' and  ( (County_name = 'MARICOPA' AND state = 'AZ') OR (County_name = 'FULTON' AND state = 'GA') OR (County_name = 'COBB' AND state = 'GA') OR (County_name = 'ORANGE' AND state = 'FL' )  ";