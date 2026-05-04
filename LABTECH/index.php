<?php
require_once 'config.php';

// Récupérer les produits pour chaque catégorie
$newProducts = $pdo->query("SELECT * FROM products WHERE new = TRUE LIMIT 6")->fetchAll();
$featuredProducts = $pdo->query("SELECT * FROM products WHERE featured = TRUE LIMIT 6")->fetchAll();
$inStockProducts = $pdo->query("SELECT * FROM products WHERE stock > 0 LIMIT 6")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Accessoires Électroniques</title>
    <link rel="stylesheet" href="style.css">
</head>
    <?php require_once 'navbar.php'; ?>

    <main>
        <!-- Slider automatique -->
        <div class="slider-container">
            <div class="slider" id="slider">
                <div class="slide">
                    <div class="slide-content">
                        <h2>Nouveautés</h2>
                        <p>Découvrez les derniers accessoires</p>
                        <div class="slide-products">
                            <?php foreach(array_slice($newProducts, 0, 3) as $product): ?>
                                <div class="slide-product">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                    <h3><?php echo $product['name']; ?></h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide-content">
                        <h2>Produits Recommandés</h2>
                        <p>Nos meilleures ventes</p>
                        <div class="slide-products">
                            <?php foreach(array_slice($featuredProducts, 0, 3) as $product): ?>
                                <div class="slide-product">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                    <h3><?php echo $product['name']; ?></h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="slide">
                    <div class="slide-content">
                        <h2>En Stock</h2>
                        <p>Disponible immédiatement</p>
                        <div class="slide-products">
                            <?php foreach(array_slice($inStockProducts, 0, 3) as $product): ?>
                                <div class="slide-product">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                    <h3><?php echo $product['name']; ?></h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <button class="slider-prev">❮</button>
            <button class="slider-next">❯</button>
            <div class="slider-dots"></div>
        </div>

        <!-- Sections produits -->
        <section class="products-section">
            <h2>Nouveautés</h2>
            <div class="products-grid">
                <?php foreach($newProducts as $product): ?>
                    <div class="product-card">
                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <div class="product-info">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price"><?php echo number_format($product['price'], 2); ?> €</p>
                            <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Ajouter au panier</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="products-section">
            <h2>Produits Recommandés</h2>
            <div class="products-grid">
                <?php foreach($featuredProducts as $product): ?>
                    <div class="product-card">
                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <div class="product-info">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price"><?php echo number_format($product['price'], 2); ?> €</p>
                            <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Ajouter au panier</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="script.js"></script>

    <?php require_once 'footer.php'; ?>

    
</body>
</html>