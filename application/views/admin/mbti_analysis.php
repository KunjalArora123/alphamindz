<div class="data-card" style="padding: 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0;">
        <div>
            <h2 style="margin: 0 0 8px 0; color: #1e293b; font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="ri-file-excel-2-line" style="color: #10b981;"></i> MBTI Raw Score Matrix
            </h2>
            <div style="display: flex; gap: 16px; color: #475569; font-size: 14px; align-items: center;">
                <span><i class="ri-user-line" style="color: #64748b;"></i> <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></span>
                <span style="color: #cbd5e1;">|</span>
                <span><i class="ri-mail-line" style="color: #64748b;"></i> <?php echo htmlspecialchars($user->email); ?></span>
                <span style="color: #cbd5e1;">|</span>
                <span><i class="ri-calendar-line" style="color: #64748b;"></i> <?php echo date('M d, Y h:i A', strtotime($attempt->completed_at)); ?></span>
            </div>
        </div>
        <a href="<?php echo site_url('admin/appeared_tests'); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-arrow-left-line"></i> Back to History
        </a>
    </div>

    <style>
        .mbti-table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; text-align: center; font-size: 13px; }
        .mbti-table th, .mbti-table td { border: 1px solid #cbd5e1; padding: 6px; }
        .mbti-table th { font-weight: bold; color: #000; }
        .col-ei { background-color: #fef08a; } /* Yellow */
        .col-sn { background-color: #bfdbfe; } /* Blue */
        .col-tf { background-color: #f9a8d4; } /* Pink */
        .col-jp { background-color: #4ade80; } /* Green */
        .active-cell { background-color: #cffafe; border: 2px solid #000; font-weight: bold; }
        .active-cell-empty { background-color: #cffafe; border: 2px solid #000; }
        .row-header { font-weight: bold; background: #fff; }
        .summary-row td { font-weight: bold; border-top: 2px solid #000; }
    </style>

    <?php
    $totals = ['E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0];
    $counts = ['E'=>20, 'I'=>20, 'S'=>22, 'N'=>22, 'T'=>24, 'F'=>24, 'J'=>22, 'P'=>22];
    ?>

    <div style="overflow-x: auto; padding-bottom: 20px;">
        <table class="mbti-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 60px; background: #fff;">S.No</th>
                    <th class="col-ei">E</th><th class="col-ei">I</th>
                    <th class="col-sn">S</th><th class="col-sn">N</th>
                    <th class="col-tf">T</th><th class="col-tf">F</th>
                    <th class="col-jp">J</th><th class="col-jp">P</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($mbti_answers)): foreach($mbti_answers as $ans): ?>
                <tr>
                    <td class="row-header"><?php echo $ans->question_number; ?></td>
                    
                    <?php
                    $pairs = [
                        ['E','I', 'col-ei'],
                        ['S','N', 'col-sn'],
                        ['T','F', 'col-tf'],
                        ['J','P', 'col-jp']
                    ];
                    
                    foreach ($pairs as $p) {
                        $left = $p[0];
                        $right = $p[1];
                        $bg_class = $p[2];
                        
                        if ($ans->pair == $left.'/'.$right) {
                            $val_left = '';
                            $val_right = '';
                            if ($ans->selected_option == 'a') {
                                $val_left = '1';
                                $val_right = '0';
                                $totals[$left]++;
                            } elseif ($ans->selected_option == 'b') {
                                $val_left = '0';
                                $val_right = '1';
                                $totals[$right]++;
                            }
                            echo "<td class='active-cell'>{$val_left}</td>";
                            echo "<td class='active-cell'>{$val_right}</td>";
                        } else {
                            echo "<td class='{$bg_class}'></td>";
                            echo "<td class='{$bg_class}'></td>";
                        }
                    }
                    ?>
                </tr>
                <?php endforeach; endif; ?>
                
                <tr><td colspan="9" style="border:none; height:20px;"></td></tr>
                
                <tr class="summary-row" style="background:#fff;">
                    <td style="text-align: left;">Total</td>
                    <td><?php echo $totals['E']; ?></td><td><?php echo $totals['I']; ?></td>
                    <td><?php echo $totals['S']; ?></td><td><?php echo $totals['N']; ?></td>
                    <td><?php echo $totals['T']; ?></td><td><?php echo $totals['F']; ?></td>
                    <td><?php echo $totals['J']; ?></td><td><?php echo $totals['P']; ?></td>
                </tr>
                <tr style="background:#fff;">
                    <td style="text-align: left; font-weight: bold;">Count</td>
                    <td><?php echo $counts['E']; ?></td><td><?php echo $counts['I']; ?></td>
                    <td><?php echo $counts['S']; ?></td><td><?php echo $counts['N']; ?></td>
                    <td><?php echo $counts['T']; ?></td><td><?php echo $counts['F']; ?></td>
                    <td><?php echo $counts['J']; ?></td><td><?php echo $counts['P']; ?></td>
                </tr>
                <tr style="background:#fff;">
                    <td style="text-align: left; font-weight: bold;">% Score</td>
                    <td><?php echo round(($totals['E']/$counts['E'])*100); ?>%</td>
                    <td><?php echo round(($totals['I']/$counts['I'])*100); ?>%</td>
                    <td><?php echo round(($totals['S']/$counts['S'])*100); ?>%</td>
                    <td><?php echo round(($totals['N']/$counts['N'])*100); ?>%</td>
                    <td><?php echo round(($totals['T']/$counts['T'])*100); ?>%</td>
                    <td><?php echo round(($totals['F']/$counts['F'])*100); ?>%</td>
                    <td><?php echo round(($totals['J']/$counts['J'])*100); ?>%</td>
                    <td><?php echo round(($totals['P']/$counts['P'])*100); ?>%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
