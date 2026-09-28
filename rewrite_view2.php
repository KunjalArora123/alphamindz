<?php
$file = 'd:\xampp\htdocs\AlphaMindz\application\views\admin\test_analysis.php';
$content = file_get_contents('d:\xampp\htdocs\AlphaMindz\application\views\admin\test_analysis_fixed.bak.php');

$start = strpos($content, '<!-- Summary Stats & Chart -->');
$top_html = substr($content, 0, $start);

$tabs_html = '
        <ul class="nav nav-tabs mb-4" id="analysisTabs" role="tablist">
            <?php if(isset($interest_answers)): ?>
            <li class="nav-item">
                <a class="nav-link" id="interest-tab" data-toggle="tab" href="#interest" role="tab" aria-controls="interest" aria-selected="false" style="font-weight: 600; font-size: 1.1rem; color: #495057; background-color: #f8f9fa; border: 1px solid #dee2e6; margin-right: 5px;">Interest Inventory</a>
            </li>
            <?php endif; ?>
            
            <?php if(isset($mbti_answers)): ?>
            <li class="nav-item">
                <a class="nav-link" id="mbti-tab" data-toggle="tab" href="#mbti" role="tab" aria-controls="mbti" aria-selected="false" style="font-weight: 600; font-size: 1.1rem; color: #495057; background-color: #f8f9fa; border: 1px solid #dee2e6; margin-right: 5px;">MBTI Profile</a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item">
                <a class="nav-link active" id="target-tab" data-toggle="tab" href="#target" role="tab" aria-controls="target" aria-selected="true" style="font-weight: 600; font-size: 1.1rem; color: #495057; background-color: #f8f9fa; border: 1px solid #dee2e6; margin-right: 5px;"><?php echo htmlspecialchars($attempt->subject); ?></a>
            </li>
        </ul>
        
        <div class="tab-content" id="analysisTabsContent">
            
            <?php if(isset($interest_answers)): ?>
            <div class="tab-pane fade" id="interest" role="tabpanel" aria-labelledby="interest-tab" style="padding: 20px; border: 1px solid #dee2e6; border-top: none;">
                <h4 style="color: #4f46e5; margin-bottom: 15px; margin-top: 10px;">Section 1</h4>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;" border="1" cellpadding="10" bordercolor="#dee2e6">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50%;">Question</th>
                            <th>Student\'s Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== \'Section 1\') continue; ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo htmlspecialchars($ans->question_text); ?></td>
                            <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h4 style="color: #4f46e5; margin-bottom: 15px; margin-top: 20px;">Section 2: Preliminary Career Interest & Background Profile</h4>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;" border="1" cellpadding="10" bordercolor="#dee2e6">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50%;">Question</th>
                            <th>Student\'s Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== \'Section 2\') continue; ?>
                        <tr>
                            <td style="font-weight: bold;"><?php echo nl2br(htmlspecialchars($ans->question_text)); ?></td>
                            <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <?php if(isset($mbti_answers)): ?>
            <div class="tab-pane fade" id="mbti" role="tabpanel" aria-labelledby="mbti-tab" style="padding: 20px; border: 1px solid #dee2e6; border-top: none;">
                <div style="display: flex; gap: 20px; margin-bottom: 30px; margin-top: 15px;">
                    <div style="flex: 1; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; text-align: center;">
                        <h5 style="color: #6c757d; margin-bottom: 10px;">Personality Type</h5>
                        <h2 style="font-size: 3rem; font-weight: bold; color: #4f46e5; margin: 0;"><?php echo $mbti_type; ?></h2>
                    </div>
                    <div style="flex: 1; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px;">
                        <?php 
                        $pairs = [
                            [\'E\', \'Extraversion\', \'I\', \'Introversion\'],
                            [\'S\', \'Sensing\', \'N\', \'Intuition\'],
                            [\'T\', \'Thinking\', \'F\', \'Feeling\'],
                            [\'J\', \'Judging\', \'P\', \'Perceiving\']
                        ];
                        foreach ($pairs as $p):
                            $t1 = $p[0]; $name1 = $p[1];
                            $t2 = $p[2]; $name2 = $p[3];
                            $s1 = $mbti_scores[$t1];
                            $s2 = $mbti_scores[$t2];
                            $m1 = $mbti_max[$t1];
                            $m2 = $mbti_max[$t2];
                            $pct1 = ($m1 > 0) ? round(($s1 / $m1) * 100) : 0;
                            $pct2 = ($m2 > 0) ? round(($s2 / $m2) * 100) : 0;
                        ?>
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; font-weight: 600;">
                                <span style="color: <?php echo ($pct1 >= $pct2) ? \'#4f46e5\' : \'#6c757d\'; ?>"><?php echo $name1; ?> (<?php echo $pct1; ?>%)</span>
                                <span style="color: <?php echo ($pct2 >= $pct1) ? \'#4f46e5\' : \'#6c757d\'; ?>"><?php echo $name2; ?> (<?php echo $pct2; ?>%)</span>
                            </div>
                            <div style="height: 8px; background-color: #e9ecef; border-radius: 4px; display: flex; overflow: hidden;">
                                <div style="width: <?php echo $pct1; ?>%; background-color: <?php echo ($pct1 >= $pct2) ? \'#4f46e5\' : \'#adb5bd\'; ?>;"></div>
                                <div style="width: <?php echo $pct2; ?>%; background-color: <?php echo ($pct2 >= $pct1) ? \'#4f46e5\' : \'#adb5bd\'; ?>;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <h4 style="color: #4f46e5; margin-bottom: 15px;">MBTI Answers</h4>
                <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="10" bordercolor="#dee2e6">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Question</th>
                            <th style="width: 150px; text-align: center;">Selected Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mbti_answers as $ans): ?>
                        <tr>
                            <td><?php echo $ans->question_number; ?></td>
                            <td><?php echo htmlspecialchars($ans->question_text); ?></td>
                            <td style="text-align: center; font-weight: bold; color: #4f46e5;"><?php echo strtoupper((string)$ans->selected_option); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <div class="tab-pane fade show active" id="target" role="tabpanel" aria-labelledby="target-tab" style="padding: 20px; border: 1px solid #dee2e6; border-top: none;">
';

$rest_html = substr($content, $start);

$end_pos = strpos($rest_html, '</div>', strrpos($rest_html, '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>'));
$script_pos = strpos($rest_html, '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>');
$before_script = substr($rest_html, 0, $script_pos);
$after_script = substr($rest_html, $script_pos);

// Actually, in the original test_analysis.php, the bottom is:
//     </div>
// </div>
// <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
// <script>
// ...

$final_html = $top_html . $tabs_html . $before_script . '
            </div> <!-- end target tab -->
        </div> <!-- end tab-content -->
' . $after_script;

// To style tabs correctly since it\'s a custom admin panel, I\'ll add some inline styles to make it robust.
$tabs_script = '
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll("#analysisTabs .nav-link");
        const panes = document.querySelectorAll(".tab-pane");
        
        tabs.forEach(tab => {
            tab.addEventListener("click", function(e) {
                e.preventDefault();
                // Remove active from all tabs
                tabs.forEach(t => {
                    t.classList.remove("active");
                    t.style.backgroundColor = "#f8f9fa";
                    t.style.borderBottomColor = "#dee2e6";
                });
                // Add active to clicked
                this.classList.add("active");
                this.style.backgroundColor = "#ffffff";
                this.style.borderBottomColor = "transparent";
                
                // Hide all panes
                panes.forEach(p => {
                    p.classList.remove("show", "active");
                    p.style.display = "none";
                });
                
                // Show target pane
                const targetId = this.getAttribute("href");
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add("show", "active");
                    targetPane.style.display = "block";
                }
            });
        });
        
        // Initialize
        const activeTab = document.querySelector("#analysisTabs .nav-link.active");
        if (activeTab) {
            activeTab.style.backgroundColor = "#ffffff";
            activeTab.style.borderBottomColor = "transparent";
        }
        
        panes.forEach(p => {
            if (!p.classList.contains("active")) {
                p.style.display = "none";
            }
        });
    });
</script>
';

$final_html .= $tabs_script;

file_put_contents($file, $final_html);
echo "View rewritten with styled tabs";
?>
