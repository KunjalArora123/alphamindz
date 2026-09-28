<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$res = $mysqli->query("SHOW CREATE TABLE test_answers");
$row = $res->fetch_assoc();
echo $row['Create Table'];
