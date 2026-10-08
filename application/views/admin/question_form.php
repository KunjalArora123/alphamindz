<div class="form-card" style="padding: 28px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
        <h2 style="margin: 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="ri-edit-line" style="color: #2563eb;"></i> Edit Question
        </h2>
        <a href="<?php echo site_url('admin/manage_questions/'.($assessment ? $assessment->id : '')); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 13px; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; gap: 4px;">
            <i class="ri-arrow-left-line"></i> Back to Questions
        </a>
    </div>

    <form action="<?php echo site_url('admin/update_question/'.$question->id); ?>" method="POST" enctype="multipart/form-data">
        <?php if(!empty($parts)): ?>
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="part_id" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Test Part / Section <span style="color: #ef4444;">*</span></label>
            <select id="part_id" name="part_id" style="width: 100%; max-width: 400px; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; font-weight: 600; color: #1e293b;" required>
                <?php foreach($parts as $pt): ?>
                    <option value="<?php echo $pt->id; ?>" <?php echo ($question->part_id == $pt->id || $question->subject === $pt->part_name) ? 'selected' : ''; ?>>-<?php echo htmlspecialchars($pt->part_name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <div style="background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 12px; font-weight: 700; color: #475569; display: flex; align-items: center; gap: 4px;">
                <i class="ri-functions" style="color: #2563eb;"></i> Math & Symbol Shortcut Toolbar:
            </span>
            <button type="button" onclick="insertSymbol('²')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Superscript 2">x²</button>
            <button type="button" onclick="insertSymbol('³')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Superscript 3">x³</button>
            <button type="button" onclick="insertSymbol('°')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Degree Sign">°</button>
            <button type="button" onclick="insertSymbol('₂')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Subscript 2">x₂</button>
            <button type="button" onclick="insertSymbol('₃')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Subscript 3">x₃</button>
            <button type="button" onclick="insertSymbol('×')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Multiply">×</button>
            <button type="button" onclick="insertSymbol('÷')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Divide">÷</button>
            <button type="button" onclick="insertSymbol('±')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Plus-Minus">±</button>
            <button type="button" onclick="insertSymbol('√')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Square Root">√</button>
            <button type="button" onclick="insertSymbol('π')" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 3px 9px; font-size: 13px; font-weight: 700; color: #1e293b; cursor: pointer;" title="Pi">π</button>
            <span style="font-size: 11px; color: #64748b; margin-left: 6px;">(Click any symbol to insert at cursor position into Option or Question text)</span>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="question_text" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Question Text <span style="color: #ef4444;">*</span></label>
            <textarea id="question_text" name="question_text" style="width: 100%; height: 100px; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required><?php echo htmlspecialchars($question->question_text); ?></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 20px; background-color: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <label for="question_image" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Question Image (Stored in <code>questions-images/</code>)</label>
            
            <?php if(!empty($question->image_path) && file_exists(FCPATH . $question->image_path)): ?>
                <div style="margin-bottom: 12px; display: flex; align-items: flex-start; gap: 16px;">
                    <img src="<?php echo base_url($question->image_path); ?>" alt="Question Image" style="max-height: 120px; border-radius: 8px; border: 1px solid #cbd5e1; padding: 4px; background: #fff;">
                    <label style="font-size: 13px; color: #dc2626; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; margin-top: 8px;">
                        <input type="checkbox" name="remove_image" value="1"> Delete Current Image
                    </label>
                </div>
            <?php endif; ?>

            <input type="file" id="question_image" name="question_image" accept="image/*" style="width: 100%; max-width: 500px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box; background: #ffffff;">
            <span style="font-size: 12px; color: #64748b; margin-top: 4px; display: block;">Select a file to upload or replace the current image.</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 20px;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_a" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option A <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_a" name="option_a" value="<?php echo htmlspecialchars($question->option_a); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 8px; display: block;">Option A Photo (Optional)</label>
                <?php if(!empty($question->option_a_image) && file_exists(FCPATH . $question->option_a_image)): ?>
                    <div style="margin-top: 4px; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo base_url($question->option_a_image); ?>" alt="Option A Image" style="max-height: 60px; border-radius: 6px; border: 1px solid #cbd5e1; padding: 2px;">
                        <label style="font-size: 12px; color: #dc2626; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="remove_option_a_image" value="1"> Delete
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" name="option_a_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #ffffff;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_b" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option B <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_b" name="option_b" value="<?php echo htmlspecialchars($question->option_b); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 8px; display: block;">Option B Photo (Optional)</label>
                <?php if(!empty($question->option_b_image) && file_exists(FCPATH . $question->option_b_image)): ?>
                    <div style="margin-top: 4px; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo base_url($question->option_b_image); ?>" alt="Option B Image" style="max-height: 60px; border-radius: 6px; border: 1px solid #cbd5e1; padding: 2px;">
                        <label style="font-size: 12px; color: #dc2626; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="remove_option_b_image" value="1"> Delete
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" name="option_b_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #ffffff;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_c" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option C <span style="color: #ef4444;">*</span></label>
                <input type="text" id="option_c" name="option_c" value="<?php echo htmlspecialchars($question->option_c); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;" required>
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 8px; display: block;">Option C Photo (Optional)</label>
                <?php if(!empty($question->option_c_image) && file_exists(FCPATH . $question->option_c_image)): ?>
                    <div style="margin-top: 4px; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo base_url($question->option_c_image); ?>" alt="Option C Image" style="max-height: 60px; border-radius: 6px; border: 1px solid #cbd5e1; padding: 2px;">
                        <label style="font-size: 12px; color: #dc2626; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="remove_option_c_image" value="1"> Delete
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" name="option_c_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #ffffff;">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="option_d" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Option D</label>
                <input type="text" id="option_d" name="option_d" value="<?php echo htmlspecialchars($question->option_d); ?>" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
                <label style="font-size: 12px; color: #64748b; font-weight: 600; margin-top: 8px; display: block;">Option D Photo (Optional)</label>
                <?php if(!empty($question->option_d_image) && file_exists(FCPATH . $question->option_d_image)): ?>
                    <div style="margin-top: 4px; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?php echo base_url($question->option_d_image); ?>" alt="Option D Image" style="max-height: 60px; border-radius: 6px; border: 1px solid #cbd5e1; padding: 2px;">
                        <label style="font-size: 12px; color: #dc2626; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" name="remove_option_d_image" value="1"> Delete
                        </label>
                    </div>
                <?php endif; ?>
                <input type="file" name="option_d_image" accept="image/*" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; background: #ffffff;">
            </div>
        </div>

        <div class="form-group" style="max-width: 260px; margin-bottom: 24px;">
            <label for="correct_option" style="font-weight: 600; color: #334155; margin-bottom: 6px; display: block;">Correct Answer Option <span style="color: #ef4444;">*</span></label>
            <select id="correct_option" name="correct_option" required style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; font-size: 14px; font-weight: 700; color: #15803d; background-color: #f0fdf4; box-sizing: border-box;">
                <option value="A" <?php echo (strtoupper($question->correct_option) === 'A') ? 'selected' : ''; ?>>Option A</option>
                <option value="B" <?php echo (strtoupper($question->correct_option) === 'B') ? 'selected' : ''; ?>>Option B</option>
                <option value="C" <?php echo (strtoupper($question->correct_option) === 'C') ? 'selected' : ''; ?>>Option C</option>
                <option value="D" <?php echo (strtoupper($question->correct_option) === 'D') ? 'selected' : ''; ?>>Option D</option>
            </select>
        </div>

        <div style="margin-top: 28px; padding-top: 18px; border-top: 1px solid #f1f5f9; display: flex; gap: 10px;">
            <button type="submit" style="background-color: #2563eb; color: #ffffff; border: none; padding: 11px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-save-line"></i> Update Question
            </button>
            <a href="<?php echo site_url('admin/manage_questions/'.($assessment ? $assessment->id : '')); ?>" style="background-color: #f1f5f9; color: #334155; text-decoration: none; padding: 11px 20px; font-size: 14px; font-weight: 600; border-radius: 8px; border: 1px solid #cbd5e1; display: inline-block;">Cancel</a>
        </div>
    </form>
</div>

<script>
let lastFocusedInput = null;
document.querySelectorAll('input[type="text"], textarea').forEach(input => {
    input.addEventListener('focus', function() {
        lastFocusedInput = this;
    });
});

function insertSymbol(symbol) {
    if (!lastFocusedInput) {
        lastFocusedInput = document.getElementById('question_text') || document.getElementById('option_a');
    }
    if (lastFocusedInput) {
        const start = lastFocusedInput.selectionStart || lastFocusedInput.value.length;
        const end = lastFocusedInput.selectionEnd || lastFocusedInput.value.length;
        const val = lastFocusedInput.value;
        lastFocusedInput.value = val.substring(0, start) + symbol + val.substring(end);
        lastFocusedInput.focus();
        lastFocusedInput.setSelectionRange(start + symbol.length, start + symbol.length);
    }
}
</script>
