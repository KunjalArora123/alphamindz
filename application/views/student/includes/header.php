<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Student Portal'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-canvas: #fdf9f0;
            --ink: #111111;
            --pink: #ff3d81;
            --yellow: #ffd23f;
            --mint: #3ddc97;
            --lilac: #c8a2ff;
            --coral: #ff6b5e;
        }
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--bg-canvas);
            background-image: 
                linear-gradient(rgba(17,17,17,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(17,17,17,0.05) 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--ink);
        }
        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Archivo Black', sans-serif;
            text-transform: uppercase;
        }
        .mono-font {
            font-family: 'Space Mono', monospace;
        }
        .student-navbar {
            background-color: #ffffff;
            border-bottom: 2px solid var(--ink);
        }
        .navbar-brand img {
            height: 40px;
        }
        
        /* Utility classes for Neubrutalism */
        .card-neu {
            background: #ffffff;
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 6px 6px 0 var(--ink);
        }
        .hero-neu {
            background-color: var(--pink);
            border: 2px solid var(--ink);
            border-radius: 18px;
            box-shadow: 9px 9px 0 var(--ink);
        }
        .btn-neu {
            background-color: #ffffff;
            border: 2px solid var(--ink);
            border-radius: 9999px;
            box-shadow: 4px 4px 0 var(--ink);
            color: var(--ink) !important;
            font-weight: 700;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-neu:active {
            box-shadow: 0px 0px 0 var(--ink);
            transform: translate(4px, 4px);
        }
        .btn-neu-pink { background-color: var(--pink); }
        .btn-neu-yellow { background-color: var(--yellow); }
        .btn-neu-mint { background-color: var(--mint); }
        .btn-neu-lilac { background-color: var(--lilac); }
        
        .pill-neu {
            border: 2px solid var(--ink);
            border-radius: 9999px;
            color: var(--ink);
            font-weight: 700;
        }
        .icon-tile-neu {
            border: 2px solid var(--ink);
            border-radius: 12px;
            box-shadow: 4px 4px 0 var(--ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg student-navbar sticky-top py-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo site_url('student'); ?>">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="AlphaMindz" class="me-2">
            <span class="fs-5 fw-bold text-dark border-start border-2 border-primary ps-2 ms-1">Student Portal</span>
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#studentNavbar" aria-controls="studentNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid var(--ink) !important; border-radius: 8px; box-shadow: 2px 2px 0 var(--ink) !important;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="studentNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if($this->session->userdata('user_logged_in')): ?>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-bold text-dark hover-primary d-flex align-items-center" href="<?php echo site_url('student'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <rect x="3" y="3" width="7" height="9"></rect>
                                <rect x="14" y="3" width="7" height="5"></rect>
                                <rect x="14" y="12" width="7" height="9"></rect>
                                <rect x="3" y="16" width="7" height="5"></rect>
                            </svg> Dashboard
                        </a>
                    </li>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-bold text-dark hover-primary d-flex align-items-center" href="<?php echo site_url('assessments'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg> Assessments
                        </a>
                    </li>
                    <!-- Profile / Logout Dropdown (Simplified for mobile) -->
                    <li class="nav-item mt-3 mt-lg-0 pt-3 pt-lg-0 ms-lg-3">
                        <a class="btn-neu btn-neu-yellow px-4 py-2" href="<?php echo site_url('auth/logout'); ?>">
                            Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-lg-3">
                        <a class="nav-link fw-bold text-dark hover-primary" href="<?php echo site_url('auth/login'); ?>">
                            Login
                        </a>
                    </li>
                    <li class="nav-item mt-3 mt-lg-0 pt-3 pt-lg-0 ms-lg-3">
                        <a class="btn-neu btn-neu-mint px-4 py-2" href="<?php echo site_url('auth/register'); ?>">
                            Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
