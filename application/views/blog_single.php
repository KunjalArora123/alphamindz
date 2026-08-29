<div class="page-header" style="background: #f8f9fa; padding: 60px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px; color: #2c3e50;"><?php echo $blog->title; ?></h1>
        <div class="blog-meta" style="font-size: 1rem; color: #6c757d;">
            <span><i class="ri-user-line"></i> Written by <strong><?php echo htmlspecialchars($blog->author); ?></strong></span>
            <span style="margin: 0 15px;">|</span>
            <span><i class="ri-calendar-line"></i> <?php echo date('F d, Y', strtotime($blog->created_at)); ?></span>
        </div>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 800px; margin: 0 auto;">
    <div class="blog-content" style="line-height: 1.8; color: #444; font-size: 1.1rem;">
        <?php echo $blog->content; ?>
    </div>
    
    <div style="margin-top: 50px; border-top: 1px solid #eee; padding-top: 30px;">
        <a href="<?php echo site_url('blogs'); ?>" style="color: #007bff; text-decoration: none;"><i class="ri-arrow-left-line"></i> Back to all blogs</a>
    </div>
</div>
