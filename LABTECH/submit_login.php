<?php
session_start();
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


$postData = $_POST;

// Validation du formulaire
if (isset($postData['email']) && isset($postData['password'])) {
if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
     $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Il faut un email valide pour soumettre le formulaire.';
} else {
     foreach ($users as $user) {
         if (
         $user['email'] === $postData['email'] &&
         password_verify($postData['password'], $user['password_hash'])
         ) {
         session_regenerate_id(true);
         $_SESSION['LOGGED_USER'] = [
             'email' => $user['email'],
             'user_id' => $user['user_id'],
         ];
         }
     }

     if (!isset($_SESSION['LOGGED_USER'])) {
         $_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
         'Les informations envoyées ne permettent pas de vous identifier : %s',
         $postData['email']
         );
     }
}

redirectToUrl('index.php');
}
