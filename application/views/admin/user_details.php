<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Student Details: <?php echo $user->first_name . ' ' . $user->last_name; ?></h3>
        <a href="<?php echo site_url('admin/users'); ?>" class="btn btn-secondary" style="padding: 5px 10px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Back to Users</a>
    </div>
    <div class="card-body">
        
        <div style="margin-bottom: 20px;">
            <h4>Basic Information</h4>
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th style="padding: 10px; text-align: left; width: 200px; border-bottom: 1px solid #dee2e6;">Name</th>
                    <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                </tr>
                <tr>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Email</th>
                    <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo $user->email; ?></td>
                </tr>
                <tr>
                    <th style="padding: 10px; text-align: left; border-bottom: 1px solid #dee2e6;">Student Since</th>
                    <td style="padding: 10px; border-bottom: 1px solid #dee2e6;"><?php echo date('F d, Y', strtotime($user->created_at)); ?></td>
                </tr>
            </table>
        </div>

        <div style="margin-bottom: 30px;">
            <h4>Performance Metrics</h4>
            <?php if(!empty($recent_tests)): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <!-- Line Chart -->
                    <div style="flex: 1 1 500px; max-width: 100%; height: 350px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                        <h5 style="text-align: center; margin-bottom: 10px; color: #444;">Score Trend</h5>
                        <canvas id="performanceChart"></canvas>
                    </div>
                    <!-- Pie Chart -->
                    <div style="flex: 1 1 300px; max-width: 100%; height: 350px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                        <h5 style="text-align: center; margin-bottom: 10px; color: #444;">Overall Accuracy</h5>
                        <canvas id="accuracyChart"></canvas>
                    </div>
                </div>
            <?php else: ?>
                <p>No tests taken yet to show performance.</p>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 30px;">
            <h4>Recent Tests Taken</h4>
            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left;">Subject</th>
                        <th style="padding: 12px; text-align: left;">Score</th>
                        <th style="padding: 12px; text-align: left;">Percentage</th>
                        <th style="padding: 12px; text-align: left;">Date Taken</th>
                        <th style="padding: 12px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_tests)): foreach($recent_tests as $test): ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($test->subject); ?></td>
                        <td style="padding: 12px;"><?php echo $test->score; ?> / <?php echo $test->total_questions; ?></td>
                        <td style="padding: 12px;"><?php echo $test->percentage; ?>%</td>
                        <td style="padding: 12px;"><?php echo date('M d, Y h:i A', strtotime($test->completed_at)); ?></td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="<?php echo site_url('admin/test_analysis/'.$test->id); ?>" style="color: #3498db; text-decoration: none;"><i class="ri-bar-chart-2-line"></i> Analysis</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 12px; text-align: center;">No recent tests found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-bottom: 30px;">
            <h4>Courses Enrolled In</h4>
            <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left;">Course Title</th>
                        <th style="padding: 12px; text-align: left;">Tier</th>
                        <th style="padding: 12px; text-align: left;">Price</th>
                        <th style="padding: 12px; text-align: left;">Enrolled Date</th>
                        <th style="padding: 12px; text-align: right;">Certificate Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($enrolled_courses)): foreach($enrolled_courses as $course): 
                        $cert = isset($certificates[$course->id]) ? $certificates[$course->id] : null;
                        $is_granted = ($cert && $cert->status === 'granted');
                    ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($course->title); ?></td>
                        <td style="padding: 12px;"><span style="text-transform: capitalize; font-weight: 600; padding: 2px 8px; background: #eef2f6; border-radius: 4px; font-size: 13px; color: #306287;"><?php echo !empty($course->tier_key) ? htmlspecialchars($course->tier_key) : 'Basic'; ?></span></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($course->price); ?></td>
                        <td style="padding: 12px;"><?php echo date('M d, Y', strtotime($course->enrolled_at)); ?></td>
                        <td style="padding: 12px; text-align: right;">
                            <?php if($is_granted): ?>
                                <span style="color: #28a745; font-weight: 600; margin-right: 8px; font-size: 13px;"><i class="ri-checkbox-circle-fill"></i> Granted (<?php echo $cert->credential_id; ?>)</span>
                                <a href="<?php echo site_url('admin/view_certificate/' . urlencode($cert->credential_id)); ?>" target="_blank" class="btn btn-sm" style="background-color: #3b82f6; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; margin-right: 6px;"><i class="ri-eye-line"></i> View</a>
                                <a href="<?php echo site_url('admin/revoke_certificate/'.$user->id.'/'.$course->id); ?>" class="btn btn-sm" style="background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 12px;" onclick="return confirm('Revoke certificate for this course?');">Revoke</a>
                            <?php else: ?>
                                <span style="color: #6c757d; font-weight: 500; margin-right: 8px; font-size: 13px;"><i class="ri-close-circle-line"></i> Not Granted</span>
                                <a href="<?php echo site_url('admin/grant_certificate/'.$user->id.'/'.$course->id); ?>" class="btn btn-sm" style="background-color: #83bb4d; color: white; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Grant Certificate Access</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" style="padding: 12px; text-align: center;">Not enrolled in any courses yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php 
if(!empty($recent_tests)): 
    $total_correct = 0;
    $total_questions = 0;
    foreach($recent_tests as $test) {
        $total_correct += $test->score;
        $total_questions += $test->total_questions;
    }
    $total_incorrect = $total_questions - $total_correct;
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart
        var ctx = document.getElementById('performanceChart').getContext('2d');
        
        var labels = [];
        var dataPoints = [];
        var pointColors = [];
        var palette = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
        
        <?php 
        $chart_data = array_reverse($recent_tests);
        foreach($chart_data as $i => $test): 
        ?>
            labels.push('<?php echo addslashes($test->subject) . " (" . date('M d', strtotime($test->completed_at)) . ")"; ?>');
            dataPoints.push(<?php echo $test->percentage; ?>);
            pointColors.push(palette[<?php echo $i; ?> % palette.length]);
        <?php endforeach; ?>

        var gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(153, 102, 255, 0.5)');
        gradient.addColorStop(1, 'rgba(153, 102, 255, 0.05)');

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Score Percentage',
                    data: dataPoints,
                    backgroundColor: gradient,
                    borderColor: '#9966FF',
                    borderWidth: 3,
                    pointBackgroundColor: pointColors,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: { display: true, text: 'Percentage (%)' }
                    },
                    x: {
                        title: { display: true, text: 'Tests Taken' },
                        ticks: { display: false } // hide x-axis text if it gets too long, or leave it. Let's leave it.
                    }
                },
                plugins: {
                    legend: { display: false } // Hide legend for line chart to save space
                }
            }
        });

        // Pie Chart
        var ctxPie = document.getElementById('accuracyChart').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'doughnut', // doughnut looks more modern and colorful
            data: {
                labels: ['Correct Answers', 'Incorrect Answers'],
                datasets: [{
                    data: [<?php echo $total_correct; ?>, <?php echo $total_incorrect; ?>],
                    backgroundColor: [
                        '#4BC0C0', // Vibrant Teal
                        '#FF6384'  // Vibrant Pink/Red
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
<?php endif; ?>
