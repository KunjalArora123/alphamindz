<?php
$old_db = new mysqli('localhost', 'root', '', 'db_march_2026');
$new_db = new mysqli('localhost', 'root', '', 'alphamindz');

// 1. Migrate Products
$sql_products = "SELECT ID, post_title, post_name, post_content, post_date FROM wp_posts WHERE post_type = 'product' AND post_status = 'publish'";
$result = $old_db->query($sql_products);
$stmt = $new_db->prepare("INSERT INTO products (old_wp_id, title, slug, description, created_at) VALUES (?, ?, ?, ?, ?)");
while ($row = $result->fetch_assoc()) {
    $content = preg_replace('/\[\/?vc_[^\]]+\]/', '', $row['post_content']);
    $content = preg_replace('/\[\/?mkdf_[^\]]+\]/', '', $content);
    $stmt->bind_param("issss", $row['ID'], $row['post_title'], $row['post_name'], $content, $row['post_date']);
    $stmt->execute();
}
echo "Products migrated!\n";

// Update Product Prices (from wp_postmeta _price)
$products = $new_db->query("SELECT id, old_wp_id FROM products");
$stmt_price = $new_db->prepare("UPDATE products SET price=? WHERE id=?");
$meta_stmt = $old_db->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_price'");
while ($prod = $products->fetch_assoc()) {
    $meta_stmt->bind_param("i", $prod['old_wp_id']);
    $meta_stmt->execute();
    $meta_res = $meta_stmt->get_result();
    if ($meta_res->num_rows > 0) {
        $price = $meta_res->fetch_assoc()['meta_value'];
        $stmt_price->bind_param("si", $price, $prod['id']);
        $stmt_price->execute();
    }
}
echo "Product prices updated!\n";

// 2. Migrate Articles
$sql_articles = "SELECT ID, post_title, post_name, post_content, post_date FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish'";
$result = $old_db->query($sql_articles);
$stmt = $new_db->prepare("INSERT INTO articles (old_wp_id, title, slug, content, published_at) VALUES (?, ?, ?, ?, ?)");
while ($row = $result->fetch_assoc()) {
    $content = preg_replace('/\[\/?vc_[^\]]+\]/', '', $row['post_content']);
    $content = preg_replace('/\[\/?mkdf_[^\]]+\]/', '', $content);
    $stmt->bind_param("issss", $row['ID'], $row['post_title'], $row['post_name'], $content, $row['post_date']);
    $stmt->execute();
}
echo "Articles migrated!\n";

// 3. Migrate Assessments
$assessment_titles = "'Career Assessment', 'Kids Interests Assessment', 'Personality Profile', 'Skill Assessment'";
$sql_assessments = "SELECT ID, post_title, post_name, post_content, post_date FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish' AND post_title IN ($assessment_titles) GROUP BY post_title"; // GROUP BY to avoid duplicates
$result = $old_db->query($sql_assessments);
$stmt = $new_db->prepare("INSERT INTO assessments (old_wp_id, title, slug, description, created_at) VALUES (?, ?, ?, ?, ?)");
while ($row = $result->fetch_assoc()) {
    $content = preg_replace('/\[\/?vc_[^\]]+\]/', '', $row['post_content']);
    $content = preg_replace('/\[\/?mkdf_[^\]]+\]/', '', $content);
    $stmt->bind_param("issss", $row['ID'], $row['post_title'], $row['post_name'], $content, $row['post_date']);
    $stmt->execute();
}
echo "Assessments migrated!\n";
