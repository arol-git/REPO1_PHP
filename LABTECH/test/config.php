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

// Démarrer la session
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
    $text = strip_tags($text);
    if (mb_strlen($text) <= $maxLength) return $text;
    return mb_substr($text, 0, $maxLength) . '...';
}

// Fonction pour obtenir la couleur du stock
function getStockColor($stock) {
    if ($stock > 10) return '#00ff88';
    if ($stock > 0) return '#ffaa00';
    return '#ff4444';
}

// Fonction pour obtenir le texte du stock
function getStockText($stock) {
    if ($stock > 10) return '✅ En stock';
    if ($stock > 0) return '⚠️ Stock limité';
    return '❌ Rupture de stock';
}
?>