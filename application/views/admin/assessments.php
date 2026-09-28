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

<!-- Admin Assessment Test Authorization Management Card -->
<div class="card" style="margin-bottom: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background-color: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;"><i class="ri-shield-keyhole-line" style="color: #2563eb;"></i> Authorize Student Assessment Tests</h3>
        <span style="font-size: 13px; color: #64748b;">Students can only take tests when Enabled by Admin</span>
    </div>
        <!-- Quick Add Student Shortcut Form -->
        <div style="background: linear-gradient(135deg, #eff6ff, #f0fdf4); padding: 22px; border-radius: 8px; margin-bottom: 28px; border: 1px solid #bfdbfe; box-shadow: 0 2px 8px rgba(37,99,235,0.06);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                <h4 style="margin: 0; color: #1e3a8a; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="ri-user-add-line" style="color: #2563eb; font-size: 20px;"></i> Quick Add Student Shortcut
                </h4>
                <span style="font-size: 12px; color: #2563eb; font-weight: 600; background: #dbeafe; padding: 4px 12px; border-radius: 12px;">Instant Account Registration</span>
            </div>

            <div style="font-size: 13px; color: #1e40af; background: #e0f2fe; padding: 8px 14px; border-radius: 6px; border: 1px solid #bae6fd; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i class="ri-information-fill" style="color: #0284c7; font-size: 18px; flex-shrink: 0;"></i>
                <span><strong>Default Password Info:</strong> Auto-generated password for new students is <code style="background: #ffffff; padding: 2px 8px; border-radius: 4px; border: 1px solid #93c5fd; font-weight: 700; color: #1d4ed8; font-size: 13px;">Alpha@1234</code></span>
            </div>

            <form action="<?php echo site_url('admin/quick_add_student'); ?>" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: flex-end;">
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e293b; font-size: 13px;">Full Name *</label>
                    <input type="text" name="name" placeholder="e.g. Rahul Sharma" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box; background: #fff;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e293b; font-size: 13px;">Username *</label>
                    <input type="text" name="username" placeholder="e.g. rahul_sharma" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box; background: #fff;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e293b; font-size: 13px;">Email Address *</label>
                    <input type="email" name="email" placeholder="e.g. student@alphamindz.com" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box; background: #fff;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #64748b; font-size: 13px;">Phone (Not Required)</label>
                    <input type="tel" name="phone" placeholder="e.g. 9876543210" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box; background: #fff;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #1e293b; font-size: 13px;">Auto-Enable Test Access</label>
                    <select name="auto_authorize_test" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box; background: #fff;">
                        <option value="">-- Do Not Authorize Test --</option>
                        <?php if(!empty($available_tests)): foreach($available_tests as $tt): ?>
                            <option value="<?php echo htmlspecialchars($tt); ?>"><?php echo htmlspecialchars($tt); ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" style="background-color: #2563eb; color: #fff; border: none; padding: 9px 18px; font-size: 14px; border-radius: 6px; cursor: pointer; height: 39px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                        <i class="ri-user-add-fill"></i> Add Student
                    </button>
                </div>
            </form>
        </div>

        <!-- Multi-Test Authorization Form -->
        <div style="background-color: #f8fafc; padding: 22px; border-radius: 8px; margin-bottom: 28px; border: 1px solid #e2e8f0;">
            <h4 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 16px; font-weight: 700;"><i class="ri-key-2-line" style="color: #10b981;"></i> Enable Test Access for a Student</h4>
            <form action="<?php echo site_url('admin/toggle_test_permission'); ?>" method="POST" style="display: flex; gap: 18px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 2; min-width: 260px;">
                    <label for="user_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px;">Select Student (Searchable)</label>
                    <select name="user_id" id="user_id" style="width: 100%;" required>
                        <option value="">-- Select a Student --</option>
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
                
                <div style="flex: 2; min-width: 280px;">
                    <label for="test_title" style="display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 14px;">Select Assessment Test (Searchable)</label>
                    <select name="test_title" id="test_title" style="width: 100%;" required>
                        <option value="">-- Select an Assessment Test --</option>
                        <?php if(!empty($available_tests)): foreach($available_tests as $tt): ?>
                            <option value="<?php echo htmlspecialchars($tt); ?>"><?php echo htmlspecialchars($tt); ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <input type="hidden" name="status" value="enabled">

                <button type="submit" style="background-color: #10b981; color: #fff; border: none; padding: 10px 22px; font-size: 14px; border-radius: 6px; cursor: pointer; height: 42px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; box-shadow: 0 2px 6px rgba(16,185,129,0.25);">
                    <i class="ri-checkbox-circle-line me-1"></i> Enable Test Access
                </button>
            </form>
        </div>

        <h4 style="margin-top: 0; margin-bottom: 16px; color: #0f172a; font-size: 16px; font-weight: 700;">Active Test Authorizations List</h4>
        <div style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table class="table" style="width: 100%; border-collapse: collapse; min-width: 750px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Student Name</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Email</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Authorized Test Title</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Status</th>
                        <th style="padding: 12px 16px; text-align: left; font-size: 13px; font-weight: 700; color: #475569;">Last Granted</th>
                        <th style="padding: 12px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #475569;">Action Toggle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($students_permissions)): foreach($students_permissions as $sp): ?>
                    <?php 
                    $st = strtolower($sp->status ? $sp->status : 'disabled');
                    $bg_color = '#64748b';
                    if ($st === 'enabled') $bg_color = '#16a34a';
                    if ($st === 'completed') $bg_color = '#0284c7';
                    $sp_name = trim(($sp->first_name ?? '') . ' ' . ($sp->last_name ?? ''));
                    if (empty($sp_name) && !empty($sp->username)) $sp_name = $sp->username;
                    if (empty($sp_name) && !empty($sp->email)) $sp_name = explode('@', $sp->email)[0];
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px 16px; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($sp_name); ?></td>
                        <td style="padding: 12px 16px; color: #64748b; font-size: 14px;"><?php echo htmlspecialchars($sp->email); ?></td>
                        <td style="padding: 12px 16px; font-weight: 600; color: #1e40af; font-size: 14px;"><?php echo htmlspecialchars($sp->test_title); ?></td>
                        <td style="padding: 12px 16px;">
                            <span style="background-color: <?php echo $bg_color; ?>; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                <?php echo $st; ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #64748b;">
                            <?php echo $sp->granted_at ? date('M d, Y h:i A', strtotime($sp->granted_at)) : 'N/A'; ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <?php if ($st === 'enabled'): ?>
                                <a href="<?php echo site_url('admin/toggle_test_permission?user_id='.$sp->user_id.'&test_title='.urlencode($sp->test_title).'&status=disabled'); ?>" style="background-color: #fef2f2; color: #dc2626; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; border: 1px solid #fca5a5; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-lock-line"></i> Disable Access
                                </a>
                            <?php else: ?>
                                <a href="<?php echo site_url('admin/toggle_test_permission?user_id='.$sp->user_id.'&test_title='.urlencode($sp->test_title).'&status=enabled'); ?>" style="background-color: #f0fdf4; color: #16a34a; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; border: 1px solid #86efac; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-key-2-line"></i> Re-Enable Access
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #64748b;">No test permissions assigned yet. Select a student and test above to enable.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- jQuery and Select2 JS for Searchable Select Box -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#user_id').select2({
        placeholder: "-- Select a Student --",
        allowClear: true,
        width: '100%'
    });
    $('#test_title').select2({
        placeholder: "-- Select an Assessment Test --",
        allowClear: true,
        width: '100%'
    });
});
</script>
