<?php
/**
 * Business Management Database System
 * Utility Functions
 *
 * This file contains common utility functions used throughout
 * the application for formatting, validation, and database operations.
 */

require_once '../config/database.php';

/**
 * Format currency amount
 * @param float $amount Amount to format
 * @return string Formatted currency string
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Format date for display
 * @param string $date Date string
 * @return string Formatted date
 */
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

/**
 * Create status badge HTML
 * @param string $status Order status
 * @return string HTML for status badge
 */
function getStatusBadge($status) {
    $colors = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];

    $color = $colors[$status] ?? 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
}

/**
 * Create stock level badge HTML
 * @param int $quantity Stock quantity
 * @return string HTML for stock badge
 */
function getStockBadge($quantity) {
    if ($quantity == 0) {
        return '<span class="badge bg-danger">Out of Stock</span>';
    } elseif ($quantity < 10) {
        return '<span class="badge bg-warning">Low Stock</span>';
    } else {
        return '<span class="badge bg-success">In Stock</span>';
    }
}

/**
 * Sanitize user input
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Execute database query with optional parameters
 * @param string $sql SQL query
 * @param array $params Optional parameters for prepared statements
 * @return array Query results
 */
function executeQuery($sql, $params = []) {
    $db = new Database();
    $conn = $db->connect();

    if (empty($params)) {
        $result = $conn->query($sql);
    } else {
        $stmt = $conn->prepare($sql);
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $db->close();
    return $data;
}

/**
 * Execute query and return single row
 * @param string $sql SQL query
 * @param array $params Optional parameters
 * @return array|null Single result row or null
 */
function executeQuerySingle($sql, $params = []) {
    $results = executeQuery($sql, $params);
    return !empty($results) ? $results[0] : null;
}

/**
 * Execute insert/update/delete query
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return bool Success status
 */
function executeUpdate($sql, $params = []) {
    $db = new Database();
    $conn = $db->connect();
    $success = false;

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();
    $db->close();

    return $success;
}

/**
 * Generate alert HTML
 * @param string $message Alert message
 * @param string $type Alert type (success, error, warning, info)
 * @return string HTML for alert
 */
function displayAlert($message, $type = 'success') {
    $alertClass = $type === 'error' ? 'danger' : $type;
    return '<div class="alert alert-' . $alertClass . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool Valid email status
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic format)
 * @param string $phone Phone number to validate
 * @return bool Valid phone status
 */
function validatePhone($phone) {
    // Allow digits, spaces, hyphens, parentheses, and plus sign
    return preg_match('/^[\d\s\-\(\)\+]+$/', $phone);
}

/**
 * Paginate query results
 * @param string $sql Base SQL query
 * @param int $page Current page number
 * @param int $perPage Items per page
 * @return array Paginated results with metadata
 */
function paginateQuery($sql, $page = 1, $perPage = 10) {
    $offset = ($page - 1) * $perPage;

    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM ($sql) as count_query";
    $totalResult = executeQuerySingle($countSql);
    $total = $totalResult['total'] ?? 0;

    // Get paginated results
    $paginatedSql = "$sql LIMIT $perPage OFFSET $offset";
    $results = executeQuery($paginatedSql);

    return [
        'data' => $results,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)
    ];
}

/**
 * Generate pagination HTML
 * @param int $currentPage Current page
 * @param int $totalPages Total pages
 * @param string $baseUrl Base URL for pagination links
 * @return string HTML for pagination
 */
function generatePagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<nav aria-label="Page navigation"><ul class="pagination">';

    // Previous button
    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }

    // Next button
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

/**
 * Create dropdown options from database results
 * @param array $options Database results array
 * @param string $valueField Field to use as value
 * @param string $displayField Field to display
 * @param mixed $selectedValue Currently selected value
 * @return string HTML for select options
 */
function createDropdownOptions($options, $valueField, $displayField, $selectedValue = null) {
    $html = '';
    foreach ($options as $option) {
        $value = htmlspecialchars($option[$valueField]);
        $display = htmlspecialchars($option[$displayField]);
        $selected = ($value == $selectedValue) ? 'selected' : '';
        $html .= "<option value=\"$value\" $selected>$display</option>";
    }
    return $html;
}

/**
 * Log system activity
 * @param string $action Action performed
 * @param string $description Action description
 * @param int $userId User ID (optional)
 */
function logActivity($action, $description, $userId = null) {
    // This could be expanded to write to a database log table
    $timestamp = date('Y-m-d H:i:s');
    $userId = $userId ?? 'system';
    $logMessage = "[$timestamp] User: $userId - Action: $action - Description: $description\n";

    // For now, write to a log file (ensure log directory is writable)
    error_log($logMessage, 3, '../logs/system.log');
}

/**
 * Check if user is logged in (placeholder for authentication)
 * @return bool Authentication status
 */
function isLoggedIn() {
    // This is a placeholder - implement proper session-based authentication
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID (placeholder for authentication)
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId() {
    // This is a placeholder - implement proper session management
    return $_SESSION['user_id'] ?? null;
}
?>