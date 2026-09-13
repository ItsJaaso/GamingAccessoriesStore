-- Joel Elizee
-- SDC310L Course Project
-- Gaming Accessories Store Database

CREATE DATABASE IF NOT EXISTS gaming_store;

USE gaming_store;

-- Products available in the store
CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(100) NOT NULL,
    ProductDescription VARCHAR(255) NOT NULL,
    ProductCost DECIMAL(10,2) NOT NULL
);

-- Customer orders
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    Subtotal DECIMAL(10,2) NOT NULL,
    Tax DECIMAL(10,2) NOT NULL,
    Shipping DECIMAL(10,2) NOT NULL,
    OrderTotal DECIMAL(10,2) NOT NULL
);

-- Products connected to each order
CREATE TABLE OrderItems (
    OrderItemID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    ProductCost DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- Starter products for the catalog
INSERT INTO Products (ProductName, ProductDescription, ProductCost)
VALUES
('Mechanical Gaming Keyboard', 'RGB mechanical keyboard designed for gaming.', 79.99),
('Gaming Mouse', 'Lightweight gaming mouse with programmable buttons.', 49.99),
('Wireless Gaming Headset', 'Wireless headset with surround sound and microphone.', 89.99),
('RGB Mouse Pad', 'Large gaming mouse pad with RGB lighting.', 29.99),
('USB Gaming Controller', 'USB controller compatible with PC games.', 39.99);