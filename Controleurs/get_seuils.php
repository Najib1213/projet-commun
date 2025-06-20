<?php
require_once '../Modèles/database.php'; 

if (!isset($_GET['capteur'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Capteur manquant']);
    exit;
}

$capteur = $_GET['capteur'];

try {
    $stmt = $pdo->prepare("SELECT seuil, danger FROM seuils_capteurs WHERE capteur = ?");
    $stmt->execute([$capteur]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
