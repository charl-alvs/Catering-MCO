<?php
include "../config/conDB.php";
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "SELECT DISTINCT package_name, package_type, package_prices FROM cater_packages ORDER BY package_name";
$result = $connect->query($query);

if (!$result) {
    // Log the SQL error
    echo json_encode([
        'error' => true,
        'message' => 'Database error: ' . $connect->error,
        'query' => $query
    ]);
    exit;
}

$packages = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $packages[] = array(
            'package_name' => $row['package_name'],
            'package_type' => $row['package_type'],
            'package_prices' => $row['package_prices']
        );
    }
}

echo json_encode($packages);
?>
