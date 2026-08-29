<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>All Blogs</h3>
        <a href="<?php echo site_url('admin/add_blog'); ?>" class="btn-primary" style="padding: 8px 16px; text-decoration: none; border-radius: 4px;">Add New Blog</a>
    </div>
    <div class="card-body">
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left;">Author</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Created At</th>
                    <th style="padding: 12px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($blogs)): foreach($blogs as $blog): ?>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 12px;"><?php echo $blog->title; ?></td>
                    <td style="padding: 12px;"><?php echo $blog->author; ?></td>
                    <td style="padding: 12px;">
                        <?php if($blog->status == 'published'): ?>
                            <span style="background-color: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Published</span>
                        <?php else: ?>
                            <span style="background-color: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;"><?php echo date('M d, Y', strtotime($blog->created_at)); ?></td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="<?php echo site_url('admin/edit_blog/'.$blog->id); ?>" style="color: #007bff; text-decoration: none; margin-right: 10px;">Edit</a>
                        <a href="<?php echo site_url('admin/delete_blog/'.$blog->id); ?>" style="color: #dc3545; text-decoration: none;" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center;">No blogs found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
