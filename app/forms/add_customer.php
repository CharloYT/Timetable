<?php
/**
 * Business Management Database System
 * Add Customer Form
 *
 * This page provides a form for adding new customers to the system.
 * It includes validation, sanitization, and database insertion functionality.
 */

require_once '../includes/functions.php';

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $city = sanitizeInput($_POST['city'] ?? '');
    $state = sanitizeInput($_POST['state'] ?? '');
    $zipCode = sanitizeInput($_POST['zip_code'] ?? '');

    // Validation
    if (empty($firstName)) {
        $errors[] = 'First name is required';
    } elseif (strlen($firstName) < 2) {
        $errors[] = 'First name must be at least 2 characters long';
    }

    if (empty($lastName)) {
        $errors[] = 'Last name is required';
    } elseif (strlen($lastName) < 2) {
        $errors[] = 'Last name must be at least 2 characters long';
    }

    if (empty($email)) {
        $errors[] = 'Email address is required';
    } elseif (!validateEmail($email)) {
        $errors[] = 'Please enter a valid email address';
    }

    if (!empty($phone) && !validatePhone($phone)) {
        $errors[] = 'Please enter a valid phone number';
    }

    if (!empty($zipCode) && !preg_match('/^\d{5}(-\d{4})?$/', $zipCode)) {
        $errors[] = 'Please enter a valid ZIP code';
    }

    // Check for duplicate email
    if (empty($errors)) {
        $existingCustomer = executeQuery("SELECT customer_id FROM customers WHERE email = ?", [$email]);
        if (!empty($existingCustomer)) {
            $errors[] = 'A customer with this email address already exists';
        }
    }

    // Process valid data
    if (empty($errors)) {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, email, phone, address, city, state, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $address, $city, $state, $zipCode);

        if ($stmt->execute()) {
            $customerId = $db->getLastInsertId();
            logActivity('customer_added', "New customer added: $firstName $lastName ($email)", getCurrentUserId());
            $message = displayAlert('Customer added successfully! Customer ID: ' . $customerId);

            // Clear form data on success
            $firstName = $lastName = $email = $phone = $address = $city = $state = $zipCode = '';
        } else {
            $message = displayAlert('Error adding customer: ' . $conn->error, 'error');
            logActivity('customer_add_failed', "Failed to add customer: $email", getCurrentUserId());
        }

        $db->close();
    }
}

// Get US states for dropdown
$usStates = [
    'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
    'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
    'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
    'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
    'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
    'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
    'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
    'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
    'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
    'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
    'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
    'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
    'WI' => 'Wisconsin', 'WY' => 'Wyoming'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Customer - Business Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-section {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .required { color: #dc3545; }
        .form-floating label { color: #6c757d; }
        .form-floating .form-control:focus ~ label { color: #0d6efd; }
        .btn-group .btn { border-radius: 0.375rem; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../pages/dashboard.php">
                <i class="bi bi-shop"></i> Business Management System
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../pages/dashboard.php">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a class="nav-link" href="add_order.php">
                    <i class="bi bi-cart-plus"></i> New Order
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Page Header -->
                <div class="text-center mb-4">
                    <h1 class="h2">Add New Customer</h1>
                    <p class="text-muted">Complete the form below to add a new customer to the system</p>
                </div>

                <!-- Form Container -->
                <div class="form-section p-4">
                    <!-- Alerts -->
                    <?php if ($message): ?>
                        <?php echo $message; ?>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle"></i> Please correct the following errors:
                            </h6>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Customer Form -->
                    <form method="POST" action="" id="customerForm" novalidate>
                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bi bi-person"></i> Personal Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                               value="<?php echo htmlspecialchars($firstName ?? ''); ?>" required>
                                        <label for="first_name">First Name <span class="required">*</span></label>
                                        <div class="invalid-feedback">
                                            Please provide a first name.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                               value="<?php echo htmlspecialchars($lastName ?? ''); ?>" required>
                                        <label for="last_name">Last Name <span class="required">*</span></label>
                                        <div class="invalid-feedback">
                                            Please provide a last name.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bi bi-envelope"></i> Contact Information
                            </h5>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <div class="invalid-feedback">
                                        Please provide a valid email address.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="<?php echo htmlspecialchars($phone ?? ''); ?>"
                                           placeholder="(555) 123-4567">
                                    <label for="phone">Phone Number</label>
                                    <div class="form-text">Format: (555) 123-4567 or 555-123-4567</div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="bi bi-geo-alt"></i> Address Information
                            </h5>
                            <div class="mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address" name="address"
                                           value="<?php echo htmlspecialchars($address ?? ''); ?>">
                                    <label for="address">Street Address</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="city" name="city"
                                               value="<?php echo htmlspecialchars($city ?? ''); ?>">
                                        <label for="city">City</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <select class="form-select" id="state" name="state">
                                        <option value="">Select State</option>
                                        <?php foreach ($usStates as $code => $name): ?>
                                            <option value="<?php echo $code; ?>" <?php echo (isset($state) && $state == $code) ? 'selected' : ''; ?>>
                                                <?php echo $name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="state" class="form-label">State</label>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                                               value="<?php echo htmlspecialchars($zipCode ?? ''); ?>"
                                               maxlength="10" pattern="\d{5}(-\d{4})?">
                                        <label for="zip_code">ZIP Code</label>
                                        <div class="invalid-feedback">
                                            Please provide a valid ZIP code (12345 or 12345-6789).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="../pages/dashboard.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-x-circle"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Add Customer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        Quick Actions:
                        <a href="../pages/customers.php" class="text-decoration-none">View All Customers</a> |
                        <a href="add_order.php" class="text-decoration-none">Create Order</a> |
                        <a href="../pages/dashboard.php" class="text-decoration-none">Dashboard</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 0) {
                if (value.length <= 3) {
                    formattedValue = value;
                } else if (value.length <= 6) {
                    formattedValue = '(' + value.slice(0, 3) + ') ' + value.slice(3);
                } else {
                    formattedValue = '(' + value.slice(0, 3) + ') ' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }

            e.target.value = formattedValue;
        });

        // ZIP code formatting
        document.getElementById('zip_code').addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.slice(0, 5) + '-' + value.slice(5, 9);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>