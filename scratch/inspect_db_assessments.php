<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$res = $db->query("SELECT id, title, slug, time_limit, status FROM assessments");
echo "=== CURRENT ASSESSMENTS ===\n";
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Title: {$row['title']} | Slug: {$row['slug']} | Time: {$row['time_limit']} min\n";
    $parts = $db->query("SELECT id, part_name FROM assessment_parts WHERE assessment_id = {$row['id']}");
    while ($p = $parts->fetch_assoc()) {
        $q_count = $db->query("SELECT COUNT(*) FROM questions WHERE assessment_id = {$row['id']} AND part_id = {$p['id']}")->fetch_row()[0];
        $img_q_count = $db->query("SELECT COUNT(*) FROM questions WHERE assessment_id = {$row['id']} AND part_id = {$p['id']} AND (image_path IS NOT NULL AND image_path != '' OR option_a_image IS NOT NULL AND option_a_image != '')")->fetch_row()[0];
        echo "   Part: {$p['part_name']} (ID: {$p['id']}) - Questions: $q_count (with images: $img_q_count)\n";
    }
}
