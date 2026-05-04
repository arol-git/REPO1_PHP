-- Création de la base de données
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    image VARCHAR(255),
    stock INT DEFAULT 10,
    featured BOOLEAN DEFAULT FALSE,
    new BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total DECIMAL(10, 2),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des articles de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertion d'un admin par défaut (mot de passe: admin123)
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin');

-- Insertion de produits de démonstration
INSERT INTO products (name, description, price, category, image, stock, featured, new) VALUES
('Chargeur Rapide 65W GaN', 'Chargeur USB-C GaN 65W avec technologie de charge rapide, compatible avec tous les appareils', 29.99, 'chargeurs', 'charger1.jpg', 25, TRUE, TRUE),
('Écouteurs TWS Pro', 'Écouteurs Bluetooth 5.3 avec réduction de bruit active, autonomie 30h', 49.99, 'ecouteurs', 'earbuds1.jpg', 15, TRUE, FALSE),
('Câble USB-C 2m', 'Câble USB-C tressé résistant, charge rapide 100W, transfert de données', 12.99, 'cables', 'cable1.jpg', 50, FALSE, TRUE),
('Power Bank 20000mAh', 'Batterie externe 20000mAh avec charge rapide 22.5W, double port USB', 39.99, 'powerbanks', 'powerbank1.jpg', 20, TRUE, FALSE),
('Support Magnétique MagSafe', 'Support magnétique pour voiture, rotation 360°, charge sans fil', 19.99, 'accessoires', 'holder1.jpg', 30, FALSE, TRUE),
('Chargeur Voiture 45W', 'Chargeur allume-cigare 45W avec 2 ports USB-C', 24.99, 'chargeurs', 'carcharger1.jpg', 18, FALSE, FALSE);