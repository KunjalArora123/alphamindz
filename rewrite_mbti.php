<?php
$file = 'd:\xampp\htdocs\AlphaMindz\application\views\admin\test_analysis.php';
$content = file_get_contents($file);

$start_str = '<h4 style="color: #4f46e5; margin-bottom: 15px;">MBTI Answers</h4>';
$end_str = '            </div>
            <?php endif; ?>
            
            <div class="tab-pane fade show active" id="target" role="tabpanel" aria-labelledby="target-tab" style="padding: 20px; border: 1px solid #dee2e6; border-top: none;">';

$start = strpos($content, $start_str);
$end = strpos($content, $end_str);

if ($start !== false && $end !== false) {
    $top = substr($content, 0, $start);
    $bottom = substr($content, $end);
    
    $new_table = '
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
                            $traits = [\'E\', \'I\', \'S\', \'N\', \'T\', \'F\', \'J\', \'P\'];
                            $totals = array_fill_keys($traits, 0);
                            $counts = array_fill_keys($traits, 0);
                            
                            foreach($mbti_answers as $ans): 
                                $is_ei = ($ans->pair == \'E/I\');
                                $is_sn = ($ans->pair == \'S/N\');
                                $is_tf = ($ans->pair == \'T/F\');
                                $is_jp = ($ans->pair == \'J/P\');
                                
                                $val_a = ($ans->selected_option == \'a\') ? 1 : 0;
                                $val_b = ($ans->selected_option == \'b\') ? 1 : 0;
                                
                                if ($is_ei) { $counts[\'E\']++; $counts[\'I\']++; $totals[\'E\'] += $val_a; $totals[\'I\'] += $val_b; }
                                if ($is_sn) { $counts[\'S\']++; $counts[\'N\']++; $totals[\'S\'] += $val_a; $totals[\'N\'] += $val_b; }
                                if ($is_tf) { $counts[\'T\']++; $counts[\'F\']++; $totals[\'T\'] += $val_a; $totals[\'F\'] += $val_b; }
                                if ($is_jp) { $counts[\'J\']++; $counts[\'P\']++; $totals[\'J\'] += $val_a; $totals[\'P\'] += $val_b; }
                            ?>
                            <tr>
                                <td style="padding: 5px; font-weight: bold; border: 1px solid #333;"><?php echo $ans->question_number; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_ei ? \'#E6F7FF\' : \'#FFF200\'; ?>;"><?php echo $is_ei ? ($val_a) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_ei ? \'#E6F7FF\' : \'#FFF200\'; ?>;"><?php echo $is_ei ? ($val_b) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_sn ? \'#E6F7FF\' : \'#99CCFF\'; ?>;"><?php echo $is_sn ? ($val_a) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_sn ? \'#E6F7FF\' : \'#99CCFF\'; ?>;"><?php echo $is_sn ? ($val_b) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_tf ? \'#E6F7FF\' : \'#FF99CC\'; ?>;"><?php echo $is_tf ? ($val_a) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_tf ? \'#E6F7FF\' : \'#FF99CC\'; ?>;"><?php echo $is_tf ? ($val_b) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_jp ? \'#E6F7FF\' : \'#00FF00\'; ?>;"><?php echo $is_jp ? ($val_a) : \'\'; ?></td>
                                <td style="border: 1px solid #333; background: <?php echo $is_jp ? \'#E6F7FF\' : \'#00FF00\'; ?>;"><?php echo $is_jp ? ($val_b) : \'\'; ?></td>
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
                                <td><?php echo $counts[$t] > 0 ? round(($totals[$t] / $counts[$t]) * 100) . \'%\' : \'0%\'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
';
    
    file_put_contents($file, $top . $new_table . $bottom);
    echo "Done";
} else {
    echo "Could not find boundaries";
}
?>
