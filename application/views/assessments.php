<?php $this->load->view('public_header', ['title' => 'Assessments | AlphaMindz']); ?>

<!-- Internal Page Header -->
<section style="padding: 50px 20px 40px; text-align: center; background: radial-gradient(circle at 50% 0%, rgba(255, 113, 154, 0.05) 0%, transparent 70%); border-bottom: 1px solid var(--border);">
    <div class="section-container" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
        <span class="hero-label" style="margin-bottom: 16px; font-size: 12px; padding: 6px 16px;">Evaluate & Grow</span>
        <h1 class="hero-title" style="font-size: 48px; margin-bottom: 16px;">Assessments & <span class="text-pink">Profiling</span></h1>
        <p class="hero-subtitle" style="font-size: 16px; margin: 0 auto;">Discover your true potential with our scientifically backed assessment tools designed for students, professionals, and kids.</p>
    </div>
</section>

<!-- Assessments Listing Section -->
<section class="assessments-page">
    <div class="section-container">
        
        <!-- Category Filters -->
        <div class="category-filters">
            <button class="filter-btn active">All Assessments</button>
            <button class="filter-btn">Career</button>
            <button class="filter-btn">Kids</button>
            <button class="filter-btn">Personality</button>
            <button class="filter-btn">Skills</button>
        </div>

        <?php
          // Fetch active assessments if not passed from controller
          if (!isset($active_assessments)) {
              $ci =& get_instance();
              $ci->load->database();
              $active_assessments = $ci->db->get_where('assessments', array('status' => 'active'))->result();
          }
        ?>

        <!-- Assessments Grid -->
        <div class="assessments-grid">
            <?php if (!empty($active_assessments)): ?>
                <?php foreach ($active_assessments as $ass): ?>
                    <?php
                        // Determine visual style based on title
                        $bg_class = 'bg-blue';
                        $icon = 'ri-briefcase-4-line';
                        $banner_style = '';

                        if (stripos($ass->title, 'kids') !== false) {
                            $bg_class = 'bg-green';
                            $icon = 'ri-bear-smile-line';
                            $banner_style = 'background: linear-gradient(135deg, rgba(139, 189, 79, 0.1), rgba(48, 98, 135, 0.1)); color: var(--color-green);';
                        } elseif (stripos($ass->title, 'personality') !== false) {
                            $bg_class = 'bg-pink';
                            $icon = 'ri-user-smile-line';
                            $banner_style = 'background: linear-gradient(135deg, rgba(255, 113, 154, 0.1), rgba(139, 189, 79, 0.1)); color: var(--color-pink);';
                        } elseif (stripos($ass->title, 'leadership') !== false || stripos($ass->title, 'skill') !== false) {
                            $bg_class = 'bg-blue';
                            $icon = 'ri-lightbulb-flash-line';
                            $banner_style = 'background: linear-gradient(135deg, rgba(48, 98, 135, 0.1), rgba(255, 113, 154, 0.1)); color: var(--color-blue);';
                        }
                    ?>
                    <div class="assessment-card">
                        <div class="card-banner" style="<?php echo $banner_style; ?>">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <div class="card-body">
                            <span class="tag <?php echo $bg_class; ?>"><?php echo htmlspecialchars($ass->title); ?></span>
                            <h3><?php echo htmlspecialchars($ass->title); ?></h3>
                            <p><?php echo htmlspecialchars($ass->description ? $ass->description : 'Comprehensive assessment evaluating core skills and abilities.'); ?></p>
                            
                            <div class="card-meta">
                                <span><i class="ri-time-line"></i> <?php echo ($ass->time_limit > 0) ? $ass->time_limit : 45; ?> mins</span>
                                <span><i class="ri-question-answer-line"></i> Online Test</span>
                            </div>

                            <div class="card-footer">
                                <span class="card-price" style="font-size: 1.25rem; font-weight: 700; color: var(--color-pink);">&#8377;<?php echo number_format($ass->price > 0 ? $ass->price : 999, 0); ?></span>
                                <button type="button" onclick="addToCart('assessment', <?php echo $ass->id; ?>, this)" class="btn-primary" style="padding: 8px 20px; border: none; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="ri-shopping-cart-line"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #6c757d;">
                    <p>No assessment tests currently available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $this->load->view('public_footer'); ?>






