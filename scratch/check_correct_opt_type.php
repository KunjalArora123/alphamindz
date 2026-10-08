<?php
$db = new mysqli("localhost", "root", "", "alphamindz");
$res = $db->query("SHOW COLUMNS FROM questions LIKE 'correct_option'");
print_r($res->fetch_assoc());
