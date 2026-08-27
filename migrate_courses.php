<?php

// Connect to old WordPress database
$old_db = new mysqli('localhost', 'root', '', 'db_march_2026');
if ($old_db->connect_error) {
    die("Old DB Connection failed: " . $old_db->connect_error);
}

// Connect to new CodeIgniter database
$new_db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($new_db->connect_error) {
    die("New DB Connection failed: " . $new_db->connect_error);
}

// 1. Create the courses table in the new database
$create_table_sql = "
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    old_wp_id INT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description LONGTEXT,
    status VARCHAR(50) DEFAULT 'publish',
    created_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if (!$new_db->query($create_table_sql)) {
    die("Error creating table: " . $new_db->error);
}

// 2. Clear existing data to prevent duplicates on re-run (optional but safe for dev)
$new_db->query("TRUNCATE TABLE courses");

// 3. Fetch courses from old WordPress database
$sql = "SELECT ID, post_title, post_name, post_content, post_status, post_date 
        FROM wp_posts 
        WHERE post_type = 'course' 
        AND post_status IN ('publish', 'draft')";
        
$result = $old_db->query($sql);

if ($result->num_rows > 0) {
    $stmt = $new_db->prepare("INSERT INTO courses (old_wp_id, title, slug, description, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    
    $count = 0;
    while($row = $result->fetch_assoc()) {
        $stmt->bind_param("isssss", 
            $row['ID'], 
            $row['post_title'], 
            $row['post_name'], 
            $row['post_content'],
            $row['post_status'],
            $row['post_date']
        );
        $stmt->execute();
        $count++;
    }
    echo "Successfully migrated $count courses!\n";
} else {
    echo "No courses found in the old database.\n";
}

$old_db->close();
$new_db->close();
