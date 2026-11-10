<div class="container py-5 my-md-5" style="max-width: 700px; margin: 0 auto; min-height: 60vh; display: flex; align-items: center; justify-content: center;">
    <div class="card-sp bg-white text-center p-4 p-md-5 border shadow-sm w-100" style="border-radius: 20px;">
        <div style="width: 80px; height: 80px; background-color: var(--sp-green-light); color: var(--sp-green-dark); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 24px;">
            <i class="ri-checkbox-circle-fill"></i>
        </div>
        
        <h1 class="fw-bold mb-3" style="color: var(--sp-blue); font-size: 2.2rem; font-family: 'Plus Jakarta Sans', sans-serif;">Thank You!</h1>
        
        <p class="text-muted fs-5 mb-4" style="line-height: 1.6;">
            Thank you for completing the <strong>MBTI Personality Profiling Test</strong>. Your responses have been successfully recorded and will be evaluated by your counselor.
        </p>

        <?php
        $target_test = $this->session->userdata('target_test');
        if ($target_test) {
            $next_url = site_url('assessments/take_test?test=' . urlencode($target_test));
            $btn_text = 'Proceed to Next Step &rarr;';
        } else {
            $next_url = site_url('student/assessment');
            $btn_text = 'Return to Assessments';
        }
        ?>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="<?php echo $next_url; ?>" class="btn btn-sp-accent text-white px-4 py-2 fw-semibold">
                <?php echo $btn_text; ?>
            </a>
            <a href="<?php echo site_url('student'); ?>" class="btn btn-sp-outline px-4 py-2 fw-semibold">
                <i class="ri-dashboard-line me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>
