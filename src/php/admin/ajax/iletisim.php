<?php
require '../../functions/db.php';
$database = Database::getInstance();
$conn = $database->getConnection();

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek metodu."]);
    exit;
}

$updateData = [
    'email_adres' => $_POST['email_adres'] ?? '',
    'telefon_numarasi' => $_POST['telefon_numarasi'] ?? '',
    'calisma_saatleri_haftaici' => $_POST['calisma_saatleri_haftaici'] ?? '',
    'calisma_saatleri_cumartesi' => $_POST['calisma_saatleri_cumartesi'] ?? '',
    'calisma_saatleri_pazar' => $_POST['calisma_saatleri_pazar'] ?? ''
];

// updateGlobalVars fonksiyonunu çağır
$resultMessage = $database->updateGlobalVars($updateData);

// Yanıtı, fonksiyonun döndürdüğü stringe göre hazırla
if (strpos($resultMessage, 'başarılı') !== false) {
    echo json_encode([
        "status" => "success",
        "message" => "İletişim ve çalışma saatleri başarıyla güncellendi!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $resultMessage // Fonksiyondan gelen detaylı hata mesajı
    ]);
}
?>