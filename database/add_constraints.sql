-- Business Management Database System
-- Add Constraints Script
--
-- This script adds PRIMARY KEY and FOREIGN KEY constraints to all tables.
-- Execute this AFTER create_tables.sql has been executed successfully.

-- Primary Key constraints
ALTER TABLE customers ADD CONSTRAINT pk_customers PRIMARY KEY (customer_id);
ALTER TABLE employees ADD CONSTRAINT pk_employees PRIMARY KEY (employee_id);
ALTER TABLE categories ADD CONSTRAINT pk_categories PRIMARY KEY (category_id);
ALTER TABLE products ADD CONSTRAINT pk_products PRIMARY KEY (product_id);
ALTER TABLE orders ADD CONSTRAINT pk_orders PRIMARY KEY (order_id);
ALTER TABLE order_details ADD CONSTRAINT pk_order_details PRIMARY KEY (detail_id);

-- Foreign Key constraints

-- Products -> Categories relationship
ALTER TABLE products
ADD CONSTRAINT fk_products_category
FOREIGN KEY (category_id) REFERENCES categories(category_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

-- Orders -> Customers relationship
ALTER TABLE orders
ADD CONSTRAINT fk_orders_customer
FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

-- Orders -> Employees relationship
ALTER TABLE orders
ADD CONSTRAINT fk_orders_employee
FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
ON UPDATE CASCADE ON DELETE RESTRICT;

-- Order Details -> Orders relationship (CASCADE DELETE)
ALTER TABLE order_details
ADD CONSTRAINT fk_order_details_order
FOREIGN KEY (order_id) REFERENCES orders(order_id)
ON UPDATE CASCADE ON DELETE CASCADE;

-- Order Details -> Products relationship
ALTER TABLE order_details
ADD CONSTRAINT fk_order_details_product
FOREIGN KEY (product_id) REFERENCES products(product_id)
ON UPDATE CASCADE ON DELETE RESTRICT;