<div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 28px; max-width: 900px; margin: 0 auto;">
    
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #334155; padding-bottom: 20px;">
        <div>
            <h3 style="margin: 0; font-size: 1.3rem; color: #f8fafc; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                <i class="ri-toggle-line" style="color: #6366f1;"></i> Website Access & Maintenance Control
            </h3>
            <p style="margin: 6px 0 0 0; color: #94a3b8; font-size: 0.88rem;">Toggle website online/offline status and customize maintenance mode notices.</p>
        </div>

        <?php if($maintenance_mode == '1'): ?>
            <span style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); padding: 8px 18px; border-radius: 9999px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; display: flex; align-items: center; gap: 8px;">
                <span style="width: 10px; height: 10px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 10px #ef4444;"></span> OFFLINE (MAINTENANCE)
            </span>
        <?php else: ?>
            <span style="background: rgba(34, 197, 94, 0.2); color: #86efac; border: 1px solid rgba(34, 197, 94, 0.4); padding: 8px 18px; border-radius: 9999px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; display: flex; align-items: center; gap: 8px;">
                <span style="width: 10px; height: 10px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></span> ONLINE & ACTIVE
            </span>
        <?php endif; ?>
    </div>

    <!-- Toggle Action Box -->
    <div style="background: #0f172a; border: 1px solid #334155; border-radius: 12px; padding: 28px; margin-bottom: 28px; text-align: center;">
        
        <div style="margin-bottom: 20px;">
            <?php if($maintenance_mode == '1'): ?>
                <div style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.15); color: #f87171; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 2.2rem; border: 2px solid rgba(239, 68, 68, 0.3);">
                    <i class="ri-power-flash-line"></i>
                </div>
                <h4 style="margin: 0; font-size: 1.25rem; color: #f8fafc; font-weight: 700;">Website is Currently OFFLINE</h4>
                <p style="margin: 6px 0 0 0; color: #94a3b8; font-size: 0.9rem;">Visitors, students, and standard admins are seeing the Under Maintenance page.</p>
            <?php else: ?>
                <div style="width: 72px; height: 72px; background: rgba(34, 197, 94, 0.15); color: #4ade80; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 2.2rem; border: 2px solid rgba(34, 197, 94, 0.3);">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
                <h4 style="margin: 0; font-size: 1.25rem; color: #f8fafc; font-weight: 700;">Website is Currently ONLINE</h4>
                <p style="margin: 6px 0 0 0; color: #94a3b8; font-size: 0.9rem;">All public pages, courses, student portal, and admin panel are accessible.</p>
            <?php endif; ?>
        </div>

        <form action="<?php echo site_url('superadmin/toggle_maintenance'); ?>" method="POST" style="margin: 0;">
            <input type="hidden" name="target_mode" value="<?php echo ($maintenance_mode == '1') ? '0' : '1'; ?>">
            
            <?php if($maintenance_mode == '1'): ?>
                <button type="submit" onclick="return confirm('Take the website back ONLINE?');" style="padding: 14px 32px; background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; border: none; border-radius: 10px; font-weight: 800; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px -5px rgba(34, 197, 94, 0.4);">
                    <i class="ri-play-circle-line" style="font-size: 1.3rem;"></i> BRING WEBSITE ONLINE
                </button>
            <?php else: ?>
                <button type="submit" onclick="return confirm('Are you sure you want to put the ENTIRE website OFFLINE? Visitors and admins will be redirected to the Under Maintenance page.');" style="padding: 14px 32px; background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; border: none; border-radius: 10px; font-weight: 800; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.4);">
                    <i class="ri-shut-down-line" style="font-size: 1.3rem;"></i> PUT WEBSITE OFFLINE
                </button>
            <?php endif; ?>
        </form>

    </div>

    <!-- Affected Scope Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px;">
        <div style="background: #0f172a; border: 1px solid #334155; padding: 16px; border-radius: 8px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Main Website</div>
            <div style="margin-top: 6px; font-weight: 700; color: <?php echo ($maintenance_mode == '1') ? '#fca5a5' : '#86efac'; ?>;">
                <?php echo ($maintenance_mode == '1') ? 'Blocked (Maintenance Page)' : 'Active / Public'; ?>
            </div>
        </div>

        <div style="background: #0f172a; border: 1px solid #334155; padding: 16px; border-radius: 8px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Student Portal</div>
            <div style="margin-top: 6px; font-weight: 700; color: <?php echo ($maintenance_mode == '1') ? '#fca5a5' : '#86efac'; ?>;">
                <?php echo ($maintenance_mode == '1') ? 'Blocked (Maintenance Page)' : 'Active / Public'; ?>
            </div>
        </div>

        <div style="background: #0f172a; border: 1px solid #334155; padding: 16px; border-radius: 8px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Admin Panel</div>
            <div style="margin-top: 6px; font-weight: 700; color: <?php echo ($maintenance_mode == '1') ? '#fca5a5' : '#86efac'; ?>;">
                <?php echo ($maintenance_mode == '1') ? 'Blocked (Maintenance Page)' : 'Active / Protected'; ?>
            </div>
        </div>

        <div style="background: #0f172a; border: 1px solid #334155; padding: 16px; border-radius: 8px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Superadmin Panel</div>
            <div style="margin-top: 6px; font-weight: 700; color: #c7d2fe;">
                Always Active (Exempt)
            </div>
        </div>
    </div>

    <!-- Customize Maintenance Notice -->
    <div style="background: #0f172a; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
        <h4 style="margin: 0 0 16px 0; font-size: 1.05rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-edit-2-line" style="color: #a855f7;"></i> Customize Maintenance Notice Text
        </h4>

        <form action="<?php echo site_url('superadmin/update_maintenance_message'); ?>" method="POST">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Maintenance Announcement Message</label>
                <textarea name="maintenance_message" rows="4" style="width: 100%; padding: 12px; background: #1e293b; border: 1px solid #334155; border-radius: 8px; color: #fff; font-size: 0.95rem; box-sizing: border-box; resize: vertical;" required><?php echo htmlspecialchars($maintenance_message); ?></textarea>
            </div>

            <button type="submit" style="padding: 10px 20px; background: #4f46e5; color: #fff; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.9rem;">
                Save Announcement Message
            </button>
        </form>
    </div>

</div>
