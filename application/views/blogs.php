<div class="page-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 70px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-block; padding: 6px 14px; background: rgba(255, 113, 154, 0.15); color: #d63384; font-size: 13px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Free Resources</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px; color: #1e293b;">Our Blogs</h1>
        <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6; margin: 0 auto;">Read our latest thoughts, education insights, and updates from the Alpha Mindz team.</p>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
        <?php if(!empty($blogs)): foreach($blogs as $blog): ?>
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
            <div class="blog-card" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.04); display: flex; flex-direction: column;">
                <?php if($has_valid_img): ?>
                    <div style="height: 200px; overflow: hidden; background: #f1f5f9;">
                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($blog->title); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onerror="this.parentElement.style.display='none';">
                    </div>
                <?php endif; ?>
                <div class="blog-content" style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-block; padding: 4px 10px; background: #fdf2f8; color: #be185d; font-size: 12px; font-weight: 600; border-radius: 4px; text-transform: uppercase;">
                            <?php echo htmlspecialchars($blog->category ?: 'Blog'); ?>
                        </span>
                    </div>
                    <h3 style="font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 12px; font-size: 1.4rem; line-height: 1.35;">
                        <a href="<?php echo site_url('blogs/'.$blog->slug); ?>" style="color: #1e293b; text-decoration: none; transition: color 0.2s;">
                            <?php echo htmlspecialchars($blog->title); ?>
                        </a>
                    </h3>
                    <div class="blog-meta" style="font-size: 0.85rem; color: #64748b; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span><i class="ri-user-line"></i> <?php echo htmlspecialchars($blog->author ?: 'Alpha Mindz'); ?></span>
                        <span>•</span>
                        <span><i class="ri-calendar-line"></i> <?php echo !empty($blog->published_at) ? date('M d, Y', strtotime($blog->published_at)) : date('M d, Y', strtotime($blog->created_at)); ?></span>
                    </div>
                    <p style="color: #475569; line-height: 1.6; margin-bottom: 20px; font-size: 0.95rem; flex: 1;">
                        <?php 
                        $text = !empty($blog->excerpt) ? $blog->excerpt : strip_tags($blog->content);
                        echo (mb_strlen($text) > 130) ? mb_substr($text, 0, 130) . '...' : $text; 
                        ?>
                    </p>
                    <div>
                        <a href="<?php echo site_url('blogs/'.$blog->slug); ?>" class="btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; text-decoration: none; border-radius: 6px; font-size: 0.88rem; font-weight: 600;">
                            Read Blog <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #64748b; background: #fff; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <i class="ri-quill-pen-line" style="font-size: 3rem; color: #94a3b8; display: block; margin-bottom: 12px;"></i>
                <h3 style="margin-bottom: 8px; color: #334155;">No Blogs Published Yet</h3>
                <p>New blog posts will be published soon. Check back shortly!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
