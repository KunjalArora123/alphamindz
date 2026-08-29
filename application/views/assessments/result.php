<div class="container" style="padding: 80px 20px; max-width: 600px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 16px; padding: 40px; text-align: center; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); border: 1px solid #f1f5f9;">
        
        <?php if($attempt->percentage >= 50): ?>
            <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                <i class="ri-checkbox-circle-fill"></i>
            </div>
            <h1 style="color: #16a34a; margin: 0 0 10px 0; font-family: 'Playfair Display', serif;">Assessment Complete!</h1>
            <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 30px;">Great job completing the <?php echo $attempt->subject; ?> assessment.</p>
        <?php else: ?>
            <div style="width: 80px; height: 80px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px;">
                <i class="ri-error-warning-fill"></i>
            </div>
            <h1 style="color: #dc2626; margin: 0 0 10px 0; font-family: 'Playfair Display', serif;">Assessment Complete</h1>
            <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 30px;">You've completed the <?php echo $attempt->subject; ?> assessment.</p>
        <?php endif; ?>

        <div style="background: #f8fafc; padding: 30px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            <div style="font-size: 4rem; font-weight: 800; color: #0f172a; line-height: 1;">
                <?php echo $attempt->score; ?><span style="font-size: 1.5rem; color: #94a3b8;">/<?php echo $attempt->total_questions; ?></span>
            </div>
            <div style="color: #64748b; margin-top: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Total Score</div>
            
            <div style="margin-top: 25px; padding-top: 25px; border-top: 1px dashed #cbd5e1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="color: #475569; font-weight: 500;">Percentage</span>
                    <span style="font-weight: 700; color: #0f172a; font-size: 1.1rem;"><?php echo $attempt->percentage; ?>%</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #475569; font-weight: 500;">Date Taken</span>
                    <span style="font-weight: 600; color: #0f172a;"><?php echo date('M d, Y', strtotime($attempt->completed_at)); ?></span>
                </div>
            </div>
        </div>

        <a href="<?php echo site_url('assessments'); ?>" class="btn-primary" style="display: inline-block; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600;">Back to Assessments</a>
    </div>
</div>
