<?php
$mysqli = new mysqli('localhost', 'root', '', 'alphamindz');
$check = $mysqli->query("SELECT id FROM assessments WHERE title = 'MBTI Personality Profiling Test'");
if ($check->num_rows == 0) {
    $mysqli->query("INSERT INTO assessments (title, slug, description, time_limit, status, created_at) VALUES ('MBTI Personality Profiling Test', 'mbti-personality-profiling-test', 'Discover your true personality type based on the Myers-Briggs Type Indicator logic.', 0, 'active', NOW())");
    echo 'Inserted MBTI into assessments table.';
} else {
    echo 'MBTI already in assessments table.';
}
