-- Création de la base de données
CREATE DATABASE IF NOT EXISTS contact_form;
USE contact_form;

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    screenshot VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);