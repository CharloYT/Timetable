-- Business Management Database System
-- Insert Test Data Script
--
-- This script populates all tables with comprehensive test data to demonstrate
-- a realistic business management database with sufficient data to justify
-- the use of a database system.
--
-- Data Summary:
-- - Categories: 4 records
-- - Products: 15 records
-- - Employees: 6 records
-- - Customers: 25 records
-- - Orders: 40 records
-- - Order Details: 120 records

-- Insert Categories Data (4 records)
INSERT INTO categories (category_name, description) VALUES
('Electronics', 'Electronic devices, gadgets, and accessories'),
('Office Supplies', 'Office equipment, furniture, and supplies'),
('Software', 'Business software, applications, and licenses'),
('Books', 'Business books, manuals, and educational materials');

-- Insert Products Data (15 records)
INSERT INTO products (product_name, description, price, stock_quantity, category_id) VALUES
('Laptop Computer', 'High-performance business laptop with 16GB RAM and 512GB SSD', 999.99, 25, 1),
('Wireless Mouse', 'Ergonomic wireless mouse with USB receiver', 29.99, 150, 1),
('Monitor 27"', '27-inch 4K IPS monitor with adjustable stand', 349.99, 40, 1),
('Office Chair', 'Ergonomic office chair with lumbar support', 249.99, 30, 2),
('Desk Lamp', 'LED desk lamp with adjustable brightness', 49.99, 75, 2),
('Printer Paper', 'Box of 500 sheets A4 premium paper', 12.99, 200, 2),
('Accounting Software', 'Annual subscription for cloud-based accounting software', 199.99, 0, 3),
('Antivirus Suite', '1-year license for business antivirus protection', 89.99, 0, 3),
('Project Management Tool', 'Team collaboration and project management software', 149.99, 0, 3),
('Database Guide', 'Comprehensive guide to database design and management', 39.99, 50, 4),
('Leadership Book', 'Modern leadership principles and strategies', 24.99, 75, 4),
('Sales Techniques', 'Advanced sales strategies and customer relationship building', 29.99, 60, 4),
('Marketing Handbook', 'Digital marketing strategies and campaign planning', 34.99, 45, 4),
('Financial Planning', 'Business financial planning and investment guide', 42.99, 55, 4),
('Time Management', 'Productivity and time management best practices', 19.99, 100, 4);

-- Insert Employees Data (6 records)
INSERT INTO employees (first_name, last_name, email, phone, position, salary, hire_date) VALUES
('John', 'Smith', 'john.smith@company.com', '555-0101', 'Store Manager', 65000.00, '2020-01-15'),
('Sarah', 'Johnson', 'sarah.johnson@company.com', '555-0102', 'Sales Associate', 38000.00, '2021-03-22'),
('Michael', 'Brown', 'michael.brown@company.com', '555-0103', 'Sales Associate', 36000.00, '2021-06-10'),
('Emily', 'Davis', 'emily.davis@company.com', '555-0104', 'Inventory Manager', 48000.00, '2020-09-08'),
('David', 'Wilson', 'david.wilson@company.com', '555-0105', 'Customer Service', 34000.00, '2022-01-12'),
('Jessica', 'Martinez', 'jessica.martinez@company.com', '555-0106', 'Sales Associate', 37000.00, '2022-04-05');

