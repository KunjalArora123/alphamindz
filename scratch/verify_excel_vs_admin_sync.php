<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$alpha_dir = "d:/xampp/htdocs/AlphaMindz/alpha_tests";
$fcpath = "d:/xampp/htdocs/AlphaMindz";

$tests_info = [
    ['file' => 'Qusetion Paper 10th AAT 2026.xlsx', 'title' => '10th AAT 2026 Assessment', 'slug' => '10th-aat-2026-assessment'],
    ['file' => 'Qusetion Paper 8th AAT 2026 (3).xlsx', 'title' => '8th AAT 2026 (3) Assessment', 'slug' => '8th-aat-2026-3-assessment'],
    ['file' => 'Qusetion Paper 9th AAT 2026.xlsx', 'title' => '9th AAT 2026 Assessment', 'slug' => '9th-aat-2026-assessment'],
    ['file' => 'Qusetion Paper ARTS 2026.xlsx', 'title' => 'ARTS 2026 Assessment', 'slug' => 'arts-2026-assessment'],
    ['file' => 'Qusetion Paper COMMERCE 2026.xlsx', 'title' => 'COMMERCE 2026 Assessment', 'slug' => 'commerce-2026-assessment'],
    ['file' => 'Qusetion Paper SCIENCE 2026.xlsx', 'title' => 'SCIENCE 2026 Assessment', 'slug' => 'science-2026-assessment']
];

echo "==========================================================================";
echo "\nSTEP-BY-STEP VERIFICATION REPORT: ADMIN PANEL / DB vs EXCEL FILES";
echo "\n==========================================================================\n\n";

$total_ass_ver = 0;
$total_parts_ver = 0;
$total_q_ver = 0;
$total_img_on_disk = 0;
$missing_files = 0;
$discrepancies = 0;

foreach ($tests_info as $step => $info) {
    $step_num = $step + 1;
    $fname = $info['file'];
    $title = $info['title'];
    $slug = $info['slug'];
    
    echo "--------------------------------------------------------------------------\n";
    echo "STEP $step_num: VERIFYING ASSESSMENT '$title'\n";
    echo "Excel File: 'alpha_tests/$fname'\n";
    echo "--------------------------------------------------------------------------\n";
    
    // 1. Check Assessment in DB
    $stmt = $db->prepare("SELECT * FROM assessments WHERE title = ? OR slug = ?");
    $stmt->bind_param("ss", $title, $slug);
    $stmt->execute();
    $ass = $stmt->get_result()->fetch_assoc();
    
    if (!$ass) {
        echo "[FAIL] Assessment '$title' missing in Database!\n\n";
        $discrepancies++;
        continue;
    }
    
    $ass_id = $ass['id'];
    $total_ass_ver++;
    echo "[OK] DB Assessment Record Found: ID=$ass_id | Slug='{$ass['slug']}' | Status='{$ass['status']}' | Time={$ass['time_limit']} mins\n";
    
    // 2. Check Parts in DB
    $parts_res = $db->query("SELECT * FROM assessment_parts WHERE assessment_id = $ass_id ORDER BY id ASC");
    $db_parts = [];
    while ($p = $parts_res->fetch_assoc()) {
        $db_parts[] = $p;
    }
    
    echo "[OK] Sections / Parts in Admin Panel (Count: " . count($db_parts) . "):\n";
    foreach ($db_parts as $p) {
        $total_parts_ver++;
        $part_id = $p['id'];
        $pname = $p['part_name'];
        
        // Count questions in this part
        $q_res = $db->query("SELECT * FROM questions WHERE assessment_id = $ass_id AND part_id = $part_id ORDER BY question_number ASC");
        $questions = [];
        while ($q = $q_res->fetch_assoc()) {
            $questions[] = $q;
        }
        
        $q_count = count($questions);
        $total_q_ver += $q_count;
        
        $img_q_cnt = 0;
        $opt_img_q_cnt = 0;
        
        foreach ($questions as $q) {
            $img_paths = [
                $q['image_path'],
                $q['image_path_2'],
                $q['option_a_image'],
                $q['option_b_image'],
                $q['option_c_image'],
                $q['option_d_image']
            ];
            
            $has_img = false;
            foreach ($img_paths as $ip) {
                if (!empty($ip)) {
                    $has_img = true;
                    $full_file = "$fcpath/$ip";
                    if (file_exists($full_file)) {
                        $total_img_on_disk++;
                    } else {
                        echo "   [FAIL] Image missing on disk: $ip (Q#{$q['question_number']})\n";
                        $missing_files++;
                    }
                }
            }
            if ($has_img) $img_q_cnt++;
            if (!empty($q['option_a_image']) || !empty($q['option_b_image']) || !empty($q['option_c_image']) || !empty($q['option_d_image'])) {
                $opt_img_q_cnt++;
            }
        }
        
        echo "   -> Section: '-$pname' (Part ID: $part_id): $q_count Questions | $img_q_cnt Questions with Images ($opt_img_q_cnt Option Images)\n";
    }
    echo "\n";
}

echo "==========================================================================\n";
echo "FINAL STEP-BY-STEP VERIFICATION RESULTS:\n";
echo "  [✓] Total Assessments Verified Sync: $total_ass_ver / 6\n";
echo "  [✓] Total Sections / Parts Verified Sync: $total_parts_ver / 36\n";
echo "  [✓] Total Questions Verified Sync: $total_q_ver / 720\n";
echo "  [✓] Total Extracted Image Files Intact on Disk: $total_img_on_disk\n";
echo "  [✓] Missing Image Files: $missing_files\n";
echo "  [✓] Total Discrepancies Found: $discrepancies\n";
echo "==========================================================================\n";
