<div class="data-card" style="padding: 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
        <div>
            <h2 style="margin: 0 0 6px 0; color: #1e293b; font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="ri-file-list-3-line" style="color: #0969da;"></i> Manage Assessment Tests
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 14px;">Create, edit, manage test parts/sections, questions, and delete assessment tests across the platform.</p>
        </div>
        <a href="<?php echo site_url('admin/add_assessment'); ?>" style="background-color: #10b981; color: #ffffff; text-decoration: none; padding: 11px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(16,185,129,0.25); transition: all 0.2s;">
            <i class="ri-add-circle-line" style="font-size: 18px;"></i> Create New Assessment Test
        </a>
    </div>

    <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
        <table style="width: 100%; border-collapse: collapse; min-width: 980px;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; width: 50px;">ID</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; min-width: 170px;">Test Title</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; min-width: 260px;">Test Parts / Sections</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Time Limit</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Total Questions</th>
                    <th style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;">Status</th>
                    <th style="padding: 14px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; min-width: 260px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($assessments)): foreach($assessments as $a): ?>
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc';" onmouseout="this.style.backgroundColor='transparent';">
                    <td style="padding: 16px; color: #94a3b8; font-weight: 600; font-size: 14px;"><?php echo $a->id; ?></td>
                    <td style="padding: 16px;">
                        <span style="font-weight: 700; color: #0f172a; font-size: 15px; display: block;"><?php echo htmlspecialchars($a->title); ?></span>
                        <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #64748b; font-family: monospace; font-size: 11px; margin-top: 4px; display: inline-block;"><?php echo htmlspecialchars($a->slug); ?></code>
                    </td>
                    <td style="padding: 16px;">
                        <?php if (!empty($a->parts)): ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                <?php foreach ($a->parts as $pt): ?>
                                    <span style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-folder-2-line" style="color: #22c55e;"></i> -<?php echo htmlspecialchars($pt->part_name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span style="color: #94a3b8; font-size: 13px; font-style: italic;">No specific parts created</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 16px; white-space: nowrap;">
                        <span style="background-color: #f1f5f9; color: #1e293b; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #e2e8f0; white-space: nowrap;">
                            <i class="ri-timer-line" style="color: #64748b;"></i> <?php echo (int)$a->time_limit; ?> Mins
                        </span>
                    </td>
                    <td style="padding: 16px; white-space: nowrap;">
                        <a href="<?php echo site_url('admin/manage_questions/'.$a->id); ?>" style="text-decoration: none;">
                            <span style="background-color: #eff6ff; color: #1d4ed8; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: 1px solid #bfdbfe; white-space: nowrap; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#dbeafe';" onmouseout="this.style.backgroundColor='#eff6ff';">
                                <i class="ri-questionnaire-line"></i> <?php echo (int)$a->question_count; ?> Questions
                            </span>
                        </a>
                    </td>
                    <td style="padding: 16px; white-space: nowrap;">
                        <?php if(strtolower($a->status) === 'active'): ?>
                            <span style="background-color: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ri-checkbox-circle-fill" style="font-size: 13px;"></i> Active
                            </span>
                        <?php else: ?>
                            <span style="background-color: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ri-close-circle-fill" style="font-size: 13px;"></i> Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 16px; text-align: right; white-space: nowrap;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center; flex-wrap: nowrap;">
                            <?php if ($a->title === 'MBTI Personality Profiling Test' || $a->title === 'Interest Inventory Test'): ?>
                                <span style="color: #94a3b8; font-size: 13px; font-style: italic;">System Test (Uneditable)</span>
                            <?php else: ?>
                                <a href="<?php echo site_url('admin/manage_questions/'.$a->id); ?>" style="background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; white-space: nowrap; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#1d4ed8';" onmouseout="this.style.backgroundColor='#2563eb';">
                                    <i class="ri-layout-grid-line"></i> Manage Parts & Questions
                                </a>
                                <a href="<?php echo site_url('admin/edit_assessment/'.$a->id); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 7px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; transition: all 0.15s;" onmouseover="this.style.backgroundColor='#e2e8f0';" onmouseout="this.style.backgroundColor='#f1f5f9';">
                                    <i class="ri-edit-line"></i> Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_assessment/'.$a->id); ?>" onclick="return confirm('Are you sure you want to delete this assessment test and ALL associated questions?');" style="background-color: #fef2f2; color: #dc2626; text-decoration: none; padding: 7px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; border: 1px solid #fca5a5; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; transition: all 0.15s;" onmouseover="this.style.backgroundColor='#fee2e2';" onmouseout="this.style.backgroundColor='#fef2f2';">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" style="padding: 32px; text-align: center; color: #64748b; font-size: 15px;">
                        No assessment tests found. Click "Create New Assessment Test" above to add one.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
