<div style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 1.5rem; color: #1e293b; margin: 0;">Manage Orders</h2>
        <div style="font-size: 0.9rem; color: #64748b;">
            <i class="ri-shopping-bag-3-line"></i> Total Orders: <strong><?php echo $counts['all']; ?></strong>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div style="display: flex; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 25px; flex-wrap: wrap;">
        <a href="<?php echo site_url('admin/orders/pending'); ?>" 
           style="padding: 10px 18px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; <?php echo ($filter === 'pending') ? 'background: #f59e0b; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-time-line"></i> Pending Approval
            <span style="background: <?php echo ($filter === 'pending') ? '#fff' : '#cbd5e1'; ?>; color: <?php echo ($filter === 'pending') ? '#d97706' : '#334155'; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: 700;">
                <?php echo $counts['pending']; ?>
            </span>
        </a>

        <a href="<?php echo site_url('admin/orders/completed'); ?>" 
           style="padding: 10px 18px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; <?php echo ($filter === 'completed') ? 'background: #10b981; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-checkbox-circle-line"></i> Completed Orders
            <span style="background: <?php echo ($filter === 'completed') ? '#fff' : '#cbd5e1'; ?>; color: <?php echo ($filter === 'completed') ? '#059669' : '#334155'; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: 700;">
                <?php echo $counts['completed']; ?>
            </span>
        </a>

        <a href="<?php echo site_url('admin/orders/rejected'); ?>" 
           style="padding: 10px 18px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; <?php echo ($filter === 'rejected') ? 'background: #ef4444; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-close-circle-line"></i> Rejected Orders
            <span style="background: <?php echo ($filter === 'rejected') ? '#fff' : '#cbd5e1'; ?>; color: <?php echo ($filter === 'rejected') ? '#dc2626' : '#334155'; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: 700;">
                <?php echo $counts['rejected']; ?>
            </span>
        </a>

        <a href="<?php echo site_url('admin/orders/all'); ?>" 
           style="padding: 10px 18px; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; <?php echo ($filter === 'all') ? 'background: #3b82f6; color: #fff;' : 'background: #f1f5f9; color: #475569;'; ?>">
            <i class="ri-list-check"></i> All Orders
            <span style="background: <?php echo ($filter === 'all') ? '#fff' : '#cbd5e1'; ?>; color: <?php echo ($filter === 'all') ? '#2563eb' : '#334155'; ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem; font-weight: 700;">
                <?php echo $counts['all']; ?>
            </span>
        </a>
    </div>
</div>

<?php if(empty($orders)): ?>
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 40px; text-align: center; color: #64748b;">
        <i class="ri-inbox-line" style="font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
        <h3 style="margin: 0 0 5px 0; color: #334155;">No orders found</h3>
        <p style="margin: 0; font-size: 0.9rem;">There are currently no orders in the "<?php echo ucfirst($filter); ?>" tab.</p>
    </div>
