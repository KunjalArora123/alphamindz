<div class="data-card" style="padding: 24px 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin: 0 0 6px 0; color: #1e293b; font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="ri-survey-line" style="color: #2563eb;"></i> Test: <?php echo htmlspecialchars($assessment->title); ?>
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 14px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <span><i class="ri-timer-line" style="color: #64748b;"></i> Time Limit: <strong><?php echo (int)$assessment->time_limit; ?> Mins</strong></span>
                <span><i class="ri-folders-line" style="color: #64748b;"></i> Total Parts: <strong><?php echo count($parts); ?></strong></span>
                <span><i class="ri-list-check" style="color: #64748b;"></i> Total Questions: <strong><?php echo count($questions); ?></strong></span>
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('admin/export_assessment_excel/'.$assessment->id); ?>" style="background-color: #10b981; color: #ffffff; text-decoration: none; padding: 9px 18px; border-radius: 8px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(16,185,129,0.25); transition: all 0.15s;" onmouseover="this.style.backgroundColor='#059669';" onmouseout="this.style.backgroundColor='#10b981';">
                <i class="ri-file-excel-2-line" style="font-size: 18px;"></i> Download Test (Excel)
            </a>
            <a href="<?php echo site_url('admin/manage_assessments'); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 9px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s;" onmouseover="this.style.backgroundColor='#e2e8f0';" onmouseout="this.style.backgroundColor='#f1f5f9';">
                <i class="ri-arrow-left-line"></i> Back to Assessments
            </a>
        </div>
    </div>
</div>

