    <!-- Page Header -->
    <header class="hero" style="padding: 50px 0; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; min-height: auto;">
        <div class="hero-container" style="grid-template-columns: 1fr; text-align: left; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div class="hero-content">
                <a href="<?php echo site_url('courses'); ?>" style="color: #64b5f6; text-decoration: none; font-size: 14px; margin-bottom: 12px; display: inline-block;"><i class="ri-arrow-left-line"></i> Back to Courses</a>
                <h1 class="hero-title" style="font-size: 38px; color: #fff; margin-bottom: 12px;"><?php echo htmlspecialchars($course->title); ?></h1>
                <?php if (!empty($course->duration)): ?>
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 13px; color: #fff;"><i class="ri-time-line"></i> Duration: <?php echo htmlspecialchars($course->duration); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Course Content -->
    <div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        
        <!-- Course Introduction Section -->
        <?php if (!empty($course->introduction)): ?>
        <section style="background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 40px;">
            <h2 style="font-size: 24px; color: #2c3e50; margin-top: 0; border-bottom: 2px solid #3498db; padding-bottom: 10px; display: inline-block;">
                <i class="ri-book-open-line"></i> Course Introduction
            </h2>
            <div style="font-size: 16px; line-height: 1.7; color: #444; margin-top: 16px;">
                <?php echo $course->introduction; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Full Description Section -->
        <?php if (!empty($course->description)): ?>
        <section style="background: #fff; border-radius: 12px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 40px;">
            <h2 style="font-size: 24px; color: #2c3e50; margin-top: 0; border-bottom: 2px solid #3498db; padding-bottom: 10px; display: inline-block;">
                <i class="ri-article-line"></i> Course Overview & Syllabus
            </h2>
            <div style="font-size: 15px; line-height: 1.6; color: #555; margin-top: 16px;">
                <?php echo $course->description; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Differential Pricing Tiers Section -->
        <section style="margin-bottom: 60px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h2 style="font-size: 32px; color: #2c3e50; margin-bottom: 8px;">Select Your Course Tier</h2>
                <p style="font-size: 16px; color: #666;">Choose the pricing tier that best fits your learning requirements and content protection preferences.</p>
            </div>

            <?php if (!empty($tiers)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 24px;">
                    <?php 
                    $badge_styles = array(
                        'basic' => array('bg' => '#e3f2fd', 'color' => '#1976d2', 'border' => '#90caf9', 'header_bg' => '#1976d2'),
                        'standard' => array('bg' => '#fff3e0', 'color' => '#e65100', 'border' => '#ffcc80', 'header_bg' => '#f57c00'),
                        'advanced' => array('bg' => '#e8f5e9', 'color' => '#2e7d32', 'border' => '#a5d6a7', 'header_bg' => '#388e3c'),
                        'premium' => array('bg' => '#f3e5f5', 'color' => '#7b1fa2', 'border' => '#ce93d8', 'header_bg' => '#8e24aa')
                    );
                    foreach ($tiers as $tier): 
                        $style = isset($badge_styles[$tier->tier_key]) ? $badge_styles[$tier->tier_key] : $badge_styles['basic'];
                    ?>
                        <div style="background: #fff; border: 2px solid <?php echo $style['border']; ?>; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 16px rgba(0,0,0,0.06); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            
                            <div>
                                <!-- Tier Header -->
                                <div style="background: <?php echo $style['header_bg']; ?>; color: #fff; padding: 20px; text-align: center;">
                                    <span style="text-transform: uppercase; letter-spacing: 1px; font-size: 12px; opacity: 0.9; font-weight: bold;"><?php echo htmlspecialchars($tier->tier_name); ?> TIER</span>
                                    <h3 style="font-size: 32px; margin: 8px 0 0 0; font-weight: 800;">₹<?php echo number_format($tier->price, 2); ?></h3>
                                </div>

                                <!-- Tier Content Details -->
                                <div style="padding: 24px;">
                                    
                                    <!-- Module Composition -->
                                    <div style="margin-bottom: 20px;">
                                        <h5 style="font-size: 12px; text-transform: uppercase; color: #888; margin: 0 0 6px 0; letter-spacing: 0.5px;">Module Composition</h5>
                                        <p style="font-size: 14px; font-weight: 600; color: #2c3e50; margin: 0; line-height: 1.4;">
                                            <i class="ri-checkbox-circle-fill" style="color: <?php echo $style['header_bg']; ?>; margin-right: 4px;"></i>
                                            <?php echo htmlspecialchars($tier->description); ?>
                                        </p>
                                    </div>

                                    <!-- Delivery Security Policy -->
                                    <?php if (!empty($tier->delivery_policy)): ?>
                                    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 12px; border-radius: 8px;">
                                        <h5 style="font-size: 11px; text-transform: uppercase; color: #666; margin: 0 0 4px 0; font-weight: bold;"><i class="ri-shield-check-line"></i> Delivery Security Policy</h5>
                                        <p style="font-size: 12px; color: #555; margin: 0; line-height: 1.4;">
                                            <?php echo htmlspecialchars($tier->delivery_policy); ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>

                                    <!-- E-Book Policy -->
                                    <?php if (!empty($tier->ebook_policy)): ?>
                                    <div style="margin-bottom: 20px; background: #f8f9fa; padding: 12px; border-radius: 8px;">
                                        <h5 style="font-size: 11px; text-transform: uppercase; color: #666; margin: 0 0 4px 0; font-weight: bold;"><i class="ri-book-3-line"></i> Digital Asset Policy</h5>
                                        <p style="font-size: 12px; color: #555; margin: 0; line-height: 1.4;">
                                            <?php echo htmlspecialchars($tier->ebook_policy); ?>
                                        </p>
                                    </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <!-- Footer / Action Button -->
                            <div style="padding: 24px; padding-top: 0;">
                                <form action="<?php echo site_url('cart/add'); ?>" method="POST">
                                    <input type="hidden" name="item_type" value="course">
                                    <input type="hidden" name="item_id" value="<?php echo $course->id; ?>">
                                    <input type="hidden" name="tier_key" value="<?php echo htmlspecialchars($tier->tier_key); ?>">
                                    <button type="submit" style="display: block; width: 100%; border: none; cursor: pointer; text-align: center; background: <?php echo $style['header_bg']; ?>; color: #fff; text-decoration: none; padding: 14px 0; border-radius: 6px; font-weight: bold; font-size: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: opacity 0.2s;">
                                        Enroll in <?php echo htmlspecialchars($tier->tier_name); ?> <i class="ri-arrow-right-line"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; background: #fff; border-radius: 12px;">
                    <p style="font-size: 18px; color: #777;">Pricing tier information is being updated for this course.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
