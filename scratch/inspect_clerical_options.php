<?php
define('BASEPATH', __DIR__ . '/../system/');
define('APPPATH', __DIR__ . '/../application/');
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';

require_once APPPATH . 'config/database.php';
$link = mysqli_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

$res = mysqli_query($link, 'SELECT q.id, q.question_number, q.question_text, q.correct_option, q.option_a, q.option_b, q.option_c, q.option_d, q.image_path, q.image_path_2, q.option_a_image, q.option_b_image, q.option_c_image, q.option_d_image FROM questions q LEFT JOIN assessment_parts ap ON q.part_id = ap.id WHERE (ap.part_name LIKE "%clerical%" OR q.subject LIKE "%clerical%") AND q.question_number >= 13 AND q.question_number <= 20 ORDER BY q.question_number');

while ($row = mysqli_fetch_assoc($res)) {
    echo "Q#: {$row['question_number']} | Correct: {$row['correct_option']}\n";
    echo "  Text: {$row['question_text']}\n";
    echo "  A: '{$row['option_a']}' | B: '{$row['option_b']}' | C: '{$row['option_c']}' | D: '{$row['option_d']}'\n";
    echo "  Img: '{$row['image_path']}' | Img2: '{$row['image_path_2']}'\n";
    echo "  OptImgs: A:'{$row['option_a_image']}' B:'{$row['option_b_image']}' C:'{$row['option_c_image']}' D:'{$row['option_d_image']}'\n\n";
}
