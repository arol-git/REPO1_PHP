<?php
require_once 'config.php';

// Vérifier si l'utilisateur est admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Traitement des actions admin
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case 'add':
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];
                $stock = $_POST['stock'];
                
                // Gestion de l'upload d'image
                $image = 'default.jpg';
                if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['image']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if(in_array($ext, $allowed)) {
                        $image = time() . '_' . $filename;
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
                    }
                }
                
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $category, $image, $stock]);
                $success = "Produit ajouté avec succès !";
                break;
                
            case 'edit':
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $category = $_POST['category'];
                $stock = $_POST['stock'];
                
                $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
                $stmt->execute([$name, $description, $price, $category, $stock, $id]);
                $success = "Produit modifié avec succès !";
                break;
                
            case 'delete':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
                $stmt->execute([$id]);
                $success = "Produit supprimé !";
                break;
        }
    }
}

// Récupérer tous les produits
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">⚡ Lab-Store Admin</a>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="shop.php">Boutique</a>
                <a href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <main>
        <div class="admin-panel">
            <h1>Dashboard Administrateur</h1>
            
            <?php if(isset($success)): ?>
                <div class="notification success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Formulaire d'ajout de produit -->
            <div class="admin-form">
                <h2>Ajouter un produit</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label>Nom du produit</label>
                        <input type="text" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Prix (€)</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Catégorie</label>
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
                        <input type="number" name="stock" value="10" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Image du produit</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    
                    <button type="submit" class="submit-btn">Ajouter le produit</button>
                </form>
            </div>
            
            <!-- Liste des produits -->
            <div class="products-list">
                <h2>Gestion des produits</h2>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price"><?php echo number_format($product['price'], 2); ?> €</p>
                                <p>Stock: <?php echo $product['stock']; ?></p>
                                
                                <!-- Formulaire d'édition -->
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                    <textarea name="description" rows="2"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                                    <select name="category">
                                        <option value="chargeurs" <?php echo $product['category'] == 'chargeurs' ? 'selected' : ''; ?>>Chargeurs</option>
                                        <option value="ecouteurs" <?php echo $product['category'] == 'ecouteurs' ? 'selected' : ''; ?>>Écouteurs</option>
                                        <option value="cables" <?php echo $product['category'] == 'cables' ? 'selected' : ''; ?>>Câbles</option>
                                        <option value="powerbanks" <?php echo $product['category'] == 'powerbanks' ? 'selected' : ''; ?>>Power Banks</option>
                                    </select>
                                    <input type="number" name="stock" value="<?php echo $product['stock']; ?>">
                                    <button type="submit" class="submit-btn" style="margin-top: 5px;">Modifier</button>
                                </form>
                                
                                <!-- Formulaire de suppression -->
                                <form method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" style="background: #ff4444; margin-top: 5px;">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>