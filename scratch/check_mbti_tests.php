<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$db->set_charset("utf8mb4");

$res = $db->query("SELECT id, title, slug FROM assessments WHERE id IN (33, 34)");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Title: {$row['title']} | Slug: {$row['slug']}\n";
}
