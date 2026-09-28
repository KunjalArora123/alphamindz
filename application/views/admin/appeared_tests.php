<!-- Completed Assessment Attempts Table -->
<div class="card" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background-color: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;"><i class="ri-history-line" style="color: #0284c7;"></i> Appeared Tests History</h3>
        <span style="background-color: #e2e8f0; color: #334155; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 700;">Total Attempts: <?php echo count($attempts); ?></span>
    </div>
    <div class="card-body" style="padding: 24px;">
        <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table class="table" style="width: 100%; border-collapse: collapse; min-width: 750px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Student Name</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Email</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Subject</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Score</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Percentage</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Date Taken</th>
                        <th style="padding: 12px 16px; text-align: center; font-size: 13px; font-weight: 700; color: #475569;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($attempts)): foreach($attempts as $attempt): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 16px; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($attempt->first_name . ' ' . $attempt->last_name); ?></td>
                        <td style="padding: 12px 16px; color: #64748b; font-size: 14px;"><?php echo htmlspecialchars($attempt->email); ?></td>
                        <td style="padding: 12px 16px; color: #1e293b; font-size: 14px;"><?php echo htmlspecialchars($attempt->subject); ?></td>
                        <td style="padding: 12px 16px; font-weight: 700; color: #0f172a;"><?php echo $attempt->score . ' / ' . $attempt->total_questions; ?></td>
                        <td style="padding: 12px 16px;">
                            <span style="color: <?php echo ($attempt->percentage >= 50) ? '#16a34a' : '#dc2626'; ?>; font-weight: 700;">
                                <?php echo $attempt->percentage; ?>%
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: #64748b; font-size: 13px;"><?php echo date('M d, Y h:i A', strtotime($attempt->completed_at)); ?></td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <a href="<?php echo site_url('admin/test_analysis/' . $attempt->id); ?>" style="background-color: #f0f9ff; color: #0284c7; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; border: 1px solid #bae6fd; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ri-bar-chart-box-line"></i> View Analysis
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #64748b;">No assessments have been taken yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
