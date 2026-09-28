<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Add time_limit column if not exists
$check_tl = $db->query("SHOW COLUMNS FROM assessments LIKE 'time_limit'");
if ($check_tl->num_rows == 0) {
    $db->query("ALTER TABLE assessments ADD COLUMN time_limit INT NOT NULL DEFAULT 45 AFTER description");
    echo "Added 'time_limit' column to assessments table.\n";
}

// 2. Add status column if not exists
$check_st = $db->query("SHOW COLUMNS FROM assessments LIKE 'status'");
if ($check_st->num_rows == 0) {
    $db->query("ALTER TABLE assessments ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'active' AFTER time_limit");
    echo "Added 'status' column to assessments table.\n";
}

// 3. Seed subjects from questions table if missing in assessments table
$subjects_res = $db->query("SELECT DISTINCT subject FROM questions WHERE subject IS NOT NULL AND subject != ''");
while ($s_row = $subjects_res->fetch_assoc()) {
    $subject = $s_row['subject'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject)));
    
    $check_exist = $db->query("SELECT id FROM assessments WHERE title = '" . $db->real_escape_string($subject) . "' OR slug = '" . $db->real_escape_string($slug) . "'");
    if ($check_exist->num_rows == 0) {
        $desc = "Assessment test evaluating core skills and competencies in " . $subject . ".";
        $stmt = $db->prepare("INSERT INTO assessments (title, slug, description, time_limit, status, created_at) VALUES (?, ?, ?, 45, 'active', NOW())");
        $stmt->bind_param("sss", $subject, $slug, $desc);
        $stmt->execute();
        echo "Seeded assessment test: '$subject'\n";
    }
}

$db->close();
echo "Assessments table migration & seeding complete.\n";
