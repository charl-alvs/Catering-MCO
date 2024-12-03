<?php
include"../../includes/header.php";
include"../../config/conDB.php";

// Initialize error array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Customer Name
    if (empty(trim($_POST["fullname"]))) {
        $errors["fullname"] = "Customer name is required";
    } elseif (strlen(trim($_POST["fullname"])) < 5) {
        $errors["fullname"] = "Customer name must be at least 5 characters";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", trim($_POST["fullname"]))) {
        $errors["fullname"] = "Only letters and spaces allowed";
    }

    // Validate Address
    if (empty(trim($_POST["address"]))) {
        $errors["address"] = "Address is required";
    } elseif (strlen(trim($_POST["address"])) < 5) {
        $errors["address"] = "Address must be at least 5 characters";
    }

    // Validate Landmark (optional but if provided must be at least 5 characters)
    if (!empty(trim($_POST["landmark"])) && strlen(trim($_POST["landmark"])) < 5) {
        $errors["landmark"] = "Landmark must be at least 5 characters if provided";
    }

    // Validate Contact Number
    if (empty(trim($_POST["contact"]))) {
        $errors["contact"] = "Contact number is required";
    } elseif (!preg_match("/^\d{11}$/", trim($_POST["contact"]))) {
        $errors["contact"] = "Contact number must be exactly 11 digits";
    }

    // Validate Email
    if (!isset($_POST["email"]) || empty(trim($_POST["email"]))) {
        $errors["email"] = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        if (!preg_match("/@gmail\.com$/i", $email)) {
            $errors["email"] = "Email must be a Gmail address";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format";
        }
    }

    // Validate Package
    if (empty(trim($_POST["package"]))) {
        $errors["package"] = "Package type is required";
    }

    // Validate Set Type
    if (empty(trim($_POST["setType"]))) {
        $errors["setType"] = "Set type is required";
    }

    // Validate Prizes
    if (empty(trim($_POST["prizes"]))) {
        $errors["prizes"] = "Package price is required";
    }

    // Validate Order Type
    if (empty(trim($_POST["orderType"]))) {
        $errors["orderType"] = "Order type is required";
    }

    // Validate Delivery Date
    if (empty(trim($_POST["deliveryDate"]))) {
        $errors["deliveryDate"] = "Delivery date is required";
    } else {
        $deliveryDate = strtotime($_POST["deliveryDate"]);
        $today = strtotime(date("Y-m-d"));
        if ($deliveryDate < $today) {
            $errors["deliveryDate"] = "Delivery date cannot be in the past";
        }
    }

    // Validate Delivery Time
    if (empty(trim($_POST["deliveryTime"]))) {
        $errors["deliveryTime"] = "Delivery time is required";
    }

    // If no errors, proceed with processing the form
    if (empty($errors)) {
        // Sanitize inputs
        $fullname = htmlspecialchars(trim($_POST["fullname"]));
        $address = htmlspecialchars(trim($_POST["address"]));
        $landmark = htmlspecialchars(trim($_POST["landmark"]));
        $contact = htmlspecialchars(trim($_POST["contact"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $package = htmlspecialchars(trim($_POST["package"]));
        $setType = htmlspecialchars(trim($_POST["setType"]));
        $prizes = htmlspecialchars(trim($_POST["prizes"]));
        $orderType = htmlspecialchars(trim($_POST["orderType"]));
        $deliveryDate = htmlspecialchars(trim($_POST["deliveryDate"]));
        $deliveryTime = htmlspecialchars(trim($_POST["deliveryTime"]));
        $created_at = date('Y-m-d H:i:s'); // Current timestamp

        try {
            // Check for existing order with same details
            $check_sql = "SELECT id FROM orders WHERE email = ? AND delivery_date = ? AND delivery_time = ? AND (status = 'Pending' OR status = 'Confirmed')";
            $check_stmt = $connect->prepare($check_sql);
            $check_stmt->bind_param("sss", $email, $deliveryDate, $deliveryTime);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                throw new Exception("You already have a pending or confirmed order for this date and time. Please choose a different schedule.");
            }

            // Prepare SQL statement
            $sql = "INSERT INTO orders (customer_name, address, landmark, contact_number, package_type, set_type, order_type, delivery_date, delivery_time, created_at, status, verified, email, package_price) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 0, ?, ?)";
            
            $stmt = $connect->prepare($sql);
            
            // Check if prepare was successful
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $connect->error);
            }

            $stmt->bind_param("ssssssssssss", 
                $fullname, 
                $address, 
                $landmark, 
                $contact,
                $package, 
                $setType, 
                $orderType,
                $deliveryDate, 
                $deliveryTime,
                $created_at,
                $email,
                $prizes
            );

            // Check if bind_param was successful
            if ($stmt->errno) {
                throw new Exception("Binding parameters failed: " . $stmt->error);
            }

            if ($stmt->execute()) {
                // Get the inserted ID
                $last_id = $connect->insert_id;
                
                // Verify the insertion
                $verify_sql = "SELECT * FROM orders WHERE id = ? AND status = 'Pending' AND verified = 0";
                $verify_stmt = $connect->prepare($verify_sql);
                $verify_stmt->bind_param("i", $last_id);
                $verify_stmt->execute();
                $result = $verify_stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Data was successfully inserted
                    $_POST = array();
                    
                    // Generate a random 6-digit code
                    $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    
                    // Store verification code, email, and order ID in session
                    session_start();
                    $_SESSION['verification_code'] = $verificationCode;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['order_id'] = $last_id;
                    
                    // Send verification email
                    require_once "../../config/mailer.php";
                    error_log("Attempting to send verification code to: " . $email);
                    $emailSent = sendCode($email, $verificationCode);
                    
                    if ($emailSent) {
                        error_log("Email sent successfully, redirecting to verification page");
                        $_SESSION['verification_message'] = true;
                        echo "<script>
                            alert('Order submitted successfully! Please check your email for verification.');
                            window.location.href = '../pages/verify.php';
                        </script>";
                        exit();
                    } else {
                        error_log("Failed to send verification email");
                        throw new Exception("Failed to send verification email. Please check your email address and try again.");
                    }
                } else {
                    throw new Exception("Order was not properly saved. Please try again.");
                }
            } else {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
        } catch (Exception $e) {
            $errors["database"] = "Error submitting order: " . $e->getMessage();
            // Log the error for debugging
            error_log("Order submission error: " . $e->getMessage());
        } finally {
            if (isset($verify_stmt)) {
                $verify_stmt->close();
            }
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($check_stmt)) {
                $check_stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../global/globals.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../stylesheets/checkout.css?v=<?php echo time(); ?>">
    <style>
        .error-container {
            width: 100%;
            min-height: 16px;
            margin-top: 2px;
        }
        .error, .php-error {
            color: red;
            font-size: 11px;
            display: block;
            margin-top: 2px;
        }
        .form-group {
            margin-bottom: 10px;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 2px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            background-color: #fff;
            margin-bottom: 2px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
        }
        .form-group input.invalid,
        .form-group select.invalid {
            border: 2px solid red;
            background-color: #fff;
            color: #333;
        }
        .form-group input.valid,
        .form-group select.valid {
            border: 2px solid #4CAF50;
            background-color: #fff;
            color: #333;
        }
        /* Style for disabled select elements */
        .form-group select:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <main class="page-layout img-bg">
        <section class="section-layout-resize">
            <div class="form-container">
                <h1 class="form-title">Order Form</h1>
                <div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="checkoutForm" onsubmit="return validateForm()">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="fullname">Customer Name</label>
                                    <input type="text" id="fullname" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" required>
                                    <div id="fullname-error-container" class="error-container">
                                        <?php if (isset($errors["fullname"])): ?>
                                            <span class="php-error"><?php echo $errors["fullname"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                                    <div id="address-error-container" class="error-container">
                                        <?php if (isset($errors["address"])): ?>
                                            <span class="php-error"><?php echo $errors["address"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="landmark">Landmark</label>
                                    <input type="text" id="landmark" name="landmark" value="<?php echo isset($_POST['landmark']) ? htmlspecialchars($_POST['landmark']) : ''; ?>">
                                    <div id="landmark-error-container" class="error-container">
                                        <?php if (isset($errors["landmark"])): ?>
                                            <span class="php-error"><?php echo $errors["landmark"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact Number</label>
                                    <input type="tel" id="contact" name="contact" value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" required>
                                    <div id="contact-error-container" class="error-container">
                                        <?php if (isset($errors["contact"])): ?>
                                            <span class="php-error"><?php echo $errors["contact"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                    <div id="email-error-container" class="error-container">
                                        <?php if (isset($errors["email"])): ?>
                                            <span class="php-error"><?php echo $errors["email"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="package">Package Type</label>
                                    <select id="package" name="package" required>
                                        <option value="">Select Package Type</option>
                                        <?php if(isset($_POST['package'])): ?>
                                            <option value="<?php echo htmlspecialchars($_POST['package']); ?>" selected>
                                                <?php echo htmlspecialchars($_POST['package']); ?>
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <div id="package-error-container" class="error-container">
                                        <?php if (isset($errors["package"])): ?>
                                            <span class="php-error"><?php echo $errors["package"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="setType">Set Type</label>
                                    <select id="setType" name="setType" required disabled>
                                        <option value="">Select Set Type</option>
                                        <?php if(isset($_POST['setType'])): ?>
                                            <option value="<?php echo htmlspecialchars($_POST['setType']); ?>" selected>
                                                <?php echo htmlspecialchars($_POST['setType']); ?>
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <div id="setType-error-container" class="error-container">
                                        <?php if (isset($errors["setType"])): ?>
                                            <span class="php-error"><?php echo $errors["setType"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="prizes">Package Prices</label>
                                    <select id="prizes" name="prizes" required disabled>
                                        <option value="">Select Package Prices</option>
                                        <?php if(isset($_POST['prizes'])): ?>
                                            <option value="<?php echo htmlspecialchars($_POST['prizes']); ?>" selected>
                                                <?php echo htmlspecialchars($_POST['prizes']); ?>
                                            </option>
                                        <?php endif; ?>
                                    </select>
                                    <div id="prizes-error-container" class="error-container">
                                        <?php if (isset($errors["prizes"])): ?>
                                            <span class="php-error"><?php echo $errors["prizes"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="orderType">Order Type</label>
                                    <select id="orderType" name="orderType" required>
                                        <option value="">Select Order Type</option>
                                        <option value="reservation" <?php echo (isset($_POST['orderType']) && $_POST['orderType'] === 'reservation') ? 'selected' : ''; ?>>Reservation</option>
                                    </select>
                                    <div id="orderType-error-container" class="error-container">
                                        <?php if (isset($errors["orderType"])): ?>
                                            <span class="php-error"><?php echo $errors["orderType"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">    
                                    <label for="deliveryDate">Date of Delivery</label>
                                    <input type="date" id="deliveryDate" name="deliveryDate" value="<?php echo isset($_POST['deliveryDate']) ? htmlspecialchars($_POST['deliveryDate']) : ''; ?>" required>
                                    <div id="deliveryDate-error-container" class="error-container">
                                        <?php if (isset($errors["deliveryDate"])): ?>
                                            <span class="php-error"><?php echo $errors["deliveryDate"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deliveryTime">Time of Delivery</label>
                                    <input type="time" id="deliveryTime" name="deliveryTime" value="<?php echo isset($_POST['deliveryTime']) ? htmlspecialchars($_POST['deliveryTime']) : ''; ?>" required>
                                    <div id="deliveryTime-error-container" class="error-container">
                                        <?php if (isset($errors["deliveryTime"])): ?>
                                            <span class="php-error"><?php echo $errors["deliveryTime"]; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group button-group">
                                    <button type="submit">Submit</button>
                                    <a href="offers.php" class="cancel-button">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script>
        let packagesData = [];

        function loadPackages() {
            $.ajax({
                url: '../../api/get_packages.php',
                method: 'GET',
                success: function(response) {
                    console.log('API Response:', response);
                    
                    if (response.error) {
                        console.error('API Error:', response.message);
                        return;
                    }
                    
                    // Store current selections before updating
                    const currentPackage = $('#package').val();
                    const currentSetType = $('#setType').val();
                    const currentPrice = $('#prizes').val();
                    
                    const packageSelect = $('#package');
                    packageSelect.find('option:not(:first)').remove();
                    
                    packagesData = response;
                    console.log('Stored Package Data:', packagesData);
                    
                    const uniquePackages = [...new Set(response.map(p => p.package_name))];
                    console.log('Unique Packages:', uniquePackages);
                    
                    uniquePackages.forEach(function(packageName) {
                        packageSelect.append($('<option>', {
                            value: packageName,
                            text: packageName
                        }));
                    });

                    // Restore previous selections if they exist
                    if (currentPackage) {
                        packageSelect.val(currentPackage);
                        packageSelect.trigger('change');

                        if (currentSetType) {
                            setTimeout(() => {
                                $('#setType').val(currentSetType);
                                $('#setType').trigger('change');

                                if (currentPrice) {
                                    setTimeout(() => {
                                        $('#prizes').val(currentPrice);
                                    }, 100);
                                }
                            }, 100);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                }
            });
        }

        // Handle package selection change
        $('#package').on('change', function() {
            const selectedPackage = $(this).val();
            const setTypeSelect = $('#setType');
            const prizesSelect = $('#prizes');
            
            console.log('Selected Package:', selectedPackage);
            
            if (!selectedPackage) {
                setTypeSelect.prop('disabled', true).find('option:not(:first)').remove();
                prizesSelect.prop('disabled', true).find('option:not(:first)').remove();
                return;
            }
            
            const packageTypes = packagesData
                .filter(p => p.package_name === selectedPackage)
                .map(p => p.package_type);
            
            console.log('Available Package Types:', packageTypes);
            
            setTypeSelect.prop('disabled', false)
                        .find('option:not(:first)').remove();
            
            [...new Set(packageTypes)].forEach(function(type) {
                setTypeSelect.append($('<option>', {
                    value: type,
                    text: type
                }));
            });

            prizesSelect.prop('disabled', true).find('option:not(:first)').remove();
        });

        // Handle set type selection change
        $('#setType').on('change', function() {
            const selectedPackage = $('#package').val();
            const selectedSetType = $(this).val();
            const prizesSelect = $('#prizes');

            console.log('Selected Set Type:', selectedSetType);
            
            if (!selectedSetType) {
                prizesSelect.prop('disabled', true).find('option:not(:first)').remove();
                return;
            }

            const selectedPackageData = packagesData.find(p => 
                p.package_name === selectedPackage && 
                p.package_type === selectedSetType
            );
            
            console.log('Selected Package Data:', selectedPackageData);

            prizesSelect.prop('disabled', false)
                       .find('option:not(:first)').remove();

            if (selectedPackageData && selectedPackageData.package_prices) {
                const priceOptions = selectedPackageData.package_prices.split(',').map(price => price.trim());
                console.log('Price Options:', priceOptions);
                
                priceOptions.forEach(function(priceOption) {
                    if (priceOption) {
                        prizesSelect.append($('<option>', {
                            value: priceOption,
                            text: priceOption
                        }));
                    }
                });
            }
        });

        // Load packages when page loads
        $(document).ready(function() {
            loadPackages();
        });
    </script>
    <script>
        function validateField(element) {
            const id = element.id;
            let isValid = true;
            let errorMessage = '';

            // Remove previous validation classes
            element.classList.remove('invalid', 'valid');
            
            // Clear both JavaScript and PHP error messages
            const errorContainer = document.getElementById(id + '-error-container');
            if (errorContainer) {
                errorContainer.innerHTML = '';
            }

            if (!element.value.trim() && element.required) {
                isValid = false;
                errorMessage = 'This field is required';
            } else {
                switch(id) {
                    case 'fullname':
                        if (element.value.trim().length < 5) {
                            isValid = false;
                            errorMessage = 'Name must be at least 5 characters';
                        } else if (!/^[A-Za-z\s]+$/.test(element.value.trim())) {
                            isValid = false;
                            errorMessage = 'Only letters and spaces allowed';
                        }
                        break;
                    case 'address':
                        if (element.value.trim().length < 5) {
                            isValid = false;
                            errorMessage = 'Address must be at least 5 characters';
                        }
                        break;
                    case 'landmark':
                        if (element.value.trim() && element.value.trim().length < 5) {
                            isValid = false;
                            errorMessage = 'Landmark must be at least 5 characters if provided';
                        }
                        break;
                    case 'contact':
                        if (!/^\d{11}$/.test(element.value.trim())) {
                            isValid = false;
                            errorMessage = 'Contact number must be exactly 11 digits';
                        }
                        break;
                    case 'email':
                        if (!element.value.trim().toLowerCase().endsWith('@gmail.com')) {
                            isValid = false;
                            errorMessage = 'Email must be a Gmail address';
                        }
                        break;
                    case 'package':
                    case 'setType':
                    case 'prizes':
                    case 'orderType':
                        if (element.value === '') {
                            isValid = false;
                            errorMessage = 'Please select an option';
                        }
                        break;
                    case 'deliveryDate':
                        if (element.value) {
                            const selectedDate = new Date(element.value);
                            const today = new Date();
                            today.setHours(0, 0, 0, 0);
                            if (selectedDate < today) {
                                isValid = false;
                                errorMessage = 'Delivery date cannot be in the past';
                            }
                        }
                        break;
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
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                // Create error container for each input if it doesn't exist
                if (!document.getElementById(input.id + '-error-container')) {
                    const errorContainer = document.createElement('div');
                    errorContainer.id = input.id + '-error-container';
                    errorContainer.className = 'error-container';
                    input.parentNode.insertBefore(errorContainer, input.nextSibling);
                }
                
                input.addEventListener('input', () => validateField(input));
                input.addEventListener('blur', () => validateField(input));
                input.addEventListener('change', () => validateField(input));
            });
        });

        function validateForm() {
            let isValid = true;
            const inputs = document.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                if (!input.disabled && !validateField(input)) {
                    isValid = false;
                }
            });

            return isValid;
        }
    </script>
</body>
</html>