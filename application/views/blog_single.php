<div class="page-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 70px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 860px; margin: 0 auto; padding: 0 20px;">
        <div style="margin-bottom: 14px;">
            <a href="<?php echo site_url('blogs'); ?>" style="color: var(--color-blue); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-arrow-left-line"></i> Back to Blogs
            </a>
        </div>
        <div style="margin-bottom: 12px;">
            <span style="display: inline-block; padding: 4px 12px; background: #fdf2f8; color: #be185d; font-size: 12px; font-weight: 700; border-radius: 4px; text-transform: uppercase;">
                <?php echo htmlspecialchars($blog->category ?: 'Blog'); ?>
            </span>
        </div>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; line-height: 1.25; margin-bottom: 18px; color: #1e293b;"><?php echo htmlspecialchars($blog->title); ?></h1>
        <div class="blog-meta" style="font-size: 0.95rem; color: #64748b; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span><i class="ri-user-line"></i> By <strong><?php echo htmlspecialchars($blog->author ?: 'Alpha Mindz'); ?></strong></span>
            <span>•</span>
            <span><i class="ri-calendar-line"></i> <?php echo !empty($blog->published_at) ? date('F d, Y', strtotime($blog->published_at)) : date('F d, Y', strtotime($blog->created_at)); ?></span>
        </div>
    </div>
</div>

<div class="container" style="padding: 50px 20px 80px; max-width: 860px; margin: 0 auto;">
    <?php 
        $raw_img = !empty($blog->image) ? $blog->image : (!empty($blog->image_url) ? $blog->image_url : '');
        $has_valid_img = !empty($raw_img) 
            && stripos($raw_img, 'localhost') === false 
            && stripos($raw_img, '127.0.0.1') === false;
        $img_src = '';
        if ($has_valid_img) {
            $img_src = (strpos($raw_img, 'http://') === 0 || strpos($raw_img, 'https://') === 0) 
                ? $raw_img 
                : base_url($raw_img);
        }
    ?>
    <?php if($has_valid_img): ?>
        <div style="margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.08);">
            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($blog->title); ?>" style="width: 100%; max-height: 480px; object-fit: cover; display: block;" onerror="this.style.display='none';">
        </div>
    <?php endif; ?>

    <div class="blog-body" style="line-height: 1.9; color: #334155; font-size: 1.15rem; word-break: break-word;">
        <?php 
            $clean_content = preg_replace('/\s*<!-- wp:button[^\>]*-->\s*<div class="wp-block-button">.*?<\/div>\s*<!-- \/wp:button -->(\s*<!-- wp:spacer[^\>]*-->\s*<div style="height:50px"[^>]*><\/div>\s*<!-- \/wp:spacer -->)?/is', '', $blog->content);
            $clean_content = preg_replace('/<div[^>]*class=[\'"][^\'"]*wp-block-button[^\'"]*[\'"][^>]*>.*?<\/div>/is', '', $clean_content);
            $clean_content = preg_replace('/<a[^>]+href=[\'"][^\'"]*\.pdf[\'"][^>]*>.*?download.*?<\/a>/is', '', $clean_content);
            $clean_content = preg_replace('/<br\s*\/?>\s*(<strong>)?\s*Email:\s*manu@localhost\/aplha_m\/public_html\s*(<\/strong>)?/i', '', $clean_content);
            $clean_content = str_ireplace('Email: manu@localhost/aplha_m/public_html', '', $clean_content);
            $clean_content = str_ireplace('manu@localhost/aplha_m/public_html', '', $clean_content);
            // Remove any legacy local WordPress image tags pointing to localhost
            $clean_content = preg_replace('/<figure[^>]*>\s*<img[^>]+src=[\'"][^\'"]*(localhost|127\.0\.0\.1)[^\'"]*[\'"][^>]*>\s*<\/figure>/is', '', $clean_content);
            $clean_content = preg_replace('/<img[^>]+src=[\'"][^\'"]*(localhost|127\.0\.0\.1)[^\'"]*[\'"][^>]*>/is', '', $clean_content);
            $clean_content = preg_replace('/<a[^>]+href=[\'"][^\'"]*(localhost|127\.0\.0\.1)[^\'"]*[\'"][^>]*>.*?<\/a>/is', '', $clean_content);
            echo $clean_content; 
        ?>
    </div>
    
    <div style="margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <a href="<?php echo site_url('blogs'); ?>" class="btn-outline" style="padding: 10px 22px; font-size: 14px; text-decoration: none; border-radius: 30px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-arrow-left-line"></i> All Blogs
        </a>
        <a href="<?php echo site_url('articles'); ?>" style="color: var(--color-blue); text-decoration: none; font-size: 14px; font-weight: 600;">
            Explore Articles <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</div>
