<div class="student-dashboard" style="min-height: 80vh; padding: 40px 0;">
    <div class="container">
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="card-neu p-3 mb-4 d-flex justify-content-between align-items-center" style="background-color: var(--mint);">
                <span class="fw-bold d-flex align-items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <?php echo $this->session->flashdata('success'); ?>
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="card-neu p-3 mb-4 d-flex justify-content-between align-items-center" style="background-color: var(--coral);">
                <span class="fw-bold d-flex align-items-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo $this->session->flashdata('error'); ?>
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Banner -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="hero-neu p-4 p-md-5 d-flex flex-column align-items-start position-relative overflow-hidden">
                    <h2 class="display-font fs-1 mb-2">
                        Greetings, <?php echo htmlspecialchars($first_name); ?>! 👋
                    </h2>
                    <p class="fs-5 fw-bold mb-0">Welcome to your student panel. Here you can track your progress and access your assessments.</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="row mb-5 gy-4">
            <div class="col-12 col-md-4">
                <div class="card-neu p-4 h-100 d-flex flex-column align-items-start">
                    <div class="icon-tile-neu mb-3" style="width: 60px; height: 60px; background-color: var(--yellow);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h5 class="display-font fs-4 mb-2">Assessments</h5>
                    <p class="fw-medium mb-4 flex-grow-1">Ready to test your skills? Take a new assessment now.</p>
                    <a href="<?php echo site_url('assessments'); ?>" class="btn-neu btn-neu-pink px-4 py-2 w-100 text-center">Start Assessment</a>
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="card-neu p-4 h-100 d-flex flex-column align-items-start">
                    <div class="icon-tile-neu mb-3" style="width: 60px; height: 60px; background-color: var(--mint);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <h5 class="display-font fs-4 mb-2">My Courses</h5>
                    <p class="fw-medium mb-4 flex-grow-1">Access your enrolled courses and learning materials.</p>
                    <a href="#" class="btn-neu btn-neu-yellow px-4 py-2 w-100 text-center">View Courses</a>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card-neu p-4 h-100 d-flex flex-column align-items-start">
                    <div class="icon-tile-neu mb-3" style="width: 60px; height: 60px; background-color: var(--lilac);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="7"></circle>
                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                        </svg>
                    </div>
                    <h5 class="display-font fs-4 mb-2">Performance</h5>
                    <p class="fw-medium mb-4 flex-grow-1">Check your overall progress and scores.</p>
                    <a href="#recent-attempts" class="btn-neu btn-neu-mint px-4 py-2 w-100 text-center">View Stats</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Assessments -->
        <div class="row" id="recent-attempts">
            <div class="col-12">
                <div class="card-neu">
                    <div class="p-4 border-bottom" style="border-bottom: 2px solid var(--ink) !important;">
                        <h4 class="display-font mb-0">Recent Assessments</h4>
                    </div>
                    <div class="p-0">
                        <?php if(!empty($recent_attempts)): ?>
                            <div class="table-responsive m-0">
                                <table class="table mb-0 align-middle">
                                    <thead>
                                        <tr style="border-bottom: 2px solid var(--ink);">
                                            <th class="border-0 px-4 py-3 fw-bold bg-transparent">Subject</th>
                                            <th class="border-0 px-4 py-3 fw-bold bg-transparent">Date</th>
                                            <th class="border-0 px-4 py-3 fw-bold bg-transparent">Score</th>
                                            <th class="border-0 px-4 py-3 fw-bold bg-transparent">Percentage</th>
                                            <th class="border-0 px-4 py-3 fw-bold bg-transparent text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recent_attempts as $attempt): ?>
                                            <tr style="border-bottom: 2px solid var(--ink);">
                                                <td class="px-4 py-4 fw-bold text-dark border-0"><?php echo htmlspecialchars($attempt->subject); ?></td>
                                                <td class="px-4 py-4 border-0 mono-font fw-bold text-dark"><?php echo date('M d, Y', strtotime($attempt->completed_at)); ?></td>
                                                <td class="px-4 py-4 border-0 mono-font fw-bold fs-5"><?php echo $attempt->score; ?>/<?php echo $attempt->total_questions; ?></td>
                                                <td class="px-4 py-4 border-0">
                                                    <?php 
                                                        $is_passing = $attempt->percentage >= 50;
                                                        $fill_color = $is_passing ? 'var(--mint)' : 'var(--coral)';
                                                    ?>
                                                    <span class="pill-neu px-3 py-1 mono-font d-inline-block" style="background-color: <?php echo $fill_color; ?>;">
                                                        <?php echo $attempt->percentage; ?>%
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 border-0 text-end">
                                                    <a href="<?php echo site_url('assessments/result/'.$attempt->id); ?>" class="btn-neu btn-neu-lilac px-3 py-1 text-decoration-none d-inline-block" style="box-shadow: 2px 2px 0 var(--ink);">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5 px-4" style="background-color: var(--yellow); border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                                <div class="icon-tile-neu mb-3 bg-white" style="width: 72px; height: 72px;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                    </svg>
                                </div>
                                <h5 class="display-font fs-3 text-dark mb-3">No recent activity</h5>
                                <p class="fw-bold text-dark mb-0 fs-5">You haven't taken any assessments yet. Click the pink button above to start your first one!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
