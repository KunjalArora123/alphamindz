<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$columns = [
    'option_a_image' => 'option_a',
    'option_b_image' => 'option_b',
    'option_c_image' => 'option_c',
    'option_d_image' => 'option_d'
];

foreach ($columns as $col => $after) {
    $chk = $db->query("SHOW COLUMNS FROM questions LIKE '$col'");
    if ($chk && $chk->num_rows == 0) {
        $db->query("ALTER TABLE questions ADD COLUMN $col VARCHAR(255) NULL AFTER $after");
        echo "Added '$col' column to questions table.\n";
    } else {
        echo "'$col' column already exists in questions table.\n";
    }
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
