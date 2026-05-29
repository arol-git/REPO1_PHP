<?php
require_once 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Mise à jour du statut de commande
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    $success = "Statut de la commande mis à jour";
}

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="admin-dashboard">
            <h1>📋 Gestion des commandes</h1>
            <a href="admin-dashboard.php" class="admin-btn" style="margin-bottom: 1rem;">← Retour au Dashboard</a>
            
            <?php if(isset($success)): ?>
                <div class="notification success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($orders) > 0): ?>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td>Utilisateur <?php echo $order['user_id']; ?></td>
                                    <td><?php echo formatPrice($order['total']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php 
                                                switch($order['status']) {
                                                    case 'pending': echo '⏳ En attente'; break;
                                                    case 'processing': echo '🔄 En traitement'; break;
                                                    case 'shipped': echo '🚚 Expédiée'; break;
                                                    case 'delivered': echo '✅ Livrée'; break;
                                                    case 'cancelled': echo '❌ Annulée'; break;
                                                    default: echo $order['status'];
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" style="display: flex; gap: 0.5rem;">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <input type="hidden" name="update_status" value="1">
                                            <select name="status" class="status-select">
                                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>En attente</option>
                                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>En traitement</option>
                                                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Expédiée</option>
                                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Livrée</option>
                                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                                            </select>
                                            <button type="submit" class="admin-btn" style="padding: 0.3rem 0.8rem;">Mettre à jour</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Aucune commande pour le moment</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <style>
        .status-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .status-pending { background: rgba(255, 170, 0, 0.2); color: #ffaa00; }
        .status-processing { background: rgba(0, 212, 255, 0.2); color: #00d4ff; }
        .status-shipped { background: rgba(123, 47, 247, 0.2); color: #7b2ff7; }
        .status-delivered { background: rgba(0, 255, 136, 0.2); color: #00ff88; }
        .status-cancelled { background: rgba(255, 68, 68, 0.2); color: #ff4444; }
        .status-select {
            padding: 0.3rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
        }
    </style>

    <?php require_once 'footer.php'; ?>
</body>
</html>