<?php
define('FCPATH', __DIR__ . '/../');

function base_url($path = '') {
    return 'http://localhost/AlphaMindz/' . $path;
}

function getQuestionImage($subject, $qNum) {
    if (stripos($subject, 'spatial') === false) {
        return null;
    }
    $prefix = 'spatial';
    
    $extensions = ['png', 'jpg', 'jpeg', 'svg', 'gif'];
    foreach ($extensions as $ext) {
        $filename = "assets/images/assessment_images/{$prefix}_q{$qNum}.{$ext}";
        if (file_exists(FCPATH . $filename)) {
            return base_url($filename);
        }
    }
    return null;
}

$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== VERIFYING TEST QUESTIONS DISPLAY LOGIC ===\n\n";

$tests_to_check = [
    59 => 'COMMERCE 2026 Assessment',
    60 => 'SCIENCE 2026 Assessment',
    55 => '10th AAT 2026 Assessment'
];

foreach ($tests_to_check as $ass_id => $ass_title) {
    echo "--- ASSESSMENT: {$ass_title} (ID {$ass_id}) ---\n";
    $res = $mysqli->query("SELECT q.*, ap.part_name FROM questions q LEFT JOIN assessment_parts ap ON q.part_id = ap.id WHERE q.assessment_id = {$ass_id} AND q.question_number = 4 AND (ap.part_name LIKE '%NUMERICAL%' OR q.subject LIKE '%NUMERICAL%')");
    
    while ($row = $res->fetch_assoc()) {
        $q = (object)$row;
        $section_name = !empty($q->part_name) ? $q->part_name : $q->subject;
        $img1 = (!empty($q->image_path) && file_exists(FCPATH . $q->image_path)) ? base_url($q->image_path) : getQuestionImage($q->subject, $q->question_number);
        
        echo "Section: {$section_name} | Q#{$q->question_number}\n";
        echo "Text: {$q->question_text}\n";
        echo "Computed Image Path (img1): " . ($img1 ? $img1 : "NULL (No image displayed)") . "\n\n";
    }
}
