<div class="container" style="padding: 80px 20px; max-width: 800px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 16px; padding: 40px; text-align: center; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); border: 1px solid #f1f5f9;">
        
        <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
            <i class="ri-checkbox-circle-fill"></i>
        </div>
        <h1 style="color: #16a34a; margin: 0 0 10px 0; font-family: 'Playfair Display', serif;">Assessment Complete!</h1>
        <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 30px;">Based on your responses, this is your Myers-Briggs personality type.</p>

        <div style="background: #f8fafc; padding: 30px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            <?php if (!empty($type)): ?>
            <div style="font-size: 4rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: 2px;">
                <?php echo $type; ?>
            </div>
            <div style="color: #64748b; margin-top: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Your Personality Type</div>
            
            <div style="margin-top: 25px; padding-top: 25px; border-top: 1px dashed #cbd5e1;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; text-align: left;">
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
                        
                        $score1 = $scores[$t1] ?? 0;
                        $max1 = $max_scores[$t1] ?? 1;
                        $pct1 = ($max1 > 0) ? round(($score1 / $max1) * 100) : 0;
                        
                        $score2 = $scores[$t2] ?? 0;
                        $max2 = $max_scores[$t2] ?? 1;
                        $pct2 = ($max2 > 0) ? round(($score2 / $max2) * 100) : 0;
                    ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-weight: 600; font-size: 13px;">
                            <span style="color: <?php echo ($pct1 >= $pct2) ? '#2563eb' : '#64748b'; ?>"><?php echo $name1; ?></span>
                            <span style="color: <?php echo ($pct2 >= $pct1) ? '#2563eb' : '#64748b'; ?>"><?php echo $name2; ?></span>
                        </div>
                        <div style="height: 6px; background-color: #e2e8f0; border-radius: 3px; overflow: hidden; display: flex;">
                            <div style="width: <?php echo $pct1; ?>%; background-color: <?php echo ($pct1 >= $pct2) ? '#2563eb' : '#cbd5e1'; ?>;"></div>
                            <div style="width: <?php echo $pct2; ?>%; background-color: <?php echo ($pct2 >= $pct1) ? '#2563eb' : '#cbd5e1'; ?>;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
                <div style="color: #64748b;">Personality type results are currently unavailable.</div>
            <?php endif; ?>
        </div>

        <?php
        $target_test = $this->session->userdata('target_test');
        if ($target_test) {
            $next_url = site_url('assessments/take_test?test=' . urlencode($target_test));
            $btn_text = 'Proceed to Final Step &rarr;';
        } else {
            $next_url = site_url('assessments');
            $btn_text = 'Back to Assessments';
        }
        ?>
        <a href="<?php echo $next_url; ?>" class="btn-primary" style="display: inline-block; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; background: #2563eb; color: #fff;"><?php echo $btn_text; ?></a>
    </div>
</div>
