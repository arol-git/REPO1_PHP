-- Création de la base de données
CRÉER UNE BASE DE DONNÉES SI NON EXISTE Ecommerce_db;
UTILISEZ ecommerce_db;

-- Table des utilisateurs
CRÉER UNE TABLE SI CE N'EST PAS EXISTER (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom d'utilisateur VARCHAR(50) UNIQUE NOT NULL,
    mot de passe VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') PAR DÉFAUT 'utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CRÉER UNE TABLE SI NON EXISTE DES produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL,
    description TEXTE,
    prix DECIMAL(10, 2) NOT NULL,
    catégorie VARCHAR(50),
    image VARCHAR(255),
    stock IN PAR DÉFAUT 10,
    En vedette BOOLEAN DEFAULT FALSE,
    nouveau BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CRÉER UNE TABLE SI CE N'EST PAS EXISTER DES Ordres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    DÉCIMAL total(10, 2),
    statut VARCHAR(50) PAR DÉFAUT 'en attente',
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) RÉFÉRENCES utilisateurs(id) SUR SUPPRIMER SET SET NULL
);

-- Table des articles de commande
CRÉER UNE TABLE SI CE N'EST PAS EXISTER order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantité INT,
    Prix DECIMAL(10, 2),
    CLÉ ÉTRANGÈRE (order_id) RÉFÉRENCES ordres(id) SUR SUPPRIMER CASCADE,
    CLÉ ÉTRANGÈRE (product_id) RÉFÉRENCES produits(id) SUR SUPPRIMER SET NULL
);

-- Insertion admin par (mot de passe: admin123)
INSÉRER VERS LES utilisateurs (nom d'utilisateur, mot de passe, email, rôle) VALEURS 
('admin', '$2y$10$92IXUNkKO00OQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@datalab-tech.com', 'admin');


--Vérifier si la colonne créé_at existe
ALTER TABLE AJOUTER LA COLONNE SI CE N'EST PAS EXISTER create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Met à jour les dates des produits existants (optionnel)
UPDATE produits SET created_at = DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 30) DAY) OÙ create_at EST NULL;


-- Insertion des produits de démonstration
INSÉRER EN produits (nom, description, prix, catégorie, image, stock, en vedette, nouveau) VALEURS
('Chargeur Rapide 65W GaN', 'Chargeur USB-C GaN 65W avec technologie de charge rapide, compatible avec tous les appareils. Charge complète en 30 minutes.', 29.99, 'chargeurs', 'charger1.jpg', 25, TRUE, TRUE),
('Écouteurs TWS Pro', 'Écouteurs Bluetooth 5.3 avec réduction de bruit actif, autonomie 30h, étui de charge sans fil.', 49.99, 'ecouteurs', 'earbuds1.jpg', 15, TRUE, FAUX),
('Câble USB-C Tressé 2m', 'Câble USB-C tressé résistant, charge rapide 100W, transfert de données jusqu'à 10Gbps.', 12.99, 'câbles', 'cable1.jpg', 50, FAUX, VRAI),
('Power Bank 20000mAh', 'Batterie externe 20000mAh avec charge rapide 22.5W, double port USB, LED écran.', 39.99, 'powerbanks', 'powerbank1.jpg', 20, TRUE, FAUX),
(«Support Magnétique MagSafe», «Support magnétique pour voiture, rotation 360°, charge sans fil 15W.», 19,99, «accessoires», «holder1.jpg», 30, FAUX, TRUE),
('Chargeur Voiture 45W', 'Chargeur allumaille -cigare 45W avec 2 ports USB-C, compatible de charge rapide PD et QC.', 24.99, 'chargeurs', 'carcharger1.jpg', 18, FAUX, FAUX),
('Casque Audio HD Pro', 'Casque circum-aural réduction de bruit active, batterie 40h, pliable pour voyage', 79.99, 'ecouteurs', 'headphone1.jpg', 12, TRUE, TRUE),
('Station Charge 3-en-1', 'Station de charge sans fil pour iPhone, Apple Watch et AirPods. Charge rapide 15W.', 59.99, 'accessoires', 'chargestation.jpg', 8, TRUE, FAUX),
('Enceinte Bluetooth Portable', 'Enceinte Bluetooth 20W, étanche IPX7, batterie 24h, son stéréo HD.', 89.99, 'ecouteurs', 'speaker1.jpg', 10, TRUE, TRUE),
('Montre Connectée Ultra', 'Montre connecte avec GPS, suivi sportif, fréquentation cardiaque, batterie 7 jours', 129.99, 'accessoires', 'smartwatch1.jpg', 15, TRUE, FALSE);