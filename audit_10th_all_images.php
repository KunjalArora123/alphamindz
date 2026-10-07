<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== ALL QUESTIONS AND IMAGES FOR ASSESSMENT 36 (10TH AAT) ===\n\n";

$res = $mysqli->query("SELECT q.id, q.question_number, q.question_text, q.image_path, q.image_path_2, q.subject, ap.part_name FROM questions q LEFT JOIN assessment_parts ap ON q.part_id = ap.id WHERE q.assessment_id = 36 ORDER BY q.part_id ASC, q.question_number ASC");

while ($r = $res->fetch_assoc()) {
    $sec = !empty($r['part_name']) ? $r['part_name'] : $r['subject'];
    $img1 = $r['image_path'];
    $img2 = $r['image_path_2'];
    if (!empty($img1) || !empty($img2)) {
        echo "Section: '$sec' | QNum {$r['question_number']} (ID {$r['id']}):\n";
        echo "   Text: " . substr(strip_tags($r['question_text']), 0, 60) . "\n";
        echo "   Img1: '$img1'\n";
        if (!empty($img2)) echo "   Img2: '$img2'\n";
        echo "\n";
    }
}
