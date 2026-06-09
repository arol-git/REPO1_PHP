<?php
// Configuration de la base de données
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'ecommerce_db';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch(PDOException $e) {
    error_log("Erreur de connexion BDD : " . $e->getMessage());
    die("Erreur de connexion à la base de données.");
}

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function requireAdmin() {
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: admin-login.php');
        exit;
    }
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrfToken($token) {
    return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function requireValidCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Jeton de sécurité invalide.');
    }
}

function saveUploadedImage($file, $uploadDir) {
    if (!isset($file) || !is_array($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException("L'image est invalide ou trop volumineuse.");
    }

    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!isset($allowedMimeTypes[$mimeType])) {
        throw new RuntimeException("Le fichier envoyé n'est pas une image autorisée.");
    }

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException("Le dossier d'upload est indisponible.");
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowedMimeTypes[$mimeType];
    $destination = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException("L'image n'a pas pu être enregistrée.");
    }

    return $filename;
}

// Taux de change (1 EUR = 655.96 XAF)
define('EURO_TO_XAF', 655.96);

// Fonction pour convertir en FCFA
function formatPrice($priceInEuro) {
    $priceInXaf = $priceInEuro * EURO_TO_XAF;
    return number_format($priceInXaf, 0, ',', ' ') . ' FCFA';
}

// Fonction pour tronquer le texte
function truncateText($text, $maxLength = 50) {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $maxLength) return $text;
    return mb_substr($text, 0, $maxLength) . '...';
}

// Fonction pour obtenir la couleur du stock
function getStockColor($stock) {
    if ($stock > 10) return '#00ff88';
    if ($stock > 0) return '#ffaa00';
    return '#ff4444';
}

// Fonction pour obtenir le texte du stock
function getStockText($stock) {
    if ($stock > 10) return '✅ En stock';
    if ($stock > 0) return '⚠️ Stock limité';
    return '❌ Rupture de stock';
}

// Fonction pour vérifier si un produit est nouveau (moins de 30 jours)
function isNewProduct($created_at) {
    if(empty($created_at)) return false;
    $date = new DateTime($created_at);
    $now = new DateTime();
    $diff = $date->diff($now);
    return $diff->days < 30;
}

// Fonction pour obtenir le badge d'un produit
function getProductBadge($created_at, $featured = false) {
    $badges = [];
    
    if(isNewProduct($created_at)) {
        $badges[] = '<span class="badge badge-new">✨ Nouveau</span>';
    }
    if($featured) {
        $badges[] = '<span class="badge badge-hot">🔥 Top vente</span>';
    }
    
    return implode('', $badges);
}



// Fonction pour obtenir le nombre de jours depuis l'ajout
function getDaysSinceAdded($created_at) {
    if(empty($created_at)) return 0;
    $date = new DateTime($created_at);
    $now = new DateTime();
    $diff = $date->diff($now);
    return $diff->days;
}
?>
