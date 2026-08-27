<?php
$old_db = new mysqli('localhost', 'root', '', 'db_march_2026');
$new_db = new mysqli('localhost', 'root', '', 'alphamindz');

// Get all courses
$courses = $new_db->query("SELECT id, title FROM courses");

while ($course = $courses->fetch_assoc()) {
    $course_title = $old_db->real_escape_string($course['title']);
    
    // We will look for pages that have a title similar to the course title, or containing it.
    // E.g. "Achievers for Children (Std. 2nd to 7th)" vs "Achievers for Children in Std. 2nd to 7th 2 sessions per week"
    
    // Try exact match first
    $query = "SELECT post_content FROM wp_posts WHERE post_type = 'page' AND post_title = '{$course_title}' LIMIT 1";
    $result = $old_db->query($query);
    
    if ($result->num_rows == 0) {
        // Try loose match based on first few words
        $words = explode(' ', $course['title']);
        if (count($words) >= 2) {
            $loose_title = $old_db->real_escape_string($words[0] . ' ' . $words[1] . '%');
            $query = "SELECT post_content FROM wp_posts WHERE post_type = 'page' AND post_title LIKE '{$loose_title}' LIMIT 1";
            $result = $old_db->query($query);
        }
    }
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $content = $row['post_content'];
        
        // Very basic WPBakery shortcode stripping regex
        // We want to keep the text inside but remove the [vc_row] tags
        $content = preg_replace('/\[\/?vc_[^\]]+\]/', '', $content);
        $content = preg_replace('/\[\/?mkdf_[^\]]+\]/', '', $content); // also strip mikado theme shortcodes
        
        // Update the course description
        $stmt = $new_db->prepare("UPDATE courses SET description = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $course['id']);
        $stmt->execute();
        
        echo "Updated content for: " . $course['title'] . "\n";
    }
}
echo "Content migration from pages completed!\n";