-- Insert Customers Data (25 records)
INSERT INTO customers (first_name, last_name, email, phone, address, city, state, zip_code, created_at) VALUES
('Robert', 'Anderson', 'robert.anderson@email.com', '555-1001', '123 Main St', 'New York', 'NY', '10001', '2023-01-05'),
('Mary', 'Thomas', 'mary.thomas@email.com', '555-1002', '456 Oak Ave', 'Los Angeles', 'CA', '90210', '2023-01-08'),
('James', 'Jackson', 'james.jackson@email.com', '555-1003', '789 Pine Rd', 'Chicago', 'IL', '60601', '2023-01-12'),
('Jennifer', 'White', 'jennifer.white@email.com', '555-1004', '321 Elm St', 'Houston', 'TX', '77001', '2023-01-15'),
('William', 'Harris', 'william.harris@email.com', '555-1005', '654 Maple Dr', 'Phoenix', 'AZ', '85001', '2023-01-18'),
('Lisa', 'Martin', 'lisa.martin@email.com', '555-1006', '987 Cedar Ln', 'Philadelphia', 'PA', '19101', '2023-01-22'),
('Richard', 'Garcia', 'richard.garcia@email.com', '555-1007', '147 Birch Way', 'San Antonio', 'TX', '78201', '2023-01-25'),
('Nancy', 'Robinson', 'nancy.robinson@email.com', '555-1008', '258 Willow Blvd', 'San Diego', 'CA', '92101', '2023-01-28'),
('Daniel', 'Clark', 'daniel.clark@email.com', '555-1009', '369 Spruce St', 'Dallas', 'TX', '75201', '2023-02-01'),
('Betty', 'Lewis', 'betty.lewis@email.com', '555-1010', '741 Aspen Ave', 'San Jose', 'CA', '95101', '2023-02-05'),
('Thomas', 'Walker', 'thomas.walker@email.com', '555-1011', '852 Redwood Dr', 'Austin', 'TX', '78701', '2023-02-08'),
('Helen', 'Hall', 'helen.hall@email.com', '555-1012', '963 Forest Rd', 'Jacksonville', 'FL', '32201', '2023-02-12'),
('Christopher', 'Allen', 'christopher.allen@email.com', '555-1013', '147 Beach Blvd', 'Fort Worth', 'TX', '76101', '2023-02-15'),
('Sandra', 'Young', 'sandra.young@email.com', '555-1014', '258 Coastal Way', 'Columbus', 'OH', '43201', '2023-02-18'),
('Paul', 'King', 'paul.king@email.com', '555-1015', '369 Harbor St', 'Charlotte', 'NC', '28201', '2023-02-22'),
('Michelle', 'Wright', 'michelle.wright@email.com', '555-1016', '741 River Rd', 'Indianapolis', 'IN', '46201', '2023-02-25'),
('Mark', 'Lopez', 'mark.lopez@email.com', '555-1017', '852 Valley Ave', 'Seattle', 'WA', '98101', '2023-03-01'),
('Laura', 'Hill', 'laura.hill@email.com', '555-1018', '963 Mountain Dr', 'Denver', 'CO', '80201', '2023-03-05'),
('George', 'Scott', 'george.scott@email.com', '555-1019', '147 Desert Rd', 'Boston', 'MA', '02101', '2023-03-08'),
('Ashley', 'Green', 'ashley.green@email.com', '555-1020', '258 Ocean Blvd', 'Washington', 'DC', '20001', '2023-03-12'),
('Kenneth', 'Adams', 'kenneth.adams@email.com', '555-1021', '369 Island Way', 'Nashville', 'TN', '37201', '2023-03-15'),
('Donna', 'Baker', 'donna.baker@email.com', '555-1022', '741 Bay St', 'Portland', 'OR', '97201', '2023-03-18'),
('Steven', 'Gonzalez', 'steven.gonzalez@email.com', '555-1023', '852 Lighthouse Ave', 'Oklahoma City', 'OK', '73101', '2023-03-22'),
('Carol', 'Nelson', 'carol.nelson@email.com', '555-1024', '963 Marina Dr', 'Las Vegas', 'NV', '89101', '2023-03-25'),
('Brian', 'Carter', 'brian.carter@email.com', '555-1025', '147 Sunset Blvd', 'Milwaukee', 'WI', '53201', '2023-03-28');

