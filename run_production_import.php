<?php
/**
 * AlphaMindz Production Database Auto-Importer
 * 
 * Instructions:
 * 1. Upload this script AND `alpha_tests_production_import.sql` to your server.
 * 2. Update DB credentials below ($db_host, $db_user, $db_pass, $db_name).
 * 3. Open this file in your browser (e.g. https://yourdomain.com/run_production_import.php).
 * 4. Delete this file from your server after importing for security.
 */

$db_host = 'localhost';
$db_user = 'root'; // Change to production DB user
$db_pass = '';     // Change to production DB password
$db_name = 'alphamindz'; // Change to production DB name

$sql_file = __DIR__ . '/alpha_tests_production_import.sql';

header('Content-Type: text/plain; charset=utf-8');

echo "=== AlphaMindz Production Import Runner ===\n\n";

if (!file_exists($sql_file)) {
    die("ERROR: SQL dump file not found at: $sql_file\n");
}

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("ERROR: Database connection failed: " . $conn->connect_error . "\n");
}

$conn->set_charset('utf8mb4');

echo "Reading SQL file...\n";
$sql_content = file_get_contents($sql_file);

// Split multi-statement SQL safely
$queries = array_filter(array_map('trim', explode(";\n", $sql_content)));

echo "Found " . count($queries) . " SQL statements to execute.\n\n";

$success = 0;
$failed = 0;

foreach ($queries as $index => $query) {
    if (empty($query) || strpos($query, '--') === 0) {
        continue;
    }
    
    if ($conn->query($query)) {
        $success++;
    } else {
        $failed++;
        echo "Failed Query #$index: " . $conn->error . "\n";
    }
}

echo "\n=== IMPORT COMPLETE ===\n";
echo "Successfully Executed: $success queries\n";
echo "Failed Queries: $failed queries\n";
echo "\nPLEASE DELETE THIS SCRIPT (run_production_import.php) FROM YOUR SERVER NOW FOR SECURITY.\n";
