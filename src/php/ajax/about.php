<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $globalVars = $database->getGlobalVars('hakkinda_icerik','hakkinda_icerik_en');

    $results = [
        "hakkinda_icerik"    => $globalVars['hakkinda_icerik'],
        "hakkinda_icerik_en" => $globalVars['hakkinda_icerik_en']
    ];

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}