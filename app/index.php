<?php
/**
 * Business Management Database System
 * Main Entry Point (index.php)
 *
 * This file serves as the main entry point to the application.
 * It redirects users to the dashboard or handles routing based on
 * URL parameters for different application sections.
 */

require_once 'includes/functions.php';

// Check if user is accessing via mobile or prefers mobile layout
$isMobile = (isset($_SERVER['HTTP_USER_AGENT']) &&
           (preg_match('/(android|iphone|ipod|ipad|blackberry|webos|windows phone)/i', $_SERVER['HTTP_USER_AGENT'])));

// Handle routing based on URL parameters
$page = sanitizeInput($_GET['page'] ?? 'dashboard');

// Valid pages array for security
$validPages = [
    'dashboard' => 'pages/dashboard.php',
    'customers' => 'pages/customers.php',
    'products' => 'pages/products.php',
    'orders' => 'pages/orders.php',
    'reports' => 'pages/reports.php',
    'employees' => 'pages/employees.php',
    'add_customer' => 'forms/add_customer.php',
    'add_order' => 'forms/add_order.php',
    'add_product' => 'forms/add_product.php',
    'search_products' => 'api/search_products.php',
    'process_order' => 'api/process_order.php'
];

// Validate the requested page
if (!array_key_exists($page, $validPages)) {
    $page = 'dashboard'; // Default to dashboard
}

// For API endpoints, include directly without HTML wrapper
if (strpos($page, 'api/') !== false || in_array($page, ['search_products', 'process_order'])) {
    require $validPages[$page];
    exit;
}

// Initialize session for user management (placeholder)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set page-specific variables
$title = 'Business Management System';
$description = 'Comprehensive business management database system for customer, product, and order management';

