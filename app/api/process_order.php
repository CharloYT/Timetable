<?php
/**
 * Business Management Database System
 * Order Processing API
 *
 * This API endpoint handles order creation and processing. It creates
 * orders with multiple items, updates inventory, and maintains data integrity
 * through database transactions.
 *
 * Usage:
 * POST /api/process_order.php
 *
 * JSON Payload:
 * {
 *   "customer_id": 1,
 *   "employee_id": 2,
 *   "items": [
 *     {
 *       "product_id": 1,
 *       "quantity": 2,
 *       "price": 999.99
 *     }
 *   ],
 *   "notes": "Customer notes"
 * }
 */

require_once '../includes/functions.php';

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'order_id' => null,
    'order_details' => null,
    'errors' => []
];

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method. Only POST requests are allowed.';
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

try {
    // Get and decode JSON payload
    $jsonInput = file_get_contents('php://input');
    if (empty($jsonInput)) {
        throw new Exception('No data received');
    }

    $data = json_decode($jsonInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    // Validate required fields
    $requiredFields = ['customer_id', 'employee_id', 'items'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        throw new Exception('Missing required fields: ' . implode(', ', $missingFields));
    }

    // Extract and validate data
    $customerId = (int)$data['customer_id'];
    $employeeId = (int)$data['employee_id'];
    $items = $data['items'];
    $notes = sanitizeInput($data['notes'] ?? '');

    // Validate customer exists
    $customer = executeQuerySingle("SELECT customer_id, CONCAT(first_name, ' ', last_name) as name FROM customers WHERE customer_id = ?", [$customerId]);
    if (!$customer) {
        throw new Exception('Invalid customer ID: Customer not found');
    }

    // Validate employee exists
    $employee = executeQuerySingle("SELECT employee_id, CONCAT(first_name, ' ', last_name) as name FROM employees WHERE employee_id = ?", [$employeeId]);
    if (!$employee) {
        throw new Exception('Invalid employee ID: Employee not found');
    }

    // Validate items array
    if (!is_array($items) || empty($items)) {
        throw new Exception('Order must contain at least one item');
    }

    // Validate each item and calculate total
    $totalAmount = 0;
    $validatedItems = [];

    foreach ($items as $index => $item) {
        // Validate item structure
        if (!isset($item['product_id']) || !isset($item['quantity']) || !isset($item['price'])) {
            throw new Exception("Item $index: Missing required fields (product_id, quantity, price)");
        }

        $productId = (int)$item['product_id'];
        $quantity = (int)$item['quantity'];
        $price = (float)$item['price'];

        if ($productId <= 0) {
            throw new Exception("Item $index: Invalid product ID");
        }

        if ($quantity <= 0) {
            throw new Exception("Item $index: Quantity must be greater than 0");
        }

        if ($price <= 0) {
            throw new Exception("Item $index: Price must be greater than 0");
        }

        // Check product exists and get current info
        $product = executeQuerySingle("SELECT product_name, stock_quantity, price FROM products WHERE product_id = ?", [$productId]);
        if (!$product) {
            throw new Exception("Item $index: Product not found");
        }

        // Check if enough stock is available
        if ($product['stock_quantity'] < $quantity) {
            throw new Exception("Item $index: Insufficient stock. Available: {$product['stock_quantity']}, Requested: $quantity");
        }

        // Validate price matches current product price (or allow override with permission)
        if (abs($price - $product['price']) > 0.01) {
            // Price differs from current - you might want to add permission check here
            // For now, we'll use the current price
            $price = $product['price'];
        }

        $lineTotal = $quantity * $price;
        $totalAmount += $lineTotal;

        $validatedItems[] = [
            'product_id' => $productId,
            'product_name' => $product['product_name'],
            'quantity' => $quantity,
            'unit_price' => $price,
            'line_total' => $lineTotal
        ];
    }

    // Start database transaction
    $db = new Database();
    $conn = $db->connect();

    try {
        $conn->begin_transaction();

        // Create order
        $orderSql = "INSERT INTO orders (customer_id, employee_id, order_date, total_amount, order_status) VALUES (?, ?, CURRENT_DATE, ?, 'pending')";
        $stmt = $conn->prepare($orderSql);
        $stmt->bind_param("iid", $customerId, $employeeId, $totalAmount);
        $stmt->execute();
        $orderId = $conn->insert_id;

        if (!$orderId) {
            throw new Exception('Failed to create order');
        }

        // Add order details and update inventory
        foreach ($validatedItems as $item) {
            // Insert order detail
            $detailSql = "INSERT INTO order_details (order_id, product_id, quantity, unit_price, line_total) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($detailSql);
            $stmt->bind_param("iiidd", $orderId, $item['product_id'], $item['quantity'], $item['unit_price'], $item['line_total']);
            $stmt->execute();

            // Update product stock
            $updateSql = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();

            // Verify stock update
            if ($stmt->affected_rows === 0) {
                throw new Exception("Failed to update stock for product: {$item['product_name']}");
            }
        }

        // Add order notes if provided
        if (!empty($notes)) {
            $notesSql = "UPDATE orders SET notes = ? WHERE order_id = ?";
            $stmt = $conn->prepare($notesSql);
            $stmt->bind_param("si", $notes, $orderId);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Log the activity
        logActivity('order_created', "Order #$orderId created for customer {$customer['name']} by {$employee['name']}", getCurrentUserId());

        // Prepare success response
        $response['success'] = true;
        $response['message'] = 'Order created successfully';
        $response['order_id'] = $orderId;
        $response['order_details'] = [
            'order_id' => $orderId,
            'customer' => $customer,
            'employee' => $employee,
            'order_date' => date('Y-m-d'),
            'total_amount' => $totalAmount,
            'total_amount_formatted' => formatCurrency($totalAmount),
            'status' => 'pending',
            'items' => $validatedItems,
            'item_count' => count($validatedItems),
            'notes' => $notes
        ];

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw new Exception('Order processing failed: ' . $e->getMessage());
    }

    $db->close();

} catch (Exception $e) {
    // Handle general errors
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    $response['errors'][] = $e->getMessage();

    // Log the error
    error_log('Order Processing API Error: ' . $e->getMessage());
    logActivity('order_creation_failed', 'Order creation failed: ' . $e->getMessage(), getCurrentUserId());
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>