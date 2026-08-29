<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Student Assessment Attempts</h3>
        <span class="status-badge status-publish" style="background-color: #e2e3e5; color: #383d41;">Total Records: <?php echo count($attempts); ?></span>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Student Name</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Subject</th>
                    <th style="padding: 12px; text-align: left;">Score</th>
                    <th style="padding: 12px; text-align: left;">Percentage</th>
                    <th style="padding: 12px; text-align: left;">Date Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($attempts)): foreach($attempts as $attempt): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($attempt->first_name . ' ' . $attempt->last_name); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($attempt->email); ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($attempt->subject); ?></td>
                    <td style="padding: 12px; font-weight: bold;"><?php echo $attempt->score . ' / ' . $attempt->total_questions; ?></td>
                    <td style="padding: 12px;">
                        <span style="color: <?php echo ($attempt->percentage >= 50) ? '#28a745' : '#dc3545'; ?>; font-weight: bold;">
                            <?php echo $attempt->percentage; ?>%
                        </span>
                    </td>
                    <td style="padding: 12px;"><?php echo date('M d, Y h:i A', strtotime($attempt->completed_at)); ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="padding: 12px; text-align: center;">No assessments have been taken yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
