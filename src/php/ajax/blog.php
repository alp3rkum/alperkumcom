<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $results = $database->selectSingle("id, blog_baslik_tr, blog_icerik_tr, kapak_fotografi FROM bloglar WHERE meta_url_tr = ?",[$_GET['url']]);

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}