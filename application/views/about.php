<?php $this->load->view('public_header', ['title' => 'About Us | AlphaMindz']); ?>

<!-- Internal Page Header -->
<section style="padding: 50px 20px 40px; text-align: center; background: radial-gradient(circle at 50% 0%, rgba(48, 98, 135, 0.05) 0%, transparent 70%); border-bottom: 1px solid var(--border);">
    <div class="section-container" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
        <span class="hero-label" style="margin-bottom: 16px; font-size: 12px; padding: 6px 16px;"><?php echo htmlspecialchars($about_hero_tag); ?></span>
        <h1 class="hero-title" style="font-size: 48px; margin-bottom: 16px;">
            <?php 
                // Highlights "AlphaMindz" with .text-pink if found in title
                $title_str = htmlspecialchars($about_hero_title);
                if (stripos($title_str, 'AlphaMindz') !== false) {
                    echo str_ireplace('AlphaMindz', '<span class="text-pink">AlphaMindz</span>', $title_str);
                } else {
                    echo $title_str;
                }
            ?>
        </h1>
        <p class="hero-subtitle" style="font-size: 16px; margin: 0 auto;"><?php echo htmlspecialchars($about_hero_subtitle); ?></p>
    </div>
</section>

<!-- Team Section -->
<section class="team-section" style="padding: 60px 20px; background-color: var(--bg-main);">
    <div class="section-container" style="max-width: 1200px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="font-family: var(--font-heading); font-size: 36px; color: var(--text-main); margin-bottom: 16px;"><?php echo htmlspecialchars($about_team_heading); ?></h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;"><?php echo htmlspecialchars($about_team_subheading); ?></p>
        </div>

        <!-- Team Solan Section -->
        <div class="team-group-container" style="margin-bottom: 60px;">
            <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid rgba(48, 98, 135, 0.15); padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <i class="ri-map-pin-2-fill" style="font-size: 1.5rem; color: #e74c3c;"></i>
                        <h3 style="font-family: var(--font-heading); font-size: 26px; color: #2c3e50; margin: 0;"><?php echo htmlspecialchars($about_solan_title); ?></h3>
                    </div>
                    <p style="color: #6c757d; font-size: 0.9rem; margin: 0 0 0 34px;">
                        <i class="ri-building-line"></i> <?php echo htmlspecialchars($about_solan_address); ?>
                    </p>
                </div>
                <?php if(!empty($about_solan_map_link)): ?>
                <a href="<?php echo htmlspecialchars($about_solan_map_link); ?>" target="_blank" rel="noopener noreferrer" style="background: #e74c3c; color: #fff; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(231,76,60,0.25);">
                    <i class="ri-map-pin-line"></i> View Map & Directions
                </a>
                <?php endif; ?>
            </div>

            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <?php if(!empty($solan_members)): foreach($solan_members as $m): ?>
                    <?php
                        $badge_colors = [
                            'blue'   => ['bg' => '#ebf5fb', 'text' => '#2980b9'],
                            'orange' => ['bg' => '#fef9e7', 'text' => '#f39c12'],
                            'green'  => ['bg' => '#eafaf1', 'text' => '#27ae60'],
                            'purple' => ['bg' => '#f4ecf7', 'text' => '#8e44ad']
                        ];
                        $theme = isset($badge_colors[$m->role_color_theme]) ? $badge_colors[$m->role_color_theme] : $badge_colors['blue'];
                        $img_src = base_url('assets/images/team/manu.jpg');
                        if (!empty($m->image_url)) {
                            $img_src = (strpos($m->image_url, 'http') === 0) ? $m->image_url : base_url($m->image_url);
                        }
                    ?>
                    <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                        <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($m->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;"><?php echo htmlspecialchars($m->name); ?></h3>
                        <span class="member-title" style="display: inline-block; background: <?php echo $theme['bg']; ?>; color: <?php echo $theme['text']; ?>; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;"><?php echo htmlspecialchars($m->role_title); ?></span>
                        <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                            <?php echo nl2br(htmlspecialchars($m->bio)); ?>
                        </p>
                    </div>
                <?php endforeach; else: ?>
                    <p style="color: #6c757d; grid-column: 1 / -1; text-align: center;">No team members assigned to Solan branch.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Team Goa Section -->
        <div class="team-group-container">
            <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid rgba(48, 98, 135, 0.15); padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <i class="ri-map-pin-2-fill" style="font-size: 1.5rem; color: #2ecc71;"></i>
                        <h3 style="font-family: var(--font-heading); font-size: 26px; color: #2c3e50; margin: 0;"><?php echo htmlspecialchars($about_goa_title); ?></h3>
                    </div>
                    <p style="color: #6c757d; font-size: 0.9rem; margin: 0 0 0 34px;">
                        <i class="ri-building-line"></i> <?php echo htmlspecialchars($about_goa_address); ?>
                    </p>
                </div>
                <?php if(!empty($about_goa_map_link)): ?>
                <a href="<?php echo htmlspecialchars($about_goa_map_link); ?>" target="_blank" rel="noopener noreferrer" style="background: #2ecc71; color: #fff; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(46,204,113,0.25);">
                    <i class="ri-map-pin-line"></i> View Map & Directions
                </a>
                <?php endif; ?>
            </div>

            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <?php if(!empty($goa_members)): foreach($goa_members as $m): ?>
                    <?php
                        $badge_colors = [
                            'blue'   => ['bg' => '#ebf5fb', 'text' => '#2980b9'],
                            'orange' => ['bg' => '#fef9e7', 'text' => '#f39c12'],
                            'green'  => ['bg' => '#eafaf1', 'text' => '#27ae60'],
                            'purple' => ['bg' => '#f4ecf7', 'text' => '#8e44ad']
                        ];
                        $theme = isset($badge_colors[$m->role_color_theme]) ? $badge_colors[$m->role_color_theme] : $badge_colors['blue'];
                        $img_src = base_url('assets/images/team/manu.jpg');
                        if (!empty($m->image_url)) {
                            $img_src = (strpos($m->image_url, 'http') === 0) ? $m->image_url : base_url($m->image_url);
                        }
                    ?>
                    <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                        <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($m->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;"><?php echo htmlspecialchars($m->name); ?></h3>
                        <span class="member-title" style="display: inline-block; background: <?php echo $theme['bg']; ?>; color: <?php echo $theme['text']; ?>; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;"><?php echo htmlspecialchars($m->role_title); ?></span>
                        <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                            <?php echo nl2br(htmlspecialchars($m->bio)); ?>
                        </p>
                    </div>
                <?php endforeach; else: ?>
                    <p style="color: #6c757d; grid-column: 1 / -1; text-align: center;">No team members assigned to Goa branch.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php $this->load->view('public_footer'); ?>
