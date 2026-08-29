<div class="card">
    <div class="card-header">
        <h3><?php echo isset($blog) ? 'Edit Blog' : 'Add New Blog'; ?></h3>
    </div>
    <div class="card-body">
        <form action="<?php echo isset($blog) ? site_url('admin/update_blog/'.$blog->id) : site_url('admin/save_blog'); ?>" method="post" style="max-width: 800px;">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="title" style="display: block; margin-bottom: 5px; font-weight: 500;">Blog Title</label>
                <input type="text" name="title" id="title" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo isset($blog) ? $blog->title : ''; ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="author" style="display: block; margin-bottom: 5px; font-weight: 500;">Author</label>
                <input type="text" name="author" id="author" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" value="<?php echo isset($blog) ? $blog->author : ''; ?>" placeholder="E.g. John Doe" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="editor_content" style="display: block; margin-bottom: 5px; font-weight: 500;">Content</label>
                <textarea name="content" id="editor_content" class="form-control" style="width: 100%; height: 300px; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" required><?php echo isset($blog) ? $blog->content : ''; ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="status" style="display: block; margin-bottom: 5px; font-weight: 500;">Status</label>
                <select name="status" id="status" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    <option value="draft" <?php echo (isset($blog) && $blog->status == 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo (isset($blog) && $blog->status == 'published') ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Save Blog</button>
                <a href="<?php echo site_url('admin/blogs'); ?>" style="margin-left: 10px; color: #6c757d; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>
</div>
