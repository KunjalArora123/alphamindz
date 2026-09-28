<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if test_title column exists
$check_col = $db->query("SHOW COLUMNS FROM user_test_permissions LIKE 'test_title'");
if ($check_col->num_rows == 0) {
    $db->query("ALTER TABLE user_test_permissions ADD COLUMN test_title VARCHAR(255) NOT NULL DEFAULT 'Comprehensive Aptitude Assessment' AFTER user_id");
    echo "Added 'test_title' column to user_test_permissions.\n";
}

// Drop old single-user unique index if present
$old_idx = $db->query("SHOW INDEX FROM user_test_permissions WHERE Key_name = 'unique_user_permission'");
if ($old_idx->num_rows > 0) {
    $db->query("ALTER TABLE user_test_permissions DROP INDEX unique_user_permission");
    echo "Dropped old 'unique_user_permission' index.\n";
}

// Add composite index if not present
$composite = $db->query("SHOW INDEX FROM user_test_permissions WHERE Key_name = 'unique_user_test'");
if ($composite->num_rows == 0) {
    $db->query("ALTER TABLE user_test_permissions ADD UNIQUE KEY unique_user_test (user_id, test_title)");
    echo "Added unique_user_test (user_id, test_title) composite key.\n";
}

$db->close();
echo "Multi-test permissions migration complete.\n";
