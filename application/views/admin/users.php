<div class="card" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px; flex-wrap: wrap; gap: 15px;">
        <h3 style="margin: 0; font-size: 1.25rem; color: #1e293b; font-weight: 700;">Registered Users</h3>
        
        <!-- Search Box Form -->
        <form action="<?php echo site_url('admin/users'); ?>" method="GET" style="display: flex; gap: 10px; align-items: center; margin: 0; flex: 1; max-width: 450px; justify-content: flex-end;">
            <div style="position: relative; flex: 1; min-width: 200px;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
                <input type="text" name="search" id="userSearchInput" value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>" placeholder="Search student by name, username, or email..." style="width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box; background: #ffffff;">
            </div>
            <button type="submit" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.9rem; white-space: nowrap;">
                Search
            </button>
            <?php if(!empty($search)): ?>
                <a href="<?php echo site_url('admin/users'); ?>" style="padding: 8px 12px; background-color: #f1f5f9; color: #475569; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; white-space: nowrap; border: 1px solid #cbd5e1;">
                    Clear
                </a>
            <?php endif; ?>
        </form>

        <span class="status-badge status-publish" style="background-color: #e2e8f0; color: #334155; padding: 6px 14px; border-radius: 12px; font-size: 0.85rem; font-weight: 700;">
            Total Users: <span id="userCountDisplay"><?php echo count($users); ?></span>
        </span>
    </div>
    
    <div class="card-body" style="padding: 20px;">
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.85rem; font-weight: 700; color: #475569;">Name</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.85rem; font-weight: 700; color: #475569;">Email</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.85rem; font-weight: 700; color: #475569;">Role</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 0.85rem; font-weight: 700; color: #475569;">Registered Date</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 0.85rem; font-weight: 700; color: #475569;">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php if(!empty($users)): foreach($users as $user): ?>
                    <?php 
                        $display_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                        if (empty($display_name) && isset($user->name)) {
                            $display_name = trim($user->name);
                        }
                        if (empty($display_name) && !empty($user->username)) {
                            $display_name = $user->username;
                        }
                        if (empty($display_name) && !empty($user->email)) {
                            $display_name = explode('@', $user->email)[0];
                        }
                        $final_name = !empty($display_name) ? $display_name : 'User #' . $user->id;
                    ?>
                    <tr class="user-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 16px; font-weight: 600; color: #0f172a;" class="user-name-col">
                            <?php echo htmlspecialchars($final_name); ?>
                            <?php if(!empty($user->username) && $user->username !== $final_name): ?>
                                <span style="font-size: 0.78rem; color: #64748b; font-weight: 400; margin-left: 4px;">(@<?php echo htmlspecialchars($user->username); ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; color: #475569;" class="user-email-col"><?php echo htmlspecialchars($user->email); ?></td>
                        <td style="padding: 12px 16px;">
                            <span style="background-color: <?php echo (strtolower($user->role) == 'admin') ? '#dbeafe' : '#f1f5f9'; ?>; color: <?php echo (strtolower($user->role) == 'admin') ? '#1e40af' : '#475569'; ?>; padding: 4px 10px; border-radius: 4px; font-size: 0.78rem; font-weight: 700; text-transform: capitalize;">
                                <?php echo htmlspecialchars($user->role); ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: #64748b; font-size: 0.88rem;"><?php echo date('M d, Y', strtotime($user->created_at)); ?></td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <?php if(strtolower($user->role) == 'student'): ?>
                                <a href="<?php echo site_url('admin/user_details/'.$user->id); ?>" style="color: #16a34a; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 0.88rem;"><i class="ri-eye-line"></i> View Details</a>
                            <?php endif; ?>
                            <a href="<?php echo site_url('admin/edit_user/'.$user->id); ?>" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 600; font-size: 0.88rem;"><i class="ri-edit-line"></i> Edit</a>
                            <a href="<?php echo site_url('admin/delete_user/'.$user->id); ?>" style="color: #dc2626; text-decoration: none; font-weight: 600; font-size: 0.88rem;" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');"><i class="ri-delete-bin-line"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr id="noUsersRow">
                        <td colspan="5" style="padding: 24px; text-align: center; color: #64748b;">No users found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    const tableBody = document.getElementById('userTableBody');
    const rows = tableBody.querySelectorAll('.user-row');
    const countDisplay = document.getElementById('userCountDisplay');

    if (searchInput && rows.length > 0) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (countDisplay) {
                countDisplay.textContent = visibleCount;
            }
        });
    }
});
</script>
