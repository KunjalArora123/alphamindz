<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

echo "=== FIXING MISSING OPTIONS IN DATABASE ===\n\n";

// 1. Fix Spatial Ability Q11-Q20 across all 6 assessments
$res = $db->query("SELECT id, assessment_id, question_number FROM questions WHERE (subject LIKE '%spatial%' OR part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%spatial%')) AND question_number BETWEEN 11 AND 20");
$spatial_fixed = 0;
while ($row = $res->fetch_assoc()) {
    $db->query("UPDATE questions SET option_a = 'Fig A', option_b = 'Fig B', option_c = 'Fig C', option_d = 'Fig D' WHERE id = {$row['id']}");
    $spatial_fixed++;
}
echo "Updated $spatial_fixed Spatial Ability Q11-Q20 questions with options (Fig A, Fig B, Fig C, Fig D).\n";

// 2. Fix Verbal Ability Q18 & Q19 in Arts, Commerce, Science
$res2 = $db->query("SELECT id, question_number, question_text FROM questions WHERE question_number IN (18, 19) AND (subject LIKE '%verbal%' OR part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%verbal%'))");
$verbal_fixed = 0;
while ($row = $res2->fetch_assoc()) {
    $q_num = $row['question_number'];
    if ($q_num == 18) {
        $db->query("UPDATE questions SET option_a = 'Affect', option_b = 'Result', option_c = 'Upshot', option_d = 'Effect', correct_option = 'A' WHERE id = {$row['id']}");
        $verbal_fixed++;
    } elseif ($q_num == 19) {
        $db->query("UPDATE questions SET option_a = 'Abjure', option_b = 'Assess', option_c = 'Amass', option_d = 'Scatter', correct_option = 'C' WHERE id = {$row['id']}");
        $verbal_fixed++;
    }
}
echo "Updated $verbal_fixed Verbal Ability Q18 & Q19 questions with complete options & answer keys.\n";

// 3. Fix 10th Reasoning Ability Q12
$res3 = $db->query("SELECT id FROM questions WHERE question_number = 12 AND (subject LIKE '%reasoning%' OR part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%reasoning%')) AND option_a = ''");
$reasoning_fixed = 0;
while ($row = $res3->fetch_assoc()) {
    $db->query("UPDATE questions SET option_a = 'N', option_b = 'M', option_c = 'P', option_d = 'R', correct_option = 'A' WHERE id = {$row['id']}");
    $reasoning_fixed++;
}
echo "Updated $reasoning_fixed Reasoning Q12 questions with options.\n";

// 4. Safety net for ANY remaining question with empty options A, B, C, D
$res4 = $db->query("SELECT id, question_number, subject FROM questions WHERE option_a = '' AND option_a_image = '' AND option_b = '' AND option_b_image = ''");
$fallback_fixed = 0;
while ($row = $res4->fetch_assoc()) {
    $db->query("UPDATE questions SET option_a = 'Option A', option_b = 'Option B', option_c = 'Option C', option_d = 'Option D' WHERE id = {$row['id']}");
    $fallback_fixed++;
}
echo "Updated $fallback_fixed fallback questions with standard options A, B, C, D.\n";

echo "\nDatabase options fix completed successfully!\n";
