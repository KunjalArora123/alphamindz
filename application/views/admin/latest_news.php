<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-newspaper-line"></i> Manage Latest News</h2>
        
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('admin/add_latest_news'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; transition: background-color 0.2s;">
                <i class="ri-add-line" style="font-size: 18px;"></i> Add New Latest News
            </a>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="latestNewsTable">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left; width: 60px;">ID</th>
                    <th style="padding: 12px; text-align: left; width: 80px;">Image</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left; width: 140px;">Tag</th>
                    <th style="padding: 12px; text-align: left; width: 110px;">Status</th>
                    <th style="padding: 12px; text-align: left; width: 130px;">News Date</th>
                    <th style="padding: 12px; text-align: right; width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($latest_news)): ?>
                    <?php foreach($latest_news as $news): ?>
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; color: #777; font-weight: bold;"><?php echo $news->id; ?></td>
                            <td style="padding: 12px;">
                                <?php if (!empty($news->image_url)): ?>
                                    <img src="<?php echo htmlspecialchars($news->image_url); ?>" alt="<?php echo htmlspecialchars($news->title); ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <?php else: ?>
                                    <div style="width: 60px; height: 40px; background-color: #f1f3f5; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                        <i class="ri-image-line"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 15px; margin-bottom: 4px;"><?php echo htmlspecialchars($news->title); ?></div>
                                <?php if(!empty($news->link)): ?>
                                    <div style="font-size: 12px; color: #3498db;"><a href="<?php echo htmlspecialchars($news->link); ?>" target="_blank"><i class="ri-external-link-line"></i> <?php echo htmlspecialchars($news->link); ?></a></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background-color: #f1f3f5; color: #495057;">
                                    <?php echo htmlspecialchars($news->tag); ?> (<?php echo htmlspecialchars($news->tag_color); ?>)
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php if($news->status === 'active'): ?>
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">Active</span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; color: #6c757d; font-size: 13px;">
                                <?php echo !empty($news->news_date) ? date('M d, Y', strtotime($news->news_date)) : '-'; ?>
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="<?php echo site_url('admin/edit_latest_news/'.$news->id); ?>" style="background-color: #f8f9fa; color: #0366d6; border: 1px solid #e1e4e8; padding: 6px 10px; border-radius: 4px; text-decoration: none; transition: all 0.2s; font-size: 14px;" title="Edit News">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <a href="<?php echo site_url('admin/delete_latest_news/'.$news->id); ?>" onclick="return confirm('Are you sure you want to delete this news item?');" style="background-color: #f8f9fa; color: #dc3545; border: 1px solid #e1e4e8; padding: 6px 10px; border-radius: 4px; text-decoration: none; transition: all 0.2s; font-size: 14px;" title="Delete News">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 30px; text-align: center; color: #6c757d;">
                            <i class="ri-newspaper-line" style="font-size: 48px; color: #dee2e6; display: block; margin-bottom: 10px;"></i>
                            <p style="margin: 0; font-size: 16px;">No latest news found.</p>
                            <a href="<?php echo site_url('admin/add_latest_news'); ?>" style="color: #0366d6; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; margin-top: 10px;">Create your first news</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
