<div class="form-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; padding: 24px; max-width: 750px; margin: 0 auto 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-feedback-line" style="color: #2563eb;"></i> <?php echo isset($testimonial) ? 'Edit Testimonial' : 'Add Testimonial'; ?>
        </h2>
        <a href="<?php echo site_url('admin/testimonials'); ?>" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Testimonials
        </a>
    </div>

    <form action="<?php echo isset($testimonial) ? site_url('admin/update_testimonial/'.$testimonial->id) : site_url('admin/save_testimonial'); ?>" method="POST">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="author_name" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Student / Author Name <span style="color: red;">*</span></label>
                <input type="text" id="author_name" name="author_name" value="<?php echo isset($testimonial) ? htmlspecialchars($testimonial->author_name) : ''; ?>" placeholder="e.g. Shubham Samant" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;" onkeyup="autoInitials(this.value)">
            </div>

            <div>
                <label for="author_role" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Course / Program / Role <span style="color: red;">*</span></label>
                <input type="text" id="author_role" name="author_role" value="<?php echo isset($testimonial) ? htmlspecialchars($testimonial->author_role) : ''; ?>" placeholder="e.g. IELTS Prep Student, Career Assessment Client" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="avatar_initials" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Avatar Initials (2 letters)</label>
                <input type="text" id="avatar_initials" name="avatar_initials" maxlength="5" value="<?php echo isset($testimonial) ? htmlspecialchars($testimonial->avatar_initials) : ''; ?>" placeholder="e.g. SS, MM" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box; text-transform: uppercase;">
            </div>

            <div>
                <label for="avatar_bg" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Avatar Color Style</label>
                <select id="avatar_bg" name="avatar_bg" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="bg-blue" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-blue') ? 'selected' : ''; ?>>Blue Badge</option>
                    <option value="bg-pink" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-pink') ? 'selected' : ''; ?>>Pink Badge</option>
                    <option value="bg-green" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-green') ? 'selected' : ''; ?>>Green Badge</option>
                    <option value="bg-purple" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-purple') ? 'selected' : ''; ?>>Purple Badge</option>
                    <option value="bg-amber" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-amber') ? 'selected' : ''; ?>>Amber / Orange Badge</option>
                    <option value="bg-teal" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-teal') ? 'selected' : ''; ?>>Teal Badge</option>
                    <option value="bg-indigo" <?php echo (isset($testimonial) && $testimonial->avatar_bg === 'bg-indigo') ? 'selected' : ''; ?>>Indigo Badge</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="quote" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Testimonial Quote / Feedback <span style="color: red;">*</span></label>
            <textarea id="quote" name="quote" rows="5" placeholder="Write student review or quote..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box; line-height: 1.5;"><?php echo isset($testimonial) ? htmlspecialchars(trim($testimonial->quote, '"')) : ''; ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 24px;">
            <div>
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Sort Order (Lowest first)</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($testimonial) ? (int)$testimonial->sort_order : 0; ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div>
                <label for="status" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Status</label>
                <select id="status" name="status" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="active" <?php echo (!isset($testimonial) || $testimonial->status === 'active') ? 'selected' : ''; ?>>Active (Visible in homepage slider)</option>
                    <option value="inactive" <?php echo (isset($testimonial) && $testimonial->status === 'inactive') ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #eee; padding-top: 18px;">
            <a href="<?php echo site_url('admin/testimonials'); ?>" style="padding: 10px 18px; border-radius: 6px; border: 1px solid #ced4da; color: #495057; text-decoration: none; font-size: 14px; font-weight: 600;">
                Cancel
            </a>
            <button type="submit" style="background-color: #2563eb; color: #fff; padding: 10px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                <i class="ri-check-line"></i> <?php echo isset($testimonial) ? 'Update Testimonial' : 'Save Testimonial'; ?>
            </button>
        </div>

    </form>
</div>

<script>
function autoInitials(val) {
    var parts = val.trim().split(/\s+/);
    var init = '';
    if (parts.length >= 2) {
        init = (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    } else if (parts.length === 1 && parts[0].length > 0) {
        init = parts[0].substring(0, 2).toUpperCase();
    }
    var initialsField = document.getElementById('avatar_initials');
    <?php if(!isset($testimonial)): ?>
        if (initialsField) initialsField.value = init;
    <?php endif; ?>
}
</script>
