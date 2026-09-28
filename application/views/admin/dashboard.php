<div class="welcome-card" style="padding: 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <h2 style="margin-top: 0; color: #0f172a; font-size: 22px; font-weight: 700;">Welcome back, <?php echo htmlspecialchars($this->session->userdata('username')); ?>!</h2>
    <p style="color: #475569; font-size: 15px; margin-bottom: 12px;">You have successfully logged into the AlphaMindz admin panel.</p>
    <p style="color: #64748b; font-size: 14px; margin-bottom: 0;">Use the sidebar menu on the left to navigate between different management tools including Courses, Manage Assessments, Student Access, Products, Blogs, and Users.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 24px;">
    
    <!-- Total Students -->
    <div style="background: #ffffff; border-radius: 10px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; border-left: 4px solid #3498db;">
        <div style="flex-grow: 1;">
            <p style="margin: 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase;">Total Students</p>
            <h3 style="margin: 8px 0 0; color: #0f172a; font-size: 28px; font-weight: 700;"><?php echo isset($total_students) ? $total_students : 0; ?></h3>
        </div>
        <div style="font-size: 32px; color: #3498db; opacity: 0.8;">
            <i class="ri-user-smile-line"></i>
        </div>
    </div>
    
    <!-- Pending Orders -->
    <div style="background: #ffffff; border-radius: 10px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; border-left: 4px solid #f39c12;">
        <div style="flex-grow: 1;">
            <p style="margin: 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase;">Pending Orders</p>
            <h3 style="margin: 8px 0 0; color: #0f172a; font-size: 28px; font-weight: 700;"><?php echo isset($pending_orders) ? $pending_orders : 0; ?></h3>
        </div>
        <div style="font-size: 32px; color: #f39c12; opacity: 0.8;">
            <i class="ri-shopping-cart-2-line"></i>
        </div>
    </div>
    
    <!-- Total Products -->
    <div style="background: #ffffff; border-radius: 10px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; border-left: 4px solid #2ecc71;">
        <div style="flex-grow: 1;">
            <p style="margin: 0; color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase;">Total Products</p>
            <h3 style="margin: 8px 0 0; color: #0f172a; font-size: 28px; font-weight: 700;"><?php echo isset($total_products) ? $total_products : 0; ?></h3>
        </div>
        <div style="font-size: 32px; color: #2ecc71; opacity: 0.8;">
            <i class="ri-book-open-line"></i>
        </div>
    </div>

</div>
