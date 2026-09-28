<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$res = $mysqli->query('SHOW COLUMNS FROM test_answers');
while ($row = $res->fetch_assoc()) echo $row['Field'] . ' ' . $row['Type'] . "\n";
