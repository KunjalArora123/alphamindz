<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

echo "=== COLUMNS IN QUESTIONS TABLE ===\n";
$res = $mysqli->query('DESCRIBE questions');
while ($row = $res->fetch_assoc()) {
    echo "{$row['Field']} - {$row['Type']}\n";
}

echo "\n=== SCIENCE (ID 60) AND COMMERCE (ID 59) NUMERICAL ABILITY QUESTIONS ===\n";
// ID 60 = Science 2026 Assessment, Part 186 = NUMERICAL ABILITY
// ID 59 = Commerce 2026 Assessment, Part 180 = NUMERICAL ABILITY

$res = $mysqli->query("SELECT q.*, p.part_name, a.title as assessment_title FROM questions q JOIN assessment_parts p ON q.part_id = p.id JOIN assessments a ON q.assessment_id = a.id WHERE q.assessment_id IN (59, 60) AND (p.part_name LIKE '%NUMERICAL%' OR q.subject LIKE '%NUMERICAL%') ORDER BY q.assessment_id, q.question_number");

if ($res) {
    while ($q = $res->fetch_assoc()) {
        echo "Assessment: {$q['assessment_title']} (ID {$q['assessment_id']}) | Part: {$q['part_name']} (ID {$q['part_id']}) | Q#: {$q['question_number']}\n";
        echo "  Question Text: {$q['question_text']}\n";
        foreach ($q as $k => $v) {
            if ($v !== null && $v !== '' && !in_array($k, ['id', 'assessment_id', 'part_id', 'question_number', 'question_text', 'part_name', 'assessment_title'])) {
                echo "  {$k}: {$v}\n";
            }
        }
        echo "---------------------------------------------------------\n";
    }
} else {
    echo "Error: " . $mysqli->error . "\n";
}
