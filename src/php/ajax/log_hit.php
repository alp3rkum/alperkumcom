<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../functions/db.php';
    $database = Database::getInstance();

    // Frontend'den gelen JSON string'i çözüyoruz
    $jsonData = $_POST['data'] ?? null;
    $data = json_decode($jsonData, true);

    // Temel veri kontrolü (Sayfa URL'si zorunlu)
    if (!$data || !isset($data['page_url'])) {
        echo json_encode(["success" => false, "message" => "Sayfa bilgisi bulunamadı!"]);
        exit;
    }

    try {
        // Sunucu değişkenlerinden otomatik bilgileri alalım
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $referrer  = $_SERVER['HTTP_REFERER'] ?? ($data['referrer'] ?? null);

        // Tabloya eklenecek veriyi hazırla
        $insertData = [
            'page_url'   => $data['page_url'],
            'referrer'   => $referrer,
            'user_id'    => isset($data['user_id']) ? (int)$data['user_id'] : null,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ];

        // Kaydı gerçekleştir
        $insertedId = $database->insert('page_hits', $insertData);

        echo json_encode([
            "success" => true,
            "message" => "Hit başarıyla kaydedildi.",
            "hit_id"  => $insertedId
        ], JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Hit kaydedilirken hata oluştu: " . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}
?>