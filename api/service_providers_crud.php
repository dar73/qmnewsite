<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['type'])) {
    $type = $_POST['type'];
    switch ($type) {
        case 'add':
            /* Getting file name */
            $filename = $_FILES['image']['name'];

            /* Location */
            $location = "../uploads/" . $filename;
            $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);

            /* Valid extensions */
            $valid_extensions = array("jpg", "jpeg", "png", "svg");

            $response = 0;
            /* Check file extension */
            if (in_array(strtolower($imageFileType), $valid_extensions)) {
                /* Upload file */
                if (move_uploaded_file($_FILES['image']['tmp_name'], $location)) {
                    $response = $location;
                }
            }
            //image url
            $url = 'uploads/' . $filename;
            $sql = "INSERT INTO `service_categories`(name,dDate,status,img) VALUES ('" . $_POST['name'] . "',NOW(),'1','$url')";
            if (sql_query($sql)) {
                echo 'Data Inserted';
            } else {

                echo mysqli_error($conn);
            }

            break;

        case 'delete':
            if (isset($_POST["id"])) {
                $query = "DELETE FROM service_providers WHERE id='" . $_POST["id"] . "' ";
                if (sql_query($query)) {
                    echo ' Deleted';
                } else {
                    echo 'Data Not Deleted';
                }
            }

            break;
        case 'getall':

            $columns = array('id');

            $query = "SELECT * FROM service_providers";

            if (isset($_POST["search"]["value"])) {
                $query .= '
            WHERE id LIKE "%' . $_POST["search"]["value"] . '%" 

            ';
            }

            if (isset($_POST["order"])) {
                $query .= 'ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' 
            ';
            } else {
                $query .= 'ORDER BY id ASC ';
            }

            $query1 = '';

            if ($_POST["length"] != -1) {
                $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }

            $number_filter_row = sql_num_rows(sql_query($query));
            //echo $number_filter_row;
            $result = sql_query($query . $query1);

            $data = array();
            while ($row = sql_fetch_array($result)) {
                $sub_array = array();
                $sub_array['id'] = $row['id'];
                $sub_array['dDate'] = $row['dDate'];
                $sub_array['First_name'] = $row['First_name'];
                $sub_array['Last_name'] = $row['Last_name'];
                $sub_array['company_name'] = $row['company_name'];
                $sub_array['street'] = $row['street'];
                $sub_array['state'] = $row['state'];
                $sub_array['county'] = $row['county'];
                $sub_array['city'] = $row['city'];
                $sub_array['phone'] = $row['phone'];
                $sub_array['email_address'] = $row['email_address'];
                $sub_array['license_number'] = $row['license_number'];
                $sub_array['pdf_file'] = $row['pdf_file'];
                $sub_array['dDate_of_expiry'] = $row['dDate_of_expiry'];
                $sub_array['dDate_insurance'] = $row['dDate_insurance'];
                $sub_array['insurance_file'] = $row['insurance_file'];
                $sub_array['email_verify'] = $row['email_verify'];
                $sub_array['approve'] = $row['approve'];
                $data[] = $sub_array;
            }

            function get_all_data()
            {
                $query = "SELECT * FROM service_providers ";
                $result = sql_query($query);
                return sql_num_rows($result);
            }

            $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  get_all_data(),
                "recordsFiltered" => $number_filter_row,
                "data"    => $data
            );

            echo json_encode($output);
            break;
        case 'edit':
            $sql = "SELECT * FROM sales WHERE id='" . $_POST['id'] . "'";
            $result = sql_query($sql);
            $output = array(
                "error" => false,
                "data" => sql_fetch_assoc($result)
            );
            header('Content-Type: application/json');
            echo json_encode($output);
            break;
        case 'update':
            $sql = "UPDATE sales SET date='" . $_POST['date'] . "',total_sales_amt='" . $_POST['esale_amt'] . "',total_sales_count='" . $_POST['esale_count'] . "' WHERE id='" . $_POST['id'] . "'";
            $result = sql_query($sql);
            if ($result) {
                $output = array(
                    "error" => false,
                    "message" => 'Record Updated Successfuly'
                );
                header('Content-Type: application/json');
                echo json_encode($output);
            } else {
                $output = array(
                    "error" => true,
                    "message" => sql_error()
                );
                header('Content-Type: application/json');
                echo json_encode($output);
            }

            break;
    }
}
