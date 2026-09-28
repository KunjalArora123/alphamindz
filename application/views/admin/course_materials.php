<div class="card" style="border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 style="margin: 0; font-size: 1.25rem; color: #1e293b; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-file-pdf-2-line" style="color: #dc2626;"></i> Course Materials (PDF): <?php echo htmlspecialchars($course->title); ?>
            </h3>
            <span style="font-size: 0.82rem; color: #64748b;">Upload and manage PDF study notes & reading modules for students</span>
        </div>
        <a href="<?php echo site_url('admin/courses'); ?>" class="btn btn-secondary" style="padding: 8px 16px; background-color: #64748b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-arrow-left-line"></i> Back to Courses
        </a>
    </div>

    <div class="card-body" style="padding: 24px;">
        
        <?php if($this->session->flashdata('error')): ?>
            <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem;">
                <i class="ri-error-warning-line"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- Upload PDF Form -->
        <div style="background: linear-gradient(135deg, #fff1f2, #f8fafc); border: 1px solid #fecdd3; border-radius: 10px; padding: 22px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.04);">
            <h4 style="margin-top: 0; margin-bottom: 16px; color: #9f1239; font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-upload-cloud-2-fill" style="color: #e11d48; font-size: 1.3rem;"></i> Upload Student PDF Material
            </h4>

            <form action="<?php echo site_url('admin/upload_course_material/' . $course->id); ?>" method="POST" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">Material Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Chapter 1 - Complete Study Notes & Formulas" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box; background: #ffffff;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">PDF File * <small style="color: #dc2626;">(Only .PDF format allowed)</small></label>
                        <input type="file" name="pdf_file" accept=".pdf,application/pdf" required style="width: 100%; padding: 8px 10px; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 0.85rem; background: #ffffff; cursor: pointer; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">Access Tier Level</label>
                        <select name="module_tier" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; background: #ffffff; box-sizing: border-box;">
                            <option value="basic">Basic Tier (All Enrolled Students)</option>
                            <option value="standard">Standard Tier</option>
                            <option value="advanced">Advanced Tier</option>
                            <option value="premium">Premium Tier Only</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 0.88rem;">Description / Instructions (Optional)</label>
                    <textarea name="description" rows="2" placeholder="Brief summary of PDF contents..." style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <button type="submit" style="background-color: #dc2626; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25); transition: all 0.2s;">
                    <i class="ri-file-upload-line" style="font-size: 1.1rem;"></i> Upload PDF Material
                </button>
            </form>
        </div>

        <!-- Materials List -->
        <div>
            <h4 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; justify-content: space-between;">
                <span>Uploaded Course Materials (PDFs)</span>
                <span style="font-size: 0.82rem; color: #64748b; font-weight: 600;">Total: <?php echo count($materials); ?></span>
            </h4>

            <?php if(empty($materials)): ?>
                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 35px; text-align: center; color: #64748b;">
                    <i class="ri-file-pdf-line" style="font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                    <h4 style="margin: 0 0 4px 0; color: #334155;">No PDF materials uploaded yet</h4>
                    <p style="margin: 0; font-size: 0.88rem;">Use the form above to upload PDF reading materials for students.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 14px 16px;">Material Title</th>
                                <th style="padding: 14px 16px;">Access Tier</th>
                                <th style="padding: 14px 16px;">Format</th>
                                <th style="padding: 14px 16px;">Upload Date</th>
                                <th style="padding: 14px 16px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($materials as $m): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 16px; vertical-align: top;">
                                        <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem; display: flex; align-items: center; gap: 8px;">
                                            <i class="ri-file-pdf-fill" style="color: #dc2626; font-size: 1.2rem;"></i>
                                            <?php echo htmlspecialchars($m->title); ?>
                                        </div>
                                        <?php if(!empty($m->description)): ?>
                                            <div style="font-size: 0.82rem; color: #64748b; margin-top: 4px; padding-left: 28px;">
                                                <?php echo htmlspecialchars($m->description); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td style="padding: 16px; vertical-align: top;">
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                            <?php echo htmlspecialchars($m->module_tier ? $m->module_tier : 'basic'); ?>
                                        </span>
                                    </td>

                                    <td style="padding: 16px; vertical-align: top;">
                                        <span style="background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
                                            PDF
                                        </span>
                                    </td>

                                    <td style="padding: 16px; vertical-align: top; color: #64748b; font-size: 0.88rem;">
                                        <?php echo date('M d, Y h:i A', strtotime($m->created_at)); ?>
                                    </td>

                                    <td style="padding: 16px; vertical-align: top; text-align: right;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <?php if(!empty($m->file_path)): ?>
                                                <a href="<?php echo base_url($m->file_path); ?>" target="_blank" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="ri-eye-line"></i> View PDF
                                                </a>
                                            <?php endif; ?>

                                            <a href="<?php echo site_url('admin/delete_course_material/' . $m->id . '/' . $course->id); ?>" onclick="return confirm('Are you sure you want to delete this PDF material?');" style="background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.82rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
