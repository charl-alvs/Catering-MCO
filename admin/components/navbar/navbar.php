<style>
    .navbar {
        padding: 1% 5%;
        display: flex;
        justify-content: space-between;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .logo {
        font-size: 20px;
        color: #ffffff;
        display: flex;
        align-items: center;
    }

    .nav-links {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 50px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        color: gray;
        text-decoration: none;
    }

    .nav-item:hover {
        color: #ffffff;
        transition: .3s ease;
    }

    .nav-item:not(:hover) {
        transition: .3s ease;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 28px;
        right: 0px;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .dropdown-item {
        text-align: center;
        font-size: 15px;
    }
</style>

<nav class="navbar">
    <h1 class="logo">Admin Dashboard</h1>
    <ul class="nav-links">
        <li><a class="nav-item" href="dashboard.php">Orders</a></li>
        <li><a class="nav-item" href="offers.php">Offers & Menu</a></li>
        <li><a class="nav-item" href="add_package.php">Add Package</a></li>
    </ul>
    <div class="dropdown" style="display: flex; align-items: center;">
        <span class="pointer" style="color: #ffffff;">Admin</>
        <div class="dropdown-content">
            <a class="dropdown-item" href="add_account.php">Add Account</a>
            <a class="dropdown-item" href="login.php">Logout</a>
        </div>
    </div>
</nav>