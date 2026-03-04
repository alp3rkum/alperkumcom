<?php
require '../functions/db.php';
$database = Database::getInstance();
$conn = $database->getConnection();

header("Content-Type: application/rss+xml; charset=UTF-8");
echo "<?xml version='1.0' encoding='UTF-8'?>";
?>
<rss version="2.0">
  <channel>
    <title>Alperkum.com (TR)</title>
    <link>https://alperkum.com</link>
    <description>Alperkum Blog RSS Feed (TR)</description>
    <language>tr-tr</language>

    <?php
    $sql = "b.blog_baslik_tr, b.meta_desc_tr, b.meta_url_tr, b.created_at,
                   k.cat_url_tr
            FROM bloglar b
            JOIN blog_kategoriler k ON b.kategori_id = k.id
            ORDER BY b.id DESC LIMIT 20";
    $blogs = $database->selectMulti($sql);

    foreach($blogs as $blog) {
        $link = "https://alperkum.com/blog/" . htmlspecialchars($blog['cat_url_tr']) . "/" . htmlspecialchars($blog['meta_url_tr']);
        echo "<item>";
        echo "<title>" . htmlspecialchars($blog['blog_baslik_tr']) . "</title>";
        echo "<link>$link</link>";
        echo "<description>" . htmlspecialchars($blog['meta_desc_tr']) . "</description>";
        echo "<pubDate>" . date(DATE_RSS, strtotime($blog['created_at'])) . "</pubDate>";
        echo "<guid>$link</guid>";
        echo "</item>";
    }
    ?>
  </channel>
</rss>