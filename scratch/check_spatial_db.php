<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== SPATIAL QUESTIONS IN DB ===\n";
$res = $mysqli->query("SELECT id, assessment_id, subject, question_number, question_text, image_path FROM questions WHERE subject LIKE '%spatial%' LIMIT 10");
while ($r = $res->fetch_assoc()) {
    echo "Q#{$r['question_number']} (Ass {$r['assessment_id']}): image_path='{$r['image_path']}'\n";
}

echo "\n=== ALL QUESTIONS WITH IMAGE_PATH SET IN DB ===\n";
$res = $mysqli->query("SELECT id, assessment_id, subject, question_number, image_path FROM questions WHERE image_path IS NOT NULL AND image_path != '' LIMIT 20");
while ($r = $res->fetch_assoc()) {
    echo "Q#{$r['question_number']} (Ass {$r['assessment_id']}, Subject: {$r['subject']}): image_path='{$r['image_path']}'\n";
}