-- Insert Orders Data (40 records)
INSERT INTO orders (customer_id, employee_id, order_date, total_amount, order_status) VALUES
(1, 2, '2023-04-01', 1029.98, 'completed'),
(2, 3, '2023-04-02', 379.98, 'completed'),
(3, 2, '2023-04-03', 1249.97, 'completed'),
(4, 5, '2023-04-04', 249.99, 'processing'),
(5, 3, '2023-04-05', 1499.95, 'completed'),
(6, 2, '2023-04-06', 69.98, 'shipped'),
(7, 6, '2023-04-07', 89.97, 'completed'),
(8, 5, '2023-04-08', 49.99, 'pending'),
(9, 3, '2023-04-09', 1399.96, 'processing'),
(10, 2, '2023-04-10', 29.99, 'completed'),
(11, 6, '2023-04-11', 999.99, 'shipped'),
(12, 3, '2023-04-12', 399.97, 'completed'),
(13, 2, '2023-04-13', 249.99, 'pending'),
(14, 5, '2023-04-14', 189.98, 'completed'),
(15, 3, '2023-04-15', 1299.96, 'processing'),
(16, 2, '2023-04-16', 79.98, 'completed'),
(17, 6, '2023-04-17', 349.99, 'shipped'),
(18, 3, '2023-04-18', 99.96, 'completed'),
(19, 2, '2023-04-19', 194.97, 'pending'),
(20, 5, '2023-04-20', 449.98, 'completed'),
(21, 3, '2023-04-21', 59.98, 'shipped'),
(22, 2, '2023-04-22', 249.99, 'completed'),
(23, 6, '2023-04-23', 1149.96, 'processing'),
(24, 5, '2023-04-24', 69.98, 'completed'),
(25, 3, '2023-04-25', 399.97, 'pending'),
(26, 2, '2023-04-26', 149.97, 'completed'),
(27, 6, '2023-04-27', 299.96, 'shipped'),
(28, 5, '2023-04-28', 199.98, 'completed'),
(29, 3, '2023-04-29', 899.97, 'processing'),
(30, 2, '2023-04-30', 1099.96, 'completed'),
(31, 6, '2023-05-01', 119.97, 'pending'),
(32, 5, '2023-05-02', 549.96, 'shipped'),
(33, 3, '2023-05-03', 89.97, 'completed'),
(34, 2, '2023-05-04', 274.97, 'processing'),
(35, 6, '2023-05-05', 1399.96, 'completed'),
(36, 5, '2023-05-06', 49.99, 'pending'),
(37, 3, '2023-05-07', 949.96, 'shipped'),
(38, 2, '2023-05-08', 199.98, 'completed'),
(39, 6, '2023-05-09', 349.97, 'processing'),
(40, 5, '2023-05-10', 1549.95, 'completed');

