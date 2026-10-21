<div class="data-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-information-line" style="color: #2563eb; font-size: 24px;"></i> Manage About Us Page
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Update the public About Us page header banner, regional branch details, and team member profiles.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('about'); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-external-link-line"></i> View Live About Page
            </a>
            <a href="<?php echo site_url('admin/add_team_member'); ?>" class="btn" style="background-color: #2563eb; color: #ffffff; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <i class="ri-user-add-line"></i> Add New Team Member
            </a>
        </div>
    </div>

    <div style="padding: 24px;">
        <!-- General Story & Branch Information Form -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 24px; margin-bottom: 35px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="ri-edit-2-line"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; color: #0f172a; font-weight: 700;">Section Text & Branch Settings</h3>
                    <p style="margin: 0; font-size: 12px; color: #64748b;">Configure the main header, page story, and regional branches' contact & address info.</p>
                </div>
            </div>

            <form action="<?php echo site_url('admin/update_about_content'); ?>" method="POST">
                <!-- Hero / Top Banner -->
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #2563eb; margin: 0 0 14px 0; font-weight: 700;">
                        1. Header / Our Story Banner
                    </h4>
                    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 16px; margin-bottom: 14px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Badge Tag</label>
                            <input type="text" name="about_hero_tag" value="<?php echo htmlspecialchars($about_hero_tag); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Main Page Heading</label>
                            <input type="text" name="about_hero_title" value="<?php echo htmlspecialchars($about_hero_title); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Sub-headline / Mission Statement</label>
                        <textarea name="about_hero_subtitle" rows="2" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;"><?php echo htmlspecialchars($about_hero_subtitle); ?></textarea>
                    </div>
                </div>

                <!-- Team Section Header -->
                <div style="margin-bottom: 24px; padding-top: 18px; border-top: 1px dashed #cbd5e1;">
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #2563eb; margin: 0 0 14px 0; font-weight: 700;">
                        2. Team Section Headings
                    </h4>
                    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Team Section Title</label>
                            <input type="text" name="about_team_heading" value="<?php echo htmlspecialchars($about_team_heading); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Team Sub-heading Description</label>
                            <input type="text" name="about_team_subheading" value="<?php echo htmlspecialchars($about_team_subheading); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                    </div>
                </div>

                <!-- Solan Branch Settings -->
                <div style="margin-bottom: 24px; padding-top: 18px; border-top: 1px dashed #cbd5e1;">
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #e74c3c; margin: 0 0 14px 0; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="ri-map-pin-2-fill"></i> 3. Solan Branch Details
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 12px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Branch Heading</label>
                            <input type="text" name="about_solan_title" value="<?php echo htmlspecialchars($about_solan_title); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Google Maps Link</label>
                            <input type="url" name="about_solan_map_link" value="<?php echo htmlspecialchars($about_solan_map_link); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Full Office Address</label>
                        <input type="text" name="about_solan_address" value="<?php echo htmlspecialchars($about_solan_address); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    </div>
                </div>

                <!-- Goa Branch Settings -->
                <div style="margin-bottom: 24px; padding-top: 18px; border-top: 1px dashed #cbd5e1;">
                    <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #2ecc71; margin: 0 0 14px 0; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="ri-map-pin-2-fill"></i> 4. Goa Branch Details
                    </h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 12px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Branch Heading</label>
                            <input type="text" name="about_goa_title" value="<?php echo htmlspecialchars($about_goa_title); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Google Maps Link</label>
                            <input type="url" name="about_goa_map_link" value="<?php echo htmlspecialchars($about_goa_map_link); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Full Office Address</label>
                        <input type="text" name="about_goa_address" value="<?php echo htmlspecialchars($about_goa_address); ?>" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 10px;">
                    <button type="submit" style="background-color: #2563eb; color: #ffffff; padding: 10px 24px; border-radius: 6px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                        <i class="ri-save-line"></i> Save Content Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Team Members List Section -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <h3 style="margin: 0; color: #0f172a; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-team-line" style="color: #2563eb;"></i> Team Members Across Hubs
                </h3>
                <a href="<?php echo site_url('admin/add_team_member'); ?>" style="background-color: #27ae60; color: #fff; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="ri-add-line"></i> Add Member
                </a>
            </div>

            <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                            <th style="padding: 12px 16px; text-align: left; width: 60px;">Photo</th>
                            <th style="padding: 12px 16px; text-align: left;">Name & Role</th>
                            <th style="padding: 12px 16px; text-align: left; width: 140px;">Branch Hub</th>
                            <th style="padding: 12px 16px; text-align: left; width: 90px;">Badge Tag</th>
                            <th style="padding: 12px 16px; text-align: left; width: 80px;">Order</th>
                            <th style="padding: 12px 16px; text-align: left; width: 90px;">Status</th>
                            <th style="padding: 12px 16px; text-align: right; width: 130px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($team_members)): foreach($team_members as $m): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                            <td style="padding: 12px 16px;">
                                <div style="width: 44px; height: 44px; border-radius: 50%; overflow: hidden; border: 2px solid #e2e8f0; background: #f8fafc;">
                                    <?php 
                                        $img_src = base_url('assets/images/team/manu.jpg');
                                        if (!empty($m->image_url)) {
                                            $img_src = (strpos($m->image_url, 'http') === 0) ? $m->image_url : base_url($m->image_url);
                                        }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($m->name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td style="padding: 12px 16px;">
                                <strong style="color: #0f172a; font-size: 15px; display: block;"><?php echo htmlspecialchars($m->name); ?></strong>
                                <span style="color: #64748b; font-size: 12px;"><?php echo htmlspecialchars($m->role_title); ?></span>
                            </td>
                            <td style="padding: 12px 16px;">
                                <?php if($m->branch_key === 'solan'): ?>
                                    <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 14px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-map-pin-2-fill"></i> Solan
                                    </span>
                                <?php else: ?>
                                    <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 14px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-map-pin-2-fill"></i> Goa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px 16px;">
                                <?php
                                    $badge_styles = [
                                        'blue'   => 'background: #ebf5fb; color: #2980b9;',
                                        'orange' => 'background: #fef9e7; color: #f39c12;',
                                        'green'  => 'background: #eafaf1; color: #27ae60;',
                                        'purple' => 'background: #f4ecf7; color: #8e44ad;',
                                        'pink'   => 'background: #fdf2e9; color: #e67e22;'
                                    ];
                                    $theme_key = isset($badge_styles[$m->role_color_theme]) ? $m->role_color_theme : 'blue';
                                ?>
                                <span style="<?php echo $badge_styles[$theme_key]; ?> font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">
                                    <?php echo ucfirst($theme_key); ?>
                                </span>
                            </td>
                            <td style="padding: 12px 16px; color: #64748b; font-weight: 600;">
                                <?php echo (int)$m->sort_order; ?>
                            </td>
                            <td style="padding: 12px 16px;">
                                <?php if((int)$m->is_active === 1): ?>
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
                                    <a href="<?php echo site_url('admin/edit_team_member/' . $m->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                        <i class="ri-edit-line"></i> Edit
                                    </a>
                                    <a href="<?php echo site_url('admin/delete_team_member/' . $m->id); ?>" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($m->name)); ?>?');" style="color: #dc2626; background: #fef2f2; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8;">
                                No team members found. Click "Add New Team Member" above to create one.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
