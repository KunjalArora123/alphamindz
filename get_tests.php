<?php
$db = new mysqli('localhost', 'root', '', 'alphamindz');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$res = $db->query("SELECT DISTINCT subject FROM questions WHERE subject IS NOT NULL AND subject != ''");
echo "Existing Test Subjects in DB:\n";
while ($row = $res->fetch_assoc()) {
    echo "- " . $row['subject'] . "\n";
}

$db->close();
