<?php
session_start();

$csrf_token = $_POST['csrf_token'] ?? '';
if ($csrf_token !== $_SESSION['csrf_token']) {
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek (CSRF hatası)!"
    ]);
    exit;
}

unset($_SESSION['admin']);

echo json_encode([
    "status" => "success",
    "message" => "Oturum başarıyla kapatıldı.",
    "redirect" => "/admin/"
]);
exit;
?>