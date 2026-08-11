<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $is_offline = intval($database->getGlobalVars('offline')['var_value']); // Pass keys as a comma-separated string or individual args if you modify the function;

    echo json_encode(['is_offline' => $is_offline]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}