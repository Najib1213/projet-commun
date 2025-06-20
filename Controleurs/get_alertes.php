<?php
header('Content-Type: application/json');
ini_set('display_errors', 0); // NE PAS afficher les erreurs en sortie
ob_clean(); // Efface tout contenu déjà envoyé (notices, espaces...)

require_once '../Modèles/database.php'; // Connexion MySQL locale

try {
    $stmt = $pdo->query("SELECT capteur, niveau, niveau_alerte, message, horodatage 
                         FROM alertes 
                         ORDER BY horodatage DESC 
                         LIMIT 20");

    $alertes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($alertes);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
