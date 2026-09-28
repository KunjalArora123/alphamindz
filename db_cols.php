<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$res = $mysqli->query('SHOW COLUMNS FROM assessments');
while ($row = $res->fetch_assoc()) echo $row['Field'] . "\n";
