<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h3>Detailed Test Analysis</h3>
        <div style="display: flex; gap: 10px;">
            <a href="<?php echo site_url('admin/download_report_card/'.$attempt->id); ?>" target="_blank" class="btn btn-primary" style="padding: 6px 14px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(37,99,235,0.2);"><i class="ri-file-pdf-fill"></i> Download Report Card (PDF)</a>
            <a href="<?php echo site_url('admin/user_details/'.$user->id); ?>" class="btn btn-secondary" style="padding: 6px 12px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Back to User</a>
        </div>
    </div>
    
    <div class="card-body">
        
        
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
                            <th>Student's Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== 'Section 1') continue; ?>
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
                            <th>Student's Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($interest_answers as $ans): if($ans->section !== 'Section 2') continue; ?>
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
                            ['E', 'Extraversion', 'I', 'Introversion'],
                            ['S', 'Sensing', 'N', 'Intuition'],
                            ['T', 'Thinking', 'F', 'Feeling'],
                            ['J', 'Judging', 'P', 'Perceiving']
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
                                <span style="color: <?php echo ($pct1 >= $pct2) ? '#4f46e5' : '#6c757d'; ?>"><?php echo $name1; ?> (<?php echo $pct1; ?>%)</span>
                                <span style="color: <?php echo ($pct2 >= $pct1) ? '#4f46e5' : '#6c757d'; ?>"><?php echo $name2; ?> (<?php echo $pct2; ?>%)</span>
                            </div>
                            <div style="height: 8px; background-color: #e9ecef; border-radius: 4px; display: flex; overflow: hidden;">
                                <div style="width: <?php echo $pct1; ?>%; background-color: <?php echo ($pct1 >= $pct2) ? '#4f46e5' : '#adb5bd'; ?>;"></div>
                                <div style="width: <?php echo $pct2; ?>%; background-color: <?php echo ($pct2 >= $pct1) ? '#4f46e5' : '#adb5bd'; ?>;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                
                <h4 style="color: #4f46e5; margin-bottom: 15px;">MBTI Answers Matrix</h4>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: center; border: 2px solid #333;" border="1">
                        <thead>
                            <tr style="font-weight: bold;">
                                <th style="width: 60px; padding: 10px; border: 1px solid #333; background: #fff;">#</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #FFF200; width: 10%;">E</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #FFF200; width: 10%;">I</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #99CCFF; width: 10%;">S</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #99CCFF; width: 10%;">N</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #FF99CC; width: 10%;">T</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #FF99CC; width: 10%;">F</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #00FF00; width: 10%;">J</th>
                                <th style="padding: 10px; border: 1px solid #333; background: #00FF00; width: 10%;">P</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $traits = ['E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'];
                            $totals = array_fill_keys($traits, 0);
                            $counts = array_fill_keys($traits, 0);
                            
                            foreach($mbti_answers as $ans): 
                                $is_ei = ($ans->pair == 'E/I');
                                $is_sn = ($ans->pair == 'S/N');
                                $is_tf = ($ans->pair == 'T/F');
                                $is_jp = ($ans->pair == 'J/P');
                                
                                $val_a = ($ans->selected_option == 'a') ? 1 : 0;
                                $val_b = ($ans->selected_option == 'b') ? 1 : 0;
                                
                                if ($is_ei) { $counts['E']++; $counts['I']++; $totals['E'] += $val_a; $totals['I'] += $val_b; }
                                if ($is_sn) { $counts['S']++; $counts['N']++; $totals['S'] += $val_a; $totals['N'] += $val_b; }
                                if ($is_tf) { $counts['T']++; $counts['F']++; $totals['T'] += $val_a; $totals['F'] += $val_b; }
                                if ($is_jp) { $counts['J']++; $counts['P']++; $totals['J'] += $val_a; $totals['P'] += $val_b; }
                            ?>
                            <tr>
                                <td style="padding: 5px; font-weight: bold; border: 1px solid #333;"><?php echo $ans->question_number; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_ei ? '#E6F7FF' : '#FFF200'; ?>;"><?php echo $is_ei ? ($val_a) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_ei ? '#E6F7FF' : '#FFF200'; ?>;"><?php echo $is_ei ? ($val_b) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_sn ? '#E6F7FF' : '#99CCFF'; ?>;"><?php echo $is_sn ? ($val_a) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_sn ? '#E6F7FF' : '#99CCFF'; ?>;"><?php echo $is_sn ? ($val_b) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_tf ? '#E6F7FF' : '#FF99CC'; ?>;"><?php echo $is_tf ? ($val_a) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_tf ? '#E6F7FF' : '#FF99CC'; ?>;"><?php echo $is_tf ? ($val_b) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_jp ? '#E6F7FF' : '#00FF00'; ?>;"><?php echo $is_jp ? ($val_a) : ''; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_jp ? '#E6F7FF' : '#00FF00'; ?>;"><?php echo $is_jp ? ($val_b) : ''; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr><td colspan="9" style="border: none; height: 20px;"></td></tr>
                            <tr>
                                <td style="text-align: left; padding: 8px; font-weight: bold;">Total</td>
                                <?php foreach($traits as $t): ?>
                                <td><?php echo $totals[$t]; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td style="text-align: left; padding: 8px; font-weight: bold;">Count</td>
                                <?php foreach($traits as $t): ?>
                                <td><?php echo $counts[$t]; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td style="text-align: left; padding: 8px; font-weight: bold;">% Score</td>
                                <?php foreach($traits as $t): ?>
                                <td><?php echo $counts[$t] > 0 ? round(($totals[$t] / $counts[$t]) * 100) . '%' : '0%'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="tab-pane fade show active" id="target" role="tabpanel" aria-labelledby="target-tab" style="padding: 20px; border: 1px solid #dee2e6; border-top: none;">
