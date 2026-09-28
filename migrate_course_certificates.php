<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create course_certificates table
$create_sql = "
CREATE TABLE IF NOT EXISTS course_certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    credential_id VARCHAR(100) UNIQUE NOT NULL,
    tier_key VARCHAR(50) DEFAULT 'basic',
    status VARCHAR(20) DEFAULT 'granted',
    issue_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_course_cert (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->query($create_sql)) {
    echo "Table 'course_certificates' created or verified.\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

$db->close();
echo "Course certificates migration complete.\n";
