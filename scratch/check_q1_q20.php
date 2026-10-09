<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== COMMERCE 2026 (ID 59) - NUMERICAL (180) ===\n";
$res = $mysqli->query("SELECT * FROM questions WHERE assessment_id = 59 AND part_id = 180 ORDER BY question_number");
while ($q = $res->fetch_assoc()) {
    echo "Q#{$q['question_number']} (ID {$q['id']}): {$q['question_text']}\n";
    if (!empty($q['question_image'])) echo "  question_image: {$q['question_image']}\n";
    if (!empty($q['option_a_image'])) echo "  option_a_image: {$q['option_a_image']}\n";
    if (!empty($q['option_b_image'])) echo "  option_b_image: {$q['option_b_image']}\n";
    if (!empty($q['option_c_image'])) echo "  option_c_image: {$q['option_c_image']}\n";
    if (!empty($q['option_d_image'])) echo "  option_d_image: {$q['option_d_image']}\n";
}

echo "\n=== SCIENCE 2026 (ID 60) - NUMERICAL (186) ===\n";
$res = $mysqli->query("SELECT * FROM questions WHERE assessment_id = 60 AND part_id = 186 ORDER BY question_number");
while ($q = $res->fetch_assoc()) {
    echo "Q#{$q['question_number']} (ID {$q['id']}): {$q['question_text']}\n";
    if (!empty($q['question_image'])) echo "  question_image: {$q['question_image']}\n";
    if (!empty($q['option_a_image'])) echo "  option_a_image: {$q['option_a_image']}\n";
    if (!empty($q['option_b_image'])) echo "  option_b_image: {$q['option_b_image']}\n";
    if (!empty($q['option_c_image'])) echo "  option_c_image: {$q['option_c_image']}\n";
    if (!empty($q['option_d_image'])) echo "  option_d_image: {$q['option_d_image']}\n";
}
