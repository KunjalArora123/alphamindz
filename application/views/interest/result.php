<main class="flex-grow max-w-4xl w-full mx-auto p-4 md:p-8">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden text-center p-10 mt-10">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-slate-800 mb-4">Assessment Completed Successfully!</h2>
        <p class="text-lg text-slate-600 mb-8">Thank you for completing the <strong>Interest Inventory Test</strong>. Your responses have been successfully recorded and will be reviewed by your counselor.</p>
        
        <?php
        $target_test = $this->session->userdata('target_test');
        if ($target_test) {
            $next_url = site_url('assessments/take_test?test=' . urlencode($target_test));
            $btn_text = 'Proceed to Next Step &rarr;';
        } else {
            $next_url = site_url('assessments');
            $btn_text = 'Return to Dashboard';
        }
        ?>
        <a href="<?php echo $next_url; ?>" class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all"><?php echo $btn_text; ?></a>
    </div>
</main>
