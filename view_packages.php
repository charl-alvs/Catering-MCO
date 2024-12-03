<?php
include "./config/conDB.php";

$query = "SELECT * FROM cater_packages";
$result = mysqli_query($connect, $query);

if ($result) {
    echo "<h2>Cater Packages Data</h2>";
    echo "<table border='1'>
    <tr>
        <th>Package Name</th>
        <th>Package Type</th>
        <th>Package Items</th>
        <th>Package Prices</th>
    </tr>";

    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['package_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['package_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['package_items']) . "</td>";
        echo "<td>" . htmlspecialchars($row['package_prices']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($connect);
}

mysqli_close($connect);
?>
