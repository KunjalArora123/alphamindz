<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion - <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #eef2f6;
            color: #1e293b;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .no-print-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            gap: 12px;
        }

        .btn-action {
            background-color: rgb(48, 98, 135);
            color: #ffffff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(48, 98, 135, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            background-color: #244864;
            transform: translateY(-2px);
        }

        .btn-accent {
            background-color: rgb(131, 187, 77);
            box-shadow: 0 4px 12px rgba(131, 187, 77, 0.3);
        }

        .btn-accent:hover {
            background-color: #6da23a;
        }

        /* A4 Landscape Dimensions ratio container */
        .certificate-container {
            width: 1000px;
            height: 700px;
            background: #ffffff;
            position: relative;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Elegant Double Border Frame */
        .cert-outer-border {
            width: 100%;
            height: 100%;
            border: 4px solid rgb(48, 98, 135);
            padding: 10px;
            position: relative;
            box-sizing: border-box;
        }

        .cert-inner-border {
            width: 100%;
            height: 100%;
            border: 2px dashed rgb(131, 187, 77);
            padding: 30px 40px;
            position: relative;
            box-sizing: border-box;
            background: radial-gradient(circle at center, #ffffff 60%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cert-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cert-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cert-logo img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        .cert-logo-text {
            font-size: 24px;
            font-weight: 800;
            color: rgb(48, 98, 135);
            letter-spacing: -0.5px;
        }

        .cert-logo-text span {
            color: rgb(131, 187, 77);
        }

        .cert-badge {
            background: linear-gradient(135deg, rgb(48, 98, 135), rgb(34, 72, 100));
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .cert-body {
            text-align: center;
            margin: 10px 0;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 32px;
            font-weight: 900;
            color: rgb(48, 98, 135);
            letter-spacing: 4px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .cert-recipient-label {
            font-size: 13px;
            color: #64748b;
            font-style: italic;
        }

        .cert-name {
            font-family: 'Cinzel', serif;
            font-size: 36px;
            font-weight: 700;
            color: #0f172a;
            margin: 8px 0 15px 0;
            border-bottom: 2px solid rgb(131, 187, 77);
            display: inline-block;
            padding-bottom: 4px;
            min-width: 400px;
        }

        .cert-course-desc {
            font-size: 15px;
            color: #334155;
            max-width: 750px;
            margin: 0 auto 12px auto;
            line-height: 1.5;
        }

        .cert-course-title {
            font-size: 22px;
            font-weight: 800;
            color: rgb(48, 98, 135);
            margin-bottom: 6px;
        }

        .tier-badge {
            display: inline-block;
            background-color: rgba(131, 187, 77, 0.15);
            color: #4d7a22;
            border: 1px solid rgb(131, 187, 77);
            font-weight: 700;
            font-size: 13px;
            padding: 3px 12px;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .cert-qr-box {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #ffffff;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .cert-qr-box img {
            width: 75px;
            height: 75px;
        }

        .qr-info {
            text-align: left;
        }

        .qr-info .cred-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
        }

        .qr-info .cred-id {
            font-family: monospace;
            font-size: 13px;
            font-weight: 700;
            color: rgb(48, 98, 135);
        }

        .qr-info .verify-tag {
            font-size: 11px;
            color: rgb(131, 187, 77);
            font-weight: 700;
            margin-top: 2px;
        }

        .qr-info .cin-tag {
            font-size: 11px;
            color: rgb(48, 98, 135);
            font-weight: 700;
            margin-top: 3px;
        }

        .cert-meta {
            text-align: center;
        }

        .cert-meta .meta-date {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .cert-meta .meta-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }

        .cert-signatures {
            display: flex;
            gap: 40px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-line {
            width: 140px;
            border-top: 1.5px solid #94a3b8;
            margin-top: 35px;
            margin-bottom: 4px;
        }

        .sig-title {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .certificate-container {
                box-shadow: none;
                width: 100vw;
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <a href="<?php echo site_url('student'); ?>" class="btn-action">
            <i class="ri-arrow-left-line"></i> Dashboard
        </a>
        <button onclick="window.print()" class="btn-action btn-accent">
            <i class="ri-printer-line"></i> Print / Download PDF
        </button>
    </div>

    <div class="certificate-container">
        <div class="cert-outer-border">
            <div class="cert-inner-border">
                
                <!-- Top Header -->
                <div class="cert-header">
                    <div class="cert-logo">
                        <img src="<?php echo base_url('assets/images/logo.png?v='.time()); ?>" alt="AlphaMindz Logo" onerror="this.style.display='none'; document.getElementById('alt-logo-text').style.display='block';">
                        <div id="alt-logo-text" class="cert-logo-text">Alpha<span>Mindz</span></div>
                    </div>
                    <div class="cert-badge">
                        Verified Credential
                    </div>
                </div>

                <!-- Main Body -->
                <div class="cert-body">
                    <h1 class="cert-title">Certificate of Completion</h1>
                    <div class="cert-subtitle">This is proudly presented to</div>

                    <div class="cert-recipient-label">Learner</div>
                    <div class="cert-name"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></div>

                    <p class="cert-course-desc">
                        for successfully completing the comprehensive learning modules and assessment criteria for
                    </p>

                    <div class="cert-course-title"><?php echo htmlspecialchars($course->title); ?></div>
                    <div style="margin-top: 8px;">
                        <span class="tier-badge"><?php echo htmlspecialchars(ucfirst($certificate->tier_key)); ?> Tier Mastery</span>
                    </div>
                </div>

                <!-- Bottom Footer with QR and Issue Date -->
                <div class="cert-footer">
                    <!-- QR Verification -->
                    <div class="cert-qr-box">
                        <img src="<?php echo $qr_url; ?>" alt="Verification QR Code">
                        <div class="qr-info">
                            <div class="cred-label">Credential ID</div>
                            <div class="cred-id"><?php echo htmlspecialchars($certificate->credential_id); ?></div>
                            <div class="verify-tag"><i class="ri-shield-check-fill"></i> Scan to Verify Authenticity</div>
                            <div class="cin-tag">CIN No. : U80903GA2008PTC005856</div>
                        </div>
                    </div>

                    <!-- Issue Date -->
                    <div class="cert-meta">
                        <div class="meta-date"><?php echo date('F d, Y', strtotime($certificate->issue_date)); ?></div>
                        <div class="meta-label">Date of Issuance</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