// Get system statistics for sidebar
$systemStats = [];
try {
    $systemStats['total_customers'] = executeQuery("SELECT COUNT(*) as count FROM customers")[0]['count'];
    $systemStats['total_products'] = executeQuery("SELECT COUNT(*) as count FROM products")[0]['count'];
    $systemStats['total_orders'] = executeQuery("SELECT COUNT(*) as count FROM orders")[0]['count'];
    $systemStats['pending_orders'] = executeQuery("SELECT COUNT(*) as count FROM orders WHERE order_status IN ('pending', 'processing')")[0]['count'];
} catch (Exception $e) {
    // If database is not available, show zero stats
    $systemStats = [
        'total_customers' => 0,
        'total_products' => 0,
        'total_orders' => 0,
        'pending_orders' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="business management, database, inventory, orders, customers, products">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0b5ed7 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .main-container {
            min-height: calc(100vh - 56px);
        }

        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            border-left: 4px solid var(--primary-color);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .content-area {
            padding: 2rem;
        }

        .stat-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }

        .quick-stats {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .quick-stats .stat-item {
            text-align: center;
            padding: 0.5rem;
        }

        .quick-stats .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .quick-stats .stat-label {
            font-size: 0.875rem;
            color: var(--secondary-color);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .content-area {
                padding: 1rem;
            }

            .stat-card .card-body {
                padding: 1rem;
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alert animations */
        .alert {
            animation: slideInDown 0.3s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?page=dashboard">
                <i class="bi bi-shop"></i> Business Management System
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>" href="?page=dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="quickActions" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-lightning"></i> Quick Actions
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?page=add_customer">
                                <i class="bi bi-person-plus"></i> Add Customer
                            </a></li>
                            <li><a class="dropdown-item" href="?page=add_order">
                                <i class="bi bi-cart-plus"></i> New Order
                            </a></li>
                            <li><a class="dropdown-item" href="?page=add_product">
                                <i class="bi bi-plus-circle"></i> Add Product
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="?page=reports">
                                <i class="bi bi-graph-up"></i> View Reports
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=reports" title="Reports">
                            <i class="bi bi-bar-chart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-lg-2 col-md-3 d-none d-md-block sidebar">
                    <div class="p-3">
                        <h6 class="text-white text-uppercase mb-3">Navigation</h6>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>" href="?page=dashboard">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'customers' ? 'active' : ''; ?>" href="?page=customers">
                                    <i class="bi bi-people"></i> Customers
                                    <span class="badge bg-primary float-end"><?php echo $systemStats['total_customers']; ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'products' ? 'active' : ''; ?>" href="?page=products">
                                    <i class="bi bi-box"></i> Products
                                    <span class="badge bg-success float-end"><?php echo $systemStats['total_products']; ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'orders' ? 'active' : ''; ?>" href="?page=orders">
                                    <i class="bi bi-receipt"></i> Orders
                                    <span class="badge bg-info float-end"><?php echo $systemStats['total_orders']; ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'employees' ? 'active' : ''; ?>" href="?page=employees">
                                    <i class="bi bi-person-badge"></i> Employees
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $page === 'reports' ? 'active' : ''; ?>" href="?page=reports">
                                    <i class="bi bi-graph-up"></i> Reports
                                </a>
                            </li>
                        </ul>

                        <hr class="text-white-50 my-3">

                        <h6 class="text-white text-uppercase mb-3">Quick Stats</h6>
                        <div class="quick-stats">
                            <div class="row">
                                <div class="col-6 stat-item">
                                    <div class="stat-number text-warning"><?php echo $systemStats['pending_orders']; ?></div>
                                    <div class="stat-label">Pending</div>
                                </div>
                                <div class="col-6 stat-item">
                                    <div class="stat-number text-info"><?php echo $systemStats['total_orders']; ?></div>
                                    <div class="stat-label">Total Orders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content Area -->
                <main class="col-lg-10 col-md-9 content-area">
                    <?php
                    // Include the requested page content
                    try {
                        require $validPages[$page];
                    } catch (Exception $e) {
                        echo displayAlert('Error loading page: ' . $e->getMessage(), 'error');
                        error_log('Page Load Error: ' . $e->getMessage());
                    }
                    ?>
                </main>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5>Business Management System</h5>
                    <p>Comprehensive database solution for business operations</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="?page=dashboard" class="text-white-50">Dashboard</a></li>
                        <li><a href="?page=reports" class="text-white-50">Reports</a></li>
                        <li><a href="?page=add_customer" class="text-white-50">Add Customer</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white-50">
            <div class="text-center">
                <small>&copy; <?php echo date('Y'); ?> Business Management System. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Loading state for buttons
        document.querySelectorAll('button[type="submit"]').forEach(function(button) {
            button.addEventListener('click', function() {
                if (this.form.checkValidity()) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="loading"></span> Processing...';
                    this.disabled = true;

                    // Re-enable after 10 seconds (timeout protection)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 10000);
                }
            });
        });

        // Mobile sidebar toggle
        const sidebarToggle = document.createElement('button');
        sidebarToggle.className = 'btn btn-primary d-md-none position-fixed';
        sidebarToggle.style.bottom = '20px';
        sidebarToggle.style.right = '20px';
        sidebarToggle.style.zIndex = '1000';
        sidebarToggle.innerHTML = '<i class="bi bi-list"></i>';
        sidebarToggle.setAttribute('data-bs-toggle', 'offcanvas');
        sidebarToggle.setAttribute('data-bs-target', '#mobileSidebar');

        document.body.appendChild(sidebarToggle);

        // Create mobile sidebar
        if (!document.getElementById('mobileSidebar')) {
            const mobileSidebar = document.createElement('div');
            mobileSidebar.className = 'offcanvas offcanvas-start';
            mobileSidebar.id = 'mobileSidebar';
            mobileSidebar.setAttribute('tabindex', '-1');
            mobileSidebar.innerHTML = `
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Navigation</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="?page=dashboard" data-bs-dismiss="offcanvas">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=customers" data-bs-dismiss="offcanvas">
                                <i class="bi bi-people"></i> Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=products" data-bs-dismiss="offcanvas">
                                <i class="bi bi-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=orders" data-bs-dismiss="offcanvas">
                                <i class="bi bi-receipt"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?page=reports" data-bs-dismiss="offcanvas">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </li>
                    </ul>
                </div>
            `;
            document.body.appendChild(mobileSidebar);
        }
    </script>
</body>
</html>