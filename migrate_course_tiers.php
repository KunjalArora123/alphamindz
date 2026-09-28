<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Add introduction column to courses table if missing
$check_col = $db->query("SHOW COLUMNS FROM courses LIKE 'introduction'");
if ($check_col && $check_col->num_rows == 0) {
    $db->query("ALTER TABLE courses ADD COLUMN introduction LONGTEXT AFTER description");
    echo "Added 'introduction' column to courses table.\n";
} else {
    echo "'introduction' column already exists.\n";
}

// 2. Create course_tiers table
$create_table_sql = "
CREATE TABLE IF NOT EXISTS course_tiers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    tier_key VARCHAR(50) NOT NULL,
    tier_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    description TEXT,
    features TEXT,
    delivery_policy TEXT,
    ebook_policy TEXT,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_course_tier (course_id, tier_key),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->query($create_table_sql)) {
    echo "Table 'course_tiers' created or already exists.\n";
} else {
    echo "Error creating table 'course_tiers': " . $db->error . "\n";
}

// 3. Seed default tiers for existing courses if they have no tiers
$courses_res = $db->query("SELECT id, price FROM courses");
if ($courses_res && $courses_res->num_rows > 0) {
    while ($course = $courses_res->fetch_assoc()) {
        $c_id = $course['id'];
        $base_price = (float)$course['price'];
        
        $check_tiers = $db->query("SELECT id FROM course_tiers WHERE course_id = $c_id");
        if ($check_tiers && $check_tiers->num_rows == 0) {
            $stmt = $db->prepare("INSERT INTO course_tiers (course_id, tier_key, tier_name, price, description, delivery_policy, ebook_policy, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            // Basic Tier
            $k1 = 'basic'; $n1 = 'Basic'; $p1 = $base_price > 0 ? $base_price : 499.00;
            $d1 = 'Tier 1 (Basic) PDF Modules';
            $dp1 = 'Strictly View-Only: Rendered inside embedded canvas. Direct download, printing, right-click, and text scraping disabled.';
            $eb1 = 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.';
            $s1 = 1;
            $stmt->bind_param("issdsssi", $c_id, $k1, $n1, $p1, $d1, $dp1, $eb1, $s1);
            $stmt->execute();
            
            // Standard Tier
            $k2 = 'standard'; $n2 = 'Standard'; $p2 = $p1 + 500.00;
            $d2 = 'Tier 1 (Basic) + Tier 2 (Standard) Modules';
            $dp2 = 'Strictly View-Only: Session-authenticated streaming via signed URLs.';
            $eb2 = 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.';
            $s2 = 2;
            $stmt->bind_param("issdsssi", $c_id, $k2, $n2, $p2, $d2, $dp2, $eb2, $s2);
            $stmt->execute();
            
            // Advanced Tier
            $k3 = 'advanced'; $n3 = 'Advanced'; $p3 = $p2 + 500.00;
            $d3 = 'Tier 1 (Basic) + Tier 2 (Standard) + Tier 3 (Advanced) Modules';
            $dp3 = 'Strictly View-Only: Cryptographically signed temporary streaming tokens.';
            $eb3 = 'Downloadable PDFs: All purchased standalone E-Books are directly downloadable to user devices.';
            $s3 = 3;
            $stmt->bind_param("issdsssi", $c_id, $k3, $n3, $p3, $d3, $dp3, $eb3, $s3);
            $stmt->execute();
        }
    }
    echo "Seeded default tiers for existing courses.\n";
}

$db->close();
echo "Migration complete.\n";