<!-- Summary Stats & Chart -->
        <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
            <div style="flex: 1 1 300px;">
                <h4>Test Details</h4>
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6; width: 150px;">Student</th>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                    </tr>
                    <tr>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Subject</th>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo htmlspecialchars($attempt->subject); ?></td>
                    </tr>
                    <tr>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Date Taken</th>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo date('F d, Y h:i A', strtotime($attempt->completed_at)); ?></td>
                    </tr>
                    <tr>
                        <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Score</th>
                        <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><strong><?php echo $attempt->score; ?> / <?php echo $attempt->total_questions; ?> (<?php echo $attempt->percentage; ?>%)</strong></td>
                    </tr>
                </table>
            </div>
            
            <div style="flex: 1 1 300px; max-width: 100%; height: 250px;">
                <canvas id="analysisChart"></canvas>
            </div>
        </div>

        <?php 
            $correct = 0;
            $incorrect = 0;
            $unanswered = 0;

            foreach($answers as $ans) {
                if(is_null($ans->selected_option) || $ans->selected_option === '') {
                    $unanswered++;
                } else if($ans->is_correct) {
                    $correct++;
                } else {
                    $incorrect++;
                }
            }

            // Fallback for section_scores if not passed from controller
            if (!isset($section_scores) || empty($section_scores)) {
                $section_scores = array();
                if (!empty($answers)) {
                    foreach ($answers as $ans) {
                        $sec_key = !empty($ans->part_id) ? $ans->part_id : (!empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General'));
                        $sec_name = !empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General Section');

                        if (!isset($section_scores[$sec_key])) {
                            $section_scores[$sec_key] = array(
                                'part_id' => !empty($ans->part_id) ? $ans->part_id : null,
                                'section_name' => $sec_name,
                                'total_questions' => 0,
                                'scored_marks' => 0,
                                'correct' => 0,
                                'incorrect' => 0,
                                'unanswered' => 0,
                                'percentage' => 0
                            );
                        }

                        $section_scores[$sec_key]['total_questions']++;

                        if (is_null($ans->selected_option) || $ans->selected_option === '') {
                            $section_scores[$sec_key]['unanswered']++;
                        } elseif ($ans->is_correct == 1) {
                            $section_scores[$sec_key]['scored_marks']++;
                            $section_scores[$sec_key]['correct']++;
                        } else {
                            $section_scores[$sec_key]['incorrect']++;
                        }
                    }

                    foreach ($section_scores as &$sec) {
                        $sec['percentage'] = ($sec['total_questions'] > 0)
                            ? round(($sec['scored_marks'] / $sec['total_questions']) * 100, 2)
                            : 0;
                    }
                    unset($sec);
                    $section_scores = array_values($section_scores);
                }
            }
        ?>

        <!-- Section-Wise Marks Breakdown Table -->
        <div style="margin-bottom: 35px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <div style="padding: 16px 20px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h4 style="margin: 0 0 4px 0; color: #1e293b; font-size: 17px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-table-line" style="color: #2563eb; font-size: 19px;"></i> Section-Wise Score & Marks Breakdown
                    </h4>
                    <p style="margin: 0; color: #64748b; font-size: 13px;">Marks scored by the student in each section out of the maximum marks.</p>
                </div>
                <span style="background-color: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; border: 1px solid #bfdbfe;">
                    <?php echo !empty($section_scores) ? count($section_scores) : 0; ?> Sections Evaluated
                </span>
            </div>

            <div style="overflow-x: auto;">
                <table class="table" style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 12px 16px; text-align: center; width: 50px;">#</th>
                            <th style="padding: 12px 16px; text-align: left; min-width: 200px;">Section / Test Part</th>
                            <th style="padding: 12px 16px; text-align: center; min-width: 120px;">Marks Scored</th>
                            <th style="padding: 12px 16px; text-align: center; min-width: 120px;">Total Marks</th>
                            <th style="padding: 12px 16px; text-align: center; min-width: 110px;">Score %</th>
                            <th style="padding: 12px 16px; text-align: left; min-width: 180px;">Performance Progress</th>
                            <th style="padding: 12px 16px; text-align: center; min-width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_sec_scored = 0;
                        $total_sec_marks = 0;
                        if (!empty($section_scores)): 
                            foreach($section_scores as $s_idx => $sec):
                                $total_sec_scored += $sec['scored_marks'];
                                $total_sec_marks += $sec['total_questions'];
                                
                                $sec_pct = $sec['percentage'];
                                
                                // Color & Status logic
                                if ($sec_pct >= 75) {
                                    $badge_bg = '#dcfce7';
                                    $badge_color = '#15803d';
                                    $badge_border = '#bbf7d0';
                                    $bar_color = '#16a34a';
                                    $status_label = 'High Mastery';
                                    $status_icon = 'ri-checkbox-circle-fill';
                                } elseif ($sec_pct >= 50) {
                                    $badge_bg = '#eff6ff';
                                    $badge_color = '#1d4ed8';
                                    $badge_border = '#bfdbfe';
                                    $bar_color = '#2563eb';
                                    $status_label = 'Proficient';
                                    $status_icon = 'ri-check-line';
                                } elseif ($sec_pct >= 35) {
                                    $badge_bg = '#fef3c7';
                                    $badge_color = '#b45309';
                                    $badge_border = '#fde68a';
                                    $bar_color = '#d97706';
                                    $status_label = 'Developing';
                                    $status_icon = 'ri-alert-line';
                                } else {
                                    $badge_bg = '#fee2e2';
                                    $badge_color = '#b91c1c';
                                    $badge_border = '#fca5a5';
                                    $bar_color = '#dc2626';
                                    $status_label = 'Needs Focus';
                                    $status_icon = 'ri-close-circle-fill';
                                }

                                $clean_sec_name = ucwords(strtolower(trim($sec['section_name'])));
                                $clean_sec_name = str_ireplace(['gk', 'aat'], ['GK', 'AAT'], $clean_sec_name);
                        ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='transparent';">
                            <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #94a3b8;"><?php echo ($s_idx + 1); ?></td>
                            <td style="padding: 14px 16px;">
                                <span style="font-weight: 700; color: #0f172a; font-size: 14px; display: block;"><?php echo htmlspecialchars($clean_sec_name); ?></span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="font-size: 17px; font-weight: 800; color: #16a34a;"><?php echo $sec['scored_marks']; ?></span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="font-size: 15px; font-weight: 700; color: #475569;"><?php echo $sec['total_questions']; ?></span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="background-color: <?php echo $badge_bg; ?>; color: <?php echo $badge_color; ?>; border: 1px solid <?php echo $badge_border; ?>; padding: 4px 10px; border-radius: 14px; font-size: 13px; font-weight: 700; display: inline-block;">
                                    <?php echo number_format($sec_pct, 1); ?>%
                                </span>
                            </td>
                            <td style="padding: 14px 16px;">
                                <div style="height: 8px; width: 100%; max-width: 180px; background: #e2e8f0; border-radius: 9999px; overflow: hidden; margin-bottom: 5px;">
                                    <div style="height: 100%; width: <?php echo min(100, $sec_pct); ?>%; background: <?php echo $bar_color; ?>; border-radius: 9999px;"></div>
                                </div>
                                <span style="font-size: 11px; color: #64748b; font-weight: 500;">
                                    <?php echo $sec['correct']; ?> Correct • <?php echo $sec['incorrect']; ?> Incorrect<?php if($sec['unanswered'] > 0): ?> • <?php echo $sec['unanswered']; ?> Unanswered<?php endif; ?>
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="color: <?php echo $badge_color; ?>; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="<?php echo $status_icon; ?>"></i> <?php echo $status_label; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if (!empty($mbti_type)): ?>
                        <tr style="background: linear-gradient(90deg, #f5f3ff 0%, #ede9fe 100%); border-bottom: 2px solid #ddd6fe;">
                            <td style="padding: 14px 16px; text-align: center; font-weight: 700; color: #7c3aed;"><i class="ri-psychotherapy-line" style="font-size: 18px;"></i></td>
                            <td style="padding: 14px 16px;">
                                <span style="font-weight: 800; color: #5b21b6; font-size: 14px; display: block;">MBTI Personality Profile</span>
                                <span style="font-size: 12px; color: #6d28d9;"><?php echo isset($mbti_title) ? htmlspecialchars($mbti_title) : 'Myers-Briggs Type Indicator'; ?></span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;" colspan="2">
                                <span style="display: inline-block; background: #7c3aed; color: #ffffff; padding: 4px 14px; border-radius: 20px; font-weight: 800; font-size: 14px; letter-spacing: 1px; box-shadow: 0 2px 4px rgba(124, 58, 237, 0.25);">
                                    Archetype: <?php echo $mbti_type; ?>
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="background-color: #ddd6fe; color: #5b21b6; border: 1px solid #c4b5fd; padding: 4px 10px; border-radius: 14px; font-size: 12px; font-weight: 700; display: inline-block;">
                                    Assessed
                                </span>
                            </td>
                            <td style="padding: 14px 16px;">
                                <span style="font-size: 12px; color: #5b21b6; font-weight: 600;">
                                    <?php 
                                        if (isset($mbti_scores)) {
                                            echo 'E:'.$mbti_scores['E'].' I:'.$mbti_scores['I'].' | S:'.$mbti_scores['S'].' N:'.$mbti_scores['N'].' | T:'.$mbti_scores['T'].' F:'.$mbti_scores['F'].' | J:'.$mbti_scores['J'].' P:'.$mbti_scores['P'];
                                        } else {
                                            echo 'Personality Assessment Complete';
                                        }
                                    ?>
                                </span>
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <a href="#mbti" onclick="document.getElementById('mbti-tab') ? document.getElementById('mbti-tab').click() : null;" style="color: #6d28d9; font-weight: 700; font-size: 12px; text-decoration: underline; cursor: pointer;">
                                    View Profile &rarr;
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <?php else: ?>
                        <tr>
                            <td colspan="7" style="padding: 24px; text-align: center; color: #64748b;">No section breakdown available for this attempt.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($section_scores)): 
                        $overall_sec_pct = ($total_sec_marks > 0) ? round(($total_sec_scored / $total_sec_marks) * 100, 2) : 0;
                    ?>
                    <tfoot>
                        <tr style="background-color: #f8fafc; border-top: 2px solid #cbd5e1; font-weight: 800;">
                            <td colspan="2" style="padding: 14px 16px; color: #0f172a; font-size: 14px; text-transform: uppercase;">Total Aggregate</td>
                            <td style="padding: 14px 16px; text-align: center; font-size: 18px; color: #16a34a; font-weight: 800;"><?php echo $total_sec_scored; ?></td>
                            <td style="padding: 14px 16px; text-align: center; font-size: 16px; color: #0f172a; font-weight: 800;"><?php echo $total_sec_marks; ?></td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <span style="background-color: #0f172a; color: #ffffff; padding: 4px 12px; border-radius: 14px; font-size: 13px; font-weight: 800;">
                                    <?php echo number_format($overall_sec_pct, 1); ?>%
                                </span>
                            </td>
                            <td colspan="2" style="padding: 14px 16px; font-size: 12px; color: #64748b; font-weight: 600;">
                                Scored <?php echo $total_sec_scored; ?> marks out of <?php echo $total_sec_marks; ?> total marks across <?php echo count($section_scores); ?> sections
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Detailed Questions -->
        <h4>Question Breakdown</h4>
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 50px;">#</th>
                    <th style="padding: 12px; text-align: left; width: 160px;">Section / Part</th>
                    <th style="padding: 12px; text-align: left;">Question</th>
                    <th style="padding: 12px; text-align: center; width: 110px;">User Answer</th>
                    <th style="padding: 12px; text-align: center; width: 110px;">Correct Answer</th>
                    <th style="padding: 12px; text-align: center; width: 110px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($answers)): foreach($answers as $idx => $ans): 
                    $sec_title = !empty($ans->part_name) ? $ans->part_name : (!empty($ans->q_subject) ? $ans->q_subject : '');
                    if(is_null($ans->selected_option) || $ans->selected_option === '') {
                        $status_color = '#f39c12';
                        $status_text = 'Unanswered';
                        $status_icon = 'ri-question-mark';
                    } else if($ans->is_correct) {
                        $status_color = '#27ae60';
                        $status_text = 'Correct';
                        $status_icon = 'ri-check-line';
                    } else {
                        $status_color = '#e74c3c';
                        $status_text = 'Incorrect';
                        $status_icon = 'ri-close-line';
                    }
                ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px; font-weight: bold; color: #64748b;"><?php echo ($idx + 1); ?></td>
                    <td style="padding: 12px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase;"><?php echo htmlspecialchars($sec_title); ?></td>
                    <td style="padding: 12px; color: #1e293b;"><?php echo strip_tags($ans->question_text ? $ans->question_text : 'Question text unavailable'); ?></td>
                    <td style="padding: 12px; text-align: center; font-weight: bold;"><?php echo $ans->selected_option ? strtoupper(htmlspecialchars($ans->selected_option)) : '<span style="color: #94a3b8; font-weight: normal; font-style: italic;">Unanswered</span>'; ?></td>
                    <td style="padding: 12px; text-align: center; font-weight: bold; color: #27ae60;"><?php echo strtoupper(htmlspecialchars($ans->correct_option)); ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="color: <?php echo $status_color; ?>; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 5px;">
                            <i class="<?php echo $status_icon; ?>"></i> <?php echo $status_text; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="padding: 12px; text-align: center;">No detailed answers available for this test attempt.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>


            </div> <!-- end target tab -->
        </div> <!-- end tab-content -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctxPie = document.getElementById('analysisChart').getContext('2d');
        var analysisChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Correct', 'Incorrect', 'Unanswered'],
                datasets: [{
                    data: [<?php echo $correct; ?>, <?php echo $incorrect; ?>, <?php echo $unanswered; ?>],
                    backgroundColor: [
                        '#27ae60', // Green
                        '#e74c3c', // Red
                        '#f39c12'  // Orange
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    });
</script>

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
