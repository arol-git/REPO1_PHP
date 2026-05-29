<?php
require_once 'config.php';

$category_filter = $_GET['category'] ?? 'all';
$sql = "SELECT * FROM products WHERE stock > 0";

if($category_filter !== 'all') {
    if($category_filter === 'audio') {
        $sql .= " AND category = 'ecouteurs'";
    } elseif($category_filter === 'power') {
        $sql .= " AND (category = 'powerbanks' OR category = 'chargeurs')";
    } elseif($category_filter === 'smart') {
        $sql .= " AND category = 'accessoires'";
    } elseif($category_filter === 'accessories') {
        $sql .= " AND category = 'accessoires'";
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
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Styles supplémentaires pour centrer les cartes */
        .shop-page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .shop-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .shop-header h1 {
            margin-bottom: 1.5rem;
        }
        
        .product-filters {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.5rem 1.2rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all var(--transition-fast);
            font-size: 0.85rem;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--accent-gradient);
            border-color: transparent;
            color: white;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            justify-content: center;
        }
        
        .product-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-normal);
            border: 1px solid var(--border-color);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-primary);
        }
        
        .product-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity var(--transition-normal);
        }
        
        .product-card:hover .product-overlay {
            opacity: 1;
        }
        
        .quick-view {
            padding: 0.5rem 1.2rem;
            background: var(--accent-primary);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            color: #000;
            text-decoration: none;
        }
        
        .product-info {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-description {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }
        
        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-primary);
            margin: 0.5rem 0;
        }
        
        .product-stock {
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
        }
        
        .add-to-cart {
            width: 100%;
            padding: 0.6rem;
            background: var(--accent-gradient);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            margin-top: auto;
        }
        
        .add-to-cart:hover:not(:disabled) {
            transform: scale(1.02);
            opacity: 0.9;
        }
        
        .add-to-cart.in-cart {
            background: #28a745;
            cursor: default;
            opacity: 0.8;
        }
        
        .add-to-cart:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .out-of-stock {
            width: 100%;
            padding: 0.6rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            cursor: not-allowed;
            margin-top: auto;
        }
        
        .empty-products {
            text-align: center;
            padding: 3rem;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            grid-column: 1 / -1;
        }
        
        .empty-products p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .shop-page {
                padding: 1rem;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1rem;
            }
            
            .product-filters {
                gap: 0.5rem;
            }
            
            .filter-btn {
                padding: 0.4rem 1rem;
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 600px) {
            .products-grid {
                grid-template-columns: 1fr;
                max-width: 350px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="shop-page">
            <div class="shop-header">
                <h1>🛍️ Notre Boutique</h1>
                <div class="product-filters">
                    <button class="filter-btn <?php echo $category_filter == 'all' ? 'active' : ''; ?>" data-filter="all">Tous</button>
                    <button class="filter-btn <?php echo $category_filter == 'audio' ? 'active' : ''; ?>" data-filter="audio">🎧 Audio</button>
                    <button class="filter-btn <?php echo $category_filter == 'power' ? 'active' : ''; ?>" data-filter="power">🔋 Power</button>
                    <button class="filter-btn <?php echo $category_filter == 'smart' ? 'active' : ''; ?>" data-filter="smart">⌚ Smart</button>
                    <button class="filter-btn <?php echo $category_filter == 'accessories' ? 'active' : ''; ?>" data-filter="accessories">💻 Accessoires</button>
                </div>
            </div>

            <div class="products-grid" id="productsGrid">
                <?php if(count($products) > 0): ?>
                    <?php foreach($products as $product): ?>
                        <?php
                            $filterCategory = 'accessories';
                            if($product['category'] == 'ecouteurs') $filterCategory = 'audio';
                            elseif($product['category'] == 'powerbanks') $filterCategory = 'power';
                            elseif($product['category'] == 'chargeurs') $filterCategory = 'power';
                            else $filterCategory = 'accessories';
                            
                            // Vérifier si le produit est nouveau (moins de 30 jours)
                            $isNew = false;
                            if(!empty($product['created_at'])) {
                                $dateAdded = new DateTime($product['created_at']);
                                $now = new DateTime();
                                $diff = $dateAdded->diff($now);
                                $isNew = $diff->days < 30;
                            }
                        ?>
                        <div class="product-card" data-id="<?php echo $product['id']; ?>" data-category="<?php echo $filterCategory; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" onclick="window.location='product.php?id=<?php echo $product['id']; ?>'">
                            <div class="product-image">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/300x300/1a1a2e/00d4ff?text=Product'">
                                </a>
                                <?php if($isNew): ?>
                                    <div class="badge-new" style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #00d4ff, #7b2ff7); color: white; padding: 3px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; z-index: 5;">
                                        ✨ Nouveau
                                    </div>
                                <?php endif; ?>
                                <div class="product-overlay">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="quick-view">👁️ Voir détails</a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description"><?php echo truncateText($product['description'], 60); ?></p>
                                <p class="product-price"><?php echo formatPrice($product['price']); ?></p>
                                <p class="product-stock" style="color: <?php echo $product['stock'] > 0 ? '#00ff88' : '#ff4444'; ?>">
                                    <?php echo $product['stock'] > 0 ? '✅ En stock (' . $product['stock'] . ')' : '❌ Rupture de stock'; ?>
                                </p>
                                <?php if($product['stock'] > 0): ?>
                                    <button class="add-to-cart" 
                                        data-id="<?php echo $product['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                                        data-price="<?php echo $product['price'] * EURO_TO_XAF; ?>" 
                                        data-image="<?php echo $product['image']; ?>"
                                        data-stock="<?php echo $product['stock']; ?>">
                                        🛒 Ajouter au panier
                                    </button>
                                <?php else: ?>
                                    <button class="out-of-stock" disabled>📦 Rupture de stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-products">
                        <p>😕 Aucun produit disponible dans cette catégorie</p>
                        <a href="shop.php" class="btn-primary">Voir tous les produits</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="js/script.js"></script>
    
    <script>
        // Fonction pour le filtrage des produits
        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');
            let visibleCount = 0;
            
            products.forEach(product => {
                const productCategory = product.dataset.category;
                let isVisible = false;
                
                if (category === 'all') {
                    isVisible = true;
                } else if (category === 'audio') {
                    isVisible = (productCategory === 'audio');
                } else if (category === 'power') {
                    isVisible = (productCategory === 'power');
                } else if (category === 'smart') {
                    isVisible = (productCategory === 'smart');
                } else if (category === 'accessories') {
                    isVisible = (productCategory === 'accessories');
                }
                
                if (isVisible) {
                    product.style.display = '';
                    visibleCount++;
                } else {
                    product.style.display = 'none';
                }
            });
            
            // Afficher un message si aucun résultat
            const productsGrid = document.getElementById('productsGrid');
            let noResultsMsg = document.getElementById('noResultsMessage');
            
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMessage';
                    noResultsMsg.className = 'empty-products';
                    noResultsMsg.innerHTML = '<p>😕 Aucun produit trouvé dans cette catégorie</p><a href="shop.php" class="btn-primary">Voir tous les produits</a>';
                    productsGrid.appendChild(noResultsMsg);
                }
                noResultsMsg.style.display = 'block';
            } else {
                if (noResultsMsg) noResultsMsg.style.display = 'none';
            }
        }
        
        // Initialisation des filtres
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterProducts(filter);
            });
        });
        
        // Fonction de recherche
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const products = document.querySelectorAll('.product-card');
                let visibleCount = 0;
                
                products.forEach(product => {
                    const productName = product.dataset.name?.toLowerCase() || '';
                    if (productName.includes(searchTerm)) {
                        product.style.display = '';
                        visibleCount++;
                    } else {
                        product.style.display = 'none';
                    }
                });
                
                const productsGrid = document.getElementById('productsGrid');
                let noResultsMsg = document.getElementById('noResultsMessage');
                
                if (visibleCount === 0 && searchTerm.length > 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMessage';
                        noResultsMsg.className = 'empty-products';
                        noResultsMsg.innerHTML = '<p>😕 Aucun produit trouvé pour "<strong>' + searchTerm + '</strong>"</p><a href="shop.php" class="btn-primary">Voir tous les produits</a>';
                        productsGrid.appendChild(noResultsMsg);
                    }
                    noResultsMsg.style.display = 'block';
                } else {
                    if (noResultsMsg) noResultsMsg.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>