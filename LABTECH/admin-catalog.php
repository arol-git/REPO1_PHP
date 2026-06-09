<?php
require_once 'config.php';

requireAdmin();

// AJOUTER UN PRODUIT
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    requireValidCsrf();

    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    
    $image = 'default.jpg';
    try {
        $uploadedImage = saveUploadedImage($_FILES['image'] ?? null, __DIR__ . '/uploads');
        if ($uploadedImage !== null) {
            $image = $uploadedImage;
        }
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
    
    if (!isset($error)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $image, $stock]);
        $success = "Produit ajouté avec succès !";
    }
}

// MODIFIER UN PRODUIT
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    requireValidCsrf();

    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    
    if($id > 0) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
        $stmt->execute([$name, $description, $price, $category, $stock, $id]);
        $success = "Produit modifié avec succès !";
    }
}

// SUPPRIMER UN PRODUIT
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    requireValidCsrf();

    $id = $_POST['id'] ?? 0;
    if($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
        $stmt->execute([$id]);
        $success = "Produit supprimé avec succès !";
    }
}

// Récupérer tous les produits avec leur date de création
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC, id DESC")->fetchAll();

// Vérifier si le formulaire d'ajout doit être affiché
$showAddForm = isset($_GET['action']) && $_GET['action'] == 'add';
// Vérifier si le formulaire de modification doit être affiché
$editProduct = null;
if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editProduct = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Admin - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .catalog-admin {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .catalog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .add-product-btn {
            background: var(--accent-gradient);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all var(--transition-normal);
        }
        .add-product-btn:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }
        .back-btn {
            background: var(--bg-hover);
            color: var(--text-primary);
            padding: 0.6rem 1.2rem;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all var(--transition-normal);
        }
        .back-btn:hover {
            border-color: var(--accent-primary);
            color: var(--accent-primary);
        }
        .product-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }
        .product-card-admin {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all var(--transition-normal);
            position: relative;
        }
        .product-card-admin:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        /* Badge nouveau produit */
        .badge-new {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            z-index: 10;
            animation: pulse 2s infinite;
        }
        .badge-new::before {
            content: "✨";
            margin-right: 4px;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .product-image-admin {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        .product-image-admin img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info-admin {
            padding: 1rem;
        }
        .product-info-admin h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        .product-price-admin {
            color: var(--accent-primary);
            font-weight: bold;
            margin: 0.5rem 0;
        }
        .product-stock-admin {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
        .product-category-admin {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        .product-date-admin {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .product-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .edit-btn, .delete-btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 500;
            transition: all var(--transition-fast);
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
        }
        .edit-btn {
            background: var(--accent-primary);
            color: white;
        }
        .delete-btn {
            background: #ff4444;
            color: white;
        }
        .edit-btn:hover, .delete-btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
        
        /* Formulaire d'ajout/modification */
        .admin-form {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            animation: fadeInUp 0.3s ease;
        }
        .admin-form h2 {
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.3rem;
            font-weight: 500;
            color: var(--accent-primary);
            font-size: 0.85rem;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.7rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 0.9rem;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-primary);
        }
        .submit-btn {
            background: var(--accent-gradient);
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: all var(--transition-normal);
        }
        .submit-btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .notification {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideInRight 0.3s ease;
        }
        .notification.success {
            background: linear-gradient(135deg, #00ff88, #00cc66);
            color: white;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .empty-catalog {
            text-align: center;
            padding: 4rem;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }
        .empty-catalog p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="catalog-admin">
            <div class="catalog-header">
                <h1>📦 Catalogue des produits</h1>
                <?php if(!$showAddForm && !$editProduct): ?>
                    <a href="?action=add" class="add-product-btn">➕ Ajouter un produit</a>
                <?php else: ?>
                    <a href="admin-catalog.php" class="back-btn">← Retour au catalogue</a>
                <?php endif; ?>
            </div>
            
            <?php if(isset($success)): ?>
                <div class="notification success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            <?php if(isset($error)): ?>
                <div class="notification error">⚠️ <?php echo e($error); ?></div>
            <?php endif; ?>
            
            <!-- Formulaire d'ajout -->
            <?php if($showAddForm): ?>
                <div class="admin-form">
                    <h2>➕ Ajouter un nouveau produit</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label>Nom du produit *</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Description *</label>
                            <textarea name="description" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prix (€) *</label>
                            <input type="number" step="0.01" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Catégorie *</label>
                            <select name="category" required>
                                <option value="chargeurs">Chargeurs</option>
                                <option value="ecouteurs">Écouteurs</option>
                                <option value="cables">Câbles</option>
                                <option value="powerbanks">Power Banks</option>
                                <option value="accessoires">Accessoires</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" value="10">
                        </div>
                        <div class="form-group">
                            <label>Image du produit</label>
                            <input type="file" name="image" accept="image/*">
                            <small style="color: var(--text-muted);">Laissez vide pour utiliser l'image par défaut</small>
                        </div>
                        <button type="submit" class="submit-btn">➕ Ajouter le produit</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire de modification -->
            <?php if($editProduct): ?>
                <div class="admin-form">
                    <h2>✏️ Modifier le produit : <?php echo htmlspecialchars($editProduct['name']); ?></h2>
                    <form method="POST">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                        <div class="form-group">
                            <label>Nom du produit *</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($editProduct['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Description *</label>
                            <textarea name="description" rows="4" required><?php echo htmlspecialchars($editProduct['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prix (€) *</label>
                            <input type="number" step="0.01" name="price" value="<?php echo $editProduct['price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Catégorie *</label>
                            <select name="category" required>
                                <option value="chargeurs" <?php echo $editProduct['category'] == 'chargeurs' ? 'selected' : ''; ?>>Chargeurs</option>
                                <option value="ecouteurs" <?php echo $editProduct['category'] == 'ecouteurs' ? 'selected' : ''; ?>>Écouteurs</option>
                                <option value="cables" <?php echo $editProduct['category'] == 'cables' ? 'selected' : ''; ?>>Câbles</option>
                                <option value="powerbanks" <?php echo $editProduct['category'] == 'powerbanks' ? 'selected' : ''; ?>>Power Banks</option>
                                <option value="accessoires" <?php echo $editProduct['category'] == 'accessoires' ? 'selected' : ''; ?>>Accessoires</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" value="<?php echo $editProduct['stock']; ?>">
                        </div>
                        <button type="submit" class="submit-btn">💾 Enregistrer les modifications</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <!-- Liste des produits -->
            <?php if(count($products) > 0): ?>
                <div class="product-grid-admin">
                    <?php foreach($products as $product): ?>
                        <?php 
                            $isNew = isNewProduct($product['created_at']);
                            $dateAdded = new DateTime($product['created_at']);
                            $now = new DateTime();
                            $diff = $dateAdded->diff($now);
                            $daysSinceAdded = $diff->days;
                        ?>
                        <div class="product-card-admin">
                            <?php if($isNew): ?>
                                <div class="badge-new">
                                    Nouveau (<?php echo $daysSinceAdded; ?> jour<?php echo $daysSinceAdded > 1 ? 's' : ''; ?>)
                                </div>
                            <?php endif; ?>
                            <div class="product-image-admin">
                                <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/300x200/1a1a2e/00d4ff?text=Product'">
                            </div>
                            <div class="product-info-admin">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-price-admin"><?php echo formatPrice($product['price']); ?></p>
                                <p class="product-stock-admin">
                                    📦 Stock: <?php echo $product['stock']; ?> unités
                                    <?php if($product['stock'] < 5 && $product['stock'] > 0): ?>
                                        <span style="color: #ffaa00;">⚠️ Stock faible</span>
                                    <?php elseif($product['stock'] == 0): ?>
                                        <span style="color: #ff4444;">❌ Rupture</span>
                                    <?php endif; ?>
                                </p>
                                <p class="product-category-admin">📁 Catégorie: <?php echo ucfirst($product['category']); ?></p>
                                <p class="product-date-admin">
                                    📅 Ajouté le: <?php echo date('d/m/Y', strtotime($product['created_at'])); ?>
                                    <?php if($isNew): ?>
                                        <span style="color: #00d4ff;">(Il y a <?php echo $daysSinceAdded; ?> jour<?php echo $daysSinceAdded > 1 ? 's' : ''; ?>)</span>
                                    <?php endif; ?>
                                </p>
                                <div class="product-actions">
                                    <a href="?action=edit&id=<?php echo $product['id']; ?>" class="edit-btn">✏️ Modifier</a>
                                    <form method="POST" style="flex:1;" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                        <?php echo csrfField(); ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="delete-btn" style="width:100%;">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-catalog">
                    <p>📭 Aucun produit dans le catalogue</p>
                    <a href="?action=add" class="add-product-btn">➕ Ajouter votre premier produit</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
</body>
</html>
