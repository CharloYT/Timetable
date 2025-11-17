<?php
/**
 * Business Management Database System
 * Dashboard Page
 *
 * This page displays a comprehensive business management dashboard with:
 * - Key performance indicators
 * - Recent orders
 * - Low stock alerts
 * - Active order status counts
 */

require_once '../includes/functions.php';

// Get dashboard statistics
$totalCustomers = executeQuery("SELECT COUNT(*) as count FROM customers")[0]['count'];
$totalProducts = executeQuery("SELECT COUNT(*) as count FROM products")[0]['count'];
$totalOrders = executeQuery("SELECT COUNT(*) as count FROM orders")[0]['count'];
$totalRevenue = executeQuery("SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'completed'")[0]['total'];

// Get recent orders
$recentOrders = executeQuery("
    SELECT o.order_id, CONCAT(c.first_name, ' ', c.last_name) as customer_name,
           o.order_date, o.total_amount, o.order_status
    FROM orders o
    JOIN customers c ON o.customer_id = c.customer_id
    ORDER BY o.order_date DESC
    LIMIT 5
");

// Get low stock products
$lowStock = executeQuery("
    SELECT product_name, stock_quantity
    FROM products
    WHERE stock_quantity < 10
    ORDER BY stock_quantity ASC
    LIMIT 5
");

// Get order status counts
$orderStatuses = executeQuery("
    SELECT order_status, COUNT(*) as count
    FROM orders
    WHERE order_status IN ('pending', 'processing', 'shipped')
    GROUP BY order_status
");

// Get top employees by sales
$topEmployees = executeQuery("
    SELECT
        e.employee_id,
        CONCAT(e.first_name, ' ', e.last_name) as employee_name,
        e.position,
        COUNT(o.order_id) as total_orders,
        COALESCE(SUM(o.total_amount), 0) as total_sales
    FROM employees e
    LEFT JOIN orders o ON e.employee_id = o.employee_id
    WHERE o.order_status = 'completed'
    GROUP BY e.employee_id, e.first_name, e.last_name, e.position
    ORDER BY total_sales DESC
    LIMIT 5
");

// Get sales by category for last 30 days
$categorySales = executeQuery("
    SELECT
        c.category_name,
        SUM(od.quantity * od.unit_price) as total_sales,
        COUNT(DISTINCT o.order_id) as order_count
    FROM categories c
    LEFT JOIN products p ON c.category_id = p.category_id
    LEFT JOIN order_details od ON p.product_id = od.product_id
    LEFT JOIN orders o ON od.order_id = o.order_id AND o.order_status = 'completed'
                        AND o.order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
    GROUP BY c.category_id, c.category_name
    HAVING total_sales > 0
    ORDER BY total_sales DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-2px); }
        .stat-card { border-left: 4px solid; }
        .stat-card.primary { border-left-color: #0d6efd; }
        .stat-card.success { border-left-color: #198754; }
        .stat-card.info { border-left-color: #0dcaf0; }
        .stat-card.warning { border-left-color: #ffc107; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 0.375rem;
            margin: 0.2rem 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .main-content { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center mb-4">Business Mgmt</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../forms/add_customer.php">
                                <i class="bi bi-person-plus"></i> Add Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../forms/add_order.php">
                                <i class="bi bi-cart-plus"></i> New Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customers.php">
                                <i class="bi bi-people"></i> Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <i class="bi bi-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class="bi bi-receipt"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="employees.php">
                                <i class="bi bi-person-badge"></i> Employees
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card primary border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Customers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($totalCustomers); ?></div>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-people fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card success border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Products</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($totalProducts); ?></div>
                                    </div>
                                    <div class="text-success">
                                        <i class="bi bi-box fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card info border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($totalOrders); ?></div>
                                    </div>
                                    <div class="text-info">
                                        <i class="bi bi-receipt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card warning border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo formatCurrency($totalRevenue); ?></div>
                                    </div>
                                    <div class="text-warning">
                                        <i class="bi bi-currency-dollar fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                                <a href="orders.php" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($recentOrders)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No recent orders found</td>
                                            </tr>
                                            <?php else: ?>
                                                <?php foreach ($recentOrders as $order): ?>
                                                <tr>
                                                    <td>#<?php echo $order['order_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                    <td><?php echo formatDate($order['order_date']); ?></td>
                                                    <td><?php echo formatCurrency($order['total_amount']); ?></td>
                                                    <td><?php echo getStatusBadge($order['order_status']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header py-3 bg-danger text-white">
                                <h6 class="m-0 font-weight-bold">Low Stock Alert</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($lowStock)): ?>
                                    <div class="text-center text-success">
                                        <i class="bi bi-check-circle fa-3x mb-3"></i>
                                        <p>All products are in stock!</p>
                                    </div>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($lowStock as $product): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                                <small class="text-muted">Stock: <?php echo $product['stock_quantity']; ?></small>
                                            </div>
                                            <span class="badge bg-danger rounded-pill"><?php echo getStockBadge($product['stock_quantity']); ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="mt-3">
                                        <a href="products.php?filter=low_stock" class="btn btn-sm btn-outline-danger w-100">Manage Inventory</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Dashboard Components -->
                <div class="row">
                    <!-- Order Status Summary -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Active Orders Status</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($orderStatuses)): ?>
                                    <p class="text-muted">No active orders</p>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($orderStatuses as $status): ?>
                                        <div class="col-4 text-center">
                                            <div class="h4 mb-0"><?php echo $status['count']; ?></div>
                                            <div class="small text-muted"><?php echo getStatusBadge($status['order_status']); ?></div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Top Sales Employees -->
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Top Sales Performers</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($topEmployees)): ?>
                                    <p class="text-muted">No sales data available</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($topEmployees as $employee): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($employee['employee_name']); ?></div>
                                                <small class="text-muted"><?php echo $employee['position']; ?> • <?php echo $employee['total_orders']; ?> orders</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold"><?php echo formatCurrency($employee['total_sales']); ?></div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>