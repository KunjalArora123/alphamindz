<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-briefcase-line"></i> Manage Career Facts</h2>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="position: relative;">
                <input type="text" id="cfSearchInput" placeholder="Search by title, author, category..." style="padding: 9px 14px 9px 36px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; width: 280px; transition: border-color 0.2s;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 16px;"></i>
            </div>
            
            <a href="<?php echo site_url('admin/add_career_fact'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;">
                <i class="ri-add-line" style="font-size: 18px;"></i> Add New Career Fact
            </a>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="cfTable">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 60px;">ID</th>
                    <th style="padding: 12px; text-align: left; width: 80px;">Cover</th>
                    <th style="padding: 12px; text-align: left;">Title & Category</th>
                    <th style="padding: 12px; text-align: left; width: 140px;">Author</th>
                    <th style="padding: 12px; text-align: left; width: 110px;">Status</th>
                    <th style="padding: 12px; text-align: left; width: 130px;">Created Date</th>
                    <th style="padding: 12px; text-align: right; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($career_facts)): ?>
                    <?php foreach($career_facts as $cf): ?>
                        <tr class="cf-row" style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; color: #777; font-weight: bold;"><?php echo $cf->id; ?></td>
                            <td style="padding: 12px;">
                                <?php 
                                $cover_img = !empty($cf->image) ? $cf->image : (!empty($cf->image_url) ? $cf->image_url : '');
                                if (!empty($cover_img)): 
                                    $cover_src = (strpos($cover_img, 'http://') === 0 || strpos($cover_img, 'https://') === 0) ? $cover_img : base_url($cover_img);
                                ?>
                                    <img src="<?php echo $cover_src; ?>" alt="Cover" style="width: 55px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='https://via.placeholder.com/55x38?text=Fact';">
                                <?php else: ?>
                                    <div style="width: 55px; height: 38px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                        <i class="ri-briefcase-line" style="font-size: 20px;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 15px;" class="search-title">
                                    <a href="<?php echo site_url('career-facts/'.$cf->slug); ?>" target="_blank" style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($cf->title); ?>
                                    </a>
                                </div>
                                <span class="search-category" style="display: inline-block; background: #ecfdf5; color: #059669; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 4px;">
                                    <?php echo !empty($cf->category) ? htmlspecialchars($cf->category) : 'Career Facts'; ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: #495057; font-size: 14px;" class="search-author">
                                <i class="ri-user-3-line" style="color: #6c757d; font-size: 13px;"></i> <?php echo !empty($cf->author) ? htmlspecialchars($cf->author) : 'Alpha Mindz'; ?>
                            </td>
                            <td style="padding: 12px;">
                                <a href="<?php echo site_url('admin/toggle_career_fact_status/'.$cf->id); ?>" style="text-decoration: none;" title="Click to toggle status">
                                    <?php if($cf->status === 'published'): ?>
                                        <span style="background-color: #d4edda; color: #155724; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-checkbox-circle-fill"></i> Published
                                        </span>
                                    <?php else: ?>
                                        <span style="background-color: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-draft-fill"></i> Draft
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td style="padding: 12px; color: #6c757d; font-size: 13px;">
                                <?php echo date('M d, Y', strtotime($cf->created_at)); ?>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <a href="<?php echo site_url('career-facts/'.$cf->slug); ?>" target="_blank" style="color: #6c757d; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Preview Career Fact"><i class="ri-external-link-line"></i></a>
                                <a href="<?php echo site_url('admin/edit_career_fact/'.$cf->id); ?>" style="color: #3498db; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Edit Career Fact"><i class="ri-edit-line"></i></a>
                                <a href="<?php echo site_url('admin/delete_career_fact/'.$cf->id); ?>" style="color: #e74c3c; text-decoration: none; font-size: 18px;" title="Delete Career Fact" onclick="return confirm('Are you sure you want to delete this career fact?');"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noCfRow">
                        <td colspan="7" style="padding: 30px; text-align: center; color: #6c757d;">
                            <i class="ri-briefcase-line" style="font-size: 36px; display: block; margin-bottom: 8px; color: #adb5bd;"></i>
                            No career facts found. Click "Add New Career Fact" to write your first entry.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('cfSearchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.cf-row');
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
    });
});
</script>
