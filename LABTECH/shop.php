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
        .shop-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
        }

        /* SIDEBAR FILTRES */
        .shop-sidebar {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-section {
            margin-bottom: 2rem;
        }

        .sidebar-section:last-child {
            margin-bottom: 0;
        }

        .sidebar-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--accent-primary);
        }

        .filter-group label {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .filter-group label:hover {
            color: var(--accent-primary);
        }

        .filter-group input[type="checkbox"],
        .filter-group input[type="radio"] {
            margin-right: 0.8rem;
            cursor: pointer;
            accent-color: var(--accent-primary);
        }

        /* MAIN CONTENT */
        .shop-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .shop-header {
            text-align: left;
            margin-bottom: 1rem;
        }

        .shop-header h1 {
            margin-bottom: 0.5rem;
        }

        .shop-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
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
            height: 200px;
            overflow: hidden;
            background: var(--bg-input);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 0.5rem;
            transition: transform var(--transition-slow);
        }

        .product-card:hover .product-image img {
            transform: scale(1.08);
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
            padding: 0.6rem 1.2rem;
            background: var(--accent-primary);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            color: #000;
            text-decoration: none;
            transition: all var(--transition-normal);
        }

        .quick-view:hover {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        .product-description {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.8rem;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent-primary);
            margin-bottom: 0.5rem;
        }

        .product-stock {
            font-size: 0.75rem;
            margin-bottom: 0.8rem;
            color: var(--text-muted);
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

        .results-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .shop-page {
                grid-template-columns: 250px 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .shop-sidebar {
                top: 80px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .shop-page {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .shop-sidebar {
                display: none;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 1rem;
            }

            .product-image {
                height: 160px;
            }
        }

        @media (max-width: 600px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.8rem;
            }

            .product-image {
                height: 140px;
            }

            .sidebar-section {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="shop-page">
            <!-- SIDEBAR FILTRES -->
            <aside class="shop-sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">🏷️ Catégories</h3>
                    <div class="filter-group">
                        <label>
                            <input type="radio" name="category" value="all" <?php echo $category_filter == 'all' ? 'checked' : ''; ?>> 
                            Tous les produits
                        </label>
                        <label>
                            <input type="radio" name="category" value="audio" <?php echo $category_filter == 'audio' ? 'checked' : ''; ?>>
                            🎧 Audio
                        </label>
                        <label>
                            <input type="radio" name="category" value="power" <?php echo $category_filter == 'power' ? 'checked' : ''; ?>>
                            🔋 Power
                        </label>
                        <label>
                            <input type="radio" name="category" value="smart" <?php echo $category_filter == 'smart' ? 'checked' : ''; ?>>
                            ⌚ Smart
                        </label>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3 class="sidebar-title">💰 Prix</h3>
                    <div class="filter-group">
                        <label>
                            <input type="checkbox" class="price-filter" value="0-50000">
                            Moins de 50 000 FCFA
                        </label>
                        <label>
                            <input type="checkbox" class="price-filter" value="50000-100000">
                            50 000 - 100 000 FCFA
                        </label>
                        <label>
                            <input type="checkbox" class="price-filter" value="100000-200000">
                            100 000 - 200 000 FCFA
                        </label>
                        <label>
                            <input type="checkbox" class="price-filter" value="200000+">
                            Plus de 200 000 FCFA
                        </label>
                    </div>
                </div>

            </aside>

            <!-- MAIN CONTENT -->
            <div class="shop-content">
                <div class="shop-header">
                    <h1>🛍️ Notre Boutique</h1>
                    <p class="results-text" id="resultsText">Affichage de tous les produits disponibles</p>
                </div>

                <div class="products-grid" id="productsGrid">
                    <?php if (empty($products)): ?>
                        <div class="empty-products">
                            <p>Aucun produit trouvé dans cette catégorie.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product-card" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                                <div class="product-image">
                                    <img src="uploads/<?php echo htmlspecialchars($product['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/240x200/1a1a2e/ffffff?text=<?php echo urlencode($product['name']); ?>'">
                                    <div class="product-overlay">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="quick-view">Voir plus</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-description"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 60)); ?></p>
                                    <div class="product-price">
                                        <?php echo number_format($product['price'] * 655.96, 0, ',', ' '); ?> FCFA
                                    </div>
                                    <div class="product-stock">
                                        <?php if ($product['stock'] > 0): ?>
                                            ✅ En stock (<?php echo $product['stock']; ?> unités)
                                        <?php else: ?>
                                            ❌ Rupture de stock
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($product['stock'] > 0): ?>
                                        <button class="add-to-cart" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price']; ?>">
                                            🛒 Ajouter
                                        </button>
                                    <?php else: ?>
                                        <button class="out-of-stock" disabled>Rupture de stock</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script>
        let allProducts = <?php echo json_encode($products); ?>;

        function getPriceRanges() {
            return Array.from(document.querySelectorAll('.price-filter:checked')).map(el => el.value);
        }

        function matchesPrice(price, filters) {
            if (!filters.length) return true;
            return filters.some(range => {
                if (range === '200000+') {
                    return price >= 200000;
                }
                const [min, max] = range.split('-').map(Number);
                return price >= min && price <= max;
            });
        }

        function updateResultsText(visibleCount) {
            const resultsText = document.getElementById('resultsText');
            const totalCount = document.querySelectorAll('.product-card').length;
            if (visibleCount === totalCount) {
                resultsText.textContent = 'Affichage de tous les produits disponibles';
            } else {
                resultsText.textContent = `${visibleCount} produit(s) trouvé(s)`;
            }
        }

        function filterProducts() {
            const selectedRanges = getPriceRanges();
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const price = parseFloat(card.dataset.price);
                const matches = matchesPrice(price, selectedRanges);
                card.style.display = matches ? 'flex' : 'none';
                if (matches) visibleCount++;
            });

            updateResultsText(visibleCount);
        }

        // Gestion des filtres de catégorie
        document.querySelectorAll('input[name="category"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const category = e.target.value;
                const url = category === 'all' ? 'shop.php' : `shop.php?category=${category}`;
                window.location.href = url;
            });
        });

        document.querySelectorAll('.price-filter').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                filterProducts();
            });
        });

        filterProducts();

        // Fonctionnalité panier
        function addToCart(productId, productName, productPrice) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const existingItem = cart.find(item => item.id == productId);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id: productId, quantity: 1 });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartUI();
            showNotification(`✅ ${productName} ajouté au panier!`);
        }

        function updateCartUI() {
            const buttons = document.querySelectorAll('.add-to-cart');
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');

            buttons.forEach(btn => {
                const productId = btn.dataset.id;
                const inCart = cart.some(item => item.id == productId);
                if (inCart) {
                    btn.classList.add('in-cart');
                    btn.textContent = '✓ Dans le panier';
                    btn.disabled = true;
                } else {
                    btn.classList.remove('in-cart');
                    btn.textContent = '🛒 Ajouter';
                    btn.disabled = false;
                }
            });
        }

        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification success';
            notification.textContent = message;
            notification.style.cssText = 'position: fixed; top: 100px; right: 20px; z-index: 1000; background: #28a745; color: white; padding: 1rem 1.5rem; border-radius: 8px; animation: slideIn 0.3s ease-out;';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }

        // Ajouter les écouteurs aux boutons
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', () => {
                addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price);
            });
        });

        updateCartUI();
    </script>
    <script src="js/script.js"></script>
</body>
</html>