    <!-- Superadmin Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?php echo base_url('assets/images/logo.png?v='.time()); ?>" alt="AlphaMindz" style="height: 38px; width: auto; background: #ffffff; padding: 4px 10px; border-radius: 6px;">
            <div>
                <span class="super-tag"><i class="ri-shield-flash-line"></i> Superadmin Control</span>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo site_url('superadmin/dashboard'); ?>" class="<?php echo ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                    <i class="ri-dashboard-3-line"></i> Control Panel
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('superadmin/users'); ?>" class="<?php echo ($this->uri->segment(2) == 'users') ? 'active' : ''; ?>">
                    <i class="ri-user-settings-line"></i> Manage Users & Roles
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('superadmin/logs'); ?>" class="<?php echo ($this->uri->segment(2) == 'logs') ? 'active' : ''; ?>">
                    <i class="ri-history-line"></i> Admin Login Logs
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('superadmin/website_access'); ?>" class="<?php echo ($this->uri->segment(2) == 'website_access') ? 'active' : ''; ?>">
                    <i class="ri-toggle-line"></i> Website Access
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('superadmin/branding'); ?>" class="<?php echo ($this->uri->segment(2) == 'branding') ? 'active' : ''; ?>">
                    <i class="ri-palette-line"></i> Site Branding
                </a>
            </li>
            <li style="margin-top: 15px; padding: 0 24px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700;">
                Standard Modules
            </li>
            <li>
                <a href="<?php echo site_url('admin/courses'); ?>" target="_blank">
                    <i class="ri-book-open-line"></i> Courses Module <i class="ri-external-link-line" style="margin-left: auto; font-size: 0.9rem;"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/manage_assessments'); ?>" target="_blank">
                    <i class="ri-file-list-3-line"></i> Assessments Module <i class="ri-external-link-line" style="margin-left: auto; font-size: 0.9rem;"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/orders'); ?>" target="_blank">
                    <i class="ri-shopping-bag-3-line"></i> Orders Module <i class="ri-external-link-line" style="margin-left: auto; font-size: 0.9rem;"></i>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>" target="_blank">
                    <i class="ri-layout-grid-line"></i> Standard Admin Panel <i class="ri-external-link-line" style="margin-left: auto; font-size: 0.9rem;"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Wrapper Start -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1><?php echo isset($page_title) ? $page_title : 'Superadmin Control Center'; ?></h1>
            <div style="display: flex; align-items: center;">
                <a href="<?php echo site_url('admin/dashboard'); ?>" class="btn-admin-switch"><i class="ri-admin-line"></i> Switch to Admin Panel</a>
                <a href="<?php echo site_url('superadmin/logout'); ?>" class="btn-logout"><i class="ri-logout-circle-r-line"></i> Logout</a>
            </div>
        </div>

        <!-- Page Content Start -->
        <div class="content-container">
            <?php if($this->session->flashdata('success')): ?>
                <div style="color: #86efac; background-color: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <i class="ri-checkbox-circle-line"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                <div style="color: #fca5a5; background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <i class="ri-error-warning-line"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
