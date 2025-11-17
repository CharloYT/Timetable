-- Business Management Database System
-- Create Tables Script
--
-- This script creates all necessary tables for the business management system:
-- - customers: Customer demographic and contact information
-- - employees: Employee information and roles
-- - categories: Product categories for organization
-- - products: Product catalog with pricing and inventory
-- - orders: Customer orders with status tracking
-- - order_details: Line items for each order

-- Create customers table
CREATE TABLE customers (
    customer_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(50),
    zip_code VARCHAR(20),
    created_at DATE NOT NULL DEFAULT CURRENT_DATE
);

-- Create employees table
CREATE TABLE employees (
    employee_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    position VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2),
    hire_date DATE NOT NULL DEFAULT CURRENT_DATE
);

-- Create categories table
CREATE TABLE categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT
);

-- Create products table
CREATE TABLE products (
    product_id INT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    category_id INT NOT NULL
);

-- Create orders table
CREATE TABLE orders (
    order_id INT NOT NULL AUTO_INCREMENT,
    customer_id INT NOT NULL,
    employee_id INT NOT NULL,
    order_date DATE NOT NULL DEFAULT CURRENT_DATE,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    order_status VARCHAR(50) NOT NULL DEFAULT 'pending'
);

-- Create order_details table
CREATE TABLE order_details (
    detail_id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    line_total DECIMAL(10,2) NOT NULL
);