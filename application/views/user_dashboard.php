<div class="page-header" style="background: #f8f9fa; padding: 40px 0; border-bottom: 1px solid #dee2e6;">
    <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: #2c3e50;">My Dashboard</h1>
        <p style="color: #6c757d;">Welcome back, <?php echo $this->session->userdata('user_name'); ?>!</p>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 1000px; margin: 0 auto;">
    
    <?php if($this->session->flashdata('success')): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 30px; font-size: 1rem;">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <!-- Profile Card -->
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; background: #007bff; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-right: 15px;">
                    <i class="ri-user-line"></i>
                </div>
                <div>
                    <h3 style="margin: 0 0 5px 0; color: #333;"><?php echo $this->session->userdata('user_name'); ?></h3>
                    <div style="color: #6c757d; font-size: 0.9rem;"><?php echo $this->session->userdata('user_email'); ?></div>
                </div>
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #555; margin-bottom: 5px;">Account Type: <strong style="text-transform: capitalize;"><?php echo $this->session->userdata('user_role'); ?></strong></p>
        </div>

        <!-- Quick Links Card -->
        <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
            <h3 style="margin: 0 0 20px 0; color: #333; font-family: 'Playfair Display', serif;">Quick Links</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 12px;"><a href="<?php echo site_url('courses'); ?>" style="color: #007bff; text-decoration: none;"><i class="ri-book-open-line" style="margin-right: 8px;"></i> Browse Courses</a></li>
                <li style="margin-bottom: 12px;"><a href="<?php echo site_url('assessments'); ?>" style="color: #007bff; text-decoration: none;"><i class="ri-survey-line" style="margin-right: 8px;"></i> Take an Assessment</a></li>
                <li style="margin-bottom: 12px;"><a href="<?php echo site_url('shop'); ?>" style="color: #007bff; text-decoration: none;"><i class="ri-store-2-line" style="margin-right: 8px;"></i> Visit the Shop</a></li>
                <li><a href="<?php echo site_url('auth/logout'); ?>" style="color: #dc3545; text-decoration: none;"><i class="ri-logout-box-line" style="margin-right: 8px;"></i> Log Out</a></li>
            </ul>
        </div>
    </div>
</div>
