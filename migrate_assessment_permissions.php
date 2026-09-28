<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create user_test_permissions table
$create_sql = "
CREATE TABLE IF NOT EXISTS user_test_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'disabled',
    granted_at DATETIME NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_permission (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->query($create_sql)) {
    echo "Table 'user_test_permissions' created or verified.\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

$db->close();
echo "Assessment permissions migration complete.\n";
