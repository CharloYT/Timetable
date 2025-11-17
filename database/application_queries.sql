-- Business Management Database System
-- Application Queries
--
-- This file contains 10 comprehensive SQL queries for the business management system.
-- These queries support various application components including navigation, forms, reports,
-- and business intelligence operations.
--
-- Query Categories:
-- 1-2: Navigation and Menu Queries
-- 3-5: Form-Related Queries
-- 6-10: Report Queries

-- =====================================================
-- Navigation and Menu Queries
-- =====================================================

-- Query 1: Product Category Navigation
-- Get all categories with product count for navigation menu
SELECT
    c.category_id,
    c.category_name,
    COUNT(p.product_id) as product_count
FROM categories c
LEFT JOIN products p ON c.category_id = p.category_id
GROUP BY c.category_id, c.category_name
ORDER BY c.category_name;

-- Usage: Populates navigation menu showing all product categories with item counts
-- Business Value: Helps customers browse products by category easily
-- Component: Navigation menu, category browsing interface


-- Query 2: Active Orders Count
-- Count of pending and processing orders for dashboard
SELECT
    order_status,
    COUNT(*) as count
FROM orders
WHERE order_status IN ('pending', 'processing', 'shipped')
GROUP BY order_status;

-- Usage: Dashboard widget showing active order status counts
-- Business Value: Improves order processing efficiency and customer service
-- Component: Management dashboard, order tracking interface


-- =====================================================
-- Form-Related Queries
-- =====================================================

-- Query 3: Customer Search/Select
-- Search customers by name or email for order forms
SELECT
    customer_id,
    CONCAT(first_name, ' ', last_name) as full_name,
    email,
    phone,
    city,
    state
FROM customers
WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?
ORDER BY last_name, first_name
LIMIT 10;

-- Usage: Autocomplete dropdown in customer selection forms
-- Business Value: Speeds up order entry and reduces data entry errors
-- Component: Customer search in order forms, customer service lookup
-- Parameters: Use wildcards (%) for partial matching


-- Query 4: Product Search with Inventory
-- Search products with availability for order forms
SELECT
    p.product_id,
    p.product_name,
    p.price,
    p.stock_quantity,
    c.category_name,
    CASE
        WHEN p.stock_quantity > 0 THEN 'In Stock'
        ELSE 'Out of Stock'
    END as availability
FROM products p
JOIN categories c ON p.category_id = c.category_id
WHERE p.product_name LIKE ? AND p.stock_quantity > 0
ORDER BY p.product_name
LIMIT 20;

-- Usage: Product search results in order entry forms
-- Business Value: Prevents ordering out-of-stock items, improves user experience
-- Component: Product catalog, order entry form
-- Parameters: Product name search term with wildcards


-- Query 5: Employee Performance Summary
-- Employee sales data for management forms
SELECT
    e.employee_id,
    CONCAT(e.first_name, ' ', e.last_name) as employee_name,
    e.position,
    COUNT(o.order_id) as total_orders,
    COALESCE(SUM(o.total_amount), 0) as total_sales,
    AVG(o.total_amount) as average_order_value
FROM employees e
LEFT JOIN orders o ON e.employee_id = o.employee_id
GROUP BY e.employee_id, e.first_name, e.last_name, e.position
ORDER BY total_sales DESC;

-- Usage: Employee performance management interface
-- Business Value: Informs staffing decisions and sales strategy
-- Component: Management dashboard, performance reviews, commission calculations


-- =====================================================
-- Report Queries
-- =====================================================

-- Query 6: Sales by Category Report
-- Monthly sales breakdown by product category
SELECT
    c.category_name,
    YEAR(o.order_date) as year,
    MONTHNAME(o.order_date) as month,
    SUM(od.quantity * od.unit_price) as total_sales,
    COUNT(DISTINCT o.order_id) as order_count,
    SUM(od.quantity) as units_sold
FROM orders o
JOIN order_details od ON o.order_id = od.order_id
JOIN products p ON od.product_id = p.product_id
JOIN categories c ON p.category_id = c.category_id
WHERE o.order_status = 'completed'
GROUP BY c.category_name, YEAR(o.order_date), MONTH(o.order_date)
ORDER BY year DESC, month DESC, total_sales DESC;

-- Usage: Monthly sales report by product category for management
-- Business Value: Identifies profitable categories and trends
-- Component: Business intelligence reports, inventory planning, marketing strategy


-- Query 7: Customer Order History
-- Detailed customer order history with status
SELECT
    c.customer_id,
    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
    c.email,
    o.order_id,
    o.order_date,
    o.total_amount,
    o.order_status,
    COUNT(od.detail_id) as item_count,
    CONCAT(e.first_name, ' ', e.last_name) as employee_name
FROM customers c
JOIN orders o ON c.customer_id = o.customer_id
JOIN order_details od ON o.order_id = od.order_id
JOIN employees e ON o.employee_id = e.employee_id
WHERE c.customer_id = ?
GROUP BY o.order_id
ORDER BY o.order_date DESC;

-- Usage: Customer service and account management interface
-- Business Value: Improves customer service and identifies valuable customers
-- Component: Customer relationship management, order history lookup
-- Parameters: Specific customer_id for filtering


