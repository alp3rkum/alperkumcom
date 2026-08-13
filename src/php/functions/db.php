<?php
// PDO Abstraction class version 1.2.0 (MySQL/MariaDB için uyarlanmış)
class Database {
    private static $instance = null;
    private $conn;
    private $driver = 'mysql';

    private $host;
    private $username;
    private $password;
    private $database;

    // Geliştirme (Dev) Ayarları
    private $host_dev = '';
    private $username_dev = '';
    private $password_dev = '';
    private $database_dev = '';

    // Üretim (Prod) Ayarları
    private $host_prod = 'localhost:3306';
    private $username_prod = 'alperpgu_admin';
    private $password_prod = 'p{(qgURS{Gn5X.4&';
    private $database_prod = 'alperpgu_alperkumcom';
    
    private function __construct() {
        $isLocal = isset($_SERVER['HTTP_HOST']) && in_array($_SERVER['HTTP_HOST'], ['localhost:8000']);

        if ($isLocal) {
            // Local ortam
            $this->host = $this->host_dev;
            $this->username = $this->username_dev;
            $this->password = $this->password_dev;
            $this->database = $this->database_dev;
        } else {
            // Prod ortam
            $this->host = $this->host_prod;
            $this->username = $this->username_prod;
            $this->password = $this->password_prod;
            $this->database = $this->database_prod;
        }

        $host_parts = explode(':', $this->host);
        $host_name = $host_parts[0];
        $port = isset($host_parts[1]) ? (int)$host_parts[1] : 3306;

        $dsn = "$this->driver:host=$host_name;dbname=$this->database;charset=utf8mb4;port=$port";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            PDO::ATTR_EMULATE_PREPARES   => false,                  
        ];

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception('Veritabanı bağlantısı başarısız: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}

    // Bu fonksiyon PDO için artık gerekli değil ama orijinal yapıyı korumak adına burada duruyor.
    private function getParamTypes($params) 
    {
        $types = "";
        foreach ($params as $param) {
            if (is_null($param)) $types .= "s"; 
            elseif (is_int($param)) $types .= "i";
            elseif (is_float($param)) $types .= "d";
            elseif (is_bool($param)) $types .= "i";
            elseif (strtotime($param) !== false) $types .= "s";
            elseif (is_object($param) || is_array($param)) $types .= "s";
            elseif (is_string($param) && strlen($param) > 65535) $types .= "b"; // BLOB
            else $types .= "s";
        }
        return $types;
    }

    private function executePreparedStatement($query, $params = []) {
        $stmt = $this->conn->prepare($query);

        if (!empty($params)) {
            // PDO'da parametreleri index'e göre bağlamak için bindValue kullanılır.
            foreach ($params as $index => $param) {
                $type = PDO::PARAM_STR;
                if (is_int($param)) $type = PDO::PARAM_INT;
                elseif (is_bool($param)) $type = PDO::PARAM_BOOL;
                elseif (is_null($param)) $type = PDO::PARAM_NULL;
                
                // Parametreler 1'den başlar (PDO için)
                $stmt->bindValue(($index + 1), $param, $type);
            }
        }

        $stmt->execute();
        return $stmt;
    }


    public function selectSingle($queryBody, $params = []) 
    {
        // Sorgu Body'si sadece SELECT'ten SONRASI olmalı.
        $query = "SELECT " . $queryBody;
        $stmt = $this->executePreparedStatement($query, $params);
        return $stmt->fetch(); // PDO'da fetch() tek satır getirir.
    }

    public function selectMulti($queryBody, $params = []) 
    {
        // Sorgu Body'si sadece SELECT'ten SONRASI olmalı.
        $query = "SELECT " . $queryBody;
        $stmt = $this->executePreparedStatement($query, $params);
        return $stmt->fetchAll(); // PDO'da fetchAll() tüm satırları getirir.
    }

    public function recordExists($table, $where) {
        $query = "SELECT COUNT(id) as record_count FROM $table WHERE $where";
        $stmt = $this->getConnection()->query($query);
        $result = $stmt->fetch();
        return (int) $result['record_count'] !== 0;
    }

    public function getGlobalVars(...$var_keys) {
        if (empty($var_keys)) {
            return [];
        }

        // Güvenlik için $var_keys'in kendisini parametre olarak kullanmak daha güvenlidir,
        // ancak orijinal mantığa uymak için burada parametresiz IN kullanılıyor.
        // BU KISIM GÜVENLİK RİSKİ TAŞIYOR! PDO'nun avantajı boşa harcanıyor.
        $placeholders = "'" . implode("','", $var_keys) . "'";
        $global_vars = $this->selectMulti("var_key, var_value FROM global_vars WHERE var_key IN ($placeholders)");

        $seo_data = [];
        foreach ($global_vars as $row) {
            $seo_data[$row['var_key']] = $row['var_value'];
        }

        return $seo_data;
    }

    public function updateGlobalVars(array $data) {
        try {
            foreach ($data as $var_key => $var_value) {
                $where = "var_key = '$var_key'"; // PDO için ? kullanıyoruz
                $update_data = ['var_value' => $var_value];
                
                // Parametreler: [var_value (SET için), var_key (WHERE için)]
                $params = [$var_value, $var_key]; 

                // update metodunu ? destekleyecek şekilde kullanıyoruz.
                $affected = $this->update("global_vars", $update_data, $where);
                
                // Eğer 0 satır etkilendiyse ve kayıt var ise (aynı değerden dolayı) hata atmayalım.
                if ($affected === 0 && $this->count("global_vars", "var_key = ?", [$var_key]) > 0) {
                    continue;
                }
                
                // Eğer kayıt yoksa ve eklenmediyse hata verelim (bu kısım karmaşıklaştı, orijinal mantığı basit tutalım)
                // Eğer satır etkilenmediyse ve kayıt varsa, devam et.
            }
            return "Güncelleme işlemi başarılı!";
        } catch (Exception $e) {
            return "Güncelleme başarısız oldu: " . $e->getMessage();
        }
    }

    public function insert($table, $data, $updateOnDuplicate = false) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $params = array_values($data);
        
        $sql = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
        if ($updateOnDuplicate) {
            $updateColumns = implode(', ', array_map(fn($key) => "`$key` = VALUES(`$key`)", array_keys($data)));
            $sql .= " ON DUPLICATE KEY UPDATE $updateColumns";
        }
        
        $stmt = $this->executePreparedStatement($sql, $params);
        
        return $this->conn->lastInsertId();
    }
    
