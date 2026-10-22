<div class="form-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; padding: 24px; max-width: 650px; margin: 0 auto 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-folder-add-line" style="color: #2563eb;"></i> <?php echo isset($category) ? 'Edit Shop Category' : 'Add Shop Category'; ?>
        </h2>
        <a href="<?php echo site_url('admin/products'); ?>" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Products
        </a>
    </div>

    <form action="<?php echo isset($category) ? site_url('admin/update_product_category/'.$category->id) : site_url('admin/save_product_category'); ?>" method="POST">
        
        <div style="margin-bottom: 18px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Category Name <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($category) ? htmlspecialchars($category->title) : ''; ?>" placeholder="e.g. Cambridge Prep, Study Planners" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;" onkeyup="generateSlug(this.value)">
        </div>

        <div style="margin-bottom: 18px;">
            <label for="slug" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">URL Slug (lowercase, hyphen-separated) <span style="color: red;">*</span></label>
            <input type="text" id="slug" name="slug" value="<?php echo isset($category) ? htmlspecialchars($category->slug) : ''; ?>" placeholder="e.g. cambridge-prep" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            <span style="font-size: 11px; color: #64748b; display: block; margin-top: 4px;">Public URL: <code>/shop/&lt;slug&gt;</code></span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
            <div>
                <label for="icon" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">RemixIcon Class</label>
                <input type="text" id="icon" name="icon" value="<?php echo isset($category) ? htmlspecialchars($category->icon) : 'ri-store-2-line'; ?>" placeholder="ri-medal-line, ri-file-text-line" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                <span style="font-size: 11px; color: #64748b;">Browse icons at <a href="https://remixicon.com" target="_blank" style="color: #2563eb;">remixicon.com</a></span>
            </div>

            <div>
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($category) ? (int)$category->sort_order : 0; ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="description" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Subtitle / Brief Description</label>
            <textarea id="description" name="description" rows="3" placeholder="Short description displayed on the shop catalog banner for this category..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box; line-height: 1.5;"><?php echo isset($category) ? htmlspecialchars($category->description) : ''; ?></textarea>
        </div>

        <div style="margin-bottom: 24px;">
            <label for="is_active" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Visibility in Store & Navigation</label>
            <select id="is_active" name="is_active" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; background: #fff;">
                <option value="1" <?php echo (!isset($category) || (int)$category->is_active === 1) ? 'selected' : ''; ?>>Active (Visible in Store & Menu)</option>
                <option value="0" <?php echo (isset($category) && (int)$category->is_active === 0) ? 'selected' : ''; ?>>Hidden (Draft / Archive)</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="<?php echo site_url('admin/products'); ?>" style="padding: 10px 18px; border: 1px solid #ced4da; background: #fff; color: #495057; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">Cancel</a>
            <button type="submit" style="padding: 10px 22px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <i class="ri-save-line"></i> <?php echo isset($category) ? 'Update Category' : 'Save Category'; ?>
            </button>
        </div>

    </form>
</div>

<script>
function generateSlug(text) {
    <?php if(!isset($category)): ?>
    const slugInput = document.getElementById('slug');
    if (slugInput) {
        slugInput.value = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
    <?php endif; ?>
}
</script>
