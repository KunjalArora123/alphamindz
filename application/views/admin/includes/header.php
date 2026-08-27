<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Dashboard'; ?> | AlphaMindz</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 20px;
            background-color: #1a252f;
            text-align: center;
        }
        .sidebar-header h2 {
            margin: 0;
            font-size: 22px;
            color: #fff;
        }
        .sidebar-menu {
            flex-grow: 1;
            padding: 20px 0;
            margin: 0;
            list-style: none;
            overflow-y: auto;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #34495e;
            color: #fff;
            border-left: 4px solid #3498db;
        }
        .sidebar-menu i {
            margin-right: 12px;
            font-size: 20px;
            width: 20px;
            text-align: center;
        }
        /* Main Content Styles */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .top-navbar {
            background-color: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .top-navbar h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .btn-logout {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #e74c3c;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background-color: #c0392b;
        }
        .content-container {
            padding: 32px;
        }
        .data-card, .form-card, .welcome-card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-card { max-width: 800px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .form-group textarea { height: 150px; resize: vertical; }
        .btn-submit { background-color: #27ae60; color: #fff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; }
        .btn-submit:hover { background-color: #219653; }
        .btn-cancel { background-color: #95a5a6; color: #fff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; margin-left: 10px; display: inline-block; }
        .btn-cancel:hover { background-color: #7f8c8d; }
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        table th, table td { text-align: left; padding: 12px; border-bottom: 1px solid #e0e0e0; }
        table th { background-color: #f8f9fa; color: #333; font-weight: 600; }
        table tr:hover { background-color: #f1f1f1; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .status-publish { background-color: #d4edda; color: #155724; }
        .status-draft { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
