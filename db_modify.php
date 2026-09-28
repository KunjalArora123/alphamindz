<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$mysqli->query("ALTER TABLE test_answers MODIFY COLUMN selected_option TEXT");
echo "Modified";
