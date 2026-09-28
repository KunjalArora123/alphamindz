<?php
\ = new mysqli('localhost', 'root', '', 'alphamindz');
\ = \->query('SHOW COLUMNS FROM test_answers');
while (\ = \->fetch_assoc()) echo \['Field'] . ' - ' . \['Type'] . "\n";
