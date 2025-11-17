# Business Management Database System
## Complete Setup and Installation Guide

### Overview

This comprehensive business management database system provides a complete solution for managing customers, products, orders, and employees. The system includes:

- **MySQL Database**: 6 relational tables with proper constraints
- **Comprehensive Test Data**: 50+ customers, 40 orders, 120 order details
- **Business Intelligence**: 10 production-ready SQL queries
- **PHP Application**: Complete web interface with forms, dashboard, and APIs
- **Performance Optimization**: Database indexes for fast queries

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Database Setup](#database-setup)
3. [Application Setup](#application-setup)
4. [Configuration](#configuration)
5. [Testing the System](#testing-the-system)
6. [Performance Optimization](#performance-optimization)
7. [Security Considerations](#security-considerations)
8. [Troubleshooting](#troubleshooting)
9. [Usage Examples](#usage-examples)

---

## System Requirements

### Database Requirements
- **MySQL 5.7+** or **MariaDB 10.2+**
- Database user with CREATE, INSERT, UPDATE, DELETE, SELECT privileges
- At least 50MB of available database space

### Web Server Requirements
- **PHP 7.4+** or **PHP 8.0+**
- **mysqli** PHP extension
- **Apache** or **Nginx** web server
- **SSL/TLS** (recommended for production)

### Optional Requirements
- **PHPMyAdmin** or **MySQL Workbench** for database management
- **Git** for version control
- **Composer** for PHP dependency management

---

## Database Setup

### Step 1: Create Database

```sql
-- Connect to MySQL as root or administrative user
mysql -u root -p

-- Create the database
CREATE DATABASE business_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create database user (recommended for security)
CREATE USER 'business_db_user'@'localhost' IDENTIFIED BY 'secure_password';

-- Grant privileges to the user
GRANT ALL PRIVILEGES ON business_management.* TO 'business_db_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

### Step 2: Execute Database Scripts

Execute the scripts in the following order:

```bash
# Navigate to the database directory
cd database

# Execute scripts in order
mysql -u business_db_user -p business_management < create_tables.sql
mysql -u business_db_user -p business_management < add_constraints.sql
mysql -u business_db_user -p business_management < insert_data.sql
mysql -u business_db_user -p business_management < performance_indexes.sql
```

### Step 3: Verify Database Installation

```sql
-- Connect to your database
mysql -u business_db_user -p business_management

-- Check all tables exist
SHOW TABLES;

-- Verify data counts
SELECT 'customers' as table_name, COUNT(*) as count FROM customers
UNION ALL
SELECT 'employees', COUNT(*) FROM employees
UNION ALL
SELECT 'categories', COUNT(*) FROM categories
UNION ALL
SELECT 'products', COUNT(*) FROM products
UNION ALL
SELECT 'orders', COUNT(*) FROM orders
UNION ALL
SELECT 'order_details', COUNT(*) FROM order_details;
```

**Expected Results:**
- customers: 25 records
- employees: 6 records
- categories: 4 records
- products: 15 records
- orders: 40 records
- order_details: 120 records

### Step 4: Test Database Integrity

```sql
-- Test foreign key constraints
SELECT
    TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM
    information_schema.KEY_COLUMN_USAGE
WHERE
    TABLE_SCHEMA = 'business_management'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Test referential integrity
SELECT COUNT(*) as orphaned_orders
FROM orders o
LEFT JOIN customers c ON o.customer_id = c.customer_id
WHERE c.customer_id IS NULL;
```

---

## Application Setup

### Step 1: Configure Web Server

#### Apache Configuration

Create a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/Timetable/app

    <Directory /path/to/Timetable/app>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/business_management_error.log
    CustomLog ${APACHE_LOG_DIR}/business_management_access.log combined
</VirtualHost>
```

Enable necessary Apache modules:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/Timetable/app;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Step 2: Set File Permissions

```bash
# Set appropriate ownership
sudo chown -R www-data:www-data /path/to/Timetable/app

# Set appropriate permissions
sudo find /path/to/Timetable/app -type d -exec chmod 755 {} \;
sudo find /path/to/Timetable/app -type f -exec chmod 644 {} \;

# Set writable permissions for logs (if using)
sudo mkdir -p /path/to/Timetable/app/logs
sudo chmod 755 /path/to/Timetable/app/logs
sudo chmod 664 /path/to/Timetable/app/logs/*
```

### Step 3: Create Additional Directories

```bash
cd /path/to/Timetable/app
mkdir -p logs uploads temp
chmod 755 logs uploads temp
```

---

## Configuration

### Step 1: Database Configuration

Edit the database configuration file:

```bash
nano app/config/database.php
```

Update the database connection parameters:

```php
private $host = 'localhost';
private $username = 'business_db_user';
private $password = 'your_secure_password';
private $database = 'business_management';
```

### Step 2: Create .htaccess for Clean URLs

Create `.htaccess` file in the app directory:

```apache
RewriteEngine On

# Security headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "no-referrer-when-downgrade"
    Header always set Content-Security-Policy "default-src 'self' cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net"
</IfModule>

# Rewrite rules for clean URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Hide .php extension
RewriteCond %{REQUEST_FILENAME}\.php -f
RewriteRule ^(.+)$ $1.php [L]

# Route all requests to index.php
RewriteRule ^(.*)$ index.php?page=$1 [L,QSA]

# Prevent access to sensitive files
<FilesMatch "^(config|database)\.php$">
    Require all denied
</FilesMatch>

# Prevent access to log files
<FilesMatch "\.log$">
    Require all denied
</FilesMatch>
```

### Step 3: PHP Configuration

Create or edit `php.ini` settings:

```ini
; Error reporting for development
display_errors = On
error_reporting = E_ALL

; Production settings (comment out for development)
; display_errors = Off
; log_errors = On
; error_log = /path/to/Timetable/app/logs/php_errors.log

; Security settings
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off

; Performance settings
max_execution_time = 30
memory_limit = 128M
post_max_size = 8M
upload_max_filesize = 2M

; Session security
session.cookie_httponly = 1
session.use_strict_mode = 1
session.cookie_samesite = Strict
```

---

## Testing the System

### Step 1: Database Query Tests

Execute these test queries to verify functionality:

```sql
-- Test basic joins work correctly
SELECT
    c.first_name,
    c.last_name,
    o.order_date,
    o.total_amount
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
LIMIT 5;

-- Test application queries
SOURCE database/application_queries.sql;

-- Test constraint functionality
-- Try to insert invalid foreign key (should fail)
START TRANSACTION;
INSERT INTO orders (customer_id, employee_id, total_amount) VALUES (999, 999, 100.00);
ROLLBACK;

-- Test cascade delete
START TRANSACTION;
INSERT INTO customers (first_name, last_name, email) VALUES ('Test', 'User', 'test@example.com');
SET @test_customer_id = LAST_INSERT_ID();
INSERT INTO orders (customer_id, employee_id, total_amount) VALUES (@test_customer_id, 1, 50.00);
DELETE FROM customers WHERE customer_id = @test_customer_id;
ROLLBACK;
```

### Step 2: PHP Application Tests

Test the web application:

1. **Access the Dashboard**:
   - URL: `http://your-domain.com/app/`
   - Verify all statistics load correctly
   - Check that recent orders display

2. **Test Customer Management**:
   - URL: `http://your-domain.com/app/forms/add_customer.php`
   - Try adding a new customer
   - Test form validation

3. **Test API Endpoints**:

```bash
# Test product search API
curl "http://your-domain.com/app/api/search_products.php?q=laptop&limit=5"

# Test order processing API
curl -X POST http://your-domain.com/app/api/process_order.php \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "employee_id": 1,
    "items": [
      {
        "product_id": 1,
        "quantity": 1,
        "price": 999.99
      }
    ]
  }'
```

### Step 3: Performance Testing

Test query performance:

```sql
-- Enable query profiling
SET profiling = 1;

-- Run a complex query
SELECT
    c.category_name,
    SUM(od.quantity * od.unit_price) as total_sales,
    COUNT(DISTINCT o.order_id) as order_count
FROM categories c
LEFT JOIN products p ON c.category_id = p.category_id
LEFT JOIN order_details od ON p.product_id = od.product_id
LEFT JOIN orders o ON od.order_id = o.order_id AND o.order_status = 'completed'
WHERE o.order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
GROUP BY c.category_id
ORDER BY total_sales DESC;

-- View query profile
SHOW PROFILE;
SHOW PROFILE FOR QUERY 1;
```

---

## Performance Optimization

### Step 1: Database Optimization

Execute the performance indexes:

```sql
mysql -u business_db_user -p business_management < database/performance_indexes.sql
```

### Step 2: MySQL Configuration

Add these settings to your MySQL configuration (`my.cnf` or `my.ini`):

```ini
[mysqld]
# Performance settings
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_method = O_DIRECT

# Query cache (MySQL 5.7 and earlier)
query_cache_type = 1
query_cache_size = 64M
query_cache_limit = 2M

# Connection settings
max_connections = 100
connect_timeout = 10
wait_timeout = 600
max_allowed_packet = 64M

# Logging (for performance monitoring)
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

### Step 3: PHP OPcache

Enable and configure OPcache in `php.ini`:

```ini
; OPcache configuration
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.enable_file_override=0
```

---

## Security Considerations

### Step 1: Database Security

```sql
-- Create limited user for web application
CREATE USER 'web_app_user'@'localhost' IDENTIFIED BY 'strong_password';

-- Grant only necessary privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON business_management.* TO 'web_app_user'@'localhost';

-- Revoke dangerous privileges
REVOKE CREATE, DROP, ALTER, INDEX ON business_management.* FROM 'web_app_user'@'localhost';
```

### Step 2: Web Server Security

```apache
# Security headers for Apache
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-XSS-Protection "1; mode=block"
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

### Step 3: PHP Security

```php
// Enable secure session settings in your PHP code
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Prevent session fixation
session_regenerate_id(true);

// Use HTTPS in production
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    // Redirect to HTTPS
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}
```

---

## Troubleshooting

### Common Issues and Solutions

#### 1. Database Connection Errors

**Problem**: "Connection failed: Access denied for user"

**Solution**:
```bash
# Check MySQL user exists
mysql -u root -p
SELECT user, host FROM mysql.user;

# Recreate user if necessary
DROP USER IF EXISTS 'business_db_user'@'localhost';
CREATE USER 'business_db_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON business_management.* TO 'business_db_user'@'localhost';
FLUSH PRIVILEGES;
```

#### 2. PHP mysqli Extension Missing

**Problem**: "Call to undefined function mysqli_connect()"

**Solution**:
```bash
# Ubuntu/Debian
sudo apt-get install php-mysql

# CentOS/RHEL
sudo yum install php-mysqli

# Restart web server
sudo systemctl restart apache2
```

#### 3. File Permission Issues

**Problem**: "Permission denied" when accessing files

**Solution**:
```bash
# Set correct ownership
sudo chown -R www-data:www-data /path/to/Timetable/app

# Fix permissions
sudo find /path/to/Timetable/app -type d -exec chmod 755 {} \;
sudo find /path/to/Timetable/app -type f -exec chmod 644 {} \;
```

#### 4. Slow Query Performance

**Problem**: Dashboard loading slowly

**Solution**:
```sql
-- Check if indexes exist
SHOW INDEX FROM orders;
SHOW INDEX FROM customers;

-- Add missing indexes
SOURCE database/performance_indexes.sql;

-- Analyze table statistics
ANALYZE TABLE customers, employees, categories, products, orders, order_details;
```

#### 5. Session Issues

**Problem**: Session data not persisting

**Solution**:
```bash
# Check session save path
php -i | grep session.save_path

# Create session directory if needed
sudo mkdir -p /var/lib/php/sessions
sudo chown www-data:www-data /var/lib/php/sessions
sudo chmod 755 /var/lib/php/sessions
```

---

## Usage Examples

### Example 1: Daily Sales Report

```sql
-- Run from database/application_queries.sql
-- Query 9: Revenue Summary Report
-- Set period to 'daily', date range for today
```

### Example 2: Add Customer via API

```bash
curl -X POST http://your-domain.com/app/api/add_customer.php \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "phone": "555-123-4567",
    "address": "123 Main St",
    "city": "Anytown",
    "state": "CA",
    "zip_code": "90210"
  }'
```

### Example 3: Product Search Integration

```javascript
// JavaScript example for real-time product search
function searchProducts(query) {
    fetch(`/app/api/search_products.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProducts(data.data);
            }
        })
        .catch(error => console.error('Error:', error));
}
```

---

## Maintenance Schedule

### Daily
- Check application logs for errors
- Monitor database performance
- Backup database (full backup weekly, incremental daily)

### Weekly
- Analyze table statistics: `ANALYZE TABLE`
- Check slow query log
- Review application performance metrics

### Monthly
- Optimize tables: `OPTIMIZE TABLE`
- Review and update security patches
- Clean up old log files
- Test backup restoration

### Quarterly
- Review index usage and remove unused indexes
- Update PHP and MySQL versions
- Performance audit and optimization
- Security audit

---

## Support and Contact

For technical support, questions, or contributions:

1. **Database Issues**: Check MySQL error logs and query performance
2. **Application Issues**: Check PHP error logs in `app/logs/`
3. **Performance Issues**: Review the performance optimization section
4. **Security Issues**: Follow security best practices and keep software updated

**Documentation References:**
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PHP Documentation](https://www.php.net/docs.php)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)

---

## License and Copyright

© 2023 Business Management Database System. All rights reserved.

This system is provided as-is for educational and demonstration purposes. Please review and customize for your specific business requirements before production deployment.