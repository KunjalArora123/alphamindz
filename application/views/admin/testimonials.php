<div class="data-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-feedback-line" style="color: #2563eb; font-size: 24px;"></i> Student Testimonials
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Manage client and student reviews, feedback, and testimonials featured on the homepage carousel.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo base_url('#testimonials'); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-external-link-line"></i> View on Homepage
            </a>
            <a href="<?php echo site_url('admin/add_testimonial'); ?>" class="btn" style="background-color: #2563eb; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <i class="ri-add-line"></i> Add Testimonial
            </a>
        </div>
    </div>

    <div style="padding: 24px;">
        <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                        <th style="padding: 12px 16px; text-align: left; width: 60px;">Avatar</th>
                        <th style="padding: 12px 16px; text-align: left; width: 220px;">Student / Author</th>
                        <th style="padding: 12px 16px; text-align: left;">Quote / Feedback</th>
                        <th style="padding: 12px 16px; text-align: left; width: 80px;">Order</th>
                        <th style="padding: 12px 16px; text-align: left; width: 90px;">Status</th>
                        <th style="padding: 12px 16px; text-align: right; width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($testimonials)): foreach($testimonials as $t): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                        <td style="padding: 12px 16px;">
                            <?php 
                                $bg_class = !empty($t->avatar_bg) ? $t->avatar_bg : 'bg-blue';
                                $bg_colors = [
                                    'bg-blue'   => '#2563eb',
                                    'bg-pink'   => '#ec4899',
                                    'bg-green'  => '#10b981',
                                    'bg-purple' => '#8b5cf6',
                                    'bg-amber'  => '#f59e0b',
                                    'bg-teal'   => '#14b8a6',
                                    'bg-indigo' => '#6366f1'
                                ];
                                $hex = isset($bg_colors[$bg_class]) ? $bg_colors[$bg_class] : '#2563eb';
                            ?>
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: <?php echo $hex; ?>; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; text-transform: uppercase;">
                                <?php echo htmlspecialchars($t->avatar_initials ?: 'AM'); ?>
                            </div>
                        </td>
                        <td style="padding: 12px 16px;">
                            <strong style="color: #0f172a; font-size: 15px; display: block;"><?php echo htmlspecialchars($t->author_name); ?></strong>
                            <span style="color: #64748b; font-size: 12px;"><?php echo htmlspecialchars($t->author_role); ?></span>
                        </td>
                        <td style="padding: 12px 16px; color: #334155; line-height: 1.5; font-size: 13px;">
                            <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?php echo htmlspecialchars($t->quote); ?>">
                                "<?php echo htmlspecialchars(trim($t->quote, '"')); ?>"
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: #64748b; font-weight: 600;">
                            <?php echo (int)$t->sort_order; ?>
                        </td>
                        <td style="padding: 12px 16px;">
                            <?php if($t->status === 'active'): ?>
                                <span style="color: #16a34a; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-checkbox-circle-fill"></i> Active
                                </span>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-close-circle-fill"></i> Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?php echo site_url('admin/edit_testimonial/' . $t->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    <i class="ri-edit-line"></i> Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_testimonial/' . $t->id); ?>" onclick="return confirm('Are you sure you want to delete this testimonial by <?php echo htmlspecialchars(addslashes($t->author_name)); ?>?');" style="color: #dc2626; background: #fef2f2; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">
                            No testimonials found. Click "Add Testimonial" above to create one.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
