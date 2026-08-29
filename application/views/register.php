<div class="page-header" style="background: #f8f9fa; padding: 40px 0; text-align: center; border-bottom: 1px solid #dee2e6;">
    <div class="container">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: #2c3e50;">Create an Account</h1>
    </div>
</div>

<div class="container" style="padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <div style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;">
        
        <?php if($this->session->flashdata('error')): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.95rem;">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('auth/register'); ?>" method="post">
            
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <label for="first_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">First Name</label>
                    <input type="text" name="first_name" id="first_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label for="last_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email Address</label>
                <input type="email" name="email" id="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Password</label>
                <input type="password" name="password" id="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="confirm_password" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer; font-weight: 600;">Register</button>
        </form>

        <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.95rem;">
            Already have an account? <a href="<?php echo site_url('auth/login'); ?>" style="color: #007bff; text-decoration: none; font-weight: 500;">Log in here</a>
        </div>
    </div>
</div>
