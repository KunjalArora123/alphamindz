<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== ASSESSMENTS IN DATABASE ===\n";
$res = $mysqli->query('SELECT id, title, slug FROM assessments WHERE status="active"');
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Title: '{$row['title']}' | Slug: '{$row['slug']}'\n";
}

echo "\n=== QUESTION COUNTS BY ASSESSMENT_ID ===\n";
$res2 = $mysqli->query('SELECT assessment_id, COUNT(*) as cnt FROM questions GROUP BY assessment_id');
while ($row = $res2->fetch_assoc()) {
    echo "Assessment ID: " . ($row['assessment_id'] ?? 'NULL') . " => Count: {$row['cnt']}\n";
}

echo "\n=== QUESTION COUNTS BY SUBJECT ===\n";
$res3 = $mysqli->query('SELECT subject, COUNT(*) as cnt FROM questions GROUP BY subject');
while ($row = $res3->fetch_assoc()) {
    echo "Subject: '{$row['subject']}' => Count: {$row['cnt']}\n";
}

echo "\n=== QUESTIONS FOR ASSESSMENT 36 (10th AAT) ===\n";
$res4 = $mysqli->query('SELECT COUNT(*) as cnt FROM questions WHERE assessment_id = 36');
echo "Questions with assessment_id = 36: " . $res4->fetch_assoc()['cnt'] . "\n";

$res5 = $mysqli->query('SELECT COUNT(*) as cnt FROM questions WHERE assessment_id IS NULL AND subject = "10th AAT 2026 Assessment"');
echo "Questions with assessment_id IS NULL & subject='10th AAT 2026 Assessment': " . $res5->fetch_assoc()['cnt'] . "\n";

echo "\n=== LOOKUP TEST FOR VARIOUS TEST TITLES ===\n";
$titles_to_test = ['Class 10 Assessment', '10th AAT 2026 Assessment', '10th-aat-2026-assessment', '8th AAT 2026 (3) Assessment', 'ARTS 2026 Assessment', 'COMMERCE 2026 Assessment', 'SCIENCE 2026 Assessment'];

foreach ($titles_to_test as $tt) {
    $res = $mysqli->query("SELECT id, title FROM assessments WHERE title = '" . $mysqli->real_escape_string($tt) . "' OR slug = '" . $mysqli->real_escape_string($tt) . "' OR title LIKE '%" . $mysqli->real_escape_string(str_replace('Assessment', '', $tt)) . "%' LIMIT 1");
    $row = $res->fetch_assoc();
    echo "Querying '$tt' => Found ID: " . ($row['id'] ?? 'NONE') . " | Title: '" . ($row['title'] ?? '') . "'\n";
}
