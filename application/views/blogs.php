<div class="page-header" style="background: #f8f9fa; padding: 60px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px;">Our Blogs</h1>
        <p style="color: #6c757d; max-width: 600px; margin: 0 auto;">Read our latest articles, insights, and updates.</p>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <?php if(!empty($blogs)): foreach($blogs as $blog): ?>
            <div class="blog-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="blog-content" style="padding: 25px;">
                    <h3 style="font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 10px; font-size: 1.5rem;">
                        <a href="<?php echo site_url('blogs/view/'.$blog->slug); ?>" style="color: #2c3e50; text-decoration: none;"><?php echo $blog->title; ?></a>
                    </h3>
                    <div class="blog-meta" style="font-size: 0.9rem; color: #6c757d; margin-bottom: 15px;">
                        <span><i class="ri-user-line"></i> By <?php echo htmlspecialchars($blog->author); ?></span>
                        <span style="margin: 0 10px;">|</span>
                        <span><i class="ri-calendar-line"></i> <?php echo date('F d, Y', strtotime($blog->created_at)); ?></span>
                    </div>
                    <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">
                        <?php echo strip_tags(substr($blog->content, 0, 120)) . '...'; ?>
                    </p>
                    <a href="<?php echo site_url('blogs/view/'.$blog->slug); ?>" class="btn-primary" style="display: inline-block; padding: 8px 20px; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">Read More</a>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6c757d;">
                <p>No blogs published yet. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
