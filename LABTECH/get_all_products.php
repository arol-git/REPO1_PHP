<?php
require_once 'config.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT id, stock FROM products");
$products = $stmt->fetchAll();

echo json_encode($products);
?>