<?php
require_once 'config.php';

// Récupérer l'ID du produit depuis l'URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header('Location: shop.php');
    exit;
}

// Récupérer le produit depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit;
}

// Récupérer les produits similaires (même catégorie)
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category'], $product_id]);
$similarProducts = $stmt->fetchAll();

// Déterminer la classe CSS pour le stock
$stockClass = $product['stock'] > 0 ? 'in-stock' : 'out-stock';
$stockText = $product['stock'] > 0 ? '✅ En stock (' . $product['stock'] . ' unités)' : '❌ Rupture de stock';
$stockColor = $product['stock'] > 0 ? '#00ff88' : '#ff4444';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="product-detail-container">
            <!-- Détail du produit -->
            <div class="product-detail">
                <div class="product-detail-image">
                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/600x600/1a1a2e/00d4ff?text=Product'">
                </div>
                <div class="product-detail-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-category">📁 Catégorie : <?php echo ucfirst($product['category']); ?></p>
                    <p class="product-detail-price"><?php echo formatPrice($product['price']); ?></p>
                    <p class="stock-info" style="color: <?php echo $stockColor; ?>">
                        <?php echo $stockText; ?>
                    </p>
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    <?php if($product['stock'] > 0): ?>
                        <button class="add-to-cart detail-add-btn" 
                            data-id="<?php echo $product['id']; ?>" 
                            data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                            data-price="<?php echo $product['price'] * EURO_TO_XAF; ?>" 
                            data-image="<?php echo $product['image']; ?>"
                            data-stock="<?php echo $product['stock']; ?>">
                            🛒 Ajouter au panier (<?php echo $product['stock']; ?> dispo)
                        </button>
                    <?php else: ?>
                        <button class="out-of-stock detail-add-btn" disabled>📦 Rupture de stock</button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Produits similaires -->
            <?php if(count($similarProducts) > 0): ?>
                <div class="similar-products">
                    <h2>Produits similaires</h2>
                    <div class="products-grid">
                        <?php foreach($similarProducts as $similar): ?>
                            <?php
                                // Déterminer la catégorie pour le filtre
                                $filterCategory = 'accessories';
                                if($similar['category'] == 'ecouteurs') $filterCategory = 'audio';
                                elseif($similar['category'] == 'powerbanks') $filterCategory = 'power';
                                elseif($similar['category'] == 'chargeurs') $filterCategory = 'power';
                                else $filterCategory = 'accessories';
                            ?>
                            <div class="product-card" data-id="<?php echo $similar['id']; ?>" data-category="<?php echo $filterCategory; ?>" onclick="window.location='product.php?id=<?php echo $similar['id']; ?>'"> 
                                <div class="product-image">
                                    <a href="product.php?id=<?php echo $similar['id']; ?>">
                                        <img src="uploads/<?php echo $similar['image']; ?>" alt="<?php echo htmlspecialchars($similar['name']); ?>" onerror="this.src='https://placehold.co/300x300/1a1a2e/00d4ff?text=Product'">
                                    </a>
                                    <div class="product-overlay">
                                        <button class="quick-view">👁️ Voir rapide</button>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($similar['name']); ?></h3>
                                    <p class="product-description"><?php echo truncateText($similar['description'], 60); ?></p>
                                    <p class="product-price"><?php echo formatPrice($similar['price']); ?></p>
                                    <?php if($similar['stock'] > 0): ?>
                                        <button class="add-to-cart" 
                                            data-id="<?php echo $similar['id']; ?>" 
                                            data-name="<?php echo htmlspecialchars($similar['name']); ?>" 
                                            data-price="<?php echo $similar['price'] * EURO_TO_XAF; ?>" 
                                            data-image="<?php echo $similar['image']; ?>"
                                            data-stock="<?php echo $similar['stock']; ?>">
                                            🛒 Ajouter au panier (<?php echo $similar['stock']; ?> dispo)
                                        </button>
                                    <?php else: ?>
                                        <button class="out-of-stock" disabled>📦 Rupture de stock</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
</body>
</html>
