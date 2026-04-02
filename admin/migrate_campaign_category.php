<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE campaigns ADD COLUMN category VARCHAR(100) DEFAULT NULL AFTER discount_text");
    echo "category column added successfully.\n";
} catch (Exception $e) {
    echo "Error or already exists: " . $e->getMessage() . "\n";
}
