<div class="student-dashboard py-3 py-md-4">
    <div class="container">
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="background-color: var(--sp-green-light); color: var(--sp-green-dark);">
                <i class="ri-checkbox-circle-fill me-2"></i> <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="ri-error-warning-fill me-2"></i> <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Mobile-First Welcome Hero Banner -->
        <div class="card-sp mb-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, rgb(48, 98, 135) 0%, rgb(34, 72, 100) 100%);">
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <span class="badge mb-2 px-3 py-2" style="background-color: var(--sp-green); color: #fff; font-size: 0.8rem; font-weight: 700;">STUDENT DASHBOARD</span>
                    <h1 class="fw-extrabold fs-2 fs-md-1 mb-2">Welcome back, <?php echo htmlspecialchars($first_name); ?>! 👋</h1>
                    <p class="text-white-50 mb-3 mb-md-0">Track your enrolled courses, tier access privileges, and assessment performance in real time.</p>
                </div>
                
            </div>
        </div>

        <!-- Quick Action Cards Grid (Mobile First) -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card-sp h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 rounded-3 me-3" style="background-color: var(--sp-blue-light); color: var(--sp-blue);">
                                <i class="ri-book-read-line fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-sp-blue">My Courses</h5>
                                <span class="small text-muted"><?php echo !empty($enrolled_courses) ? count($enrolled_courses) : 0; ?> Active Enrolled</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-4">Access course modules, view-only PDFs, and video lectures based on your active tier level.</p>
                    </div>
                    <a href="#my-courses" class="btn btn-sp-primary w-100">
                        View Enrolled Courses <i class="ri-arrow-down-line ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card-sp h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 rounded-3 me-3" style="background-color: var(--sp-green-light); color: var(--sp-green-dark);">
                                <i class="ri-task-line fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-sp-green">Assessments</h5>
                                <span class="small text-muted">Online Tests</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-4">Evaluate your knowledge with real-time test attempts and immediate feedback.</p>
                    </div>
                    <a href="<?php echo site_url('student/assessment'); ?>" class="btn btn-sp-accent text-white w-100">
                        Take Assessment <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-lg-4">
                <div class="card-sp h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 rounded-3 me-3" style="background-color: #f1f5f9; color: #475569;">
                                <i class="ri-bar-chart-2-line fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Performance Stats</h5>
                                <span class="small text-muted">Test History</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-4">Review scores, answer keys, and improvement recommendations from completed tests.</p>
                    </div>
                    <a href="#recent-attempts" class="btn btn-sp-outline w-100">
                        View Results <i class="ri-history-line ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses & Tier Access Section -->
        <div class="row mb-4" id="my-courses">
            <div class="col-12">
                <div class="card-sp">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 pb-3 border-bottom">
                        <div>
                            <h4 class="fw-bold mb-1 text-sp-blue"><i class="ri-book-open-fill text-sp-green me-1"></i> Enrolled Courses & Tier Access</h4>
                            <span class="small text-muted">Content unlocked according to your purchased pricing tier</span>
                        </div>
                        
                    </div>

                    <?php if(!empty($enrolled_courses)): ?>
                        <div class="row g-3 g-md-4">
                            <?php foreach($enrolled_courses as $c): ?>
                                <?php 
                                $tk = isset($c->tier_key) ? strtoupper($c->tier_key) : 'BASIC';
                                $pill_class = 'pill-sp-blue';
                                if ($tk === 'STANDARD' || $tk === 'ADVANCED' || $tk === 'PREMIUM') {
                                    $pill_class = 'pill-sp-green';
                                }
                                ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card-sp h-100 d-flex flex-column justify-content-between bg-white border">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="<?php echo $pill_class; ?>">
                                                    <?php echo $tk; ?> TIER
                                                </span>
                                                <?php if(!empty($c->duration)): ?>
                                                    <span class="small text-muted fw-semibold"><i class="ri-time-line"></i> <?php echo htmlspecialchars($c->duration); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <h5 class="fw-bold mb-2 text-dark fs-6 fs-md-5"><?php echo htmlspecialchars($c->title); ?></h5>
                                            <p class="small text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?php echo strip_tags($c->description); ?>
                                            </p>
                                        </div>
                                        <div class="d-flex flex-column gap-2 mt-3">
                                            <a href="<?php echo site_url('student/course/'.$c->id); ?>" class="btn btn-sp-primary w-100">
                                                Access Materials <i class="ri-arrow-right-line ms-1"></i>
                                            </a>
                                            <?php if(!empty($c->certificate)): ?>
                                                <a href="<?php echo site_url('student/certificate/'.$c->certificate->credential_id); ?>" class="btn btn-sp-accent text-white w-100" target="_blank">
                                                    <i class="ri-award-line me-1"></i> Download PDF Certificate
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="ri-book-3-line text-muted display-4 mb-2 d-block"></i>
                            <h5 class="fw-bold mb-1">No Enrolled Courses Found</h5>
                            <p class="small text-muted mb-3">Browse our course matrix to choose from 3 or 4 differential pricing tiers.</p>
                            
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Assessments Table -->
        <div class="row" id="recent-attempts">
            <div class="col-12">
                <div class="card-sp p-0 overflow-hidden">
                    <div class="p-3 p-md-4 bg-light border-bottom">
                        <h5 class="fw-bold mb-0 text-sp-blue"><i class="ri-history-line text-sp-green me-1"></i> Recent Assessment Attempts</h5>
                    </div>
                    
                    <?php if(!empty($recent_attempts)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 px-md-4 py-3 text-muted small fw-bold">SUBJECT</th>
                                        <th class="px-3 px-md-4 py-3 text-muted small fw-bold">DATE</th>
                                        <th class="px-3 px-md-4 py-3 text-muted small fw-bold">SCORE</th>
                                        <th class="px-3 px-md-4 py-3 text-muted small fw-bold">RESULT</th>
                                        <th class="px-3 px-md-4 py-3 text-muted small fw-bold text-end">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_attempts as $attempt): ?>
                                        <tr>
                                            <td class="px-3 px-md-4 py-3 fw-bold text-dark"><?php echo htmlspecialchars($attempt->subject); ?></td>
                                            <td class="px-3 px-md-4 py-3 text-muted small"><?php echo date('M d, Y', strtotime($attempt->completed_at)); ?></td>
                                            <td class="px-3 px-md-4 py-3 fw-semibold"><?php echo $attempt->score; ?> / <?php echo $attempt->total_questions; ?></td>
                                            <td class="px-3 px-md-4 py-3">
                                                <?php if($attempt->percentage >= 50): ?>
                                                    <span class="pill-sp-green"><i class="ri-checkbox-circle-fill me-1"></i> <?php echo $attempt->percentage; ?>% Pass</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger px-2 py-1"><i class="ri-close-circle-fill me-1"></i> <?php echo $attempt->percentage; ?>%</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-3 px-md-4 py-3 text-end">
                                                <a href="<?php echo site_url('assessments/result/'.$attempt->id); ?>" class="btn btn-sm btn-sp-outline">View Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 px-3">
                            <i class="ri-inbox-line text-muted display-4 mb-2 d-block"></i>
                            <p class="fw-bold mb-1">No Assessment Attempts Yet</p>
                            <p class="small text-muted mb-3">Complete tests to view detailed performance metrics here.</p>
                            <a href="<?php echo site_url('assessments'); ?>" class="btn btn-sm btn-sp-accent text-white">Start Your First Test</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>




