<?php
require_once 'config.php';

// Si déjà connecté en tant qu'admin, rediriger
if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: admin-dashboard.php');
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireValidCsrf();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($username) || empty($password)) {
        $error = '❌ Veuillez remplir tous les champs';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $admin['id'],
                'username' => $admin['username'],
                'role' => $admin['role']
            ];
            header('Location: admin-dashboard.php');
            exit;
        } else {
            $error = '❌ Identifiants admin incorrects';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--bg-primary);
            padding: 1rem;
        }

        .admin-login-container {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 3rem 2rem;
            max-width: 400px;
            width: 100%;
            box-shadow: var(--shadow-lg);
            animation: slideInUp 0.5s ease;
        }

        .admin-login-container h1 {
            text-align: center;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-size: 1.8rem;
        }

        .admin-login-container .subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all var(--transition-fast);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }

        .error-message {
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid rgba(255, 68, 68, 0.3);
            color: #ff4444;
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            box-shadow: var(--shadow-md);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer-link a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .admin-login-container {
                padding: 2rem 1.5rem;
            }

            .admin-login-container h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h1>🔐 Admin Panel</h1>
        <p class="subtitle">Connexion Administrateur</p>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

            <form method="POST">
                <?php echo csrfField(); ?>
                <div class="form-group">
                <label for="username">👤 Identifiant</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">🔑 Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Se connecter</button>
        </form>

        <div class="footer-link">
            <p>Pas un administrateur ? <a href="index.php">Retour à l'accueil</a></p>
        </div>
    </div>
</body>
</html>
