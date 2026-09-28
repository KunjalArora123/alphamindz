<div class="container" style="max-width: 800px; margin: 50px auto; padding: 0 20px;">
    
    <div style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 40px 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        
        <?php if($order->payment_status === 'pending'): ?>
            <!-- PENDING APPROVAL BADGE & BANNER -->
            <div style="width: 80px; height: 80px; background: #fff8e1; color: #f39c12; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 20px auto;">
                <i class="ri-time-line"></i>
            </div>

            <h1 style="font-family: 'Playfair Display', serif; color: #2c3e50; font-size: 2.2rem; margin-bottom: 10px;">Payment Proof Submitted!</h1>
            <p style="color: #6c757d; font-size: 1rem; margin-bottom: 25px;">Your order reference is <strong style="color: #2c3e50; font-family: monospace; font-size: 1.1rem; background: #f8f9fa; padding: 2px 8px; border-radius: 4px; border: 1px solid #dee2e6;"><?php echo htmlspecialchars($order->order_number); ?></strong></p>

            <div style="background: #fffdf5; border: 1px solid #ffe082; border-radius: 10px; padding: 20px; margin: 25px 0; text-align: left;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                    <i class="ri-shield-user-line" style="font-size: 1.8rem; color: #d35400;"></i>
                    <h3 style="margin: 0; color: #d35400; font-size: 1.2rem; font-family: 'Playfair Display', serif;">Pending Admin Verification</h3>
                </div>
                <p style="color: #5d4037; font-size: 0.95rem; margin-bottom: 10px;">
                    We have received your payment screenshot proof and Transaction UTR / Ref ID: <strong style="font-family: monospace; color: #2c3e50; font-size: 1.05rem;"><?php echo htmlspecialchars($order->transaction_id ? $order->transaction_id : 'Submitted'); ?></strong>.
                </p>
                <p style="color: #795548; font-size: 0.88rem; margin: 0;">
                    Our team will verify your transaction against our records. Once approved by the administrator, access to your purchased assessments will be automatically activated in your account.
                </p>
            </div>
        <?php else: ?>
            <!-- COMPLETED / APPROVED BADGE -->
            <div style="width: 80px; height: 80px; background: #e8f8f5; color: #2ecc71; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 20px auto;">
                <i class="ri-checkbox-circle-fill"></i>
            </div>

            <h1 style="font-family: 'Playfair Display', serif; color: #2c3e50; font-size: 2.2rem; margin-bottom: 10px;">Order Completed & Approved!</h1>
            <p style="color: #6c757d; font-size: 1rem; margin-bottom: 25px;">Thank you for your purchase. Your order reference number is <strong style="color: #2c3e50; font-family: monospace; font-size: 1.1rem; background: #f8f9fa; padding: 2px 8px; border-radius: 4px; border: 1px solid #dee2e6;"><?php echo htmlspecialchars($order->order_number); ?></strong></p>

            <?php if(!empty($assessments_purchased)): ?>
                <div style="background: linear-gradient(135deg, #ebf5fb, #e8f8f5); border: 1px solid #aed6f1; border-radius: 10px; padding: 25px; margin: 30px 0; text-align: left;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i class="ri-key-2-fill" style="font-size: 1.8rem; color: #2980b9;"></i>
                        <h3 style="margin: 0; color: #1b4f72; font-size: 1.25rem; font-family: 'Playfair Display', serif;">Assessment Access Unlocked!</h3>
                    </div>
                    <p style="color: #34495e; font-size: 0.95rem; margin-bottom: 15px;">Your account has been granted authorization for the following assessment test(s):</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                        <?php foreach($assessments_purchased as $ap): ?>
                            <div style="background: #fff; padding: 12px 18px; border-radius: 6px; border: 1px solid #d4e6f1; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <span style="font-weight: 600; color: #2c3e50;"><i class="ri-file-list-3-line" style="color: #3498db;"></i> <?php echo htmlspecialchars($ap->item_name); ?></span>
                                <a href="<?php echo site_url('assessments/take_test?test=' . urlencode($ap->item_name)); ?>" class="btn-primary" style="padding: 8px 18px; font-size: 0.85rem; background: #27ae60; border-color: #27ae60; text-decoration: none;">
                                    Take Test Now <i class="ri-play-fill"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Order Summary Breakdown -->
        <div style="border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; margin-bottom: 30px; text-align: left;">
            <div style="padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #e9ecef; font-weight: 600; color: #2c3e50; display: flex; justify-content: space-between;">
                <span>Order Summary</span>
                <span>Status: <strong style="text-transform: uppercase; color: <?php echo $order->payment_status === 'completed' ? '#27ae60' : ($order->payment_status === 'rejected' ? '#e74c3c' : '#f39c12'); ?>;"><?php echo htmlspecialchars($order->payment_status); ?></strong></span>
            </div>
            <div style="padding: 20px;">
                <?php foreach($items as $item): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 0.95rem;">
                        <div>
                            <strong style="color: #2c3e50;"><?php echo htmlspecialchars($item->item_name); ?></strong>
                            <span style="color: #95a5a6; font-size: 0.8rem; margin-left: 6px;">(<?php echo ucfirst($item->item_type); ?> x <?php echo $item->quantity; ?>)</span>
                        </div>
                        <div style="font-weight: 600; color: #2c3e50;">
                            ₹<?php echo number_format($item->subtotal, 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr style="border: none; border-top: 1px solid #f1f3f5; margin: 15px 0;">
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; color: #2c3e50;">
                    <span>Total Amount</span>
                    <span style="color: #e74c3c;">₹<?php echo number_format($order->total_amount, 2); ?></span>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo site_url('student'); ?>" class="btn-primary" style="padding: 12px 25px; text-decoration: none;">
                <i class="ri-user-3-line"></i> Go to Student Dashboard
            </a>
            <a href="<?php echo site_url('shop'); ?>" class="btn-primary" style="background: #34495e; padding: 12px 25px; text-decoration: none;">
                <i class="ri-store-2-line"></i> Back to Shop
            </a>
        </div>

    </div>

</div>
