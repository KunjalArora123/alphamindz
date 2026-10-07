<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

$assessments = [36, 37, 38, 39, 40, 41];

foreach ($assessments as $aid) {
    echo "=== ASSESSMENT ID $aid SECTIONS ===\n";
    $res = $mysqli->query("SELECT q.id, q.question_number, q.part_id, q.subject, ap.part_name FROM questions q LEFT JOIN assessment_parts ap ON q.part_id = ap.id WHERE q.assessment_id = $aid ORDER BY q.part_id ASC, q.question_number ASC");
    $sections = [];
    while ($r = $res->fetch_assoc()) {
        $s_name = !empty($r['part_name']) ? $r['part_name'] : $r['subject'];
        $sections[$s_name][] = $r['id'];
    }
    $sec_num = 1;
    foreach ($sections as $name => $qs) {
        echo "  Section $sec_num: '$name' => " . count($qs) . " questions\n";
        $sec_num++;
    }
    echo "\n";
}
