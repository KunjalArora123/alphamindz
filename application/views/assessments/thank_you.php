<div class="container py-5 my-md-5" style="max-width: 700px; margin: 0 auto; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="card-sp bg-white text-center p-4 p-md-5 border shadow-sm w-100" style="border-radius: 20px;">
        <div style="width: 80px; height: 80px; background-color: var(--sp-green-light); color: var(--sp-green-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 24px;">
            <i class="ri-checkbox-circle-fill"></i>
        </div>
        
        <h1 class="fw-bold mb-3" style="color: var(--sp-blue); font-size: 2.2rem; font-family: 'Plus Jakarta Sans', sans-serif;">Thank You!</h1>
        
        <p class="text-muted fs-5 mb-4" style="line-height: 1.6;">
            Your responses for <strong><?php echo htmlspecialchars($attempt->subject); ?></strong> have been submitted successfully.
        </p>

        <div class="p-3 bg-light rounded-3 mb-4 text-secondary small">
            <i class="ri-information-line me-1 text-primary"></i> Your test attempt has been recorded. Your counselor/administrator will evaluate your assessment and share further guidance with you.
        </div>
        
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="<?php echo site_url('student'); ?>" class="btn btn-sp-primary px-4 py-2 fw-semibold">
                <i class="ri-dashboard-line me-1"></i> Go to Dashboard
            </a>
            <a href="<?php echo site_url('student/assessment'); ?>" class="btn btn-sp-outline px-4 py-2 fw-semibold">
                <i class="ri-task-line me-1"></i> My Assessments
            </a>
        </div>
    </div>
</div>
