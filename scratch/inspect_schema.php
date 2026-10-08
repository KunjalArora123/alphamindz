<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$res = $db->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $table = $row[0];
    echo "=== TABLE: $table ===\n";
    $cres = $db->query("SHOW COLUMNS FROM `$table`");
    while ($crow = $cres->fetch_assoc()) {
        echo "  " . $crow['Field'] . " (" . $crow['Type'] . ")\n";
    }
}
