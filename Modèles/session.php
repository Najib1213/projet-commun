<?php
session_start();

function utilisateurConnecte() {
    return isset($_SESSION['user']);
}

function deconnexion() {
    session_unset();
    session_destroy();
}

function getPrenomConnecte() {
    return $_SESSION['user']['prenom'] ?? '';
}
?>
