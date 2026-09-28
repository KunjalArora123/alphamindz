<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Add image_path column to questions table if not exists
$chk = $db->query("SHOW COLUMNS FROM questions LIKE 'image_path'");
if ($chk->num_rows == 0) {
    $db->query("ALTER TABLE questions ADD COLUMN image_path VARCHAR(255) NULL AFTER option_d");
    echo "Added 'image_path' column to questions table.\n";
} else {
    echo "'image_path' column already exists in questions table.\n";
}

// Create questions-images directory if missing
$target_dir = __DIR__ . '/questions-images';
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
    echo "Created 'questions-images' directory.\n";
} else {
    echo "'questions-images' directory already exists.\n";
}

$db->close();
