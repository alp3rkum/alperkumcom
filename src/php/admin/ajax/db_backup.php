<?php
require('../../functions/util.php');
require '../../functions/db.php';
$database = Database::getInstance();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek"
    ]);
    exit;
}

session_start();
header('Content-Type: application/json');

$csrf_token = $_POST['csrf_token'] ?? '';
if (!csrf_check($csrf_token)) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek (CSRF hatası)!"
    ]);
    exit;
}

$response = [];

try {
    if ($_POST['mode'] == 'backup') {
        $database->backup();
        $response = ["status" => "success", "message" => "Veritabanı başarıyla yedeklendi."];
    } elseif ($_POST['mode'] == 'restore') {
        $database->restore();
        $response = ["status" => "success", "message" => "Veritabanı başarıyla geri yüklendi."];
    } else {
        throw new Exception("Yanlış mod seçildi.");
    }
} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

echo json_encode($response);
?>