<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();
$db->exec("ALTER TABLE pages ADD COLUMN parent_id INT NOT NULL DEFAULT 0 AFTER id");
echo "pages tablosuna parent_id eklendi.";
