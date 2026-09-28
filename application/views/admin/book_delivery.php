<div style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h2 style="font-size: 1.5rem; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <i class="ri-book-read-line" style="color: #2563eb;"></i> Book Delivery - Temporary Link Generator
        </h2>
        <span style="font-size: 0.88rem; color: #64748b; background: #f1f5f9; padding: 6px 14px; border-radius: 12px; font-weight: 700;">
            Active Links: <strong><?php echo count(array_filter($links, function($l) { return strtotime($l->expires_at) > time(); })); ?></strong>
        </span>
    </div>

    <!-- Create Temporary Link Form Card -->
    <div style="background: linear-gradient(135deg, #eff6ff, #f8fafc); padding: 24px; border-radius: 12px; border: 1px solid #bfdbfe; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(37,99,235,0.06);">
        <h4 style="margin: 0 0 16px 0; color: #1e3a8a; font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-links-line" style="color: #2563eb; font-size: 1.3rem;"></i> Generate Customer Download Link (TTL Protected)
        </h4>

        <form action="<?php echo site_url('admin/create_download_link'); ?>" method="POST" enctype="multipart/form-data">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 20px;">
                
                <!-- Option 1: Select Existing PDF -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">Select Uploaded PDF Book *</label>
                    <select name="selected_pdf" id="selected_pdf" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; background: #ffffff;">
                        <option value="">-- Choose from uploaded PDFs --</option>
                        <?php if(!empty($uploaded_pdfs)): foreach($uploaded_pdfs as $up): ?>
                            <?php if(!empty($up->file_path)): ?>
                                <option value="<?php echo htmlspecialchars($up->file_path); ?>">
                                    <?php echo htmlspecialchars($up->title); ?> (<?php echo htmlspecialchars($up->course_title ? $up->course_title : 'General'); ?>)
                                </option>
                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Option 2: Or Upload New PDF File -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">Or Upload New PDF File</label>
                    <input type="file" name="new_pdf" accept=".pdf,application/pdf" style="width: 100%; padding: 7px 10px; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 0.85rem; background: #ffffff; cursor: pointer; box-sizing: border-box;">
                </div>

            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 20px;">
                
                <!-- TTL Input in Seconds -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">
                        Link Time-To-Live (TTL in Seconds) *
                    </label>
                    <input type="number" name="ttl_seconds" id="ttl_seconds" value="3600" min="10" required placeholder="e.g. 600 for 10 mins" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; font-weight: 700; color: #2563eb; outline: none; box-sizing: border-box; background: #ffffff;">
                    
                    <!-- Quick Presets -->
                    <div style="display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap;">
                        <button type="button" onclick="setTTL(300)" style="background: #e0f2fe; border: none; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">5 Mins (300s)</button>
                        <button type="button" onclick="setTTL(600)" style="background: #e0f2fe; border: none; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">10 Mins (600s)</button>
                        <button type="button" onclick="setTTL(3600)" style="background: #e0f2fe; border: none; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">1 Hour (3600s)</button>
                        <button type="button" onclick="setTTL(86400)" style="background: #e0f2fe; border: none; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">24 Hours (86400s)</button>
                    </div>
                </div>

                <!-- Custom Download Display Name -->
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600; color: #1e293b; font-size: 0.88rem;">Download Display Name (Optional)</label>
                    <input type="text" name="custom_file_name" placeholder="e.g. Course_Ebook_2026.pdf" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9rem; outline: none; box-sizing: border-box; background: #ffffff;">
                </div>

            </div>

            <button type="submit" style="background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 0.98rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                <i class="ri-link-m" style="font-size: 1.2rem;"></i> Create Temporary Download Link
            </button>
        </form>
    </div>

    <!-- Active & Historical Links List -->
    <div>
        <h3 style="margin: 0 0 16px 0; color: #0f172a; font-size: 1.15rem; font-weight: 700;">Generated Temporary Download Links</h3>

        <?php if(empty($links)): ?>
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 40px; text-align: center; color: #64748b;">
                <i class="ri-links-line" style="font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 5px 0; color: #334155;">No download links generated yet</h4>
                <p style="margin: 0; font-size: 0.9rem;">Select a PDF and set a TTL above to create your first temporary link.</p>
            </div>
        <?php else: ?>
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 14px 16px;">PDF Book</th>
                            <th style="padding: 14px 16px;">Temporary Link URL</th>
                            <th style="padding: 14px 16px;">TTL & Expiration</th>
                            <th style="padding: 14px 16px;">Status</th>
                            <th style="padding: 14px 16px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($links as $l): ?>
                            <?php 
                                $full_url = site_url('download/book?token=' . $l->token);
                                $is_expired = (strtotime($l->expires_at) <= time());
                                $remaining_sec = max(0, strtotime($l->expires_at) - time());
                            ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                
                                <!-- File Details -->
                                <td style="padding: 16px; vertical-align: top;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem; display: flex; align-items: center; gap: 6px;">
                                        <i class="ri-file-pdf-fill" style="color: #dc2626; font-size: 1.1rem;"></i>
                                        <?php echo htmlspecialchars($l->file_name); ?>
                                    </div>
                                    <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px; font-family: monospace;">
                                        <?php echo htmlspecialchars($l->file_path); ?>
                                    </div>
                                </td>

                                <!-- Generated Link & Copy Button -->
                                <td style="padding: 16px; vertical-align: top;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <input type="text" readonly value="<?php echo htmlspecialchars($full_url); ?>" id="link_input_<?php echo $l->id; ?>" style="padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; font-size: 0.82rem; width: 240px; background: #f8fafc; outline: none;">
                                        <button type="button" onclick="copyLink('link_input_<?php echo $l->id; ?>', this)" style="background: #2563eb; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="ri-file-copy-line"></i> Copy Link
                                        </button>
                                    </div>
                                </td>

                                <!-- TTL & Expiration -->
                                <td style="padding: 16px; vertical-align: top;">
                                    <div style="font-weight: 600; color: #334155; font-size: 0.88rem;">
                                        TTL: <?php echo number_format($l->ttl_seconds); ?> seconds
                                    </div>
                                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                                        Expires: <?php echo date('M d, Y h:i:s A', strtotime($l->expires_at)); ?>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td style="padding: 16px; vertical-align: top;">
                                    <?php if(!$is_expired): ?>
                                        <span style="background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                            Active (<?php echo number_format($remaining_sec); ?>s remaining)
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                            Expired
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Actions -->
                                <td style="padding: 16px; vertical-align: top; text-align: right;">
                                    <a href="<?php echo site_url('admin/delete_download_link/' . $l->id); ?>" onclick="return confirm('Delete this temporary download link?');" style="color: #dc2626; text-decoration: none; font-size: 0.85rem; font-weight: 600;">
                                        <i class="ri-delete-bin-line"></i> Delete
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function setTTL(sec) {
    document.getElementById('ttl_seconds').value = sec;
}

function copyLink(inputId, btnElement) {
    const input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);

    const origText = btnElement.innerHTML;
    btnElement.innerHTML = '<i class="ri-check-line"></i> Copied!';
    btnElement.style.background = '#10b981';

    setTimeout(() => {
        btnElement.innerHTML = origText;
        btnElement.style.background = '#2563eb';
    }, 2000);
}
</script>
