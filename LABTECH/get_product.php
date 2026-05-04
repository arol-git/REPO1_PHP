<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT id, name, price, image, stock FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

header('Content-Type: application/json');
echo json_encode($product);
?>