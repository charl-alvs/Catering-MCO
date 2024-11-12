<?php
include "../../includes/header.php";
include "../../config/conDB.php";
include "../helpers/helpers.php";
?>
<link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../stylesheets/add_package.css?v=<?php echo time(); ?>">
<script type="module" src="../javascript/admin.js?v=<?php echo time(); ?>"></script>  
</head>
<body>
    <main class="page-layout bg-light">
        <?php include "../components/navbar/navbar.php"; ?>
        <section class="section-layout-resize section-container">
            <h1 class="section-header-h1">Add Package</h1>
            <div class="form-container">
                <form id="add_package_submit">
                    <div class="form-card">
                        <div class="form-group">
                            <select class="form-select" name="package_name" id="package_name">
                                <option value="">Select Package</option>
                                <option value="Birthday Package">Birthday Package</option>
                                <option value="Company Package">Company Package</option>
                                <option value="Wedding Package">Wedding Package</option>
                                <option value="Anniversary Package">Anniversary Package</option>
                            </select>
                            <select class="form-select" name="package_type" id="package_type">
                                <option value="">Select type</option>
                                <?php 
                                    foreach ($set_type as $type) {
                                        ?>
                                        <option value=<?php echo $type ?>><?php echo "Set $type" ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <textarea 
                            class="w-100 textarea-default" 
                            style="margin-top: 8px;" 
                            name="package_items" 
                            id="package_items" 
                            rows="5" 
                            placeholder="example format: Adobo, Sinigang, Baked Cheesy Banana"
                        ></textarea>
                        <textarea 
                            class="w-100 textarea-default" 
                            style="margin-top: 8px;" 
                            name="package_prices" 
                            id="package_prices" 
                            rows="5" 
                            placeholder="example format: 10 PAX - 2750, 15 PAX - 3750, 30 PAX - 4750"
                        ></textarea>
                    </div>
                    <div class="center-element" style="margin-top: 10px; gap: 10px;">
                        <button type="submit" class="form-btn" style="background-color: #29bf12; color: white;">Submit</button>
                        <a href="dashboard.php" class="form-btn text-decoration-none" style="background-color: crimson; color: white;">Cancel</a>
                    </div>
                </form>
            </div>
            <h3 id="alertMsg" class="text-center" style="margin-top: 20px; color: green;"></h3>
        </section>
    </main>
</body>
</html>