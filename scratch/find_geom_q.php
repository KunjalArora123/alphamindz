<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== SEARCHING DB FOR GEOMETRY ANGLE QUESTION ===\n";
$res = $mysqli->query("SELECT q.id, q.assessment_id, a.title as assessment_title, q.part_id, p.part_name, q.subject, q.question_number, q.question_text, q.image_path FROM questions q JOIN assessment_parts p ON q.part_id = p.id JOIN assessments a ON q.assessment_id = a.id WHERE q.question_text LIKE '%OAB%' OR q.question_text LIKE '%75%' OR q.question_text LIKE '%figure%' ORDER BY q.assessment_id, q.question_number");

while ($q = $res->fetch_assoc()) {
    echo "Ass ID: {$q['assessment_id']} ({$q['assessment_title']}) | Part: {$q['part_name']} | Q#: {$q['question_number']}\n";
    echo "  Text: {$q['question_text']}\n";
    echo "  image_path in DB: '{$q['image_path']}'\n";
    echo "---------------------------------------------------------\n";
}
