    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header" style="text-align: center; padding: 16px 10px;">
            <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz" style="height: 38px; width: auto; background: #ffffff; padding: 4px 10px; border-radius: 6px;">
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
                <a href="<?php echo site_url('admin/manage_assessments'); ?>" class="<?php echo (in_array($this->uri->segment(2), ['manage_assessments', 'add_assessment', 'edit_assessment', 'manage_questions', 'edit_question'])) ? 'active' : ''; ?>">
                    <i class="ri-file-list-3-line"></i> Manage Assessments
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/assessments'); ?>" class="<?php echo ($this->uri->segment(2) == 'assessments') ? 'active' : ''; ?>">
                    <i class="ri-shield-keyhole-line"></i> Student Test Access
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/appeared_tests'); ?>" class="<?php echo ($this->uri->segment(2) == 'appeared_tests') ? 'active' : ''; ?>">
                    <i class="ri-file-paper-2-line"></i> Appeared Tests
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/products'); ?>" class="<?php echo ($this->uri->segment(2) == 'products') ? 'active' : ''; ?>">
                    <i class="ri-store-2-line"></i> Shop Products
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/orders'); ?>" class="<?php echo ($this->uri->segment(2) == 'orders') ? 'active' : ''; ?>">
                    <i class="ri-shopping-bag-3-line"></i> Manage Orders
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/certificates'); ?>" class="<?php echo ($this->uri->segment(2) == 'certificates') ? 'active' : ''; ?>">
                    <i class="ri-award-line"></i> Certification
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/book_delivery'); ?>" class="<?php echo ($this->uri->segment(2) == 'book_delivery') ? 'active' : ''; ?>">
                    <i class="ri-book-read-line"></i> Book Delivery
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

    <!-- Main Wrapper Start -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1><?php echo isset($page_title) ? $page_title : 'Admin Dashboard'; ?></h1>
            <a href="<?php echo site_url('admin/logout'); ?>" class="btn-logout"><i class="ri-logout-circle-r-line"></i> Logout</a>
        </div>

        <!-- Page Content Start -->
        <div class="content-container">
            <?php if($this->session->flashdata('success')): ?>
                <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 16px;">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
