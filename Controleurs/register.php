<?php
require_once '../Modèles/utilisateur.php';

function passwordRespecteLesCritères($mdp) {
    return strlen($mdp) >= 8 &&
           preg_match('/[a-z]/', $mdp) &&
           preg_match('/[A-Z]/', $mdp) &&
           preg_match('/[0-9]/', $mdp) &&
           preg_match('/[^a-zA-Z0-9]/', $mdp);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug temporaire pour vérifier la réception du POST
    file_put_contents(__DIR__ . '/debug_post.txt', print_r($_POST, true));

    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!preg_match('/@.+\.isep\.fr$/i', $email)) {
        header('Location: ../Vues/Inscription.php?error=L\'adresse email doit se terminer par ".isep.fr".');
        exit;
    }

    if ($password !== $confirm) {
        header('Location: ../Vues/Inscription.php?error=Les mots de passe ne correspondent pas.');
        exit;
    }

    if (!passwordRespecteLesCritères($password)) {
        header('Location: ../Vues/Inscription.php?error=Le mot de passe ne respecte pas les critères requis.');
        exit;
    }

    if (creerUtilisateur($prenom, $nom, $email, $password)) {
        header('Location: ../Vues/Connexion.php');
        exit;
    } else {
        header('Location: ../Vues/Inscription.php?error=Erreur lors de la création du compte (email existant ?).');
        exit;
    }
}
?>