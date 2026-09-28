<?php
$is_edit = isset($assessment);
$form_action = $is_edit ? site_url('admin/update_assessment/'.$assessment->id) : site_url('admin/save_assessment');
?>

<div class="form-card" style="padding: 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
        <h2 style="margin: 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="<?php echo $is_edit ? 'ri-edit-line' : 'ri-add-circle-line'; ?>" style="color: #0969da;"></i> 
            <?php echo $is_edit ? 'Edit Assessment Test' : 'Create New Assessment Test'; ?>
        </h2>
        <a href="<?php echo site_url('admin/manage_assessments'); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to List
        </a>
    </div>

    <form action="<?php echo $form_action; ?>" method="POST">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="title" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Test Title <span style="color: #ef4444;">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo $is_edit ? htmlspecialchars($assessment->title) : ''; ?>" placeholder="e.g. Logical Reasoning & Aptitude Test" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="description" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Description</label>
            <textarea id="description" name="description" placeholder="Brief explanation of what this test evaluates..." style="width: 100%; height: 120px; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;"><?php echo $is_edit ? htmlspecialchars($assessment->description) : ''; ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="time_limit" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Time Limit (Minutes) <span style="color: #ef4444;">*</span></label>
                <input type="number" id="time_limit" name="time_limit" min="1" max="600" value="<?php echo $is_edit ? (int)$assessment->time_limit : 45; ?>" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="status" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Status</label>
                <select id="status" name="status" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
                    <option value="active" <?php echo ($is_edit && strtolower($assessment->status) == 'active') ? 'selected' : (! $is_edit ? 'selected' : ''); ?>>Active (Published)</option>
                    <option value="inactive" <?php echo ($is_edit && strtolower($assessment->status) == 'inactive') ? 'selected' : ''; ?>>Inactive (Draft)</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid #f1f5f9; display: flex; gap: 10px;">
            <button type="submit" style="background-color: #10b981; color: #ffffff; border: none; padding: 11px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(16,185,129,0.25);">
                <i class="ri-save-line"></i> <?php echo $is_edit ? 'Update Assessment Test' : 'Save & Create Test'; ?>
            </button>
            <a href="<?php echo site_url('admin/manage_assessments'); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 11px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: 1px solid #cbd5e1; display: inline-block;">Cancel</a>
        </div>
    </form>
</div>
