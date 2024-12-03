<?php
session_start();
require_once "../../config/conDB.php";

// Check if tracking number is set in the URL
if (!isset($_GET['tracking'])) {
    header("Location: track_order.php");
    exit();
}

$trackingNumber = trim($_GET['tracking']);
$orderDetails = null;
$error = null;

// Fetch order details
try {
    $sql = "SELECT id, address, landmark, package_type, set_type, order_type, 
            delivery_date, delivery_time, status, package_price, tracking_number 
            FROM orders WHERE tracking_number = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $trackingNumber);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $orderDetails = $result->fetch_assoc();
        } else {
            $error = "No order found with this tracking number.";
        }
    } else {
        $error = "Error occurred while fetching order details.";
    }
} catch (Exception $e) {
    $error = "An error occurred while processing your request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - Catering Service</title>
    <link rel="icon" type="image/png" href="/cateringMCO/assets/icons/client/chef-platter.png">
    <link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
    <style>
        .img-bg {
            background-image: url('../../assets/images/background1.png');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-layout-resize {
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .status-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            color: white;
        }

        .status-title {
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .order-info {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .order-info h2 {
            color: #C29C6D;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-label {
            font-weight: bold;
            color: #C29C6D;
        }

        .status-timeline {
            margin: 50px 0;
            display: flex;
            flex-direction: row-reverse;
            justify-content: space-between;
            padding: 40px 20px;
            position: relative;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            height: 2px;
            background-color: #e0e0e0;
            width: 100%;
            top: 40px;
            left: 0;
        }

        .timeline-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            min-width: 100px;
            z-index: 1;
        }

        .timeline-dot {
            width: 16px;
            height: 16px;
            background-color: #e0e0e0;
            border-radius: 50%;
            z-index: 1;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #e0e0e0;
            margin-bottom: 30px;
        }

        .timeline-item.active .timeline-dot {
            background-color: #4CAF50;
            box-shadow: 0 0 0 2px #4CAF50;
        }

        .timeline-content {
            text-align: center;
            padding-top: 10px;
            width: 100%;
        }

        .timeline-text {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-size: 0.9em;
            white-space: nowrap;
        }

        .timeline-date {
            font-size: 0.8em;
            color: #666;
        }

        .timeline-item.active .timeline-text {
            color: #4CAF50;
        }

        .timeline-item.active ~ .timeline-item .timeline-dot {
            background-color: #e0e0e0;
        }

        @media (max-width: 600px) {
            .status-timeline {
                padding: 40px 10px;
            }

            .timeline-text {
                font-size: 0.8em;
            }

            .timeline-date {
                font-size: 0.7em;
            }
        }

        .error-message {
            color: #ff6b6b;
            text-align: center;
            padding: 20px;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        .back-button {
            background-color: transparent;
            border: 2px solid #C29C6D;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #C29C6D;
        }
    </style>
</head>
<body>
    <main class="page-layout img-bg">
        <section class="section-layout-resize">
            <div class="status-container">
                <?php if ($error): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($orderDetails): ?>
                    <h1 class="status-title">Order Status</h1>
                    
                    <div class="order-info">
                        <h2>Order Details</h2>
                        <div class="info-row">
                            <span class="info-label">Order ID:</span>
                            <span><?php echo htmlspecialchars($orderDetails['id']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tracking Number:</span>
                            <span><?php echo htmlspecialchars($orderDetails['tracking_number']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span><?php echo htmlspecialchars($orderDetails['status']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Package Type:</span>
                            <span><?php echo htmlspecialchars($orderDetails['package_type']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Set Type:</span>
                            <span><?php echo htmlspecialchars($orderDetails['set_type']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Order Type:</span>
                            <span><?php echo htmlspecialchars($orderDetails['order_type']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Delivery Date:</span>
                            <span><?php echo date('F j, Y', strtotime($orderDetails['delivery_date'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Delivery Time:</span>
                            <span><?php echo date('g:i A', strtotime($orderDetails['delivery_time'])); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span><?php echo htmlspecialchars($orderDetails['address']); ?></span>
                        </div>
                        <?php if (!empty($orderDetails['landmark'])): ?>
                        <div class="info-row">
                            <span class="info-label">Landmark:</span>
                            <span><?php echo htmlspecialchars($orderDetails['landmark']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="info-label">Package Price:</span>
                            <span><?php echo htmlspecialchars($orderDetails['package_price']); ?></span>
                        </div>
                    </div>

                    <div class="status-timeline">
                        <div class="timeline-item <?php echo $orderDetails['status'] === 'Completed' ? 'active' : ''; ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-text">Completed</div>
                                <div class="timeline-date"><?php echo $orderDetails['status'] === 'Completed' ? date('g:i A', strtotime($orderDetails['delivery_date'] . ' ' . $orderDetails['delivery_time'])) : ''; ?></div>
                            </div>
                        </div>

                        <div class="timeline-item <?php echo $orderDetails['status'] === 'On The Way' ? 'active' : ''; ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-text">On The Way</div>
                                <div class="timeline-date"><?php echo $orderDetails['status'] === 'On The Way' ? date('g:i A', strtotime($orderDetails['delivery_date'] . ' ' . $orderDetails['delivery_time'])) : ''; ?></div>
                            </div>
                        </div>

                        <div class="timeline-item <?php echo $orderDetails['status'] === 'Cooking' ? 'active' : ''; ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-text">Cooking</div>
                                <div class="timeline-date"><?php echo $orderDetails['status'] === 'Cooking' ? date('g:i A', strtotime($orderDetails['delivery_date'] . ' ' . $orderDetails['delivery_time'])) : ''; ?></div>
                            </div>
                        </div>

                        <div class="timeline-item <?php echo $orderDetails['status'] === 'Pending' ? 'active' : ''; ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-text">Pending</div>
                                <div class="timeline-date"><?php echo date('g:i A', strtotime($orderDetails['delivery_date'] . ' ' . $orderDetails['delivery_time'])); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="button-group">
                    <a href="track_order.php" class="back-button">Back to Track Order</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
