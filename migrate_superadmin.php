<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'alphamindz';

$db = new mysqli($hostname, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

echo "Connected to database successfully.\n";

// 1. Create admins table if not exists
$create_admins_table = "CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(150) NOT NULL UNIQUE,
    `email` VARCHAR(150) NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'admin',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($create_admins_table) === TRUE) {
    echo "Admins table ready.\n";
} else {
    echo "Error creating admins table: " . $db->error . "\n";
}

// Add columns if missing in existing table
$check_email = $db->query("SHOW COLUMNS FROM admins LIKE 'email'");
if ($check_email && $check_email->num_rows == 0) {
    $db->query("ALTER TABLE admins ADD COLUMN email VARCHAR(150) NULL AFTER username");
    echo "Added 'email' column to admins table.\n";
}

$check_role = $db->query("SHOW COLUMNS FROM admins LIKE 'role'");
if ($check_role && $check_role->num_rows == 0) {
    $db->query("ALTER TABLE admins ADD COLUMN role VARCHAR(50) DEFAULT 'admin' AFTER password");
    echo "Added 'role' column to admins table.\n";
}

// Seed Superadmin account in admins table
$sa_email = 'kunjalarora2@gmail.com';
$sa_pass = 'password@711';
$sa_role = 'superadmin';

$stmt = $db->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $sa_email, $sa_email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    $ins = $db->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, ?)");
    $ins->bind_param("ssss", $sa_email, $sa_email, $sa_pass, $sa_role);
    if ($ins->execute()) {
        echo "Superadmin created in 'admins' table: $sa_email\n";
    } else {
        echo "Error inserting superadmin in 'admins': " . $ins->error . "\n";
    }
} else {
    $upd = $db->prepare("UPDATE admins SET password = ?, role = ? WHERE username = ? OR email = ?");
    $upd->bind_param("ssss", $sa_pass, $sa_role, $sa_email, $sa_email);
    if ($upd->execute()) {
        echo "Superadmin updated in 'admins' table: $sa_email\n";
    } else {
        echo "Error updating superadmin in 'admins': " . $upd->error . "\n";
    }
}

// Also check users table if exists and sync superadmin user
$check_users = $db->query("SHOW TABLES LIKE 'users'");
if ($check_users && $check_users->num_rows > 0) {
    $u_stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $u_stmt->bind_param("s", $sa_email);
    $u_stmt->execute();
    $u_res = $u_stmt->get_result();
    
    $hashed_pass = password_hash($sa_pass, PASSWORD_DEFAULT);
    if ($u_res->num_rows == 0) {
        $u_ins = $db->prepare("INSERT INTO users (first_name, last_name, email, username, password, role, created_at) VALUES ('Super', 'Admin', ?, 'superadmin', ?, ?, NOW())");
        $u_ins->bind_param("sss", $sa_email, $hashed_pass, $sa_role);
        $u_ins->execute();
        echo "Superadmin created in 'users' table.\n";
    } else {
        $u_upd = $db->prepare("UPDATE users SET password = ?, role = ? WHERE email = ?");
        $u_upd->bind_param("sss", $hashed_pass, $sa_role, $sa_email);
        $u_upd->execute();
        echo "Superadmin updated in 'users' table.\n";
    }
}

$db->close();
echo "Superadmin Migration finished successfully!\n";
