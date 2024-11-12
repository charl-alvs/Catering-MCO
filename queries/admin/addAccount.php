<?php
include "../../config/conDB.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $firstname = $_POST['firstname'];

        $data = [
            'firstname' => $firstname
        ];

        echo "<h1>Charl Alvarado</h1>";

        echo json_encode($data);

    }
?>