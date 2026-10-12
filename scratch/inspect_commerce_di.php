<?php
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';

require_once APPPATH . 'config/database.php';
$link = mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
if (!$link) { die('Connect error: ' . mysqli_connect_error()); }

$query = "SELECT q.id, a.id as assessment_id, a.title as test_title, ap.id as part_id, ap.part_name, 
                 q.question_number, q.question_text, q.image_path, q.image_path_2 
          FROM questions q 
          JOIN assessment_parts ap ON q.part_id = ap.id 
          JOIN assessments a ON q.assessment_id = a.id 
          WHERE a.title LIKE '%COMMERCE%' AND ap.part_name LIKE '%DATA%' 
          ORDER BY q.question_number";

$res = mysqli_query($link, $query);
echo "Rows found: " . mysqli_num_rows($res) . "\n";
while ($row = mysqli_fetch_assoc($res)) {
    echo "Q#: {$row['question_number']} | ID: {$row['id']} | Part: {$row['part_name']}\n";
    echo "  Text: " . substr($row['question_text'], 0, 80) . "...\n";
    echo "  Img1: '{$row['image_path']}'\n";
    echo "  Img2: '{$row['image_path_2']}'\n";
    echo "--------------------------------------------------------\n";
}
