<!-- Metrics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 28px;">
    
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total Students/Users</span>
            <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo $total_users; ?></h2>
        </div>
        <div style="background: rgba(99, 102, 241, 0.15); color: #818cf8; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="ri-user-line"></i>
        </div>
    </div>

    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Admin Accounts</span>
            <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo $total_admins; ?></h2>
        </div>
        <div style="background: rgba(168, 85, 247, 0.15); color: #c084fc; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="ri-admin-line"></i>
        </div>
    </div>

    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total Courses</span>
            <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo $total_courses; ?></h2>
        </div>
        <div style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="ri-book-open-line"></i>
        </div>
    </div>

    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total Assessments</span>
            <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo $total_assessments; ?></h2>
        </div>
        <div style="background: rgba(34, 197, 94, 0.15); color: #4ade80; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="ri-file-list-3-line"></i>
        </div>
    </div>

    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total Orders</span>
            <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo $total_orders; ?></h2>
        </div>
        <div style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="ri-shopping-cart-2-line"></i>
        </div>
    </div>

    <a href="<?php echo site_url('superadmin/logs'); ?>" style="text-decoration: none;">
        <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; height: 100%; box-sizing: border-box;">
            <div>
                <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Login Logs</span>
                <h2 style="margin: 8px 0 0 0; font-size: 1.8rem; color: #f8fafc; font-weight: 800;"><?php echo isset($total_logs) ? $total_logs : 0; ?></h2>
            </div>
            <div style="background: rgba(236, 72, 153, 0.15); color: #f472b6; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="ri-history-line"></i>
            </div>
        </div>
    </a>

</div>

<!-- Admin Management & Quick Action Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">

    <!-- Existing Admins List -->
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.1rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-user-shield-line" style="color: #818cf8;"></i> Administrator Accounts
            </h3>
            <span style="background: rgba(99, 102, 241, 0.2); color: #c7d2fe; font-size: 0.8rem; font-weight: 700; padding: 4px 10px; border-radius: 9999px;">
                Count: <?php echo count($admins_list); ?>
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #334155; color: #94a3b8; font-size: 0.85rem;">
                        <th style="padding: 10px 14px;">Username</th>
                        <th style="padding: 10px 14px;">Email</th>
                        <th style="padding: 10px 14px;">Role</th>
                        <th style="padding: 10px 14px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($admins_list)): foreach($admins_list as $adm): ?>
                    <tr style="border-bottom: 1px solid #334155; font-size: 0.9rem;">
                        <td style="padding: 12px 14px; font-weight: 600; color: #f1f5f9;">
                            <?php echo htmlspecialchars($adm->username); ?>
                        </td>
                        <td style="padding: 12px 14px; color: #cbd5e1;">
                            <?php echo htmlspecialchars(!empty($adm->email) ? $adm->email : 'N/A'); ?>
                        </td>
                        <td style="padding: 12px 14px;">
                            <span style="background: <?php echo (strtolower($adm->role ?? '') === 'superadmin') ? 'rgba(168, 85, 247, 0.2)' : 'rgba(59, 130, 246, 0.2)'; ?>; color: <?php echo (strtolower($adm->role ?? '') === 'superadmin') ? '#d8b4fe' : '#93c5fd'; ?>; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                                <?php echo htmlspecialchars($adm->role ?? 'admin'); ?>
                            </span>
                        </td>
                        <td style="padding: 12px 14px;">
                            <?php if(($adm->username !== 'kunjalarora2@gmail.com') && ($adm->username !== 'superadmin')): ?>
                                <a href="<?php echo site_url('superadmin/delete_admin/'.$adm->id); ?>" onclick="return confirm('Remove admin account?');" style="color: #fca5a5; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                                    <i class="ri-delete-bin-line"></i> Remove
                                </a>
                            <?php else: ?>
                                <span style="color: #64748b; font-size: 0.8rem; font-style: italic;">Primary Master</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" style="padding: 16px; text-align: center; color: #94a3b8;">No admin users found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Admin Form -->
    <div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
        <h3 style="margin: 0 0 16px 0; font-size: 1.1rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-user-add-line" style="color: #a855f7;"></i> Add New Admin
        </h3>

        <form action="<?php echo site_url('superadmin/create_admin'); ?>" method="POST">
            <div style="margin-bottom: 14px;">
                <label style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Username</label>
                <input type="text" name="username" required placeholder="admin_username" style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Email Address</label>
                <input type="email" name="email" placeholder="admin@alphamindz.com" style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Password</label>
                <input type="password" name="password" required placeholder="Set password" style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.9rem; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; margin-bottom: 6px; font-size: 0.85rem; font-weight: 600; color: #cbd5e1;">Role</label>
                <select name="role" style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.9rem; box-sizing: border-box;">
                    <option value="admin">Standard Admin</option>
                    <option value="superadmin">Super Admin</option>
                </select>
            </div>

            <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 0.95rem;">
                <i class="ri-add-line"></i> Create Admin Account
            </button>
        </form>
    </div>

</div>

<!-- Recent Users List -->
<div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 1.1rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-user-follow-line" style="color: #3b82f6;"></i> Recent Registered Users
        </h3>
        <a href="<?php echo site_url('superadmin/users'); ?>" style="color: #818cf8; text-decoration: none; font-size: 0.88rem; font-weight: 600;">
            View All Users & Roles <i class="ri-arrow-right-line"></i>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #334155; color: #94a3b8; font-size: 0.85rem;">
                    <th style="padding: 10px 14px;">User</th>
                    <th style="padding: 10px 14px;">Email</th>
                    <th style="padding: 10px 14px;">Role</th>
                    <th style="padding: 10px 14px;">Registered Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($recent_users)): foreach($recent_users as $u): ?>
                <tr style="border-bottom: 1px solid #334155; font-size: 0.9rem;">
                    <td style="padding: 12px 14px; font-weight: 600; color: #f1f5f9;">
                        <?php echo htmlspecialchars(trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->username ?? 'User #'.$u->id)); ?>
                    </td>
                    <td style="padding: 12px 14px; color: #cbd5e1;">
                        <?php echo htmlspecialchars($u->email); ?>
                    </td>
                    <td style="padding: 12px 14px;">
                        <span style="background: rgba(148, 163, 184, 0.15); color: #cbd5e1; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                            <?php echo htmlspecialchars($u->role ?? 'student'); ?>
                        </span>
                    </td>
                    <td style="padding: 12px 14px; color: #94a3b8; font-size: 0.85rem;">
                        <?php echo isset($u->created_at) ? date('M d, Y', strtotime($u->created_at)) : 'N/A'; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="4" style="padding: 16px; text-align: center; color: #94a3b8;">No registered users found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
