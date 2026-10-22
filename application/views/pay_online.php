<?php $this->load->view('public_header', ['title' => 'Pay Online | Scan & Pay | AlphaMindz']); ?>

<!-- Page Header -->
<section style="padding: 60px 20px 40px; text-align: center; background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.08) 0%, transparent 75%); border-bottom: 1px solid var(--border);">
    <div class="section-container" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
        <span class="hero-label" style="margin-bottom: 16px; font-size: 12px; padding: 6px 16px;">
            <i class="ri-secure-payment-line" style="color: #2563eb;"></i> Secure Payments
        </span>
        <h1 class="hero-title" style="font-size: 44px; margin-bottom: 16px;">
            Pay Online & <span class="text-pink">Instant UPI</span>
        </h1>
        <p class="hero-subtitle" style="font-size: 16px; margin: 0 auto; color: var(--text-muted); max-width: 600px;">
            Scan our official AlphaMindz QR code using any UPI app to pay for courses, study materials, or assessments instantly.
        </p>
    </div>
</section>

<!-- Payment QR Content Section -->
<section style="padding: 60px 20px 90px; background: var(--bg-main);">
    <div class="section-container" style="max-width: 950px; margin: 0 auto;">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 35px; align-items: center;">
            
            <!-- QR Code Presentation Card -->
            <div style="background: #ffffff; border-radius: 20px; padding: 36px 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; text-align: center; position: relative;">
                
                <div style="display: inline-flex; align-items: center; gap: 8px; background: #eff6ff; color: #2563eb; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px;">
                    <i class="ri-qr-code-line"></i> Official AlphaMindz QR
                </div>

                <div style="background: #ffffff; border: 3px dashed #cbd5e1; border-radius: 16px; padding: 18px; display: inline-block; box-shadow: 0 8px 24px rgba(0,0,0,0.05); margin-bottom: 20px; max-width: 270px; width: 100%;">
                    <img src="<?php echo base_url('assets/images/payment_qr.png'); ?>" alt="AlphaMindz Payment QR Code" style="width: 100%; height: auto; display: block; border-radius: 8px;">
                </div>

                <h3 style="margin: 0 0 6px 0; font-size: 18px; color: #0f172a; font-weight: 700;">
                    Scan to Pay with Any UPI App
                </h3>
                <p style="margin: 0 0 20px 0; color: #64748b; font-size: 13px;">
                    Google Pay • PhonePe • Paytm • BHIM • Any Banking App
                </p>

                <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php echo base_url('assets/images/payment_qr.png'); ?>" download="AlphaMindz-Payment-QR.png" class="btn" style="background-color: #2563eb; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                        <i class="ri-download-2-line"></i> Save QR Code
                    </a>
                </div>
            </div>

            <!-- Instructions & Payment Support Details -->
            <div>
                <div style="margin-bottom: 25px;">
                    <span style="background: rgba(39, 174, 96, 0.1); color: #27ae60; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 12px;">
                        Quick & Hassle-Free
                    </span>
                    <h2 style="font-family: var(--font-heading); font-size: 28px; color: #0f172a; margin: 0 0 12px 0;">
                        How to complete your payment
                    </h2>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0;">
                        Follow these simple steps to finalize your course enrolment, aptitude assessment registration, or study material order.
                    </p>
                </div>

                <!-- Steps List -->
                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 30px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start; background: #fff; padding: 16px 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</div>
                        <div>
                            <strong style="color: #0f172a; font-size: 14px; display: block; margin-bottom: 2px;">Open Any UPI Application</strong>
                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">Launch Google Pay, PhonePe, Paytm, BHIM, or your bank's UPI app on your smartphone.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 14px; align-items: flex-start; background: #fff; padding: 16px 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</div>
                        <div>
                            <strong style="color: #0f172a; font-size: 14px; display: block; margin-bottom: 2px;">Scan the QR & Enter Amount</strong>
                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">Scan the QR code, enter the specified fees or order total, and complete the PIN authorization.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 14px; align-items: flex-start; background: #fff; padding: 16px 18px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</div>
                        <div>
                            <strong style="color: #0f172a; font-size: 14px; display: block; margin-bottom: 2px;">Share the Screenshot with Our Team</strong>
                            <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">Send your payment confirmation or transaction ID via WhatsApp/Email for instant verification.</p>
                        </div>
                    </div>
                </div>

                <!-- Support Card -->
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; border-radius: 14px; padding: 22px 24px; display: flex; flex-direction: column; gap: 12px;">
                    <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #38bdf8; letter-spacing: 0.5px;">
                        <i class="ri-customer-service-2-line"></i> Payment Assistance & Helpdesk
                    </div>
                    <p style="margin: 0; color: #cbd5e1; font-size: 13px; line-height: 1.5;">
                        Need confirmation or help regarding fees? Reach our advisors directly:
                    </p>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 13px; margin-top: 4px;">
                        <span style="color: #fff;"><i class="ri-phone-fill" style="color: #2ecc71;"></i> <strong>Solan:</strong> 8308770200</span>
                        <span style="color: #fff;"><i class="ri-phone-fill" style="color: #2ecc71;"></i> <strong>Goa:</strong> 7447720000</span>
                        <span style="color: #fff;"><i class="ri-mail-fill" style="color: #38bdf8;"></i> info.alphamindz@gmail.com</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<?php $this->load->view('public_footer'); ?>
