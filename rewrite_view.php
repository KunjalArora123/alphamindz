<?php
$file = 'd:\xampp\htdocs\AlphaMindz\application\views\admin\test_analysis.php';
$content = file_get_contents($file);

// We need to find the start of the Chart & Summary div and wrap things in tabs
$start = strpos($content, '<!-- Summary Stats & Chart -->');

$top_html = substr($content, 0, $start);

$tabs_html = '
        <ul class="nav nav-tabs mb-4" id="analysisTabs" role="tablist">
            <?php if(isset($interest_answers)): ?>
            <li class="nav-item">
                <a class="nav-link" id="interest-tab" data-toggle="tab" href="#interest" role="tab" aria-controls="interest" aria-selected="false">Interest Inventory</a>
            </li>
            <?php endif; ?>
            
            <?php if(isset($mbti_answers)): ?>
            <li class="nav-item">
                <a class="nav-link" id="mbti-tab" data-toggle="tab" href="#mbti" role="tab" aria-controls="mbti" aria-selected="false">MBTI Profile</a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item">
                <a class="nav-link active" id="target-tab" data-toggle="tab" href="#target" role="tab" aria-controls="target" aria-selected="true"><?php echo htmlspecialchars($attempt->subject); ?></a>
            </li>
        </ul>
        
        <div class="tab-content" id="analysisTabsContent">
            
            <?php if(isset($interest_answers)): ?>
            <div class="tab-pane fade" id="interest" role="tabpanel" aria-labelledby="interest-tab">
                <h4 class="text-primary mb-3 mt-2">Section 1</h4>
                <table class="table table-bordered table-striped mb-5">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50%;">Question</th>
                            <th>Student\'s Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== \'Section 1\') continue; ?>
                        <tr>
                            <td class="font-weight-bold"><?php echo htmlspecialchars($ans->question_text); ?></td>
                            <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h4 class="text-primary mb-3 mt-4">Section 2: Preliminary Career Interest & Background Profile</h4>
                <table class="table table-bordered table-striped mb-4">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50%;">Question</th>
                            <th>Student\'s Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== \'Section 2\') continue; ?>
                        <tr>
                            <td class="font-weight-bold"><?php echo nl2br(htmlspecialchars($ans->question_text)); ?></td>
                            <td><?php echo nl2br(htmlspecialchars((string)$ans->selected_option)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <?php if(isset($mbti_answers)): ?>
            <div class="tab-pane fade" id="mbti" role="tabpanel" aria-labelledby="mbti-tab">
                <div class="row mt-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="text-muted mb-2">Personality Type</h5>
                                <h2 class="display-4 font-weight-bold text-primary mb-0"><?php echo $mbti_type; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-3">
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
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 13px; font-weight: 600;">
                                        <span class="<?php echo ($pct1 >= $pct2) ? \'text-primary\' : \'text-muted\'; ?>"><?php echo $name1; ?> (<?php echo $pct1; ?>%)</span>
                                        <span class="<?php echo ($pct2 >= $pct1) ? \'text-primary\' : \'text-muted\'; ?>"><?php echo $name2; ?> (<?php echo $pct2; ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar <?php echo ($pct1 >= $pct2) ? \'bg-primary\' : \'bg-secondary\'; ?>" style="width: <?php echo $pct1; ?>%"></div>
                                        <div class="progress-bar <?php echo ($pct2 >= $pct1) ? \'bg-primary\' : \'bg-secondary\'; ?>" style="width: <?php echo $pct2; ?>%"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h4 class="text-primary mt-4 mb-3">MBTI Answers</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Question</th>
                                <th class="text-center" style="width: 150px;">Selected Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($mbti_answers as $ans): ?>
                            <tr>
                                <td><?php echo $ans->question_number; ?></td>
                                <td><?php echo htmlspecialchars($ans->question_text); ?></td>
                                <td class="text-center font-weight-bold text-primary"><?php echo strtoupper((string)$ans->selected_option); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="tab-pane fade show active" id="target" role="tabpanel" aria-labelledby="target-tab">
';

// The rest of the content (chart, questions table)
$rest_html = substr($content, $start);

// Wait, the bottom of the content has </div></div>
// We need to close the tab-pane and tab-content.
$end_pos = strpos($rest_html, '</div>', strrpos($rest_html, '<script>'));
// Let\'s just append the closing tags right before the script tag.
$script_pos = strpos($rest_html, '<script>');
$before_script = substr($rest_html, 0, $script_pos);
$after_script = substr($rest_html, $script_pos);

$final_html = $top_html . $tabs_html . $before_script . '
            </div> <!-- end target tab -->
        </div> <!-- end tab-content -->
' . $after_script;

file_put_contents($file, $final_html);
echo "View rewritten with tabs";
?>
