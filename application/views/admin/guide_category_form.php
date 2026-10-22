<div class="form-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; padding: 24px; max-width: 700px; margin: 0 auto 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-folder-add-line" style="color: #2563eb;"></i> <?php echo isset($category) ? 'Edit Guide Category' : 'Add Guide Category'; ?>
        </h2>
        <a href="<?php echo site_url('admin/downloadable_guides'); ?>" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Guides
        </a>
    </div>

    <form action="<?php echo isset($category) ? site_url('admin/update_guide_category/'.$category->id) : site_url('admin/save_guide_category'); ?>" method="POST">
        
        <div style="margin-bottom: 18px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Category Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($category) ? htmlspecialchars($category->title) : ''; ?>" placeholder="e.g. Technology & AI Guides" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;" onkeyup="generateSlug(this.value)">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="slug" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">URL Slug <span style="color: red;">*</span></label>
                <input type="text" id="slug" name="slug" value="<?php echo isset($category) ? htmlspecialchars($category->slug) : ''; ?>" placeholder="e.g. tech-ai-guides" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <span style="font-size: 11px; color: #64748b;">Public URL: /resources/&lt;slug&gt;</span>
            </div>

            <div>
                <label for="badge" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Badge Tag / Topic</label>
                <input type="text" id="badge" name="badge" value="<?php echo isset($category) ? htmlspecialchars($category->badge) : 'Guides'; ?>" placeholder="e.g. Tech, Mindset, Test Prep" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="icon" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">RemixIcon Class</label>
                <input type="text" id="icon" name="icon" value="<?php echo isset($category) ? htmlspecialchars($category->icon) : 'ri-file-pdf-line'; ?>" placeholder="ri-line-chart-line, ri-compass-3-line" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <span style="font-size: 11px; color: #64748b;">Browse at <a href="https://remixicon.com" target="_blank" style="color: #2563eb;">remixicon.com</a></span>
            </div>

            <div>
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($category) ? (int)$category->sort_order : 0; ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="description" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Brief Description</label>
            <textarea id="description" name="description" rows="3" placeholder="Summary of what visitors will learn or download in this track..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box; line-height: 1.5;"><?php echo isset($category) ? htmlspecialchars($category->description) : ''; ?></textarea>
        </div>

        <div style="margin-bottom: 24px;">
            <label for="is_active" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Visibility</label>
            <select id="is_active" name="is_active" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                <option value="1" <?php echo (!isset($category) || (int)$category->is_active === 1) ? 'selected' : ''; ?>>Active (Visible in header dropdown and overview page)</option>
                <option value="0" <?php echo (isset($category) && (int)$category->is_active === 0) ? 'selected' : ''; ?>>Hidden (Do not display publicly)</option>
            </select>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #eee; padding-top: 18px;">
            <a href="<?php echo site_url('admin/downloadable_guides'); ?>" style="padding: 10px 18px; border-radius: 6px; border: 1px solid #ced4da; color: #495057; text-decoration: none; font-size: 14px; font-weight: 600;">
                Cancel
            </a>
            <button type="submit" style="background-color: #2563eb; color: #fff; padding: 10px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                <i class="ri-check-line"></i> <?php echo isset($category) ? 'Update Category' : 'Create Category'; ?>
            </button>
        </div>

    </form>
</div>

<script>
function generateSlug(text) {
    <?php if(!isset($category)): ?>
    var slug = text.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-');
    document.getElementById('slug').value = slug;
    <?php endif; ?>
}
</script>
