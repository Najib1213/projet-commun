<?php
session_start();
require_once '../Modèles/utilisateur.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifications serveur (anti-bypass JS)
    if (!preg_match('/@.+\.isep\.fr$/i', $email)) {
        header('Location: ../Vues/Connexion.php?error=Adresse email invalide (format .isep.fr attendu)');
        exit;
    }

    if (trim($password) === '') {
        header('Location: ../Vues/Connexion.php?error=Mot de passe requis.');
        exit;
    }

    $user = verifierConnexion($email, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: ../Vues/tableau.php');
        exit;
    } else {
        header('Location: ../Vues/Connexion.php?error=Email ou mot de passe incorrect.');
        exit;
    }
}
