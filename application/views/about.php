<?php $this->load->view('public_header', ['title' => 'About Us | AlphaMindz']); ?>

<!-- Internal Page Header -->
<section style="padding: 50px 20px 40px; text-align: center; background: radial-gradient(circle at 50% 0%, rgba(48, 98, 135, 0.05) 0%, transparent 70%); border-bottom: 1px solid var(--border);">
    <div class="section-container" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
        <span class="hero-label" style="margin-bottom: 16px; font-size: 12px; padding: 6px 16px;">Our Story</span>
        <h1 class="hero-title" style="font-size: 48px; margin-bottom: 16px;">About <span class="text-pink">AlphaMindz</span></h1>
        <p class="hero-subtitle" style="font-size: 16px; margin: 0 auto;">Empowering minds, inspiring futures, and motivating individuals to reach their highest potential since 2007.</p>
    </div>
</section>

<!-- Team Section -->
<section class="team-section" style="padding: 60px 20px; background-color: var(--bg-main);">
    <div class="section-container" style="max-width: 1200px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="font-family: var(--font-heading); font-size: 36px; color: var(--text-main); margin-bottom: 16px;">Meet Our Team</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">The visionary leaders and dedicated professionals driving our mission forward across our regional hubs.</p>
        </div>

        <!-- Team Solan Section -->
        <div class="team-group-container" style="margin-bottom: 60px;">
            <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid rgba(48, 98, 135, 0.15); padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <i class="ri-map-pin-2-fill" style="font-size: 1.5rem; color: #e74c3c;"></i>
                        <h3 style="font-family: var(--font-heading); font-size: 26px; color: #2c3e50; margin: 0;">Solan Branch (Himachal Pradesh)</h3>
                    </div>
                    <p style="color: #6c757d; font-size: 0.9rem; margin: 0 0 0 34px;">
                        <i class="ri-building-line"></i> First Floor, Satish Complex, Kotla Nala, Solan, Himachal Pradesh 173212
                    </p>
                </div>
                <a href="https://www.google.com/maps/place/First+Floor,+Alpha+Mindz+Solan,+Satish+Complex,+Kotla+Nala,+Solan,+Himachal+Pradesh+173212/data=!4m2!3m1!1s0x390f8138573242bb:0x1e78d5e86a03c187!18m1!1e1?utm_source=mstt_1&entry=gps&coh=192189&g_ep=CAESBzI2LjM4LjEYACCenQoqvQEsOTQyNjc3MjcsOTQyOTIxOTUsOTQyOTk1MzIsMTAwNzk2NDk4LDEwMDc5Nzc2MSwxMDA3OTY1MzUsOTQyODA1NzYsOTQy07M0OTQsOTQyMDc1MDYsOTQyMDg1MDYsOTQyMTg2NTMsOTQyMjk4MzksOTQyNzUxNjgsOTQyNzk2MTksMTAwODM1NzEwLDEwMDgyNTAyNSwxMDA8MjAyMzcsMTAwODIyNDk4LDEwMDgyNzk3NSwxMDA8MzgzNTVCAklO&skid=2b3d107f-431e-4815-a371-6f43a8cd9a18&g_st=aw" target="_blank" rel="noopener noreferrer" style="background: #e74c3c; color: #fff; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(231,76,60,0.25);">
                    <i class="ri-map-pin-line"></i> View Map & Directions
                </a>
            </div>

            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <!-- Manu Anand -->
                <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="<?php echo base_url('assets/images/team/manu.jpg'); ?>" alt="Manu Anand" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;">Manu Anand</h3>
                    <span class="member-title" style="display: inline-block; background: #ebf5fb; color: #2980b9; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;">CEO</span>
                    <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                        Alumni of IIM Ahmedabad - Mechanical Engineer having +27 years of working experience in the industry. Has worked in Senior Management Positions at Pentair Water as the Country head and at Purolator as a General Manager before Founding Alpha Mindz in 2007.
                    </p>
                </div>

                <!-- Candie Anand -->
                <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="<?php echo base_url('assets/images/team/candie.jpg'); ?>" alt="Candie Anand" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;">Candie Anand</h3>
                    <span class="member-title" style="display: inline-block; background: #fef9e7; color: #f39c12; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;">DIRECTOR</span>
                    <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                        An Electronics Engineer with 24+ years of experience in the diverse Sectors of Maintenance, Testing Design and Training with Purolator, Mindarica and Anand University. Co-Founded Alpha Mindz in 2007.
                    </p>
                </div>
            </div>
        </div>

        <!-- Team Goa Section -->
        <div class="team-group-container">
            <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid rgba(48, 98, 135, 0.15); padding-bottom: 16px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <i class="ri-map-pin-2-fill" style="font-size: 1.5rem; color: #2ecc71;"></i>
                        <h3 style="font-family: var(--font-heading); font-size: 26px; color: #2c3e50; margin: 0;">Goa Branch (Panaji)</h3>
                    </div>
                    <p style="color: #6c757d; font-size: 0.9rem; margin: 0 0 0 34px;">
                        <i class="ri-building-line"></i> 302, Sai Plaza, opp. Gomantak Press, St. Inez, Altinho, Panaji, Goa 403001
                    </p>
                </div>
                <a href="https://maps.app.goo.gl/orfvmi45fKQES2Mx6?g_st=aw" target="_blank" rel="noopener noreferrer" style="background: #2ecc71; color: #fff; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(46,204,113,0.25);">
                    <i class="ri-map-pin-line"></i> View Map & Directions
                </a>
            </div>

            <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <!-- Rupa Sawant -->
                <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="<?php echo base_url('assets/images/team/rupa.jpg'); ?>" alt="Rupa Sawant" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;">Rupa Sawant</h3>
                    <span class="member-title" style="display: inline-block; background: #eafaf1; color: #27ae60; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;">SR. MANAGER BUSINESS DEVELOPMENT</span>
                    <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                        BBA from Goa University and Executive MBA from GIM (Goa Institute of Management). Started career with Alpha Mindz in the year 2007. With over 12 years of experience in the field of Business Development and training.
                    </p>
                </div>

                <!-- Deepa Prabhu -->
                <div class="team-member text-center" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.3s, box-shadow 0.3s;">
                    <div class="member-image-wrapper" style="width: 140px; height: 140px; margin: 0 auto 20px auto; border-radius: 50%; overflow: hidden; border: 4px solid #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="<?php echo base_url('assets/images/team/deepa.jpg'); ?>" alt="Deepa Prabhu" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h3 class="member-name" style="font-family: var(--font-heading); font-size: 1.4rem; color: #2c3e50; margin: 0 0 6px 0;">Deepa Prabhu</h3>
                    <span class="member-title" style="display: inline-block; background: #f4ecf7; color: #8e44ad; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 15px;">SR. MANAGER MARKETING</span>
                    <p class="member-bio" style="color: #6c757d; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                        BBA from Goa University and Executive MBA from GIM (Goa Institute of Management). Started career with Alpha Mindz in the year 2007. With over 12 years of experience in the field of Marketing and training.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

<?php $this->load->view('public_footer'); ?>
