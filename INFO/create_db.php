<?php
try {
    $dbHost = getenv('INFO_DB_HOST') ?: 'localhost';
    $dbName = getenv('INFO_DB_NAME') ?: 'contact_form';
    $dbUser = getenv('INFO_DB_USER') ?: 'root';
    $dbPassword = getenv('INFO_DB_PASSWORD') ?: '';

    if (!preg_match('/^[A-Za-z0-9_]+$/', $dbName)) {
        throw new PDOException('Nom de base de données invalide');
    }

    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    echo "Base de données créée avec succès !";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
