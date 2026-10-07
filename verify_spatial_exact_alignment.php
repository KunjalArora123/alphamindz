<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');

echo "=== VERIFYING EXACT SPATIAL ALIGNMENT FOR ALL 6 ASSESSMENTS ===\n\n";

$spatial_map = [
    1 => 'questions-images/Commerce_img_2.png',
    2 => 'questions-images/Commerce_img_3.png',
    3 => 'questions-images/Commerce_img_4.png',
    4 => 'questions-images/Commerce_img_5.png',
    5 => 'questions-images/Commerce_img_6.png',
    6 => 'questions-images/Commerce_img_7.png',
    7 => 'questions-images/Commerce_img_8.png',
    8 => 'questions-images/Commerce_img_9.png',
    9 => 'questions-images/Commerce_img_10.png',
    10 => 'questions-images/Commerce_img_11.png',
];

$assessments = [36, 37, 38, 39, 40, 41];

foreach ($assessments as $aid) {
    for ($q = 1; $q <= 10; $q++) {
        $path = $spatial_map[$q];
        $mysqli->query("UPDATE questions SET image_path = '$path' WHERE assessment_id = $aid AND question_number = $q AND (subject LIKE '%spatial%' OR part_id IN (SELECT id FROM assessment_parts WHERE part_name LIKE '%spatial%'))");
    }
    echo "Updated Assessment ID $aid Spatial Q1-Q10 images.\n";
}
