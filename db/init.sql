CREATE DATABASE IF NOT EXISTS shop;
USE shop;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL
);

INSERT INTO products (name, description, price) VALUES
('Sản phẩm 1', 'Mô tả sản phẩm 1', 100000),
('Sản phẩm 2', 'Mô tả sản phẩm 2', 150000),
('Sản phẩm 3', 'Mô tả sản phẩm 3', 200000);
