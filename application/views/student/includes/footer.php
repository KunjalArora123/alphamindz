<!-- Mobile Bottom Navigation Bar -->
<div class="mobile-bottom-nav d-lg-none">
    <a href="<?php echo site_url('student'); ?>" class="nav-item <?php echo ($this->uri->segment(1) == 'student' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
        <i class="ri-dashboard-line"></i>
        <span>Home</span>
    </a>
    
    <a href="<?php echo site_url('student/assessment'); ?>" class="nav-item <?php echo ($this->uri->segment(1) == 'assessments') ? 'active' : ''; ?>">
        <i class="ri-task-line"></i>
        <span>Tests</span>
    </a>
    <a href="<?php echo site_url('auth/logout'); ?>" class="nav-item">
        <i class="ri-logout-box-r-line"></i>
        <span>Logout</span>
    </a>
</div>

<footer class="bg-white border-top py-3 mt-5">
    <div class="container text-center text-muted">
        <p class="mb-0 small fw-medium">&copy; <?php echo date('Y'); ?> AlphaMindz Student Portal. Powered by AlphaMindz.</p>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


