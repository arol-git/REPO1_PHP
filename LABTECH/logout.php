<?php
session_start();
session_destroy();

// Redirection basée sur la source
// Si vient d'admin-dashboard/catalog/orders, rediriger vers admin-login
// Sinon vers l'accueil
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if(strpos($referer, 'admin-') !== false) {
    header('Location: admin-login.php');
} else {
    header('Location: index.php');
}
exit;
?>