<div class="data-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #2c3e50;"><i class="ri-book-open-line"></i> Manage Courses</h2>
        <a href="<?php echo site_url('admin/add_course'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-add-line" style="font-size: 18px;"></i> Add New Course
        </a>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Thumbnail</th>
                    <th style="padding: 12px; text-align: left;">Title</th>
                    <th style="padding: 12px; text-align: left;">Price</th>
                    <th style="padding: 12px; text-align: left;">Duration</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                    <th style="padding: 12px; text-align: left;">Created At</th>
                    <th style="padding: 12px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($courses)): ?>
                    <?php foreach($courses as $course): ?>
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 12px; color: #777; font-weight: bold;"><?php echo $course->id; ?></td>
                            <td style="padding: 12px;">
                                <?php if (!empty($course->thumbnail)): ?>
                                    <img src="<?php echo base_url($course->thumbnail); ?>" alt="Thumb" style="width: 55px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=120&q=80" alt="Default Thumb" style="width: 55px; height: 38px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; opacity: 0.6;">
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($course->title); ?></td>
                            <td style="padding: 12px;">
                                <?php 
                                if (!empty($course->tiers)) {
                                    $prices = array_map(function($t) { return (float)$t->price; }, $course->tiers);
                                    $min_p = min($prices);
                                    $max_p = max($prices);
                                    $tier_count = count($course->tiers);
                                    if ($min_p == $max_p) {
                                        echo '₹' . number_format($min_p, 2);
                                    } else {
                                        echo '₹' . number_format($min_p, 0) . ' - ₹' . number_format($max_p, 0) . '<br><small style="color:#777;">(' . $tier_count . ' Tiers)</small>';
                                    }
                                } else {
                                    echo $course->price ? '₹' . number_format($course->price, 2) : 'Free';
                                }
                                ?>
                            </td>
                            <td style="padding: 12px; color: #555;"><?php echo $course->duration ? htmlspecialchars($course->duration) : 'N/A'; ?></td>
                            <td style="padding: 12px;">
                                <span class="status-badge <?php echo $course->status === 'publish' ? 'status-publish' : 'status-draft'; ?>">
                                    <?php echo ucfirst($course->status); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: #777; font-size: 13px;"><?php echo date('M d, Y', strtotime($course->created_at)); ?></td>
                            <td style="padding: 12px; text-align: right;">
                                <a href="<?php echo site_url('admin/course_materials/'.$course->id); ?>" style="color: #dc2626; text-decoration: none; margin-right: 12px; font-size: 19px;" title="Upload & Manage PDF Materials"><i class="ri-file-pdf-2-line"></i></a>
                                <a href="<?php echo site_url('admin/enroll_students/'.$course->id); ?>" style="color: #27ae60; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Enroll Students"><i class="ri-user-add-line"></i></a>
                                <a href="<?php echo site_url('admin/edit_course/'.$course->id); ?>" style="color: #3498db; text-decoration: none; margin-right: 12px; font-size: 18px;" title="Edit"><i class="ri-edit-line"></i></a>
                                <a href="<?php echo site_url('admin/delete_course/'.$course->id); ?>" style="color: #e74c3c; text-decoration: none; font-size: 18px;" title="Delete" onclick="return confirm('Are you sure you want to delete this course?');"><i class="ri-delete-bin-line"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="padding: 20px; text-align: center; color: #666;">No courses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
