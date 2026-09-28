<?php
header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Under Maintenance | AlphaMindz</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #090d16;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Glowing Orbs Background */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: floatOrb 12s infinite alternate ease-in-out;
            pointer-events: none;
        }
        .orb-1 {
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            top: -100px;
            left: -100px;
        }
        .orb-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            bottom: -100px;
            right: -100px;
            animation-delay: -6s;
        }
        .orb-3 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -3s;
        }

        @keyframes floatOrb {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(40px) scale(1.1); }
        }

        /* Grid Background Pattern Overlay */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            opacity: 0.6;
        }

        /* Main Container Card */
        .maintenance-card {
            position: relative;
            z-index: 10;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 580px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px rgba(99, 102, 241, 0.15);
        }

        /* Logo Area */
        .logo-box {
            margin-bottom: 28px;
        }
        .logo-box img {
            height: 46px;
            width: auto;
            background: #ffffff;
            padding: 6px 16px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Animated Icon Wrapper */
        .icon-wrapper {
            position: relative;
            width: 96px;
            height: 96px;
            margin: 0 auto 28px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(168, 85, 247, 0.2));
            border: 2px dashed rgba(168, 85, 247, 0.5);
            animation: spinBorder 15s linear infinite;
            position: absolute;
            inset: 0;
        }
        @keyframes spinBorder {
            100% { transform: rotate(360deg); }
        }
        .icon-inner {
            font-size: 2.8rem;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            z-index: 2;
            animation: pulseIcon 2s ease-in-out infinite alternate;
        }
        @keyframes pulseIcon {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 10px #ef4444;
            animation: blinkDot 1.2s infinite;
        }
        @keyframes blinkDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Typography */
        h1 {
            font-size: 2.1rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        p.message {
            color: #cbd5e1;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Progress Scanner Line */
        .progress-box {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 28px;
        }
        .progress-bar-container {
            height: 6px;
            background: #1e293b;
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
        }
        .progress-bar-fill {
            position: absolute;
            height: 100%;
            width: 45%;
            background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
            border-radius: 9999px;
            animation: moveProgress 2.5s ease-in-out infinite alternate;
        }
        @keyframes moveProgress {
            0% { left: 0%; width: 30%; }
            100% { left: 70%; width: 30%; }
        }
        .progress-text {
            font-size: 0.8rem;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }

        /* Refresh Button */
        .btn-refresh {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }
        .btn-refresh:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.6);
        }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="bg-grid"></div>

    <div class="maintenance-card">
        <div class="logo-box">
            <img src="<?php echo base_url('assets/images/logo.png?v='.filemtime(FCPATH.'assets/images/logo.png')); ?>" alt="AlphaMindz">
        </div>

        <div class="status-badge">
            <span class="pulse-dot"></span> Maintenance Mode Active
        </div>

        <div class="icon-wrapper">
            <div class="icon-circle"></div>
            <i class="ri-tools-line icon-inner"></i>
        </div>

        <h1>Under Scheduled Maintenance</h1>

        <p class="message">
            <?php 
                echo isset($maintenance_message) && !empty($maintenance_message) 
                    ? htmlspecialchars($maintenance_message) 
                    : 'We are currently performing scheduled maintenance and performance upgrades to improve your experience. We will be back online shortly!'; 
            ?>
        </p>

        <div class="progress-box">
            <div class="progress-bar-container">
                <div class="progress-bar-fill"></div>
            </div>
            <div class="progress-text">
                <span>System Optimization in Progress</span>
                <span id="timerCountdown">Auto Check</span>
            </div>
        </div>

        <button onclick="window.location.reload();" class="btn-refresh">
            <i class="ri-refresh-line"></i> Check Server Status
        </button>
    </div>

    <script>
        // Auto check every 30 seconds
        let seconds = 30;
        const countdownEl = document.getElementById('timerCountdown');
        setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                window.location.reload();
            } else {
                if (countdownEl) countdownEl.innerText = `Refreshing in ${seconds}s`;
            }
        }, 1000);
    </script>

</body>
</html>
