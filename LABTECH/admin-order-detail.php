<?php
require_once 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: admin-login.php');
    exit;
}

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($orderId <= 0) {
    header('Location: admin-orders.php');
    exit;
}

$stmt = $pdo->prepare('SELECT o.*, u.username AS customer_username, u.email AS customer_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: admin-orders.php');
    exit;
}

$orderItemsStmt = $pdo->prepare('SELECT oi.quantity, oi.price, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
$orderItemsStmt->execute([$orderId]);
$orderItems = $orderItemsStmt->fetchAll(PDO::FETCH_ASSOC);

$shippingInfo = [];
if (!empty($order['shipping_info'])) {
    $decoded = json_decode($order['shipping_info'], true);
    if (is_array($decoded)) {
        $shippingInfo = $decoded;
    }
}

function formatStatus($status) {
    switch($status) {
        case 'pending': return '⏳ En attente';
        case 'processing': return '🔄 En traitement';
        case 'shipped': return '🚚 Expédiée';
        case 'delivered': return '✅ Livrée';
        case 'cancelled': return '❌ Annulée';
        default: return htmlspecialchars($status);
    }
}

function cleanPhone($phone) {
    return preg_replace('/[^0-9]/', '', $phone);
}

$whatsappNumber = '';
if (!empty($shippingInfo['whatsapp'])) {
    $whatsappNumber = cleanPhone($shippingInfo['whatsapp']);
    if (strpos($whatsappNumber, '237') !== 0) {
        $whatsappNumber = '237' . ltrim($whatsappNumber, '0');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails commande #<?php echo $order['id']; ?> - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .order-detail-page {
            padding: 2.5rem 1rem;
            min-height: calc(100vh - 200px);
        }
        .order-detail-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .order-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 1.8rem;
        }
        .order-card h1,
        .order-card h2 {
            margin: 0 0 1rem;
        }
        .order-summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .order-section {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.2rem 1.4rem;
        }
        .order-section h3 {
            margin: 0 0 0.8rem;
            font-size: 1rem;
            color: var(--accent-primary);
        }
        .order-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .order-row:last-child {
            border-bottom: none;
        }
        .order-row span {
            display: block;
        }
        .order-row strong {
            font-weight: 600;
        }
        .items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .items-list li {
            padding: 0.9rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .items-list li:last-child {
            border-bottom: none;
        }
        .items-list-title {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .whatsapp-button {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: #25D366;
            color: white;
            padding: 0.85rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
        }
        .detail-actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .admin-btn {
            padding: 0.7rem 1.15rem;
            border-radius: 999px;
            font-weight: 600;
        }
        @media (max-width: 860px) {
            .order-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main class="order-detail-page">
        <div class="order-detail-container">
            <div class="order-card">
                <div class="detail-actions">
                    <div>
                        <h1>Commande #<?php echo $order['id']; ?></h1>
                        <p>Statut : <?php echo formatStatus($order['status']); ?></p>
                    </div>
                    <div>
                        <a href="admin-orders.php" class="admin-btn">← Retour aux commandes</a>
                        <?php if ($whatsappNumber): ?>
                            <a href="https://wa.me/<?php echo htmlspecialchars($whatsappNumber); ?>" target="_blank" rel="noopener" class="whatsapp-button">WhatsApp</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order-summary-grid">
                    <div class="order-section">
                        <h3>Informations client</h3>
                        <div class="order-row"><span>Utilisateur</span><strong><?php echo !empty($order['customer_username']) ? htmlspecialchars($order['customer_username']) : 'Client invité'; ?></strong></div>
                        <div class="order-row"><span>Email</span><strong><?php echo htmlspecialchars($order['customer_email'] ?? ($shippingInfo['email'] ?? 'Non renseigné')); ?></strong></div>
                        <div class="order-row"><span>Téléphone</span><strong><?php echo htmlspecialchars($shippingInfo['phone'] ?? 'Non renseigné'); ?></strong></div>
                        <div class="order-row"><span>WhatsApp</span><strong><?php echo htmlspecialchars($shippingInfo['whatsapp'] ?? 'Non renseigné'); ?></strong></div>
                    </div>

                    <div class="order-section">
                        <h3>Livraison</h3>
                        <div class="order-row"><span>Nom complet</span><strong><?php echo htmlspecialchars(trim(($shippingInfo['firstName'] ?? '') . ' ' . ($shippingInfo['lastName'] ?? ''))); ?></strong></div>
                        <div class="order-row"><span>Quartier</span><strong><?php echo htmlspecialchars($shippingInfo['quartier'] ?? 'Non renseigné'); ?></strong></div>
                        <div class="order-row"><span>Ville</span><strong><?php echo htmlspecialchars($shippingInfo['ville'] ?? 'Non renseigné'); ?></strong></div>
                        <div class="order-row"><span>Adresse</span><strong><?php echo htmlspecialchars($shippingInfo['streetAddress'] ?? 'Non renseigné'); ?></strong></div>
                        <div class="order-row"><span>Zipcode</span><strong><?php echo htmlspecialchars($shippingInfo['zipcode'] ?? 'Non renseigné'); ?></strong></div>
                        <div class="order-row"><span>Client par défaut</span><strong><?php echo !empty($shippingInfo['defaultAddress']) ? 'Oui' : 'Non'; ?></strong></div>
                    </div>
                </div>

                <div class="order-section" style="margin-top: 1.5rem;">
                    <h3>Contenu de la commande</h3>
                    <div class="items-list-title">
                        <span>Produit</span>
                        <span>Prix / Qté</span>
                    </div>
                    <ul class="items-list">
                        <?php foreach ($orderItems as $item): ?>
                            <li>
                                <div style="display:flex; justify-content:space-between; gap:1rem;">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['name'] ?? 'Produit supprimé'); ?></strong><br>
                                        Qté: <?php echo (int)$item['quantity']; ?>
                                    </div>
                                    <div>
                                        <?php echo formatPrice($item['price']); ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="order-row" style="margin-top: 1rem; font-size: 1rem;">
                        <span><strong>Total</strong></span><strong><?php echo formatPrice($order['total']); ?></strong>
                    </div>
                    <div class="order-row">
                        <span>Mode de paiement</span><strong><?php echo htmlspecialchars($order['payment_method'] ?? 'Non défini'); ?></strong>
                    </div>
                    <div class="order-row">
                        <span>Date de commande</span><strong><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
</body>
</html>
