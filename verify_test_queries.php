<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

$assessments = $mysqli->query('SELECT id, title, slug FROM assessments WHERE status="active"')->fetch_all(MYSQLI_ASSOC);

echo "=== VERIFYING QUESTION COUNTS FOR ALL ACTIVE ASSESSMENTS ===\n\n";

foreach ($assessments as $a) {
    $id = $a['id'];
    $title = $a['title'];
    
    if ($title == 'MBTI Personality Profiling Test') {
        $json = json_decode(file_get_contents('application/config/mbti_questions.json'), true);
        echo "Assessment ID: $id | Title: '$title' => Count: " . count($json) . " (MBTI JSON)\n";
        continue;
    }
    
    if ($title == 'Interest Inventory Test') {
        $json = json_decode(file_get_contents('application/config/interest_inventory.json'), true);
        echo "Assessment ID: $id | Title: '$title' => Count: " . count($json) . " (Interest JSON)\n";
        continue;
    }

    // Simulate new query logic
    $res = $mysqli->query("SELECT q.*, ap.part_name FROM questions q LEFT JOIN assessment_parts ap ON q.part_id = ap.id WHERE q.assessment_id = $id ORDER BY q.part_id ASC, q.question_number ASC");
    $questions = $res->fetch_all(MYSQLI_ASSOC);
    $count = count($questions);
    
    // Also check admin panel count for comparison
    $admin_res = $mysqli->query("SELECT COUNT(*) as cnt FROM questions WHERE assessment_id = $id");
    $admin_count = $admin_res->fetch_assoc()['cnt'];
    
    $match = ($count === $admin_count) ? "MATCHES ADMIN PANEL EXACTLY" : "DISCREPANCY DETECTED!";
    echo "Assessment ID: $id | Title: '$title'\n";
    echo "  -> Student view question count: $count\n";
    echo "  -> Admin panel question count: $admin_count\n";
    echo "  -> Status: $match\n\n";
}
