<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-quill-pen-line"></i> Manage Blogs</h2>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="position: relative;">
                <input type="text" id="blogSearchInput" placeholder="Search by title, author, category..." style="padding: 9px 14px 9px 36px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; width: 280px; transition: border-color 0.2s;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 16px;"></i>
            </div>
            
            <a href="<?php echo site_url('admin/add_blog'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;">
                <i class="ri-add-line" style="font-size: 18px;"></i> Add New Blog
            </a>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="blogsTable">
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
                <?php if(!empty($blogs)): ?>
                    <?php foreach($blogs as $blog): ?>
                        <tr class="blog-row" style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; color: #777; font-weight: bold;"><?php echo $blog->id; ?></td>
                            <td style="padding: 12px;">
                                <?php 
                                $cover_img = !empty($blog->image) ? $blog->image : (!empty($blog->image_url) ? $blog->image_url : '');
                                if (!empty($cover_img)): 
                                    $cover_src = (strpos($cover_img, 'http://') === 0 || strpos($cover_img, 'https://') === 0) ? $cover_img : base_url($cover_img);
                                ?>
                                    <img src="<?php echo $cover_src; ?>" alt="Cover" style="width: 55px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" onerror="this.src='https://via.placeholder.com/55x38?text=Blog';">
                                <?php else: ?>
                                    <div style="width: 55px; height: 38px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                        <i class="ri-quill-pen-line" style="font-size: 20px;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 15px;" class="search-title">
                                    <a href="<?php echo site_url('blogs/'.$blog->slug); ?>" target="_blank" style="color: inherit; text-decoration: none;">
                                        <?php echo htmlspecialchars($blog->title); ?>
                                    </a>
                                </div>
                                <span class="search-category" style="display: inline-block; background: #fdf2f8; color: #be185d; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-top: 4px;">
                                    <?php echo !empty($blog->category) ? htmlspecialchars($blog->category) : 'Blogs'; ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: #495057; font-size: 14px;" class="search-author">
                                <i class="ri-user-3-line" style="color: #6c757d; font-size: 13px;"></i> <?php echo !empty($blog->author) ? htmlspecialchars($blog->author) : 'Alpha Mindz'; ?>
                            </td>
                            <td style="padding: 12px;">
                                <a href="<?php echo site_url('admin/toggle_blog_status/'.$blog->id); ?>" style="text-decoration: none;" title="Click to toggle status">
                                    <?php if($blog->status === 'published'): ?>
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
                                <?php echo date('M d, Y', strtotime($blog->created_at)); ?>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <a href="<?php echo site_url('blogs/'.$blog->slug); ?>" target="_blank" style="color: #6c757d; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Preview Blog"><i class="ri-external-link-line"></i></a>
                                <a href="<?php echo site_url('admin/edit_blog/'.$blog->id); ?>" style="color: #3498db; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Edit Blog"><i class="ri-edit-line"></i></a>
                                <a href="<?php echo site_url('admin/delete_blog/'.$blog->id); ?>" style="color: #e74c3c; text-decoration: none; font-size: 18px;" title="Delete Blog" onclick="return confirm('Are you sure you want to delete this blog?');"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="noBlogsRow">
                        <td colspan="7" style="padding: 30px; text-align: center; color: #6c757d;">
                            <i class="ri-quill-pen-line" style="font-size: 36px; display: block; margin-bottom: 8px; color: #adb5bd;"></i>
                            No blogs found. Click "Add New Blog" to write your first blog post.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('blogSearchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.blog-row');
    rows.forEach(function(row) {
        var text = row.innerText.toLowerCase();
        row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
    });
});
</script>
