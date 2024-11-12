<?php
include "../../config/conDB.php";
include "../../enums/enums.php";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $package_name = $_POST['package_name'];
        $package_type = $_POST['package_type'];
        $package_items = $_POST['package_items'];
        $package_prices = $_POST['package_prices'];

        $insertQuery = "INSERT INTO cater_packages (package_name, package_type, package_items, package_prices) VALUES (?,?,?,?)";
        $statement = $connect -> prepare($insertQuery);
        $statement -> bind_param("ssss", $package_name, $package_type, $package_items, $package_prices);

        if ($statement -> execute()) {
            echo json_encode($success);
        } else {
            echo json_encode($failed);
        }

    } else {
        echo json_encode($invalid);
    }
?>