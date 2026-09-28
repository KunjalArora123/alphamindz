<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'alphamindz';

$db = new mysqli($hostname, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

$create_table = "CREATE TABLE IF NOT EXISTS `admin_login_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT NULL,
    `device_type` VARCHAR(50) DEFAULT 'Desktop',
    `browser` VARCHAR(100) NULL,
    `platform` VARCHAR(100) NULL,
    `status` VARCHAR(20) DEFAULT 'success',
    `login_time` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($create_table) === TRUE) {
    echo "admin_login_logs table created/verified successfully.\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

$db->close();
