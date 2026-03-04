<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../../functions/util.php');
    require('../../functions/db.php');
    $database = Database::getInstance();
    $conn = $database->getConnection();

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

    $table = $_POST['table'] ?? null;
    $data  = json_decode($_POST['data'],true) ?? null;
    $where = $_POST['where'] ?? null; // update.php'de 'where' koşulu olmalı (örn: id = 5)

    if (!$table || !$data || !is_array($data) || !$where) {
        echo json_encode([
            "success" => false,
            "message" => "Table, data or where condition not specified!"
        ]);
        exit;
    }

    try {
        // 1. Projeyi Güncelle
        $database->update($table, $data, $where);
        
        // Proje ID'sini WHERE koşulundan çıkarın (örneğin 'id = 5' ise 5'i al)
        $projectId = explode('=', $where)[1]; 
        // NOT: Bu basit ayırma yöntemi, 'where' sorgunuzun formatına bağlıdır. 
        // Güvenli olması için, önce SELECT yapıp ID'yi çekmek daha iyidir, ama basitleştiriyoruz.

        // 2. Dosya Yükleme İşlemi (create.php'deki mantığın aynısı)
        $kategori = $data['kategori'] ?? 'proje'; // Kategori bilgisini POST'tan almayı veya varsaymayı unutmayın
        $uploadDirBase = dirname(__DIR__, 2) . "/assets/images/";
        $mediaFiles = $_FILES['media_files'] ?? null;

        if ($mediaFiles && $mediaFiles['tmp_name'][0] !== "") 
        {
            $uploadDir = $uploadDirBase . $kategori; 

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageInsertData = [];
            
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

                        // Veri yapısı
                        $imageInsertData[] = [
                            'proje_id'    => $projectId, // ÖNEMLİ: Mevcut proje ID'si kullanılıyor
                            'gorsel_yolu' => $relativePath,
                            'gorsel_tipi' => $tip
                        ];
                    }
                    else
                    {
                        throw new Exception("Dosya taşınamadı: " . $originalName);
                    }
                }
            }
            
            if (!empty($imageInsertData)) {
                foreach($imageInsertData as $imgData) {
                    // Proje_gorseller tablosuna yeni kayıt ekle
                    $database->insert('proje_gorseller', $imgData);
                }
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Proje ve medya dosyaları başarıyla güncellendi!",
            "id" => $projectId // Güncellenen ID'yi döndürüyoruz
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
?>