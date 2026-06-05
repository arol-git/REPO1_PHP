<?php
require_once 'config.php';

// Si admin tente de se connecter ici, rediriger vers admin-login.php
header('Location: admin-login.php');
exit;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="login-container">
            <h2>🔐 Connexion</h2>
            
            <?php if($error): ?>
                <div class="notification error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Nom d'utilisateur</label>
                    <input type="text" name="username" required placeholder="admin">
                </div>
                
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" required placeholder="••••••">
                </div>
                
                <button type="submit" class="submit-btn" style="width:100%">Se connecter</button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">
                👤 Admin: <strong>admin</strong> / 🔑 <strong>admin123</strong>
            </p>
        </div>
    </main>

<?php
require_once 'config.php';
header('Location: admin-login.php');
exit;
?>