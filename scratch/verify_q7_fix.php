<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$res = $db->query("SELECT question_number, question_text, image_path, option_a, option_b, option_c, option_d, correct_option FROM questions WHERE assessment_id = 50 AND part_id = 126 AND question_number IN (6, 7, 8)");

echo "=== VERIFYING 8TH NUMERICAL Q6, Q7, Q8 ===\n\n";
while ($row = $res->fetch_assoc()) {
    echo "Q#{$row['question_number']}:\n";
    echo "  Text: '{$row['question_text']}'\n";
    echo "  Img:  '{$row['image_path']}'\n";
    echo "  Opts: A='{$row['option_a']}', B='{$row['option_b']}', C='{$row['option_c']}', D='{$row['option_d']}'\n";
    echo "  Ans:  '{$row['correct_option']}'\n\n";
}
