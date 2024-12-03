<?php
session_start();
require_once "../../config/conDB.php";
require_once "../../config/mailer.php";

// Handle AJAX resend request
if (isset($_POST['action']) && $_POST['action'] === 'resend') {
    header('Content-Type: application/json');
    try {
        error_log("=== Resend Code Request ===");
        error_log("Session variables before resend: " . print_r($_SESSION, true));
        
        if (!isset($_SESSION['order_id']) || !isset($_SESSION['user_email'])) {
            error_log("Missing session variables for resend");
            throw new Exception("Session expired. Please try ordering again.");
        }

        // Generate new verification code
        $newVerificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $_SESSION['verification_code'] = $newVerificationCode;
        
        error_log("Attempting to resend code to: " . $_SESSION['user_email']);
        
        // Send new verification code
        if (sendCode($_SESSION['user_email'], $newVerificationCode)) {
            error_log("Successfully sent new code");
            echo json_encode(['success' => true, 'message' => 'New verification code sent successfully']);
        } else {
            error_log("Failed to send code via mailer");
            throw new Exception("Failed to send verification code. Please try again.");
        }
        exit();
    } catch (Exception $e) {
        error_log("Resend Code Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
}

// Handle form submission
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verificationCode'])) {
    $verificationCode = trim($_POST['verificationCode']);
    
    // Validate verification code
    if (!isset($_SESSION['verification_code']) || !isset($_SESSION['order_id']) || !isset($_SESSION['user_email'])) {
        $errors["verificationCode"] = "Verification session expired. Please try again.";
    } else if ($verificationCode !== $_SESSION['verification_code']) {
        $errors["verificationCode"] = "Invalid verification code. Please try again.";
    } else {
        // Update query using id instead of order_id
        $sql = "UPDATE orders SET verified = 1, tracking_number = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("si", $verificationCode, $_SESSION['order_id']);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Clear verification sessions
            unset($_SESSION['verification_code']);
            unset($_SESSION['order_id']);
            unset($_SESSION['user_email']);
            
            echo "<script>
                alert('Order verified successfully! Your tracking number is: " . $verificationCode . "');
                window.location.href = '../../index.php';
            </script>";
            exit();
        } else {
            $errors["verificationCode"] = "Failed to update order. Please ensure your email matches the order.";
        }
    }
}

// Only include header and output HTML for non-AJAX requests
if (!isset($_POST['action'])) {
    include "../../includes/header.php";
    
    // Debug session variables
    error_log("=== Starting Verification Process ===");
    error_log("Session ID: " . session_id());
    error_log("Session variables: " . print_r($_SESSION, true));
    
    // Debug database connection
    error_log("Database connection status: " . ($connect ? "Connected" : "Not connected"));
    if (!$connect) {
        error_log("Database connection error: " . mysqli_connect_error());
    }
}

// Check for verification message
if (isset($_SESSION['verification_message'])) {
    echo "<script>
        alert('Please check your email to verify');
    </script>";
    // Remove the session variable
    unset($_SESSION['verification_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Catering Service</title>
    <link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../stylesheets/checkout.css?v=<?php echo time(); ?>">
    <style>

        .section-layout-resize {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-container {
            background: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .form-title {
            text-align: center;
            color: white;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .error-container {
            width: 100%;
            min-height: 16px;
            margin-top: 4px;
        }

        .error, .php-error {
            color: #ff6b6b;
            font-size: 12px;
            display: block;
            margin-top: 4px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: white;
            font-weight: 500;
        }

        .verification-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 24px;
            letter-spacing: 12px;
            text-align: center;
            font-family: monospace;
            transition: all 0.3s ease;
            background: #fff !important;
            color: #000 !important;
        }

        .verification-input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }

        .verification-input.invalid {
            border-color: #dc3545;
            background-color: #fff;
        }

        .verification-input.valid {
            border-color: #4CAF50;
            background-color: #fff;
        }

        .verification-input::placeholder {
            letter-spacing: 2px;
            color: #999 !important;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .button-group button,
        .button-group .cancel-button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .button-group button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        .button-group button[type="submit"]:hover {
            background-color: #45a049;
        }

        .button-group .cancel-button {
            background-color: #dc3545;
            color: white;
        }

        .button-group .cancel-button:hover {
            background-color: #c82333;
        }

        .info-text {
            text-align: center;
            margin-bottom: 25px;
            color: white;
            font-size: 15px;
            line-height: 1.5;
        }

        .resend-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .resend-link a {
            color: #7fff7f;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .resend-link a:hover {
            color: #9fff9f;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-layout-resize {
                padding: 0 15px;
            }

            .form-container {
                padding: 20px;
            }

            .verification-input {
                font-size: 20px;
                letter-spacing: 8px;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group button,
            .button-group .cancel-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="page-layout img-bg">
        <section class="section-layout-resize">
            <div class="form-container">
                <h1 class="form-title">Email Verification</h1>
                <?php if (isset($errors["order"])): ?>
                    <div class="error-container">
                        <span class="php-error"><?php echo $errors["order"]; ?></span>
                    </div>
                <?php else: ?>
                    <p class="info-text">
                        Please enter the 6-digit verification code sent to your email address.<br>
                        <small>The code will expire in 10 minutes</small>
                    </p>
                    <form method="POST" id="verifyForm" onsubmit="return validateForm()">
                        <input type="hidden" name="order_id" value="<?php echo isset($_SESSION['order_id']) ? $_SESSION['order_id'] : ''; ?>">
                        <div class="form-group">
                            <label for="verificationCode">Verification Code</label>
                            <input type="text" id="verificationCode" name="verificationCode" class="verification-input" maxlength="6" pattern="\d{6}" placeholder="123456" autocomplete="off" value="<?php echo isset($_POST['verificationCode']) ? htmlspecialchars($_POST['verificationCode']) : ''; ?>" required>
                            <div id="verificationCode-error-container" class="error-container">
                                <?php if (isset($errors["verificationCode"])): ?>
                                    <span class="php-error"><?php echo $errors["verificationCode"]; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="button-group">
                            <button type="submit">Verify Email</button>
                            <a href="offers.php" class="cancel-button">Cancel</a>
                        </div>
                    </form>
                    <div class="resend-link">
                        <a href="javascript:void(0)" onclick="resendCode()">Didn't receive the code? Send again</a>
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
            } else if (id === 'verificationCode') {
                if (!/^\d{6}$/.test(element.value.trim())) {
                    isValid = false;
                    errorMessage = 'Please enter a valid 6-digit code';
                }
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
            const verificationInput = document.getElementById('verificationCode');
            
            // Only allow numbers
            verificationInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^\d]/g, '');
                validateField(this);
            });

            verificationInput.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateForm() {
            const verificationInput = document.getElementById('verificationCode');
            return validateField(verificationInput);
        }

        function resendCode() {
            // Disable the resend link to prevent multiple clicks
            const resendLink = document.querySelector('.resend-link a');
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';

            fetch('verify.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=resend',
                credentials: 'same-origin' // Include cookies/session
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('A new verification code has been sent to your email.');
                } else {
                    throw new Error(data.message || 'Failed to send verification code');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to resend verification code: ' + error.message);
            })
            .finally(() => {
                // Re-enable the resend link after 30 seconds
                setTimeout(() => {
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity = '1';
                }, 30000);
            });
        }
    </script>
</body>
</html>