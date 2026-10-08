<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$res = $db->query("SELECT q.id, q.assessment_id, a.title as ass_title, q.question_number, q.subject, q.option_a, q.option_b, q.option_c, q.option_d, q.option_a_image, q.option_b_image, q.option_c_image, q.option_d_image 
FROM questions q 
JOIN assessments a ON q.assessment_id = a.id 
WHERE (q.option_a = '' AND q.option_a_image = '') 
   OR (q.option_b = '' AND q.option_b_image = '') 
   OR (q.option_c = '' AND q.option_c_image = '') 
   OR (q.option_d = '' AND q.option_d_image = '')");

echo "=== QUESTIONS WITH MISSING/EMPTY OPTIONS ===\n\n";
$empty_by_ass = [];
while ($row = $res->fetch_assoc()) {
    $ass = $row['ass_title'];
    $subj = $row['subject'];
    $qn = $row['question_number'];
    if (!isset($empty_by_ass[$ass])) $empty_by_ass[$ass] = [];
    if (!isset($empty_by_ass[$ass][$subj])) $empty_by_ass[$ass][$subj] = [];
    $empty_by_ass[$ass][$subj][] = $qn;
}

foreach ($empty_by_ass as $ass => $subjs) {
    echo "Assessment: '$ass'\n";
    foreach ($subjs as $subj => $qnums) {
        echo "   Subject/Part: '$subj' -> Questions without options: " . implode(', ', $qnums) . "\n";
    }
    echo "\n";
}
