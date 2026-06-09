<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$host = getenv('INFO_DB_HOST') ?: 'localhost';
$dbname = getenv('INFO_DB_NAME') ?: 'contact_form';
$username = getenv('INFO_DB_USER') ?: 'root';
$password = getenv('INFO_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("Erreur BDD INFO/admin.php : " . $e->getMessage());
    die("Erreur de connexion à la base de données.");
}

session_start();

function infoCsrfToken() {
    if (empty($_SESSION['info_csrf_token'])) {
        $_SESSION['info_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['info_csrf_token'];
}

function infoCsrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(infoCsrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

function infoValidCsrf($token) {
    return is_string($token) && isset($_SESSION['info_csrf_token']) && hash_equals($_SESSION['info_csrf_token'], $token);
}

$adminUser = getenv('INFO_ADMIN_USER') ?: 'admin';
$adminPasswordHash = getenv('INFO_ADMIN_PASSWORD_HASH') ?: '$2y$10$btuv9i10dH1pml4E3yd8LOzA8KRXy2O7DZl1KSSzbFnH4VQ54zGyO';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if (!isset($_SESSION['logged_in']) || !infoValidCsrf($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Jeton de sécurité invalide.');
    }

    $id = (int)$_POST['delete'];
    $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$id]);
    header("Location: admin.php?deleted=1");
    exit;
}

if (!isset($_SESSION['logged_in'])) {
    if (
        isset($_POST['username'], $_POST['password']) &&
        infoValidCsrf($_POST['csrf_token'] ?? '') &&
        $_POST['username'] === $adminUser &&
        password_verify($_POST['password'], $adminPasswordHash)
    ) {
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        header("Location: admin.php");
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion Admin - DATALAB-TECH</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #0a0a0a 0%, #0f0f1a 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            body::before {
                content: '';
                position: absolute;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle at 20% 80%, rgba(0, 212, 255, 0.08) 0%, transparent 50%),
                            radial-gradient(circle at 80% 20%, rgba(123, 47, 247, 0.08) 0%, transparent 50%);
                animation: rotate 25s linear infinite;
            }

            @keyframes rotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .login-container {
                position: relative;
                z-index: 1;
                background: rgba(26, 26, 46, 0.95);
                backdrop-filter: blur(20px);
                padding: 2.5rem;
                border-radius: 32px;
                border: 1px solid rgba(0, 212, 255, 0.3);
                width: 100%;
                max-width: 440px;
                text-align: center;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                animation: fadeInUp 0.6s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .login-container .logo {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #00d4ff, #7b2ff7);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 2.2rem;
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.4); }
                50% { box-shadow: 0 0 0 15px rgba(0, 212, 255, 0); }
            }

            .login-container h2 {
                font-size: 1.8rem;
                font-weight: 800;
                background: linear-gradient(135deg, #00d4ff, #7b2ff7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin-bottom: 0.5rem;
            }

            .login-container p {
                color: rgba(255, 255, 255, 0.5);
                margin-bottom: 2rem;
                font-size: 0.9rem;
            }

            .input-group {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .input-group i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: rgba(255, 255, 255, 0.3);
                font-size: 1.1rem;
                transition: all 0.3s;
            }

            .input-group input {
                width: 100%;
                padding: 0.9rem 1rem 0.9rem 2.8rem;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                color: white;
                font-size: 0.9rem;
                transition: all 0.3s;
            }

            .input-group input:focus {
                outline: none;
                border-color: #00d4ff;
                background: rgba(255, 255, 255, 0.08);
                box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
            }

            .input-group input:focus + i {
                color: #00d4ff;
            }

            button {
                width: 100%;
                padding: 0.9rem;
                background: linear-gradient(135deg, #00d4ff, #7b2ff7);
                border: none;
                border-radius: 16px;
                color: white;
                font-weight: 600;
                font-size: 1rem;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px rgba(0, 212, 255, 0.4);
            }

            .error {
                background: rgba(255, 68, 68, 0.15);
                border: 1px solid rgba(255, 68, 68, 0.3);
                padding: 0.8rem;
                border-radius: 12px;
                color: #ff6666;
                margin-bottom: 1rem;
                font-size: 0.85rem;
            }

            .login-footer {
                margin-top: 1.5rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
                color: rgba(255, 255, 255, 0.3);
                font-size: 0.7rem;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="logo">
                <i class="fas fa-code"></i>
            </div>
            <h2>DATALAB-TECH</h2>
            <p>Espace administration sécurisé</p>
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><i class="fas fa-exclamation-triangle"></i> Identifiants incorrects</div>
            <?php endif; ?>
            <form method="POST">
                <?= infoCsrfField() ?>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <button type="submit"><i class="fas fa-arrow-right-to-bracket"></i> Se connecter</button>
            </form>
            <div class="login-footer">
                <i class="fas fa-shield-alt"></i> Accès réservé
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
$count = count($messages);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DATALAB-TECH</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #0f0f1a 100%);
            color: #fff;
            min-height: 100vh;
        }

        /* ==================== BOUTON MENU MOBILE ==================== */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
            transition: all 0.3s;
        }

        .menu-toggle i {
            font-size: 1.4rem;
            color: white;
        }

        .menu-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 212, 255, 0.4);
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: 280px;
            background: rgba(26, 26, 46, 0.98);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            position: fixed;
            height: 100vh;
            padding: 2rem 1.5rem;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .logo {
            text-align: center;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 2rem;
        }

        .sidebar .logo .icon {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.6rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .sidebar .logo h2 {
            font-size: 1.2rem;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .sidebar .logo p {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 0.3rem;
        }

        .sidebar nav ul {
            list-style: none;
            margin-bottom: 2rem;
        }

        .sidebar nav li {
            margin-bottom: 0.5rem;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .sidebar nav a i {
            width: 24px;
            font-size: 1.1rem;
        }

        .sidebar nav a:hover {
            background: rgba(0, 212, 255, 0.12);
            color: #00d4ff;
        }

        .sidebar nav li.active a {
            background: rgba(0, 212, 255, 0.12);
            color: #00d4ff;
            border-left: 3px solid #00d4ff;
        }

        .sidebar .separator {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 1rem 0;
        }

        .sidebar .external-links {
            margin: 1rem 0;
        }

        .sidebar .external-links h4 {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.8rem;
        }

        .sidebar .external-links a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.7rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s;
        }

        .sidebar .external-links a:hover {
            background: rgba(0, 212, 255, 0.12);
            color: #00d4ff;
            transform: translateX(5px);
        }

        .stats-sidebar {
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.5rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 0;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        .stat-value {
            color: #00d4ff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .logout-btn {
            padding: 0.8rem;
            background: rgba(255, 68, 68, 0.08);
            border: 1px solid rgba(255, 68, 68, 0.2);
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 68, 68, 0.15);
            border-color: rgba(255, 68, 68, 0.4);
        }

        .logout-btn a {
            color: #ff6666;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .top-bar h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .badge {
            background: rgba(0, 212, 255, 0.12);
            border: 1px solid rgba(0, 212, 255, 0.25);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge i {
            margin-right: 0.5rem;
            color: #00d4ff;
        }

        .search-box {
            margin-bottom: 2rem;
        }

        .search-box input {
            padding: 0.8rem 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            color: white;
            width: 320px;
            font-family: inherit;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #00d4ff;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.08);
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .messages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        /* ==================== MESSAGE CARD ==================== */
        .message-card {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            position: relative;
            overflow: hidden;
        }

        .message-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00d4ff, #7b2ff7, #00d4ff);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .message-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0, 212, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 212, 255, 0.1);
        }

        .message-card:hover::before {
            opacity: 1;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .message-name h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #00d4ff;
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .message-email {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.45);
        }

        .message-email i {
            margin-right: 0.3rem;
        }

        .message-date {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.03);
            padding: 0.3rem 0.7rem;
            border-radius: 50px;
        }

        .message-text {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 16px;
            margin: 1rem 0;
            font-size: 0.85rem;
            line-height: 1.6;
            max-height: 150px;
            overflow-y: auto;
            color: rgba(255, 255, 255, 0.8);
        }

        .message-text::-webkit-scrollbar {
            width: 4px;
        }

        .message-text::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .message-text::-webkit-scrollbar-thumb {
            background: #00d4ff;
            border-radius: 10px;
        }

        .message-screenshot {
            margin: 0.5rem 0;
        }

        .message-screenshot img {
            max-width: 100%;
            max-height: 130px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .message-screenshot img:hover {
            transform: scale(1.02);
            border-color: #00d4ff;
        }

        .message-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            gap: 1rem;
        }

        .btn-reply, .btn-delete {
            flex: 1;
            padding: 0.6rem;
            border-radius: 12px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-reply {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.25);
            color: #00d4ff;
        }

        .btn-reply:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: scale(1.02);
        }

        .btn-delete {
            background: rgba(255, 68, 68, 0.08);
            border: 1px solid rgba(255, 68, 68, 0.25);
            color: #ff8888;
        }

        .btn-delete:hover {
            background: rgba(255, 68, 68, 0.15);
            transform: scale(1.02);
        }

        .empty-state {
            text-align: center;
            padding: 4rem;
            background: rgba(26, 26, 46, 0.9);
            border-radius: 24px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(0, 212, 255, 0.2);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.4);
        }

        .success {
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.25);
            padding: 0.8rem 1.2rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            color: #00ff88;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
                width: 280px;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 80px;
            }
            
            .messages-grid {
                grid-template-columns: 1fr;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .top-bar {
                flex-direction: column;
                text-align: center;
            }
            
            .top-bar h1 {
                font-size: 1.3rem;
            }
        }

        /* ==================== SCROLLBAR ==================== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            border-radius: 10px;
        }
        
        /* Overlay pour mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            backdrop-filter: blur(4px);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Bouton menu mobile -->
<button class="menu-toggle" id="menuToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay pour fermer le menu -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <div class="icon"><i class="fas fa-database"></i></div>
        <h2>DATALAB-TECH</h2>
        <p>Admin Panel v2.0</p>
    </div>
    <nav>
        <ul>
            <li class="active"><a href="#"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-envelope"></i> Messages <span style="margin-left: auto; background: rgba(0,212,255,0.15); padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.7rem;"><?= $count ?></span></a></li>
        </ul>
    </nav>
    
    <!-- Liens externes (tous responsives comme les premiers) -->
    <div class="external-links">
        <h4><i class="fas fa-external-link-alt"></i> Liens rapides</h4>
        <ul style="list-style: none;">
            <!-- Lien vers le site (original) -->
            <li style="margin-bottom: 0.5rem;">
                <a href="index.php">
                    <i class="fas fa-globe"></i>
                    <span> Voir le site</span>
                </a>
            </li>
            <!-- Lien vers la boutique (à modifier) -->
            <li style="margin-bottom: 0.5rem;">
                <a href="#" id="shopLink">
                    <i class="fas fa-store"></i>
                    <span> Boutique</span>
                </a>
            </li>
            <!-- Lien vers l'espace administrateur (à modifier) -->
            <li style="margin-bottom: 0.5rem;">
                <a href="#" id="adminSpaceLink">
                    <i class="fas fa-user-shield"></i>
                    <span> Espace Admin</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="separator"></div>
    
    <div class="stats-sidebar">
        <div class="stat-item">
            <span><i class="fas fa-inbox"></i> Total messages</span>
            <span class="stat-value"><?= $count ?></span>
        </div>
        <div class="stat-item">
            <span><i class="fas fa-clock"></i> Dernier message</span>
            <span class="stat-value"><?= $count > 0 ? date('d/m', strtotime($messages[0]['created_at'])) : '---' ?></span>
        </div>
    </div>
    <div class="logout-btn">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1><i class="fas fa-message"></i> Gestion des messages</h1>
        <div class="badge"><i class="fas fa-chart-line"></i> <?= $count ?> message(s) reçu(s)</div>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="success">
            <i class="fas fa-check-circle"></i>
            Message supprimé avec succès
        </div>
    <?php endif; ?>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="🔍 Rechercher par nom, email...">
    </div>

    <div class="messages-grid">
        <?php if ($count === 0): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucun message</h3>
                <p>Les messages des visiteurs apparaîtront ici</p>
            </div>
        <?php endif; ?>

        <?php foreach ($messages as $msg): ?>
        <div class="message-card" data-name="<?= strtolower(htmlspecialchars($msg['name'])) ?>" data-email="<?= strtolower(htmlspecialchars($msg['email'])) ?>">
            <div class="message-header">
                <div class="message-name">
                    <h3><i class="fas fa-user-astronaut"></i> <?= htmlspecialchars($msg['name']) ?></h3>
                    <div class="message-email"><i class="fas fa-envelope"></i> <?= htmlspecialchars($msg['email']) ?></div>
                </div>
                <div class="message-date"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></div>
            </div>
            <div class="message-text">
                <i class="fas fa-quote-left" style="opacity:0.3; margin-right:5px;"></i>
                <?= nl2br(htmlspecialchars($msg['message'])) ?>
            </div>
            <?php if ($msg['screenshot']): ?>
                <div class="message-screenshot">
                    <img src="uploads/<?= htmlspecialchars($msg['screenshot']) ?>" alt="screenshot" onclick="window.open(this.src)">
                </div>
            <?php endif; ?>
            <div class="message-footer">
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn-reply"><i class="fas fa-reply"></i> Répondre</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce message ?')">
                    <?= infoCsrfField() ?>
                    <input type="hidden" name="delete" value="<?= (int) $msg['id'] ?>">
                    <button type="submit" class="btn-delete"><i class="fas fa-trash-alt"></i> Supprimer</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Menu mobile
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    function openMenu() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMenu() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (menuToggle) {
        menuToggle.addEventListener('click', openMenu);
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeMenu();
        }
    });

    // Lien vers la boutique
    document.getElementById('shopLink')?.setAttribute('href', '../LABTECH/index.php');
    
    // Lien vers l'espace administrateur
    document.getElementById('adminSpaceLink')?.setAttribute('href', '../LABTECH/admin-dashboard.php');
    
    // ============================================================
    
    // Recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const cards = document.querySelectorAll('.message-card');
            cards.forEach(card => {
                if (card.getAttribute('data-name')) {
                    const name = card.getAttribute('data-name');
                    const email = card.getAttribute('data-email');
                    if (name.includes(value) || email.includes(value)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    }
</script>
</body>
</html>
