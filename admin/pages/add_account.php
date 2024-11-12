<?php
include "../../includes/header.php";
?>
<script type="module" src="../javascript/admin.js?v=<?php echo time(); ?>"></script>
<link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../stylesheets/add_account.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="page-layout">
        <?php include "../components/navbar/navbar.php"; ?>
        <section class="p-tb card-container">
            <div class="admin-list-card">
                <h1>Admin List Registered</h1>
            </div>
            <div class="add-admin-card">
                <h1 class="text-center">Add Admin Form</h1>
                <form id="add-admin-form">
                    <div style="margin-top: 10px;">
                        <input class="form-control" type="text" name="firstname" id="firstname" placeholder="firstname">
                        <input class="form-control" type="text" name="lastname" id="lastname" placeholder="lastname">
                        <input class="form-control" type="text" name="username" id="username" placeholder="username">
                        <input class="form-control" type="password" name="password" id="password" placeholder="password">
                        <input class="form-control" type="password" name="confirm-password" id="confirm-password" placeholder="confirm password">   
                    </div>
                    <button type="submit" class="form-btn">Add Account</button>
                    <p id="alertMsg" style="text-align: center; margin-top: 15px;"></p>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
