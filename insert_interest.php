<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$res = $mysqli->query("SELECT id FROM assessments WHERE title = 'Interest Inventory Test'");
if ($res->num_rows == 0) {
    $mysqli->query("INSERT INTO assessments (title, description, status, time_limit) VALUES ('Interest Inventory Test', 'Preliminary Career Interest & Background Profile', 'active', 0)");
    echo "Inserted";
} else {
    echo "Already exists";
}
