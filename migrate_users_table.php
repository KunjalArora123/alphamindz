<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$check_u = $db->query("SHOW COLUMNS FROM users LIKE 'username'");
if ($check_u->num_rows == 0) {
    $db->query("ALTER TABLE users ADD COLUMN username VARCHAR(100) NULL AFTER first_name");
    echo "Added 'username' column to users table.\n";
}

$check_p = $db->query("SHOW COLUMNS FROM users LIKE 'phone'");
if ($check_p->num_rows == 0) {
    $db->query("ALTER TABLE users ADD COLUMN phone VARCHAR(50) NULL AFTER email");
    echo "Added 'phone' column to users table.\n";
}

$db->close();
echo "Users table migration complete.\n";
