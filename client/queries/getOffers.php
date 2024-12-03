<?php
function displayPackages($packageName) {
    include "../../config/conDB.php";
    
    $query = "SELECT * FROM cater_packages WHERE package_name = ? ORDER BY package_type ASC";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $packageName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items = explode(",", $row['package_items']);
            $prices = explode(",", $row['package_prices']);
            ?>
            <div class="offers-card">
                <h1 class="text-center">Set <?php echo htmlspecialchars($row['package_type']); ?></h1>
                <p class="text-center">
                    <?php
                    // Display items
                    foreach ($items as $item) {
                        echo htmlspecialchars(trim($item)) . "<br>";
                    }
                    echo "<br>";
                    // Display prices
                    foreach ($prices as $price) {
                        echo htmlspecialchars(trim($price)) . "<br>";
                    }
                    ?>
                </p>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-center'>No packages found.</p>";
    }
    $stmt->close();
}
?>
