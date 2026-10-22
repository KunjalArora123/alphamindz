<div class="data-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-file-pdf-line" style="color: #ef4444; font-size: 24px;"></i> Manage Downloadable Guides & PDFs
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Manage categories and upload PDF study guides, worksheets, and resources shown under Free Resources.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('resources'); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-external-link-line"></i> View Live Guides
            </a>
            <a href="<?php echo site_url('admin/add_guide_category'); ?>" class="btn" style="background-color: #ffffff; color: #2563eb; border: 1.5px solid #2563eb; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-folder-add-line"></i> Add New Category
            </a>
            <a href="<?php echo site_url('admin/add_guide_file' . ($current_cat_id ? '?category_id='.$current_cat_id : '')); ?>" class="btn" style="background-color: #ef4444; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(239,68,68,0.25);">
                <i class="ri-upload-cloud-line"></i> Upload New PDF
            </a>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #475569; margin-right: 4px;">Filter Category:</span>
            <a href="<?php echo site_url('admin/downloadable_guides'); ?>" style="padding: 5px 14px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo empty($current_cat_id) ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">
                All Categories (<?php echo $total_files_count; ?>)
            </a>
            <?php foreach($categories as $c): ?>
                <a href="<?php echo site_url('admin/downloadable_guides?category_id='.$c->id); ?>" style="padding: 5px 14px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_cat_id == $c->id) ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">
                    <i class="<?php echo !empty($c->icon) ? $c->icon : 'ri-folder-line'; ?>"></i> <?php echo htmlspecialchars($c->title); ?> (<?php echo isset($c->file_count) ? $c->file_count : 0; ?>)
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if($current_category): ?>
        <div>
            <a href="<?php echo site_url('admin/edit_guide_category/'.$current_category->id); ?>" style="font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; background: #eff6ff; padding: 4px 10px; border-radius: 6px;">
                <i class="ri-edit-line"></i> Edit "<?php echo htmlspecialchars($current_category->title); ?>" Category
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Files Table -->
    <div style="padding: 24px;">
        <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                        <th style="padding: 12px 16px; text-align: left; width: 50px;">#</th>
                        <th style="padding: 12px 16px; text-align: left;">Title & Filename</th>
                        <th style="padding: 12px 16px; text-align: left; width: 180px;">Category</th>
                        <th style="padding: 12px 16px; text-align: left; width: 100px;">Size</th>
                        <th style="padding: 12px 16px; text-align: left; width: 80px;">Order</th>
                        <th style="padding: 12px 16px; text-align: left; width: 90px;">Status</th>
                        <th style="padding: 12px 16px; text-align: right; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($guide_files)): foreach($guide_files as $f): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                        <td style="padding: 12px 16px; color: #94a3b8; font-weight: 600;"><?php echo $f->id; ?></td>
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                    <i class="ri-file-pdf-fill"></i>
                                </div>
                                <div>
                                    <strong style="color: #0f172a; font-size: 15px; display: block;"><?php echo htmlspecialchars($f->title); ?></strong>
                                    <span style="color: #64748b; font-size: 12px;"><i class="ri-attachment-line"></i> <?php echo htmlspecialchars($f->filename); ?></span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px 16px;">
                            <span style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                <?php echo htmlspecialchars($f->category_title); ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: #64748b; font-size: 13px;">
                            <?php echo htmlspecialchars($f->file_size); ?>
                        </td>
                        <td style="padding: 12px 16px; color: #64748b; font-weight: 600;">
                            <?php echo (int)$f->sort_order; ?>
                        </td>
                        <td style="padding: 12px 16px;">
                            <?php if((int)$f->is_active === 1): ?>
                                <span style="color: #16a34a; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-checkbox-circle-fill"></i> Active
                                </span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-close-circle-fill"></i> Hidden
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?php echo base_url($f->file_path); ?>" target="_blank" style="color: #0f766e; background: #ccfbf1; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;" title="View PDF">
                                    <i class="ri-eye-line"></i> View
                                </a>
                                <a href="<?php echo site_url('admin/edit_guide_file/' . $f->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    <i class="ri-edit-line"></i> Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_guide_file/' . $f->id); ?>" onclick="return confirm('Are you sure you want to delete this PDF: <?php echo htmlspecialchars(addslashes($f->title)); ?>?');" style="color: #dc2626; background: #fef2f2; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" style="padding: 40px 20px; text-align: center; color: #94a3b8;">
                            <i class="ri-file-pdf-line" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            No PDF guides found in this category. Click "Upload New PDF" above to add one.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Category Management Section -->
        <div style="margin-top: 40px; padding-top: 24px; border-top: 2px dashed #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0 0 4px 0; color: #0f172a; font-size: 17px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ri-folders-line" style="color: #2563eb;"></i> Guide Categories / Tracks
                    </h3>
                    <p style="margin: 0; color: #64748b; font-size: 12px;">Add or rename categories appearing in the navigation header dropdown and overview cards.</p>
                </div>
                <a href="<?php echo site_url('admin/add_guide_category'); ?>" style="background-color: #2563eb; color: #fff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-add-line"></i> New Category
                </a>
            </div>

            <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                            <th style="padding: 10px 14px; text-align: left; width: 60px;">Icon</th>
                            <th style="padding: 10px 14px; text-align: left;">Category Title</th>
                            <th style="padding: 10px 14px; text-align: left; width: 140px;">URL Slug</th>
                            <th style="padding: 10px 14px; text-align: left; width: 100px;">Badge</th>
                            <th style="padding: 10px 14px; text-align: left; width: 80px;">PDFs</th>
                            <th style="padding: 10px 14px; text-align: left; width: 70px;">Order</th>
                            <th style="padding: 10px 14px; text-align: left; width: 80px;">Status</th>
                            <th style="padding: 10px 14px; text-align: right; width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $c): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                            <td style="padding: 10px 14px; text-align: center; color: #2563eb; font-size: 18px;">
                                <i class="<?php echo !empty($c->icon) ? $c->icon : 'ri-file-pdf-line'; ?>"></i>
                            </td>
                            <td style="padding: 10px 14px;">
                                <strong style="color: #0f172a;"><?php echo htmlspecialchars($c->title); ?></strong>
                                <span style="display: block; font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($c->description); ?></span>
                            </td>
                            <td style="padding: 10px 14px; color: #64748b; font-family: monospace;">
                                /resources/<?php echo htmlspecialchars($c->slug); ?>
                            </td>
                            <td style="padding: 10px 14px;">
                                <span style="background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                    <?php echo htmlspecialchars($c->badge); ?>
                                </span>
                            </td>
                            <td style="padding: 10px 14px; font-weight: 600; color: #0f172a;">
                                <?php echo isset($c->file_count) ? $c->file_count : 0; ?>
                            </td>
                            <td style="padding: 10px 14px; color: #64748b;">
                                <?php echo (int)$c->sort_order; ?>
                            </td>
                            <td style="padding: 10px 14px;">
                                <?php if((int)$c->is_active === 1): ?>
                                    <span style="color: #16a34a; font-weight: 600; font-size: 11px;">Active</span>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-weight: 600; font-size: 11px;">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 10px 14px; text-align: right;">
                                <a href="<?php echo site_url('admin/edit_guide_category/'.$c->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_guide_category/'.$c->id); ?>" onclick="return confirm('Deleting category <?php echo htmlspecialchars(addslashes($c->title)); ?> will also delete all files in it. Proceed?');" style="color: #dc2626; background: #fef2f2; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; margin-left: 4px;">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
