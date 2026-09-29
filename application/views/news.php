<div class="page-header" style="background: #f8f9fa; padding: 60px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 15px;">Latest News</h1>
        <p style="color: #6c757d; max-width: 600px; margin: 0 auto;">Stay updated with the latest news, announcements, and insights from Alpha Mindz.</p>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 1200px; margin: 0 auto;">
    <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
        <?php if(!empty($all_news)): foreach($all_news as $news): ?>
            <article class="news-card" style="display: flex; gap: 20px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); align-items: center; border: 1px solid #eee;">
                <div class="news-image" style="flex-shrink: 0;">
                    <img src="<?php echo htmlspecialchars($news->image_url); ?>" alt="Update" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover;">
                </div>
                <div class="news-content" style="flex: 1;">
                    <span class="tag <?php echo htmlspecialchars($news->tag_color); ?>" style="position: static; font-size: 11px; padding: 4px 10px; margin-bottom: 10px; display: inline-block; border-radius: 20px; font-weight: bold; text-transform: uppercase;">
                        <?php echo htmlspecialchars($news->tag); ?>
                    </span>
                    <h3 style="margin: 0 0 8px 0; font-size: 1.1rem; line-height: 1.4;"><a href="<?php echo htmlspecialchars($news->link); ?>" style="color: #2c3e50; text-decoration: none;"><?php echo htmlspecialchars($news->title); ?></a></h3>
                    <span class="date" style="font-size: 13px; color: #6c757d; font-weight: 500;"><i class="ri-calendar-line"></i> <?php echo date('M d, Y', strtotime($news->news_date)); ?></span>
                </div>
            </article>
        <?php endforeach; else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6c757d;">
                <i class="ri-newspaper-line" style="font-size: 48px; color: #dee2e6; margin-bottom: 10px; display: block;"></i>
                <p>No news available yet. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</div>
