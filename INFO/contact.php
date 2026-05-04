<?php
// Connexion à la base (déplacée en haut pour éviter les répétitions)
$pdo = new PDO('mysql:host=localhost;dbname=contact_form;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active les exceptions pour les erreurs

$postData = $_POST;

// Validations (inchangées)
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

// Gestion du fichier (inchangée)
$screenshotName = null;
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    if ($_FILES['screenshot']['size'] > 1000000) {
        echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
        return;
    }
    $fileInfo = pathinfo($_FILES['screenshot']['name']);
    $extension = $fileInfo['extension'];
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
        return;
    }
    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
        return;
    }
    $screenshotName = basename($_FILES['screenshot']['name']);
    move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . $screenshotName);
}

// Insertion en base (déplacée ici, après validations)
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
    echo "Erreur lors de l'insertion en base : " . $e->getMessage();
    return;
}

// Affichage du succès (inchangé)
?>
<link rel="stylesheet" href="contact.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php require_once(__DIR__ . '/header.php'); ?>

<div class="contenaire">
    <h1>Message bien reçu !</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Rappel de vos informations</h5>
            <p><b>Nom</b> : <?php echo htmlspecialchars($postData['name']); ?></p>
            <p><b>Email</b> : <?php echo htmlspecialchars($postData['email']); ?></p>
            <p><b>Message</b> : <?php echo htmlspecialchars($postData['message']); ?></p>
        </div>
    </div>
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>