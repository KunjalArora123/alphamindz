<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Detailed Test Analysis</h3>
        <a href="<?php echo site_url('admin/user_details/'.$user->id); ?>" class="btn btn-secondary" style="padding: 5px 10px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Back to User</a>
    </div>
    
    <div class="card-body">
        
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
        ?>

        <!-- Detailed Questions -->
        <h4>Question Breakdown</h4>
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 50px;">#</th>
                    <th style="padding: 12px; text-align: left;">Question</th>
                    <th style="padding: 12px; text-align: center; width: 100px;">User Answer</th>
                    <th style="padding: 12px; text-align: center; width: 100px;">Correct Answer</th>
                    <th style="padding: 12px; text-align: center; width: 100px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($answers)): foreach($answers as $ans): 
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
                    <td style="padding: 12px; font-weight: bold;"><?php echo $ans->question_number ? $ans->question_number : '-'; ?></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($ans->question_text ? $ans->question_text : 'Question text unavailable'); ?></td>
                    <td style="padding: 12px; text-align: center; font-weight: bold;"><?php echo $ans->selected_option ? $ans->selected_option : '-'; ?></td>
                    <td style="padding: 12px; text-align: center; font-weight: bold; color: #27ae60;"><?php echo $ans->correct_option; ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="color: <?php echo $status_color; ?>; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 5px;">
                            <i class="<?php echo $status_icon; ?>"></i> <?php echo $status_text; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">No detailed answers available for this legacy test.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

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
