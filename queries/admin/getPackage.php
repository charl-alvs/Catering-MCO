<?php
    function displayPackage($packageName): void {
        include "../../config/conDB.php";
        $get_Query = "SELECT * FROM cater_packages WHERE package_name = ?";
        $get_statement = $connect -> prepare($get_Query);
        $get_statement -> bind_param("s", $packageName);
        $get_statement -> execute();
        $result = $get_statement -> get_result();
        if ($result -> num_rows > 0) {
            while ($rows = $result -> fetch_assoc()) {
                ?>
                    <div class="offers-card">
                        <h1 class="text-center">Set <?php echo $rows['package_type'] ?></h1>
                        <p class="text-center">
                            <?php
                                $items = explode(",", $rows['package_items']);
                                foreach ($items as $each) {
                                    echo "$each <br>";
                                }
                            ?>
                            <br>
                            <span style="font-weight: bold; font-size: 18px;">Prices</span>
                            <br>
                            <?php
                                $prices = explode(",", $rows['package_prices']);
                                foreach ($prices as $price) {
                                    echo "$price <br>";
                                }
                            ?>
                        </p>
                    </div>
                <?php
            }
        }

    }
?>