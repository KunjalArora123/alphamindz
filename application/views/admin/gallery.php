<div class="data-card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0; color: #2c3e50;"><i class="ri-gallery-line"></i> Manage Gallery Media</h2>
            <div style="margin-top: 6px; display: flex; gap: 8px;">
                <a href="<?php echo site_url('admin/gallery'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo empty($current_type) ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>">All</a>
                <a href="<?php echo site_url('admin/gallery?type=photo'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_type === 'photo') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>"><i class="ri-image-line"></i> Photos</a>
                <a href="<?php echo site_url('admin/gallery?type=video'); ?>" style="padding: 4px 12px; border-radius: 20px; font-size: 13px; text-decoration: none; font-weight: 600; <?php echo ($current_type === 'video') ? 'background: #2563eb; color: #fff;' : 'background: #e2e8f0; color: #475569;'; ?>"><i class="ri-video-line"></i> Videos</a>
            </div>
        </div>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="position: relative;">
                <input type="text" id="gallerySearchInput" placeholder="Search by title, category..." style="padding: 9px 14px 9px 36px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; width: 240px; transition: border-color 0.2s;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 16px;"></i>
            </div>
            
            <a href="#categories-section" style="background-color: #4b5563; color: #fff; text-decoration: none; padding: 10px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-price-tag-3-line"></i> Manage Categories
            </a>

            <a href="<?php echo site_url('admin/add_gallery_item'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;">
                <i class="ri-add-line" style="font-size: 18px;"></i> Add Media
            </a>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="galleryTable">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 60px;">ID</th>
                    <th style="padding: 12px; text-align: left; width: 100px;">Preview</th>
                    <th style="padding: 12px; text-align: left;">Title & Category</th>
                    <th style="padding: 12px; text-align: left; width: 90px;">Type</th>
                    <th style="padding: 12px; text-align: left; width: 80px;">Order</th>
                    <th style="padding: 12px; text-align: left; width: 110px;">Status</th>
                    <th style="padding: 12px; text-align: left; width: 130px;">Date Added</th>
                    <th style="padding: 12px; text-align: right; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($gallery_items)): ?>
                    <?php foreach($gallery_items as $item): ?>
                        <tr class="gallery-row" style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; color: #777; font-weight: bold;"><?php echo $item->id; ?></td>
                            <td style="padding: 12px;">
                                <?php if($item->type === 'photo'): ?>
                                    <?php if(!empty($item->file_path) && file_exists(FCPATH . $item->file_path)): ?>
                                        <img src="<?php echo base_url($item->file_path); ?>" alt="Preview" style="width: 60px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 42px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                            <i class="ri-image-line" style="font-size: 20px;"></i>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div style="width: 60px; height: 42px; background: #fee2e2; color: #ef4444; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                        <i class="ri-video-fill"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 15px;" class="search-title">
                                    <?php echo htmlspecialchars($item->title); ?>
                                </div>
                                <span class="search-category" style="display: inline-block; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 4px;">
                                    <?php echo htmlspecialchars($item->category ?: 'General'); ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php if($item->type === 'photo'): ?>
                                    <span style="background: #ecfdf5; color: #059669; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                        <i class="ri-image-line"></i> Photo
                                    </span>
                                <?php else: ?>
                                    <span style="background: #fef2f2; color: #dc2626; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                        <i class="ri-video-line"></i> Video
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; color: #64748b; font-weight: 600;">
                                <?php echo $item->sort_order; ?>
                            </td>
                            <td style="padding: 12px;">
                                <a href="<?php echo site_url('admin/toggle_gallery_status/'.$item->id); ?>" style="text-decoration: none;" title="Toggle Status">
                                    <?php if($item->status === 'active'): ?>
                                        <span style="background-color: #d4edda; color: #155724; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-checkbox-circle-fill"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span style="background-color: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-close-circle-fill"></i> Inactive
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td style="padding: 12px; color: #6c757d; font-size: 13px;">
                                <?php echo date('M d, Y', strtotime($item->created_at)); ?>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <a href="<?php echo site_url('gallery'); ?>" target="_blank" style="color: #6c757d; text-decoration: none; margin-right: 12px; font-size: 18px;" title="View Live Gallery"><i class="ri-external-link-line"></i></a>
                                <a href="<?php echo site_url('admin/edit_gallery_item/'.$item->id); ?>" style="color: #3498db; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Edit Media"><i class="ri-edit-line"></i></a>
                                <a href="<?php echo site_url('admin/delete_gallery_item/'.$item->id); ?>" style="color: #e74c3c; text-decoration: none; font-size: 18px;" title="Delete Media" onclick="return confirm('Are you sure you want to delete this media item?');"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noGalleryRow">
                        <td colspan="8" style="padding: 40px; text-align: center; color: #6c757d;">
                            <i class="ri-gallery-line" style="font-size: 40px; display: block; margin-bottom: 8px; color: #adb5bd;"></i>
                            No gallery media found. Click "Add Media" to upload photos or videos.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================================= -->
