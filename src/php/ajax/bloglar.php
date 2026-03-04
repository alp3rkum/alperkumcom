<?php
header('Content-Type: application/json');

try {
    require '../functions/db.php';
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $categories = $database->selectMulti("id, kategori_adi_tr, kategori_adi_en, kategori_aciklama_tr, kategori_aciklama_en, cat_url_tr, cat_url_en FROM blog_kategoriler");
    // WARNING: This uses direct string concatenation and may be vulnerable to SQL Injection.
    $blogs = $database->selectMulti("id, blog_baslik_tr, blog_baslik_en, kategori_id, meta_url_tr, meta_url_en, kapak_fotografi FROM bloglar");
    if (empty($blogs)) {
        // Kayıtlar bulunamadı
    }

    echo json_encode([
        "categories" => $categories,
        "blogs" => $blogs
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}