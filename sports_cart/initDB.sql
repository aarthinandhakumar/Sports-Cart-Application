DROP TABLE ORDER_ITEMS;
DROP TABLE ORDERS;
DROP TABLE CUSTOMERS;
DROP TABLE PRODUCTS;


CREATE DATABASE sports_cart;

USE sports_cart;

 CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL
);

 CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);
   CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO products (name, description, price, stock_quantity) VALUES
('Basketball', 'High-quality equipment for outdoor and indoor use.', 29.99, 50),
('Soccer Ball', 'Durable and well suited for training and matches.', 19.99, 75),
('Tennis Racket', 'Lightweight with excellent grip.', 89.99, 20),
('Baseball Gloves', 'Leather gloves for both left and right-handed players.', 59.99, 30),
('Running Shoes', 'Comfortable shoes for all types of terrain.', 69.99, 60),
('Golf Clubs', 'Set of high-quality golf sticks for beginners and pros.', 299.99, 5),
('Yoga Mat', 'Non-slip yoga mat for all types of exercises.', 24.99, 40),
('Swimming Goggles', 'Anti-fog goggles for clear vision underwater.', 14.99, 100),
('Hockey Stick', 'Durable hockey stick for ice and field hockey.', 79.99, 25),
('FitBit', 'Advanced way to track your health and fitness with heart rate monitor.', 149.99, 35),
('Boxing Gloves', 'High-impact boxing gloves for training and matches.', 49.99, 45),
('Ski Poles', 'Adjustable ski poles for downhill skiing.', 39.99, 30);

INSERT INTO customers (name) VALUES
('Ronald Jay'),
('Paige Warner'),
('Charlie Lewis'),
('Catherine Brown'),
('Alexa Morris');

SELECT * FROM products;
SELECT * FROM customers;