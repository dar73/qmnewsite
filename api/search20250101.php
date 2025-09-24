<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['search'])) {
    $sql = "SELECT *  FROM areas WHERE zip LIKE '" . $_POST['search'] . "%' and  ( (state = 'AZ') OR (state = 'GA')  OR (state = 'FL' ) OR (state = 'TX' ) OR (state = 'PA' ) OR (state = 'MA' ) OR (state = 'MD' ) OR (state = 'CA' ) OR (state = 'NJ' ) OR (state = 'NY' ) OR (state = 'IL' ) OR (state='MN' and zip=55401 ) )   ";
    $result = sql_query($sql);
    if (sql_num_rows($result)) {
        while ($row = sql_fetch_assoc($result)) {
            echo '<a href="javascript:void(0);" data-id="' . $row['id'] . '" class="list-group list-group-item-action border p-2">' . $row['zip'] . ', ' . $row['city'] . ', ' . $row['state'] . '</a>';
        }
    } else {
        echo '<p class="list-group list-group-item">Currently not servicing this zipcode</p>';
    }
}


//60440, IL, WILL,
// 

// $sql = "SELECT *  FROM areas WHERE zip LIKE '" . $_POST['search'] . "%' and  ( (County_name = 'MARICOPA' AND state = 'AZ') OR (County_name = 'FULTON' AND state = 'GA') OR (County_name = 'COBB' AND state = 'GA') OR (County_name = 'ORANGE' AND state = 'FL' )  ";