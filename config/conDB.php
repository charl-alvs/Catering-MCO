<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "catering_db";
    $port = 3308;

    // connect to phpMyAdmin
    $connect = mysqli_connect($servername, $username, $password, $dbname, $port);

    // check connection to database
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>