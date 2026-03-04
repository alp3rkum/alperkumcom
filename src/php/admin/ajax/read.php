<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require('../../functions/util.php');
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

    $table = $_POST['table'] ?? null;
    $columns = $_POST['columns'] ?? "*"; // İstenirse kolon listesi, yoksa *
    $where = $_POST['where'] ?? "";      // Opsiyonel koşul
    $limit = $_POST['limit'] ?? "";      // Opsiyonel limit

    if (!$table) {
        echo json_encode([
            "success" => false,
            "message" => "No table specified!"
        ]);
        exit;
    }

    try {
        // Sorgu oluştur
        $sql = "$columns FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT " . intval($limit);
        }
        $result = $database->selectMulti($sql);

        echo json_encode([
            "success" => true,
            "message" => "Data fetched successfully.",
            "data"    => $result
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
?>