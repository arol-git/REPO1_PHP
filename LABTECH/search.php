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

// Mode fragment pour l'overlay de recherche
if (isset($_GET['overlay']) && $_GET['overlay'] == '1') {
    ?>
    <div class="search-page">
        <div class="search-hero">
            <h1><i class="fa-solid fa-magnifying-glass fa-xl"></i> Que recherchez-vous ?</h1>
            <p>Trouvez l'accessoire parfait parmi notre catalogue</p>
            
            <div class="search-bar-large">
                <div class="search-input-wrapper">
                    <input type="text" id="mainSearchInput" placeholder="Ex: chargeur rapide, écouteurs bluetooth..." value="<?php echo htmlspecialchars($search_query); ?>" autocomplete="off">
                    <button id="searchButton">Rechercher</button>
                </div>
                <div class="search-suggestions" id="searchSuggestions"></div>
            </div>
        </div>

        <?php if(!empty($search_query)): ?>
            <div class="results-header">
                <h2>📋 Résultats pour "<span style="color: var(--accent-primary);"><?php echo htmlspecialchars($search_query); ?></span>"</h2>
                <p class="results-count"><?php echo count($search_results); ?> produit(s) trouvé(s)</p>
            </div>

            <?php if(count($search_results) > 0): ?>
                <div class="products-grid">
                    <?php foreach($search_results as $product): ?>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
                            <div class="product-image"><img src="uploads/<?php echo $product['image'] ?: 'default.jpg'; ?>" onerror="this.src='https://placehold.co/300x200/1a1a2e/00d4ff?text=?'" alt="<?php echo htmlspecialchars($product['name']); ?>"></div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price"><?php echo number_format($product['price'] * 655.96, 0, ',', ' '); ?> FCFA</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">😕</div>
                    <h3>Aucun résultat trouvé</h3>
                    <p>Nous n'avons pas trouvé de produit correspondant à "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="recommendations">
            <h2>✨ Découvrez par catégorie</h2>
            <div class="category-recommendations">
                <?php foreach($category_products as $cat => $prod): ?>
                    <a href="product.php?id=<?php echo $prod['id']; ?>" class="rec-card">
                        <div class="rec-image"><img src="uploads/<?php echo $prod['image'] ?: 'default.jpg'; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" onerror="this.src='https://placehold.co/300x200/1a1a2e/00d4ff?text=?'"></div>
                        <div class="rec-info">
                            <div class="rec-category"><?php echo htmlspecialchars(strtoupper($cat)); ?></div>
                            <div class="rec-name"><?php echo htmlspecialchars($prod['name']); ?></div>
                            <div class="rec-price"><?php echo number_format($prod['price'] * 655.96, 0, ',', ' '); ?> FCFA</div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    exit;
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
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="search-page">
            <!-- Hero avec barre de recherche -->
            <div class="search-hero">
                <h1><i class="fa-solid fa-magnifying-glass fa-xl"></i> Que recherchez-vous ?</h1>
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
                const response = await fetch(`/search_suggestions.php?q=${encodeURIComponent(query)}`);
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
    
    <script src="/js/script.js"></script>
</body>
</html>