-- Query 8: Inventory Status Report
-- Current inventory levels with reorder suggestions
SELECT
    p.product_id,
    p.product_name,
    p.stock_quantity,
    p.price,
    c.category_name,
    CASE
        WHEN p.stock_quantity = 0 THEN 'Out of Stock'
        WHEN p.stock_quantity < 10 THEN 'Low Stock'
        WHEN p.stock_quantity < 25 THEN 'Reorder Soon'
        ELSE 'In Stock'
    END as stock_status,
    COALESCE(SUM(od.quantity), 0) as total_sold
FROM products p
JOIN categories c ON p.category_id = c.category_id
LEFT JOIN order_details od ON p.product_id = od.product_id
LEFT JOIN orders o ON od.order_id = o.order_id AND o.order_status = 'completed'
GROUP BY p.product_id, p.product_name, p.stock_quantity, p.price, c.category_name
ORDER BY
    CASE
        WHEN p.stock_quantity = 0 THEN 1
        WHEN p.stock_quantity < 10 THEN 2
        WHEN p.stock_quantity < 25 THEN 3
        ELSE 4
    END,
    total_sold DESC;

-- Usage: Inventory management dashboard with reorder alerts
-- Business Value: Prevents stockouts and optimizes inventory investment
-- Component: Inventory management system, purchasing decisions, demand forecasting


-- Query 9: Revenue Summary Report
-- Revenue summary by time period
SELECT
    CASE
        WHEN ? = 'daily' THEN DATE(order_date)
        WHEN ? = 'weekly' THEN DATE_SUB(DATE(order_date), INTERVAL WEEKDAY(order_date) DAY)
        WHEN ? = 'monthly' THEN DATE_FORMAT(order_date, '%Y-%m-01')
        WHEN ? = 'yearly' THEN DATE_FORMAT(order_date, '%Y-01-01')
    END as period,
    COUNT(DISTINCT o.order_id) as order_count,
    SUM(o.total_amount) as total_revenue,
    AVG(o.total_amount) as average_order,
    COUNT(DISTINCT o.customer_id) as unique_customers
FROM orders o
WHERE o.order_status = 'completed'
    AND order_date BETWEEN ? AND ?
GROUP BY period
ORDER BY period DESC;

-- Usage: Flexible revenue reporting by daily/weekly/monthly/yearly periods
-- Business Value: Supports data-driven business decisions
-- Component: Financial reporting, business planning, performance tracking
-- Parameters: period_type ('daily', 'weekly', 'monthly', 'yearly'), start_date, end_date


-- Query 10: Top Products Report
-- Best-selling products by quantity and revenue
SELECT
    p.product_id,
    p.product_name,
    p.price,
    c.category_name,
    SUM(od.quantity) as total_quantity_sold,
    SUM(od.line_total) as total_revenue,
    COUNT(DISTINCT od.order_id) as times_ordered,
    AVG(od.unit_price) as average_price
FROM products p
JOIN order_details od ON p.product_id = od.product_id
JOIN categories c ON p.category_id = c.category_id
JOIN orders o ON od.order_id = o.order_id
WHERE o.order_status = 'completed'
    AND o.order_date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)
GROUP BY p.product_id, p.product_name, p.price, c.category_name
ORDER BY total_revenue DESC
LIMIT 20;

-- Usage: Product performance analysis for marketing and inventory
-- Business Value: Identifies successful products and opportunities
-- Component: Product performance dashboard, marketing decisions, inventory planning


-- =====================================================
-- Additional Utility Queries
-- =====================================================

-- Query: Database Setup Verification
-- Check that all tables exist and have data
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

-- Usage: Verify database installation and data integrity
-- Component: Database setup validation, health checks


-- Query: Recent Orders Dashboard View
-- Show recent orders with customer and status information
SELECT
    o.order_id,
    CONCAT(c.first_name, ' ', c.last_name) as customer_name,
    o.order_date,
    o.total_amount,
    o.order_status,
    CONCAT(e.first_name, ' ', e.last_name) as employee_name,
    COUNT(od.detail_id) as item_count
FROM orders o
JOIN customers c ON o.customer_id = c.customer_id
JOIN employees e ON o.employee_id = e.employee_id
LEFT JOIN order_details od ON o.order_id = od.order_id
GROUP BY o.order_id
ORDER BY o.order_date DESC
LIMIT 20;

-- Usage: Main dashboard showing recent order activity
-- Component: Order management interface, sales dashboard


-- =====================================================
-- Query Execution Notes:
-- =====================================================
--
-- 1. All queries are optimized for performance with proper JOIN operations
-- 2. Parameter placeholders (?) indicate where user input should be bound
-- 3. ORDER BY clauses ensure consistent, meaningful result ordering
-- 4. COALESCE functions handle NULL values gracefully in reports
-- 5. CASE statements provide user-friendly status indicators
-- 6. Aggregate functions (COUNT, SUM, AVG) enable business intelligence
-- 7. WHERE clauses filter for relevant data (e.g., completed orders)
-- 8. LIMIT clauses prevent result set overload in user interfaces
--
-- Security Note:
-- Always use prepared statements when executing these queries from PHP
-- to prevent SQL injection attacks. The parameter placeholders (?)
-- should be bound using proper parameter binding mechanisms.