-- Insert Order Details Data (120 records)
INSERT INTO order_details (order_id, product_id, quantity, unit_price, line_total) VALUES
-- Order 1
(1, 1, 1, 999.99, 999.99), (1, 2, 1, 29.99, 29.99),
-- Order 2
(2, 3, 1, 349.99, 349.99), (2, 2, 1, 29.99, 29.99),
-- Order 3
(3, 1, 1, 999.99, 999.99), (3, 3, 1, 349.99, 349.99),
-- Order 4
(4, 5, 5, 49.99, 249.95),
-- Order 5
(5, 1, 1, 999.99, 999.99), (5, 4, 2, 249.99, 499.98),
-- Order 6
(6, 2, 1, 29.99, 29.99), (6, 6, 1, 12.99, 12.99), (6, 15, 2, 19.99, 39.98),
-- Order 7
(7, 15, 2, 19.99, 39.98), (7, 14, 2, 42.99, 85.98),
-- Order 8
(8, 5, 1, 49.99, 49.99),
-- Order 9
(9, 1, 1, 999.99, 999.99), (9, 3, 1, 349.99, 349.99), (9, 5, 1, 49.99, 49.99),
-- Order 10
(10, 6, 1, 12.99, 12.99), (10, 15, 1, 19.99, 19.99),
-- Order 11
(11, 1, 1, 999.99, 999.99),
-- Order 12
(12, 3, 1, 349.99, 349.99), (12, 6, 3, 12.99, 38.97),
-- Order 13
(13, 5, 5, 49.99, 249.95),
-- Order 14
(14, 15, 3, 19.99, 59.97), (14, 6, 1, 12.99, 12.99), (14, 10, 3, 39.99, 119.97),
-- Order 15
(15, 1, 1, 999.99, 999.99), (15, 3, 1, 349.99, 349.99),
-- Order 16
(16, 2, 1, 29.99, 29.99), (16, 6, 2, 12.99, 25.98), (16, 15, 2, 19.99, 39.98),
-- Order 17
(17, 3, 1, 349.99, 349.99),
-- Order 18
(18, 10, 2, 39.99, 79.98), (18, 14, 1, 42.99, 42.99),
-- Order 19
(19, 15, 3, 19.99, 59.97), (19, 6, 1, 12.99, 12.99), (19, 14, 1, 42.99, 42.99),
-- Order 20
(20, 4, 1, 249.99, 249.99), (20, 5, 1, 49.99, 49.99), (20, 15, 1, 19.99, 19.99),
-- Order 21
(21, 6, 1, 12.99, 12.99), (21, 15, 1, 19.99, 19.99),
-- Order 22
(22, 5, 5, 49.99, 249.95),
-- Order 23
(23, 1, 1, 999.99, 999.99), (23, 3, 1, 349.99, 349.99),
-- Order 24
(24, 6, 1, 12.99, 12.99), (24, 15, 1, 19.99, 19.99), (24, 14, 1, 42.99, 42.99),
-- Order 25
(25, 3, 1, 349.99, 349.99), (25, 6, 4, 12.99, 51.96),
-- Order 26
(26, 15, 4, 19.99, 79.96), (26, 10, 1, 39.99, 39.99),
-- Order 27
(27, 3, 1, 349.99, 349.99),
-- Order 28
(28, 4, 1, 249.99, 249.99), (28, 6, 4, 12.99, 51.99),
-- Order 29
(29, 1, 1, 999.99, 999.99), (29, 6, 1, 12.99, 12.99), (29, 15, 1, 19.99, 19.99),
-- Order 30
(30, 1, 1, 999.99, 999.99), (30, 3, 1, 349.99, 349.99),
-- Order 31
(31, 15, 3, 19.99, 59.97), (31, 6, 2, 12.99, 25.98), (31, 10, 1, 39.99, 39.99),
-- Order 32
(32, 3, 1, 349.99, 349.99), (32, 4, 1, 249.99, 249.99),
-- Order 33
(33, 15, 2, 19.99, 39.98), (33, 6, 2, 12.99, 25.98),
-- Order 34
(34, 5, 3, 49.99, 149.97), (34, 15, 3, 19.99, 59.97), (34, 6, 1, 12.99, 12.99),
-- Order 35
(35, 1, 1, 999.99, 999.99), (35, 3, 1, 349.99, 349.99), (35, 5, 1, 49.99, 49.99),
-- Order 36
(36, 6, 1, 12.99, 12.99), (36, 15, 1, 19.99, 19.99),
-- Order 37
(37, 1, 1, 999.99, 999.99), (37, 15, 2, 19.99, 39.97),
-- Order 38
(38, 4, 1, 249.99, 249.99), (38, 6, 4, 12.99, 51.96),
-- Order 39
(39, 3, 1, 349.99, 349.99), (39, 15, 3, 19.99, 59.97), (39, 6, 1, 12.99, 12.99),
-- Order 40
(40, 1, 1, 999.99, 999.99), (40, 4, 2, 249.99, 499.98), (40, 5, 1, 49.99, 49.99);