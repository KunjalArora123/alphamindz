<style>
.pdf-row-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: all 0.25s ease;
}
.pdf-row-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.pdf-info-wrap {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
    flex: 1;
}
.pdf-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: #fee2e2;
    color: #ef4444;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
}
.pdf-text-content {
    min-width: 0;
    flex: 1;
}
.pdf-text-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 6px 0;
    line-height: 1.4;
    overflow-wrap: break-word;
    word-break: normal;
}
.pdf-meta-info {
    font-size: 0.85rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.pdf-actions-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
@media (max-width: 768px) {
    .pdf-row-card {
        flex-direction: column;
        align-items: stretch;
        padding: 16px;
        gap: 14px;
    }
    .pdf-info-wrap {
        width: 100%;
        align-items: flex-start;
        gap: 14px;
    }
    .pdf-icon-box {
        width: 42px;
        height: 42px;
        font-size: 1.4rem;
    }
    .pdf-text-content h3 {
        font-size: 1.05rem;
    }
    .pdf-actions-wrap {
        width: 100%;
        display: flex;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    .pdf-actions-wrap a {
        flex: 1;
        justify-content: center;
        text-align: center;
        padding: 10px 14px !important;
        font-size: 14px !important;
    }
}
</style>

<div class="page-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 70px 0; border-bottom: 1px solid #334155;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <div style="margin-bottom: 16px;">
            <a href="<?php echo site_url('resources'); ?>" style="color: #38bdf8; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-arrow-left-line"></i> All Free Resources
            </a>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-size: 13px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="<?php echo $category['icon']; ?>"></i> <?php echo htmlspecialchars($category['badge']); ?>
            </span>
            <span style="color: #94a3b8; font-size: 0.9rem;">
                <i class="ri-file-pdf-line" style="color: #f87171;"></i> <?php echo count($pdfs); ?> PDF Downloadable Guide<?php echo count($pdfs) > 1 ? 's' : ''; ?>
            </span>
        </div>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; line-height: 1.25; margin-bottom: 14px; color: #fff;">
            <?php echo htmlspecialchars($category['title']); ?>
        </h1>
        <p style="color: #cbd5e1; font-size: 1.1rem; line-height: 1.6; max-width: 800px; margin: 0;">
            <?php echo htmlspecialchars($category['desc']); ?>
        </p>
    </div>
</div>

<div class="container" style="padding: 60px 20px 100px; max-width: 1000px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1e293b; margin: 0;">Available Worksheets & PDFs</h2>
        <span style="font-size: 0.9rem; color: #64748b;">Free Instant Download • No Login Required</span>
    </div>

    <?php if(!empty($pdfs)): ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach($pdfs as $idx => $pdf): ?>
                <div class="pdf-row-card">
                    <div class="pdf-info-wrap">
                        <div class="pdf-icon-box">
                            <i class="ri-file-pdf-fill"></i>
                        </div>
                        <div class="pdf-text-content">
                            <h3>
                                <?php echo htmlspecialchars($pdf['title']); ?>
                            </h3>
                            <div class="pdf-meta-info">
                                <span><i class="ri-attachment-line"></i> <?php echo htmlspecialchars($pdf['filename']); ?></span>
                                <span>•</span>
                                <span><i class="ri-hard-drive-2-line"></i> <?php echo $pdf['size']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pdf-actions-wrap">
                        <a href="<?php echo $pdf['view_url']; ?>" target="_blank" class="btn-outline" style="padding: 10px 18px; font-size: 14px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #cbd5e1; color: #475569; background: #fff;" title="Preview PDF in new tab">
                            <i class="ri-eye-line"></i> View
                        </a>
                        <a href="<?php echo $pdf['dl_url']; ?>" class="btn-primary" style="padding: 10px 22px; font-size: 14px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #2563eb; color: #fff; box-shadow: 0 4px 8px rgba(37,99,235,0.25);">
                            <i class="ri-download-2-line"></i> Download
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <i class="ri-folder-open-line" style="font-size: 3rem; color: #94a3b8; display: block; margin-bottom: 12px;"></i>
            <h3 style="margin-bottom: 8px; color: #334155;">No Files Available</h3>
            <p style="color: #64748b;">PDFs for this category will be available shortly.</p>
        </div>
    <?php endif; ?>

    <div style="margin-top: 50px; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <h4 style="margin: 0 0 6px 0; color: #1e293b; font-size: 1rem;">Explore Other Free Learning Tracks</h4>
            <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Browse hundreds of free guidance resources, worksheets, and articles.</p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="<?php echo site_url('articles'); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Articles &rarr;</a>
            <a href="<?php echo site_url('blogs'); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Blogs &rarr;</a>
            <a href="<?php echo site_url('career-facts'); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Career Facts &rarr;</a>
        </div>
    </div>
</div>
