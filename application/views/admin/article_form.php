<div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-article-line"></i> <?php echo isset($article) ? 'Edit Article' : 'Add New Article'; ?></h2>
        <a href="<?php echo site_url('admin/articles'); ?>" style="color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Articles
        </a>
    </div>

    <form action="<?php echo isset($article) ? site_url('admin/update_article/'.$article->id) : site_url('admin/save_article'); ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Article Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($article) ? htmlspecialchars($article->title) : ''; ?>" placeholder="Enter article title..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
        </div>

        <div style="display: flex; gap: 16px; flex-wrap: wrap;" class="form-group">
            <div style="flex: 1; min-width: 240px;">
                <label for="category" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-price-tag-3-line"></i> Category</label>
                <input type="text" id="category" name="category" list="category_list" value="<?php echo isset($article->category) ? htmlspecialchars($article->category) : 'General'; ?>" placeholder="e.g. Career, Education, Growth..." style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
                <datalist id="category_list">
                    <option value="Career">
                    <option value="Education">
                    <option value="Growth">
                    <option value="Voice Master">
                    <option value="RJ Training">
                    <option value="Executive Series">
                    <option value="Marketing">
                </datalist>
            </div>

            <div style="flex: 1; min-width: 240px;">
                <label for="author" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-user-3-line"></i> Author</label>
                <input type="text" id="author" name="author" value="<?php echo isset($article) ? htmlspecialchars($article->author) : 'Alpha Mindz Team'; ?>" placeholder="Author name" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label for="status" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-eye-line"></i> Status</label>
                <select id="status" name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff;">
                    <option value="published" <?php echo (isset($article) && $article->status === 'published') ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo (isset($article) && $article->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="image" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-image-line"></i> Article Cover Image</label>
            <?php 
            $curr_img = isset($article) ? (!empty($article->image_url) ? $article->image_url : (!empty($article->image) ? $article->image : '')) : '';
            if (!empty($curr_img)): 
                $curr_src = (strpos($curr_img, 'http://') === 0 || strpos($curr_img, 'https://') === 0) ? $curr_img : base_url($curr_img);
            ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo $curr_src; ?>" alt="Cover Preview" style="max-width: 200px; max-height: 130px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; display: block; margin-bottom: 6px;">
                    <span style="font-size: 12px; color: #6c757d;">Current image: <code><?php echo htmlspecialchars($curr_img); ?></code></span>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 6px;">
            <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 4px;">Upload JPG, PNG, WEBP cover image for the article.</small>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="excerpt" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-text-snippet"></i> Short Excerpt / Summary</label>
            <textarea id="excerpt" name="excerpt" rows="3" placeholder="Brief summary of the article to display on homepage and listings..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; font-family: inherit;"><?php echo isset($article->excerpt) ? htmlspecialchars($article->excerpt) : ''; ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label for="content" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-file-text-line"></i> Full Article Content <span style="color: red;">*</span></label>
            <textarea id="content" name="content" required><?php echo isset($article) ? htmlspecialchars($article->content) : ''; ?></textarea>
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn-submit" style="background-color: #27ae60; color: #fff; padding: 12px 28px; border: none; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ri-save-line"></i> <?php echo isset($article) ? 'Update Article' : 'Save Article'; ?>
            </button>
            <a href="<?php echo site_url('admin/articles'); ?>" class="btn-cancel" style="margin-left: 12px; color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 500;">Cancel</a>
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
