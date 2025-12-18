<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Check</h1>";

include 'db_connect.php';

// Check connection
if ($conn) {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Users table exists</p>";
        
        // Count users
        $count = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc();
        echo "<p>Total users: " . $count['total'] . "</p>";
        
        // List users
        $users = $conn->query("SELECT user_id, username, email, full_name FROM users");
        echo "<h3>Registered Users:</h3><ul>";
        while ($user = $users->fetch_assoc()) {
            echo "<li>{$user['username']} ({$user['full_name']}) - {$user['email']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Users table does NOT exist</p>";
        echo "<p><strong>Action:</strong> Import create_users_table.sql via phpMyAdmin</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>";
}
?>
