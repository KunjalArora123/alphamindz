<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$direction_text = "Direction: From Question (11 – 20). The Shapes given in the question correspond to a figure as given in the group 2 Figures are identical, although some of them may be rotated. Which shape in Group 2 corresponds to the shape in the question . Write that letter in the answer";

echo "=== UPDATING SPATIAL ABILITY Q11-20 QUESTION TEXT ACROSS ALL ASSESSMENTS ===\n\n";

$stmt = $db->prepare("UPDATE questions SET question_text = ? WHERE question_number BETWEEN 11 AND 20 AND (subject LIKE '%spatial%' OR part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%spatial%'))");
$stmt->bind_param("s", $direction_text);
$stmt->execute();
$affected = $stmt->affected_rows;

echo "Successfully updated $affected questions across all Spatial Ability sections with direction instruction text!\n";

// Verify
$res = $db->query("SELECT q.id, a.title, q.question_number, q.question_text FROM questions q JOIN assessments a ON q.assessment_id = a.id WHERE q.question_number BETWEEN 11 AND 20 AND (q.subject LIKE '%spatial%' OR q.part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%spatial%')) LIMIT 5");

echo "\nVerification sample:\n";
while ($row = $res->fetch_assoc()) {
    echo "  Assessment: '{$row['title']}' | Q#{$row['question_number']} (ID {$row['id']}):\n";
    echo "     Text: '{$row['question_text']}'\n";
}
