<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Report Card - <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?> - AlphaMindz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 30px 15px;
            min-height: 100vh;
        }

        /* Top Action Bar (Visible in browser, hidden in print & PDF export) */
        .action-toolbar {
            max-width: 960px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .action-toolbar .title-info {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-group-actions {
            display: flex;
            gap: 10px;
        }

        .btn-report {
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-primary-pdf {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }
        .btn-primary-pdf:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-print {
            background-color: #0f172a;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1e293b;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        /* Report Sheet Container - A4 standard aspect */
        .report-page {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 36px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* Decorative Header Banner */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-box img {
            max-height: 60px;
            max-width: 220px;
            object-fit: contain;
        }

        .org-details {
            text-align: right;
        }

        .org-details h2 {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .org-details p {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-ref-badge {
            display: inline-block;
            margin-top: 6px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            font-family: monospace;
        }

        /* Student Identity Profile Card (Prominent Name on Top) */
        .student-profile-banner {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border: 1px solid #cbd5e1;
            border-left: 6px solid #2563eb;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .student-top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 15px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .student-top-row .name-block {
            flex: 1;
        }

        .student-top-row .subtitle-tag {
            font-size: 11px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .student-top-row h1 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .verified-stamp-badge {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .student-meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .meta-item .meta-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .meta-item .meta-val {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Executive Score Overview Cards */
        .score-metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .metric-card.score::before { background: #2563eb; }
        .metric-card.pct::before { background: #16a34a; }
        .metric-card.grade::before { background: #8b5cf6; }
        .metric-card.mbti::before { background: #f59e0b; }

        .metric-card .card-label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .metric-card .card-value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .metric-card .card-subtext {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-top: 4px;
        }

        /* Section Headings */
        .section-heading-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-heading-bar i {
            color: #2563eb;
            font-size: 18px;
        }

        .section-heading-bar h3 {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        /* Performance Graph Section */
        .graph-section-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .graph-layout {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .chart-canvas-box {
            flex: 1 1 55%;
            height: 230px;
            position: relative;
        }

        .css-bars-box {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bar-row {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .bar-label-group {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
        }

        .bar-track {
            height: 8px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .bar-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        /* Section-Wise Table */
        .score-table-container {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .score-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .score-table thead tr {
            background-color: #f8fafc;
            border-bottom: 2px solid #cbd5e1;
        }

        .score-table th {
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .score-table td {
            padding: 10px 14px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
        }

        .score-table tbody tr:last-child td {
            border-bottom: none;
        }

        .score-table tfoot tr {
            background-color: #f8fafc;
            border-top: 2px solid #cbd5e1;
            font-weight: 800;
        }

        .score-table tfoot td {
            padding: 12px 14px;
            font-size: 13px;
            color: #0f172a;
        }

        .level-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        /* MBTI Personality Profiling Card */
        .mbti-card-box {
            background: linear-gradient(135deg, #fdf4ff 0%, #f5f3ff 100%);
            border: 1px solid #e9d5ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .mbti-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            border-bottom: 1px solid #e9d5ff;
            padding-bottom: 12px;
        }

        .mbti-badge-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mbti-code-pill {
            background: #7c3aed;
            color: #ffffff;
            font-size: 18px;
            font-weight: 800;
            padding: 4px 14px;
            border-radius: 8px;
            letter-spacing: 2px;
        }

        .mbti-title-text {
            font-size: 15px;
            font-weight: 800;
            color: #4c1d95;
        }

        .mbti-desc-text {
            font-size: 12px;
            line-height: 1.6;
            color: #5b21b6;
            margin-bottom: 16px;
        }

        .dichotomy-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 20px;
        }

        .dichotomy-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dichotomy-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 700;
            color: #6b21a8;
        }

        .dichotomy-track {
            height: 8px;
            background: #ddd6fe;
            border-radius: 999px;
            overflow: hidden;
            display: flex;
        }

        .dichotomy-left {
            background: #7c3aed;
            height: 100%;
        }

        .dichotomy-right {
            background: #c084fc;
            height: 100%;
        }

        /* Recommendations & Strengths */
        .recommendations-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 24px;
        }

        .rec-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .rec-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .rec-item h4 {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rec-item p {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        /* Official Authentication & Footer */
        .report-auth-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
        }

        .seal-box {
            display: flex;
            align-items: center;
        }

        .official-seal-badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px dashed #1e3a8a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 4px;
            color: #1e3a8a;
        }

        .official-seal-badge i {
            font-size: 20px;
        }

        .official-seal-badge .seal-text {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }

        .confidentiality-notice {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }

        /* PRINT STYLES - Exact A4 output */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000000 !important;
            }

            .action-toolbar {
                display: none !important;
            }

            .report-page {
                box-shadow: none !important;
                border: none !important;
                padding: 10mm 12mm !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            .page-break-before {
                page-break-before: always;
            }

            .no-page-break {
                page-break-inside: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 10mm 12mm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (Hidden during Print and in exported PDF) -->
    <div class="action-toolbar" id="toolbar">
        <div class="title-info">
            <i class="ri-file-chart-line" style="color: #2563eb; font-size: 18px;"></i>
            <span>Student Official Diagnostic Report Card</span>
            <span style="color: #cbd5e1;">|</span>
            <span style="color: #0f172a; font-weight: 700;"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></span>
        </div>
        <div class="btn-group-actions">
            <a href="<?php echo site_url('admin/user_details/' . $user->id); ?>" class="btn-report btn-back">
                <i class="ri-arrow-left-line"></i> Back to User
            </a>
            <button onclick="window.print();" class="btn-report btn-print">
                <i class="ri-printer-line"></i> Print
            </button>
            <button onclick="generatePdf();" class="btn-report btn-primary-pdf" id="downloadPdfBtn">
                <i class="ri-download-2-line"></i> Download PDF
            </button>
        </div>
    </div>

    <!-- Main Report Page Document -->
    <div class="report-page" id="reportContent">

        <!-- Header: Official Logo and Academy Details -->
        <header class="report-header">
            <div class="logo-box">
                <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz Logo" onerror="this.style.display='none'; document.getElementById('alt-brand-title').style.display='block';">
                <div id="alt-brand-title" style="display: none;">
                    <h2 style="font-family: 'Cinzel', serif; font-size: 24px; color: #1e3a8a; margin: 0; font-weight: 800;">ALPHAMINDZ</h2>
                    <p style="font-size: 11px; color: #16a34a; font-weight: 700; margin: 0;">ACADEMY OF EXCELLENCE</p>
                </div>
            </div>
            <div class="org-details">
                <h2>ALPHAMINDZ ACADEMY</h2>
                <p>Cognitive Aptitude & Career Assessment Bureau</p>
                <div class="report-ref-badge">
                    REF ID: <?php echo htmlspecialchars($report_id); ?>
                </div>
            </div>
        </header>

        <!-- Student Profile Identity Banner (STUDENT NAME PROMINENTLY AT TOP) -->
        <section class="student-profile-banner">
            <div class="student-top-row">
                <div class="name-block">
                    <div class="subtitle-tag">
                        <i class="ri-user-star-line"></i> Official Student Assessment Report Card
                    </div>
                    <h1><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></h1>
                </div>
                <div class="verified-stamp-badge">
                    <i class="ri-shield-check-fill"></i> Verified & Validated Record
                </div>
            </div>
            <div class="student-meta-grid">
                <div class="meta-item">
                    <div class="meta-label">Student ID / Username</div>
                    <div class="meta-val"><?php echo htmlspecialchars($user->username); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Email Address</div>
                    <div class="meta-val"><?php echo htmlspecialchars($user->email); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Assessment Subject</div>
                    <div class="meta-val"><?php echo htmlspecialchars($attempt->subject); ?></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Completion Date</div>
                    <div class="meta-val"><?php echo date('M d, Y h:i A', strtotime($attempt->completed_at ? $attempt->completed_at : date('Y-m-d H:i:s'))); ?></div>
                </div>
            </div>
        </section>

        <!-- Executive Metrics Grid -->
        <?php
            $score_pct = $attempt->percentage;
            if ($score_pct >= 80) {
                $grade_letter = 'Grade A+';
                $grade_label = 'Exceptional Mastery';
            } elseif ($score_pct >= 65) {
                $grade_letter = 'Grade A';
                $grade_label = 'Proficient Aptitude';
            } elseif ($score_pct >= 50) {
                $grade_letter = 'Grade B+';
                $grade_label = 'Competent Aptitude';
            } elseif ($score_pct >= 35) {
                $grade_letter = 'Grade B';
                $grade_label = 'Developing Foundation';
            } else {
                $grade_letter = 'Grade C';
                $grade_label = 'Requires Focus';
            }
        ?>
        <section class="score-metrics-grid">
            <div class="metric-card score">
                <div class="card-label">Overall Score</div>
                <div class="card-value"><?php echo $attempt->score; ?> <span style="font-size: 15px; color: #64748b; font-weight: 600;">/ <?php echo $attempt->total_questions; ?></span></div>
                <div class="card-subtext">Total Questions: <?php echo $attempt->total_questions; ?></div>
            </div>
            <div class="metric-card pct">
                <div class="card-label">Aggregate Percentage</div>
                <div class="card-value" style="color: #16a34a;"><?php echo number_format($score_pct, 1); ?>%</div>
                <div class="card-subtext">Accuracy Across Sections</div>
            </div>
            <div class="metric-card grade">
                <div class="card-label">Aptitude Rating</div>
                <div class="card-value" style="color: #7c3aed;"><?php echo $grade_letter; ?></div>
                <div class="card-subtext"><?php echo $grade_label; ?></div>
            </div>
            <div class="metric-card mbti">
                <div class="card-label">MBTI Personality</div>
                <div class="card-value" style="color: #d97706;"><?php echo !empty($mbti_type) ? $mbti_type : 'Assessed'; ?></div>
                <div class="card-subtext"><?php echo !empty($mbti_archetype) ? explode('/', $mbti_archetype['title'])[0] : 'Profile Evaluated'; ?></div>
            </div>
        </section>

        <!-- Student Performance Graph -->
        <section class="graph-section-wrapper no-page-break">
            <div class="section-heading-bar">
                <i class="ri-bar-chart-grouped-fill"></i>
                <h3>Cognitive Aptitude & Section-Wise Performance Graph</h3>
            </div>
            
            <div class="graph-layout">
                <!-- Dynamic Chart.js Bar Chart -->
                <div class="chart-canvas-box">
                    <canvas id="studentPerformanceChart"></canvas>
                </div>

                <!-- Print-Resilient CSS Progress Visualizer -->
                <div class="css-bars-box">
                    <?php if(!empty($section_scores)): foreach($section_scores as $sec): 
                        $sec_pct = $sec['percentage'];
                        $clean_name = ucwords(strtolower(trim($sec['section_name'])));
                        $clean_name = str_ireplace(['gk', 'aat'], ['GK', 'AAT'], $clean_name);
                        
                        if ($sec_pct >= 75) $bar_bg = '#16a34a';
                        elseif ($sec_pct >= 50) $bar_bg = '#2563eb';
                        elseif ($sec_pct >= 35) $bar_bg = '#d97706';
                        else $bar_bg = '#dc2626';
                    ?>
                    <div class="bar-row">
                        <div class="bar-label-group">
                            <span><?php echo htmlspecialchars($clean_name); ?></span>
                            <span><?php echo $sec['scored_marks']; ?>/<?php echo $sec['total_questions']; ?> (<?php echo number_format($sec_pct, 1); ?>%)</span>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width: <?php echo min(100, $sec_pct); ?>%; background: <?php echo $bar_bg; ?>;"></div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </section>

        <!-- Section-Wise Marks Breakdown Table -->
        <section class="no-page-break" style="margin-bottom: 24px;">
            <div class="section-heading-bar">
                <i class="ri-table-fill"></i>
                <h3>Section-Wise Marks & Performance Breakdown</h3>
            </div>
            <div class="score-table-container">
                <table class="score-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;">#</th>
                            <th>Section / Cognitive Domain</th>
                            <th style="text-align: center; width: 110px;">Marks Scored</th>
                            <th style="text-align: center; width: 100px;">Total Marks</th>
                            <th style="text-align: center; width: 90px;">Percentage</th>
                            <th style="width: 140px;">Accuracy</th>
                            <th style="text-align: center; width: 130px;">Mastery Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_sec_scored = 0;
                        $total_sec_marks = 0;
                        if (!empty($section_scores)): 
                            foreach($section_scores as $s_idx => $sec):
                                $total_sec_scored += $sec['scored_marks'];
                                $total_sec_marks += $sec['total_questions'];
                                $sec_pct = $sec['percentage'];

                                if ($sec_pct >= 75) {
                                    $bg = '#dcfce7'; $color = '#15803d'; $lbl = 'High Mastery'; $icon = 'ri-checkbox-circle-fill';
                                } elseif ($sec_pct >= 50) {
                                    $bg = '#eff6ff'; $color = '#1d4ed8'; $lbl = 'Proficient'; $icon = 'ri-check-line';
                                } elseif ($sec_pct >= 35) {
                                    $bg = '#fef3c7'; $color = '#b45309'; $lbl = 'Developing'; $icon = 'ri-alert-line';
                                } else {
                                    $bg = '#fee2e2'; $color = '#b91c1c'; $lbl = 'Needs Focus'; $icon = 'ri-close-circle-fill';
                                }

                                $clean_name = ucwords(strtolower(trim($sec['section_name'])));
                                $clean_name = str_ireplace(['gk', 'aat'], ['GK', 'AAT'], $clean_name);
                        ?>
                        <tr>
                            <td style="text-align: center; font-weight: 700; color: #94a3b8;"><?php echo ($s_idx + 1); ?></td>
                            <td style="font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($clean_name); ?></td>
                            <td style="text-align: center; font-weight: 800; color: #16a34a; font-size: 14px;"><?php echo $sec['scored_marks']; ?></td>
                            <td style="text-align: center; font-weight: 700; color: #475569;"><?php echo $sec['total_questions']; ?></td>
                            <td style="text-align: center; font-weight: 800; color: #0f172a;"><?php echo number_format($sec_pct, 1); ?>%</td>
                            <td style="font-size: 11px; color: #64748b;">
                                <strong style="color: #16a34a;"><?php echo $sec['correct']; ?>C</strong> • 
                                <strong style="color: #dc2626;"><?php echo $sec['incorrect']; ?>I</strong>
                                <?php if($sec['unanswered'] > 0): ?> • <strong style="color: #94a3b8;"><?php echo $sec['unanswered']; ?>U</strong><?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="level-badge" style="background: <?php echo $bg; ?>; color: <?php echo $color; ?>;">
                                    <i class="<?php echo $icon; ?>"></i> <?php echo $lbl; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">No section details available.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($section_scores)): 
                        $overall_sec_pct = ($total_sec_marks > 0) ? round(($total_sec_scored / $total_sec_marks) * 100, 2) : 0;
                    ?>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-transform: uppercase;">Aggregate Total</td>
                            <td style="text-align: center; color: #16a34a; font-size: 16px;"><?php echo $total_sec_scored; ?></td>
                            <td style="text-align: center; color: #0f172a;"><?php echo $total_sec_marks; ?></td>
                            <td style="text-align: center; color: #2563eb;"><?php echo number_format($overall_sec_pct, 1); ?>%</td>
                            <td colspan="2" style="font-size: 11px; color: #64748b; font-weight: 600;">
                                Scored <?php echo $total_sec_scored; ?> of <?php echo $total_sec_marks; ?> marks across all <?php echo count($section_scores); ?> assessment domains
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </section>

        <!-- MBTI Personality Profiling Section -->
        <?php if (!empty($mbti_type)): ?>
        <section class="mbti-card-box no-page-break">
            <div class="mbti-top-bar">
                <div class="mbti-badge-group">
                    <span class="mbti-code-pill"><?php echo $mbti_type; ?></span>
                    <div>
                        <div class="mbti-title-text"><?php echo isset($mbti_archetype['title']) ? htmlspecialchars($mbti_archetype['title']) : 'Myers-Briggs Personality Assessment'; ?></div>
                        <span style="font-size: 11px; color: #7c3aed; font-weight: 700; text-transform: uppercase;">Psychological Learning & Behavioral Archetype</span>
                    </div>
                </div>
                <div style="font-size: 12px; font-weight: 800; color: #7c3aed; background: #ede9fe; padding: 4px 10px; border-radius: 6px;">
                    MBTI Validated
                </div>
            </div>

            <?php if (isset($mbti_archetype['desc'])): ?>
            <p class="mbti-desc-text">
                <?php echo htmlspecialchars($mbti_archetype['desc']); ?>
            </p>
            <?php endif; ?>

            <?php if (isset($mbti_scores) && isset($mbti_max)): 
                // Calculate trait dichotomy percentages
                $e_sum = ($mbti_scores['E'] + $mbti_scores['I']) > 0 ? ($mbti_scores['E'] + $mbti_scores['I']) : 1;
                $e_pct = round(($mbti_scores['E'] / $e_sum) * 100);
                $i_pct = 100 - $e_pct;

                $s_sum = ($mbti_scores['S'] + $mbti_scores['N']) > 0 ? ($mbti_scores['S'] + $mbti_scores['N']) : 1;
                $s_pct = round(($mbti_scores['S'] / $s_sum) * 100);
                $n_pct = 100 - $s_pct;

                $t_sum = ($mbti_scores['T'] + $mbti_scores['F']) > 0 ? ($mbti_scores['T'] + $mbti_scores['F']) : 1;
                $t_pct = round(($mbti_scores['T'] / $t_sum) * 100);
                $f_pct = 100 - $t_pct;

                $j_sum = ($mbti_scores['J'] + $mbti_scores['P']) > 0 ? ($mbti_scores['J'] + $mbti_scores['P']) : 1;
                $j_pct = round(($mbti_scores['J'] / $j_sum) * 100);
                $p_pct = 100 - $j_pct;
            ?>
            <div class="dichotomy-grid">
                <!-- E vs I -->
                <div class="dichotomy-row">
                    <div class="dichotomy-labels">
                        <span>Extraversion (E): <?php echo $e_pct; ?>%</span>
                        <span>Introversion (I): <?php echo $i_pct; ?>%</span>
                    </div>
                    <div class="dichotomy-track">
                        <div class="dichotomy-left" style="width: <?php echo $e_pct; ?>%;"></div>
                        <div class="dichotomy-right" style="width: <?php echo $i_pct; ?>%;"></div>
                    </div>
                </div>

                <!-- S vs N -->
                <div class="dichotomy-row">
                    <div class="dichotomy-labels">
                        <span>Sensing (S): <?php echo $s_pct; ?>%</span>
                        <span>Intuition (N): <?php echo $n_pct; ?>%</span>
                    </div>
                    <div class="dichotomy-track">
                        <div class="dichotomy-left" style="width: <?php echo $s_pct; ?>%;"></div>
                        <div class="dichotomy-right" style="width: <?php echo $n_pct; ?>%;"></div>
                    </div>
                </div>

                <!-- T vs F -->
                <div class="dichotomy-row">
                    <div class="dichotomy-labels">
                        <span>Thinking (T): <?php echo $t_pct; ?>%</span>
                        <span>Feeling (F): <?php echo $f_pct; ?>%</span>
                    </div>
                    <div class="dichotomy-track">
                        <div class="dichotomy-left" style="width: <?php echo $t_pct; ?>%;"></div>
                        <div class="dichotomy-right" style="width: <?php echo $f_pct; ?>%;"></div>
                    </div>
                </div>

                <!-- J vs P -->
                <div class="dichotomy-row">
                    <div class="dichotomy-labels">
                        <span>Judging (J): <?php echo $j_pct; ?>%</span>
                        <span>Perceiving (P): <?php echo $p_pct; ?>%</span>
                    </div>
                    <div class="dichotomy-track">
                        <div class="dichotomy-left" style="width: <?php echo $j_pct; ?>%;"></div>
                        <div class="dichotomy-right" style="width: <?php echo $p_pct; ?>%;"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
        <?php endif; ?>

        <!-- Automated Diagnostic Recommendations & Pathways -->
        <?php
            // Identify top strengths and growth areas based on section scores
            $sorted_sections = !empty($section_scores) ? $section_scores : [];
            usort($sorted_sections, function($a, $b) {
                return $b['percentage'] <=> $a['percentage'];
            });

            $top_strengths = array_slice($sorted_sections, 0, 2);
            $growth_areas = array_reverse(array_slice($sorted_sections, -2));
        ?>
        <section class="recommendations-box no-page-break">
            <div class="section-heading-bar">
                <i class="ri-compass-3-fill"></i>
                <h3>Counselor Diagnostic Insights & Pathway Recommendations</h3>
            </div>
            
            <div class="rec-grid">
                <div class="rec-item">
                    <h4><i class="ri-award-fill" style="color: #16a34a;"></i> Key Cognitive Strengths</h4>
                    <p>
                        <?php if(!empty($top_strengths)): ?>
                            Demonstrates exceptional proficiency in 
                            <strong><?php echo htmlspecialchars(ucwords(strtolower($top_strengths[0]['section_name']))); ?> (<?php echo $top_strengths[0]['percentage']; ?>%)</strong>
                            <?php if(isset($top_strengths[1])): ?>
                                and <strong><?php echo htmlspecialchars(ucwords(strtolower($top_strengths[1]['section_name']))); ?> (<?php echo $top_strengths[1]['percentage']; ?>%)</strong>.
                            <?php endif; ?>
                            Indicates high aptitude for conceptual logic, structured analytical execution, and rapid problem diagnosis.
                        <?php else: ?>
                            Solid foundational aptitude exhibited across core diagnostic modules.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="rec-item">
                    <h4><i class="ri-lightbulb-flash-fill" style="color: #2563eb;"></i> Recommended Educational Pathways</h4>
                    <p>
                        Strong cognitive alignment with 
                        <strong>
                            <?php 
                                if (stripos($attempt->subject, 'science') !== false || stripos($attempt->subject, '10th') !== false) {
                                    echo 'STEM Disciplines, Engineering, Applied Mathematics, Computer Science & AI Systems';
                                } elseif (stripos($attempt->subject, 'commerce') !== false) {
                                    echo 'Financial Analytics, Chartered Accountancy, Business Economics & Corporate Management';
                                } elseif (stripos($attempt->subject, 'art') !== false) {
                                    echo 'Design & Architecture, Humanities, Mass Media & Civil Services';
                                } else {
                                    echo 'Higher Secondary Specialization matching Quantitative & Verbal Aptitude Strengths';
                                }
                            ?>
                        </strong>.
                    </p>
                </div>
            </div>
        </section>

        <!-- Official Authentication Seal -->
        <footer class="report-auth-footer">
            <div class="seal-box">
                <div class="official-seal-badge">
                    <i class="ri-shield-check-fill"></i>
                    <div class="seal-text">AlphaMindz<br>Official Seal<br>Verified</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Official Assessment Record</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Report ID: <span style="font-family: monospace; font-weight: 700; color: #1e293b;"><?php echo htmlspecialchars($report_id); ?></span></div>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">Issued: <?php echo date('F d, Y', strtotime($attempt->completed_at ? $attempt->completed_at : date('Y-m-d'))); ?></div>
            </div>
        </footer>

        <div class="confidentiality-notice">
            This diagnostic report card is an official educational record issued by AlphaMindz Academy. Registered Office CIN: U80903GA2008PTC005856. All scores verified against standardized psychometric benchmarks.
        </div>

    </div>

    <!-- Script for Dynamic Chart rendering and 1-Click PDF Generation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Render Chart.js Bar Chart
            var ctx = document.getElementById('studentPerformanceChart').getContext('2d');
            
            var labels = [];
            var dataScores = [];
            var maxScores = [];
            var colors = ['#2563eb', '#16a34a', '#8b5cf6', '#f59e0b', '#06b6d4', '#ec4899', '#f97316'];
            
            <?php if(!empty($section_scores)): foreach($section_scores as $i => $sec): 
                $clean_name = ucwords(strtolower(trim($sec['section_name'])));
                $clean_name = str_ireplace(['gk', 'aat'], ['GK', 'AAT'], $clean_name);
            ?>
                labels.push('<?php echo addslashes($clean_name); ?>');
                dataScores.push(<?php echo $sec['scored_marks']; ?>);
                maxScores.push(<?php echo $sec['total_questions']; ?>);
            <?php endforeach; endif; ?>

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Marks Scored',
                            data: dataScores,
                            backgroundColor: '#2563eb',
                            borderRadius: 6,
                            barPercentage: 0.65
                        },
                        {
                            label: 'Total Marks',
                            data: maxScores,
                            backgroundColor: '#e2e8f0',
                            borderRadius: 6,
                            barPercentage: 0.65
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false, // Essential for instant, accurate PDF rasterization
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { size: 10, weight: 600 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { size: 10, weight: 600 },
                                maxRotation: 25,
                                minRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 11, weight: 700 },
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        });

        // 1-Click Client-Side PDF Generation using html2pdf.js
        function generatePdf() {
            var btn = document.getElementById('downloadPdfBtn');
            var originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Generating PDF...';
            btn.disabled = true;

            var element = document.getElementById('reportContent');
            var opt = {
                margin:       [8, 10, 8, 10], // top, left, bottom, right in mm
                filename:     'AlphaMindz_ReportCard_<?php echo preg_replace("/[^A-Za-z0-9]/", "_", $user->first_name . "_" . $user->last_name); ?>_<?php echo $report_id; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }).catch(function(err) {
                console.error('PDF generation error:', err);
                btn.innerHTML = originalText;
                btn.disabled = false;
                window.print(); // Fallback to native print
            });
        }
    </script>
</body>
</html>
