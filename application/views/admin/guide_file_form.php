<div class="form-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; padding: 24px; max-width: 700px; margin: 0 auto 30px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #f1f3f5; padding-bottom: 16px;">
        <h2 style="margin: 0; color: #2c3e50; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-file-pdf-fill" style="color: #ef4444;"></i> <?php echo isset($file) ? 'Edit Guide PDF' : 'Upload Guide PDF'; ?>
        </h2>
        <a href="<?php echo site_url('admin/downloadable_guides' . (isset($file) ? '?category_id='.$file->category_id : '')); ?>" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Guides
        </a>
    </div>

    <form action="<?php echo isset($file) ? site_url('admin/update_guide_file/'.$file->id) : site_url('admin/save_guide_file'); ?>" method="POST" enctype="multipart/form-data">
        
        <div style="margin-bottom: 18px;">
            <label for="title" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Guide / Worksheet Title <span style="color: red;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo isset($file) ? htmlspecialchars($file->title) : ''; ?>" placeholder="e.g. IELTS Reading Strategy & Formula Guide" required style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 18px;">
            <label for="category_id" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Assigned Category / Track <span style="color: red;">*</span></label>
            <select id="category_id" name="category_id" required style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                <?php 
                    $selected_cat = isset($file) ? $file->category_id : (isset($category_id) ? $category_id : 0);
                    foreach($categories as $c): 
                ?>
                    <option value="<?php echo $c->id; ?>" <?php echo ($selected_cat == $c->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c->title); ?> (/resources/<?php echo htmlspecialchars($c->slug); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- File Upload Section -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 18px;">
            <label style="font-weight: 600; color: #343a40; display: block; margin-bottom: 8px;">
                PDF Document File <?php echo !isset($file) ? '<span style="color: red;">*</span>' : ''; ?>
            </label>
            
            <?php if(isset($file) && !empty($file->file_path)): ?>
                <div style="display: flex; align-items: center; justify-content: space-between; background: #fff; border: 1px solid #cbd5e1; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="ri-file-pdf-fill" style="color: #ef4444; font-size: 24px;"></i>
                        <div>
                            <span style="font-weight: 600; font-size: 13px; color: #0f172a; display: block;"><?php echo htmlspecialchars($file->filename); ?></span>
                            <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($file->file_size); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo base_url($file->file_path); ?>" target="_blank" style="color: #2563eb; font-size: 12px; font-weight: 600; text-decoration: none;">
                        <i class="ri-external-link-line"></i> View Existing
                    </a>
                </div>
            <?php endif; ?>

            <input type="file" name="pdf_file" accept=".pdf,application/pdf" <?php echo !isset($file) ? 'required' : ''; ?> style="font-size: 13px;">
            <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">Allowed formats: PDF (.pdf) only. Max file size: 50MB.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 24px;">
            <div>
                <label for="sort_order" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Sort Order (Lowest first)</label>
                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($file) ? (int)$file->sort_order : 0; ?>" style="width: 100%; padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
            </div>

            <div>
                <label for="is_active" style="font-weight: 600; color: #343a40; display: block; margin-bottom: 6px;">Visibility Status</label>
                <select id="is_active" name="is_active" style="width: 100%; padding: 10px 12px; border: 1px solid #ced4da; border-radius: 6px; background-color: #fff; font-size: 14px; box-sizing: border-box;">
                    <option value="1" <?php echo (!isset($file) || (int)$file->is_active === 1) ? 'selected' : ''; ?>>Active (Visible on public resources)</option>
                    <option value="0" <?php echo (isset($file) && (int)$file->is_active === 0) ? 'selected' : ''; ?>>Hidden (Do not display publicly)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #eee; padding-top: 18px;">
            <a href="<?php echo site_url('admin/downloadable_guides'); ?>" style="padding: 10px 18px; border-radius: 6px; border: 1px solid #ced4da; color: #495057; text-decoration: none; font-size: 14px; font-weight: 600;">
                Cancel
            </a>
            <button type="submit" style="background-color: #ef4444; color: #fff; padding: 10px 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(239,68,68,0.25);">
                <i class="ri-check-line"></i> <?php echo isset($file) ? 'Save PDF Updates' : 'Upload & Save PDF'; ?>
            </button>
        </div>

    </form>
</div>
