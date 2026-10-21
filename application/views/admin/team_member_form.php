<div class="form-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; padding: 24px; max-width: 800px; margin: 0 auto 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-user-star-line" style="color: #2563eb;"></i> <?php echo isset($member) ? 'Edit Team Member' : 'Add Team Member'; ?>
        </h2>
        <a href="<?php echo site_url('admin/about'); ?>" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to About Us
        </a>
    </div>

    <form action="<?php echo isset($member) ? site_url('admin/update_team_member/'.$member->id) : site_url('admin/save_team_member'); ?>" method="POST" enctype="multipart/form-data">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="name" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Full Name <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo isset($member) ? htmlspecialchars($member->name) : ''; ?>" placeholder="e.g. Manu Anand" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div>
                <label for="role_title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Job Title / Designation <span style="color: red;">*</span></label>
                <input type="text" id="role_title" name="role_title" value="<?php echo isset($member) ? htmlspecialchars($member->role_title) : ''; ?>" placeholder="e.g. CEO, DIRECTOR, SR. MANAGER..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="branch_key" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Assigned Branch / Hub <span style="color: red;">*</span></label>
                <select id="branch_key" name="branch_key" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="solan" <?php echo (isset($member) && $member->branch_key === 'solan') ? 'selected' : ''; ?>>Solan Branch (Himachal Pradesh)</option>
                    <option value="goa" <?php echo (isset($member) && $member->branch_key === 'goa') ? 'selected' : ''; ?>>Goa Branch (Panaji)</option>
                </select>
            </div>

            <div>
                <label for="role_color_theme" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Role Badge Color Style</label>
                <select id="role_color_theme" name="role_color_theme" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="blue" <?php echo (isset($member) && $member->role_color_theme === 'blue') ? 'selected' : ''; ?>>Blue Badge (e.g. CEO style)</option>
                    <option value="orange" <?php echo (isset($member) && $member->role_color_theme === 'orange') ? 'selected' : ''; ?>>Orange / Yellow Badge (e.g. Director style)</option>
                    <option value="green" <?php echo (isset($member) && $member->role_color_theme === 'green') ? 'selected' : ''; ?>>Green Badge (e.g. BD style)</option>
                    <option value="purple" <?php echo (isset($member) && $member->role_color_theme === 'purple') ? 'selected' : ''; ?>>Purple Badge (e.g. Marketing style)</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="bio" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Biography & Experience Summary</label>
            <textarea id="bio" name="bio" rows="4" placeholder="Brief background, education, and previous career achievements..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box; line-height: 1.5;"><?php echo isset($member) ? htmlspecialchars($member->bio) : ''; ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Sort Order (Lowest first)</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($member) ? (int)$member->sort_order : 0; ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div>
                <label for="is_active" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Visibility Status</label>
                <select id="is_active" name="is_active" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="1" <?php echo (!isset($member) || (int)$member->is_active === 1) ? 'selected' : ''; ?>>Active (Visible on public About page)</option>
                    <option value="0" <?php echo (isset($member) && (int)$member->is_active === 0) ? 'selected' : ''; ?>>Hidden (Do not display publicly)</option>
                </select>
            </div>
        </div>

        <!-- Photo Upload & Current Image Preview -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 24px;">
            <label style="font-weight: 600; color: #343a40; display: block; margin-bottom: 8px;">Profile Photo</label>
            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                <?php if(isset($member) && !empty($member->image_url)): ?>
                    <div style="text-align: center;">
                        <?php 
                            $curr_img = (strpos($member->image_url, 'http') === 0) ? $member->image_url : base_url($member->image_url);
                        ?>
                        <img src="<?php echo htmlspecialchars($curr_img); ?>" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #2563eb; display: block; margin: 0 auto 6px auto;">
                        <span style="font-size: 11px; color: #64748b;">Current Photo</span>
                    </div>
                <?php endif; ?>
                <div style="flex: 1; min-width: 240px;">
                    <input type="file" name="member_image" accept="image/*" style="font-size: 13px;">
                    <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Recommended: Square photo (JPG, PNG, WebP). Leave blank to keep existing photo.</p>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #eee; padding-top: 18px;">
            <a href="<?php echo site_url('admin/about'); ?>" style="padding: 10px 18px; border-radius: 6px; border: 1px solid #ced4da; color: #495057; text-decoration: none; font-size: 14px; font-weight: 600;">
                Cancel
            </a>
            <button type="submit" style="background-color: #2563eb; color: #fff; padding: 10px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                <i class="ri-check-line"></i> <?php echo isset($member) ? 'Save Updates' : 'Add Team Member'; ?>
            </button>
        </div>

    </form>
</div>
