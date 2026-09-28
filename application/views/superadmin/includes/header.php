<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Superadmin Control Center'; ?> | AlphaMindz</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/images/favicon.png?v='.filemtime(FCPATH.'assets/images/favicon.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            background-color: #0f172a;
            color: #f8fafc;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 270px;
            background: #1e293b;
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #334155;
        }
        .sidebar-header {
            padding: 24px 20px;
            background: #0f172a;
            text-align: center;
            border-bottom: 1px solid #334155;
        }
        .super-tag {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }
        .sidebar-menu {
            flex-grow: 1;
            padding: 16px 0;
            margin: 0;
            list-style: none;
            overflow-y: auto;
        }
        .sidebar-menu li {
            margin-bottom: 4px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #334155;
            color: #ffffff;
            border-left: 4px solid #6366f1;
        }
        .sidebar-menu i {
            margin-right: 12px;
            font-size: 1.25rem;
            color: #818cf8;
        }
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #0f172a;
        }
        .top-navbar {
            background: #1e293b;
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
        }
        .top-navbar h1 {
            margin: 0;
            font-size: 1.35rem;
            color: #f8fafc;
            font-weight: 700;
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }
        .btn-admin-switch {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.4);
            color: #c7d2fe;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 10px;
        }
        .btn-admin-switch:hover {
            background: #4f46e5;
            color: #fff;
        }
        .content-container {
            padding: 24px 28px;
            overflow-y: auto;
            flex-grow: 1;
        }
    </style>
</head>
<body>
