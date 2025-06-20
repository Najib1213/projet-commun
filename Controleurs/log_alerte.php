<?php
require_once '../Modèles/database.php'; // Connexion MySQL locale

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $capteur = $_POST['capteur'];
    $niveau = $_POST['niveau'];
    $niveau_alerte = $_POST['niveau_alerte'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO alertes (capteur, niveau, niveau_alerte, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$capteur, $niveau, $niveau_alerte, $message]);

    echo json_encode(["success" => true]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>
