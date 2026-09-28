<div style="background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; color: #f8fafc; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-history-line" style="color: #a855f7;"></i> Admin Login Activity & Security Logs
            </h3>
            <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 0.85rem;">Tracks timestamps, IP addresses, browser specs, device types, and login statuses for all admin sign-ins.</p>
        </div>

        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="<?php echo site_url('superadmin/clear_logs'); ?>" onclick="return confirm('Are you sure you want to clear all login activity logs? This action cannot be undone.');" style="padding: 8px 14px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">
                <i class="ri-delete-bin-line"></i> Clear All Logs
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; background: #0f172a; padding: 14px; border-radius: 8px; border: 1px solid #334155;">
        
        <!-- Status Filter Buttons -->
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo site_url('superadmin/logs'); ?>" style="padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 700; <?php echo empty($status) ? 'background: #4f46e5; color: #fff;' : 'background: #1e293b; color: #94a3b8;'; ?>">
                All Logs
            </a>
            <a href="<?php echo site_url('superadmin/logs?status=success'); ?>" style="padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 700; <?php echo (isset($status) && $status === 'success') ? 'background: #16a34a; color: #fff;' : 'background: #1e293b; color: #94a3b8;'; ?>">
                <i class="ri-checkbox-circle-line"></i> Success
            </a>
            <a href="<?php echo site_url('superadmin/logs?status=failed'); ?>" style="padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 700; <?php echo (isset($status) && $status === 'failed') ? 'background: #dc2626; color: #fff;' : 'background: #1e293b; color: #94a3b8;'; ?>">
                <i class="ri-close-circle-line"></i> Failed
            </a>
        </div>

        <!-- Search Form -->
        <form action="<?php echo site_url('superadmin/logs'); ?>" method="GET" style="display: flex; gap: 8px; align-items: center; margin: 0; flex: 1; max-width: 400px;">
            <?php if(!empty($status)): ?>
                <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <?php endif; ?>
            <div style="position: relative; flex: 1;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                <input type="text" name="search" value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>" placeholder="Search by username, email, IP, browser..." style="width: 100%; padding: 8px 12px 8px 36px; background: #1e293b; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 0.88rem; outline: none; box-sizing: border-box;">
            </div>
            <button type="submit" style="padding: 8px 14px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.88rem;">
                Search
            </button>
            <?php if(!empty($search)): ?>
                <a href="<?php echo site_url('superadmin/logs'); ?>" style="padding: 8px 12px; background: #334155; color: #cbd5e1; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Logs Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #334155; color: #94a3b8; font-size: 0.85rem;">
                    <th style="padding: 12px 14px;">Date & Time</th>
                    <th style="padding: 12px 14px;">Admin / User</th>
                    <th style="padding: 12px 14px;">IP Address</th>
                    <th style="padding: 12px 14px;">Device & OS</th>
                    <th style="padding: 12px 14px;">Browser</th>
                    <th style="padding: 12px 14px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($logs)): foreach($logs as $log): ?>
                <tr style="border-bottom: 1px solid #334155; font-size: 0.9rem;">
                    
                    <!-- Date & Time -->
                    <td style="padding: 14px; color: #f1f5f9; font-weight: 600; white-space: nowrap;">
                        <i class="ri-time-line" style="color: #64748b; margin-right: 4px;"></i>
                        <?php echo date('M d, Y h:i:s A', strtotime($log->login_time)); ?>
                    </td>

                    <!-- Admin User -->
                    <td style="padding: 14px; color: #f8fafc; font-weight: 600;">
                        <div><?php echo htmlspecialchars($log->username); ?></div>
                        <?php if(!empty($log->email) && $log->email !== $log->username): ?>
                            <div style="font-size: 0.78rem; color: #94a3b8; font-weight: 400;"><?php echo htmlspecialchars($log->email); ?></div>
                        <?php endif; ?>
                    </td>

                    <!-- IP Address -->
                    <td style="padding: 14px; color: #818cf8; font-family: monospace; font-weight: 700; font-size: 0.92rem;">
                        <i class="ri-global-line" style="margin-right: 4px;"></i>
                        <?php echo htmlspecialchars($log->ip_address); ?>
                    </td>

                    <!-- Device & OS -->
                    <td style="padding: 14px; color: #cbd5e1;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #0f172a; padding: 4px 10px; border-radius: 6px; border: 1px solid #334155; font-size: 0.82rem;">
                            <?php if(strtolower($log->device_type) === 'mobile'): ?>
                                <i class="ri-smartphone-line" style="color: #f43f5e;"></i>
                            <?php else: ?>
                                <i class="ri-computer-line" style="color: #38bdf8;"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($log->platform ?? 'Desktop OS'); ?>
                        </span>
                    </td>

                    <!-- Browser -->
                    <td style="padding: 14px; color: #cbd5e1; font-size: 0.85rem;">
                        <i class="ri-chrome-line" style="color: #fbbf24; margin-right: 4px;"></i>
                        <?php echo htmlspecialchars($log->browser ?? 'Browser'); ?>
                    </td>

                    <!-- Status -->
                    <td style="padding: 14px;">
                        <?php if(strtolower($log->status) === 'success'): ?>
                            <span style="background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase;">
                                <i class="ri-checkbox-circle-fill"></i> Success
                            </span>
                        <?php else: ?>
                            <span style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase;">
                                <i class="ri-error-warning-fill"></i> Failed
                            </span>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8;">
                        No login activity records found matching your filters.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
