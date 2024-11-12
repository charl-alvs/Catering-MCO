<?php
include "../../includes/header.php";
?>
<link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../stylesheets/offers.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="page-layout bg-offers">
        <?php include"../components/navbar/navbar.php"; ?>
        <section class="section-layout-resize padding-x" style="margin-bottom: 50px;">
            <h1 class="section-header text-dark">Offers & Menu</h1>
            <h1 class="text-dark">Birthday Packages</h1>
            <div class="offers-gallery">
                <?php
                    include "../../queries/admin/getPackage.php";
                    displayPackage('Birthday Package');
                ?>
            </div>
        </section>
    </main>
</body>
</html>