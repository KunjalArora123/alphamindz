    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-about">
                    <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz Logo" style="height: 55px; width: auto; background: #ffffff; padding: 6px 12px; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                    <p>Empowering the next generation of global leaders through scientific capability assessments and personalized career counselling.</p>
                    <div class="social-links" style="margin-top: 24px; font-size: 20px; display: flex; gap: 16px;">
                        <a href="https://www.facebook.com/share/1F929ZPEeC/" target="_blank" title="Facebook" rel="noopener noreferrer"><i class="ri-facebook-fill"></i></a>
                        <a href="https://www.instagram.com/alphamindzsolan?stkn=M3VsZjV3Y3czbnl4" target="_blank" title="Instagram - Solan" rel="noopener noreferrer"><i class="ri-instagram-fill"></i></a>
                        <a href="https://www.instagram.com/alphamindz.india?stkn=bTlwdjJ3MWwwc2Qy" target="_blank" title="Instagram - India" rel="noopener noreferrer"><i class="ri-instagram-fill"></i></a>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo site_url('about'); ?>">About Us</a></li>
                        <li><a href="<?php echo site_url('courses'); ?>">Our Courses</a></li>
                        <li><a href="<?php echo site_url('verify'); ?>">Verify Certificate</a></li>
                        <li><a href="<?php echo site_url('shop'); ?>">Alpha Shop</a></li>
                        <li><a href="<?php echo site_url('auth/login'); ?>">Login / Register</a></li>
                    </ul>
                </div>

                <div class="footer-widget" style="max-width: 380px;">
                    <h3>Our Branches</h3>
                    <div style="margin-bottom: 14px;">
                        <h4 style="font-size: 0.95rem; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <i class="ri-map-pin-2-fill text-pink"></i> Solan Branch (HP)
                        </h4>
                        <p style="font-size: 0.85rem; color: #a0aec0; margin: 0 0 4px 22px; line-height: 1.4;">
                            First Floor, Satish Complex, Kotla Nala, Solan, Himachal Pradesh 173212
                        </p>
                        <a href="https://www.google.com/maps/place/First+Floor,+Alpha+Mindz+Solan,+Satish+Complex,+Kotla+Nala,+Solan,+Himachal+Pradesh+173212/data=!4m2!3m1!1s0x390f8138573242bb:0x1e78d5e86a03c187!18m1!1e1?utm_source=mstt_1&entry=gps&coh=192189&g_ep=CAESBzI2LjM4LjEYACCenQoqvQEsOTQyNjc3MjcsOTQyOTIxOTUsOTQyOTk1MzIsMTAwNzk2NDk4LDEwMDc5Nzc2MSwxMDA3OTY1MzUsOTQyODA1NzYsOTQyMDczOTQsOTQyMDc1MDYsOTQyMDg1MDYsOTQyMTg2NTMsOTQyMjk4MzksOTQyNzUxNjgsOTQyNzk2MTksMTAwODM1NzEwLDEwMDgyNTAyNSwxMDA8MjAyMzcsMTAwODIyNDk4LDEwMDgyNzk3NSwxMDA8MzgzNTVCAklO&skid=2b3d107f-431e-4815-a371-6f43a8cd9a18&g_st=aw" target="_blank" rel="noopener noreferrer" style="font-size: 0.8rem; color: #3498db; margin-left: 22px; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-map-pin-line"></i> View on Google Maps
                        </a>
                    </div>
                    <div style="margin-bottom: 14px;">
                        <h4 style="font-size: 0.95rem; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <i class="ri-map-pin-2-fill text-green"></i> Goa Branch (Panaji)
                        </h4>
                        <p style="font-size: 0.85rem; color: #a0aec0; margin: 0 0 4px 22px; line-height: 1.4;">
                            302, Sai Plaza, opp. Gomantak Press, St. Inez, Altinho, Panaji, Goa 403001
                        </p>
                        <a href="https://maps.app.goo.gl/orfvmi45fKQES2Mx6?g_st=aw" target="_blank" rel="noopener noreferrer" style="font-size: 0.8rem; color: #3498db; margin-left: 22px; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="ri-map-pin-line"></i> View on Google Maps
                        </a>
                    </div>
                    <ul class="contact-list" style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                        <li><i class="ri-phone-fill text-green"></i> Solan: 8308770200</li>
                        <li><i class="ri-phone-fill text-green"></i> Goa: 7447720000</li>
                        <li><i class="ri-mail-fill text-blue"></i> info@alphamindz.com</li>
                        <li><i class="ri-time-fill"></i> Mon To Sat 11AM - 7PM</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 AlphaMindz. All Rights Reserved.</p>
                <p>Developed By PrismLogic</p>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.article-card, .testimonial-card, .video-card, .stat-card, .product-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                observer.observe(el);
            });

            // Video Play/Pause toggle
            const showcaseVideo = document.querySelector('.showcase-video');
            const playPauseBtn = document.querySelector('.video-play-pause');
            const playPauseIcon = playPauseBtn?.querySelector('i');

            if (playPauseBtn && showcaseVideo) {
                playPauseBtn.addEventListener('click', () => {
                    if (showcaseVideo.paused) {
                        showcaseVideo.play();
                        playPauseIcon.className = 'ri-pause-fill';
                    } else {
                        showcaseVideo.pause();
                        playPauseIcon.className = 'ri-play-fill';
                    }
                });
            }

            // Video Mute/Unmute toggle
            const muteUnmuteBtn = document.querySelector('.video-mute-unmute');
            const muteUnmuteIcon = muteUnmuteBtn?.querySelector('i');

            if (muteUnmuteBtn && showcaseVideo) {
                muteUnmuteBtn.addEventListener('click', () => {
                    if (showcaseVideo.muted) {
                        showcaseVideo.muted = false;
                        muteUnmuteIcon.className = 'ri-volume-up-fill';
                    } else {
                        showcaseVideo.muted = true;
                        muteUnmuteIcon.className = 'ri-volume-mute-fill';
                    }
                });
            }

            // Testimonials Slider Logic
            const track = document.getElementById('testimonialSliderTrack');
            const prevBtn = document.getElementById('testimonialPrevBtn');
            const nextBtn = document.getElementById('testimonialNextBtn');
            const dotsContainer = document.getElementById('testimonialSliderDots');

            if (track && prevBtn && nextBtn && dotsContainer) {
                const cards = track.querySelectorAll('.testimonial-card');
                let currentIndex = 0;
                let autoPlayInterval = null;

                function getCardsPerView() {
                    return 1;
                }

                function getMaxIndex() {
                    const perView = getCardsPerView();
                    return Math.max(0, cards.length - perView);
                }

                function createDots() {
                    dotsContainer.innerHTML = '';
                    const perView = getCardsPerView();
                    const totalPages = Math.ceil(cards.length / perView);

                    for (let i = 0; i < totalPages; i++) {
                        const dot = document.createElement('div');
                        dot.className = 'slider-dot' + (i === Math.floor(currentIndex / perView) ? ' active' : '');
                        dot.addEventListener('click', () => {
                            currentIndex = Math.min(i * perView, getMaxIndex());
                            updateSlider();
                            resetAutoPlay();
                        });
                        dotsContainer.appendChild(dot);
                    }
                }

                function updateSlider() {
                    const maxIdx = getMaxIndex();
                    if (currentIndex > maxIdx) currentIndex = maxIdx;
                    if (currentIndex < 0) currentIndex = 0;

                    const perView = getCardsPerView();
                    const gap = 30;
                    const cardWidth = cards[0] ? cards[0].getBoundingClientRect().width : 0;
                    const moveAmount = (cardWidth + gap) * currentIndex;

                    track.style.transform = `translateX(-${moveAmount}px)`;

                    prevBtn.disabled = (currentIndex === 0);
                    nextBtn.disabled = (currentIndex >= maxIdx);

                    const dots = dotsContainer.querySelectorAll('.slider-dot');
                    const activePageIndex = Math.floor(currentIndex / perView);
                    dots.forEach((dot, idx) => {
                        if (idx === activePageIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }

                prevBtn.addEventListener('click', () => {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                        resetAutoPlay();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    const maxIdx = getMaxIndex();
                    if (currentIndex < maxIdx) {
                        currentIndex++;
                    } else {
                        currentIndex = 0;
                    }
                    updateSlider();
                    resetAutoPlay();
                });

                function startAutoPlay() {
                    stopAutoPlay();
                    autoPlayInterval = setInterval(() => {
                        const maxIdx = getMaxIndex();
                        if (currentIndex < maxIdx) {
                            currentIndex++;
                        } else {
                            currentIndex = 0;
                        }
                        updateSlider();
                    }, 5000);
                }

                function stopAutoPlay() {
                    if (autoPlayInterval) clearInterval(autoPlayInterval);
                }

                function resetAutoPlay() {
                    stopAutoPlay();
                    startAutoPlay();
                }

                track.parentElement.addEventListener('mouseenter', stopAutoPlay);
                track.parentElement.addEventListener('mouseleave', startAutoPlay);

                let startX = 0;
                let isDragging = false;

                track.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                    stopAutoPlay();
                }, { passive: true });

                track.addEventListener('touchend', (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    const endX = e.changedTouches[0].clientX;
                    const diffX = startX - endX;
                    if (Math.abs(diffX) > 50) {
                        if (diffX > 0 && currentIndex < getMaxIndex()) {
                            currentIndex++;
                        } else if (diffX < 0 && currentIndex > 0) {
                            currentIndex--;
                        }
                        updateSlider();
                    }
                    startAutoPlay();
                });

                window.addEventListener('resize', () => {
                    createDots();
                    updateSlider();
                });

                createDots();
                updateSlider();
                startAutoPlay();
            }
        });
    </script>
</body>
</html>
