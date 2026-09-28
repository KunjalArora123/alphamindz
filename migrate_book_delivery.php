<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create temporary_download_links table
$create_sql = "
CREATE TABLE IF NOT EXISTS temporary_download_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(100) NOT NULL UNIQUE,
    file_path TEXT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    ttl_seconds INT NOT NULL DEFAULT 3600,
    expires_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->query($create_sql)) {
    echo "Table 'temporary_download_links' created or verified.\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

$db->close();
echo "Book delivery migration complete.\n";
