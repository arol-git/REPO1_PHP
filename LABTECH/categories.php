<?php
require_once 'config.php';

// Récupérer les produits par catégorie
$categories = [
    'audio' => ['name' => 'Audio', 'icon' => '🎧', 'products' => []],
    'power' => ['name' => 'Power', 'icon' => '🔋', 'products' => []],
    'smart' => ['name' => 'Smart Devices', 'icon' => '⌚', 'products' => []],
    'accessories' => ['name' => 'Accessoires', 'icon' => '💻', 'products' => []]
];

// Récupérer 4 produits par catégorie
foreach($categories as $key => $cat) {
    if($key == 'audio') {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'ecouteurs' AND stock > 0 LIMIT 4");
    } elseif($key == 'power') {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE (category = 'powerbanks' OR category = 'chargeurs') AND stock > 0 LIMIT 4");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'accessoires' AND stock > 0 LIMIT 4");
    }
    $stmt->execute();
    $categories[$key]['products'] = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .categories-page {
            max-width: 1280px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .category-hero {
            text-align: center;
            margin-bottom: 3rem;
        }
        .category-hero h1 {
            margin-bottom: 0.5rem;
        }
        .category-section {
            margin-bottom: 3rem;
        }
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }
        .category-header h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .category-header a {
            color: var(--accent-primary);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .category-products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-normal);
            border: 1px solid var(--border-color);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        .product-image {
            height: 200px;
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
        .product-info {
            padding: 1rem;
        }
        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .product-price {
            color: var(--accent-primary);
            font-weight: bold;
        }
        .empty-category {
            text-align: center;
            padding: 2rem;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            color: var(--text-muted);
        }
        @media (max-width: 768px) {
            .categories-page {
                padding: 1rem;
            }
            .category-products {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>
<body>
    <?php require_once 'navbar.php'; ?>
    
    <main>
        <div class="categories-page">
            <div class="category-hero">
                <h1>📁 Nos catégories</h1>
                <p>Découvrez nos produits par catégorie</p>
            </div>
            
            <?php foreach($categories as $key => $cat): ?>
                <?php if(count($cat['products']) > 0): ?>
                    <div class="category-section">
                        <div class="category-header">
                            <h2><span><?php echo $cat['icon']; ?></span> <?php echo $cat['name']; ?></h2>
                            <a href="shop.php?category=<?php echo $key; ?>">Voir tout →</a>
                        </div>
                        <div class="category-products">
                            <?php foreach($cat['products'] as $product): ?>
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="product.php?id=<?php echo $product['id']; ?>">
                                            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                                        <p class="product-price"><?php echo formatPrice($product['price']); ?></p>
                                        <button class="add-to-cart" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price'] * EURO_TO_XAF; ?>" data-image="<?php echo $product['image']; ?>">
                                            🛒 Ajouter
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>
    
    <?php require_once 'footer.php'; ?>
</body>
</html>
