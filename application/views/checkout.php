<div class="checkout-header-banner" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); padding: 40px 20px; text-align: center; color: #fff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 10px; color: #ffffff;">Payment & Checkout</h1>
        <p style="color: #e0e6ed; font-size: 0.95rem;">Complete your details and scan the payment QR code to complete your order.</p>
    </div>
</div>

<div class="container" style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <?php if($this->session->flashdata('error')): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #f5c6cb;">
            <i class="ri-error-warning-line"></i> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo site_url('cart/process_checkout'); ?>" method="POST" enctype="multipart/form-data" id="checkoutForm">
        <div class="checkout-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 35px; align-items: start;">
            
            <!-- Main Checkout Column -->
            <div>
                <!-- STEP 1: Customer Contact Details -->
                <div class="checkout-form-section" id="step1_contact" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;">
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f3f5; padding-bottom: 15px; margin-bottom: 25px;">
                        <h3 style="font-family: 'Playfair Display', serif; margin: 0; color: #2c3e50; font-size: 1.3rem; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #2a5298; color: #fff; border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem; font-family: sans-serif;">1</span>
                            Contact Information
                        </h3>
                        <?php if($user_logged_in): ?>
                            <span style="background: #e8f8f5; color: #27ae60; font-size: 0.8rem; font-weight: 600; padding: 4px 10px; border-radius: 4px;">
                                <i class="ri-user-check-line"></i> Logged in as <?php echo htmlspecialchars($user_name); ?>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo site_url('auth/login'); ?>" style="color: #3498db; font-size: 0.85rem; font-weight: 600; text-decoration: none;">
                                Already registered? Log in
                            </a>
                        <?php endif; ?>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; color: #34495e; margin-bottom: 6px; font-size: 0.9rem;">Full Name *</label>
                        <input type="text" name="customer_name" id="customer_name" required value="<?php echo isset($user_info) ? htmlspecialchars($user_info->first_name . ' ' . $user_info->last_name) : (isset($user_name) ? htmlspecialchars($user_name) : ''); ?>" placeholder="Enter your full name" style="width: 100%; padding: 12px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; color: #34495e; margin-bottom: 6px; font-size: 0.9rem;">Email Address *</label>
                        <input type="email" name="customer_email" id="customer_email" required value="<?php echo isset($user_info) ? htmlspecialchars($user_info->email) : (isset($user_email) ? htmlspecialchars($user_email) : ''); ?>" placeholder="Enter your email" style="width: 100%; padding: 12px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                        <?php if(!$user_logged_in): ?>
                            <span style="font-size: 0.8rem; color: #7f8c8d; margin-top: 4px; display: block;">An account will be automatically setup for your email to manage your purchased tests & downloads.</span>
                        <?php endif; ?>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 600; color: #34495e; margin-bottom: 6px; font-size: 0.9rem;">Phone Number</label>
                        <input type="tel" name="customer_phone" id="customer_phone" placeholder="+91 98765 43210" style="width: 100%; padding: 12px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                    </div>

                    <div id="proceed_btn_container">
                        <button type="button" id="btnProceedToPayment" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border: none; padding: 14px 28px; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; transition: all 0.3s ease;">
                            Proceed to Checkout <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>

                </div>

                <!-- STEP 2: Payment QR & Verification (Shown on clicking Proceed to Checkout) -->
                <div class="checkout-form-section" id="step2_payment" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: none;">
                    
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f3f5; padding-bottom: 15px; margin-bottom: 25px;">
                        <h3 style="font-family: 'Playfair Display', serif; margin: 0; color: #2c3e50; font-size: 1.3rem; display: flex; align-items: center; gap: 10px;">
                            <span style="background: #27ae60; color: #fff; border-radius: 50%; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem; font-family: sans-serif;">2</span>
                            Scan QR Code & Submit Payment Proof
                        </h3>
                        <button type="button" id="btnEditContact" style="background: none; border: none; color: #3498db; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                            <i class="ri-edit-line"></i> Edit Contact Info
                        </button>
                    </div>

                    <!-- Payment QR Code Container -->
                    <div style="background: #f8f9fa; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 25px; text-align: center; margin-bottom: 25px;">
                        <div style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700; margin-bottom: 8px;">Scan QR Code to Pay</div>
                        
                        <!-- Display QR Code image -->
                        <div style="background: #ffffff; padding: 15px; border-radius: 10px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin: 10px 0;">
                            <img src="<?php echo base_url('assets/images/payment_qr.png'); ?>" alt="Payment QR Code" style="width: 220px; height: 220px; object-fit: contain; display: block;">
                        </div>

                        <div style="margin-top: 15px;">
                            <span style="font-size: 0.9rem; color: #475569;">Amount to be paid:</span>
                            <div style="font-size: 2rem; font-weight: 800; color: #1e293b; margin-top: 2px;">
                                ₹<?php echo number_format($total, 2); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px; font-size: 0.85rem; color: #64748b;">
                            <i class="ri-phone-find-line"></i> Open Google Pay, PhonePe, Paytm or any UPI App to scan
                        </div>
                    </div>

                    <!-- Upload Proof & Transaction ID Input -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 25px;">
                        <h4 style="margin: 0 0 15px 0; color: #1e293b; font-size: 1.05rem;"><i class="ri-shield-keyhole-line" style="color: #27ae60;"></i> Payment Confirmation Details</h4>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 600; color: #34495e; margin-bottom: 6px; font-size: 0.9rem;">
                                Transaction ID / UTR Number *
                            </label>
                            <input type="text" name="transaction_id" id="transaction_id" placeholder="e.g. 123456789012 or UPI Reference UTR" style="width: 100%; padding: 12px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                            <span style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px; display: block;">Enter the 12-digit UPI Ref/UTR No. or Transaction Reference from your payment receipt app.</span>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: 600; color: #34495e; margin-bottom: 6px; font-size: 0.9rem;">
                                Upload Payment Screenshot *
                            </label>
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #94a3b8; border-radius: 6px; font-size: 0.9rem; background: #f8fafc; cursor: pointer; box-sizing: border-box;">
                            <span style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px; display: block;">Upload screenshot of completed payment confirmation screen (JPG, PNG, WEBP).</span>
                        </div>

                        <!-- Screenshot Image Preview Container -->
                        <div id="screenshot_preview_container" style="display: none; margin-top: 15px; text-align: center; background: #f1f5f9; padding: 12px; border-radius: 8px;">
                            <span style="font-size: 0.8rem; color: #475569; display: block; margin-bottom: 6px; font-weight: 600;">Uploaded Screenshot Preview:</span>
                            <img id="screenshot_preview" src="#" alt="Screenshot Preview" style="max-width: 100%; max-height: 200px; border-radius: 6px; border: 1px solid #cbd5e1; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <button type="submit" id="btnSubmitOrder" style="background: #27ae60; color: white; border: none; width: 100%; padding: 16px; border-radius: 8px; font-size: 1.1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3); transition: all 0.3s ease;">
                        <i class="ri-check-double-line" style="font-size: 1.3rem;"></i> Submit Payment Proof & Complete Order
                    </button>

                </div>
            </div>

            <!-- Order Items Sidebar -->
            <div class="checkout-summary-section" style="background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 20px;">
                <h3 style="font-family: 'Playfair Display', serif; margin: 0 0 20px 0; color: #2c3e50; font-size: 1.3rem; border-bottom: 1px solid #f1f3f5; padding-bottom: 12px;">Order Summary</h3>
                
                <div class="checkout-items-list" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                    <?php foreach($cart as $item): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem;">
                            <div style="flex: 1; padding-right: 10px;">
                                <div style="font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($item['title']); ?></div>
                                <span style="font-size: 0.75rem; color: #95a5a6;">
                                    <?php echo ucfirst($item['type']); ?> x <?php echo $item['qty']; ?>
                                </span>
                            </div>
                            <div style="font-weight: 600; color: #2c3e50;">
                                ₹<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr style="border: none; border-top: 1px solid #e9ecef; margin: 15px 0;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <span style="font-weight: 700; color: #2c3e50; font-size: 1.1rem;">Total Amount</span>
                    <div style="text-align: right;">
                        <span style="font-weight: 800; color: #e74c3c; font-size: 1.4rem;">₹<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; font-size: 0.82rem; color: #64748b; line-height: 1.5;">
                    <i class="ri-information-line" style="color: #2563eb;"></i> 
                    After submitting your payment proof, our team will review the transaction and grant immediate access.
                </div>

                <div style="text-align: center; margin-top: 15px; font-size: 0.8rem; color: #95a5a6;">
                    <i class="ri-lock-line"></i> 256-bit SSL Encrypted Checkout
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnProceed = document.getElementById('btnProceedToPayment');
    const btnEditContact = document.getElementById('btnEditContact');
    const step1 = document.getElementById('step1_contact');
    const step2 = document.getElementById('step2_payment');
    const nameInput = document.getElementById('customer_name');
    const emailInput = document.getElementById('customer_email');
    const txnIdInput = document.getElementById('transaction_id');
    const proofInput = document.getElementById('payment_proof');
    const checkoutForm = document.getElementById('checkoutForm');
    const previewContainer = document.getElementById('screenshot_preview_container');
    const previewImg = document.getElementById('screenshot_preview');

    btnProceed.addEventListener('click', function() {
        if (!nameInput.value.trim() || !emailInput.value.trim()) {
            alert('Please fill in your Full Name and Email Address to proceed.');
            if(!nameInput.value.trim()) nameInput.focus();
            else emailInput.focus();
            return;
        }

        // Hide Step 1 button container & show Step 2
        document.getElementById('proceed_btn_container').style.display = 'none';
        step2.style.display = 'block';
        
        // Disable contact inputs to lock step 1 unless edit is clicked
        nameInput.readOnly = true;
        emailInput.readOnly = true;
        document.getElementById('customer_phone').readOnly = true;

        // Smooth scroll to Step 2
        step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Set required on step 2 inputs
        txnIdInput.setAttribute('required', 'required');
        proofInput.setAttribute('required', 'required');
    });

    btnEditContact.addEventListener('click', function() {
        step2.style.display = 'none';
        document.getElementById('proceed_btn_container').style.display = 'block';
        nameInput.readOnly = false;
        emailInput.readOnly = false;
        document.getElementById('customer_phone').readOnly = false;
        nameInput.focus();
    });

    // Preview uploaded screenshot image
    proofInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Form submit validation
    checkoutForm.addEventListener('submit', function(e) {
        if (step2.style.display === 'none') {
            e.preventDefault();
            btnProceed.click();
            return false;
        }
        if (!txnIdInput.value.trim()) {
            e.preventDefault();
            alert('Please enter the Transaction ID / UTR Number.');
            txnIdInput.focus();
            return false;
        }
        if (!proofInput.files || proofInput.files.length === 0) {
            e.preventDefault();
            alert('Please select and upload the payment screenshot proof.');
            proofInput.focus();
            return false;
        }
    });
});
</script>

<style>
@media (max-width: 850px) {
    .checkout-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
