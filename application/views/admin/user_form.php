<div class="card">
    <div class="card-header">
        <h3>Edit User: <?php echo $user->first_name . ' ' . $user->last_name; ?></h3>
    </div>
    <div class="card-body">
        <form action="<?php echo site_url('admin/update_user/'.$user->id); ?>" method="post" style="max-width: 600px;">
            
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="first_name" style="display: block; margin-bottom: 5px; font-weight: 500;">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo $user->first_name; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="last_name" style="display: block; margin-bottom: 5px; font-weight: 500;">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo $user->last_name; ?>" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px; font-weight: 500;">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo $user->email; ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="role" style="display: block; margin-bottom: 5px; font-weight: 500;">Role</label>
                <select name="role" id="role" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="student" <?php echo ($user->role == 'student') ? 'selected' : ''; ?>>Student</option>
                    <option value="admin" <?php echo ($user->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Save Changes</button>
                <a href="<?php echo site_url('admin/users'); ?>" style="margin-left: 10px; color: #6c757d; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>
