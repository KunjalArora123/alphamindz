<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "=== CONSOLIDATING CLASS 10 ASSESSMENT & PARTS ===\n";

$target_title = 'Class 10 Assessment';
$target_slug = 'class-10-assessment';
$target_desc = 'Comprehensive assessment for Class 10 students covering General Science, Mechanical, Numerical, Reasoning, Spatial, and Verbal abilities.';

// 1. Find or create Class 10 Assessment
$a_res = $db->query("SELECT id FROM assessments WHERE title = 'Class 10 Assessment' OR title = 'Comprehensive Aptitude Assessment' OR title = 'Career Assessment' LIMIT 1");
if ($a_res->num_rows > 0) {
    $a_row = $a_res->fetch_assoc();
    $class10_id = $a_row['id'];
    $db->query("UPDATE assessments SET title = '" . $db->real_escape_string($target_title) . "', slug = '" . $db->real_escape_string($target_slug) . "', description = '" . $db->real_escape_string($target_desc) . "', status = 'active' WHERE id = {$class10_id}");
    echo "1. Updated assessment ID {$class10_id} to '{$target_title}'.\n";
} else {
    $stmt = $db->prepare("INSERT INTO assessments (title, slug, description, time_limit, status, created_at) VALUES (?, ?, ?, 45, 'active', NOW())");
    $stmt->bind_param("sss", $target_title, $target_slug, $target_desc);
    $stmt->execute();
    $class10_id = $db->insert_id;
    echo "1. Created new assessment ID {$class10_id} '{$target_title}'.\n";
}

// 2. Standard 6 parts for Class 10 Assessment
$parts_list = [
    'General Science Ability' => 'General Science evaluation testing physics, chemistry, and biological principles.',
    'Mechanical Ability' => 'Mechanical aptitude assessment measuring physical principles and machinery comprehension.',
    'Numerical Ability' => 'Numerical ability test evaluating arithmetic reasoning, statistics, and mathematics.',
    'Reasoning Ability' => 'Logical and analytical reasoning test measuring problem solving and critical thinking.',
    'Spatial Ability' => 'Spatial orientation and visualization test assessing pattern recognition and mental rotation.',
    'Verbal Ability' => 'Verbal aptitude test evaluating vocabulary, reading comprehension, and grammar.'
];

// Clean existing parts for other assessment IDs
$db->query("DELETE FROM assessment_parts WHERE part_name IN ('General Science Ability', 'Mechanical Ability', 'Numerical Ability', 'Reasoning Ability', 'Spatial Ability', 'Verbal Ability') AND assessment_id != {$class10_id}");

// Ensure parts exist under Class 10 Assessment
$part_id_map = [];
foreach ($parts_list as $p_name => $p_desc) {
    $p_chk = $db->query("SELECT id FROM assessment_parts WHERE assessment_id = {$class10_id} AND part_name = '" . $db->real_escape_string($p_name) . "'");
    if ($p_chk->num_rows > 0) {
        $p_row = $p_chk->fetch_assoc();
        $part_id_map[$p_name] = $p_row['id'];
    } else {
        $db->query("INSERT INTO assessment_parts (assessment_id, part_name, description, created_at) VALUES ({$class10_id}, '" . $db->real_escape_string($p_name) . "', '" . $db->real_escape_string($p_desc) . "', NOW())");
        $part_id_map[$p_name] = $db->insert_id;
    }
}
echo "2. Configured 6 parts under Class 10 Assessment.\n";

// 3. Update questions table to point assessment_id and part_id to Class 10 Assessment parts
foreach ($part_id_map as $p_name => $pid) {
    $db->query("UPDATE questions SET assessment_id = {$class10_id}, part_id = {$pid} WHERE subject = '" . $db->real_escape_string($p_name) . "'");
}
$db->query("UPDATE questions SET assessment_id = {$class10_id} WHERE assessment_id IS NULL OR assessment_id IN (1, 5, 6, 7, 8, 9, 10, 11)");
echo "3. Re-linked questions to Class 10 Assessment and its parts.\n";

// 4. Remove duplicate/standalone assessments that were previously listed individually
$db->query("DELETE FROM assessments WHERE title IN ('General Science Ability', 'Mechanical Ability', 'Numerical Ability', 'Reasoning Ability', 'Spatial Ability', 'Verbal Ability', 'Comprehensive Aptitude Assessment', 'Career Assessment') AND id != {$class10_id}");
echo "4. Consolidated standalone assessment rows.\n";

// 5. Update user_test_permissions to point to Class 10 Assessment
$db->query("UPDATE user_test_permissions SET test_title = 'Class 10 Assessment' WHERE test_title IN ('General Science Ability', 'Mechanical Ability', 'Numerical Ability', 'Reasoning Ability', 'Spatial Ability', 'Verbal Ability', 'Comprehensive Aptitude Assessment', 'Career Assessment')");
echo "5. Updated user test permissions.\n";

echo "=== CONSOLIDATION COMPLETE ===\n";
$db->close();
