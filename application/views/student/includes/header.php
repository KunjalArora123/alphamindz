<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo isset($title) ? $title : 'Student Portal'; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sp-blue: rgb(48, 98, 135);        /* Primary Navy Blue */
            --sp-blue-dark: rgb(34, 72, 100);
            --sp-blue-light: rgb(235, 242, 247);
            --sp-green: rgb(131, 187, 77);      /* Accent Leaf Green */
            --sp-green-dark: rgb(105, 155, 58);
            --sp-green-light: rgb(242, 249, 236);
            --sp-bg: #f4f7f9;
            --sp-text: #1e293b;
            --sp-text-muted: #64748b;
            --sp-card-bg: #ffffff;
            --sp-radius: 14px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--sp-bg);
            color: var(--sp-text);
            padding-bottom: 70px; /* Space for mobile bottom bar */
        }
        @media (min-width: 992px) {
            body {
                padding-bottom: 0;
            }
        }

        /* Student Mobile First Navbar */
        .student-navbar {
            background-color: var(--sp-blue);
            box-shadow: 0 4px 12px rgba(48, 98, 135, 0.15);
        }
        .student-navbar .navbar-brand {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.1rem;
        }
        .student-navbar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .student-navbar .nav-link:hover, .student-navbar .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Mobile First Buttons & Cards */
        .btn-sp-primary {
            background-color: var(--sp-blue);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 10px;
            padding: 12px 20px;
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            box-shadow: 0 4px 10px rgba(48, 98, 135, 0.2);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-sp-primary:hover, .btn-sp-primary:focus {
            background-color: var(--sp-blue-dark);
            transform: translateY(-1px);
        }

        .btn-sp-accent {
            background-color: var(--sp-green);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 10px;
            padding: 12px 20px;
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            box-shadow: 0 4px 10px rgba(131, 187, 77, 0.25);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-sp-accent:hover, .btn-sp-accent:focus {
            background-color: var(--sp-green-dark);
            transform: translateY(-1px);
        }

        .btn-sp-outline {
            background-color: transparent;
            color: var(--sp-blue) !important;
            border: 2px solid var(--sp-blue);
            font-weight: 700;
            border-radius: 10px;
            padding: 10px 18px;
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-sp-outline:hover {
            background-color: var(--sp-blue-light);
        }

        .card-sp {
            background: var(--sp-card-bg);
            border-radius: var(--sp-radius);
            border: 1px solid rgba(48, 98, 135, 0.1);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            padding: 1.25rem;
        }
        @media (min-width: 768px) {
            .card-sp {
                padding: 1.75rem;
            }
        }

        .pill-sp-blue {
            background-color: var(--sp-blue-light);
            color: var(--sp-blue);
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        .pill-sp-green {
            background-color: var(--sp-green-light);
            color: var(--sp-green-dark);
            font-weight: 700;
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* Mobile Bottom Nav Bar */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 1050;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.05);
        }
        .mobile-bottom-nav .nav-item {
            text-align: center;
            color: var(--sp-text-muted);
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .mobile-bottom-nav .nav-item i {
            font-size: 1.35rem;
            margin-bottom: 2px;
        }
        .mobile-bottom-nav .nav-item.active {
            color: var(--sp-blue);
            font-weight: 800;
        }

        /* Utility Helpers */
        .bg-sp-blue { background-color: var(--sp-blue) !important; }
        .bg-sp-green { background-color: var(--sp-green) !important; }
        .text-sp-blue { color: var(--sp-blue) !important; }
        .text-sp-green { color: var(--sp-green) !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg student-navbar sticky-top py-2">
    <div class="container">
        <!-- Brand / Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?php echo site_url('student'); ?>">
            <img src="<?php echo base_url('assets/images/logo.png?v='.time()); ?>" alt="AlphaMindz" style="height: 38px; width: auto; background: #ffffff; padding: 4px 10px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);" class="me-2">
            <span class="badge" style="background-color: var(--sp-green); color: #fff; font-size: 0.7rem;">STUDENT PORTAL</span>
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 text-white p-2" type="button" data-bs-toggle="collapse" data-bs-target="#studentNavbar" aria-controls="studentNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-3-line fs-3"></i>
        </button>

        <!-- Navbar Links for Desktop / Collapsible -->
        <div class="collapse navbar-collapse" id="studentNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center mt-3 mt-lg-0">
                <?php if($this->session->userdata('user_logged_in')): ?>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link d-flex align-items-center" href="<?php echo site_url('student'); ?>">
                            <i class="ri-dashboard-line me-1 fs-5"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link d-flex align-items-center" href="<?php echo site_url('courses'); ?>">
                            <i class="ri-book-open-line me-1 fs-5"></i> Courses
                        </a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link d-flex align-items-center" href="<?php echo site_url('assessments'); ?>">
                            <i class="ri-task-line me-1 fs-5"></i> Assessments
                        </a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2">
                        <a class="btn btn-sm btn-sp-accent text-white px-3" href="<?php echo site_url('auth/logout'); ?>">
                            <i class="ri-logout-box-r-line me-1"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-lg-2">
                        <a class="nav-link" href="<?php echo site_url('auth/login'); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-sp-accent text-white px-3" href="<?php echo site_url('auth/register'); ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
