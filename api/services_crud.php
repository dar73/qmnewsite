<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('../includes/common_front.php');
if (isset($_POST['type'])) {
    $type = $_POST['type'];
    switch ($type) {
        case 'add':
            $name = $_POST['name'];
            $category_id = $_POST['category_id'];
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
            $sql = "INSERT INTO `services`( `name`, `category_id`, `image`, `dDate`) VALUES ('$name','$category_id','$url',NOW())";
            if (mysqli_query($conn, $sql)) {
                echo 'Data Inserted';
            } else {

                echo mysqli_error($conn);
            }

            break;

        case 'delete':
            if (isset($_POST["id"])) {
                $query = "DELETE FROM services WHERE id='" . $_POST["id"] . "' ";
                if (mysqli_query($conn, $query)) {
                    echo ' Deleted';
                } else {
                    echo 'Data Not Deleted';
                }
            }

            break;
        case 'getall':

            $columns = array('t1.id');

            $query = "SELECT t1.name as category_name,t2.* FROM `service_categories` t1 INNER JOIN services t2 ON t1.id=t2.category_id  ";

            if (isset($_POST["search"]["value"])) {
                $query .= '
            WHERE t1.id LIKE "%' . $_POST["search"]["value"] . '%" AND t1.status=1

            ';
            }

            if (isset($_POST["order"])) {
                $query .= 'ORDER BY ' . $columns[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' 
            ';
            } else {
                $query .= 'ORDER BY t1.id ASC ';
            }

            $query1 = '';

            if ($_POST["length"] != -1) {
                $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
            }

            $number_filter_row = mysqli_num_rows(mysqli_query($conn, $query));
            //echo $number_filter_row;
            $result = mysqli_query($conn, $query . $query1);

            $data = array();
            while ($row = mysqli_fetch_array($result)) {
                $sub_array = array();
                $sub_array['id'] = $row['id'];
                $sub_array['dDate'] = $row['dDate'];
                $sub_array['name'] = $row['name'];
                $sub_array['category_name'] = $row['category_name'];
                $sub_array['image'] = $row['image'];
                $data[] = $sub_array;
            }

            function get_all_data($conn)
            {
                $query = "SELECT t1.name as category_name,t2.* FROM `service_categories` t1 INNER JOIN services t2 ON t1.id=t2.category_id WHERE t1.status='1'";
                $result = mysqli_query($conn, $query);
                return mysqli_num_rows($result);
            }

            $output = array(
                "draw"    => intval($_POST["draw"]),
                "recordsTotal"  =>  get_all_data($conn),
                "recordsFiltered" => $number_filter_row,
                "data"    => $data
            );

            echo json_encode($output);
            break;
        case 'edit':
            $sql = "SELECT * FROM sales WHERE id='" . $_POST['id'] . "'";
            $result = mysqli_query($conn, $sql);
            $output = array(
                "error" => false,
                "data" => mysqli_fetch_assoc($result)
            );
            header('Content-Type: application/json');
            echo json_encode($output);
            break;
        case 'update':
            $sql = "UPDATE sales SET date='" . $_POST['date'] . "',total_sales_amt='" . $_POST['esale_amt'] . "',total_sales_count='" . $_POST['esale_count'] . "' WHERE id='" . $_POST['id'] . "'";
            $result = mysqli_query($conn, $sql);
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
                    "message" => mysqli_error($conn)
                );
                header('Content-Type: application/json');
                echo json_encode($output);
            }

            break;
    }
}
