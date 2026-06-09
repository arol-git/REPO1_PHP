<?php
require_once 'config.php';

$newProducts = $pdo->query("SELECT * FROM products WHERE `new` = TRUE LIMIT 6")->fetchAll();
$featuredProducts = $pdo->query("SELECT * FROM products WHERE featured = TRUE LIMIT 6")->fetchAll();
$inStockProducts = $pdo->query("SELECT * FROM products WHERE stock > 0 LIMIT 6")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATALAB-TECH | Accessoires Électroniques Premium</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-badge">🔥 Nouveautés 2026</div>
                    <h1 class="hero-title">
                        L'avenir de la<br>
                        <span class="gradient-text">tech est ici</span>
                    </h1>
                    <p class="hero-subtitle">
                        Découvrez les derniers accessoires électroniques qui révolutionnent 
                        votre quotidien. Qualité premium, design innovant et livraison rapide au Cameroun.
                    </p>
                    <div class="hero-buttons">
                        <a href="shop.php" class="btn-primary">Acheter maintenant →</a>
                        <a href="help.php" class="btn-secondary">En savoir plus</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">5K+</span>
                            <span class="stat-label">Clients satisfaits</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support technique</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">2 ans</span>
                            <span class="stat-label">Garantie</span>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-image-wrapper">
                        <img src="images/hero-product.png" alt="Premium Tech Products" loading="lazy">
                        <div class="floating-card card-1">🔥 -25%</div>
                        <div class="floating-card card-2">⚡ Charge rapide</div>
                        <div class="floating-card card-3">🎵 Audio HD</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Catégories</span>
                    <h2 class="section-title">Explorez nos<br><span class="gradient-text">produits phares</span></h2>
                    <p class="section-subtitle">Des accessoires de qualité pour tous vos besoins technologiques</p>
                </div>
                <div class="categories-grid">
                    <div class="category-card" data-category="audio">
                        <div class="category-icon">🎧</div>
                        <h3>Audio</h3>
                        <p>Écouteurs & Casques</p>
                    </div>
                    <div class="category-card" data-category="power">
                        <div class="category-icon">🔋</div>
                        <h3>Power</h3>
                        <p>Chargeurs & Batteries</p>
                    </div>
                    <div class="category-card" data-category="smart">
                        <div class="category-icon">⌚</div>
                        <h3>Smart Devices</h3>
                        <p>Montres & Accessoires</p>
                    </div>
                    <div class="category-card" data-category="accessories">
                        <div class="category-icon">💻</div>
                        <h3>Accessoires</h3>
                        <p>Câbles & Stations</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Slider Section -->
        <div class="slider-container fade-up">
            <div class="slider" id="slider">
                <div class="slide">
                    <div class="slide-content">
                        <h2>Nouveautés</h2>
                        <p>Découvrez les derniers accessoires</p>
                        <div class="slide-products">
                            <?php foreach(array_slice($newProducts, 0, 3) as $product): ?>
                                <div class="slide-product" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
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
                                <div class="slide-product" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
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
                                <div class="slide-product" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
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

        <!-- Products Section -->
        <section class="products fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Meilleures ventes</span>
                    <h2 class="section-title">Produits<br><span class="gradient-text">populaires</span></h2>
                    <p class="section-subtitle">Les accessoires les plus demandés par nos clients</p>
                </div>
                
                <div class="product-filters">
                    <button class="filter-btn active" data-filter="all">Tous</button>
                    <button class="filter-btn" data-filter="audio">🎧 Audio</button>
                    <button class="filter-btn" data-filter="power">🔋 Power</button>
                    <button class="filter-btn" data-filter="smart">⌚ Smart</button>
                    <button class="filter-btn" data-filter="accessories">💻 Accessoires</button>
                </div>
                
                <div class="products-grid" id="productsGrid">
                    <?php foreach($featuredProducts as $product): ?>
                        <!-- Convertir la catégorie de la base de données en filtre -->
                        <?php
                            $filterCategory = 'accessories';
                            if($product['category'] == 'ecouteurs') $filterCategory = 'audio';
                            elseif($product['category'] == 'powerbanks') $filterCategory = 'power';
                            elseif($product['category'] == 'accessoires') $filterCategory = 'accessories';
                            elseif($product['category'] == 'chargeurs') $filterCategory = 'power';
                            else $filterCategory = 'accessories';
                        ?>
                        <div class="product-card" data-id="<?php echo $product['id']; ?>" data-category="<?php echo $filterCategory; ?>" data-original-category="<?php echo $product['category']; ?>" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
                            <div class="product-image">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/300x300/1a1a2e/00d4ff?text=Product'">
                                </a>
                                <div class="product-overlay">
                                    <button class="quick-view">👁️ Voir rapide</button>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description"><?php echo truncateText($product['description'], 60); ?></p>
                                <p class="product-price"><?php echo formatPrice($product['price']); ?></p>
                                <?php if($product['stock'] > 0): ?>
                                    <button class="add-to-cart" 
                                        data-id="<?php echo $product['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                        data-price="<?php echo $product['price'] * EURO_TO_XAF; ?>" 
                                        data-image="<?php echo $product['image']; ?>"
                                        data-stock="<?php echo $product['stock']; ?>">
                                        🛒 Ajouter au panier (<?php echo $product['stock']; ?> dispo)
                                    </button>
                                <?php else: ?>
                                    <button class="out-of-stock" disabled>Rupture de stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Special Offer Banner -->
        <section class="offer-banner fade-up">
            <div class="offer-container">
                <div class="offer-content">
                    <span class="offer-badge">🎁 Offre Limitée</span>
                    <h2>Jusqu'à <span class="gradient-text">-30%</span><br>sur les accessoires audio</h2>
                    <p>Profitez de nos remises exceptionnelles sur une sélection de produits premium. Offre valable jusqu'à épuisement des stocks.</p>
                    <a href="shop.php" class="btn-primary">Profiter de l'offre →</a>
                </div>
                <div class="offer-image">
                    <img src="images/offer-image.png" alt="Special Offer" loading="lazy">
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="features fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Pourquoi nous ?</span>
                    <h2 class="section-title">Pourquoi choisir<br><span class="gradient-text">DATALAB-TECH ?</span></h2>
                    <p class="section-subtitle">Une expérience d'achat unique et des services premium</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🚚</div>
                        <h3>Livraison Express</h3>
                        <p>Livraison gratuite sous 24-48h sur toute commande</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3>Paiement Sécurisé</h3>
                        <p>Transactions 100% sécurisées avec cryptage SSL</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>Garantie 2 Ans</h3>
                        <p>Service après-vente réactif et garantie constructeur</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎧</div>
                        <h3>Support 24/7</h3>
                        <p>Assistance technique disponible à tout moment</p>🔋
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
