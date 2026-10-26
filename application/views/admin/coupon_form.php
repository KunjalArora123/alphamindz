<?php 
    $is_edit = !empty($coupon); 
    $action_url = $is_edit ? site_url('admin/update_coupon/' . $coupon->id) : site_url('admin/save_coupon');
?>

<div class="data-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; max-width: 800px; margin: 0 auto 30px auto;">
    
    <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2 style="margin: 0 0 4px 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-coupon-3-line" style="color: #2563eb; font-size: 24px;"></i> <?php echo $is_edit ? 'Edit Coupon Code' : 'Create New Coupon Code'; ?>
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Configure the discount type, minimum purchase requirements, and expiration date.</p>
        </div>
        <a href="<?php echo site_url('admin/coupons'); ?>" style="color: #64748b; background: #f1f5f9; padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="ri-arrow-left-line"></i> Back to Coupons
        </a>
    </div>

    <form action="<?php echo $action_url; ?>" method="POST" style="padding: 24px;" id="couponForm">
        
        <!-- Field 1: Coupon Code (Required) + Generate Button -->
        <div style="margin-bottom: 22px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                Coupon Code <span style="color: #dc2626;">*</span>
            </label>
            <div style="display: flex; gap: 10px;">
                <div style="position: relative; flex: 1;">
                    <input type="text" 
                           name="code" 
                           id="coupon_code_input" 
                           required 
                           maxlength="50"
                           value="<?php echo $is_edit ? htmlspecialchars($coupon->code) : ''; ?>" 
                           placeholder="e.g. WELCOME20, ALPHASAVE, DIWALI100" 
                           style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 15px; font-family: monospace; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; box-sizing: border-box; outline: none;"
                           oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9_\-]/g, '')">
                </div>
                <button type="button" 
                        onclick="generateRandomCoupon()" 
                        style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #1e293b; padding: 11px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; transition: all 0.2s;"
                        onmouseover="this.style.background='#e2e8f0'"
                        onmouseout="this.style.background='#f1f5f9'">
                    <i class="ri-magic-line" style="color: #2563eb;"></i> Generate Code
                </button>
            </div>
            <span style="font-size: 12px; color: #64748b; margin-top: 5px; display: block;">
                The promo code customers will type on the cart page (letters, numbers, hyphens only).
            </span>
        </div>

        <!-- Field 2: Discount Type and Amount (Required) -->
        <div style="margin-bottom: 22px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px;">
            <label style="display: block; font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                Discount: How Much OFF? <span style="color: #dc2626;">*</span>
            </label>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                        Discount Type <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="discount_type" id="discount_type_select" onchange="updateDiscountLabel()" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #fff; font-weight: 500; outline: none; box-sizing: border-box;">
                        <option value="percentage" <?php echo ($is_edit && $coupon->discount_type === 'percentage') ? 'selected' : ''; ?>>Percentage Discount (%)</option>
                        <option value="fixed" <?php echo ($is_edit && $coupon->discount_type === 'fixed') ? 'selected' : ''; ?>>Fixed Numeric Amount (₹ Flat OFF)</option>
                    </select>
                </div>

                <div>
                    <label id="discount_value_label" style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">
                        Percentage Off (%) <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="number" 
                               name="discount_value" 
                               id="discount_value_input" 
                               step="0.01" 
                               min="0.01" 
                               required 
                               value="<?php echo $is_edit ? floatval($coupon->discount_value) : ''; ?>" 
                               placeholder="e.g. 20 (for 20% off)" 
                               style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; font-weight: 600; box-sizing: border-box; outline: none;">
                    </div>
                </div>
            </div>

            <div id="discount_hint" style="font-size: 12px; color: #64748b;">
                Percentage discount reduces cart subtotal by the specified percent (up to 100%).
            </div>
        </div>

        <!-- Field 3: Minimum Spend (Not Required / Optional) -->
        <div style="margin-bottom: 22px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                How much has the customer have to spend? <span style="color: #64748b; font-weight: 400; font-size: 13px;">(Optional)</span>
            </label>
            <div style="position: relative;">
                <span style="position: absolute; left: 14px; top: 11px; color: #64748b; font-weight: 600;">₹</span>
                <input type="number" 
                       name="min_spend" 
                       id="min_spend_input" 
                       step="0.01" 
                       min="0"
                       value="<?php echo ($is_edit && !empty($coupon->min_spend)) ? floatval($coupon->min_spend) : ''; ?>" 
                       placeholder="Leave blank or enter minimum order value (e.g. 500)" 
                       style="width: 100%; padding: 11px 14px 11px 32px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box; outline: none;">
            </div>
            <span style="font-size: 12px; color: #64748b; margin-top: 5px; display: block;">
                Minimum cart total required before this coupon can be applied. Leave empty for no minimum spend requirement.
            </span>
        </div>

        <!-- Field 4: Valid Till (Required) -->
        <div style="margin-bottom: 22px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 6px;">
                Valid Till (Expiration Date) <span style="color: #dc2626;">*</span>
            </label>
            <input type="date" 
                   name="valid_till" 
                   id="valid_till_input" 
                   required 
                   min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo $is_edit ? date('Y-m-d', strtotime($coupon->valid_till)) : date('Y-m-d', strtotime('+30 days')); ?>" 
                   style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box; outline: none;">
            <span style="font-size: 12px; color: #64748b; margin-top: 5px; display: block;">
                The coupon will remain usable up until 11:59:59 PM on this date.
            </span>
        </div>

        <!-- Field 5: Status -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                Status
            </label>
            <div style="display: flex; gap: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: #1e293b; cursor: pointer;">
                    <input type="radio" name="status" value="1" <?php echo (!$is_edit || $coupon->status == 1) ? 'checked' : ''; ?>>
                    <span style="color: #16a34a; font-weight: 600;"><i class="ri-checkbox-circle-fill"></i> Active</span>
                </label>
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: #1e293b; cursor: pointer;">
                    <input type="radio" name="status" value="0" <?php echo ($is_edit && $coupon->status == 0) ? 'checked' : ''; ?>>
                    <span style="color: #64748b; font-weight: 600;"><i class="ri-pause-circle-fill"></i> Inactive</span>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <a href="<?php echo site_url('admin/coupons'); ?>" style="padding: 10px 18px; border: 1px solid #cbd5e1; color: #475569; background: #fff; border-radius: 6px; font-size: 14px; font-weight: 600; text-decoration: none;">
                Cancel
            </a>
            <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <i class="ri-save-line"></i> <?php echo $is_edit ? 'Update Coupon' : 'Save & Publish Coupon'; ?>
            </button>
        </div>

    </form>
