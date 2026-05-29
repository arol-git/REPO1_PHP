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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table des articles de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Insertion admin par défaut (mot de passe: admin123)
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@datalab-tech.com', 'admin');


-- Vérifier si la colonne created_at existe
ALTER TABLE products ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Mettre à jour les dates des produits existants (optionnel)
UPDATE products SET created_at = DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 30) DAY) WHERE created_at IS NULL;


-- Insertion des produits de démonstration
INSERT INTO products (name, description, price, category, image, stock, featured, new) VALUES
('Chargeur Rapide 65W GaN', 'Chargeur USB-C GaN 65W avec technologie de charge rapide, compatible avec tous les appareils. Charge complète en 30 minutes.', 29.99, 'chargeurs', 'charger1.jpg', 25, TRUE, TRUE),
('Écouteurs TWS Pro', 'Écouteurs Bluetooth 5.3 avec réduction de bruit active, autonomie 30h, étui de charge sans fil.', 49.99, 'ecouteurs', 'earbuds1.jpg', 15, TRUE, FALSE),
('Câble USB-C Tressé 2m', 'Câble USB-C tressé résistant, charge rapide 100W, transfert de données jusqu\'à 10Gbps.', 12.99, 'cables', 'cable1.jpg', 50, FALSE, TRUE),
('Power Bank 20000mAh', 'Batterie externe 20000mAh avec charge rapide 22.5W, double port USB, écran LED.', 39.99, 'powerbanks', 'powerbank1.jpg', 20, TRUE, FALSE),
('Support Magnétique MagSafe', 'Support magnétique pour voiture, rotation 360°, charge sans fil 15W.', 19.99, 'accessoires', 'holder1.jpg', 30, FALSE, TRUE),
('Chargeur Voiture 45W', 'Chargeur allume-cigare 45W avec 2 ports USB-C, charge rapide compatible PD et QC.', 24.99, 'chargeurs', 'carcharger1.jpg', 18, FALSE, FALSE),
('Casque Audio HD Pro', 'Casque circum-aural avec réduction de bruit active, batterie 40h, pliable pour voyage.', 79.99, 'ecouteurs', 'headphone1.jpg', 12, TRUE, TRUE),
('Station Charge 3-en-1', 'Station de charge sans fil pour iPhone, Apple Watch et AirPods. Charge rapide 15W.', 59.99, 'accessoires', 'chargingstation.jpg', 8, TRUE, FALSE),
('Enceinte Bluetooth Portable', 'Enceinte Bluetooth 20W, étanche IPX7, batterie 24h, son stéréo HD.', 89.99, 'ecouteurs', 'speaker1.jpg', 10, TRUE, TRUE),
('Montre Connectée Ultra', 'Montre connectée avec GPS, suivi sportif, fréquence cardiaque, batterie 7 jours.', 129.99, 'accessoires', 'smartwatch1.jpg', 15, TRUE, FALSE);