<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Check user_enrollments table
$check_table = $db->query("SHOW TABLES LIKE 'user_enrollments'");
if ($check_table->num_rows == 0) {
    $db->query("CREATE TABLE user_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        entity_type VARCHAR(50) NOT NULL DEFAULT 'course',
        entity_id INT NOT NULL,
        tier_key VARCHAR(50) NOT NULL DEFAULT 'basic',
        enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Created user_enrollments table.\n";
} else {
    $check_col = $db->query("SHOW COLUMNS FROM user_enrollments LIKE 'tier_key'");
    if ($check_col->num_rows == 0) {
        $db->query("ALTER TABLE user_enrollments ADD COLUMN tier_key VARCHAR(50) NOT NULL DEFAULT 'basic' AFTER entity_id");
        echo "Added 'tier_key' column to user_enrollments.\n";
    } else {
        echo "'tier_key' column already exists in user_enrollments.\n";
    }
}

// 2. Create course_modules table for additive tier module content (Tier 1 Basic, Tier 2 Standard, Tier 3 Advanced)
$db->query("CREATE TABLE IF NOT EXISTS course_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    module_tier VARCHAR(50) NOT NULL DEFAULT 'basic',
    title VARCHAR(255) NOT NULL,
    content_type VARCHAR(50) DEFAULT 'pdf',
    file_path TEXT,
    description TEXT,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
echo "Table 'course_modules' verified/created.\n";

$db->close();
echo "Student panel migration complete.\n";
