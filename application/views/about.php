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
<section class="team-section" style="padding: 80px 0; background-color: var(--bg-main);">
    <div class="section-container">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-family: var(--font-heading); font-size: 36px; color: var(--text-main); margin-bottom: 16px;">Meet Our Team</h2>
            <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">The visionary leaders and dedicated professionals driving our mission forward.</p>
        </div>

        <div class="team-grid">
            <!-- Manu Anand -->
            <div class="team-member text-center">
                <div class="member-image-wrapper">
                    <img src="<?php echo base_url('assets/images/team/manu.jpg'); ?>" alt="Manu Anand">
                </div>
                <h3 class="member-name">Manu Anand</h3>
                <span class="member-title">CEO</span>
                <p class="member-bio">
                    Alumni of IIM Ahmedabad -Mechanical Engineer having +27 years of working experience in the industry. Has worked in Senior Management Positions at Pentair Water as the Country head and at Purolator as a General Manager before Founding Alpha Mindz in 2007.
                </p>
            </div>

            <!-- Candie Anand -->
            <div class="team-member text-center">
                <div class="member-image-wrapper">
                    <img src="<?php echo base_url('assets/images/team/candie.jpg'); ?>" alt="Candie Anand">
                </div>
                <h3 class="member-name">Candie Anand</h3>
                <span class="member-title">DIRECTOR</span>
                <p class="member-bio">
                    An Electronics Engineer with 24+ years of experience in the diverse Sectors of Maintenance , Testing Design and Training with Purolator , Mindarica and Anand University .Co Founded Alpha Mindz in 2007.
                </p>
            </div>

            <!-- Rupa Sawant -->
            <div class="team-member text-center">
                <div class="member-image-wrapper">
                    <img src="<?php echo base_url('assets/images/team/rupa.jpg'); ?>" alt="Rupa Sawant">
                </div>
                <h3 class="member-name">Rupa Sawant</h3>
                <span class="member-title">SR. MANAGER BUSINESS DEVELOPMENT</span>
                <p class="member-bio">
                    BBA from Goa University and Executive MBA from GIM (Goa Institute of Management).Started career with Alpha Mindz in the year 2007. With over 12 years of experience in the field of Business Development and training.
                </p>
            </div>

            <!-- Deepa Prabhu -->
            <div class="team-member text-center">
                <div class="member-image-wrapper">
                    <img src="<?php echo base_url('assets/images/team/deepa.jpg'); ?>" alt="Deepa Prabhu">
                </div>
                <h3 class="member-name">Deepa Prabhu</h3>
                <span class="member-title">SR. MANAGER MARKETING</span>
                <p class="member-bio">
                    BBA from Goa University and Executive MBA from GIM (Goa Institute of Management).Started career with Alpha Mindz in the year 2007. With over 12 years of experience in the field of Marketing and training.
                </p>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('public_footer'); ?>