<?php else: ?>
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                    <th style="padding: 14px 16px;">Order # & Date</th>
                    <th style="padding: 14px 16px;">Customer (Who Ordered)</th>
                    <th style="padding: 14px 16px;">Items & Amount</th>
                    <th style="padding: 14px 16px;">Transaction ID / UTR</th>
                    <th style="padding: 14px 16px;">Proof of Payment</th>
                    <th style="padding: 14px 16px;">Status</th>
                    <th style="padding: 14px 16px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                        <!-- Order # & Date -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="font-weight: 700; color: #1e293b; font-family: monospace; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($o->order_number); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">
                                <i class="ri-calendar-line"></i> <?php echo date('M d, Y h:i A', strtotime($o->created_at)); ?>
                            </div>
                        </td>

                        <!-- Customer Details -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                <?php echo htmlspecialchars($o->customer_name); ?>
                            </div>
                            <div style="font-size: 0.82rem; color: #475569; margin-top: 2px;">
                                <i class="ri-mail-line" style="color: #94a3b8;"></i> <?php echo htmlspecialchars($o->customer_email); ?>
                            </div>
                            <?php if(!empty($o->customer_phone)): ?>
                                <div style="font-size: 0.82rem; color: #475569; margin-top: 2px;">
                                    <i class="ri-phone-line" style="color: #94a3b8;"></i> <?php echo htmlspecialchars($o->customer_phone); ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($o->username)): ?>
                                <span style="display: inline-block; margin-top: 6px; background: #e0f2fe; color: #0369a1; font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 4px;">
                                    User ID: #<?php echo $o->user_id; ?> (<?php echo htmlspecialchars($o->username); ?>)
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Items & Total -->
                        <td style="padding: 16px; vertical-align: top;">
                            <div style="margin-bottom: 8px;">
                                <?php if(!empty($o->items)): ?>
                                    <?php foreach($o->items as $item): ?>
                                        <div style="font-size: 0.85rem; color: #334155; margin-bottom: 3px;">
                                            • <strong><?php echo htmlspecialchars($item->item_name); ?></strong>
                                            <span style="font-size: 0.78rem; color: #64748b;">(<?php echo ucfirst($item->item_type); ?> x<?php echo $item->quantity; ?>)</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div style="font-weight: 800; color: #dc2626; font-size: 1.05rem;">
                                ₹<?php echo number_format($o->total_amount, 2); ?>
                            </div>
                        </td>

                        <!-- Transaction ID -->
                        <td style="padding: 16px; vertical-align: top;">
                            <?php if(!empty($o->transaction_id)): ?>
                                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 6px; font-family: monospace; font-weight: 700; color: #1e293b; font-size: 0.9rem; display: inline-block;">
                                    <?php echo htmlspecialchars($o->transaction_id); ?>
                                </div>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Not Provided</span>
                            <?php endif; ?>
                        </td>

                        <!-- Proof of Payment (Screenshot) -->
                        <td style="padding: 16px; vertical-align: top;">
                            <?php if(!empty($o->payment_proof) && file_exists(FCPATH . $o->payment_proof)): ?>
                                <div style="position: relative; display: inline-block;">
                                    <button type="button" 
                                            onclick="openScreenshotModal('<?php echo base_url($o->payment_proof); ?>', '<?php echo htmlspecialchars($o->order_number); ?>', '<?php echo htmlspecialchars($o->transaction_id); ?>')" 
                                            style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="ri-image-line" style="font-size: 1rem;"></i> View Proof
                                    </button>
                                </div>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">No Screenshot</span>
                            <?php endif; ?>
                        </td>

                        <!-- Status Badge -->
                        <td style="padding: 16px; vertical-align: top;">
                            <?php if($o->payment_status === 'pending'): ?>
                                <span style="background: #fef3c7; color: #d97706; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                    Pending Review
                                </span>
                            <?php elseif($o->payment_status === 'completed'): ?>
                                <span style="background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                    Approved
                                </span>
                            <?php else: ?>
                                <span style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; text-transform: uppercase;">
                                    Rejected
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Actions (Approve / Reject) -->
                        <td style="padding: 16px; vertical-align: top; text-align: center;">
                            <?php if($o->payment_status === 'pending'): ?>
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="<?php echo site_url('admin/approve_order/' . $o->id); ?>" 
                                       onclick="return confirm('Approve order <?php echo htmlspecialchars($o->order_number); ?> and unlock assessment access for the student?');" 
                                       style="background: #10b981; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 6px rgba(16,185,129,0.3);">
                                        <i class="ri-check-line" style="font-size: 1rem;"></i> Approve
                                    </a>

                                    <a href="<?php echo site_url('admin/reject_order/' . $o->id); ?>" 
                                       onclick="return confirm('Reject order <?php echo htmlspecialchars($o->order_number); ?>?');" 
                                       style="background: #ef4444; color: white; border: none; padding: 8px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="ri-close-line" style="font-size: 1rem;"></i> Reject
                                    </a>
                                </div>
                            <?php elseif($o->payment_status === 'completed'): ?>
                                <span style="color: #059669; font-size: 0.82rem; font-weight: 600;">
                                    <i class="ri-checkbox-circle-fill"></i> Completed
                                </span>
                            <?php else: ?>
                                <span style="color: #dc2626; font-size: 0.82rem; font-weight: 600;">
                                    <i class="ri-close-circle-fill"></i> Rejected
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Payment Proof Image Modal -->
<div id="screenshotModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
    <div style="background: #fff; border-radius: 12px; max-width: 650px; width: 100%; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); animation: modalFadeIn 0.2s ease-out;">
        
        <div style="padding: 16px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.1rem; color: #0f172a;" id="modalOrderNum">Payment Screenshot Proof</h3>
                <div style="font-size: 0.8rem; color: #64748b;" id="modalTxnId">Transaction ID</div>
            </div>
            <button type="button" onclick="closeScreenshotModal()" style="background: #f1f5f9; border: none; font-size: 1.3rem; color: #475569; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <div style="padding: 20px; overflow-y: auto; text-align: center; background: #0f172a;">
            <img id="modalImage" src="" alt="Payment Screenshot" style="max-width: 100%; max-height: 70vh; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        </div>

        <div style="padding: 12px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0; text-align: right;">
            <a id="modalFullLink" href="#" target="_blank" style="color: #2563eb; font-weight: 600; text-decoration: none; font-size: 0.88rem;">
                Open Full Resolution <i class="ri-external-link-line"></i>
            </a>
        </div>
    </div>
</div>

<script>
function openScreenshotModal(imgUrl, orderNum, txnId) {
    document.getElementById('modalImage').src = imgUrl;
    document.getElementById('modalFullLink').href = imgUrl;
    document.getElementById('modalOrderNum').innerText = 'Payment Proof - Order #' + orderNum;
    document.getElementById('modalTxnId').innerText = 'Transaction UTR/Ref ID: ' + (txnId ? txnId : 'N/A');
    document.getElementById('screenshotModal').style.display = 'flex';
}

function closeScreenshotModal() {
    document.getElementById('screenshotModal').style.display = 'none';
}

// Close modal on click outside
document.getElementById('screenshotModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeScreenshotModal();
    }
});
</script>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
</style>
