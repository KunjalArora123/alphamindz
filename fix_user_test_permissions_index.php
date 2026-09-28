<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Drop old single-column unique index on user_id if present
$check = $db->query("SHOW INDEX FROM user_test_permissions WHERE Key_name = 'unique_user_permission'");
if ($check->num_rows > 0) {
    // Check foreign keys or drop key directly
    $db->query("ALTER TABLE user_test_permissions DROP INDEX unique_user_permission");
    echo "Successfully dropped old unique index 'unique_user_permission'.\n";
} else {
    echo "Index 'unique_user_permission' not found or already dropped.\n";
}

// 2. Ensure composite unique key on (user_id, test_title) exists
$composite = $db->query("SHOW INDEX FROM user_test_permissions WHERE Key_name = 'unique_user_test'");
if ($composite->num_rows == 0) {
    $db->query("ALTER TABLE user_test_permissions ADD UNIQUE KEY unique_user_test (user_id, test_title)");
    echo "Added composite unique key 'unique_user_test' (user_id, test_title).\n";
} else {
    echo "Composite key 'unique_user_test' is active.\n";
}

$db->close();
echo "Permission index fix complete.\n";
