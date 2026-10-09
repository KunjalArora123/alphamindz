<?php
define('FCPATH', __DIR__ . '/../');
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== ALL NUMERICAL ABILITY Q4 QUESTIONS ===\n";
$res = $mysqli->query("SELECT q.id, q.assessment_id, a.title as assessment_title, q.part_id, p.part_name, q.subject, q.question_number, q.question_text, q.image_path, q.image_path_2 FROM questions q JOIN assessment_parts p ON q.part_id = p.id JOIN assessments a ON q.assessment_id = a.id WHERE q.question_number = 4 AND (p.part_name LIKE '%numerical%' OR q.subject LIKE '%numerical%')");

while ($q = $res->fetch_assoc()) {
    echo "Ass ID: {$q['assessment_id']} ({$q['assessment_title']}) | Part: {$q['part_name']} | Subject: {$q['subject']}\n";
    echo "  Text: {$q['question_text']}\n";
    echo "  image_path in DB: '{$q['image_path']}'\n";
    if (!empty($q['image_path'])) {
        $full_p = FCPATH . $q['image_path'];
        echo "  File exists on disk? " . (file_exists($full_p) ? "YES ($full_p)" : "NO ($full_p)") . "\n";
    }
    echo "---------------------------------------------------------\n";
}
