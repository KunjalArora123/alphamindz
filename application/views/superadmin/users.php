<div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <h3 style="margin: 0; font-size: 1.2rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-user-settings-line" style="color: #6366f1;"></i> Manage System Users & Roles
        </h3>

        <!-- Search Box -->
        <form action="<?php echo site_url('superadmin/users'); ?>" method="GET" style="display: flex; gap: 10px; align-items: center; margin: 0; max-width: 450px; flex: 1;">
            <div style="position: relative; flex: 1;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                <input type="text" name="search" value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>" placeholder="Search users by name, username, or email..." style="width: 100%; padding: 10px 12px 10px 36px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.9rem; outline: none; box-sizing: border-box;">
            </div>
            <button type="submit" style="padding: 10px 16px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem; white-space: nowrap;">
                Search
            </button>
            <?php if(!empty($search)): ?>
                <a href="<?php echo site_url('superadmin/users'); ?>" style="padding: 10px 14px; background: #334155; color: #cbd5e1; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.88rem; white-space: nowrap;">
                    Clear
                </a>
            <?php endif; ?>
        </form>

        <span style="background: rgba(99, 102, 241, 0.15); color: #c7d2fe; padding: 6px 14px; border-radius: 9999px; font-size: 0.85rem; font-weight: 700;">
            Total Users: <?php echo count($users); ?>
        </span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #334155; color: #94a3b8; font-size: 0.85rem;">
                    <th style="padding: 12px 16px;">Name / Details</th>
                    <th style="padding: 12px 16px;">Email</th>
                    <th style="padding: 12px 16px;">Current Role</th>
                    <th style="padding: 12px 16px;">Change Role</th>
                    <th style="padding: 12px 16px;">Registered</th>
                    <th style="padding: 12px 16px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)): foreach($users as $user): ?>
                <?php 
                    $display_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                    if (empty($display_name) && !empty($user->username)) $display_name = $user->username;
                    if (empty($display_name) && !empty($user->email)) $display_name = explode('@', $user->email)[0];
                    $final_name = !empty($display_name) ? $display_name : 'User #' . $user->id;
                ?>
                <tr style="border-bottom: 1px solid #334155; font-size: 0.9rem;">
                    <td style="padding: 14px 16px; font-weight: 600; color: #f8fafc;">
                        <?php echo htmlspecialchars($final_name); ?>
                        <?php if(!empty($user->username)): ?>
                            <div style="font-size: 0.78rem; color: #94a3b8; font-weight: 400;">@<?php echo htmlspecialchars($user->username); ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 14px 16px; color: #cbd5e1;">
                        <?php echo htmlspecialchars($user->email); ?>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span style="background: <?php echo (strtolower($user->role) === 'superadmin') ? 'rgba(168, 85, 247, 0.2)' : ((strtolower($user->role) === 'admin') ? 'rgba(59, 130, 246, 0.2)' : 'rgba(148, 163, 184, 0.15)'); ?>; color: <?php echo (strtolower($user->role) === 'superadmin') ? '#d8b4fe' : ((strtolower($user->role) === 'admin') ? '#93c5fd' : '#cbd5e1'); ?>; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                            <?php echo htmlspecialchars($user->role ?? 'student'); ?>
                        </span>
                    </td>
                    <td style="padding: 14px 16px;">
                        <form action="<?php echo site_url('superadmin/change_user_role/'.$user->id); ?>" method="POST" style="display: flex; gap: 6px; align-items: center; margin: 0;">
                            <select name="role" style="padding: 6px 10px; background: #0f172a; border: 1px solid #334155; border-radius: 4px; color: #fff; font-size: 0.82rem;">
                                <option value="student" <?php echo (strtolower($user->role ?? '') === 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="admin" <?php echo (strtolower($user->role ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="superadmin" <?php echo (strtolower($user->role ?? '') === 'superadmin') ? 'selected' : ''; ?>>Superadmin</option>
                            </select>
                            <button type="submit" style="padding: 6px 10px; background: #3b82f6; color: white; border: none; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">Update</button>
                        </form>
                    </td>
                    <td style="padding: 14px 16px; color: #94a3b8; font-size: 0.85rem;">
                        <?php echo isset($user->created_at) ? date('M d, Y', strtotime($user->created_at)) : 'N/A'; ?>
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="<?php echo site_url('superadmin/delete_user/'.$user->id); ?>" onclick="return confirm('Are you sure you want to delete this user?');" style="color: #fca5a5; text-decoration: none; font-weight: 600; font-size: 0.85rem;">
                            <i class="ri-delete-bin-line"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8;">No users found matching your criteria.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
