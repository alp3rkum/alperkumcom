<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek"
    ]);
    exit;
}


session_start();

// CSRF kontrolü
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz CSRF token"
    ]);
    exit;
}

// Dosya kontrolü
if (!isset($_FILES['file-input']) || $_FILES['file-input']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "status" => "error",
        "message" => "Dosya yüklenemedi"
    ]);
    exit;
}

// Yüklenen dosya bilgileri
$fileTmpPath = $_FILES['file-input']['tmp_name'];
$fileType = mime_content_type($fileTmpPath);

// Sadece resim kabul et
$allowedTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
if (!in_array($fileType, $allowedTypes)) {
    echo json_encode([
        "status" => "error",
        "message" => "Sadece resim dosyaları yüklenebilir"
    ]);
    exit;
}

// Hedef dosya yolu
$destination = __DIR__ . '/../logo.png';

// Dosyayı taşı
if (move_uploaded_file($fileTmpPath, $destination)) {
    echo json_encode([
        "status" => "success",
        "message" => "Logo başarıyla yüklendi"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Dosya taşınırken hata oluştu"
    ]);
}