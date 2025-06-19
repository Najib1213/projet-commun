<?php
header('Content-Type: application/json');
require_once '../Modèles/db_pg.php';

try {
    $stmt = $pdo->query("SELECT value_percent, recorded_at FROM luminosity_readings");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
?>
