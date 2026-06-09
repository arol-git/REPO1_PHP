CREATE DATABASE IF NOT EXISTS ecommerce_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ecommerce_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    stock INT NOT NULL DEFAULT 10,
    featured BOOLEAN NOT NULL DEFAULT FALSE,
    `new` BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_products_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    total DECIMAL(10, 2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Paiement à la livraison',
    shipping_info TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (username, password, email, role)
VALUES ('admin', '$2y$10$btuv9i10dH1pml4E3yd8LOzA8KRXy2O7DZl1KSSzbFnH4VQ54zGyO', 'admin@datalab-tech.com', 'admin')
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    role = VALUES(role);

INSERT INTO products (name, description, price, category, image, stock, featured, `new`) VALUES
('Chargeur Rapide 65W GaN', 'Chargeur USB-C GaN 65W avec technologie de charge rapide, compatible avec tous les appareils.', 29.99, 'chargeurs', 'chargeur1.jpg', 25, TRUE, TRUE),
('Écouteurs TWS Pro', 'Écouteurs Bluetooth avec réduction de bruit actif et étui de charge.', 49.99, 'ecouteurs', 'earbuds1.jpg', 15, TRUE, FALSE),
('Câble USB-C Tressé 2m', 'Câble USB-C tressé résistant avec charge rapide.', 12.99, 'cables', 'cable1.jpg', 50, FALSE, TRUE),
('Power Bank 20000mAh', 'Batterie externe avec charge rapide et double port USB.', 39.99, 'powerbanks', 'powerbank1.jpg', 20, TRUE, FALSE),
('Support Magnétique MagSafe', 'Support magnétique pour voiture avec rotation 360°.', 19.99, 'accessoires', 'stand.jpg', 30, FALSE, TRUE),
('Chargeur Voiture 45W', 'Chargeur allume-cigare 45W avec ports USB-C.', 24.99, 'chargeurs', 'carcharger1.jpg', 18, FALSE, FALSE),
('Casque Audio HD Pro', 'Casque circum-aural avec réduction de bruit active.', 79.99, 'ecouteurs', 'headphone1.jpg', 12, TRUE, TRUE),
('Station Charge 3-en-1', 'Station de charge sans fil pour téléphone, montre et écouteurs.', 59.99, 'accessoires', 'chargingstation.jpg', 8, TRUE, FALSE),
('Enceinte Bluetooth Portable', 'Enceinte Bluetooth étanche avec son stéréo.', 89.99, 'ecouteurs', 'speaker1.jpg', 10, TRUE, TRUE)
ON DUPLICATE KEY UPDATE
    name = VALUES(name);
