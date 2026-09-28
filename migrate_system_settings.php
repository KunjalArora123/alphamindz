<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'alphamindz';

$db = new mysqli($hostname, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

$create_table = "CREATE TABLE IF NOT EXISTS `system_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($create_table) === TRUE) {
    echo "system_settings table ready.\n";
} else {
    echo "Error creating system_settings table: " . $db->error . "\n";
}

// Seed default maintenance mode setting if not present
$check = $db->query("SELECT id FROM system_settings WHERE setting_key = 'maintenance_mode'");
if ($check && $check->num_rows == 0) {
    $db->query("INSERT INTO system_settings (setting_key, setting_value) VALUES ('maintenance_mode', '0')");
    echo "Default 'maintenance_mode' set to 0 (Online).\n";
}

$check_msg = $db->query("SELECT id FROM system_settings WHERE setting_key = 'maintenance_message'");
if ($check_msg && $check_msg->num_rows == 0) {
    $msg = "We are currently performing scheduled maintenance to upgrade our system. We will be back online shortly!";
    $stmt = $db->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('maintenance_message', ?)");
    $stmt->bind_param("s", $msg);
    $stmt->execute();
    echo "Default 'maintenance_message' set.\n";
}

$db->close();
echo "System settings migration completed successfully.\n";
