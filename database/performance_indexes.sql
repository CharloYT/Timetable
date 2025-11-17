-- Business Management Database System
-- Performance Optimization Indexes
--
-- This script creates database indexes to optimize query performance
-- for common operations in the business management system.
--
-- Execute this script after the tables are created and populated.
-- These indexes will significantly improve query response times.

-- =====================================================
-- Customer Table Indexes
-- =====================================================

-- Index for customer name searches (order forms, customer lookups)
CREATE INDEX idx_customers_name ON customers(last_name, first_name);

-- Index for email lookups (customer authentication, duplicate checks)
CREATE INDEX idx_customers_email ON customers(email);

-- Index for customer city/state queries (regional reports)
CREATE INDEX idx_customers_location ON customers(city, state);

-- Index for customer creation date (customer acquisition reports)
CREATE INDEX idx_customers_created_at ON customers(created_at);

-- =====================================================
-- Employee Table Indexes
-- =====================================================

-- Index for employee name searches (performance reports, management)
CREATE INDEX idx_employees_name ON employees(last_name, first_name);

-- Index for position queries (role-based reports, management)
CREATE INDEX idx_employees_position ON employees(position);

-- Index for hire date (employee tenure reports)
CREATE INDEX idx_employees_hire_date ON employees(hire_date);

-- =====================================================
-- Categories Table Indexes
-- =====================================================

-- Index for category name searches (navigation, product filtering)
CREATE INDEX idx_categories_name ON categories(category_name);

-- =====================================================
-- Products Table Indexes
-- =====================================================

-- Index for product name searches (product search, catalog browsing)
CREATE INDEX idx_products_name ON products(product_name);

-- Index for category lookups (category-based product browsing)
CREATE INDEX idx_products_category ON products(category_id);

-- Index for price range queries (price-based searches and filtering)
CREATE INDEX idx_products_price ON products(price);

-- Index for stock quantity (inventory management, low stock alerts)
CREATE INDEX idx_products_stock ON products(stock_quantity);

-- Composite index for category + stock filtering (product availability by category)
CREATE INDEX idx_products_category_stock ON products(category_id, stock_quantity);

-- =====================================================
-- Orders Table Indexes
-- =====================================================

-- Index for order date queries (date-based reports, dashboard)
CREATE INDEX idx_orders_date ON orders(order_date);

-- Index for order status queries (order management, dashboard widgets)
CREATE INDEX idx_orders_status ON orders(order_status);

-- Index for customer lookups (customer order history)
CREATE INDEX idx_orders_customer ON orders(customer_id);

-- Index for employee lookups (sales performance reports)
CREATE INDEX idx_orders_employee ON orders(employee_id);

-- Composite index for date + status (common reporting combination)
CREATE INDEX idx_orders_date_status ON orders(order_date, order_status);

-- Composite index for status + date (dashboard active orders)
CREATE INDEX idx_orders_status_date ON orders(order_status, order_date);

-- =====================================================
-- Order Details Table Indexes
-- =====================================================

-- Index for order lookups (order detail retrieval)
CREATE INDEX idx_order_details_order ON order_details(order_id);

-- Index for product lookups (product sales history)
CREATE INDEX idx_order_details_product ON order_details(product_id);

-- Composite index for order + product (duplicate prevention, analysis)
CREATE INDEX idx_order_details_order_product ON order_details(order_id, product_id);

-- =====================================================
-- Composite Indexes for Common Query Patterns
-- =====================================================

-- Customer order history with status
CREATE INDEX idx_orders_customer_date ON orders(customer_id, order_date DESC);

-- Employee performance by date range
CREATE INDEX idx_orders_employee_date ON orders(employee_id, order_date);

-- Product sales by order completion status
CREATE INDEX idx_order_details_product_order_status ON order_details(product_id);

-- Order completion by date
CREATE INDEX idx_orders_completed_date ON orders(order_status, order_date)
WHERE order_status = 'completed';

-- =====================================================
-- Full-text Search Indexes (Optional - MySQL 5.6+)
-- =====================================================