</div>

<script>
function updateDiscountLabel() {
    var select = document.getElementById('discount_type_select');
    var label = document.getElementById('discount_value_label');
    var input = document.getElementById('discount_value_input');
    var hint = document.getElementById('discount_hint');

    if (select.value === 'percentage') {
        label.innerHTML = 'Percentage Off (%) <span style="color: #dc2626;">*</span>';
        input.placeholder = 'e.g. 20 (for 20% off)';
        input.max = '100';
        hint.innerHTML = 'Percentage discount reduces cart subtotal by the specified percent (up to 100%).';
    } else {
        label.innerHTML = 'Flat Numeric Value Off (₹) <span style="color: #dc2626;">*</span>';
        input.placeholder = 'e.g. 150 (for ₹150 flat off)';
        input.removeAttribute('max');
        hint.innerHTML = 'Fixed numeric value discount subtracts this exact rupee amount from the order total.';
    }
}

function generateRandomCoupon() {
    var prefixes = ['ALPHA', 'MINDZ', 'SAVE', 'OFF', 'LEARN', 'SUPER'];
    var prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    var num = Math.floor(10 + Math.random() * 90);
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    var suffix = '';
    for (var i = 0; i < 2; i++) {
        suffix += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('coupon_code_input').value = prefix + num + suffix;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateDiscountLabel();
});
</script>
