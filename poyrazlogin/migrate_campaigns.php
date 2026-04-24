<?php
require_once __DIR__ . '/../includes/db.php';
try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    discount_text VARCHAR(100),
    icon VARCHAR(100) DEFAULT 'fas fa-tag',
    image VARCHAR(500) DEFAULT NULL,
    bg_color VARCHAR(100) DEFAULT 'linear-gradient(135deg, #1E3A8A, #162d6b)',
    features TEXT,
    link_url VARCHAR(500) DEFAULT NULL,
    link_text VARCHAR(100) DEFAULT 'Teklif Al',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active_dates (is_active, start_date, end_date),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo 'SUCCESS: campaigns table created';
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
