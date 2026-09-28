<!-- Select2 CSS for Searchable Select Boxes -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 40px !important;
    padding: 5px 12px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
    color: #1e293b !important;
    font-size: 14px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px !important;
}
</style>

<div style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h2 style="font-size: 1.5rem; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="ri-award-line" style="color: #2563eb;"></i> Certification & Credential Management
        </h2>
        <span style="font-size: 0.9rem; color: #64748b; background: #f1f5f9; padding: 6px 14px; border-radius: 12px; font-weight: 700;">
            Total Issued: <strong><?php echo count($certificates); ?></strong>
        </span>
    </div>

    <!-- Quick Issue Certificate Card -->
    <div style="background: linear-gradient(135deg, #eff6ff, #f0fdf4); padding: 22px; border-radius: 10px; border: 1px solid #bfdbfe; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(37,99,235,0.05);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <h4 style="margin: 0; color: #1e3a8a; font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-award-fill" style="color: #2563eb; font-size: 1.2rem;"></i> Issue / Allot New Course Certificate
            </h4>
            <span style="font-size: 0.78rem; color: #1e40af; background: #dbeafe; padding: 4px 10px; border-radius: 10px; font-weight: 600;">Manual Certificate Allotment</span>
        </div>

        <form action="<?php echo site_url('admin/process_issue_certificate'); ?>" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; align-items: flex-end;">
            <div>
                <label for="cert_user_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Select Student *</label>
                <select name="user_id" id="cert_user_id" style="width: 100%;" required>
                    <option value="">-- Choose a Student --</option>
                    <?php if(!empty($students)): foreach($students as $st): ?>
                        <?php 
                            $st_name = trim(($st->first_name ?? '') . ' ' . ($st->last_name ?? ''));
                            if (empty($st_name) && !empty($st->username)) $st_name = $st->username;
                            if (empty($st_name) && !empty($st->email)) $st_name = explode('@', $st->email)[0];
                        ?>
                        <option value="<?php echo $st->id; ?>"><?php echo htmlspecialchars($st_name); ?> (<?php echo htmlspecialchars($st->email); ?>)</option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div>
                <label for="cert_course_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Select Course *</label>
                <select name="course_id" id="cert_course_id" style="width: 100%;" required>
                    <option value="">-- Choose a Course --</option>
                    <?php if(!empty($courses)): foreach($courses as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo htmlspecialchars($c->title); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.85rem;">Pricing / Achievement Tier</label>
                <select name="tier_key" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; background: #fff; height: 40px; box-sizing: border-box;">
                    <option value="basic">Basic Tier</option>
                    <option value="standard">Standard Tier</option>
                    <option value="advanced">Advanced Tier</option>
                    <option value="premium">Premium Tier</option>
                </select>
            </div>

            <div>
                <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; cursor: pointer; height: 40px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                    <i class="ri-award-line"></i> Issue Certificate
                </button>
            </div>
        </form>
    </div>

    <!-- Search & Filter Controls -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        
        <form action="<?php echo site_url('admin/certificates'); ?>" method="GET" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 500px;">
            <div style="position: relative; flex: 1;">
                <i class="ri-search-line" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
                <input type="text" name="search" id="certSearchInput" value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>" placeholder="Search by student name, credential ID, course..." style="width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box;">
            </div>
            <button type="submit" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.88rem; white-space: nowrap;">
                Search
            </button>
            <?php if(!empty($search) || (isset($status_filter) && $status_filter !== 'all')): ?>
                <a href="<?php echo site_url('admin/certificates'); ?>" style="padding: 8px 12px; background-color: #f1f5f9; color: #475569; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid #cbd5e1; white-space: nowrap;">
                    Clear
                </a>
            <?php endif; ?>
        </form>

        <!-- Status Filter Tabs -->
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo site_url('admin/certificates?status=all' . (!empty($search) ? '&search='.urlencode($search) : '')); ?>" 
               style="padding: 6px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; text-decoration: none; <?php echo ($status_filter === 'all') ? 'background: #3b82f6; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
                All
            </a>
            <a href="<?php echo site_url('admin/certificates?status=granted' . (!empty($search) ? '&search='.urlencode($search) : '')); ?>" 
               style="padding: 6px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; text-decoration: none; <?php echo ($status_filter === 'granted') ? 'background: #10b981; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
                Granted / Active
            </a>
            <a href="<?php echo site_url('admin/certificates?status=revoked' . (!empty($search) ? '&search='.urlencode($search) : '')); ?>" 
               style="padding: 6px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; text-decoration: none; <?php echo ($status_filter === 'revoked') ? 'background: #ef4444; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
                Revoked
            </a>
        </div>

    </div>
</div>

<?php if(empty($certificates)): ?>
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 40px; text-align: center; color: #64748b;">
        <i class="ri-award-line" style="font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
        <h3 style="margin: 0 0 5px 0; color: #334155;">No certificates found</h3>
        <p style="margin: 0; font-size: 0.9rem;">No course certificates match your search query or filter.</p>
    </div>
<?php else: ?>
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                    <th style="padding: 14px 16px;">Student Name</th>
                    <th style="padding: 14px 16px;">Course & Tier</th>
                    <th style="padding: 14px 16px;">Credential ID</th>
                    <th style="padding: 14px 16px;">Allotted Date (When)</th>
                    <th style="padding: 14px 16px;">Status</th>
                    <th style="padding: 14px 16px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody id="certTableBody">
                <?php foreach($certificates as $c): ?>
                    <?php 
                        $user_display_name = trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''));
                        if (empty($user_display_name) && !empty($c->username)) {
                            $user_display_name = $c->username;
                        }
                        if (empty($user_display_name) && !empty($c->email)) {
                            $user_display_name = explode('@', $c->email)[0];
                        }
                        $final_user_name = !empty($user_display_name) ? $user_display_name : 'User #' . $c->user_id;
                    ?>
                    <tr class="cert-row" style="border-bottom: 1px solid #f1f5f9;">
                        <!-- Student Info -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($final_user_name); ?>
                            </div>
                            <div style="font-size: 0.82rem; color: #64748b; margin-top: 2px;">
                                <i class="ri-mail-line" style="color: #94a3b8;"></i> <?php echo htmlspecialchars($c->email); ?>
                            </div>
                            <a href="<?php echo site_url('admin/user_details/' . $c->user_id); ?>" style="font-size: 0.75rem; color: #2563eb; text-decoration: none; display: inline-block; margin-top: 4px; font-weight: 600;">
                                User Profile #<?php echo $c->user_id; ?>
                            </a>
                        </td>

                        <!-- Course & Tier -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="font-weight: 600; color: #1e293b;">
                                <?php echo htmlspecialchars($c->course_title ? $c->course_title : 'Course #' . $c->course_id); ?>
                            </div>
                            <span style="display: inline-block; margin-top: 4px; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                <?php echo htmlspecialchars($c->tier_key ? $c->tier_key : 'basic'); ?> Tier
                            </span>
                        </td>

                        <!-- Credential ID -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 6px 10px; border-radius: 6px; font-family: monospace; font-weight: 700; color: #1e293b; font-size: 0.88rem; display: inline-block;">
                                <?php echo htmlspecialchars($c->credential_id); ?>
                            </div>
                        </td>

                        <!-- Issue Date (When Allotted) -->
                        <td style="padding: 16px; vertical-align: top; color: #475569; font-size: 0.9rem;">
                            <i class="ri-calendar-event-line" style="color: #64748b;"></i> <?php echo date('M d, Y', strtotime($c->issue_date)); ?>
                        </td>

                        <!-- Status -->
                        <td style="padding: 16px; vertical-align: top;">
                            <?php if($c->status === 'granted'): ?>
                                <span style="background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                    Granted / Active
                                </span>
                            <?php else: ?>
                                <span style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                    Revoked
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Actions -->
                        <td style="padding: 16px; vertical-align: top; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                <a href="<?php echo site_url('admin/view_certificate/' . urlencode($c->credential_id)); ?>" target="_blank" style="color: #3730a3; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 5px 10px; background: #e0e7ff; border: 1px solid #c7d2fe; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-eye-line"></i> View Certificate
                                </a>

                                <a href="<?php echo site_url('verify?id=' . urlencode($c->credential_id)); ?>" target="_blank" style="color: #2563eb; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 5px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-external-link-line"></i> Verify
                                </a>

                                <?php if($c->status === 'granted'): ?>
                                    <a href="<?php echo site_url('admin/toggle_certificate_status/' . $c->id . '/revoked'); ?>" 
                                       onclick="return confirm('Are you sure you want to REVOKE certificate <?php echo htmlspecialchars($c->credential_id); ?> for <?php echo htmlspecialchars($final_user_name); ?>?');" 
                                       style="background: #ef4444; color: white; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 5px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-close-circle-line"></i> Revoke Certificate
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('admin/toggle_certificate_status/' . $c->id . '/granted'); ?>" 
                                       onclick="return confirm('Re-grant certificate access for <?php echo htmlspecialchars($final_user_name); ?>?');" 
                                       style="background: #10b981; color: white; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 5px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-checkbox-circle-line"></i> Re-Grant Certificate
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Select2 JS and Live Instant Filter -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#cert_user_id').select2({
        placeholder: "-- Choose a Student --",
        allowClear: true,
        width: '100%'
    });
    $('#cert_course_id').select2({
        placeholder: "-- Choose a Course --",
        allowClear: true,
        width: '100%'
    });

    // Instant keyup live search
    const searchInput = document.getElementById('certSearchInput');
    const tableBody = document.getElementById('certTableBody');
    if (searchInput && tableBody) {
        const rows = tableBody.querySelectorAll('.cert-row');
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>
