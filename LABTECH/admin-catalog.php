<?php
require_once 'config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Ajout produit
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if($_POST['action'] == 'add') {
        $image = 'default.jpg';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if(in_array($ext, $allowed)) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
            }
        }
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $image, $_POST['stock']]);
        $success = "Produit ajouté";
    }
    elseif($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, stock=? WHERE id=?");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['stock'], $_POST['id']]);
        $success = "Produit modifié";
    }
    elseif($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $success = "Produit supprimé";
    }
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Catalogue - DATALAB-TECH</title>
    <link rel="stylesheet" href="style-main.css">
    <link rel="stylesheet" href="style-navbar.css">
    <link rel="stylesheet" href="style-footer.css">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="admin-dashboard">
            <h1>Gestion du catalogue</h1>
            <a href="admin-dashboard.php" class="admin-btn" style="margin-bottom: 1rem;">← Retour</a>
            
            <?php if(isset($success)): ?>
                <div class="notification success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <!-- Formulaire ajout -->
            <div class="admin-form">
                <h2>Ajouter un produit</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" required></textarea>
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
                        <label>Image</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="submit-btn">Ajouter</button>
                </form>
            </div>
            
            <!-- Liste produits -->
            <div class="admin-table">
                <h2>Liste des produits</h2>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Image</th><th>Nom</th><th>Prix</th><th>Stock</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><img src="uploads/<?php echo $p['image']; ?>" width="50" height="50" style="object-fit:cover;border-radius:5px;"></td>
                            <td><?php echo htmlspecialchars(truncateText($p['name'], 30)); ?></td>
                            <td><?php echo formatPrice($p['price']); ?></td>
                            <td><?php echo $p['stock']; ?></td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($p['name']); ?>" size="15">
                                    <input type="number" step="0.01" name="price" value="<?php echo $p['price']; ?>" size="5">
                                    <input type="number" name="stock" value="<?php echo $p['stock']; ?>" size="3">
                                    <button type="submit" class="admin-btn" style="padding:0.2rem 0.5rem;">✏️</button>
                                </form>
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" style="background:#ff4444;border:none;padding:0.2rem 0.5rem;border-radius:5px;cursor:pointer;">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>