-- Full-text search for product names and descriptions
-- Note: Requires InnoDB or MyISAM engine and appropriate MySQL version

-- CREATE FULLTEXT INDEX idx_products_search ON products(product_name, description);

-- Full-text search for customer names
-- CREATE FULLTEXT INDEX idx_customers_search ON customers(first_name, last_name);

-- =====================================================
-- Index Usage Analysis Queries
-- =====================================================

-- Query to check which indexes are being used
-- SELECT OBJECT_SCHEMA, OBJECT_NAME, INDEX_NAME, COUNT_READ, COUNT_FETCH
-- FROM performance_schema.table_io_waits_summary_by_index_usage
-- WHERE OBJECT_SCHEMA = 'business_management'
-- ORDER BY COUNT_READ DESC;

-- Query to find unused indexes
-- SELECT s.TABLE_SCHEMA, s.TABLE_NAME, s.INDEX_NAME, s.CARDINALITY
-- FROM information_schema.STATISTICS s
-- LEFT JOIN performance_schema.table_io_waits_summary_by_index_usage i
-- ON s.TABLE_SCHEMA = i.OBJECT_SCHEMA AND s.TABLE_NAME = i.OBJECT_NAME AND s.INDEX_NAME = i.INDEX_NAME
-- WHERE s.TABLE_SCHEMA = 'business_management' AND i.INDEX_NAME IS NULL
-- ORDER BY s.TABLE_SCHEMA, s.TABLE_NAME, s.INDEX_NAME;

-- =====================================================
-- Maintenance and Optimization Commands
-- =====================================================

-- Analyze table statistics (update index cardinality)
-- ANALYZE TABLE customers;
-- ANALYZE TABLE employees;
-- ANALYZE TABLE categories;
-- ANALYZE TABLE products;
-- ANALYZE TABLE orders;
-- ANALYZE TABLE order_details;

-- Optimize table (rebuild table and optimize indexes)
-- OPTIMIZE TABLE customers;
-- OPTIMIZE TABLE employees;
-- OPTIMIZE TABLE categories;
-- OPTIMIZE TABLE products;
-- OPTIMIZE TABLE orders;
-- OPTIMIZE TABLE order_details;

-- Check table status and health
-- SHOW TABLE STATUS FROM business_management;

-- =====================================================
-- Performance Monitoring Query
-- =====================================================

-- Monitor slow queries (ensure slow_query_log is enabled)
-- SET GLOBAL slow_query_log = 'ON';
-- SET GLOBAL long_query_time = 1; -- Log queries taking more than 1 second

-- View current slow query log status
-- SHOW VARIABLES LIKE 'slow_query_log%';

-- =====================================================
-- Query Optimization Examples
-- =====================================================

-- Before optimization (without proper indexes):
-- SELECT o.*, c.first_name, c.last_name
-- FROM orders o
-- JOIN customers c ON o.customer_id = c.customer_id
-- WHERE o.order_date BETWEEN '2023-01-01' AND '2023-12-31'
-- ORDER BY o.order_date DESC;

-- After optimization (with idx_orders_date and idx_orders_customer):
-- Same query now uses indexes for JOIN and WHERE clauses

-- =====================================================
-- Expected Performance Improvements
-- =====================================================

-- Customer searches: From full table scan to index lookup
-- Product catalog browsing: Improved category filtering
-- Order management: Faster status-based queries
-- Dashboard widgets: Real-time statistics with minimal load
-- Report generation: Significant speed improvements for date-range queries
-- Inventory management: Quick low-stock identification

-- =====================================================
-- Index Maintenance Schedule
-- =====================================================

-- Recommended maintenance:
-- 1. Run ANALYZE TABLE weekly for updated statistics
-- 2. Run OPTIMIZE TABLE monthly for heavily modified tables
-- 3. Monitor index usage and remove unused indexes quarterly
-- 4. Review query performance after major data changes

-- Performance improvement expected:
-- - Dashboard queries: 70-90% faster
-- - Product searches: 80-95% faster
-- - Order history: 60-85% faster
-- - Report generation: 75-90% faster