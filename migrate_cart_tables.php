<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// 1. Create orders table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(20) NOT NULL DEFAULT 'completed',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'demo_pay',
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($sql_orders) === TRUE) {
    echo "Table 'orders' checked/created successfully.\n";
} else {
    echo "Error creating table 'orders': " . $db->error . "\n";
}

// 2. Create order_items table
$sql_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_type VARCHAR(50) NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    INDEX (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($sql_order_items) === TRUE) {
    echo "Table 'order_items' checked/created successfully.\n";
} else {
    echo "Error creating table 'order_items': " . $db->error . "\n";
}

// 3. Add price column to assessments table if not existing
$check_pr = $db->query("SHOW COLUMNS FROM assessments LIKE 'price'");
if ($check_pr->num_rows == 0) {
    $db->query("ALTER TABLE assessments ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 999.00 AFTER description");
    echo "Added 'price' column to assessments table.\n";
}

// 4. Seed assessments if missing
$seed_assessments = [
    [
        'title' => 'Class 10 Assessment',
        'slug' => 'class-10-assessment',
        'description' => 'Comprehensive assessment for Class 10 students covering General Science, Mechanical, Numerical, Reasoning, Spatial, and Verbal abilities.',
        'time_limit' => 45,
        'price' => 999.00
    ],
    [
        'title' => 'Student Career Assessment',
        'slug' => 'student-career-assessment',
        'description' => 'Find the perfect career path based on your strengths, interests, and personality traits.',
        'time_limit' => 45,
        'price' => 999.00
    ],
    [
        'title' => 'Kids Interests Assessment',
        'slug' => 'kids-interests-assessment',
        'description' => 'Discover your child\'s innate talents and inclinations to guide their extracurricular activities.',
        'time_limit' => 30,
        'price' => 599.00
    ],
    [
        'title' => 'Personality Profile',
        'slug' => 'personality-profile',
        'description' => 'Gain deep insights into your behavioral patterns and interpersonal dynamics.',
        'time_limit' => 60,
        'price' => 1499.00
    ],
    [
        'title' => 'Leadership Skill Assessment',
        'slug' => 'leadership-skill-assessment',
        'description' => 'Evaluate your leadership capabilities and identify areas for professional growth.',
        'time_limit' => 40,
        'price' => 1299.00
    ]
];

foreach ($seed_assessments as $a) {
    $title_esc = $db->real_escape_string($a['title']);
    $check = $db->query("SELECT id FROM assessments WHERE title = '$title_esc'");
    if ($check->num_rows == 0) {
        $stmt = $db->prepare("INSERT INTO assessments (title, slug, description, time_limit, price, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->bind_param("sssds", $a['title'], $a['slug'], $a['description'], $a['time_limit'], $a['price']);
        $stmt->execute();
        echo "Seeded assessment: '" . $a['title'] . "' with price ₹" . $a['price'] . "\n";
    } else {
        $row = $check->fetch_assoc();
        $db->query("UPDATE assessments SET price = " . $a['price'] . " WHERE id = " . $row['id']);
    }
}

$db->close();
echo "Cart migration & assessment seeding finished successfully.\n";
