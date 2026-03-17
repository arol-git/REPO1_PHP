
<h1>Message bien reçu !</h1>
        
<div class="card">
    
    <div class="card-body">
        <h5 class="card-title">Rappel de vos informations</h5>
        <p class="card-text"><b>Email</b> : <?php echo $_GET['email']; ?></p>
        <p class="card-text"><b>Message</b> : <?php echo $_GET['message']; ?></p>
    </div>
</div>
<?php
$getData = $_GET;

if (!isset($getData['email']) || !isset($getData['message']))
{
    echo('Il faut un email et un message pour soumettre le formulaire.');
    // Arrête l'exécution de ce fichier par PHP
    return;
}
?>

<?php
$getData = $_GET;

if (
    !isset($getData['email'])
    || !filter_var($getData['email'], FILTER_VALIDATE_EMAIL)
    || empty($getData['message'])
    || trim($getData['message']) === ''
) {
    echo('Il faut un email et un message valides pour soumettre le formulaire.');
    return;
}