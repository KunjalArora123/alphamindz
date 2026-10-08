<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

echo "=== DATABASE INTEGRITY REPORT ===\n\n";

$res = $db->query("SELECT id, title, slug, time_limit FROM assessments WHERE id NOT IN (33, 34) ORDER BY id ASC");
while ($ass = $res->fetch_assoc()) {
    echo "ASSESSMENT ID {$ass['id']}: {$ass['title']} (Slug: {$ass['slug']})\n";
    $p_res = $db->query("SELECT id, part_name FROM assessment_parts WHERE assessment_id = {$ass['id']} ORDER BY id ASC");
    $total_ass_q = 0;
    $total_ass_img = 0;
    
    while ($p = $p_res->fetch_assoc()) {
        $q_res = $db->query("SELECT COUNT(*) as q_cnt, 
            SUM(CASE WHEN image_path != '' OR image_path_2 != '' OR option_a_image != '' OR option_b_image != '' OR option_c_image != '' OR option_d_image != '' THEN 1 ELSE 0 END) as img_cnt,
            SUM(CASE WHEN option_a_image != '' OR option_b_image != '' OR option_c_image != '' OR option_d_image != '' THEN 1 ELSE 0 END) as opt_img_cnt
            FROM questions WHERE assessment_id = {$ass['id']} AND part_id = {$p['id']}");
        $q_stats = $q_res->fetch_assoc();
        
        $q_cnt = $q_stats['q_cnt'];
        $img_cnt = $q_stats['img_cnt'];
        $opt_img_cnt = $q_stats['opt_img_cnt'];
        
        $total_ass_q += $q_cnt;
        $total_ass_img += $img_cnt;
        
        echo "   Part ID {$p['id']} [{$p['part_name']}]: $q_cnt questions (with images: $img_cnt, with option images: $opt_img_cnt)\n";
    }
    echo "   TOTAL: $total_ass_q questions ($total_ass_img with images)\n\n";
}
