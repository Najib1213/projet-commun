<?php
session_start();
require_once '../Modèles/utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifications serveur (anti-bypass JS)
    if (!preg_match('/@.+\.isep\.fr$/i', $email)) {
        die("Adresse email invalide. Elle doit se terminer par '@xxxxx.isep.fr'");
    }

    if (trim($password) === '') {
        die("Mot de passe requis.");
    }

    $user = verifierConnexion($email, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: ../Vues/Accueil.php');
        exit;
    } else {
        die("Email ou mot de passe incorrect.");
    }
}