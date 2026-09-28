<div class="page-header" style="background: #f8f9fa; padding: 40px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: #2c3e50;">Assessments Portal</h1>
        <p style="color: #6c757d;">Test your skills and track your progress across multiple disciplines.</p>
    </div>
</div>

<div class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="ri-error-warning-line me-2"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 style="font-size: 1.8rem; margin-bottom: 25px; color: var(--sp-blue); font-weight: 800;">Available Assessments</h2>
    
    <?php 
    $tests_dict = array();
    if (!empty($active_assessments)) {
        foreach ($active_assessments as $aa) {
            $tests_dict[$aa->title] = array(
                'desc' => strip_tags($aa->description ? $aa->description : 'Assessment test evaluating core skills and competencies.'),
                'time_limit' => ($aa->time_limit > 0 ? (int)$aa->time_limit : 45)
            );
        }
    } else {
        $default_tests = array(
            'Class 10 Assessment' => 'Comprehensive assessment for Class 10 students covering General Science, Mechanical, Numerical, Reasoning, Spatial & Verbal abilities.'
        );
        foreach ($default_tests as $dt_title => $dt_desc) {
            $tests_dict[$dt_title] = array('desc' => $dt_desc, 'time_limit' => 45);
        }
    }

    // Filter tests to ONLY show tests granted to this student (enabled or completed)
    $granted_tests = array();
    foreach ($tests_dict as $title => $test_data) {
        $p_status = isset($permissions_map[$title]) ? strtolower($permissions_map[$title]) : 'disabled';
        if ($p_status === 'enabled' || $p_status === 'completed') {
            $granted_tests[$title] = array(
                'desc' => $test_data['desc'],
                'time_limit' => $test_data['time_limit'],
                'status' => $p_status
            );
        }
    }
    ?>

    <?php if(!empty($granted_tests)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <?php foreach ($granted_tests as $title => $info): ?>
                <div class="card-sp bg-white border d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div style="font-size: 1.8rem; color: var(--sp-blue);"><i class="ri-survey-line"></i></div>
                            <?php if($info['status'] === 'enabled'): ?>
                                <span class="pill-sp-green"><i class="ri-checkbox-circle-fill me-1"></i> ACCESS GRANTED</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark px-2 py-1"><i class="ri-history-line me-1"></i> COMPLETED</span>
                            <?php endif; ?>
                        </div>

                        <h4 style="margin: 0 0 10px 0; font-size: 1.15rem; color: var(--sp-blue); font-weight: 800;"><?php echo htmlspecialchars($title); ?></h4>
                        <p style="color: var(--sp-text-muted); font-size: 0.85rem; margin-bottom: 15px; min-height: 40px;"><?php echo htmlspecialchars($info['desc']); ?></p>
                        
                        <p style="color: #dc3545; font-size: 0.85rem; font-weight: bold; margin-bottom: 20px;"><i class="ri-timer-line me-1"></i> Time Limit: <?php echo (int)$info['time_limit']; ?> Minutes</p>
                    </div>

                    <div>
                        <?php if($info['status'] === 'enabled'): ?>
                            <a href="<?php echo site_url('assessments/take_test?test='.urlencode($title)); ?>" class="btn btn-sp-accent text-white w-100">
                                Start Assessment Test <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        <?php else: ?>
                            <div class="p-2 bg-light border rounded-3 mb-2 text-start">
                                <span class="small text-muted fw-semibold"><i class="ri-information-line me-1"></i> Test Completed. Re-authorization required for retakes.</span>
                            </div>
                            <button class="btn btn-secondary w-100" disabled style="cursor: not-allowed; opacity: 0.7; font-size: 0.85rem;">
                                <i class="ri-checkbox-circle-line me-1"></i> Test Completed
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card-sp text-center py-5 bg-white mb-4">
            <i class="ri-shield-keyhole-line text-muted display-3 mb-3 d-block"></i>
            <h4 class="fw-bold mb-2 text-sp-blue">No Assessment Tests Access Granted Yet</h4>
            <p class="text-muted small mb-3" style="max-width: 500px; margin: 0 auto;">Your administrator has not granted you access to any assessment tests yet. Once the admin enables a test for your account, it will appear right here.</p>
        </div>
    <?php endif; ?>

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
