<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$res = $db->query("SELECT id, title, slug FROM assessments");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Title: {$row['title']}\n";
}
