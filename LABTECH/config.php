<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'ecommerce_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Taux de change (1 EUR = 655.96 XAF)
define('EURO_TO_XAF', 655.96);

// Fonction pour convertir en FCFA
function formatPrice($priceInEuro) {
    $priceInXaf = $priceInEuro * EURO_TO_XAF;
    return number_format($priceInXaf, 0, ',', ' ') . ' FCFA';
}

// Fonction pour tronquer le texte
function truncateText($text, $maxLength = 50) {
    if (strlen($text) <= $maxLength) return $text;
    return substr($text, 0, $maxLength) . '...';
}
?>