<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
$db->set_charset("utf8mb4");

$sql = [];
$sql[] = "-- AlphaMindz Production Test Import Dump";
$sql[] = "-- Generated on 2026-10-08\n";
$sql[] = "SET NAMES utf8mb4;\n";
$sql[] = "ALTER TABLE questions MODIFY COLUMN correct_option VARCHAR(20) NOT NULL;\n";

// 1. Get assessment IDs
$res = $db->query("SELECT id FROM assessments WHERE id NOT IN (33, 34)");
$ass_ids = [];
while ($row = $res->fetch_assoc()) {
    $ass_ids[] = $row['id'];
}
$ass_ids_str = implode(',', $ass_ids);

$sql[] = "-- Delete existing aptitude test data if re-importing";
$sql[] = "DELETE FROM test_answers WHERE question_id IN (SELECT id FROM questions WHERE assessment_id IN ($ass_ids_str));";
$sql[] = "DELETE FROM questions WHERE assessment_id IN ($ass_ids_str);";
$sql[] = "DELETE FROM assessment_parts WHERE assessment_id IN ($ass_ids_str);";
$sql[] = "DELETE FROM assessments WHERE id IN ($ass_ids_str);\n";

// 2. Dump assessments
$sql[] = "-- 1. ASSESSMENTS TABLE";
$res = $db->query("SELECT * FROM assessments WHERE id IN ($ass_ids_str)");
while ($row = $res->fetch_assoc()) {
    $id = (int)$row['id'];
    $title = $db->real_escape_string($row['title']);
    $slug = $db->real_escape_string($row['slug']);
    $desc = $db->real_escape_string($row['description']);
    $time = (int)$row['time_limit'];
    $status = $db->real_escape_string($row['status']);
    $sql[] = "INSERT INTO assessments (id, title, slug, description, time_limit, status, created_at, updated_at) VALUES ($id, '$title', '$slug', '$desc', $time, '$status', NOW(), NOW());";
}

// 3. Dump assessment_parts
$sql[] = "\n-- 2. ASSESSMENT_PARTS TABLE";
$res = $db->query("SELECT * FROM assessment_parts WHERE assessment_id IN ($ass_ids_str)");
while ($row = $res->fetch_assoc()) {
    $id = (int)$row['id'];
    $ass_id = (int)$row['assessment_id'];
    $pname = $db->real_escape_string($row['part_name']);
    $pdesc = $db->real_escape_string($row['description']);
    $sql[] = "INSERT INTO assessment_parts (id, assessment_id, part_name, description, created_at, updated_at) VALUES ($id, $ass_id, '$pname', '$pdesc', NOW(), NOW());";
}

// 4. Dump questions
$sql[] = "\n-- 3. QUESTIONS TABLE";
$res = $db->query("SELECT * FROM questions WHERE assessment_id IN ($ass_ids_str)");
while ($row = $res->fetch_assoc()) {
    $id = (int)$row['id'];
    $ass_id = (int)$row['assessment_id'];
    $part_id = (int)$row['part_id'];
    $subj = $db->real_escape_string($row['subject']);
    $q_num = (int)$row['question_number'];
    $q_text = $db->real_escape_string($row['question_text']);
    $op_a = $db->real_escape_string($row['option_a']);
    $op_a_img = $db->real_escape_string($row['option_a_image']);
    $op_b = $db->real_escape_string($row['option_b']);
    $op_b_img = $db->real_escape_string($row['option_b_image']);
    $op_c = $db->real_escape_string($row['option_c']);
    $op_c_img = $db->real_escape_string($row['option_c_image']);
    $op_d = $db->real_escape_string($row['option_d']);
    $op_d_img = $db->real_escape_string($row['option_d_image']);
    $img_1 = $db->real_escape_string($row['image_path']);
    $img_2 = $db->real_escape_string($row['image_path_2']);
    $correct = $db->real_escape_string($row['correct_option']);
    
    $sql[] = "INSERT INTO questions (id, assessment_id, part_id, subject, question_number, question_text, option_a, option_a_image, option_b, option_b_image, option_c, option_c_image, option_d, option_d_image, image_path, image_path_2, correct_option, created_at, updated_at) VALUES ($id, $ass_id, $part_id, '$subj', $q_num, '$q_text', '$op_a', '$op_a_img', '$op_b', '$op_b_img', '$op_c', '$op_c_img', '$op_d', '$op_d_img', '$img_1', '$img_2', '$correct', NOW(), NOW());";
}

$out_path = __DIR__ . '/../alpha_tests_production_import.sql';
file_put_contents($out_path, implode("\n", $sql));
echo "Generated SQL Dump file at: $out_path (" . count($sql) . " lines)\n";
