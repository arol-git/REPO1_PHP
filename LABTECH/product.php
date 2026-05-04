<?php
require_once 'config.php';

$product_id = $_GET['id'] ?? 0;

// Récupérer le produit
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if(!$product) {
    header('Location: shop.php');
    exit;
}

// Récupérer produits similaires (même catégorie)
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category'], $product_id]);
$similarProducts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <?php require_once 'navbar.php'; ?>

    <main>
        <div class="product-detail-container">
            <div class="product-detail">
                <div class="product-image">
                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                </div>
                <div class="product-detail-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="category">Catégorie : <?php echo ucfirst($product['category']); ?></p>
                    <p class="detail-price"><?php echo number_format($product['price'], 2); ?> €</p>
                    <p class="stock-info">
                        <?php if($product['stock'] > 0): ?>
                            ✅ En stock (<?php echo $product['stock']; ?> unités)
                        <?php else: ?>
                            ❌ Rupture de stock
                        <?php endif; ?>
                    </p>
                    <div class="product-description">
                        <h3>Description</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    <?php if($product['stock'] > 0): ?>
                        <button class="add-to-cart detail-add-btn" data-id="<?php echo $product['id']; ?>">
                            Ajouter au panier
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if(count($similarProducts) > 0): ?>
                <div class="similar-products">
                    <h2>Produits similaires</h2>
                    <div class="products-grid">
                        <?php foreach($similarProducts as $similar): ?>
                            <div class="product-card">
                                <a href="product.php?id=<?php echo $similar['id']; ?>">
                                    <img src="uploads/<?php echo $similar['image']; ?>" alt="<?php echo $similar['name']; ?>">
                                </a>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($similar['name']); ?></h3>
                                    <p class="price"><?php echo number_format($similar['price'], 2); ?> €</p>
                                    <button class="add-to-cart" data-id="<?php echo $similar['id']; ?>">
                                        Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="script.js"></script>

    <?php require_once 'footer.php'; ?>

    
</body>
</html>