<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'AlphaMindz | Empower. Inspire. Motivate.'; ?></title>
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('style.css?v=4'); ?>">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="nav-container top-bar-container">
            <div class="top-contact">
                <a href="#"><i class="ri-phone-fill"></i> +91 830 800 0200</a>
                <a href="#"><i class="ri-mail-fill"></i> info@alphamindz.com</a>
            </div>
            <div class="top-links">
                <a href="<?php echo site_url('about'); ?>">About Us</a>
                <a href="https://www.alphamindz.com/reachus">Reach Us</a>
                <a href="https://www.alphamindz.com/pay-online-form">Pay Online</a>
                <a href="#"><i class="ri-user-line"></i> Login / Register</a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="https://www.alphamindz.com" class="brand" style="display: flex; align-items: center;">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="AlphaMindz" style="height: 48px; width: auto; object-fit: contain;">
            </a>
            
            <div class="nav-links">
                <a href="#" class="nav-link">Home</a>
                
                <div class="nav-item has-dropdown mega-dropdown">
                    <a href="<?php echo site_url('courses'); ?>" class="nav-link">Courses <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu mega-menu">
                        <div class="mega-column">
                            <a href="#">Achievers for Children</a>
                            <a href="#">Alpha Talent</a>
                            <a href="#">Corporate Trainings</a>
                            <a href="#">Entrance Exam Prep</a>
                            <a href="#">IELTS</a>
                            <a href="#">Lean Six Sigma</a>
                            <a href="#">Mentoring Programs</a>
                        </div>
                        <div class="mega-column">
                            <a href="#">Management Dev Programs</a>
                            <a href="#">Personality Development</a>
                            <a href="#">Spoken English</a>
                            <a href="#">Supervisor Skills</a>
                            <a href="#">Train the Trainer</a>
                            <a href="#">Radio Jockey (RJ)</a>
                            <a href="#">Voice Master</a>
                        </div>
                    </div>
                </div>

                <a href="#" class="nav-link">Counselling Scope</a>

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
                    <a href="#" class="nav-link">Shop <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu">
                        <a href="#">E-Books</a>
                        <a href="#">IELTS Achievers</a>
                        <a href="#">Training & Educational Kits</a>
                    </div>
                </div>

                <div class="nav-item has-dropdown">
                    <a href="#" class="nav-link">Free Resources <i class="ri-arrow-down-s-line"></i></a>
                    <div class="dropdown-menu">
                        <a href="#">Blogs</a>
                        <a href="#">Articles</a>
                        <a href="#">Career Facts</a>
                        <a href="#">IELTS Download</a>
                    </div>
                </div>
            </div>
            <!-- Extra CTA (Optional, can be hidden on small screens) -->
            <a href="#" class="btn-primary">Take the Test <i class="ri-arrow-right-line"></i></a>
        </div>
    </nav>
