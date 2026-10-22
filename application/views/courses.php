    <!-- Page Header -->
    <header class="hero" style="padding: 60px 0; padding-bottom: 40px; min-height: auto;">
        <div class="hero-container" style="grid-template-columns: 1fr; text-align: center; gap: 20px;">
            <div class="hero-content">
                <h1 class="hero-title" style="font-size: 48px;">Our <span class="text-pink">Courses</span></h1>
                <p class="hero-subtitle" style="margin: 0 auto;">Explore our diverse range of courses designed to empower and inspire you.</p>
            </div>
        </div>
    </header>

    <!-- Courses Grid Section -->
    <section class="shop-section" style="background: var(--bg-main); padding-top: 20px;">
        <div class="section-container">
            <div class="shop-grid">
                <?php if(!empty($courses)): ?>
                    <?php foreach($courses as $course): ?>
                        <?php 
                        $min_price = $course->price;
                        $tier_count = 0;
                        if (!empty($course->tiers)) {
                            $tier_count = count($course->tiers);
                            $tier_prices = array_map(function($t) { return (float)$t->price; }, $course->tiers);
                            $min_price = min($tier_prices);
                        }
                        $has_media = !empty($course->media);
                        ?>
                        <div class="product-card" style="display: flex; flex-direction: column;">
                            <div class="product-image" style="aspect-ratio: 16/9; background: #eee; position: relative;">
                                <?php 
                                $thumb_src = !empty($course->thumbnail) ? base_url($course->thumbnail) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                                ?>
                                <img id="course-main-img-<?php echo $course->id; ?>" src="<?php echo $thumb_src; ?>" alt="<?php echo htmlspecialchars($course->title); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: opacity 0.2s ease;">
                                
                                <?php if($tier_count > 0): ?>
                                <span class="product-badge" style="background: var(--color-blue); color: #fff; text-transform: capitalize;"><?php echo $tier_count; ?> Tiers Available</span>
                                <?php endif; ?>

                                <?php if($has_media): ?>
                                <span style="position: absolute; bottom: 8px; right: 8px; background: rgba(15, 23, 42, 0.8); color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; backdrop-filter: blur(4px);">
                                    <i class="ri-image-2-line"></i> <?php echo count($course->media) + (!empty($course->thumbnail) ? 1 : 0); ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <!-- Course Media Gallery Strip if custom images exist -->
                            <?php if($has_media): ?>
                            <div style="display: flex; gap: 6px; padding: 8px 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; overflow-x: auto; align-items: center;">
                                <?php if(!empty($course->thumbnail)): ?>
                                    <img src="<?php echo base_url($course->thumbnail); ?>" 
                                         alt="Cover" 
                                         onclick="switchCoursePreview(<?php echo $course->id; ?>, '<?php echo base_url($course->thumbnail); ?>', this)"
                                         style="width: 38px; height: 38px; object-fit: cover; border-radius: 4px; border: 2px solid #2563eb; cursor: pointer; flex-shrink: 0;"
                                         title="Cover image">
                                <?php endif; ?>
                                <?php foreach($course->media as $med): ?>
                                    <img src="<?php echo base_url($med->image_path); ?>" 
                                         alt="<?php echo htmlspecialchars($med->caption ?: 'Course Image'); ?>" 
                                         onclick="switchCoursePreview(<?php echo $course->id; ?>, '<?php echo base_url($med->image_path); ?>', this)"
                                         style="width: 38px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1; cursor: pointer; flex-shrink: 0; opacity: 0.85; transition: all 0.2s;"
                                         onmouseover="this.style.opacity='1'"
                                         onmouseout="if(!this.classList.contains('active-thumb')) this.style.opacity='0.85'"
                                         title="<?php echo htmlspecialchars($med->caption ?: 'View image'); ?>">
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="product-info" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h4 style="min-height: 48px; line-height: 1.4;">
                                        <a href="<?php echo site_url('courses/'.$course->id); ?>" style="color: inherit; text-decoration: none;">
                                            <?php echo htmlspecialchars($course->title); ?>
                                        </a>
                                    </h4>
                                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?php echo !empty($course->introduction) ? strip_tags($course->introduction) : strip_tags($course->description); ?>
                                    </p>
                                </div>
                                
                                <div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                        <p class="price" style="margin-bottom: 0;">Starting at ₹<?php echo number_format($min_price, 2); ?></p>
                                        <span style="font-size: 12px; font-weight: 600; color: var(--color-green);"><i class="ri-time-line"></i> <?php echo htmlspecialchars($course->duration); ?></span>
                                    </div>
                                    <a href="<?php echo site_url('courses/'.$course->id); ?>" class="btn-primary" style="width: 100%; justify-content: center;">View Tiers & Enroll <i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px 0;">
                        <i class="ri-book-3-line" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px; display: inline-block;"></i>
                        <p style="font-size: 18px; color: var(--text-muted);">No courses available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
    function switchCoursePreview(courseId, imgSrc, clickedThumb) {
        var mainImg = document.getElementById('course-main-img-' + courseId);
        if (mainImg) {
            mainImg.style.opacity = '0.4';
            setTimeout(function() {
                mainImg.src = imgSrc;
                mainImg.style.opacity = '1';
            }, 150);
        }
        var container = clickedThumb.parentElement;
        if (container) {
            var thumbs = container.querySelectorAll('img');
            thumbs.forEach(function(t) {
                t.style.border = '1px solid #cbd5e1';
                t.classList.remove('active-thumb');
            });
            clickedThumb.style.border = '2px solid #2563eb';
            clickedThumb.classList.add('active-thumb');
        }
    }
    </script>
