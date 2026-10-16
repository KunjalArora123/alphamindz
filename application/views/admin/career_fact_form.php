<div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-briefcase-line"></i> <?php echo isset($career_fact) ? 'Edit Career Fact' : 'Add New Career Fact'; ?></h2>
        <a href="<?php echo site_url('admin/career_facts'); ?>" style="color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Career Facts
        </a>
    </div>

    <form action="<?php echo isset($career_fact) ? site_url('admin/update_career_fact/'.$career_fact->id) : site_url('admin/save_career_fact'); ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Career Fact Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($career_fact) ? htmlspecialchars($career_fact->title) : ''; ?>" placeholder="Enter career fact title..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
        </div>

        <div style="display: flex; gap: 16px; flex-wrap: wrap;" class="form-group">
            <div style="flex: 1; min-width: 240px;">
                <label for="category" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-price-tag-3-line"></i> Category</label>
                <input type="text" id="category" name="category" list="category_list" value="<?php echo isset($career_fact->category) ? htmlspecialchars($career_fact->category) : 'Career Facts'; ?>" placeholder="e.g. Career Facts, Tech, Medical..." style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
                <datalist id="category_list">
                    <option value="Career Facts">
                    <option value="Future Careers">
                    <option value="Traditional Gateways">
                    <option value="Industry Specializations">
                </datalist>
            </div>

            <div style="flex: 1; min-width: 240px;">
                <label for="author" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-user-3-line"></i> Author</label>
                <input type="text" id="author" name="author" value="<?php echo isset($career_fact) ? htmlspecialchars($career_fact->author) : 'Alpha Mindz Team'; ?>" placeholder="Author name" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label for="status" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-eye-line"></i> Status</label>
                <select id="status" name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff;">
                    <option value="published" <?php echo (isset($career_fact) && $career_fact->status === 'published') ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo (isset($career_fact) && $career_fact->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="image" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-image-line"></i> Cover Image</label>
            <?php 
            $curr_img = isset($career_fact) ? (!empty($career_fact->image) ? $career_fact->image : '') : '';
            if (!empty($curr_img)): 
                $curr_src = (strpos($curr_img, 'http://') === 0 || strpos($curr_img, 'https://') === 0) ? $curr_img : base_url($curr_img);
            ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo $curr_src; ?>" alt="Cover Preview" style="max-width: 200px; max-height: 130px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; display: block; margin-bottom: 6px;">
                    <span style="font-size: 12px; color: #6c757d;">Current image: <code><?php echo htmlspecialchars($curr_img); ?></code></span>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 6px;">
            <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 4px;">Upload JPG, PNG, WEBP cover image for the career fact.</small>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="excerpt" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-text-snippet"></i> Short Excerpt / Summary</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Brief summary of the career fact to display on listings..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; font-family: inherit;"><?php echo isset($career_fact->excerpt) ? htmlspecialchars($career_fact->excerpt) : ''; ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label for="content" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-file-text-line"></i> Full Content <span style="color: red;">*</span></label>
            <textarea id="content" name="content" required><?php echo isset($career_fact) ? htmlspecialchars($career_fact->content) : ''; ?></textarea>
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn-submit" style="background-color: #27ae60; color: #fff; padding: 12px 28px; border: none; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ri-save-line"></i> <?php echo isset($career_fact) ? 'Update Career Fact' : 'Save Career Fact'; ?>
            </button>
            <a href="<?php echo site_url('admin/career_facts'); ?>" class="btn-cancel" style="margin-left: 12px; color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 500;">Cancel</a>
        </div>
    </form>
</div>

<!-- CKEditor Rich Text Editor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        height: 350,
        removeButtons: 'PasteFromWord',
        versionCheck: false
    });
</script>
