<!-- Select2 CSS & Custom Searchable Dropdown Styling -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 42px !important;
    padding: 6px 12px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background-color: #ffffff !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
    color: #1e293b !important;
    font-size: 14px !important;
    font-weight: 500 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
}
.select2-dropdown {
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
    z-index: 9999 !important;
}
.select2-search__field {
    padding: 8px 12px !important;
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    font-family: inherit !important;
    font-size: 14px !important;
    outline: none !important;
}
.select2-results__option--highlighted[aria-selected] {
    background-color: #2563eb !important;
}
</style>

<div class="card" style="border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background-color: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;">Enroll Students: <?php echo htmlspecialchars($course->title); ?></h3>
        <a href="<?php echo site_url('admin/courses'); ?>" class="btn btn-secondary" style="padding: 8px 14px; background-color: #64748b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 13px;">Back to Courses</a>
    </div>
    
    <div class="card-body" style="padding: 24px;">
        <?php if($this->session->flashdata('error')): ?>
            <div style="color: #721c24; background-color: #f8d7da; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px;">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- Quick Register & Enroll New Student -->
        <div style="background: linear-gradient(135deg, #eff6ff, #f0fdf4); padding: 22px; border-radius: 8px; margin-bottom: 26px; border: 1px solid #bfdbfe; box-shadow: 0 2px 8px rgba(37,99,235,0.06);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                <h4 style="margin: 0; color: #1e3a8a; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-user-add-line" style="color: #2563eb; font-size: 20px;"></i> Register & Enroll New Student
                </h4>
                <span style="font-size: 12px; color: #2563eb; font-weight: 600; background: #dbeafe; padding: 4px 12px; border-radius: 12px;">Instant Account Registration & Course Access</span>
            </div>

            <div style="font-size: 13px; color: #1e40af; background: #e0f2fe; padding: 8px 14px; border-radius: 6px; border: 1px solid #bae6fd; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i class="ri-information-fill" style="color: #0284c7; font-size: 18px; flex-shrink: 0;"></i>
                <span>Creates student account and immediately assigns them to this course so it appears in their student panel. Default password: <code style="background: #ffffff; padding: 2px 8px; border-radius: 4px; border: 1px solid #93c5fd; font-weight: 700; color: #1d4ed8; font-size: 13px;">Alpha@1234</code></span>
            </div>

            <form action="<?php echo site_url('admin/register_and_enroll_student/'.$course->id); ?>" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; align-items: flex-end;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Student Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Rahul Sharma" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: #fff;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Email Address *</label>
                    <input type="email" name="email" required placeholder="student@example.com" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: #fff;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Username (Optional)</label>
                    <input type="text" name="username" placeholder="Auto from email" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: #fff;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Phone (Optional)</label>
                    <input type="tel" name="phone" placeholder="9876543210" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; box-sizing: border-box; background: #fff;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Password</label>
                    <input type="text" name="password" value="Alpha@1234" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; box-sizing: border-box; font-weight: 600; color: #1d4ed8; background: #fff;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e3a8a; font-size: 13px;">Course Tier *</label>
                    <select name="tier_key" style="width: 100%; padding: 9px 12px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 14px; height: 38px; box-sizing: border-box; background: #fff;" required>
                        <?php if(!empty($tiers)): foreach($tiers as $tier): ?>
                            <option value="<?php echo htmlspecialchars($tier->tier_key); ?>"><?php echo htmlspecialchars($tier->tier_name); ?> Tier</option>
                        <?php endforeach; else: ?>
                            <option value="basic">Basic Tier</option>
                            <option value="standard">Standard Tier</option>
                            <option value="advanced">Advanced Tier</option>
                            <option value="premium">Premium Tier</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="grid-column: 1 / -1; display: flex; justify-content: flex-end; margin-top: 4px;">
                    <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 22px; font-size: 14px; border-radius: 6px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.3);">
                        <i class="ri-user-add-line"></i> Register & Enroll New Student
                    </button>
                </div>
            </form>
        </div>

        <!-- Enroll Existing Registered Student Form -->
        <div style="background-color: #f8fafc; padding: 22px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            <h4 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;"><i class="ri-user-shared-line" style="color: #10b981;"></i> Enroll Existing Registered Student</h4>
            <form action="<?php echo site_url('admin/process_enrollment/'.$course->id); ?>" method="POST" style="display: flex; gap: 18px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 260px;">
                    <label for="user_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px;">Select Student (Searchable)</label>
                    <select name="user_id" id="user_id" style="width: 100%;" required>
                        <option value="">-- Choose a student --</option>
                        <?php if(!empty($students)): foreach($students as $student): ?>
                            <?php 
                                $st_name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                                if (empty($st_name) && !empty($student->username)) $st_name = $student->username;
                                if (empty($st_name) && !empty($student->email)) $st_name = explode('@', $student->email)[0];
                            ?>
                            <option value="<?php echo $student->id; ?>"><?php echo htmlspecialchars($st_name); ?> (<?php echo htmlspecialchars($student->email); ?>)</option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                
                <div style="flex: 1; min-width: 200px;">
                    <label for="tier_key" style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px;">Pricing Tier</label>
                    <select name="tier_key" id="tier_key" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; font-size: 14px; height: 42px; box-sizing: border-box;" required>
                        <?php if(!empty($tiers)): foreach($tiers as $tier): ?>
                            <option value="<?php echo htmlspecialchars($tier->tier_key); ?>"><?php echo htmlspecialchars($tier->tier_name); ?> Tier (₹<?php echo number_format($tier->price, 2); ?>)</option>
                        <?php endforeach; else: ?>
                            <option value="basic">Basic Tier</option>
                            <option value="standard">Standard Tier</option>
                            <option value="advanced">Advanced Tier</option>
                            <option value="premium">Premium Tier</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="submit" style="background-color: #10b981; color: #fff; border: none; padding: 10px 22px; font-size: 14px; border-radius: 6px; cursor: pointer; height: 42px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(16,185,129,0.25);">
                    <i class="ri-user-add-line"></i> Enroll Student
                </button>
            </form>
        </div>

        <!-- Enrolled Students List -->
        <div>
            <h4 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 16px; font-weight: 700;">Currently Enrolled Students</h4>
            <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                <table class="table" style="width: 100%; border-collapse: collapse; min-width: 600px;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Name</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Email</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Enrolled Tier</th>
                            <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Enrolled Date</th>
                            <th style="padding: 12px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #475569;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($enrolled)): foreach($enrolled as $en): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 16px; font-weight: 600; color: #0f172a;">
                                <?php 
                                    $en_name = trim(($en->first_name ?? '') . ' ' . ($en->last_name ?? ''));
                                    if (empty($en_name) && !empty($en->username)) $en_name = $en->username;
                                    if (empty($en_name) && !empty($en->email)) $en_name = explode('@', $en->email)[0];
                                    echo htmlspecialchars($en_name);
                                ?>
                            </td>
                            <td style="padding: 12px 16px; color: #64748b; font-size: 14px;"><?php echo htmlspecialchars($en->email); ?></td>
                            <td style="padding: 12px 16px;">
                                <span style="background-color: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                    <?php echo htmlspecialchars($en->tier_key ? $en->tier_key : 'basic'); ?>
                                </span>
                            </td>
                            <td style="padding: 12px 16px; color: #64748b; font-size: 13px;"><?php echo date('M d, Y', strtotime($en->enrolled_at)); ?></td>
                            <td style="padding: 12px 16px; text-align: right;">
                                <a href="<?php echo site_url('admin/remove_enrollment/'.$en->enrollment_id.'/'.$course->id); ?>" onclick="return confirm('Remove student enrollment?');" style="color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 600;">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #64748b;">No students enrolled in this course yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Select2 JS for Searchable Select Box -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#user_id').select2({
        placeholder: "-- Choose a student --",
        allowClear: true,
        width: '100%'
    });
});
</script>
