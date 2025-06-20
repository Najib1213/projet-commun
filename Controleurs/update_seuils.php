<?php
require_once '../Modèles/database.php';

if (!isset($_POST['capteur'], $_POST['seuil'], $_POST['danger'])) {
    http_response_code(400);
    exit("Paramètres manquants");
}

$capteur = $_POST['capteur'];
$seuil = $_POST['seuil'];
$danger = $_POST['danger'];

try {
    $stmt = $pdo->prepare("UPDATE seuils_capteurs SET seuil = ?, danger = ? WHERE capteur = ?");
    $stmt->execute([$seuil, $danger, $capteur]);
    echo "OK";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur lors de la mise à jour";
}
