<?php
require('../../functions/util.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require '../../functions/db.php';
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

    $table = $_POST['table'];
    $where = $_POST['where'];

    try {
        $rows = $database->selectMulti("* FROM `$table` WHERE $where");

        // 2. Dosya yolu kontrolü
        foreach ($rows as $row) {
            foreach ($row as $col => $val) {
                if (is_string($val) && strpos($val, 'assets/') !== false) {
                    $filePath = realpath(__DIR__ . '/../../' . $val);
                    if ($filePath && file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
        $database->delete($table, $where);

        echo json_encode([
            "success" => true,
            "message" => "Kayıt ve ilgili dosyalar başarıyla silindi!"
        ]);
    } catch(Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
?>