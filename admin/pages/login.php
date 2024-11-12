<?php
include "../../includes/header.php";
?>
<script type="module" src="../javascript/admin.js?v=<?php echo time(); ?>"></script>
<link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../stylesheets/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="page-layout center-element login-bg">
        <div class="login-card">
            <h1 class="text-center pointer text-light">Catering Admin</h1>
            <form id="login-submit">
                <div class="form-group-login">
                    <input class="form-control" id="username" name="username" type="text" placeholder="username">
                    <input class="form-control" id="password" name="password" type="password" placeholder="password">
                    <button type="submit" class="login-btn">Login</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>