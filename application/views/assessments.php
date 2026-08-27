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
        
        <!-- Category Filters (Mobile Scrollable) -->
        <div class="category-filters">
            <button class="filter-btn active">All Assessments</button>
            <button class="filter-btn">Career</button>
            <button class="filter-btn">Kids</button>
            <button class="filter-btn">Personality</button>
            <button class="filter-btn">Skills</button>
        </div>

        <!-- Assessments Grid -->
        <div class="assessments-grid">
            
            <!-- Assessment Card 1 -->
            <div class="assessment-card">
                <div class="card-banner">
                    <i class="ri-briefcase-4-line"></i>
                </div>
                <div class="card-body">
                    <span class="tag bg-blue">Career</span>
                    <h3>Student Career Assessment</h3>
                    <p>Find the perfect career path based on your strengths, interests, and personality traits.</p>
                    
                    <div class="card-meta">
                        <span><i class="ri-time-line"></i> 45 mins</span>
                        <span><i class="ri-question-answer-line"></i> 60 Qs</span>
                    </div>

                    <div class="card-footer">
                        <span class="card-price">₹999</span>
                        <a href="#" class="btn-primary" style="padding: 8px 20px;">Enroll Now</a>
                    </div>
                </div>
            </div>

            <!-- Assessment Card 2 -->
            <div class="assessment-card">
                <div class="card-banner" style="background: linear-gradient(135deg, rgba(139, 189, 79, 0.1), rgba(48, 98, 135, 0.1)); color: var(--color-green);">
                    <i class="ri-bear-smile-line"></i>
                </div>
                <div class="card-body">
                    <span class="tag bg-green">Kids</span>
                    <h3>Kids Interests Assessment</h3>
                    <p>Discover your child's innate talents and inclinations to guide their extracurricular activities.</p>
                    
                    <div class="card-meta">
                        <span><i class="ri-time-line"></i> 30 mins</span>
                        <span><i class="ri-question-answer-line"></i> 40 Qs</span>
                    </div>

                    <div class="card-footer">
                        <span class="card-price">₹599</span>
                        <a href="#" class="btn-primary" style="padding: 8px 20px;">Enroll Now</a>
                    </div>
                </div>
            </div>

            <!-- Assessment Card 3 -->
            <div class="assessment-card">
                <div class="card-banner" style="background: linear-gradient(135deg, rgba(255, 113, 154, 0.1), rgba(139, 189, 79, 0.1)); color: var(--color-pink);">
                    <i class="ri-user-smile-line"></i>
                </div>
                <div class="card-body">
                    <span class="tag bg-pink">Personality</span>
                    <h3>Personality Profile</h3>
                    <p>Gain deep insights into your behavioral patterns and interpersonal dynamics.</p>
                    
                    <div class="card-meta">
                        <span><i class="ri-time-line"></i> 60 mins</span>
                        <span><i class="ri-question-answer-line"></i> 100 Qs</span>
                    </div>

                    <div class="card-footer">
                        <span class="card-price">₹1,499</span>
                        <a href="#" class="btn-primary" style="padding: 8px 20px;">Enroll Now</a>
                    </div>
                </div>
            </div>

            <!-- Assessment Card 4 -->
            <div class="assessment-card">
                <div class="card-banner" style="background: linear-gradient(135deg, rgba(48, 98, 135, 0.1), rgba(255, 113, 154, 0.1)); color: var(--color-blue);">
                    <i class="ri-lightbulb-flash-line"></i>
                </div>
                <div class="card-body">
                    <span class="tag bg-blue">Skills</span>
                    <h3>Leadership Skill Assessment</h3>
                    <p>Evaluate your leadership capabilities and identify areas for professional growth.</p>
                    
                    <div class="card-meta">
                        <span><i class="ri-time-line"></i> 40 mins</span>
                        <span><i class="ri-question-answer-line"></i> 50 Qs</span>
                    </div>

                    <div class="card-footer">
                        <span class="card-price">₹1,299</span>
                        <a href="#" class="btn-primary" style="padding: 8px 20px;">Enroll Now</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php $this->load->view('public_footer'); ?>
