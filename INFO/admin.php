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

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([$id]);
    header("Location: admin.php");
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <div class="sidebar">
        <h2>DATALAB</h2>
        <ul>
            <li class="active">📊 Dashboard</li>
            <li>📩 Messages (<?= $count ?>)</li>
            <li>⚙️ Paramètres</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="top-bar">
            <h1>Dashboard</h1>
            <div class="badge">📩 <?= $count ?> messages</div>
        </div>
        <div class="cards-grid">
            <?php foreach ($messages as $msg): ?>
            <div class="message-card">
                <h3><?= htmlspecialchars($msg['name']) ?></h3>
                <p><?= htmlspecialchars($msg['message']) ?></p>
                <small><?= htmlspecialchars($msg['email']) ?></small>
                <?php if ($msg['screenshot']): ?>
                    <img src="uploads/<?= htmlspecialchars($msg['screenshot']) ?>" alt="screenshot">
                <?php endif; ?>
                <a href="?delete=<?= $msg['id'] ?>" class="btn-delete">Supprimer</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>