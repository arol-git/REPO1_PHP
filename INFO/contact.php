<?php
try {
    $dbHost = getenv('INFO_DB_HOST') ?: 'localhost';
    $dbName = getenv('INFO_DB_NAME') ?: 'contact_form';
    $dbUser = getenv('INFO_DB_USER') ?: 'root';
    $dbPassword = getenv('INFO_DB_PASSWORD') ?: '';

    if (!preg_match('/^[A-Za-z0-9_]+$/', $dbName)) {
        throw new PDOException('Nom de base de données invalide');
    }

    $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `$dbName`;");
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        screenshot VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    error_log("Erreur BDD INFO/contact.php : " . $e->getMessage());
    echo "Erreur de connexion ou création de la base.";
    return;
}

$postData = $_POST;

if (!isset($postData['email']) || !isset($postData['message']) || !isset($postData['name'])) {
    echo('Il faut un email, un message et un nom pour soumettre le formulaire.');
    return;
}
if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
    echo('L\'email doit être valide pour soumettre le formulaire.');
    return;
}
if (empty($postData['message']) || trim($postData['message']) === '') {
    echo('Le message ne peut pas être vide pour soumettre le formulaire.');
    return;
}
if (empty($postData['name']) || trim($postData['name']) === '') {
    echo('Le nom ne peut pas être vide pour soumettre le formulaire.');
    return;
}

$screenshotName = null;
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    if ($_FILES['screenshot']['size'] > 1000000) {
        echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
        return;
    }

    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($_FILES['screenshot']['tmp_name']);

    if (!isset($allowedMimeTypes[$mimeType])) {
        echo "L'envoi n'a pas pu être effectué, le fichier n'est pas une image autorisée";
        return;
    }

    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
        return;
    }
    $screenshotName = bin2hex(random_bytes(16)) . '.' . $allowedMimeTypes[$mimeType];
    if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . $screenshotName)) {
        echo "L'envoi n'a pas pu être effectué, erreur d'enregistrement";
        return;
    }
}

try {
    $sql = "INSERT INTO messages (name, email, message, screenshot) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $postData['name'],
        $postData['email'],
        $postData['message'],
        $screenshotName
    ]);
} catch (PDOException $e) {
    error_log("Erreur insertion INFO/contact.php : " . $e->getMessage());
    echo "Erreur lors de l'insertion en base.";
    return;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Reçu - DATALAB-TECH</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php require_once(__DIR__ . '/header.php'); ?>

    <main style="min-height: 70vh; display: flex; align-items: center;">
        <div class="container" style="text-align: center;">
            <div style="max-width: 600px; margin: 0 auto;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                <h1 style="color: var(--accent-primary); margin-bottom: 1rem;">Message bien reçu !</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 2rem;">Merci d'avoir pris le temps de me contacter. Je vous répondrai dès que possible.</p>
                
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 2rem; border-radius: 16px; text-align: left; margin-bottom: 2rem;">
                    <h3 style="color: var(--accent-primary); margin-bottom: 1rem;">Récapitulatif de votre message :</h3>
                    <p><strong style="color: var(--accent-primary);">Nom :</strong> <?php echo htmlspecialchars($postData['name']); ?></p>
                    <p><strong style="color: var(--accent-primary);">Email :</strong> <?php echo htmlspecialchars($postData['email']); ?></p>
                    <p><strong style="color: var(--accent-primary);">Message :</strong><br><?php echo nl2br(htmlspecialchars($postData['message'])); ?></p>
                </div>
                
                <a href="index.php" class="btn-primary" style="display: inline-block;">← Retour à l'accueil</a>
            </div>
        </div>
    </main>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
