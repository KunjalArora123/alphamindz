<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$db->set_charset("utf8mb4");

echo "=== STEP 1: ALTER TABLE SCHEMA ===\n";
$db->query("ALTER TABLE questions MODIFY COLUMN correct_option VARCHAR(20) NOT NULL");
echo "Altered questions.correct_option to VARCHAR(20)\n";

echo "\n=== STEP 2: DELETE EXISTING APTITUDE ASSESSMENTS ===\n";
$res = $db->query("SELECT id, title FROM assessments WHERE id NOT IN (33, 34)");
$old_ids = [];
while ($row = $res->fetch_assoc()) {
    $old_ids[] = $row['id'];
}

if (!empty($old_ids)) {
    $old_ids_str = implode(',', $old_ids);
    echo "Deleting assessments IDs: $old_ids_str\n";
    $db->query("DELETE FROM test_answers WHERE question_id IN (SELECT id FROM questions WHERE assessment_id IN ($old_ids_str))");
    $db->query("DELETE FROM questions WHERE assessment_id IN ($old_ids_str)");
    $db->query("DELETE FROM assessment_parts WHERE assessment_id IN ($old_ids_str)");
    $db->query("DELETE FROM assessments WHERE id IN ($old_ids_str)");
    echo "Old assessments deleted successfully.\n";
} else {
    echo "No old aptitude assessments found to delete.\n";
}

echo "\n=== STEP 3: IMPORT ALL 6 ASSESSMENTS FROM JSON ===\n";
$json_path = __DIR__ . '/full_test_import.json';
if (!file_exists($json_path)) {
    die("Error: JSON file not found at $json_path\n");
}

$json_data = file_get_contents($json_path);
$assessments = json_decode($json_data, true);

if (!is_array($assessments)) {
    die("Error: Invalid JSON data\n");
}

$total_assessments = 0;
$total_parts = 0;
$total_questions = 0;
$total_images = 0;

$stmt_ass = $db->prepare("INSERT INTO assessments (title, slug, description, time_limit, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'active', NOW(), NOW())");
$stmt_part = $db->prepare("INSERT INTO assessment_parts (assessment_id, part_name, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
$stmt_q = $db->prepare("INSERT INTO questions (assessment_id, part_id, subject, question_number, question_text, option_a, option_a_image, option_b, option_b_image, option_c, option_c_image, option_d, option_d_image, image_path, image_path_2, correct_option, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

foreach ($assessments as $ass) {
    $title = $ass['title'];
    $slug = $ass['slug'];
    $desc = $ass['description'];
    $time_limit = $ass['time_limit'];
    
    $stmt_ass->bind_param("sssi", $title, $slug, $desc, $time_limit);
    $stmt_ass->execute();
    $assessment_id = $stmt_ass->insert_id;
    $total_assessments++;
    
    echo "\nCreated Assessment: '$title' (ID: $assessment_id)\n";
    
    foreach ($ass['parts'] as $part) {
        $part_name = $part['part_name'];
        $part_desc = $part['description'];
        
        $stmt_part->bind_param("iss", $assessment_id, $part_name, $part_desc);
        $stmt_part->execute();
        $part_id = $stmt_part->insert_id;
        $total_parts++;
        
        $q_count = 0;
        $q_img_count = 0;
        
        foreach ($part['questions'] as $q) {
            $q_num = $q['question_number'];
            $q_text = $q['question_text'];
            $opt_a = $q['option_a'];
            $opt_a_img = $q['option_a_image'];
            $opt_b = $q['option_b'];
            $opt_b_img = $q['option_b_image'];
            $opt_c = $q['option_c'];
            $opt_c_img = $q['option_c_image'];
            $opt_d = $q['option_d'];
            $opt_d_img = $q['option_d_image'];
            $img_1 = $q['image_path'];
            $img_2 = $q['image_path_2'];
            $correct_opt = $q['correct_option'];
            
            // Types: assessment_id (i), part_id (i), subject (s), question_number (i), question_text (s), option_a (s), option_a_image (s), option_b (s), option_b_image (s), option_c (s), option_c_image (s), option_d (s), option_d_image (s), image_path (s), image_path_2 (s), correct_option (s)
            $stmt_q->bind_param(
                "iisissssssssssss",
                $assessment_id, $part_id, $part_name, $q_num, $q_text,
                $opt_a, $opt_a_img, $opt_b, $opt_b_img, $opt_c, $opt_c_img,
                $opt_d, $opt_d_img, $img_1, $img_2, $correct_opt
            );
            $stmt_q->execute();
            $total_questions++;
            $q_count++;
            
            if ($opt_a_img || $opt_b_img || $opt_c_img || $opt_d_img || $img_1 || $img_2) {
                $q_img_count++;
                $total_images++;
            }
        }
        
        echo "   Part: '$part_name' (ID: $part_id) -> Inserted $q_count questions ($q_img_count with images)\n";
    }
}

echo "\n==========================================================\n";
echo "RE-UPLOAD COMPLETED SUCCESSFULLY!\n";
echo "Total Assessments Created: $total_assessments\n";
echo "Total Assessment Parts Created: $total_parts\n";
echo "Total Questions Created: $total_questions\n";
echo "Total Questions with Images Mapped: $total_images\n";
echo "==========================================================\n";
