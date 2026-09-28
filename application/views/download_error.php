<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($error_title) ? htmlspecialchars($error_title) : 'Download Expired'; ?> | AlphaMindz</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/favicon.png?v='.time()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .error-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px 30px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .icon-badge {
            width: 80px;
            height: 80px;
            background: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px auto;
        }
        h1 {
            font-size: 1.5rem;
            margin: 0 0 10px 0;
            color: #0f172a;
        }
        p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0 0 25px 0;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .btn-home:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon-badge">
            <i class="ri-time-line"></i>
        </div>
        <h1><?php echo isset($error_title) ? htmlspecialchars($error_title) : 'Download Link Expired'; ?></h1>
        <p><?php echo isset($error_message) ? htmlspecialchars($error_message) : 'This download link has reached its TTL expiration time and is no longer valid. Please request a new link from support.'; ?></p>
        <a href="<?php echo base_url(); ?>" class="btn-home">
            <i class="ri-home-4-line"></i> Go to Homepage
        </a>
    </div>

</body>
</html>
