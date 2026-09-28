<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

// Check reset_token column
$check_rt = $db->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
if ($check_rt->num_rows == 0) {
    $db->query("ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL AFTER password");
    echo "Added 'reset_token' column to users table.\n";
} else {
    echo "'reset_token' column already exists.\n";
}

// Check reset_token_expires column
$check_rte = $db->query("SHOW COLUMNS FROM users LIKE 'reset_token_expires'");
if ($check_rte->num_rows == 0) {
    $db->query("ALTER TABLE users ADD COLUMN reset_token_expires DATETIME NULL AFTER reset_token");
    echo "Added 'reset_token_expires' column to users table.\n";
} else {
    echo "'reset_token_expires' column already exists.\n";
}

$db->close();
echo "Password reset table migration complete.\n";
