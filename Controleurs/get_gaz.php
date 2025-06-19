<?php
header('Content-Type: application/json');
require_once '../Modèles/db_pg.php';

try {
    $stmt = $pdo->query("SELECT cap_fumee_val AS value_percent, cap_fumee_time AS recorded_at FROM pc_g8 ORDER BY cap_fumee_time ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(500);
}
?>
