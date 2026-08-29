<div class="page-header" style="background: #f8f9fa; padding: 40px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: #2c3e50;">Assessments Portal</h1>
        <p style="color: #6c757d;">Test your skills and track your progress across multiple disciplines.</p>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 1000px; margin: 0 auto;">
    <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 25px; color: #2c3e50;">Available Assessments</h2>
    
    <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-bottom: 60px;">
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
            <div style="font-size: 2rem; color: #007bff; margin-bottom: 15px;"><i class="ri-survey-line"></i></div>
            <h3 style="margin: 0 0 10px 0; font-size: 1.25rem; color: #333;">Comprehensive Aptitude Assessment</h3>
            <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 15px;">Test your knowledge across multiple disciplines in this comprehensive 90-question assessment.</p>
            <ul style="color: #6c757d; font-size: 0.9rem; margin-bottom: 25px; padding-left: 20px;">
                <li>General Science Ability (20 questions)</li>
                <li>Mechanical Ability (20 questions)</li>
                <li>Numerical Ability (20 questions)</li>
                <li>Reasoning Ability (20 questions)</li>
                <li>Spatial Ability (10 questions)</li>
            </ul>
            <p style="color: #dc3545; font-size: 0.9rem; font-weight: bold; margin-bottom: 20px;"><i class="ri-timer-line"></i> Time Limit: 45 Minutes</p>
            <a href="<?php echo site_url('assessments/take_test'); ?>" class="btn-primary" style="display: inline-block; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: bold;">Start Assessment</a>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

    <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 25px; color: #2c3e50;">My Past Attempts</h2>
    <?php if(!empty($attempts)): ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left;">Subject</th>
                        <th style="padding: 12px; text-align: left;">Date Taken</th>
                        <th style="padding: 12px; text-align: left;">Score</th>
                        <th style="padding: 12px; text-align: left;">Percentage</th>
                        <th style="padding: 12px; text-align: left;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($attempts as $attempt): ?>
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; font-weight: 500;"><?php echo $attempt->subject; ?></td>
                            <td style="padding: 12px;"><?php echo date('M d, Y H:i', strtotime($attempt->completed_at)); ?></td>
                            <td style="padding: 12px; font-weight: bold;"><?php echo $attempt->score; ?> / <?php echo $attempt->total_questions; ?></td>
                            <td style="padding: 12px;">
                                <span style="color: <?php echo ($attempt->percentage >= 50) ? '#28a745' : '#dc3545'; ?>; font-weight: bold;">
                                    <?php echo $attempt->percentage; ?>%
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <a href="<?php echo site_url('assessments/result/'.$attempt->id); ?>" style="color: #007bff; text-decoration: none;">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="color: #6c757d; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: center;">You have not taken any assessments yet.</p>
    <?php endif; ?>
</div>
