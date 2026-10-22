<div class="page-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 75px 0; text-align: center; border-bottom: 1px solid #334155;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-block; padding: 6px 16px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-size: 13px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;">Free Knowledge Base</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px; color: #fff;">Free Learning Resources & Downloads</h1>
        <p style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.6; max-width: 700px; margin: 0 auto;">Download complimentary study guides, audit sheets, exam prep toolkits, and career development worksheets curated by AlphaMindz educators.</p>
    </div>
</div>

<div class="container" style="padding: 70px 20px 100px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px;">
        <?php foreach($categories as $slug => $cat): ?>
            <?php 
                $count = isset($cat['pdf_count']) ? $cat['pdf_count'] : 0;
            ?>
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px 28px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div style="width: 54px; height: 54px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                            <i class="<?php echo $cat['icon']; ?>"></i>
                        </div>
                        <span style="display: inline-block; padding: 4px 10px; background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700; border-radius: 20px; text-transform: uppercase;">
                            <?php echo $count; ?> PDF<?php echo $count != 1 ? 's' : ''; ?>
                        </span>
                    </div>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 12px 0; color: #1e293b;">
                        <a href="<?php echo site_url('resources/'.$slug); ?>" style="color: #1e293b; text-decoration: none;">
                            <?php echo htmlspecialchars($cat['title']); ?>
                        </a>
                    </h3>
                    <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin: 0 0 24px 0;">
                        <?php echo htmlspecialchars($cat['desc']); ?>
                    </p>
                </div>
                <div>
                    <a href="<?php echo site_url('resources/'.$slug); ?>" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; font-size: 14px; border-radius: 8px; text-decoration: none; font-weight: 600; background: #2563eb; color: #fff;">
                        Browse & Download <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
