<?php
require_once 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Statistiques
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$lowStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock < 5")->fetchColumn();
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
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="admin-dashboard">
            <h1>Dashboard Administrateur</h1>
            
            <div class="admin-stats">
                <div class="stat-card">
                    <h3><?php echo $totalProducts; ?></h3>
                    <p>Produits</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $totalOrders; ?></h3>
                    <p>Commandes</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $lowStock; ?></h3>
                    <p>Stock faible</p>
                </div>
            </div>
            
            <div class="admin-actions">
                <a href="admin-catalog.php" class="admin-btn">📦 Gérer le catalogue</a>
                <a href="admin-orders.php" class="admin-btn">📋 Voir les commandes</a>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>