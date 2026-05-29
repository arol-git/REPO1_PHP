<?php
require_once 'config.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_results = [];
$category_products = [];

if(!empty($search_query)) {
    // Recherche du produit
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? AND stock > 0");
    $stmt->execute(["%$search_query%"]);
    $search_results = $stmt->fetchAll();
    
    // Si un produit est trouvé, récupérer les similaires
    if(count($search_results) >= 1) {
        $first_product = $search_results[0];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND stock > 0 LIMIT 4");
        $stmt->execute([$first_product['category'], $first_product['id']]);
        $similar_products = $stmt->fetchAll();
    }
}

// Récupérer un produit par catégorie pour les recommandations
$categories = ['ecouteurs', 'powerbanks', 'chargeurs', 'accessoires'];
foreach($categories as $cat) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND stock > 0 LIMIT 1");
    $stmt->execute([$cat]);
    $product = $stmt->fetch();
    if($product) {
        $category_products[$cat] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .search-page {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* Barre de recherche centrale */
        .search-hero {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-color);
        }
        .search-hero h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .search-hero p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        .search-bar-large {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        .search-input-wrapper {
            display: flex;
            background: var(--bg-input);
            border: 2px solid var(--border-color);
            border-radius: 60px;
            overflow: hidden;
            transition: all var(--transition-normal);
        }
        .search-input-wrapper:focus-within {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }
        .search-input-wrapper input {
            flex: 1;
            padding: 1rem 1.5rem;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1rem;
            outline: none;
        }
        .search-input-wrapper button {
            padding: 0 1.5rem;
            background: var(--accent-gradient);
            border: none;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-normal);
        }
        .search-input-wrapper button:hover {
            opacity: 0.9;
        }
        
        /* Suggestions de recherche */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            margin-top: 10px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 100;
            display: none;
            box-shadow: var(--shadow-lg);
        }
        .search-suggestions.active {
            display: block;
        }
        .suggestion-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1rem;
            text-decoration: none;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-color);
            transition: background var(--transition-fast);
        }
        .suggestion-item:hover {
            background: var(--bg-hover);
        }
        .suggestion-item img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: var(--radius-sm);
        }
        .suggestion-info {
            flex: 1;
        }
        .suggestion-name {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        .suggestion-category {
            font-size: 0.7rem;
            color: var(--text-muted);
        }
        .suggestion-price {
            color: var(--accent-primary);
            font-weight: bold;
        }
        
        /* Recommandations par catégorie */
        .recommendations {
            margin-top: 3rem;
        }
        .recommendations h2 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }
        .category-recommendations {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .rec-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all var(--transition-normal);
            text-decoration: none;
            color: var(--text-primary);
            display: block;
        }
        .rec-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-primary);
        }
        .rec-image {
            height: 180px;
            overflow: hidden;
        }
        .rec-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }
        .rec-card:hover .rec-image img {
            transform: scale(1.05);
        }
        .rec-info {
            padding: 1rem;
        }
        .rec-category {
            font-size: 0.7rem;
            color: var(--accent-primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .rec-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        .rec-price {
            color: var(--accent-primary);
            font-weight: bold;
        }
        
        /* Résultats de recherche */
        .results-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .results-header h2 {
            margin-bottom: 0.5rem;
        }
        .results-count {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .no-results {
            text-align: center;
            padding: 4rem;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }
        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="search-page">
            <!-- Hero avec barre de recherche -->
            <div class="search-hero">
                <h1>🔍 Que recherchez-vous ?</h1>
                <p>Trouvez l'accessoire parfait parmi notre catalogue</p>
                
                <div class="search-bar-large">
                    <div class="search-input-wrapper">
                        <input type="text" id="mainSearchInput" placeholder="Ex: chargeur rapide, écouteurs bluetooth..." value="<?php echo htmlspecialchars($search_query); ?>" autocomplete="off">
                        <button id="searchButton">Rechercher</button>
                    </div>
                    <div class="search-suggestions" id="searchSuggestions"></div>
                </div>
            </div>
            
            <!-- Résultats de recherche -->
            <?php if(!empty($search_query)): ?>
                <div class="results-header">
                    <h2>📋 Résultats pour "<span style="color: var(--accent-primary);"><?php echo htmlspecialchars($search_query); ?></span>"</h2>
                    <p class="results-count"><?php echo count($search_results); ?> produit(s) trouvé(s)</p>
                </div>
                
                <?php if(count($search_results) > 0): ?>
                    <div class="products-grid">
                        <?php foreach($search_results as $product): ?>
                            <div class="product-card" data-id="<?php echo $product['id']; ?>">
                                <div class="product-image">
                                    <a href="product.php?id=<?php echo $product['id']; ?>">
                                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/300x300/1a1a2e/00d4ff?text=Product'">
                                    </a>
                                    <div class="product-overlay">
                                        <a href="product.php?id=<?php echo $product['id']; ?>" class="quick-view">👁️ Voir détails</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="product-description"><?php echo truncateText($product['description'], 60); ?></p>
                                    <p class="product-price"><?php echo formatPrice($product['price']); ?></p>
                                    <p class="product-stock" style="color: <?php echo $product['stock'] > 0 ? '#00ff88' : '#ff4444'; ?>">
                                        <?php echo $product['stock'] > 0 ? '✅ En stock' : '❌ Rupture'; ?>
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
                                        <button class="out-of-stock" disabled>📦 Rupture</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Produits similaires -->
                    <?php if(isset($similar_products) && count($similar_products) > 0): ?>
                        <div style="margin-top: 2rem;">
                            <h3 style="margin-bottom: 1rem;">🔄 Produits similaires</h3>
                            <div class="products-grid">
                                <?php foreach($similar_products as $similar): ?>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <a href="product.php?id=<?php echo $similar['id']; ?>">
                                                <img src="uploads/<?php echo $similar['image']; ?>" alt="<?php echo htmlspecialchars($similar['name']); ?>">
                                            </a>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-title"><?php echo htmlspecialchars($similar['name']); ?></h3>
                                            <p class="product-price"><?php echo formatPrice($similar['price']); ?></p>
                                            <button class="add-to-cart" data-id="<?php echo $similar['id']; ?>" data-name="<?php echo htmlspecialchars($similar['name']); ?>" data-price="<?php echo $similar['price'] * EURO_TO_XAF; ?>" data-image="<?php echo $similar['image']; ?>" data-stock="<?php echo $similar['stock']; ?>">
                                                🛒 Ajouter au panier
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-results">
                        <div class="no-results-icon">😕</div>
                        <h3>Aucun résultat trouvé</h3>
                        <p>Nous n'avons pas trouvé de produit correspondant à "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
                        <p style="margin-top: 1rem; color: var(--text-muted);">Essayez avec d'autres mots-clés ou parcourez nos catégories ci-dessous</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Recommandations par catégorie (toujours visible) -->
            <div class="recommendations">
                <h2>✨ Découvrez par catégorie</h2>
                <div class="category-recommendations">
                    <!-- Audio / Écouteurs -->
                    <?php if(isset($category_products['ecouteurs'])): ?>
                        <a href="product.php?id=<?php echo $category_products['ecouteurs']['id']; ?>" class="rec-card">
                            <div class="rec-image">
                                <img src="uploads/<?php echo $category_products['ecouteurs']['image']; ?>" alt="<?php echo htmlspecialchars($category_products['ecouteurs']['name']); ?>">
                            </div>
                            <div class="rec-info">
                                <div class="rec-category">🎧 AUDIO</div>
                                <div class="rec-name"><?php echo htmlspecialchars($category_products['ecouteurs']['name']); ?></div>
                                <div class="rec-price"><?php echo formatPrice($category_products['ecouteurs']['price']); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Power / Batteries -->
                    <?php if(isset($category_products['powerbanks'])): ?>
                        <a href="product.php?id=<?php echo $category_products['powerbanks']['id']; ?>" class="rec-card">
                            <div class="rec-image">
                                <img src="uploads/<?php echo $category_products['powerbanks']['image']; ?>" alt="<?php echo htmlspecialchars($category_products['powerbanks']['name']); ?>">
                            </div>
                            <div class="rec-info">
                                <div class="rec-category">🔋 POWER</div>
                                <div class="rec-name"><?php echo htmlspecialchars($category_products['powerbanks']['name']); ?></div>
                                <div class="rec-price"><?php echo formatPrice($category_products['powerbanks']['price']); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Chargeurs -->
                    <?php if(isset($category_products['chargeurs'])): ?>
                        <a href="product.php?id=<?php echo $category_products['chargeurs']['id']; ?>" class="rec-card">
                            <div class="rec-image">
                                <img src="uploads/<?php echo $category_products['chargeurs']['image']; ?>" alt="<?php echo htmlspecialchars($category_products['chargeurs']['name']); ?>">
                            </div>
                            <div class="rec-info">
                                <div class="rec-category">⚡ CHARGEURS</div>
                                <div class="rec-name"><?php echo htmlspecialchars($category_products['chargeurs']['name']); ?></div>
                                <div class="rec-price"><?php echo formatPrice($category_products['chargeurs']['price']); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Accessoires -->
                    <?php if(isset($category_products['accessoires'])): ?>
                        <a href="product.php?id=<?php echo $category_products['accessoires']['id']; ?>" class="rec-card">
                            <div class="rec-image">
                                <img src="uploads/<?php echo $category_products['accessoires']['image']; ?>" alt="<?php echo htmlspecialchars($category_products['accessoires']['name']); ?>">
                            </div>
                            <div class="rec-info">
                                <div class="rec-category">💻 ACCESSOIRES</div>
                                <div class="rec-name"><?php echo htmlspecialchars($category_products['accessoires']['name']); ?></div>
                                <div class="rec-price"><?php echo formatPrice($category_products['accessoires']['price']); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
    
    <script>
        // ==================== RECHERCHE AVEC SUGGESTIONS ====================
        const searchInput = document.getElementById('mainSearchInput');
        const searchButton = document.getElementById('searchButton');
        const suggestionsBox = document.getElementById('searchSuggestions');
        
        let searchTimeout;
        
        async function fetchSuggestions(query) {
            if (query.length < 2) {
                suggestionsBox.classList.remove('active');
                return;
            }
            
            try {
                const response = await fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`);
                const suggestions = await response.json();
                showSuggestions(suggestions);
            } catch(error) {
                console.error('Erreur:', error);
            }
        }
        
        function showSuggestions(suggestions) {
            if (suggestions.length === 0) {
                suggestionsBox.classList.remove('active');
                return;
            }
            
            suggestionsBox.innerHTML = suggestions.map(product => `
                <a href="product.php?id=${product.id}" class="suggestion-item">
                    <img src="uploads/${product.image}" alt="${product.name}" onerror="this.src='https://placehold.co/45x45/1a1a2e/00d4ff?text=?'">
                    <div class="suggestion-info">
                        <div class="suggestion-name">${product.name}</div>
                        <div class="suggestion-category">${product.category}</div>
                    </div>
                    <div class="suggestion-price">${(product.price * 655.96).toLocaleString('fr-FR')} FCFA</div>
                </a>
            `).join('');
            
            suggestionsBox.classList.add('active');
        }
        
        function performSearch() {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `search.php?q=${encodeURIComponent(query)}`;
            }
        }
        
        // Écouteurs d'événements
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value;
            searchTimeout = setTimeout(() => fetchSuggestions(query), 300);
        });
        
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        searchButton.addEventListener('click', performSearch);
        
        // Fermer les suggestions en cliquant ailleurs
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.remove('active');
            }
        });
        
        // Mettre en évidence le champ de recherche si vide
        if (!searchInput.value.trim()) {
            searchInput.placeholder = "Ex: chargeur rapide, écouteurs bluetooth...";
        }
    </script>
    
    <script src="js/script.js"></script>
</body>
</html>