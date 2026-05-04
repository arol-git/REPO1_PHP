<?php
require_once 'config.php';

$category_filter = $_GET['category'] ?? 'all';
$sql = "SELECT * FROM products";
$params = [];

if($category_filter !== 'all') {
    if($category_filter === 'new') {
        $sql .= " WHERE new = TRUE";
    } elseif($category_filter === 'offres') {
        $sql .= " WHERE featured = TRUE";
    } elseif($category_filter === 'audio') {
        $sql .= " WHERE category = 'ecouteurs'";
    } elseif($category_filter === 'power') {
        $sql .= " WHERE category = 'powerbanks'";
    } elseif($category_filter === 'watch' || $category_filter === 'personal' || $category_filter === 'appliances') {
        $sql .= " WHERE category = 'accessoires'";
    }
}

$sql .= " ORDER BY created_at DESC";
$products = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - DATALAB-TECH</title>
    <link rel="stylesheet" href="style-main.css">
    <link rel="stylesheet" href="style-navbar.css">
    <link rel="stylesheet" href="style-footer.css">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="shop-header">
            <h1>Notre Boutique</h1>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Rechercher un produit...">
            </div>
        </div>

        <div class="products-grid" id="products-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-image">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                        <div class="product-menu">
                            <button class="menu-trigger">⋯</button>
                            <div class="menu-dropdown">
                                <a href="product.php?id=<?php echo $product['id']; ?>">📖 Voir détails</a>
                                <button class="quick-add" data-id="<?php echo $product['id']; ?>">🛒 Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo truncateText(htmlspecialchars($product['description']), 60); ?></p>
                        <p class="product-price"><?php echo formatPrice($product['price']); ?></p>
                        <p class="product-stock <?php echo $product['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                            <?php echo $product['stock'] > 0 ? '✅ En stock' : '❌ Rupture'; ?>
                        </p>
                        <?php if($product['stock'] > 0): ?>
                            <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Ajouter au panier</button>
                        <?php else: ?>
                            <button class="out-of-stock" disabled>Rupture de stock</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>