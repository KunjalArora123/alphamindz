<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AlphaMindz</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 20px;
            background-color: #1a252f;
            text-align: center;
        }
        .sidebar-header h2 {
            margin: 0;
            font-size: 22px;
            color: #fff;
        }
        .sidebar-menu {
            flex-grow: 1;
            padding: 20px 0;
            margin: 0;
            list-style: none;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #34495e;
            color: #fff;
            border-left: 4px solid #3498db;
        }
        .sidebar-menu i {
            margin-right: 12px;
            font-size: 20px;
        }
        /* Main Content Styles */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .top-navbar {
            background-color: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .top-navbar h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .btn-logout {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #e74c3c;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
        .content-container {
            padding: 32px;
        }
        .welcome-card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>AlphaMindz</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>" class="<?php echo ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/courses'); ?>" class="<?php echo ($this->uri->segment(2) == 'courses' || $this->uri->segment(2) == 'add_course' || $this->uri->segment(2) == 'edit_course') ? 'active' : ''; ?>">
                    <i class="ri-book-open-line"></i> Courses
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/assessments'); ?>" class="<?php echo ($this->uri->segment(2) == 'assessments') ? 'active' : ''; ?>">
                    <i class="ri-survey-line"></i> Assessments
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/products'); ?>" class="<?php echo ($this->uri->segment(2) == 'products') ? 'active' : ''; ?>">
                    <i class="ri-store-2-line"></i> Shop Products
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/articles'); ?>" class="<?php echo ($this->uri->segment(2) == 'articles') ? 'active' : ''; ?>">
                    <i class="ri-article-line"></i> Articles
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/blogs'); ?>" class="<?php echo ($this->uri->segment(2) == 'blogs' || $this->uri->segment(2) == 'add_blog' || $this->uri->segment(2) == 'edit_blog') ? 'active' : ''; ?>">
                    <i class="ri-quill-pen-line"></i> Blogs
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/users'); ?>" class="<?php echo ($this->uri->segment(2) == 'users' || $this->uri->segment(2) == 'edit_user') ? 'active' : ''; ?>">
                    <i class="ri-group-line"></i> Users
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1>Admin Dashboard</h1>
            <a href="<?php echo site_url('admin/logout'); ?>" class="btn-logout"><i class="ri-logout-circle-r-line"></i> Logout</a>
        </div>

        <!-- Page Content -->
        <div class="content-container">
            <div class="welcome-card">
                <h2>Welcome back, <?php echo $this->session->userdata('username'); ?>!</h2>
                <p>You have successfully logged into the AlphaMindz admin panel.</p>
                <p>Use the sidebar on the left to navigate between different management tools. We've added a placeholder for <strong>Courses</strong> as requested.</p>
            </div>
        </div>
    </div>

</body>
</html>
