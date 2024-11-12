<?php
include"./includes/header.php";
?>
<link rel="stylesheet" href="global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="index.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="page-layout">
        <section class="first-section">
            <?php include"./client/components/navbar/navbar.php"; ?> <!-- include navbar -->
            <h1 class="text-center header" style="margin-top: 7%;">Welcome <br> to <br> <span style="color: C29C6D;">Alita's Catering</span></h1>
        </section>
        <section id="About" class="second-section center-element">
            <div class="row">
                <div class="align-middle">
                    <span>
                        <h1 class="section-about-header">They provide the most valuable pleasure</h1>
                        <p>
                            It is important to take care of the patient, to <br>
                            be followed by the patient, but it will happen at such a time <br>
                            that there is a lot of work and pain.
                        </p>
                    </span>
                </div>
                <div>
                    <img class="card-img" src="./assets/images/card-image1.png" alt="image">
                </div>
            </div>
        </section>
        <section id="offersMenu" class="third-section">
            <h1 class="section-menu-header">Offers & Menu</h1>
            <h1 class="semiHeader">Events</h1>
            <div class="gallery">
                <div class="card-gallery">
                    <img class="img" src="./assets/images/birthday-parties.png" alt="image">
                    <h3 class="text-center">Birthday Package</h3>
                </div>
                <div class="card-gallery">
                    <img class="img" src="./assets/images/office-events.png" alt="image">
                    <h3 class="text-center">Company Package</h3>
                </div>
                <div class="card-gallery">
                    <img class="img" src="./assets/images/wedding-events.png" alt="image">
                    <h3 class="text-center">Wedding Package</h3>
                </div>
                <div class="card-gallery">
                    <img class="img" src="./assets/images/anniverary_catering.png" alt="image">
                    <h3 class="text-center">Anniversary Package</h3>
                </div>
            </div>
        </section>
        <section id="contact" class="fourth-section">
            <h1 class="contact-header">Get in Touch</h1>
            <p class="mail-link">
                <a class="mail-link" href="mailto:catering.reservation@gmail.com">catering.reservation@gmail.com</a> <br>
                +63 939 680 3798 <br>
                We accept order around Tinambacan, Sta. Margarita, & Calbayog City only.
            </p>
        </section>
    </main>
</body>
</html>