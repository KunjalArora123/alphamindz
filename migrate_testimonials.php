<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'alphamindz';

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error . "\n");
}

echo "Connected to database successfully.\n";

// 1. Drop existing table if structure differs and create testimonials table
$db->query("DROP TABLE IF EXISTS `testimonials`");
$create_table = "CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `author_name` VARCHAR(100) NOT NULL,
  `author_role` VARCHAR(100) DEFAULT NULL,
  `avatar_initials` VARCHAR(10) NOT NULL,
  `avatar_bg` VARCHAR(20) NOT NULL DEFAULT 'bg-blue',
  `quote` TEXT NOT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($db->query($create_table) === TRUE) {
    echo "Table 'testimonials' created or already exists.\n";
} else {
    die("Error creating table: " . $db->error . "\n");
}

// 2. Testimonials data array
$testimonials = [
    [
        'author_name' => 'Shubham Samant',
        'author_role' => 'UGAT Prep Student',
        'avatar_initials' => 'SS',
        'avatar_bg' => 'bg-blue',
        'quote' => 'Hello! I am Shubham Samant and willing to answer UGAT 2020. I came to know about Alpha Mindz from my family friend. I started my regular UGAT classes under the guidance of teacher Aishwarya, who helped me a lot to get all the information about the exam. I received excellent training from teacher Aishwarya, and all my queries were solved time to time. All the students always received personal attention during the class which helped us to enhance our knowledge. It was a wonderful learning experience. Thank you!'
    ],
    [
        'author_name' => 'Megan Menezes',
        'author_role' => 'Personality Development',
        'avatar_initials' => 'MM',
        'avatar_bg' => 'bg-pink',
        'quote' => 'At Alpha Mindz, I learnt a lot of tips on how I can make the most of everyday through the personality development course. It can be very helpful for those who want to be more confident and to improve on your public speaking skills. The teachers also motivate you in that process by being very encouraging people.'
    ],
    [
        'author_name' => 'Mukund Dravid',
        'author_role' => 'Parent (Spoken English & IELTS)',
        'avatar_initials' => 'MD',
        'avatar_bg' => 'bg-green',
        'quote' => 'My son has been a student at Alpha Mindz. He has completed his Spoken English and IELTS coaching under the valuable Guidance of the Alpha Mindz team. Their highly qualified team and most professional minds in the field assure and deliver you the best of results. The encouragement and support offered by them proves to be precious for the entire future of a student. Appreciate the efforts by Manu Sir and his entire Team. (Alisha, Rupa, Aishwarya, Giselle and Sukanya Mam)'
    ],
    [
        'author_name' => 'Juned Momin',
        'author_role' => 'Student',
        'avatar_initials' => 'JM',
        'avatar_bg' => 'bg-purple',
        'quote' => 'Alpha Mindz teachers are giving me good knowledge thanks to all staff.'
    ],
    [
        'author_name' => 'Nesha Rocks',
        'author_role' => 'Student',
        'avatar_initials' => 'NR',
        'avatar_bg' => 'bg-amber',
        'quote' => 'It was wonderful experience learning with Alpha Mindz well done guys.'
    ],
    [
        'author_name' => 'Sandesh Agarwadekar',
        'author_role' => 'Professional Training',
        'avatar_initials' => 'SA',
        'avatar_bg' => 'bg-teal',
        'quote' => 'It is a very professional training center. Have excellent faculties.'
    ],
    [
        'author_name' => 'Sangita Amonker',
        'author_role' => 'Spoken English Student',
        'avatar_initials' => 'SA',
        'avatar_bg' => 'bg-indigo',
        'quote' => 'This is the best class which I joined for English.'
    ],
    [
        'author_name' => 'Rohan Rolt',
        'author_role' => 'Personality Development',
        'avatar_initials' => 'RR',
        'avatar_bg' => 'bg-blue',
        'quote' => 'Alpha Mindz is the best place for personality development. The Faculty know how to help you very well based on your personality.'
    ],
    [
        'author_name' => 'Tushar Khandeparkar',
        'author_role' => 'Spoken English Student',
        'avatar_initials' => 'TK',
        'avatar_bg' => 'bg-green',
        'quote' => 'Alpha Mindz institution is one of the best institutions for learning. It helped me to increase my speaking confidence level & also motivate me a lot.'
    ],
    [
        'author_name' => 'Raj Shukla',
        'author_role' => 'Spoken English Student',
        'avatar_initials' => 'RS',
        'avatar_bg' => 'bg-pink',
        'quote' => "It's a very good work of my teacher and I think that Alpha Mindz classes are very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good."
    ],
    [
        'author_name' => 'Gopi Pandit',
        'author_role' => 'IELTS Preparation',
        'avatar_initials' => 'GP',
        'avatar_bg' => 'bg-purple',
        'quote' => 'I attended the IELTS classes at Alpha Mindz. My faculty conducted the classes and provided great insights for the preparation. Alpha Mindz made us familiar with the actual test format which helped me gain confidence in the test day. The study material is comprehensive. The classes were very helpful. I want to tell big thanks to Alisha, Deepa and Roopa who helped me to clear my IELTS with good band.'
    ],
    [
        'author_name' => 'Leon Scott',
        'author_role' => 'IELTS Crash Course',
        'avatar_initials' => 'LS',
        'avatar_bg' => 'bg-amber',
        'quote' => 'Excellent, took the 1 week crash course (went 4 times) and got 7.5 in IELTS, the one to one classes makes all the difference.'
    ],
    [
        'author_name' => 'Mukesh Suthar',
        'author_role' => 'Counselling Client',
        'avatar_initials' => 'MS',
        'avatar_bg' => 'bg-teal',
        'quote' => "Best counsellor in Panjim.... it's near Vivanta in Panjim Goa."
    ],
    [
        'author_name' => 'Arushi Naik',
        'author_role' => 'IELTS Student',
        'avatar_initials' => 'AN',
        'avatar_bg' => 'bg-indigo',
        'quote' => 'Had a wonderful experience with Alpha Mindz, great place to training yourself for IELTS. It has very helpful and informative trainers, especially Alisha. Thanks for sharing your knowledge.'
    ],
    [
        'author_name' => 'Rebekah Philip',
        'author_role' => 'IELTS & TOEFL Prep',
        'avatar_initials' => 'RP',
        'avatar_bg' => 'bg-blue',
        'quote' => 'Alpha Mindz has helped me immensely. The faculty is very accommodating and helpful. The schedule is flexible and catered to your convenience. Alpha Mindz made my IELTS exam preparation stress free and easy. I recommend it to anyone seeking help with the same.'
    ],
    [
        'author_name' => 'Swathi K',
        'author_role' => 'TOEFL Student',
        'avatar_initials' => 'SK',
        'avatar_bg' => 'bg-pink',
        'quote' => 'Alpha Mindz has the most helpful and amazing teachers. They have helped me get the required score in my TOEFL exam for foreign education.'
    ],
    [
        'author_name' => 'Purva Kinalekar',
        'author_role' => 'Confidence Coaching',
        'avatar_initials' => 'PK',
        'avatar_bg' => 'bg-green',
        'quote' => 'Alpha Mindz is a great place to help infiltrate confidence within you. Every trainer is exceptionally helpful and skilled. The programs are very well integrated so as to provide a whole experience.'
    ],
    [
        'author_name' => 'Abigail Fernandes',
        'author_role' => 'IELTS & Spoken English',
        'avatar_initials' => 'AF',
        'avatar_bg' => 'bg-purple',
        'quote' => 'Alpha Mindz provides excellent coaching for IELTS, spoken English and other coaching for children as well. They have friendly and cooperative staff.'
    ],
    [
        'author_name' => 'Vinod Vijayan',
        'author_role' => 'IELTS Coaching Student',
        'avatar_initials' => 'VV',
        'avatar_bg' => 'bg-amber',
        'quote' => 'Alpha Mindz was an excellent experience for me in terms of IELTS coaching. Their professional approach in training helped me succeed in the exam. All tutors and the entire team were very supportive and helpful, right from registration to course completion. Keep up your marvellous work Alpha Mindz team.'
    ],
    [
        'author_name' => 'Sashikant Shukla',
        'author_role' => 'Spoken English Student',
        'avatar_initials' => 'SS',
        'avatar_bg' => 'bg-teal',
        'quote' => "It's a very good work of my teacher and I think that Alpha Mindz classes is very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good."
    ],
    [
        'author_name' => 'Manasi Talaulikar',
        'author_role' => 'Career Assessment Client',
        'avatar_initials' => 'MT',
        'avatar_bg' => 'bg-indigo',
        'quote' => 'Alpha Mindz is a great place for learning as the staff is very professional and customer oriented. The courses here aim at proper learning of the concepts rather than finishing it faster. They provide you with a detailed report after your aptitude test which helps you better understand yourself and choose a career that is best suited for you.'
    ]
];

// Truncate existing testimonials to avoid duplicate key conflicts on re-run
$db->query("TRUNCATE TABLE `testimonials`");

$stmt = $db->prepare("INSERT INTO `testimonials` (`author_name`, `author_role`, `avatar_initials`, `avatar_bg`, `quote`, `status`, `sort_order`) VALUES (?, ?, ?, ?, ?, 'active', ?)");

$order = 1;
foreach ($testimonials as $t) {
    $stmt->bind_param("sssssi", $t['author_name'], $t['author_role'], $t['avatar_initials'], $t['avatar_bg'], $t['quote'], $order);
    $stmt->execute();
    $order++;
}

echo "Successfully inserted " . count($testimonials) . " testimonials into database!\n";
$db->close();
