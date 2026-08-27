<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($course) ? 'Edit Course' : 'Add New Course'; ?> | Admin | AlphaMindz</title>
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
        /* Sidebar Styles (Duplicated for standalone page, normally templated) */
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
        .form-card {
            background-color: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-width: 800px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group textarea {
            height: 150px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #27ae60;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #219653;
        }
        .btn-cancel {
            background-color: #95a5a6;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background-color: #7f8c8d;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>AlphaMindz</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('admin/courses'); ?>" class="active">
                    <i class="ri-book-open-line"></i> Courses
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h1><?php echo isset($course) ? 'Edit Course' : 'Add New Course'; ?></h1>
            <a href="<?php echo site_url('admin/logout'); ?>" class="btn-logout"><i class="ri-logout-circle-r-line"></i> Logout</a>
        </div>

        <!-- Page Content -->
        <div class="content-container">
            <div class="form-card">
                <form action="<?php echo isset($course) ? site_url('admin/update_course/'.$course->id) : site_url('admin/save_course'); ?>" method="POST">
                    
                    <div class="form-group">
                        <label for="title">Course Title</label>
                        <input type="text" id="title" name="title" value="<?php echo isset($course) ? htmlspecialchars($course->title) : ''; ?>" required>
                    </div>

                    <div style="display: flex; gap: 16px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="price">Price (₹)</label>
                            <input type="text" id="price" name="price" value="<?php echo isset($course) ? htmlspecialchars($course->price) : ''; ?>">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="duration">Duration (e.g. 10 hours)</label>
                            <input type="text" id="duration" name="duration" value="<?php echo isset($course) ? htmlspecialchars($course->duration) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="publish" <?php echo (isset($course) && $course->status === 'publish') ? 'selected' : ''; ?>>Publish</option>
                            <option value="draft" <?php echo (isset($course) && $course->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Course Description / Content</label>
                        <textarea id="description" name="description"><?php echo isset($course) ? htmlspecialchars($course->description) : ''; ?></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn-submit">
                            <i class="ri-save-line"></i> <?php echo isset($course) ? 'Update Course' : 'Save Course'; ?>
                        </button>
                        <a href="<?php echo site_url('admin/courses'); ?>" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- CKEditor Rich Text Editor -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
      CKEDITOR.replace('description', {
          height: 300,
          removeButtons: 'PasteFromWord',
          versionCheck: false
      });
    </script>
</body>
</html>
