<!-- PDF.js library for HTML5 Canvas View-Only PDF Rendering -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<div class="student-course-view py-3 py-md-4">
    <div class="container">
        
        <!-- Navigation Header -->
        <div class="mb-3">
            <a href="<?php echo site_url('student'); ?>" class="btn btn-sm btn-sp-outline">
                <i class="ri-arrow-left-line me-1"></i> Back to Dashboard
            </a>
        </div>

        <!-- Course Header Banner -->
        <div class="card-sp mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, rgb(48, 98, 135) 0%, rgb(34, 72, 100) 100%);">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <?php 
                $tk = strtoupper($tier_key);
                ?>
                <span class="badge px-3 py-2" style="background-color: var(--sp-green); color: #fff; font-size: 0.8rem; font-weight: 700;">
                    <?php echo $tk; ?> TIER ENROLLED
                </span>
                <?php if(!empty($course->duration)): ?>
                    <span class="badge bg-white text-dark"><i class="ri-time-line me-1"></i> <?php echo htmlspecialchars($course->duration); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="fw-bold fs-2 fs-md-1 mb-2"><?php echo htmlspecialchars($course->title); ?></h1>
            <?php if(!empty($course->introduction)): ?>
                <div class="p-3 rounded-3 mt-3" style="background: rgba(255, 255, 255, 0.12); border-left: 4px solid var(--sp-green);">
                    <h6 class="fw-bold mb-1 text-white"><i class="ri-book-open-line me-1"></i> Introduction</h6>
                    <div class="small text-white-50"><?php echo $course->introduction; ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Additive Course Modules Section (Mobile First) -->
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold mb-3 text-sp-blue"><i class="ri-stack-fill text-sp-green me-1"></i> Your Additive Tier Content Modules</h4>
                
                <?php if(!empty($modules)): ?>
                    <div class="d-flex flex-column gap-3 gap-md-4">
                        <?php foreach($modules as $m): ?>
                            <?php 
                            $mt_name = strtoupper($m->module_tier);
                            $is_basic_pdf = ($m->module_tier === 'basic' || $m->content_type === 'pdf');
                            ?>
                            <div class="card-sp bg-white border">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                    <h5 class="fw-bold mb-0 text-dark fs-6 fs-md-5">
                                        <span class="badge bg-sp-blue text-white me-2"><?php echo $mt_name; ?> MODULE</span>
                                        <?php echo htmlspecialchars($m->title); ?>
                                    </h5>
                                    <span class="badge bg-light text-dark border"><?php echo strtoupper($m->content_type); ?></span>
                                </div>

                                <p class="small text-muted mb-3"><?php echo htmlspecialchars($m->description); ?></p>

                                <?php if ($is_basic_pdf): ?>
                                    <!-- BASIC TIER: View-Only Canvas PDF Delivery (Strict Protection) -->
                                    <div class="pdf-secure-viewer-container p-3 border rounded-3 position-relative" style="background: #0f172a; color: #fff; user-select: none; -webkit-user-select: none;" oncontextmenu="return false;">
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                                            <span class="small text-warning fw-semibold"><i class="ri-shield-keyhole-fill me-1"></i> Strictly Protected View-Only Canvas</span>
                                            <span class="badge bg-danger"><i class="ri-lock-2-line me-1"></i> Downloads Restricted</span>
                                        </div>
                                        
                                        <!-- Embedded HTML5 Canvas Rendered Pages -->
                                        <?php if (!empty($m->file_path) && file_exists(FCPATH . $m->file_path)): ?>
                                            <div id="canvas_container_<?php echo $m->id; ?>" style="background: #1e293b; max-height: 750px; overflow-y: auto; border-radius: 6px; padding: 15px; text-align: center;">
                                                <div id="pdf_pages_<?php echo $m->id; ?>">
                                                    <div class="text-white-50 p-3 small"><i class="ri-loader-4-line spin me-1"></i> Rendering protected pages...</div>
                                                </div>
                                            </div>

                                            <script>
                                            (function() {
                                                const url = '<?php echo base_url($m->file_path); ?>';
                                                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                                                const container = document.getElementById('pdf_pages_<?php echo $m->id; ?>');

                                                pdfjsLib.getDocument(url).promise.then(pdf => {
                                                    container.innerHTML = ''; // clear loader
                                                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                                                        pdf.getPage(pageNum).then(page => {
                                                            const viewport = page.getViewport({ scale: 1.3 });
                                                            const canvas = document.createElement('canvas');
                                                            canvas.style.display = 'block';
                                                            canvas.style.margin = '0 auto 15px auto';
                                                            canvas.style.boxShadow = '0 4px 15px rgba(0,0,0,0.4)';
                                                            canvas.style.borderRadius = '4px';
                                                            canvas.style.maxWidth = '100%';

                                                            const context = canvas.getContext('2d');
                                                            canvas.height = viewport.height;
                                                            canvas.width = viewport.width;

                                                            const renderContext = {
                                                                canvasContext: context,
                                                                viewport: viewport
                                                            };
                                                            page.render(renderContext);
                                                            container.appendChild(canvas);
                                                        });
                                                    }
                                                }).catch(err => {
                                                    console.error('Error rendering PDF:', err);
                                                    container.innerHTML = '<div class="text-white p-3 small">Failed to load protected PDF document.</div>';
                                                });
                                            })();
                                            </script>
                                        <?php else: ?>
                                            <div id="pdf-canvas-container" style="background: #ffffff; color: #1e293b; padding: 24px; border-radius: 8px; box-shadow: inset 0 0 10px rgba(0,0,0,0.1); min-height: 180px; text-align: center;">
                                                <i class="ri-file-pdf-2-fill text-danger" style="font-size: 48px;"></i>
                                                <h6 class="fw-bold mt-2 text-sp-blue"><?php echo htmlspecialchars($m->title); ?></h6>
                                                <p class="small text-muted mb-0">Protected reading mode active. Content will be uploaded by admin.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <!-- STANDARD / ADVANCED TIER: Streamed Content Player -->
                                    <div class="p-4 border rounded-3 text-white text-center" style="background: #0f172a;">
                                        <div class="py-3">
                                            <i class="ri-play-circle-fill text-sp-green" style="font-size: 56px;"></i>
                                            <h6 class="fw-bold mt-2 text-white"><?php echo htmlspecialchars($m->title); ?></h6>
                                            <p class="small text-white-50 mb-0">Session-Authenticated Streaming URL Active</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="card-sp text-center py-5">
                        <i class="ri-folder-info-line text-muted display-4 mb-2 d-block"></i>
                        <h5 class="fw-bold mb-1">No Modules Uploaded Yet</h5>
                        <p class="small text-muted mb-0">Modules for this tier are currently being prepared by the admin.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Protection Scripts for View-Only Canvas -->
<script>
document.addEventListener('contextmenu', function(e) {
    if (e.target.closest('.pdf-secure-viewer-container') || e.target.tagName === 'CANVAS') {
        e.preventDefault();
        alert('Protection Active: Right-clicking and saving are disabled for course materials.');
        return false;
    }
});

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S' || e.key === 'p' || e.key === 'P' || e.key === 'u' || e.key === 'U')) {
        e.preventDefault();
        alert('Protection Active: Saving and printing are disabled for protected course materials.');
        return false;
    }
});
</script>
