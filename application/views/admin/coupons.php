<div class="data-card" style="background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 style="margin: 0 0 4px 0; color: #0f172a; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="ri-coupon-3-line" style="color: #2563eb; font-size: 24px;"></i> Discount Coupons
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Create, manage, and monitor coupon discount codes available for customers at checkout.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="<?php echo site_url('cart'); ?>" target="_blank" class="btn" style="background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <i class="ri-shopping-cart-2-line"></i> View Cart Page
            </a>
            <a href="<?php echo site_url('admin/add_coupon'); ?>" class="btn" style="background-color: #2563eb; color: #ffffff; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                <i class="ri-add-line"></i> Create Coupon
            </a>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <div style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="ri-coupon-line"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Coupons</div>
                <div style="font-size: 20px; font-weight: 800; color: #0f172a;"><?php echo isset($counts['total']) ? $counts['total'] : 0; ?></div>
            </div>
        </div>

        <div style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Active Coupons</div>
                <div style="font-size: 20px; font-weight: 800; color: #10b981;"><?php echo isset($counts['active']) ? $counts['active'] : 0; ?></div>
            </div>
        </div>

        <div style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="ri-time-line"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Expired / Inactive</div>
                <div style="font-size: 20px; font-weight: 800; color: #ef4444;"><?php echo isset($counts['expired']) ? $counts['expired'] : 0; ?></div>
            </div>
        </div>

        <div style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px;">
            <div style="width: 44px; height: 44px; border-radius: 8px; background: #f5f3ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="ri-user-star-line"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Total Redemptions</div>
                <div style="font-size: 20px; font-weight: 800; color: #8b5cf6;"><?php echo isset($counts['total_used']) ? $counts['total_used'] : 0; ?></div>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div style="padding: 24px;">
        <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #475569;">
                        <th style="padding: 14px 16px; text-align: left;">Coupon Code</th>
                        <th style="padding: 14px 16px; text-align: left;">Discount (How much OFF)</th>
                        <th style="padding: 14px 16px; text-align: left;">Min Spend Required</th>
                        <th style="padding: 14px 16px; text-align: left;">Valid Till</th>
                        <th style="padding: 14px 16px; text-align: center;">Times Used</th>
                        <th style="padding: 14px 16px; text-align: center;">Status</th>
                        <th style="padding: 14px 16px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($coupons)): foreach($coupons as $c): 
                        $is_expired = (strtotime($c->valid_till . ' 23:59:59') < time());
                        $is_active = ($c->status == 1 && !$is_expired);
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                        <!-- Coupon Code -->
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="font-family: monospace; font-size: 14px; font-weight: 700; background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 6px; border: 1px dashed #93c5fd; letter-spacing: 0.5px;">
                                    <?php echo htmlspecialchars($c->code); ?>
                                </span>
                                <button type="button" onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($c->code); ?>'); alert('Copied <?php echo htmlspecialchars($c->code); ?> to clipboard!');" title="Copy code" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px; font-size: 16px;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#94a3b8'">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Discount Type and Value -->
                        <td style="padding: 14px 16px;">
                            <?php if($c->discount_type === 'percentage'): ?>
                                <span style="display: inline-flex; align-items: center; gap: 5px; background: #ecfdf5; color: #059669; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 13px;">
                                    <i class="ri-percent-line"></i> <?php echo floatval($c->discount_value); ?>% OFF
                                </span>
                            <?php else: ?>
                                <span style="display: inline-flex; align-items: center; gap: 5px; background: #f0fdf4; color: #16a34a; font-weight: 700; padding: 4px 10px; border-radius: 20px; font-size: 13px;">
                                    <i class="ri-money-rupee-circle-line"></i> ₹<?php echo number_format($c->discount_value, 2); ?> OFF
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Minimum Spend Required -->
                        <td style="padding: 14px 16px; color: #334155;">
                            <?php if(!empty($c->min_spend) && floatval($c->min_spend) > 0): ?>
                                <strong style="color: #0f172a;">₹<?php echo number_format($c->min_spend, 2); ?></strong>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic;">No minimum spend</span>
                            <?php endif; ?>
                        </td>

                        <!-- Valid Till -->
                        <td style="padding: 14px 16px;">
                            <div>
                                <span style="font-weight: 600; color: #0f172a;">
                                    <?php echo date('M d, Y', strtotime($c->valid_till)); ?>
                                </span>
                            </div>
                            <div style="margin-top: 3px;">
                                <?php if($is_expired): ?>
                                    <span style="font-size: 11px; color: #dc2626; font-weight: 600; background: #fef2f2; padding: 2px 6px; border-radius: 4px;">
                                        <i class="ri-time-line"></i> Expired
                                    </span>
                                <?php else: 
                                    $diff_days = ceil((strtotime($c->valid_till . ' 23:59:59') - time()) / 86400);
                                ?>
                                    <span style="font-size: 11px; color: #16a34a; font-weight: 600; background: #f0fdf4; padding: 2px 6px; border-radius: 4px;">
                                        <i class="ri-check-line"></i> <?php echo ($diff_days <= 1) ? 'Expires today' : ($diff_days . ' days left'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Times Used -->
                        <td style="padding: 14px 16px; text-align: center;">
                            <span style="background: #f1f5f9; color: #334155; font-weight: 700; padding: 4px 12px; border-radius: 12px; font-size: 12px;">
                                <?php echo (int)$c->used_count; ?>
                            </span>
                        </td>

                        <!-- Status -->
                        <td style="padding: 14px 16px; text-align: center;">
                            <?php if($is_expired): ?>
                                <span style="color: #ef4444; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-error-warning-fill"></i> Expired
                                </span>
                            <?php elseif($c->status == 1): ?>
                                <a href="<?php echo site_url('admin/toggle_coupon_status/' . $c->id); ?>" title="Click to deactivate" style="color: #16a34a; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; background: #ecfdf5; padding: 4px 8px; border-radius: 4px;">
                                    <i class="ri-checkbox-circle-fill"></i> Active
                                </a>
                            <?php else: ?>
                                <a href="<?php echo site_url('admin/toggle_coupon_status/' . $c->id); ?>" title="Click to activate" style="color: #94a3b8; font-weight: 600; font-size: 12px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">
                                    <i class="ri-pause-circle-fill"></i> Inactive
                                </a>
                            <?php endif; ?>
                        </td>

                        <!-- Actions -->
                        <td style="padding: 14px 16px; text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="<?php echo site_url('admin/edit_coupon/' . $c->id); ?>" style="color: #2563eb; background: #eff6ff; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="ri-edit-line"></i> Edit
                                </a>
                                <a href="<?php echo site_url('admin/delete_coupon/' . $c->id); ?>" onclick="return confirm('Are you sure you want to delete coupon <?php echo htmlspecialchars(addslashes($c->code)); ?>?');" style="color: #dc2626; background: #fef2f2; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 600;">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" style="padding: 50px 20px; text-align: center; color: #64748b;">
                            <div style="font-size: 40px; color: #cbd5e1; margin-bottom: 10px;">
                                <i class="ri-coupon-3-line"></i>
                            </div>
                            <h3 style="margin: 0 0 6px 0; color: #334155; font-size: 16px;">No discount coupons created yet</h3>
                            <p style="margin: 0 0 16px 0; font-size: 13px;">Create promotional coupon codes to give students and customers percentage or flat discounts.</p>
                            <a href="<?php echo site_url('admin/add_coupon'); ?>" style="background-color: #2563eb; color: #ffffff; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="ri-add-line"></i> Create First Coupon
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
