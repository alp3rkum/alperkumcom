<?php
require '../functions/db.php';
$database = Database::getInstance();
$conn = $database->getConnection();

header("Content-Type: application/rss+xml; charset=UTF-8");
echo "<?xml version='1.0' encoding='UTF-8'?>";
?>
<rss version="2.0">
  <channel>
    <title>Alperkum.com (EN)</title>
    <link>https://alperkum.com</link>
    <description>Alperkum Blog RSS Feed (EN)</description>
    <language>en-en</language>

    <?php
    $sql = "b.blog_baslik_en, b.meta_desc_en, b.meta_url_en, b.created_at,
                   k.cat_url_en
            FROM bloglar b
            JOIN blog_kategoriler k ON b.kategori_id = k.id
            ORDER BY b.id DESC LIMIT 20";
    $blogs = $database->selectMulti($sql);

    foreach($blogs as $blog) {
        $link = "https://alperkum.com/blog/" . htmlspecialchars($blog['cat_url_en']) . "/" . htmlspecialchars($blog['meta_url_en']);
        echo "<item>";
        echo "<title>" . htmlspecialchars($blog['blog_baslik_en']) . "</title>";
        echo "<link>$link</link>";
        echo "<description>" . htmlspecialchars($blog['meta_desc_en']) . "</description>";
        echo "<pubDate>" . date(DATE_RSS, strtotime($blog['created_at'])) . "</pubDate>";
        echo "<guid>$link</guid>";
        echo "</item>";
    }
    ?>
  </channel>
</rss>