<!-- GALLERY CATEGORIES MANAGEMENT SECTION -->
<!-- ============================================================= -->
<div class="data-card" id="categories-section" style="margin-top: 30px; border-top: 3px solid #2563eb;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0; color: #2c3e50;"><i class="ri-price-tag-3-line" style="color: #2563eb;"></i> Gallery Categories</h2>
            <p style="margin: 4px 0 0 0; color: #64748b; font-size: 13px;">Manage photo & video albums/categories for the public gallery and admin uploads.</p>
        </div>
        <div>
            <button type="button" onclick="openAddCategoryModal()" style="background-color: #2563eb; color: #fff; border: none; padding: 9px 16px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-add-line" style="font-size: 16px;"></i> Add Category
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 10px 12px; text-align: left; width: 60px;">#</th>
                    <th style="padding: 10px 12px; text-align: left;">Category Name</th>
                    <th style="padding: 10px 12px; text-align: left; width: 140px;">Applies To</th>
                    <th style="padding: 10px 12px; text-align: left; width: 100px;">Sort Order</th>
                    <th style="padding: 10px 12px; text-align: right; width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($categories)): ?>
                    <?php foreach($categories as $idx => $cat): ?>
                        <tr style="border-bottom: 1px solid #f1f3f5;">
                            <td style="padding: 12px; color: #777; font-weight: 600;"><?php echo $idx + 1; ?></td>
                            <td style="padding: 12px; font-weight: 600; color: #1e293b;">
                                <i class="ri-folder-line" style="color: #3b82f6; margin-right: 6px;"></i>
                                <?php echo htmlspecialchars($cat->name); ?>
                            </td>
                            <td style="padding: 12px;">
                                <?php if($cat->type === 'photo'): ?>
                                    <span style="background: #ecfdf5; color: #059669; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">
                                        <i class="ri-image-line"></i> Photos
                                    </span>
                                <?php elseif($cat->type === 'video'): ?>
                                    <span style="background: #fef2f2; color: #dc2626; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">
                                        <i class="ri-video-line"></i> Videos
                                    </span>
                                <?php else: ?>
                                    <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">
                                        <i class="ri-folders-line"></i> Both
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; color: #64748b; font-weight: 600;">
                                <?php echo (int)$cat->sort_order; ?>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <button type="button" onclick="openEditCategoryModal(<?php echo $cat->id; ?>, '<?php echo htmlspecialchars(addslashes($cat->name)); ?>', '<?php echo $cat->type; ?>', <?php echo (int)$cat->sort_order; ?>)" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 18px; margin-right: 8px;" title="Edit Category">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <a href="<?php echo site_url('admin/delete_gallery_category/'.$cat->id); ?>" onclick="return confirm('Are you sure you want to delete category \'<?php echo htmlspecialchars(addslashes($cat->name)); ?>\'? Any media using this category will be reassigned to General.');" style="color: #ef4444; text-decoration: none; font-size: 18px;" title="Delete Category">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">
                            No custom categories added yet. Click "+ Add Category" to create one.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal (Add / Edit) -->
<div id="categoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; align-items: center; justify-content: center;">
    <div style="background: #fff; width: 440px; max-width: 92%; border-radius: 10px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
            <h3 id="modalTitle" style="margin: 0; color: #1e293b; font-size: 18px;">Add Gallery Category</h3>
            <button type="button" onclick="closeCategoryModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="categoryForm" action="<?php echo site_url('admin/add_gallery_category'); ?>" method="POST">
            <div style="margin-bottom: 14px;">
                <label for="cat_name" style="display: block; font-weight: 600; font-size: 13px; color: #334155; margin-bottom: 6px;">Category Name <span style="color: red;">*</span></label>
                <input type="text" id="cat_name" name="name" required placeholder="e.g. Annual Sports, Alumni Meet..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            </div>
            <div style="margin-bottom: 14px;">
                <label for="cat_type" style="display: block; font-weight: 600; font-size: 13px; color: #334155; margin-bottom: 6px;">Applicable Media Type</label>
                <select id="cat_type" name="type" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #fff;">
                    <option value="both">Both (Photos & Videos)</option>
                    <option value="photo">Photos Only</option>
                    <option value="video">Videos Only</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="cat_sort" style="display: block; font-weight: 600; font-size: 13px; color: #334155; margin-bottom: 6px;">Sort Order</label>
                <input type="number" id="cat_sort" name="sort_order" value="0" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeCategoryModal()" style="padding: 8px 14px; border: 1px solid #cbd5e1; background: #f8fafc; color: #475569; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Cancel</button>
                <button type="submit" id="modalSubmitBtn" style="padding: 8px 18px; border: none; background: #2563eb; color: #fff; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('gallerySearchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.gallery-row');
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
    });
});

function openAddCategoryModal() {
    document.getElementById('modalTitle').innerText = 'Add Gallery Category';
    document.getElementById('categoryForm').action = '<?php echo site_url('admin/add_gallery_category'); ?>';
    document.getElementById('cat_name').value = '';
    document.getElementById('cat_type').value = 'both';
    document.getElementById('cat_sort').value = '0';
    document.getElementById('modalSubmitBtn').innerText = 'Add Category';
    document.getElementById('categoryModal').style.display = 'flex';
}

function openEditCategoryModal(id, name, type, sort) {
    document.getElementById('modalTitle').innerText = 'Edit Gallery Category';
    document.getElementById('categoryForm').action = '<?php echo site_url('admin/update_gallery_category/'); ?>' + id;
    document.getElementById('cat_name').value = name;
    document.getElementById('cat_type').value = type;
    document.getElementById('cat_sort').value = sort;
    document.getElementById('modalSubmitBtn').innerText = 'Update Category';
    document.getElementById('categoryModal').style.display = 'flex';
}

function closeCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
}
</script>
