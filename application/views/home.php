
    <!-- Hero -->
    <header class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-label">Scientific Capability Assessments</div>
                <h1 class="hero-title">Empower. Inspire.<br><span class="text-pink">Motivate.</span></h1>
                <p class="hero-subtitle">Discover personalized career counselling and industry-accredited courses designed to guide your education journey.</p>
                
                <div class="hero-cta">
                    <a href="#" class="btn-solid-blue">Start Assessment</a>
                    <a href="#" class="btn-outline"><i class="ri-play-circle-line"></i> Watch Video</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="image-wrapper">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Students collaborating">
                    <div class="floating-badge badge-1">
                        <i class="ri-medal-fill"></i>
                        <div>
                            <strong>Top Global Colleges</strong>
                            <span>Placements</span>
                        </div>
                    </div>
                    <div class="floating-badge badge-2">
                        <i class="ri-verified-badge-fill"></i>
                        <div>
                            <strong>Expert Advisors</strong>
                            <span>Verified Guidance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Showcase Video -->
    <section class="main-video-section">
        <div class="section-container">
            <div class="video-container">
                <video autoplay loop muted playsinline class="showcase-video">
                    <source src="<?php echo base_url('assets/video/index.mp4'); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="video-overlay" style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                    <button class="video-play-pause"><i class="ri-pause-fill"></i></button>
                    <button class="video-mute-unmute"><i class="ri-volume-mute-fill"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="section-container">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>12,960<span class="text-blue">+</span></h3>
                    <p>Counselling Sessions</p>
                </div>
                <div class="stat-card">
                    <h3>3,270<span class="text-green">+</span></h3>
                    <p>Active Learners</p>
                </div>
                <div class="stat-card">
                    <h3>4,240<span class="text-pink">+</span></h3>
                    <p>Seminars Hosted</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Certificate Verification Section -->
    <section class="certificate-verify-section" style="padding: 50px 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; margin: 20px 0;">
        <div class="section-container" style="max-width: 1000px; margin: 0 auto; text-align: center;">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.3); color: #2ecc71; padding: 6px 16px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; margin-bottom: 16px;">
                <i class="ri-shield-check-fill" style="font-size: 1.1rem;"></i> Instant Credential Verification
            </div>
            <h2 style="font-family: var(--font-heading); font-size: 32px; color: #fff; margin-bottom: 12px;">Verify Certificate Authenticity</h2>
            <p style="color: #94a3b8; max-width: 650px; margin: 0 auto 28px auto; font-size: 0.95rem; line-height: 1.6;">
                Validate official certificates and training credentials issued by AlphaMindz Academy using the unique Credential ID.
            </p>
            
            <form action="<?php echo site_url('verify'); ?>" method="GET" style="max-width: 580px; margin: 0 auto; display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="id" placeholder="Enter Credential ID (e.g. AMZ-2024-XXXX)" required 
                       style="flex: 1; min-width: 260px; padding: 14px 20px; border-radius: 40px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 0.95rem; outline: none;">
                <button type="submit" class="btn-solid-blue" style="padding: 14px 28px; border-radius: 40px; white-space: nowrap; display: inline-flex; align-items: center; gap: 8px; background: #3498db; border: none; cursor: pointer; font-weight: 600; font-size: 0.95rem;">
                    <i class="ri-search-line"></i> Verify Now
                </button>
            </form>
        </div>
    </section>

    <!-- Masterclass & Latest Split Section -->
    <section class="mixed-section">
        <div class="section-container mixed-grid">
            
            <!-- Left: Recent Articles -->
            <div class="mixed-left">
                <div class="section-header">
                    <h2>Recent Articles</h2>
                    <a href="<?php echo site_url('blogs'); ?>" class="view-all">View all articles <i class="ri-arrow-right-line"></i></a>
                </div>
                <div class="articles-grid">
                    <?php 
                    $fallback_articles = array(
                        array(
                            'title' => 'The Art of Communication',
                            'slug' => 'art-of-communication',
                            'category' => 'Voice Master',
                            'tag_bg' => 'bg-blue',
                            'author' => 'Voice Master',
                            'date' => 'Apr 05, 2024',
                            'excerpt' => 'Master vocal modulation, confidence, and impactful public speaking techniques for any audience.'
                        ),
                        array(
                            'title' => 'Broadcasting 101',
                            'slug' => 'broadcasting-101',
                            'category' => 'RJ Training',
                            'tag_bg' => 'bg-green',
                            'author' => 'RJ Training',
                            'date' => 'Apr 08, 2024',
                            'excerpt' => 'Essential radio jockey and podcasting skills from content creation to studio audio production.'
                        ),
                        array(
                            'title' => 'Leadership Dynamics',
                            'slug' => 'leadership-dynamics',
                            'category' => 'Executive Series',
                            'tag_bg' => 'bg-pink',
                            'author' => 'Executive Series',
                            'date' => 'Apr 12, 2024',
                            'excerpt' => 'Key strategies for leading teams, strategic decision making, and executive presence.'
                        ),
                        array(
                            'title' => 'Digital Strategy',
                            'slug' => 'digital-strategy',
                            'category' => 'Marketing',
                            'tag_bg' => 'bg-blue',
                            'author' => 'Fundamentals',
                            'date' => 'Apr 15, 2024',
                            'excerpt' => 'Core digital marketing concepts, brand positioning, and online audience engagement frameworks.'
                        )
                    );

                    $display_articles = array();
                    if (!empty($recent_articles)) {
                        $tag_bgs = array('bg-blue', 'bg-green', 'bg-pink', 'bg-blue');
                        foreach ($recent_articles as $idx => $art) {
                            $raw_excerpt = !empty($art->content) ? strip_tags($art->content) : '';
                            $excerpt = (mb_strlen($raw_excerpt) > 90) ? mb_substr($raw_excerpt, 0, 90) . '...' : ($raw_excerpt ?: 'Read our latest article insights and guidance from Alpha Mindz.');
                            $display_articles[] = array(
                                'title' => $art->title,
                                'slug' => $art->slug,
                                'category' => !empty($art->author) ? $art->author : 'Article',
                                'tag_bg' => $tag_bgs[$idx % 4],
                                'author' => !empty($art->author) ? $art->author : 'Alpha Mindz',
                                'date' => date('M d, Y', strtotime($art->created_at)),
                                'excerpt' => $excerpt
                            );
                        }
                    }
                    
                    $fallback_idx = 0;
                    while (count($display_articles) < 4 && isset($fallback_articles[$fallback_idx])) {
                        $display_articles[] = $fallback_articles[$fallback_idx];
                        $fallback_idx++;
                    }

                    foreach ($display_articles as $article):
                    ?>
                        <article class="recent-article-card">
                            <div class="recent-article-body">
                                <span class="tag <?php echo $article['tag_bg']; ?>"><?php echo htmlspecialchars($article['category']); ?></span>
                                <h3><a href="<?php echo site_url('blogs/view/' . $article['slug']); ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
                                <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            </div>
                            <div class="recent-article-meta">
                                <span><i class="ri-calendar-line"></i> <?php echo htmlspecialchars($article['date']); ?></span>
                                <span><i class="ri-user-line"></i> <?php echo htmlspecialchars($article['author']); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right: Latest With Alpha Mindz -->
            <div class="mixed-right">
                <div class="section-header">
                    <h2>Latest With Alpha Mindz</h2>
                    <a href="<?php echo site_url('news'); ?>" class="view-all">View all <i class="ri-arrow-right-line"></i></a>
                </div>
                <div class="latest-list">
                    <?php if(!empty($latest_news)): ?>
                        <?php foreach($latest_news as $news): ?>
                            <article class="latest-card">
                                <div class="latest-image">
                                    <img src="<?php echo htmlspecialchars($news->image_url); ?>" alt="Update">
                                </div>
                                <div class="latest-content">
                                    <span class="tag <?php echo htmlspecialchars($news->tag_color); ?>" style="position: static; font-size: 10px; padding: 4px 8px; margin-bottom: 8px; display: inline-block;"><?php echo htmlspecialchars($news->tag); ?></span>
                                    <h3><a href="<?php echo htmlspecialchars($news->link); ?>"><?php echo htmlspecialchars($news->title); ?></a></h3>
                                    <span class="date" style="font-size: 13px; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($news->news_date)); ?></span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No recent news available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Preview Section -->
    <section class="shop-section">
        <div class="section-container">
            <div class="section-header">
                <h2>Alpha Shop</h2>
                <a href="<?php echo site_url('shop'); ?>" class="view-all">Browse store <i class="ri-arrow-right-line"></i></a>
            </div>
            <div class="shop-grid">
                <?php if(!empty($products)): foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if($product->image_url): ?>
                            <img src="<?php echo base_url($product->image_url); ?>" alt="<?php echo htmlspecialchars($product->title); ?>" style="object-fit: contain;">
                        <?php else: ?>
                            <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #ccc; font-size: 3rem;">
                                <i class="ri-book-2-line"></i>
                            </div>
                        <?php endif; ?>
                        <span class="product-badge">Product</span>
                    </div>
                    <div class="product-info">
                        <h4><?php echo htmlspecialchars($product->title); ?></h4>
                        <p class="price" style="font-weight: 600; font-size: 1.1rem; color: #ff4757; margin-top: 8px;"><?php echo htmlspecialchars($product->price); ?></p>
                        <button type="button" onclick="addToCart('product', <?php echo $product->id; ?>, this)" class="btn-outline btn-sm" style="width: 100%; margin-top: 15px; text-align: center; display: block;">Add to Cart</button>
                    </div>
                </div>
                <?php endforeach; else: ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #6c757d; padding: 40px 0;">No products available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="section-container">
            <div class="section-header-row" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
                <div>
                    <span class="section-badge" style="background: rgba(48, 98, 135, 0.1); color: #306287; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; margin-bottom: 10px;">What Our Students Say</span>
                    <h2 class="section-title" style="margin: 0; text-align: left;">Student Testimonials</h2>
                </div>
                <div class="slider-controls" style="display: flex; align-items: center; gap: 12px;">
                    <button class="slider-nav-btn prev-btn" id="testimonialPrevBtn" aria-label="Previous Testimonials">
                        <i class="ri-arrow-left-s-line"></i>
                    </button>
                    <button class="slider-nav-btn next-btn" id="testimonialNextBtn" aria-label="Next Testimonials">
                        <i class="ri-arrow-right-s-line"></i>
                    </button>
                </div>
            </div>

            <div class="testimonials-slider-wrapper">
                <div class="testimonials-slider-track" id="testimonialSliderTrack">
                    <?php 
                    if (empty($testimonials)) {
                        $testimonials = [
                            ['author_name' => 'Shubham Samant', 'author_role' => 'UGAT Prep Student', 'avatar_initials' => 'SS', 'avatar_bg' => 'bg-blue', 'quote' => 'Hello! I am Shubham Samant and willing to answer UGAT 2020. I came to know about Alpha Mindz from my family friend. I started my regular UGAT classes under the guidance of teacher Aishwarya, who helped me a lot to get all the information about the exam. I received excellent training from teacher Aishwarya, and all my queries were solved time to time. All the students always received personal attention during the class which helped us to enhance our knowledge. It was a wonderful learning experience. Thank you!'],
                            ['author_name' => 'Megan Menezes', 'author_role' => 'Personality Development', 'avatar_initials' => 'MM', 'avatar_bg' => 'bg-pink', 'quote' => 'At Alpha Mindz, I learnt a lot of tips on how I can make the most of everyday through the personality development course. It can be very helpful for those who want to be more confident and to improve on your public speaking skills. The teachers also motivate you in that process by being very encouraging people.'],
                            ['author_name' => 'Mukund Dravid', 'author_role' => 'Parent (Spoken English & IELTS)', 'avatar_initials' => 'MD', 'avatar_bg' => 'bg-green', 'quote' => 'My son has been a student at Alpha Mindz. He has completed his Spoken English and IELTS coaching under the valuable Guidance of the Alpha Mindz team. Their highly qualified team and most professional minds in the field assure and deliver you the best of results. The encouragement and support offered by them proves to be precious for the entire future of a student. Appreciate the efforts by Manu Sir and his entire Team. (Alisha, Rupa, Aishwarya, Giselle and Sukanya Mam)'],
                            ['author_name' => 'Juned Momin', 'author_role' => 'Student', 'avatar_initials' => 'JM', 'avatar_bg' => 'bg-purple', 'quote' => 'Alpha Mindz teachers are giving me good knowledge thanks to all staff.'],
                            ['author_name' => 'Nesha Rocks', 'author_role' => 'Student', 'avatar_initials' => 'NR', 'avatar_bg' => 'bg-amber', 'quote' => 'It was wonderful experience learning with Alpha Mindz well done guys.'],
                            ['author_name' => 'Sandesh Agarwadekar', 'author_role' => 'Professional Training', 'avatar_initials' => 'SA', 'avatar_bg' => 'bg-teal', 'quote' => 'It is a very professional training center. Have excellent faculties.'],
                            ['author_name' => 'Sangita Amonker', 'author_role' => 'Spoken English Student', 'avatar_initials' => 'SA', 'avatar_bg' => 'bg-indigo', 'quote' => 'This is the best class which I joined for English.'],
                            ['author_name' => 'Rohan Rolt', 'author_role' => 'Personality Development', 'avatar_initials' => 'RR', 'avatar_bg' => 'bg-blue', 'quote' => 'Alpha Mindz is the best place for personality development. The Faculty know how to help you very well based on your personality.'],
                            ['author_name' => 'Tushar Khandeparkar', 'author_role' => 'Spoken English Student', 'avatar_initials' => 'TK', 'avatar_bg' => 'bg-green', 'quote' => 'Alpha Mindz institution is one of the best institutions for learning. It helped me to increase my speaking confidence level & also motivate me a lot.'],
                            ['author_name' => 'Raj Shukla', 'author_role' => 'Spoken English Student', 'avatar_initials' => 'RS', 'avatar_bg' => 'bg-pink', 'quote' => "It's a very good work of my teacher and I think that Alpha Mindz classes are very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good."],
                            ['author_name' => 'Gopi Pandit', 'author_role' => 'IELTS Preparation', 'avatar_initials' => 'GP', 'avatar_bg' => 'bg-purple', 'quote' => 'I attended the IELTS classes at Alpha Mindz. My faculty conducted the classes and provided great insights for the preparation. Alpha Mindz made us familiar with the actual test format which helped me gain confidence in the test day. The study material is comprehensive. The classes were very helpful. I want to tell big thanks to Alisha, Deepa and Roopa who helped me to clear my IELTS with good band.'],
                            ['author_name' => 'Leon Scott', 'author_role' => 'IELTS Crash Course', 'avatar_initials' => 'LS', 'avatar_bg' => 'bg-amber', 'quote' => 'Excellent, took the 1 week crash course (went 4 times) and got 7.5 in IELTS, the one to one classes makes all the difference.'],
                            ['author_name' => 'Mukesh Suthar', 'author_role' => 'Counselling Client', 'avatar_initials' => 'MS', 'avatar_bg' => 'bg-teal', 'quote' => "Best counsellor in Panjim.... it's near Vivanta in Panjim Goa."],
                            ['author_name' => 'Arushi Naik', 'author_role' => 'IELTS Student', 'avatar_initials' => 'AN', 'avatar_bg' => 'bg-indigo', 'quote' => 'Had a wonderful experience with Alpha Mindz, great place to training yourself for IELTS. It has very helpful and informative trainers, especially Alisha. Thanks for sharing your knowledge.'],
                            ['author_name' => 'Rebekah Philip', 'author_role' => 'IELTS & TOEFL Prep', 'avatar_initials' => 'RP', 'avatar_bg' => 'bg-blue', 'quote' => 'Alpha Mindz has helped me immensely. The faculty is very accommodating and helpful. The schedule is flexible and catered to your convenience. Alpha Mindz made my IELTS exam preparation stress free and easy. I recommend it to anyone seeking help with the same.'],
                            ['author_name' => 'Swathi K', 'author_role' => 'TOEFL Student', 'avatar_initials' => 'SK', 'avatar_bg' => 'bg-pink', 'quote' => 'Alpha Mindz has the most helpful and amazing teachers. They have helped me get the required score in my TOEFL exam for foreign education.'],
                            ['author_name' => 'Purva Kinalekar', 'author_role' => 'Confidence Coaching', 'avatar_initials' => 'PK', 'avatar_bg' => 'bg-green', 'quote' => 'Alpha Mindz is a great place to help infiltrate confidence within you. Every trainer is exceptionally helpful and skilled. The programs are very well integrated so as to provide a whole experience.'],
                            ['author_name' => 'Abigail Fernandes', 'author_role' => 'IELTS & Spoken English', 'avatar_initials' => 'AF', 'avatar_bg' => 'bg-purple', 'quote' => 'Alpha Mindz provides excellent coaching for IELTS, spoken English and other coaching for children as well. They have friendly and cooperative staff.'],
                            ['author_name' => 'Vinod Vijayan', 'author_role' => 'IELTS Coaching Student', 'avatar_initials' => 'VV', 'avatar_bg' => 'bg-amber', 'quote' => 'Alpha Mindz was an excellent experience for me in terms of IELTS coaching. Their professional approach in training helped me succeed in the exam. All tutors and the entire team were very supportive and helpful, right from registration to course completion. Keep up your marvellous work Alpha Mindz team.'],
                            ['author_name' => 'Sashikant Shukla', 'author_role' => 'Spoken English Student', 'avatar_initials' => 'SS', 'avatar_bg' => 'bg-teal', 'quote' => "It's a very good work of my teacher and I think that Alpha Mindz classes is very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good."],
                            ['author_name' => 'Manasi Talaulikar', 'author_role' => 'Career Assessment Client', 'avatar_initials' => 'MT', 'avatar_bg' => 'bg-indigo', 'quote' => 'Alpha Mindz is a great place for learning as the staff is very professional and customer oriented. The courses here aim at proper learning of the concepts rather than finishing it faster. They provide you with a detailed report after your aptitude test which helps you better understand yourself and choose a career that is best suited for you.']
                        ];
                    }

                    foreach ($testimonials as $t): 
                        $t = (array) $t;
                        $bg_class = !empty($t['avatar_bg']) ? $t['avatar_bg'] : 'bg-blue';
                    ?>
                        <div class="testimonial-card">
                            <i class="ri-double-quotes-l quote-icon"></i>
                            <p class="quote">"<?php echo htmlspecialchars(trim($t['quote'], '"')); ?>"</p>
                            <div class="author">
                                <div class="avatar <?php echo htmlspecialchars($bg_class); ?>">
                                    <?php echo htmlspecialchars($t['avatar_initials']); ?>
                                </div>
                                <div>
                                    <h4><?php echo htmlspecialchars($t['author_name']); ?></h4>
                                    <span><?php echo htmlspecialchars($t['author_role']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="slider-dots" id="testimonialSliderDots" style="display: flex; justify-content: center; gap: 8px; margin-top: 30px;"></div>
        </div>
    </section>

    <!-- Expanded Multi-column Footer -->





