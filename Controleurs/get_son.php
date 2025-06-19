<?php
header('Content-Type: application/json');
require_once '../Modèles/db_pg.php';

try {
    $stmt = $pdo->query("SELECT db_level AS value_percent, created_at AS recorded_at FROM sound_measurements ORDER BY created_at ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
?>
