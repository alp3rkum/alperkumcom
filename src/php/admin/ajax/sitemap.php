<?php
require('../../functions/util.php');
require_once '../../functions/db.php';
$database = Database::getInstance();
$conn = $database->getConnection();

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Geçersiz istek"]);
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!csrf_check($csrf_token)) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "status" => "error",
        "message" => "Geçersiz istek (CSRF hatası)!"
    ]);
    exit;
}

header("Content-Type: application/xml; charset=utf-8");

$domain = 'https://' . $_SERVER['HTTP_HOST'];
$static_urls = [
    "https://alperkum.com",
    "https://alperkum.com/hakkimda",
    "https://alperkum.com/portfoy",
    "https://alperkum.com/bloglar",
    "https://alperkum.com/iletisim",
];

$dynamic_urls = [];

$proje_urls = $database->selectMulti("id, meta_url_tr, meta_url_en FROM projeler");
if (!empty($proje_urls)) {
    foreach ($proje_urls as $url) {
        $dynamic_urls[] = 'https://alperkum.com/proje/' . $url['meta_url_tr'];
        $dynamic_urls[] = 'https://alperkum.com/proje/' . $url['meta_url_en'];
    }
}

$blog_urls = $database->selectMulti("meta_url_tr, meta_url_en FROM bloglar");
if (!empty($blog_urls)) {
    foreach ($blog_urls as $url) {
        // Kategori bilgisi çek
        $kategori = $db->selectOne("cat_url_tr, cat_url_en FROM blog_kategoriler WHERE id = ?", [$url['kategori_id']]);

        if ($kategori) {
            // Türkçe link
            $dynamic_urls[] = 'https://alperkum.com/blog/' . $kategori['cat_url_tr'] . '/' . $url['meta_url_tr'];
            // İngilizce link
            $dynamic_urls[] = 'https://alperkum.com/blog/' . $kategori['cat_url_en'] . '/' . $url['meta_url_en'];
        }
    }
}

$xml_output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xml_output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

// Statik URL'ler
foreach ($static_urls as $url) {
    $xml_output .= "  <url>\n";
    $xml_output .= "    <loc>$url</loc>\n";
    $xml_output .= "    <changefreq>monthly</changefreq>\n";
    $xml_output .= "    <priority>0.8</priority>\n";
    $xml_output .= "  </url>\n";
}

foreach ($dynamic_urls as $url) {
    $xml_output .= "  <url>\n";
    $xml_output .= "    <loc>$url</loc>\n";
    $xml_output .= "    <changefreq>monthly</changefreq>\n";
    $xml_output .= "    <priority>0.8</priority>\n";
    $xml_output .= "  </url>\n";
}

$xml_output .= "</urlset>";

$file_path = '../../sitemap.xml';
$file = fopen($file_path, 'w');
fwrite($file, $xml_output);
fclose($file);

echo $xml_output;
?>