<?php
require_once 'database.php';

function creerUtilisateur($prenom, $nom, $email, $mot_de_passe) {
    global $pdo;
    $hash = hash('sha256', $mot_de_passe);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$prenom, $nom, $email, $hash]);
    } catch (PDOException $e) {
        file_put_contents(__DIR__ . '/log_erreurs.txt', $e->getMessage()."\n", FILE_APPEND);
        return false;
    }
}

function verifierConnexion($email, $mot_de_passe) {
    global $pdo;
    $hash = hash('sha256', $mot_de_passe);
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?");
    $stmt->execute([$email, $hash]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>