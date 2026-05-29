<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT id, name, description, price, image, stock, category FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($product ?: null);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(null);
}
?>