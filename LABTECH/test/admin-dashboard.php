<?php
require_once 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 5")->fetchColumn();
$totalValue = $pdo->query("SELECT SUM(price * stock) FROM products")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - DATALAB-TECH</title>
    <link rel="stylesheet" href="style-main.css">
    <link rel="stylesheet" href="style-navbar.css">
    <link rel="stylesheet" href="style-footer.css">
</head>
<body>
    <?php require_once 'navbar.php';