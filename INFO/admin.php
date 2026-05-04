<?php
$user = "admin";
$pass = "1234";

if (!isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] != $user ||
    $_SERVER['PHP_AUTH_PW'] != $pass) {

    header('WWW-Authenticate: Basic realm="Admin"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Accès refusé');
}

$pdo = new PDO('mysql:host=localhost;dbname=contact_form;charset=utf8', 'root', '');

// suppression
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([$id]);
    header("Location: admin.php");
    exit;
}

// récupération
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();
$count = count($messages);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="sidebar">
    <h2>DATALAB</h2>
    <ul>
        <li class="active">📊 Dashboard</li>
        <li>📩 Messages</li>
        <li>⚙️ Paramètres</li>
    </ul>
</div>

<div class="main">

    <div class="top">
        <h1>Dashboard</h1>
        <div class="badge">📩 <?= $count ?> messages</div>
    </div>

    <div class="cards">

        <?php foreach ($messages as $msg): ?>
        <div class="card fade-in">
            <h3><?= htmlspecialchars($msg['name']) ?></h3>
            <p><?= htmlspecialchars($msg['message']) ?></p>
            <span><?= htmlspecialchars($msg['email']) ?></span>

            <?php if ($msg['screenshot']): ?>
                <img src="uploads/<?= htmlspecialchars($msg['screenshot']) ?>">
            <?php endif; ?>

            <a href="?delete=<?= $msg['id'] ?>" class="btn">Supprimer</a>
        </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>