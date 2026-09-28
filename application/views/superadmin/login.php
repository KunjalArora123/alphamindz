<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Login | AlphaMindz Portal</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/logo.png'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #f8fafc;
        }
        .super-login-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
        }
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .brand-header img {
            height: 44px;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .brand-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .brand-header p {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 4px;
        }
        .badge-super {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #cbd5e1;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .btn-super-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .btn-super-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.4);
        }
        .alert-error {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            text-align: center;
        }
        .alert-success {
            background-color: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #86efac;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            text-align: center;
        }
        .credentials-info {
            margin-top: 24px;
            padding: 12px;
            background: rgba(15, 23, 42, 0.6);
            border-radius: 8px;
            font-size: 0.8rem;
            color: #94a3b8;
            text-align: center;
            border: 1px dashed rgba(255, 255, 255, 0.1);
        }
        .credentials-info code {
            color: #a5b4fc;
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="super-login-card">
        <div class="brand-header">
            <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="AlphaMindz">
            <h2>Superadmin Portal</h2>
            <p>Elevated Security Authentication</p>
            <span class="badge-super"><i class="ri-shield-flash-line"></i> Master Access</span>
        </div>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert-error">
                <i class="ri-error-warning-line"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('success')): ?>
            <div class="alert-success">
                <i class="ri-checkbox-circle-line"></i> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('superadmin/authenticate'); ?>" method="POST">
            <div class="form-group">
                <label for="email">Superadmin Email / Username</label>
                <div class="input-wrapper">
                    <i class="ri-mail-line"></i>
                    <input type="text" id="email" name="email" class="form-control" placeholder="kunjalarora2@gmail.com" required value="kunjalarora2@gmail.com">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Admin Password</label>
                <div class="input-wrapper">
                    <i class="ri-lock-password-line"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter admin password" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-super-login">
                <i class="ri-shield-keyhole-line"></i> Authenticate & Enter
            </button>
        </form>

        <div class="credentials-info">
            Protected area. Master Admin authorization required.<br>
            Default Email: <code>kunjalarora2@gmail.com</code>
        </div>
    </div>

</body>
</html>
