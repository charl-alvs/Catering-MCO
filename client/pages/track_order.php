<?php
session_start();
require_once "../../config/conDB.php";

// Handle form submission
$errors = [];
$orderDetails = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trackingNumber'])) {
    $trackingNumber = trim($_POST['trackingNumber']);
    
    // Validate tracking number
    if (empty($trackingNumber)) {
        $errors["trackingNumber"] = "Please enter a tracking number.";
    } else {
        // Query the database for the order
        $sql = "SELECT * FROM orders WHERE tracking_number = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $trackingNumber);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Redirect to order status page with tracking number
                header("Location: order_status.php?tracking=" . urlencode($trackingNumber));
                exit();
            } else {
                $errors["trackingNumber"] = "No order found with this tracking number.";
            }
        } else {
            $errors["trackingNumber"] = "Error occurred while searching for the order.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Catering Service</title>
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

        .form-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
        }

        .form-title {
            color: #ffffff;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .info-text {
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            margin-bottom: 5px;
        }

        .tracking-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #C29C6D;
            border-radius: 5px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        button {
            background-color: #C29C6D;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #A27B4D;
        }

        .cancel-button {
            background-color: transparent;
            border: 2px solid #C29C6D;
            color: #ffffff;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .cancel-button:hover {
            background-color: #C29C6D;
        }

        .error-container {
            color: #ff6b6b;
            margin-top: 5px;
            font-size: 14px;
        }

        .order-details {
            margin-top: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
        }

        .order-details h2 {
            color: #C29C6D;
            margin-bottom: 15px;
        }

        .order-details p {
            margin: 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <main class="page-layout img-bg">
        <section class="section-layout-resize">
            <div class="form-container">
                <h1 class="form-title">Track Your Order</h1>
                <p class="info-text">
                    Enter your tracking number to view your order details.
                </p>

                <form method="POST" id="trackForm" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="trackingNumber">Tracking Number</label>
                        <input 
                            type="text" 
                            id="trackingNumber" 
                            name="trackingNumber" 
                            class="tracking-input"
                            placeholder="Enter your tracking number"
                            value="<?php echo isset($_POST['trackingNumber']) ? htmlspecialchars($_POST['trackingNumber']) : ''; ?>"
                            required
                        >
                        <div id="trackingNumber-error-container" class="error-container">
                            <?php if (isset($errors["trackingNumber"])): ?>
                                <span class="php-error"><?php echo $errors["trackingNumber"]; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit">Track Order</button>
                        <a href="../../index.php" class="cancel-button">Cancel</a>
                    </div>
                </form>

                <?php if ($orderDetails): ?>
                <div class="order-details">
                    <h2>Order Details</h2>
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($orderDetails['id']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($orderDetails['status']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($orderDetails['order_date']); ?></p>
                    <!-- Add more order details as needed -->
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        function validateField(element) {
            const id = element.id;
            let isValid = true;
            let errorMessage = '';

            // Remove previous validation classes
            element.classList.remove('invalid', 'valid');
            
            // Clear error messages
            const errorContainer = document.getElementById(id + '-error-container');
            if (errorContainer) {
                errorContainer.innerHTML = '';
            }

            if (!element.value.trim()) {
                isValid = false;
                errorMessage = 'This field is required';
            }

            if (!isValid && errorMessage) {
                element.classList.add('invalid');
                if (errorContainer) {
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'error';
                    errorSpan.textContent = errorMessage;
                    errorContainer.appendChild(errorSpan);
                }
            } else {
                element.classList.add('valid');
            }
            return isValid;
        }

        // Add event listeners for real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const trackingInput = document.getElementById('trackingNumber');
            
            trackingInput.addEventListener('input', function() {
                validateField(this);
            });

            trackingInput.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateForm() {
            const trackingInput = document.getElementById('trackingNumber');
            return validateField(trackingInput);
        }
    </script>
</body>
</html>
