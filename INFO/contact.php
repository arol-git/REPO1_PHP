<?php
$getData = $_GET;

if (!isset($getData['email']) || !isset($getData['message']))
{
    echo('Il faut un email et un message pour soumettre le formulaire.');
    // Arrête l'exécution de ce fichier par PHP
    return;
}
if (!filter_var($getData['email'], FILTER_VALIDATE_EMAIL)) {
    echo('L\'email doit être valide pour soumettre le formulaire.');
    return;
}
if (empty($getData['message']) || trim($getData['message']) === '') {
    echo('Le message ne peut pas être vide pour soumettre le formulaire.');
    return;
}
?>  
<?php
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

<?php
$getData = $_GET;

if (
    !isset($getData['name'])
    || !isset($getData['email'])
    || !filter_var($getData['email'], FILTER_VALIDATE_EMAIL)
    || empty($getData['message'])
    || trim($getData['message']) === ''
) {
    echo('Il faut un email et un message valides pour soumettre le formulaire.');
    return;
}
?>
<h1>Message bien reçu !</h1>
        
<div class="card">
    
    <div class="card-body">
        <h5 class="card-title">Rappel de vos informations</h5>
        <p class="card-text"><b>Nom</b> : <?php echo $_GET['name']; ?></p>
        <p class="card-text"><b>Email</b> : <?php echo $_GET['email']; ?></p>
        <p class="card-text"><b>Message</b> : <?php echo $_GET['message']; ?></p>
    </div>
</div>