<!-- Test Parts / Sections Management Card -->
<div class="form-card" style="padding: 24px 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 18px;">
        <h3 style="margin: 0; color: #0f172a; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-folder-keyhole-line" style="color: #059669;"></i> Test Parts / Sections Structure
        </h3>
        <span style="font-size: 13px; color: #64748b;">Each assessment test can contain multiple parts (e.g. Mechanical Ability, Verbal Ability)</span>
    </div>

    <!-- Existing Parts Grid -->
    <?php if(!empty($parts)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; margin-bottom: 20px;">
            <?php foreach($parts as $pt): ?>
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-weight: 700; color: #0f172a; font-size: 14px; display: block;">
                            -<?php echo htmlspecialchars($pt->part_name); ?>
                        </span>
                        <span style="font-size: 12px; color: #64748b; font-weight: 600;">
                            <i class="ri-questionnaire-line" style="color: #2563eb;"></i> <?php echo (int)$pt->question_count; ?> Questions
                        </span>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <a href="<?php echo site_url('admin/manage_questions/'.$assessment->id.'?part_id='.$pt->id); ?>" style="background-color: #eff6ff; color: #2563eb; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #bfdbfe;">
                            Filter
                        </a>
                        <a href="<?php echo site_url('admin/delete_assessment_part/'.$pt->id); ?>" onclick="return confirm('Deleting this part will also delete all questions inside it. Continue?');" style="background-color: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #fca5a5;">
                            <i class="ri-delete-bin-line"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Add New Part Form -->
    <form action="<?php echo site_url('admin/save_assessment_part/'.$assessment->id); ?>" method="POST" style="background-color: #f8fafc; padding: 16px; border-radius: 8px; border: 1px dashed #cbd5e1; display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 240px;">
            <label for="part_name" style="font-weight: 600; color: #334155; font-size: 13px; margin-bottom: 4px; display: block;">Add New Test Part / Section Name</label>
            <input type="text" id="part_name" name="part_name" placeholder="e.g. Mechanical Ability, Verbal Ability, Numerical Ability..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
        </div>
        <div style="flex: 3; min-width: 280px;">
            <label for="description" style="font-weight: 600; color: #334155; font-size: 13px; margin-bottom: 4px; display: block;">Part Description (Optional)</label>
            <input type="text" id="description" name="description" placeholder="Brief info about what this part tests..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
        </div>
        <button type="submit" style="background-color: #059669; color: #ffffff; border: none; padding: 9px 18px; font-size: 14px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; height: 38px; white-space: nowrap;">
            <i class="ri-add-line"></i> Add Test Part
        </button>
    </form>
</div>

<!-- Add Question Card -->
<div class="form-card" style="padding: 24px 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 28px; max-width: 100%;">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: #0f172a; font-size: 18px; font-weight: 700; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        <i class="ri-add-circle-line" style="color: #10b981;"></i> Add New Question to Assessment
    </h3>
    <form action="<?php echo site_url('admin/save_question/'.$assessment->id); ?>" method="POST" enctype="multipart/form-data">
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="part_id" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Select Test Part / Section <span style="color: #ef4444;">*</span></label>
            <select id="part_id" name="part_id" style="width: 100%; max-width: 400px; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; font-weight: 600; color: #1e293b;" required>
                <?php if(!empty($parts)): foreach($parts as $pt): ?>
                    <option value="<?php echo $pt->id; ?>" <?php echo ($selected_part_id == $pt->id) ? 'selected' : ''; ?>>-<?php echo htmlspecialchars($pt->part_name); ?></option>
                <?php endforeach; else: ?>
                    <option value="">General Test (Default)</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label for="question_text" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Question Text <span style="color: #ef4444;">*</span></label>
            <textarea id="question_text" name="question_text" placeholder="Enter full question text..." style="width: 100%; height: 90px; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="question_image" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Attach Question Image (Optional)</label>
            <input type="file" id="question_image" name="question_image" accept="image/*" style="width: 100%; max-width: 500px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box; background: #f8fafc;">
            <span style="font-size: 12px; color: #64748b; margin-top: 4px; display: block;"><i class="ri-image-line"></i> Images will be automatically stored in the <code>questions-images</code> directory.</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_a" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option A <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_a" name="option_a" placeholder="Option A choice" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 6px; display: block;">Option A Photo (Optional)</label>
                <input type="file" name="option_a_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #f8fafc;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_b" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option B <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_b" name="option_b" placeholder="Option B choice" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 6px; display: block;">Option B Photo (Optional)</label>
                <input type="file" name="option_b_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #f8fafc;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_c" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option C <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_c" name="option_c" placeholder="Option C choice" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 6px; display: block;">Option C Photo (Optional)</label>
                <input type="file" name="option_c_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #f8fafc;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_d" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option D</label>
                <input type="text" id="option_d" name="option_d" placeholder="Option D choice (optional)" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 6px; display: block;">Option D Photo (Optional)</label>
                <input type="file" name="option_d_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #f8fafc;">
            </div>
        </div>

        <div class="form-group" style="max-width: 260px; margin-bottom: 24px;">
            <label for="correct_option" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Correct Answer Option <span style="color: #ef4444;">*</span></label>
            <select id="correct_option" name="correct_option" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; font-weight: 700; color: #15803d; background-color: #f0fdf4; box-sizing: border-box;">
                <option value="A">Option A</option>
                <option value="B">Option B</option>
                <option value="C">Option C</option>
                <option value="D">Option D</option>
            </select>
        </div>

        <button type="submit" style="background-color: #10b981; color: #ffffff; border: none; padding: 11px 22px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(16,185,129,0.25);">
            <i class="ri-add-circle-line me-1"></i> Add Question to Test
        </button>
    </form>
</div>

<!-- Existing Questions List -->
<div class="data-card" style="padding: 24px 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <h3 style="margin: 0; color: #0f172a; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-list-check-2" style="color: #2563eb;"></i> Existing Test Questions (<?php echo count($questions); ?>)
        </h3>

        <?php if(!empty($parts)): ?>
            <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                <span style="font-size: 13px; font-weight: 600; color: #64748b;">Filter by Part:</span>
                <a href="<?php echo site_url('admin/manage_questions/'.$assessment->id); ?>" style="text-decoration: none; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; <?php echo (empty($selected_part_id)) ? 'background-color: #2563eb; color: #fff;' : 'background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
                    All Parts
                </a>
                <?php foreach($parts as $pt): ?>
                    <a href="<?php echo site_url('admin/manage_questions/'.$assessment->id.'?part_id='.$pt->id); ?>" style="text-decoration: none; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; <?php echo ($selected_part_id == $pt->id) ? 'background-color: #2563eb; color: #fff;' : 'background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;'; ?>">
                        -<?php echo htmlspecialchars($pt->part_name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if(!empty($questions)): ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach($questions as $idx => $q): ?>
                <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background-color: #fafbfc; transition: border-color 0.15s;" onmouseover="this.style.borderColor='#cbd5e1';" onmouseout="this.style.borderColor='#e2e8f0';">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap;">
                                <span style="background-color: #1e293b; color: #ffffff; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                                    Q#<?php echo htmlspecialchars($q->question_number ? $q->question_number : ($idx + 1)); ?>
                                </span>
                                <span style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-folder-2-line" style="color: #22c55e;"></i> Part: -<?php echo htmlspecialchars($q->part_name ? $q->part_name : $q->subject); ?>
                                </span>
                            </div>
                            <div style="font-size: 15px; font-weight: 700; color: #0f172a; line-height: 1.5; margin-bottom: 10px;">
                                <?php echo htmlspecialchars($q->question_text); ?>
                            </div>

                            <?php if(!empty($q->image_path) && file_exists(FCPATH . $q->image_path)): ?>
                                <div style="margin-bottom: 14px;">
                                    <img src="<?php echo base_url($q->image_path); ?>" alt="Question Image" style="max-height: 150px; max-width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 4px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                </div>
                            <?php endif; ?>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                                <div style="padding: 10px 14px; border-radius: 6px; font-size: 14px; <?php echo (strtoupper($q->correct_option) === 'A') ? 'background-color: #dcfce7; border: 1px solid #86efac; color: #14532d; font-weight: 700;' : 'background-color: #ffffff; border: 1px solid #cbd5e1; color: #475569;'; ?>">
                                    <strong>A:</strong> <?php echo htmlspecialchars($q->option_a); ?>
                                    <?php if(!empty($q->option_a_image) && file_exists(FCPATH . $q->option_a_image)): ?>
                                        <div style="margin-top: 6px;"><img src="<?php echo base_url($q->option_a_image); ?>" alt="Option A Image" style="max-height: 90px; max-width: 100%; border-radius: 6px; border: 1px solid #cbd5e1;"></div>
                                    <?php endif; ?>
                                    <?php if(strtoupper($q->correct_option) === 'A'): ?>
                                        <i class="ri-checkbox-circle-fill" style="color: #16a34a; float: right; font-size: 16px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div style="padding: 10px 14px; border-radius: 6px; font-size: 14px; <?php echo (strtoupper($q->correct_option) === 'B') ? 'background-color: #dcfce7; border: 1px solid #86efac; color: #14532d; font-weight: 700;' : 'background-color: #ffffff; border: 1px solid #cbd5e1; color: #475569;'; ?>">
                                    <strong>B:</strong> <?php echo htmlspecialchars($q->option_b); ?>
                                    <?php if(!empty($q->option_b_image) && file_exists(FCPATH . $q->option_b_image)): ?>
                                        <div style="margin-top: 6px;"><img src="<?php echo base_url($q->option_b_image); ?>" alt="Option B Image" style="max-height: 90px; max-width: 100%; border-radius: 6px; border: 1px solid #cbd5e1;"></div>
                                    <?php endif; ?>
                                    <?php if(strtoupper($q->correct_option) === 'B'): ?>
                                        <i class="ri-checkbox-circle-fill" style="color: #16a34a; float: right; font-size: 16px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div style="padding: 10px 14px; border-radius: 6px; font-size: 14px; <?php echo (strtoupper($q->correct_option) === 'C') ? 'background-color: #dcfce7; border: 1px solid #86efac; color: #14532d; font-weight: 700;' : 'background-color: #ffffff; border: 1px solid #cbd5e1; color: #475569;'; ?>">
                                    <strong>C:</strong> <?php echo htmlspecialchars($q->option_c); ?>
                                    <?php if(!empty($q->option_c_image) && file_exists(FCPATH . $q->option_c_image)): ?>
                                        <div style="margin-top: 6px;"><img src="<?php echo base_url($q->option_c_image); ?>" alt="Option C Image" style="max-height: 90px; max-width: 100%; border-radius: 6px; border: 1px solid #cbd5e1;"></div>
                                    <?php endif; ?>
                                    <?php if(strtoupper($q->correct_option) === 'C'): ?>
                                        <i class="ri-checkbox-circle-fill" style="color: #16a34a; float: right; font-size: 16px;"></i>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($q->option_d) || (!empty($q->option_d_image) && file_exists(FCPATH . $q->option_d_image))): ?>
                                <div style="padding: 10px 14px; border-radius: 6px; font-size: 14px; <?php echo (strtoupper($q->correct_option) === 'D') ? 'background-color: #dcfce7; border: 1px solid #86efac; color: #14532d; font-weight: 700;' : 'background-color: #ffffff; border: 1px solid #cbd5e1; color: #475569;'; ?>">
                                    <strong>D:</strong> <?php echo htmlspecialchars($q->option_d); ?>
                                    <?php if(!empty($q->option_d_image) && file_exists(FCPATH . $q->option_d_image)): ?>
                                        <div style="margin-top: 6px;"><img src="<?php echo base_url($q->option_d_image); ?>" alt="Option D Image" style="max-height: 90px; max-width: 100%; border-radius: 6px; border: 1px solid #cbd5e1;"></div>
                                    <?php endif; ?>
                                    <?php if(strtoupper($q->correct_option) === 'D'): ?>
                                        <i class="ri-checkbox-circle-fill" style="color: #16a34a; float: right; font-size: 16px;"></i>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px; white-space: nowrap;">
                            <a href="<?php echo site_url('admin/edit_question/'.$q->id); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                <i class="ri-edit-line"></i> Edit
                            </a>
                            <a href="<?php echo site_url('admin/delete_question/'.$q->id); ?>" onclick="return confirm('Are you sure you want to delete this question?');" style="background-color: #fef2f2; color: #dc2626; text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; border: 1px solid #fca5a5; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
                                <i class="ri-delete-bin-line"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #64748b; padding: 36px; background-color: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; font-size: 15px;">
            No questions found for this test or selected part. Use the form above to add questions.
        </p>
    <?php endif; ?>
</div>
