<?php
include "../../includes/header.php";
?>
<link rel="stylesheet" type="text/css" href="../../global/globals.css?v=<?php echo time(); ?>">
<link rel="stylesheet" type="text/css" href="../stylesheets/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <main class="page-layout bg-dashboard">
        <?php include "../components/navbar/navbar.php"; ?>
        <h1 class="header">Customer Orders & Informations</h1>
        <div class="table-container">
            <div class="wrapper">
                <table>
                    <thead>
                        <th>Action</th>
                        <th>Customer Name</th>
                        <th>Address</th>
                        <th>Contact No.</th>
                        <th>Package Type</th>
                        <th>Set Type</th>
                        <th>Order Type</th>
                        <th>Status</th>
                        <th>Date of Delivery</th>
                        <th>Time</th>
                    </thead>
                    <tbody>
                        <td>
                            <div class="actions">
                                <img style="cursor: pointer;" src="../../assets/icons/admin/edit.svg" alt="image">
                                <img style="cursor: pointer;" src="../../assets/icons/admin/trash.svg" alt="image">
                            </div>
                        </td>
                        <td>John Doe</td>
                        <td>Calbayog City</td>
                        <td>09287564737</td>
                        <td>Birthday Package</td>
                        <td>Set A</td>
                        <td>Reservation</td>
                        <td>Pending</td>
                        <td>May 26, 2025</td>
                        <td>8:00 AM</td>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>