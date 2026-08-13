<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../../functions/util.php');
    require('../../functions/db.php');
    
    $database = Database::getInstance();
    $conn = $database->getConnection();

    session_start();
    header('Content-Type: application/json');

    // 1. CSRF Kontrolü
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!csrf_check($csrf_token)) {
        echo json_encode(["status" => "error", "message" => "Geçersiz istek (CSRF hatası)!"]);
        exit;
    }

    // 2. Verileri Al (Kritik Düzeltme Burası)
    $table    = $_POST['table'] ?? null;
    $data     = json_decode($_POST['data'], true) ?? [];
    
    // JS'den doğrudan gönderilen 'kategori' değerini alıyoruz
    $kategori = $_POST['kategori'] ?? 'genel'; 

    if (!$table || empty($data)) {
        echo json_encode(["success" => false, "message" => "Tablo veya veri eksik!"]);
        exit;
    }

    try {
        // 3. Ana Kaydı Oluştur
        $projectId = $database->insert($table, $data);

        if (!$projectId) {
            throw new Exception("Veritabanına kayıt eklenemedi.");
        }

        // 4. Dosya Yükleme İşlemi
        $uploadDirBase = dirname(__DIR__, 2) . "/assets/images/";
        $mediaFiles = $_FILES['media_files'] ?? null;

        if ($mediaFiles && isset($mediaFiles['tmp_name'][0]) && $mediaFiles['tmp_name'][0] !== "") 
        {
            // Klasör yolu artık doğru: /assets/images/blog/
            $uploadDir = $uploadDirBase . $kategori; 

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($mediaFiles['tmp_name'] as $index => $tmpName)
            {
                if ($mediaFiles['error'][$index] === UPLOAD_ERR_OK)
                {
                    $originalName = basename($mediaFiles['name'][$index]);
                    $filename = uniqid() . "_" . $originalName;
                    $targetPath = $uploadDir . "/" . $filename;

                    if (move_uploaded_file($tmpName, $targetPath))
                    {
                        $relativePath = "assets/images/" . $kategori . "/" . $filename;
                        
                        $mimeType = mime_content_type($targetPath);
                        $tip = (strpos($mimeType, 'video') !== false) ? 1 : 0;

                        // İlişkili tabloya görseli kaydet
                        $imageInsertData = [
                            'proje_id'    => $projectId, // Blog için de olsa ortak sütun adın buysa böyle kalabilir
                            'gorsel_yolu' => $relativePath,
                            'gorsel_tipi' => $tip
                        ];

                        $database->insert('proje_gorseller', $imageInsertData);
                    }
                }
            }
        }

        echo json_encode([
            "success" => true, 
            "message" => "İşlem başarıyla tamamlandı!", 
            "id" => $projectId
        ]);

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>