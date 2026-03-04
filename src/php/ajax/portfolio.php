<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $results = $database->selectMulti("
        id, 
        proje_baslik_tr, 
        proje_baslik_en, 
        proje_teknolojiler, 
        meta_url_tr, 
        meta_url_en,
        (SELECT gorsel_yolu 
         FROM proje_gorseller 
         WHERE proje_gorseller.proje_id = projeler.id 
         ORDER BY id ASC 
         LIMIT 1) AS kapak_foto
        FROM projeler
    ");

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}