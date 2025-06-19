<?php
header('Content-Type: application/json');
require_once '../Modèles/db_pg.php';

try {
    $stmt = $pdo->query("SELECT hum AS value_percent, temps AS recorded_at FROM capteur_hum_temp ORDER BY temps ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
?>
