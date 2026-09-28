<?php
$sql = "

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(100) NOT NULL,
  `author_role` varchar(100) DEFAULT NULL,
  `avatar_initials` varchar(10) NOT NULL,
  `avatar_bg` varchar(20) NOT NULL DEFAULT 'bg-blue',
  `quote` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `author_name`, `author_role`, `avatar_initials`, `avatar_bg`, `quote`, `status`, `sort_order`, `created_at`) VALUES
(1, 'Shubham Samant', 'UGAT Prep Student', 'SS', 'bg-blue', 'Hello! I am Shubham Samant and willing to answer UGAT 2020. I came to know about Alpha Mindz from my family friend. I started my regular UGAT classes under the guidance of teacher Aishwarya, who helped me a lot to get all the information about the exam. I received excellent training from teacher Aishwarya, and all my queries were solved time to time. All the students always received personal attention during the class which helped us to enhance our knowledge. It was a wonderful learning experience. Thank you!', 'active', 1, NOW()),
(2, 'Megan Menezes', 'Personality Development', 'MM', 'bg-pink', 'At Alpha Mindz, I learnt a lot of tips on how I can make the most of everyday through the personality development course. It can be very helpful for those who want to be more confident and to improve on your public speaking skills. The teachers also motivate you in that process by being very encouraging people.', 'active', 2, NOW()),
(3, 'Mukund Dravid', 'Parent (Spoken English & IELTS)', 'MD', 'bg-green', 'My son has been a student at Alpha Mindz. He has completed his Spoken English and IELTS coaching under the valuable Guidance of the Alpha Mindz team. Their highly qualified team and most professional minds in the field assure and deliver you the best of results. The encouragement and support offered by them proves to be precious for the entire future of a student. Appreciate the efforts by Manu Sir and his entire Team. (Alisha, Rupa, Aishwarya, Giselle and Sukanya Mam)', 'active', 3, NOW()),
(4, 'Juned Momin', 'Student', 'JM', 'bg-purple', 'Alpha Mindz teachers are giving me good knowledge thanks to all staff.', 'active', 4, NOW()),
(5, 'Nesha Rocks', 'Student', 'NR', 'bg-amber', 'It was wonderful experience learning with Alpha Mindz well done guys.', 'active', 5, NOW()),
(6, 'Sandesh Agarwadekar', 'Professional Training', 'SA', 'bg-teal', 'It is a very professional training center. Have excellent faculties.', 'active', 6, NOW()),
(7, 'Sangita Amonker', 'Spoken English Student', 'SA', 'bg-indigo', 'This is the best class which I joined for English.', 'active', 7, NOW()),
(8, 'Rohan Rolt', 'Personality Development', 'RR', 'bg-blue', 'Alpha Mindz is the best place for personality development. The Faculty know how to help you very well based on your personality.', 'active', 8, NOW()),
(9, 'Tushar Khandeparkar', 'Spoken English Student', 'TK', 'bg-green', 'Alpha Mindz institution is one of the best institutions for learning. It helped me to increase my speaking confidence level & also motivate me a lot.', 'active', 9, NOW()),
(10, 'Raj Shukla', 'Spoken English Student', 'RS', 'bg-pink', 'It\'s a very good work of my teacher and I think that Alpha Mindz classes are very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good.', 'active', 10, NOW()),
(11, 'Gopi Pandit', 'IELTS Preparation', 'GP', 'bg-purple', 'I attended the IELTS classes at Alpha Mindz. My faculty conducted the classes and provided great insights for the preparation. Alpha Mindz made us familiar with the actual test format which helped me gain confidence in the test day. The study material is comprehensive. The classes were very helpful. I want to tell big thanks to Alisha, Deepa and Roopa who helped me to clear my IELTS with good band.', 'active', 11, NOW()),
(12, 'Leon Scott', 'IELTS Crash Course', 'LS', 'bg-amber', 'Excellent, took the 1 week crash course (went 4 times) and got 7.5 in IELTS, the one to one classes makes all the difference.', 'active', 12, NOW()),
(13, 'Mukesh Suthar', 'Counselling Client', 'MS', 'bg-teal', 'Best counsellor in Panjim.... it\'s near Vivanta in Panjim Goa.', 'active', 13, NOW()),
(14, 'Arushi Naik', 'IELTS Student', 'AN', 'bg-indigo', 'Had a wonderful experience with Alpha Mindz, great place to training yourself for IELTS. It has very helpful and informative trainers, especially Alisha. Thanks for sharing your knowledge.', 'active', 14, NOW()),
(15, 'Rebekah Philip', 'IELTS & TOEFL Prep', 'RP', 'bg-blue', 'Alpha Mindz has helped me immensely. The faculty is very accommodating and helpful. The schedule is flexible and catered to your convenience. Alpha Mindz made my IELTS exam preparation stress free and easy. I recommend it to anyone seeking help with the same.', 'active', 15, NOW()),
(16, 'Swathi K', 'TOEFL Student', 'SK', 'bg-pink', 'Alpha Mindz has the most helpful and amazing teachers. They have helped me get the required score in my TOEFL exam for foreign education.', 'active', 16, NOW()),
(17, 'Purva Kinalekar', 'Confidence Coaching', 'PK', 'bg-green', 'Alpha Mindz is a great place to help infiltrate confidence within you. Every trainer is exceptionally helpful and skilled. The programs are very well integrated so as to provide a whole experience.', 'active', 17, NOW()),
(18, 'Abigail Fernandes', 'IELTS & Spoken English', 'AF', 'bg-purple', 'Alpha Mindz provides excellent coaching for IELTS, spoken English and other coaching for children as well. They have friendly and cooperative staff.', 'active', 19, 'Vinod Vijayan', 'IELTS Coaching Student', 'VV', 'bg-amber', 'Alpha Mindz was an excellent experience for me in terms of IELTS coaching. Their professional approach in training helped me succeed in the exam. All tutors and the entire team were very supportive and helpful, right from registration to course completion. Keep up your marvellous work Alpha Mindz team.', 'active', 19, NOW()),
(20, 'Sashikant Shukla', 'Spoken English Student', 'SS', 'bg-teal', 'It\'s a very good work of my teacher and I think that Alpha Mindz classes is very useful for all of Us who wants to learn good English so in classes it is individual class and hence easy to understand. To learn everyday is too good.', 'active', 20, NOW()),
(21, 'Manasi Talaulikar', 'Career Assessment Client', 'MT', 'bg-indigo', 'Alpha Mindz is a great place for learning as the staff is very professional and customer oriented. The courses here aim at proper learning of the concepts rather than finishing it faster. They provide you with a detailed report after your aptitude test which helps you better understand yourself and choose a career that is best suited for you.', 'active', 21, NOW());
";

file_put_contents('d:/xampp/htdocs/AlphaMindz/alphamindz.sql', $sql, FILE_APPEND);
echo "Appended SQL successfully\n";
