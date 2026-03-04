<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $results = $database->selectMulti("
        id, proje_baslik_tr, proje_aciklama_tr, proje_baslik_en, proje_aciklama_en, proje_teknolojiler, meta_url_tr, meta_url_en,
        (SELECT gorsel_yolu FROM proje_gorseller WHERE proje_id = projeler.id ORDER BY id ASC LIMIT 1) AS kapak_foto, meta_title_tr, meta_desc_tr, meta_keyword_tr, meta_title_en, meta_desc_en, meta_keyword_en
        FROM projeler
        WHERE meta_url_tr = ? OR meta_url_en = ?
    ", [$_GET['url'], $_GET['url']]);

    $projectId = $results[0]['id'];

    $gorseller = $database->selectMulti("gorsel_yolu FROM proje_gorseller WHERE proje_id = ?", [$projectId]);

    echo json_encode([
        "project" => $results[0],
        "gorseller" => $gorseller
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}