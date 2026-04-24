<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

try {
    $db->exec("ALTER TABLE campaigns ADD COLUMN views INT DEFAULT 0 AFTER sort_order");
    echo "views column added\n";
} catch (Exception $e) {
    echo "views: " . $e->getMessage() . "\n";
}

try {
    $db->exec("ALTER TABLE campaigns ADD COLUMN inquiry_count INT DEFAULT 0 AFTER views");
    echo "inquiry_count column added\n";
} catch (Exception $e) {
    echo "inquiry_count: " . $e->getMessage() . "\n";
}

echo "Done\n";
