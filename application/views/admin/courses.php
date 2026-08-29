<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses | Admin | AlphaMindz</title>
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
        .data-card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        table th, table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-publish {
            background-color: #d4edda;
            color: #155724;
        }
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
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
            <h1>Manage Courses</h1>
            <a href="<?php echo site_url('admin/logout'); ?>" class="btn-logout"><i class="ri-logout-circle-r-line"></i> Logout</a>
        </div>

        <!-- Page Content -->
        <div class="content-container">
            <?php if($this->session->flashdata('success')): ?>
                <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 16px;">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <div class="data-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="margin: 0;">Manage Courses</h2>
                    <a href="<?php echo site_url('admin/add_course'); ?>" style="background-color: #3498db; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 4px;"><i class="ri-add-line"></i> Add New Course</a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($courses)): ?>
                            <?php foreach($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course->id; ?></td>
                                    <td><strong><?php echo $course->title; ?></strong></td>
                                    <td><?php echo $course->price ? '₹'.$course->price : 'Free'; ?></td>
                                    <td><?php echo $course->duration ? $course->duration : 'N/A'; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $course->status === 'publish' ? 'status-publish' : 'status-draft'; ?>">
                                            <?php echo ucfirst($course->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($course->created_at)); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/edit_course/'.$course->id); ?>" style="color: #3498db; text-decoration: none; margin-right: 8px;" title="Edit"><i class="ri-edit-line"></i></a>
                                        <a href="<?php echo site_url('admin/delete_course/'.$course->id); ?>" style="color: #e74c3c; text-decoration: none;" title="Delete" onclick="return confirm('Are you sure you want to delete this course?');"><i class="ri-delete-bin-line"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No courses found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
