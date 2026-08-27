    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-about">
                    <img src="https://www.alphamindz.com/wp-content/uploads/2024/12/cropped-alpha-mindz-logo-1-270x270.png'); ?>" alt="AlphaMindz Logo" style="height: 50px; margin-bottom: 24px; filter: brightness(0) invert(1);">
                    <p>Empowering the next generation of global leaders through scientific capability assessments and personalized career counselling.</p>
                    <div class="social-links" style="margin-top: 24px; font-size: 20px; display: flex; gap: 16px;">
                        <a href="https://twitter.com/mentorsbasket"><i class="ri-twitter-fill"></i></a>
                        <a href="https://www.instagram.com/mentorsbasket/"><i class="ri-instagram-fill"></i></a>
                        <a href="https://www.facebook.com/mentorsbasket"><i class="ri-facebook-fill"></i></a>
                        <a href="https://www.youtube.com/channel/UCN8ds9k4o9Su7jyqF1GFYaw"><i class="ri-youtube-fill"></i></a>
                    </div>
                </div>
                
                <div class="footer-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="<?php echo site_url('courses'); ?>">Our Courses</a></li>
                        <li><a href="#">Free Resources</a></li>
                        <li><a href="#">Alpha Shop</a></li>
                        <li><a href="#">Login / Register</a></li>
                    </ul>
                </div>

                <div class="footer-widget">
                    <h3>Legal & Utility</h3>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Refund Policy</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>

                <div class="footer-widget">
                    <h3>Contact Us</h3>
                    <ul class="contact-list">
                        <li><i class="ri-map-pin-2-fill text-pink"></i> 123 Education Hub, Knowledge City</li>
                        <li><i class="ri-phone-fill text-green"></i> +91 830 800 0200</li>
                        <li><i class="ri-mail-fill text-blue"></i> info@alphamindz.com</li>
                        <li><i class="ri-time-fill"></i> Mon-Sat: 9:00 AM - 6:00 PM</li>
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
        });
    </script>
</body>
</html>
