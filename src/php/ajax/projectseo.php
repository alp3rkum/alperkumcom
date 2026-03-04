<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    // URL parametresini al
    $url = $_GET['url'];

    // Sadece SEO alanlarını çek
    $results = $database->selectSingle("
        meta_title_tr, meta_desc_tr, meta_keyword_tr,
        meta_title_en, meta_desc_en, meta_keyword_en
        FROM projeler
        WHERE meta_url_tr = ? OR meta_url_en = ?
    ", [$url, $url]);

    echo json_encode($results);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>