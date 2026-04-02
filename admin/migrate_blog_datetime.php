<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();
$db->exec("ALTER TABLE blog_posts MODIFY published_at DATETIME DEFAULT NULL");
$stmt = $db->query("SELECT id FROM blog_posts WHERE published_at IS NOT NULL AND TIME(published_at) = '00:00:00'");
$rows = $stmt->fetchAll();
foreach ($rows as $r) {
    $h = str_pad(rand(8, 19), 2, '0', STR_PAD_LEFT);
    $m = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
    $db->prepare("UPDATE blog_posts SET published_at = CONCAT(DATE(published_at), ' ', ?, ':', ?, ':00') WHERE id = ?")->execute([$h, $m, $r['id']]);
}
echo "OK - published_at DATETIME olarak guncellendi.\n";
