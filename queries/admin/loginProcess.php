<?php
include "../../config/conDB.php";
include "../../enums/enums.php";
session_start();

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        

    } else {
        echo json_encode($invalid);
    }
?>