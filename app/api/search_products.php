<?php
/**
 * Business Management Database System
 * Product Search API
 *
 * This API endpoint provides product search functionality for use in forms
 * and applications. It returns JSON-formatted product data with inventory
 * information and availability status.
 *
 * Usage:
 * GET /api/search_products.php?q=search_term&category_id=1&limit=10
 *
 * Parameters:
 * q: Search term (searches product name and description)
 * category_id: Optional category filter
 * limit: Maximum number of results (default: 20)
 * in_stock: Set to '1' to only show products in stock
 */

require_once '../includes/functions.php';

// Set response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Initialize response array
$response = [
    'success' => true,
    'data' => [],
    'message' => '',
    'total_count' => 0
];

try {
    // Get and validate parameters
    $searchTerm = sanitizeInput($_GET['q'] ?? '');
    $categoryId = sanitizeInput($_GET['category_id'] ?? '');
    $limit = (int)($_GET['limit'] ?? 20);
    $inStockOnly = sanitizeInput($_GET['in_stock'] ?? '');
    $sortBy = sanitizeInput($_GET['sort_by'] ?? 'product_name');
    $sortOrder = sanitizeInput($_GET['sort_order'] ?? 'ASC');

    // Validate limit
    $limit = min(max($limit, 1), 100); // Between 1 and 100

    // Validate sort order
    $sortOrder = in_array(strtoupper($sortOrder), ['ASC', 'DESC']) ? strtoupper($sortOrder) : 'ASC';

    // Build base query
    $sql = "SELECT
                p.product_id,
                p.product_name,
                p.description,
                p.price,
                p.stock_quantity,
                p.category_id,
                c.category_name,
                CASE
                    WHEN p.stock_quantity = 0 THEN 'Out of Stock'
                    WHEN p.stock_quantity < 10 THEN 'Low Stock'
                    WHEN p.stock_quantity < 25 THEN 'Limited Stock'
                    ELSE 'In Stock'
                END as stock_status,
                (SELECT COUNT(*) FROM order_details od
                 JOIN orders o ON od.order_id = o.order_id
                 WHERE od.product_id = p.product_id AND o.order_status = 'completed') as times_sold,
                (SELECT AVG(od.unit_price) FROM order_details od
                 JOIN orders o ON od.order_id = o.order_id
                 WHERE od.product_id = p.product_id AND o.order_status = 'completed') as avg_sale_price
            FROM products p
            JOIN categories c ON p.category_id = c.category_id";

    // Build WHERE conditions
    $whereConditions = [];
    $params = [];

    // Search term condition (searches product name and description)
    if (!empty($searchTerm)) {
        $whereConditions[] = "(p.product_name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
    }

    // Category filter
    if (!empty($categoryId) && is_numeric($categoryId)) {
        $whereConditions[] = "p.category_id = ?";
        $params[] = $categoryId;
    }

    // Stock filter
    if ($inStockOnly === '1') {
        $whereConditions[] = "p.stock_quantity > 0";
    }

    // Combine WHERE conditions
    if (!empty($whereConditions)) {
        $sql .= " WHERE " . implode(' AND ', $whereConditions);
    }

    // Add ORDER BY clause
    $allowedSortFields = ['product_name', 'price', 'stock_quantity', 'category_name', 'times_sold'];
    $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'product_name';
    $sql .= " ORDER BY p.$sortBy $sortOrder";

    // Add LIMIT clause
    $sql .= " LIMIT ?";

    // Add limit to parameters
    $params[] = $limit;

    // Execute main query
    $products = executeQuery($sql, $params);

    // Get total count (without LIMIT)
    $countSql = "SELECT COUNT(*) as total FROM products p JOIN categories c ON p.category_id = c.category_id";
    if (!empty($whereConditions)) {
        $countSql .= " WHERE " . implode(' AND ', $whereConditions);
    }
    $countResult = executeQuerySingle($countSql, array_slice($params, 0, -1)); // Remove limit parameter
    $totalCount = $countResult['total'] ?? 0;

    // Format product data
    $formattedProducts = [];
    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_id' => (int)$product['product_id'],
            'product_name' => htmlspecialchars($product['product_name']),
            'description' => htmlspecialchars($product['description']),
            'price' => (float)$product['price'],
            'price_formatted' => formatCurrency($product['price']),
            'stock_quantity' => (int)$product['stock_quantity'],
            'category_id' => (int)$product['category_id'],
            'category_name' => htmlspecialchars($product['category_name']),
            'stock_status' => $product['stock_status'],
            'stock_badge' => getStockBadge($product['stock_quantity']),
            'times_sold' => (int)$product['times_sold'],
            'avg_sale_price' => $product['avg_sale_price'] ? (float)$product['avg_sale_price'] : null,
            'avg_sale_price_formatted' => $product['avg_sale_price'] ? formatCurrency($product['avg_sale_price']) : 'N/A'
        ];
    }

    // Set successful response
    $response['success'] = true;
    $response['data'] = $formattedProducts;
    $response['total_count'] = (int)$totalCount;
    $response['message'] = 'Products retrieved successfully';
    $response['query_info'] = [
        'search_term' => $searchTerm,
        'category_id' => $categoryId,
        'limit' => $limit,
        'in_stock_only' => $inStockOnly === '1',
        'sort_by' => $sortBy,
        'sort_order' => $sortOrder
    ];

} catch (Exception $e) {
    // Handle database errors
    $response['success'] = false;
    $response['message'] = 'Database error: ' . $e->getMessage();
    $response['data'] = [];
    $response['total_count'] = 0;

    // Log the error
    error_log('Product Search API Error: ' . $e->getMessage());
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>