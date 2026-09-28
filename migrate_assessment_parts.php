<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Set assessment_id = 5 for all questions where subject is in standard parts
$db->query("UPDATE questions SET assessment_id = 5 WHERE subject IN ('Mechanical Ability', 'Verbal Ability', 'Numerical Ability', 'Reasoning Ability', 'Spatial Ability', 'General Science Ability')");

// 2. Set part_id matching assessment_parts for assessment_id = 5
$db->query("UPDATE questions q 
JOIN assessment_parts ap ON ap.assessment_id = 5 AND ap.part_name = q.subject 
SET q.part_id = ap.id 
WHERE q.assessment_id = 5");

echo "Updated questions for assessment_id = 5.\n";
$db->close();
