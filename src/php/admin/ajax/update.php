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
    $where = $_POST['where'] ?? null;
    $resim_sutun = $_POST['resim_sutun'] ?? null;

    if (!$table || !$data || !is_array($data) || !$where) {
        echo json_encode([
            "success" => false,
            "message" => "Table, data or where condition not specified!"
        ]);
        exit;
    }

    try {
        // 1. Projeyi güncelle
        $database->update($table, $data, $where);

        // 2. Proje ID'sini al
        $projectId = trim(explode('=', $where)[1]);

        // 3. Dosya yükleme
        $kategori = $_POST['kategori'] ?? 'proje';
        $uploadDirBase = dirname(__DIR__, 2) . "/assets/images/";
        $mediaFiles = $_FILES['media_files'] ?? null;

        if ($mediaFiles && $mediaFiles['tmp_name'][0] !== "") {
            $uploadDir = $uploadDirBase . $kategori;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageInsertData = [];

            foreach ($mediaFiles['tmp_name'] as $index => $tmpName) {
                if ($mediaFiles['error'][$index] === UPLOAD_ERR_OK) {
                    $originalName = basename($mediaFiles['name'][$index]);
                    $filename = uniqid() . "_" . $originalName;
                    $targetPath = $uploadDir . "/" . $filename;

                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $relativePath = "assets/images/" . $kategori . "/" . $filename;
                        $mimeType = mime_content_type($targetPath);
                        $tip = (strpos($mimeType, 'video') !== false) ? 1 : 0;
                        if($resim_sutun != null)
                        {
                            $data[$resim_sutun] = $relativePath;
                            $database->update($table, $data, $where);
                        }
                        else
                        {
                            $imageInsertData[] = [
                                'proje_id'    => $projectId,
                                'gorsel_yolu' => $relativePath,
                                'gorsel_tipi' => $tip
                            ];
                        }
                        
                    } else {
                        throw new Exception("Dosya taşınamadı: " . $originalName);
                    }
                }
            }

            if (!empty($imageInsertData)) {
                foreach($imageInsertData as $imgData) {
                    $database->insert($resim_tablo, $imgData);
                }
            }
        }

        echo json_encode([
            "success" => true,
            "message" => "Proje başarıyla güncellendi!",
            "data" => $data,
            "id" => $projectId
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
?>