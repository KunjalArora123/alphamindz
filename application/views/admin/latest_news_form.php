<div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-newspaper-line"></i> <?php echo isset($news) ? 'Edit Latest News' : 'Add New Latest News'; ?></h2>
        <a href="<?php echo site_url('admin/latest_news'); ?>" style="color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Latest News
        </a>
    </div>

    <form action="<?php echo isset($news) ? site_url('admin/update_latest_news/'.$news->id) : site_url('admin/save_latest_news'); ?>" method="POST">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">News Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($news) ? htmlspecialchars($news->title) : ''; ?>" placeholder="Enter news title..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
        </div>

        <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px;" class="form-group">
            <div style="flex: 1; min-width: 240px;">
                <label for="tag" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Tag Name</label>
                <input type="text" id="tag" name="tag" value="<?php echo isset($news) ? htmlspecialchars($news->tag) : ''; ?>" placeholder="e.g. Career, Education" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
            </div>
            
            <div style="flex: 1; min-width: 240px;">
                <label for="tag_color" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Tag Color</label>
                <select id="tag_color" name="tag_color" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
                    <option value="bg-green" <?php echo (isset($news) && $news->tag_color == 'bg-green') ? 'selected' : ''; ?>>Green (e.g. Career)</option>
                    <option value="bg-blue" <?php echo (isset($news) && $news->tag_color == 'bg-blue') ? 'selected' : ''; ?>>Blue (e.g. Education)</option>
                    <option value="bg-pink" <?php echo (isset($news) && $news->tag_color == 'bg-pink') ? 'selected' : ''; ?>>Pink (e.g. Growth)</option>
                    <option value="bg-orange" <?php echo (isset($news) && $news->tag_color == 'bg-orange') ? 'selected' : ''; ?>>Orange</option>
                    <option value="bg-purple" <?php echo (isset($news) && $news->tag_color == 'bg-purple') ? 'selected' : ''; ?>>Purple</option>
                </select>
            </div>
        </div>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="link" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Link URL</label>
            <input type="text" id="link" name="link" value="<?php echo isset($news) ? htmlspecialchars($news->link) : '#'; ?>" placeholder="https://..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="image_url" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Image URL</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo isset($news) ? htmlspecialchars($news->image_url) : ''; ?>" placeholder="https://..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
            <small style="color: #6c757d; margin-top: 4px; display: block;">Provide an external image link (e.g. Unsplash) or a relative path.</small>
        </div>

        <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;" class="form-group">
            <div style="flex: 1; min-width: 240px;">
                <label for="news_date" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">News Date</label>
                <input type="date" id="news_date" name="news_date" value="<?php echo isset($news) ? $news->news_date : date('Y-m-d'); ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
            </div>

            <div style="flex: 1; min-width: 240px;">
                <label for="status" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Status</label>
                <select id="status" name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
                    <option value="active" <?php echo (isset($news) && $news->status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo (isset($news) && $news->status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div style="border-top: 2px solid #f1f3f5; padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?php echo site_url('admin/latest_news'); ?>" style="padding: 10px 18px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; color: #495057; font-weight: 600; text-decoration: none;">Cancel</a>
            <button type="submit" style="padding: 10px 24px; background-color: #27ae60; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                <i class="ri-save-line"></i> <?php echo isset($news) ? 'Update News' : 'Save News'; ?>
            </button>
        </div>
    </form>
</div>
