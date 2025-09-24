<?php
include '../includes/common.php';
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

                echo sql_error();
            }

            break;

        case 'delete':
            if (isset($_POST["id"])) {
                $query = "DELETE FROM service_categories WHERE id='" . $_POST["id"] . "' ";
                if (sql_query($query)) {
                    echo ' Deleted';
                } else {
                    echo 'Data Not Deleted';
                }
            }

            break;
        case 'getall':

            $columns = array('id');

            $query = "SELECT * FROM `service_categories`";

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
                $sub_array['name'] = $row['name'];
                $sub_array['status'] = $row['status'];
                $sub_array['img'] = $row['img'];
                $data[] = $sub_array;
            }

            function get_all_data()
            {
                $query = "SELECT * FROM `service_categories` ";
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
