<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>Registered Users</h3>
        <span class="status-badge status-publish" style="background-color: #e2e3e5; color: #383d41;">Total: <?php echo count($users); ?></span>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Name</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Role</th>
                    <th style="padding: 12px; text-align: left;">Registered Date</th>
                    <th style="padding: 12px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)): foreach($users as $user): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px; font-weight: 500;"><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                    <td style="padding: 12px;"><?php echo $user->email; ?></td>
                    <td style="padding: 12px;">
                        <span style="background-color: <?php echo ($user->role == 'admin') ? '#cce5ff' : '#e2e3e5'; ?>; color: <?php echo ($user->role == 'admin') ? '#004085' : '#383d41'; ?>; padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: capitalize;">
                            <?php echo $user->role; ?>
                        </span>
                    </td>
                    <td style="padding: 12px;"><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="<?php echo site_url('admin/edit_user/'.$user->id); ?>" style="color: #007bff; text-decoration: none; margin-right: 10px;"><i class="ri-edit-line"></i> Edit</a>
                        <a href="<?php echo site_url('admin/delete_user/'.$user->id); ?>" style="color: #dc3545; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');"><i class="ri-delete-bin-line"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">No users registered yet.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
