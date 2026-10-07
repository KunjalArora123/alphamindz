<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'AlphaMindz | Empower. Inspire. Motivate.'; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('style.css?v=4'); ?>">

    <style>
    @media (max-width: 1023px) {
        .btn-download-app-desktop {
            display: none !important;
        }
    }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="nav-container top-bar-container">
            <div class="top-contact">
                <a href="tel:8308770200"><i class="ri-phone-fill"></i> Solan: 8308770200</a>
                <a href="tel:7447720000"><i class="ri-phone-fill"></i> Goa: 7447720000</a>
                <a href="mailto:info.alphamindz@gmail.com"><i class="ri-mail-fill"></i> info.alphamindz@gmail.com</a>
            </div>
            <div class="top-links">
                <a href="<?php echo site_url('about'); ?>">About Us</a>
                <a href="<?php echo site_url('reachus'); ?>">Reach Us</a>
                <a href="https://www.alphamindz.com/pay-online-form">Pay Online</a>
                
                <?php if($this->session->userdata('user_logged_in')): ?>
                    <a href="<?php echo site_url('student'); ?>"><i class="ri-user-line"></i> My Account</a>
                    <a href="<?php echo site_url('auth/logout'); ?>"><i class="ri-logout-box-line"></i> Logout</a>
                <?php else: ?>
                    <a href="<?php echo site_url('auth/login'); ?>"><i class="ri-user-line"></i> Login / Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo base_url(); ?>" class="brand" style="display: flex; align-items: center;">
                <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz" style="height: 48px; width: auto; object-fit: contain;">
            </a>
            
            <div class="nav-links">
                <a href="<?php echo base_url(); ?>" class="nav-link">Home</a>
                
                <div class="nav-item has-dropdown mega-dropdown">
                    <a href="<?php echo site_url('courses'); ?>" class="nav-link">Courses <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu mega-menu">
                        <?php 
                        $ci_header =& get_instance();
                        $ci_header->load->database();

                        // Dynamically fetch published courses directly from the 'courses' table (matching /courses page)
                        $published_courses = $ci_header->db->where('status', 'publish')
                                                          ->order_by('id', 'DESC')
                                                          ->limit(14)
                                                          ->get('courses')
                                                          ->result();

                        $half_count = ceil(count($published_courses) / 2);
                        $col1_items = array_slice($published_courses, 0, $half_count);
                        $col2_items = array_slice($published_courses, $half_count);
                        ?>
                        <div class="mega-column">
                            <?php foreach ($col1_items as $c_item): ?>
                                <a href="<?php echo site_url('courses/' . ($c_item->slug ? $c_item->slug : $c_item->id)); ?>">
                                    <?php echo htmlspecialchars(html_entity_decode($c_item->title, ENT_QUOTES, 'UTF-8')); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mega-column">
                            <?php foreach ($col2_items as $c_item): ?>
                                <a href="<?php echo site_url('courses/' . ($c_item->slug ? $c_item->slug : $c_item->id)); ?>">
                                    <?php echo htmlspecialchars(html_entity_decode($c_item->title, ENT_QUOTES, 'UTF-8')); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <a href="<?php echo site_url('blogs'); ?>" class="nav-link">Blogs</a>
                <a href="<?php echo site_url('news'); ?>" class="nav-link">News</a>

                <div class="nav-item has-dropdown">
                    <a href="<?php echo site_url('assessments'); ?>" class="nav-link">Assessment <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu">
                        <a href="#">Career Assessment</a>
                        <a href="#">Kids Interests Assessment</a>
                        <a href="#">Personality Profile</a>
                        <a href="#">Skill Assessment</a>
                    </div>
                </div>

                <div class="nav-item has-dropdown">
                    <a href="<?php echo site_url('shop'); ?>" class="nav-link">Shop <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu">
                        <a href="<?php echo site_url('shop'); ?>">E-Books</a>
                        <a href="<?php echo site_url('shop'); ?>">IELTS Achievers</a>
                        <a href="<?php echo site_url('shop'); ?>">Training & Educational Kits</a>
                    </div>
                </div>

                <a href="<?php echo site_url('verify'); ?>" class="nav-link" style="display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-shield-check-fill" style="color: var(--color-green); font-size: 1.1rem;"></i> Verify Certificate
                </a>
            </div>
            <!-- Cart Icon & CTA -->
            <?php 
              $ci =& get_instance();
              $ci->load->library('session');
              $cart = $ci->session->userdata('cart');
              $cart_count = 0;
              if (is_array($cart)) {
                  foreach ($cart as $c_item) {
                      $cart_count += (int)$c_item['qty'];
                  }
              }
            ?>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="<?php echo base_url('app-release.apk'); ?>" download class="btn-download-app btn-download-app-desktop" style="padding: 10px 20px; font-size: 14px; border-radius: 40px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; background: #27ae60; color: #fff; box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2); transition: all 0.3s;"><i class="ri-android-line" style="font-size: 1.1rem;"></i> App</a>
                <a href="<?php echo site_url('cart'); ?>" class="nav-cart-btn" style="position: relative; font-size: 1.4rem; color: #2c3e50; text-decoration: none; padding: 8px 12px; display: flex; align-items: center; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.2s;">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span class="cart-badge" id="headerCartCount" style="position: absolute; top: -6px; right: -6px; background: #e74c3c; color: #fff; font-size: 0.75rem; font-weight: 700; border-radius: 10px; padding: 2px 7px; min-width: 18px; text-align: center; box-shadow: 0 2px 4px rgba(231,76,60,0.3);"><?php echo $cart_count; ?></span>
                </a>
                
                <?php if ($ci->session->userdata('user_logged_in') || $ci->session->userdata('student_logged_in') || $ci->session->userdata('logged_in')): ?>
                    <a href="<?php echo site_url('student'); ?>" class="btn-outline" style="padding: 10px 20px; font-size: 14px; border-radius: 40px; border: 1.5px solid var(--text-main); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                        <i class="ri-user-line"></i> My Account
                    </a>
                <?php else: ?>
                    <a href="<?php echo site_url('auth/login'); ?>" class="btn-outline" style="padding: 10px 20px; font-size: 14px; border-radius: 40px; border: 1.5px solid var(--text-main); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; background: #fff;">
                        <i class="ri-user-line"></i> Login
                    </a>
                <?php endif; ?>
                <button type="button" class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="ri-menu-3-line"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Drawer -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="toggleMobileMenu()"></div>
    <div class="mobile-nav-drawer" id="mobileNavDrawer">
        <div class="mobile-nav-header">
            <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz" style="height: 32px;">
            <button class="mobile-nav-close" onclick="toggleMobileMenu()"><i class="ri-close-line"></i></button>
        </div>
        <div class="mobile-nav-links">
            <a href="<?php echo base_url(); ?>" class="mobile-nav-link">Home</a>
            <a href="<?php echo site_url('courses'); ?>" class="mobile-nav-link">Courses</a>
            <a href="<?php echo site_url('assessments'); ?>" class="mobile-nav-link">Assessments</a>
            <a href="<?php echo site_url('blogs'); ?>" class="mobile-nav-link">Blogs</a>
            <a href="<?php echo site_url('news'); ?>" class="mobile-nav-link">News</a>
            <a href="<?php echo site_url('shop'); ?>" class="mobile-nav-link">Shop</a>
            <a href="<?php echo site_url('verify'); ?>" class="mobile-nav-link">Verify Certificate</a>
            <a href="<?php echo base_url('app-release.apk'); ?>" download class="mobile-nav-link" style="color: #27ae60; font-weight: 700;"><i class="ri-android-fill"></i> Download App</a>
            <div class="mobile-nav-divider"></div>
            <a href="<?php echo site_url('about'); ?>" class="mobile-nav-link">About Us</a>
            <a href="<?php echo site_url('reachus'); ?>" class="mobile-nav-link">Reach Us</a>
            <a href="https://www.alphamindz.com/pay-online-form" class="mobile-nav-link">Pay Online</a>
            <div class="mobile-nav-divider"></div>
            <div style="font-size: 14px; color: #64748b; display: flex; flex-direction: column; gap: 8px;">
                <span><i class="ri-phone-fill"></i> Solan: 8308770200</span>
                <span><i class="ri-phone-fill"></i> Goa: 7447720000</span>
                <span><i class="ri-mail-fill"></i> info.alphamindz@gmail.com</span>
            </div>
        </div>
    </div>
    <script>
    function toggleMobileMenu() {
        document.getElementById('mobileNavOverlay').classList.toggle('active');
        document.getElementById('mobileNavDrawer').classList.toggle('active');
    }
    </script>

    <!-- Global Cart Notification Toast -->
    <div id="cartToast" style="display: none; position: fixed; bottom: 25px; right: 25px; z-index: 9999; background: #2c3e50; color: #fff; padding: 14px 24px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); font-weight: 500; font-size: 0.95rem; align-items: center; gap: 12px;">
        <i class="ri-checkbox-circle-fill" style="color: #2ecc71; font-size: 1.3rem;"></i>
        <span id="cartToastMsg">Item added to cart!</span>
        <a href="<?php echo site_url('cart'); ?>" style="color: #3498db; margin-left: 10px; text-decoration: underline; font-weight: 600;">View Cart</a>
    </div>

    <script>
    function addToCart(type, id, btnElement = null) {
        var formData = new FormData();
        formData.append('item_type', type);
        formData.append('item_id', id);
        formData.append('qty', 1);

        if (btnElement) {
            // Add a click animation effect immediately
            btnElement.style.transform = 'scale(0.95)';
            setTimeout(() => btnElement.style.transform = 'scale(1)', 150);
        }

        fetch('<?php echo site_url("cart/add"); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                var badge = document.getElementById('headerCartCount');
                if (badge) {
                    badge.innerText = data.cart_count;
                    // Pop animation on cart badge
                    badge.style.transform = 'scale(1.3)';
                    setTimeout(() => badge.style.transform = 'scale(1)', 300);
                }
                var toast = document.getElementById('cartToast');
                var msg = document.getElementById('cartToastMsg');
                if (toast && msg) {
                    msg.innerText = data.message || 'Added to cart successfully!';
                    toast.style.display = 'flex';
                    setTimeout(function() {
                        toast.style.display = 'none';
                    }, 3500);
                }

                // Cool button animation
                if (btnElement) {
                    btnElement.innerHTML = '<i class="ri-check-double-line"></i> Added to Cart!';
                    btnElement.style.backgroundColor = '#2ecc71';
                    btnElement.style.color = '#fff';
                    btnElement.style.border = 'none';
                    btnElement.style.boxShadow = '0 0 15px rgba(46, 204, 113, 0.5)';
                    // The button now stays in this "Added to Cart!" state permanently without resetting.
                }
            } else {
                alert(data.message || 'Could not add item to cart.');
            }
        })
        .catch(err => {
            console.error('Cart error:', err);
            window.location.href = '<?php echo site_url("cart"); ?>';
        });
    }
    </script>







