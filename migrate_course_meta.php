<?php
$old_db = new mysqli('localhost', 'root', '', 'db_march_2026');
$new_db = new mysqli('localhost', 'root', '', 'alphamindz');

$courses = $new_db->query("SELECT id, old_wp_id FROM courses");

$stmt = $new_db->prepare("UPDATE courses SET price=?, duration=?, instructor_id=?, thumbnail_id=?, max_students=? WHERE id=?");
$meta_stmt = $old_db->prepare("SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id=?");

while ($course = $courses->fetch_assoc()) {
    $meta_stmt->bind_param("i", $course['old_wp_id']);
    $meta_stmt->execute();
    $result = $meta_stmt->get_result();
    
    $price = null;
    $duration = null;
    $duration_param = null;
    $instructor_id = null;
    $thumbnail_id = null;
    $max_students = null;

    while ($meta = $result->fetch_assoc()) {
        if ($meta['meta_key'] == 'mkdf_course_price_meta') $price = $meta['meta_value'];
        if ($meta['meta_key'] == 'mkdf_course_duration_meta') $duration = $meta['meta_value'];
        if ($meta['meta_key'] == 'mkdf_course_duration_parameter_meta') $duration_param = $meta['meta_value'];
        if ($meta['meta_key'] == 'mkdf_course_instructor_meta') $instructor_id = $meta['meta_value'];
        if ($meta['meta_key'] == '_thumbnail_id') $thumbnail_id = $meta['meta_value'];
        if ($meta['meta_key'] == 'mkdf_course_maximum_students_meta') $max_students = $meta['meta_value'];
    }

    if ($duration && $duration_param) {
        $duration = $duration . ' ' . $duration_param;
    }

    $stmt->bind_param("ssiiii", $price, $duration, $instructor_id, $thumbnail_id, $max_students, $course['id']);
    $stmt->execute();
}

echo "Course meta data migrated!\n";
