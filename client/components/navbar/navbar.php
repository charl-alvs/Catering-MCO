<style>
    .navbar {
        padding: 1% 5%;
        display: flex;
        justify-content: space-between;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .logo {
        font-size: 20px;
        color: #ffffff;
        display: flex;
        align-items: center;
    }

    .nav-links {
        list-style: none;
        display: flex;
        gap: 50px;
        color: #ffffff;
        align-items: center;
    }

    .nav-items {
        text-decoration: none;
        color: #ffffff;
    }

    .nav-items:hover {
        color: #C29C6D;
    }
</style>

<nav class="navbar">
    <h1 class="logo">Catering</h1>
    <ul class="nav-links">
        <li><a class="nav-items" href="#About">About</a></li>
        <li><a class="nav-items" href="#offersMenu">Offers & Menu</a></li>
        <li><a class="nav-items" href="#contact">Contact</a></li>
    </ul>
    <a class="default-btn" href="client/pages/offers.php" style="font-size: 13.5px; text-decoration: none;">View Offers</a>
</nav>