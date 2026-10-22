<div class="form-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-gallery-line"></i> <?php echo isset($item) ? 'Edit Gallery Media' : 'Add Gallery Media'; ?></h2>
        <a href="<?php echo site_url('admin/gallery'); ?>" style="color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Gallery
        </a>
    </div>

    <form action="<?php echo isset($item) ? site_url('admin/update_gallery_item/'.$item->id) : site_url('admin/save_gallery_item'); ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Media Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($item) ? htmlspecialchars($item->title) : ''; ?>" placeholder="Enter photo or video title..." required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 15px;">
        </div>

        <div style="display: flex; gap: 16px; flex-wrap: wrap;" class="form-group">
            <div style="flex: 1; min-width: 200px;">
                <label for="type" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-folder-video-line"></i> Media Type <span style="color: red;">*</span></label>
                <select id="type" name="type" onchange="handleTypeChange()" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-weight: 600;">
                    <option value="photo" <?php echo (isset($item) && $item->type === 'photo') ? 'selected' : ''; ?>>Photo (Saved in Galary/photos)</option>
                    <option value="video" <?php echo (isset($item) && $item->type === 'video') ? 'selected' : ''; ?>>Video (Saved in Galary/videos or URL)</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label for="category" style="font-weight: 600; color: #343a40; display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <span><i class="ri-price-tag-3-line"></i> Category / Album</span>
                    <a href="<?php echo site_url('admin/gallery#categories-section'); ?>" style="font-size: 11px; color: #2563eb; text-decoration: none; font-weight: 600;" target="_blank"><i class="ri-settings-4-line"></i> Manage Categories</a>
                </label>
                <select id="category" name="category" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-weight: 500;">
                    <?php 
                        $current_cat = isset($item) ? $item->category : 'Events';
                        $cat_found = false;
                    ?>
                    <?php if(!empty($categories)): ?>
                        <?php foreach($categories as $c): ?>
                            <?php 
                                if($current_cat === $c->name) $cat_found = true; 
                                $type_label = ($c->type === 'both') ? '' : ' (' . ucfirst($c->type) . ' only)';
                            ?>
                            <option value="<?php echo htmlspecialchars($c->name); ?>" data-type="<?php echo $c->type; ?>" <?php echo ($current_cat === $c->name) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c->name . $type_label); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if(!$cat_found && !empty($current_cat)): ?>
                        <option value="<?php echo htmlspecialchars($current_cat); ?>" selected><?php echo htmlspecialchars($current_cat); ?> (Custom)</option>
                    <?php endif; ?>
                </select>
            </div>

            <div style="width: 130px;">
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-sort-asc"></i> Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($item) ? (int)$item->sort_order : 0; ?>" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px;">
            </div>

            <div style="flex: 1; min-width: 160px;">
                <label for="status" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-eye-line"></i> Status</label>
                <select id="status" name="status" style="width: 100%; padding: 9px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff;">
                    <option value="active" <?php echo (isset($item) && $item->status === 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo (isset($item) && $item->status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="form-group" style="margin-bottom: 20px;">
            <label id="fileLabel" for="file" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">
                <i class="ri-upload-cloud-line"></i> Upload File
            </label>
            <?php if(isset($item) && !empty($item->file_path)): ?>
                <div style="margin-bottom: 12px;">
                    <?php if($item->type === 'photo'): ?>
                        <img src="<?php echo base_url($item->file_path); ?>" alt="Preview" style="max-width: 220px; max-height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; display: block; margin-bottom: 6px;">
                    <?php else: ?>
                        <video controls style="max-width: 320px; max-height: 180px; border-radius: 8px; border: 1px solid #cbd5e1; display: block; margin-bottom: 6px;">
                            <source src="<?php echo base_url($item->file_path); ?>">
                        </video>
                    <?php endif; ?>
                    <span style="font-size: 12px; color: #64748b;">Current path: <code><?php echo htmlspecialchars($item->file_path); ?></code></span>
                </div>
            <?php endif; ?>
            <input type="file" id="file" name="file" style="width: 100%; padding: 9px; border: 1px solid #ced4da; border-radius: 6px;">
            <small id="fileHint" style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                Upload JPG, PNG, WEBP, or GIF. Files will be saved into <code>Galary/photos</code>.
            </small>
        </div>

        <!-- Video URL Section (Optional for YouTube / Vimeo / CDN) -->
        <div class="form-group" id="videoUrlGroup" style="margin-bottom: 20px; display: none;">
            <label for="video_url" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">
                <i class="ri-youtube-line" style="color: red;"></i> Video Embed URL (Optional for YouTube / External Link)
            </label>
            <input type="url" id="video_url" name="video_url" value="<?php echo isset($item) ? htmlspecialchars($item->video_url) : ''; ?>" placeholder="https://www.youtube.com/watch?v=... or direct MP4 URL" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px;">
            <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 4px;">Paste YouTube link or embed URL if you are not uploading a local video file.</small>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label for="description" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;"><i class="ri-file-text-line"></i> Description / Caption (Optional)</label>
            <textarea id="description" name="description" rows="3" placeholder="Brief description of this photo or video..." style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; font-family: inherit;"><?php echo isset($item) ? htmlspecialchars($item->description) : ''; ?></textarea>
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn-submit" style="background-color: #27ae60; color: #fff; padding: 12px 28px; border: none; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="ri-save-line"></i> <?php echo isset($item) ? 'Update Media' : 'Upload & Save Media'; ?>
            </button>
            <a href="<?php echo site_url('admin/gallery'); ?>" class="btn-cancel" style="margin-left: 12px; color: #6c757d; text-decoration: none; font-size: 14px; font-weight: 500;">Cancel</a>
        </div>
    </form>
</div>

<script>
function handleTypeChange() {
    var type = document.getElementById('type').value;
    var fileLabel = document.getElementById('fileLabel');
    var fileHint = document.getElementById('fileHint');
    var fileInput = document.getElementById('file');
    var videoUrlGroup = document.getElementById('videoUrlGroup');

    if (type === 'video') {
        fileLabel.innerHTML = '<i class="ri-video-upload-line"></i> Upload Video File (Saved to Galary/videos)';
        fileHint.innerHTML = 'Upload MP4, WEBM, MOV, or MKV video. Files will be saved in <code>Galary/videos</code>.';
        fileInput.setAttribute('accept', 'video/*');
        videoUrlGroup.style.display = 'block';
    } else {
        fileLabel.innerHTML = '<i class="ri-image-line"></i> Upload Photo (Saved to Galary/photos)';
        fileHint.innerHTML = 'Upload JPG, PNG, WEBP, or GIF. Files will be saved in <code>Galary/photos</code>.';
        fileInput.setAttribute('accept', 'image/*');
        videoUrlGroup.style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', handleTypeChange);
</script>
