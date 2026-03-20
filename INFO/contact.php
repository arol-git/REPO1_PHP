<link rel="stylesheet" href="contact.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<?php
$postData = $_POST;

if (!isset($postData['email']) || !isset($postData['message']) || !isset($postData['name'])) {
    echo('Il faut un email, un message et un nom pour soumettre le formulaire.');
    // Arrête l'exécution de ce fichier par PHP
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

// Testons si le fichier a bien été envoyé et s'il n'y a pas des erreurs
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    
    // Testons, si le fichier est trop volumineux
    if ($_FILES['screenshot']['size'] > 1000000) {
        echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
        return;
    }
    
    // Testons, si l'extension n'est pas autorisée
    $fileInfo = pathinfo($_FILES['screenshot']['name']);
    $extension = $fileInfo['extension'];
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
        return;
    }
    // Testons, si le dossier uploads est manquant
    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
        return;

    }
    move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . basename($_FILES['screenshot']['name']));
}


?>

<?php require_once(__DIR__ . '/header.php'); ?>

<div class="contenaire">
    <h1>Message bien reçu !</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Rappel de vos informations</h5>
            <p><b>Nom</b> : <?php echo $_POST['name']; ?></p>
            <p><b>Email</b> : <?php echo $_POST['email']; ?></p>
            <p><b>Message</b> : <?php echo $_POST['message']; ?></p>
        </div>
    </div>
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>