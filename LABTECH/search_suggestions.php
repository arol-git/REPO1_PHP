<?php
require_once 'config.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if(strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Rechercher les produits correspondants
$stmt = $pdo->prepare("SELECT id, name, price, image, category FROM products WHERE name LIKE ? AND stock > 0 LIMIT 5");
$stmt->execute(["%$query%"]);
$products = $stmt->fetchAll();

// Ajouter le prix en FCFA à chaque produit
foreach($products as &$product) {
    $product['price'] = $product['price'];
    $product['category_name'] = ucfirst($product['category']);
}

echo json_encode($products);
?>