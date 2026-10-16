<div class="page-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 70px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 860px; margin: 0 auto; padding: 0 20px;">
        <div style="margin-bottom: 14px;">
            <a href="<?php echo site_url('articles'); ?>" style="color: var(--color-blue); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-arrow-left-line"></i> Back to Articles
            </a>
        </div>
        <div style="margin-bottom: 12px;">
            <span style="display: inline-block; padding: 4px 12px; background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 700; border-radius: 4px; text-transform: uppercase;">
                <?php echo htmlspecialchars($article->category ?: 'Article'); ?>
            </span>
        </div>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; line-height: 1.25; margin-bottom: 18px; color: #1e293b;"><?php echo htmlspecialchars($article->title); ?></h1>
        <div class="blog-meta" style="font-size: 0.95rem; color: #64748b; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span><i class="ri-user-line"></i> By <strong><?php echo htmlspecialchars($article->author ?: 'Alpha Mindz'); ?></strong></span>
            <span>•</span>
            <span><i class="ri-calendar-line"></i> <?php echo !empty($article->published_at) ? date('F d, Y', strtotime($article->published_at)) : date('F d, Y', strtotime($article->created_at)); ?></span>
        </div>
    </div>
</div>

<div class="container" style="padding: 50px 20px 80px; max-width: 860px; margin: 0 auto;">
    <?php if(!empty($article->image_url)): ?>
        <div style="margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.08);">
            <img src="<?php echo htmlspecialchars($article->image_url); ?>" alt="<?php echo htmlspecialchars($article->title); ?>" style="width: 100%; max-height: 480px; object-fit: cover; display: block;" onerror="this.style.display='none';">
        </div>
    <?php endif; ?>

    <div class="article-body" style="line-height: 1.9; color: #334155; font-size: 1.15rem; word-break: break-word;">
        <?php echo $article->content; ?>
    </div>
    
    <div style="margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: gap: 16px;">
        <a href="<?php echo site_url('articles'); ?>" class="btn-outline" style="padding: 10px 22px; font-size: 14px; text-decoration: none; border-radius: 30px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-arrow-left-line"></i> All Articles
        </a>
        <a href="<?php echo site_url('blogs'); ?>" style="color: var(--color-blue); text-decoration: none; font-size: 14px; font-weight: 600;">
            Explore Blogs <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</div>