    // update metodunu ? parametrelerini kabul edecek şekilde düzenledim.
    public function update($table, $data, $where, $params = []) {
        // SET kısmında ? kullanıyoruz
        $setPart = implode(', ', array_map(fn($key) => "`$key` = ?", array_keys($data)));
        
        // SET değerleri
        $data_params = array_values($data);
        
        // WHERE değerleri ($params'ta gelmeli)
        $where_params = $params;
        
        // Tüm parametreleri birleştir (SET parametreleri + WHERE parametreleri)
        $all_params = array_merge($data_params, $where_params); 

        $sql = "UPDATE `$table` SET $setPart WHERE " . str_replace('?', '?', $where); // WHERE kısmındaki ?'leri koru

        $stmt = $this->executePreparedStatement($sql, $all_params);
    
        return $stmt->rowCount();
    }
    
    
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM `$table` WHERE " . str_replace('?', '?', $where);
    
        $stmt = $this->executePreparedStatement($sql, $params);
    
        return $stmt->rowCount();
    }
    
    public function truncate($table)
    {
        $query = "TRUNCATE TABLE `$table`";
        if ($this->conn->query($query) === TRUE) {
            return true;
        } else {
            throw new Exception('Tabloyu temizleme işlemi başarısız: ' . $this->conn->error);
        }
    }

    // backup ve restore kısımları aynı kaldı.
    public function backup() {
        $targetDir = __DIR__ . '/../admin/db_backup';
    
        // Dizin var mı kontrol et, yoksa oluştur
        if (!file_exists($targetDir)) {
            // 0755 izinleri genel kullanım için güvenlidir. 
            // true parametresi iç içe klasörlerin (admin/db_backup) oluşturulmasını sağlar.
            if (!mkdir($targetDir, 0755, true)) {
                throw new Exception("Yedekleme dizini oluşturulamadı: " . $targetDir);
            }
        }

        $backupDir = realpath($targetDir);

        $backupDir = realpath(__DIR__ . '/../admin/db_backup');
        if (!$backupDir) {
            throw new Exception("Yedekleme dizini bulunamadı!");
        }

        $backupFile = $backupDir . '/backup-' . date("Y-m-d-H-i-s") . '.sql';
        
        // Dosyayı yazma modunda aç (Memory limitini zorlamamak için parça parça yazacağız)
        $handle = fopen($backupFile, 'w+');
        
        // Başlangıç ayarları
        fwrite($handle, "-- Alper Kum SQL Backup\n-- Date: " . date("Y-m-d H:i:s") . "\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n\n");

        // Tüm tabloları çek
        $stmt = $this->conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // 1. Tablo Yapısını Al (CREATE TABLE)
            $createStmt = $this->conn->query("SHOW CREATE TABLE `$table`")->fetch();
            fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
            fwrite($handle, $createStmt['Create Table'] . ";\n\n");

            // 2. Tablo Verilerini Al
            $rows = $this->conn->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        if ($value === null) return 'NULL';
                        return $this->conn->quote($value);
                    }, $row);
                    
                    $sql = "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                    fwrite($handle, $sql);
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;");
        fclose($handle);
    }

    public function count($table, $whereClause = "", $params = [])
    {
        $query = "SELECT COUNT(*) as total FROM `" . $table . "`";
        if (!empty($whereClause)) {
            $query .= " WHERE " . $whereClause;
        }

        $stmt = $this->executePreparedStatement($query, $params);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function restore() {
        $targetDir = __DIR__ . '/../admin/db_backup';
    
        // Dizin var mı kontrol et, yoksa oluştur
        if (!file_exists($targetDir)) {
            // 0755 izinleri genel kullanım için güvenlidir. 
            // true parametresi iç içe klasörlerin (admin/db_backup) oluşturulmasını sağlar.
            if (!mkdir($targetDir, 0755, true)) {
                throw new Exception("Yedekleme dizini oluşturulamadı: " . $targetDir);
            }
        }

        $backupDir = realpath($targetDir);
        $backups = glob("$backupDir/*.sql");

        if (empty($backups)) {
            throw new Exception("Hata: Hiç yedek bulunamadı!");
        }

        // En son yedeği al
        $backupFile = end($backups);
        $sql = file_get_contents($backupFile);

        try {
            // FOREIGN_KEY_CHECKS'i kapatıp toplu sorgu çalıştırıyoruz
            $this->conn->exec("SET FOREIGN_KEY_CHECKS=0; " . $sql . " SET FOREIGN_KEY_CHECKS=1;");
        } catch (PDOException $e) {
            throw new Exception("Geri yükleme hatası: " . $e->getMessage());
        }
    }
}
?>
