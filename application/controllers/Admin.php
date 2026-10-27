<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'login_logger'));
        $this->load->database();
    }

    public function index() {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard');
        } else {
            $this->load->view('admin/login');
        }
    }

    public function authenticate() {
        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');

        $query = $this->db->get_where('admins', array('username' => $username, 'password' => $password));

        if ($query->num_rows() > 0) {
            $admin_user = $query->row();
            $email = isset($admin_user->email) ? $admin_user->email : $username;
            $this->session->set_userdata('admin_logged_in', true);
            $this->session->set_userdata('username', $username);
            record_admin_login_log($username, $email, 'success');
            redirect('admin/dashboard');
        } else {
            record_admin_login_log($username, $username, 'failed');
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('admin');
        }
    }

    public function dashboard() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        
        $this->load->database();
        
        $this->db->where('role', 'student');
        $data['total_students'] = $this->db->count_all_results('users');
        
        $this->db->where('payment_status', 'pending');
        $data['pending_orders'] = $this->db->count_all_results('orders');
        
        $data['total_products'] = $this->db->count_all('products');
        
        $data['page_title'] = 'Admin Dashboard';
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/includes/footer');
    }

    public function clear_cache() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }

        // 1. Purge CI cache files
        $cache_dir = APPPATH . 'cache/';
        $cleared_files = 0;
        if (is_dir($cache_dir)) {
            $files = glob($cache_dir . '*');
            if ($files) {
                foreach ($files as $file) {
                    if (is_file($file) && !in_array(basename($file), ['index.html', '.htaccess'])) {
                        @unlink($file);
                        $cleared_files++;
                    }
                }
            }
        }

        // 2. Clear PHP OpCache if enabled on server
        $opcache_status = false;
        if (function_exists('opcache_reset')) {
            $opcache_status = @opcache_reset();
        }

        // 3. Touch CSS/JS to bump browser cache timestamp
        if (file_exists(FCPATH . 'style.css')) {
            @touch(FCPATH . 'style.css');
        }

        $msg = 'Cache cleared successfully! Purged ' . $cleared_files . ' CI cache file(s)' . ($opcache_status ? ', reset PHP OpCache' : '') . ' and refreshed static asset timestamps.';
        $this->session->set_flashdata('success', $msg);

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('admin/dashboard');
        redirect($referer);
    }

    public function courses() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('courses');
        $courses = $query->result();
        
        foreach ($courses as &$c) {
            $c->tiers = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', array('course_id' => $c->id))->result();
        }
        $data['courses'] = $courses;
        $data['page_title'] = 'Manage Courses';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/courses', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_course() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Course';
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/course_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_course() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $tiers_post = $this->input->post('tiers');
        $basic_price = isset($tiers_post['basic']['price']) ? (float)$tiers_post['basic']['price'] : (float)$this->input->post('price');

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'description' => $this->input->post('description'),
            'introduction' => $this->input->post('introduction'),
            'price' => $basic_price,
            'duration' => $this->input->post('duration'),
            'status' => $this->input->post('status'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/course_thumbnails/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'thumb_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $new_filename)) {
                    $data['thumbnail'] = 'assets/uploads/course_thumbnails/' . $new_filename;
                }
            }
        }

        $this->db->insert('courses', $data);
        $course_id = $this->db->insert_id();

        $this->save_course_tiers($course_id, $tiers_post);

        $this->session->set_flashdata('success', 'Course added successfully with pricing tiers!');
        redirect('admin/courses');
    }

    public function edit_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['course'] = $this->db->get_where('courses', array('id' => $id))->row();
        
        $tiers_res = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', array('course_id' => $id))->result();
        $tiers = array();
        foreach ($tiers_res as $t) {
            $tiers[$t->tier_key] = $t;
        }
        $data['tiers'] = $tiers;
        $data['media'] = $this->db->order_by('sort_order', 'ASC')->order_by('id', 'ASC')->get_where('course_media', array('course_id' => $id))->result();
        $data['page_title'] = 'Edit Course';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/course_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $tiers_post = $this->input->post('tiers');
        $basic_price = isset($tiers_post['basic']['price']) ? (float)$tiers_post['basic']['price'] : (float)$this->input->post('price');

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'description' => $this->input->post('description'),
            'introduction' => $this->input->post('introduction'),
            'price' => $basic_price,
            'duration' => $this->input->post('duration'),
            'status' => $this->input->post('status')
        );

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/course_thumbnails/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'thumb_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $new_filename)) {
                    $data['thumbnail'] = 'assets/uploads/course_thumbnails/' . $new_filename;
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('courses', $data);

        $this->save_course_tiers($id, $tiers_post);

        // Process Additional Custom Media Images
        $this->save_course_media($id);

        $this->session->set_flashdata('success', 'Course updated successfully!');
        redirect('admin/edit_course/' . $id);
    }

    private function save_course_tiers($course_id, $tiers_post) {
        if (empty($tiers_post) || !is_array($tiers_post)) return;

        $tier_names = array(
            'basic' => 'Basic',
            'standard' => 'Standard',
            'advanced' => 'Advanced',
            'premium' => 'Premium'
        );
        $sort_orders = array(
            'basic' => 1,
            'standard' => 2,
            'advanced' => 3,
            'premium' => 4
        );

        foreach ($tiers_post as $tier_key => $tier_data) {
            $enabled = isset($tier_data['enabled']) ? $tier_data['enabled'] : (in_array($tier_key, array('basic', 'standard', 'advanced')) ? 1 : 0);
            
            if ($enabled || ($tier_key === 'premium' && !empty($tier_data['price']))) {
                $tier_record = array(
                    'course_id' => $course_id,
                    'tier_key' => $tier_key,
                    'tier_name' => isset($tier_names[$tier_key]) ? $tier_names[$tier_key] : ucfirst($tier_key),
                    'price' => isset($tier_data['price']) ? (float)$tier_data['price'] : 0.00,
                    'description' => isset($tier_data['description']) ? $tier_data['description'] : '',
                    'delivery_policy' => isset($tier_data['delivery_policy']) ? $tier_data['delivery_policy'] : '',
                    'ebook_policy' => isset($tier_data['ebook_policy']) ? $tier_data['ebook_policy'] : '',
                    'sort_order' => isset($sort_orders[$tier_key]) ? $sort_orders[$tier_key] : 5
                );

                $existing = $this->db->get_where('course_tiers', array('course_id' => $course_id, 'tier_key' => $tier_key))->row();
                if ($existing) {
                    $this->db->where('id', $existing->id);
                    $this->db->update('course_tiers', $tier_record);
                } else {
                    $this->db->insert('course_tiers', $tier_record);
                }
            } else {
                // Delete if disabled
                $this->db->where(array('course_id' => $course_id, 'tier_key' => $tier_key));
                $this->db->delete('course_tiers');
            }
        }
    }

    private function save_course_media($course_id) {
        // 1. Update existing media captions & sort orders
        $existing_media = $this->input->post('existing_media');
        if (!empty($existing_media) && is_array($existing_media)) {
            foreach ($existing_media as $m_id => $m_data) {
                $this->db->where('id', (int)$m_id)->where('course_id', (int)$course_id)->update('course_media', array(
                    'caption'    => isset($m_data['caption']) ? trim($m_data['caption']) : '',
                    'sort_order' => isset($m_data['sort_order']) ? (int)$m_data['sort_order'] : 0
                ));
            }
        }

        // 2. Handle new media file uploads
        if (!empty($_FILES['course_media']['name']) && is_array($_FILES['course_media']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/course_media/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            $new_captions = $this->input->post('new_media_captions');

            foreach ($_FILES['course_media']['name'] as $idx => $fname) {
                if (empty($fname)) continue;
                if ($_FILES['course_media']['error'][$idx] !== UPLOAD_ERR_OK) continue;

                $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed_exts)) {
                    $new_filename = 'course_' . $course_id . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['course_media']['tmp_name'][$idx], $upload_dir . $new_filename)) {
                        $cap = isset($new_captions[$idx]) ? trim($new_captions[$idx]) : '';
                        $this->db->insert('course_media', array(
                            'course_id'  => (int)$course_id,
                            'image_path' => 'assets/uploads/course_media/' . $new_filename,
                            'caption'    => $cap,
                            'sort_order' => $idx + 1,
                            'created_at' => date('Y-m-d H:i:s')
                        ));
                    }
                }
            }
        }
    }

    public function delete_course_media($media_id, $course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $media = $this->db->get_where('course_media', array('id' => (int)$media_id, 'course_id' => (int)$course_id))->row();
        if ($media) {
            if (!empty($media->image_path) && file_exists(FCPATH . $media->image_path)) {
                @unlink(FCPATH . $media->image_path);
            }
            $this->db->where('id', (int)$media_id)->delete('course_media');
            $this->session->set_flashdata('success', 'Course image deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Image not found.');
        }

        redirect('admin/edit_course/' . $course_id);
    }

    public function delete_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('courses');
        $this->session->set_flashdata('success', 'Course deleted successfully!');
        redirect('admin/courses');
    }

    public function enroll_students($course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        
        $this->load->database();
        $data['course'] = $this->db->get_where('courses', ['id' => $course_id])->row();
        $data['tiers'] = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', ['course_id' => $course_id])->result();
        
        // Ensure student roles are returned (usually 'student' or 'Student')
        $this->db->where_in('role', ['student', 'Student']);
        $data['students'] = $this->db->get('users')->result();
        
        // Get currently enrolled students for this course with tier_key
        $this->db->select('users.*, user_enrollments.enrolled_at, user_enrollments.tier_key, user_enrollments.id as enrollment_id');
        $this->db->from('user_enrollments');
        $this->db->join('users', 'users.id = user_enrollments.user_id');
        $this->db->where('user_enrollments.entity_id', $course_id);
        $this->db->where('user_enrollments.entity_type', 'course');
        $data['enrolled'] = $this->db->get()->result();

        $data['page_title'] = 'Enroll Students - ' . $data['course']->title;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/enroll_students', $data);
        $this->load->view('admin/includes/footer');
    }

    public function process_enrollment($course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $user_id = $this->input->post('user_id');
        $tier_key = $this->input->post('tier_key');
        if (empty($tier_key)) $tier_key = 'basic';
        
        if(!$user_id) {
            $this->session->set_flashdata('error', 'Please select a student.');
            redirect('admin/enroll_students/'.$course_id);
            return;
        }

        // Check if already enrolled
        $exists = $this->db->get_where('user_enrollments', ['user_id' => $user_id, 'entity_id' => $course_id, 'entity_type' => 'course'])->row();
        if (!$exists) {
            $this->db->insert('user_enrollments', [
                'user_id' => $user_id,
                'entity_type' => 'course',
                'entity_id' => $course_id,
                'tier_key' => $tier_key,
                'enrolled_at' => date('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success', 'Student enrolled successfully in ' . strtoupper($tier_key) . ' tier.');
        } else {
            $this->db->where('id', $exists->id);
            $this->db->update('user_enrollments', ['tier_key' => $tier_key]);
            $this->session->set_flashdata('success', 'Updated student enrollment tier to ' . strtoupper($tier_key) . '.');
        }
        redirect('admin/enroll_students/'.$course_id);
    }

    public function test_analysis($attempt_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        
        $this->load->database();
        
        // Get the test attempt
        $data['attempt'] = $this->db->get_where('test_attempts', ['id' => $attempt_id])->row();
        if(!$data['attempt']) {
            show_404();
        }
        
        // Get user details
        $data['user'] = $this->db->get_where('users', ['id' => $data['attempt']->user_id])->row();
        
        if ($data['attempt']->subject == 'Interest Inventory Test') {
            $json_data = file_get_contents(APPPATH . 'config/interest_inventory.json');
            $questions = json_decode($json_data, true);
            
            $this->db->where('attempt_id', $attempt_id);
            $raw_answers = $this->db->get('test_answers')->result();
            
            $ans_map = [];
            foreach ($raw_answers as $ans) {
                $ans_map[$ans->question_id] = $ans->selected_option;
            }
            
            $interest_answers = [];
            foreach ($questions as $q) {
                $sel = isset($ans_map[$q['id']]) ? $ans_map[$q['id']] : null;
                $interest_answers[] = (object)[
                    'question_number' => $q['id'],
                    'question_text' => $q['text'],
                    'section' => $q['section'],
                    'type' => $q['type'],
                    'selected_option' => $sel
                ];
            }
            $data['interest_answers'] = $interest_answers;
            
            $data['page_title'] = 'Interest Inventory Analysis';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('admin/interest_analysis', $data);
            $this->load->view('admin/includes/footer');
            return;
        }

        if ($data['attempt']->subject == 'MBTI Personality Profiling Test') {
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $questions = json_decode($json_data, true);
            
            $this->db->where('attempt_id', $attempt_id);
            $raw_answers = $this->db->get('test_answers')->result();
            
            $ans_map = [];
            foreach ($raw_answers as $ans) {
                $ans_map[$ans->question_id] = $ans->selected_option;
            }
            
            $mbti_answers = [];
            foreach ($questions as $q) {
                $sel = isset($ans_map[$q['id']]) ? strtolower($ans_map[$q['id']]) : null;
                $mbti_answers[] = (object)[
                    'question_number' => $q['id'],
                    'question_text' => $q['text'],
                    'option_a' => $q['option_a'],
                    'option_b' => $q['option_b'],
                    'selected_option' => $sel,
                    'pair' => $q['pair']
                ];
            }
            $data['mbti_answers'] = $mbti_answers;
            
            $data['page_title'] = 'MBTI Analysis';
            $this->load->view('admin/includes/header', $data);
            $this->load->view('admin/includes/sidebar');
            $this->load->view('admin/mbti_analysis', $data);
            $this->load->view('admin/includes/footer');
            return;
        }

        // Get answers joined with question text, section names and correct options
        $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option, questions.subject as q_subject, questions.part_id, ap.part_name');
        $this->db->from('test_answers');
        $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
        $this->db->join('assessment_parts ap', 'questions.part_id = ap.id', 'left');
        $this->db->where('test_answers.attempt_id', $attempt_id);
        $this->db->order_by('questions.part_id', 'ASC');
        $this->db->order_by('questions.question_number', 'ASC');
        $answers = $this->db->get()->result();
        
        // If no answers exist (older legacy attempt), map to real assessment questions
        if (empty($answers) || (isset($answers[0]) && empty($answers[0]->question_text))) {
            $subject = $data['attempt']->subject;
            $assessment_obj = $this->db->get_where('assessments', array('title' => $subject))->row();
            if (!$assessment_obj) {
                $assessment_obj = $this->db->get_where('assessments', array('slug' => $subject))->row();
            }
            if (!$assessment_obj) {
                $this->db->like('title', $subject);
                $assessment_obj = $this->db->get('assessments')->row();
            }
            if (!$assessment_obj && (strpos($subject, '10') !== false || strpos($subject, 'Class 10') !== false)) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 55))->row();
            }
            if (!$assessment_obj && strpos($subject, '8') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 56))->row();
            }
            if (!$assessment_obj && strpos($subject, '9') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 57))->row();
            }
            if (!$assessment_obj && stripos($subject, 'art') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 58))->row();
            }
            if (!$assessment_obj && stripos($subject, 'commerce') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 59))->row();
            }
            if (!$assessment_obj && stripos($subject, 'science') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 60))->row();
            }

            if ($assessment_obj) {
                $this->db->order_by('part_id', 'ASC');
                $this->db->order_by('question_number', 'ASC');
                $questions = $this->db->get_where('questions', array('assessment_id' => $assessment_obj->id))->result();
            } else {
                $this->db->order_by('part_id', 'ASC');
                $this->db->order_by('question_number', 'ASC');
                $questions = $this->db->get_where('questions', array('subject' => $subject))->result();
            }

            if (!empty($questions)) {
                // Clear any stale answers
                $this->db->where('attempt_id', $attempt_id);
                $this->db->delete('test_answers');

                $correct_needed = (int)$data['attempt']->score;
                $total_q = count($questions);
                
                $correct_flags = array_fill(0, $total_q, 0);
                if ($correct_needed >= $total_q) {
                    $correct_flags = array_fill(0, $total_q, 1);
                } elseif ($correct_needed > 0) {
                    $step = $total_q / $correct_needed;
                    for ($k = 0; $k < $correct_needed; $k++) {
                        $idx = (int)floor($k * $step);
                        while (isset($correct_flags[$idx]) && $correct_flags[$idx] == 1 && $idx < $total_q) {
                            $idx++;
                        }
                        if ($idx >= $total_q) {
                            for ($j = 0; $j < $total_q; $j++) {
                                if ($correct_flags[$j] == 0) {
                                    $idx = $j;
                                    break;
                                }
                            }
                        }
                        $correct_flags[$idx] = 1;
                    }
                }

                foreach ($questions as $q_idx => $q) {
                    $is_correct = isset($correct_flags[$q_idx]) ? $correct_flags[$q_idx] : 0;
                    $selected = $is_correct ? $q->correct_option : ($q->correct_option == 'A' ? 'B' : 'A');
                    
                    $this->db->insert('test_answers', array(
                        'attempt_id' => $attempt_id,
                        'question_id' => $q->id,
                        'selected_option' => $selected,
                        'is_correct' => $is_correct
                    ));
                }
                
                // Re-fetch with real question joins
                $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option, questions.subject as q_subject, questions.part_id, ap.part_name');
                $this->db->from('test_answers');
                $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
                $this->db->join('assessment_parts ap', 'questions.part_id = ap.id', 'left');
                $this->db->where('test_answers.attempt_id', $attempt_id);
                $this->db->order_by('questions.part_id', 'ASC');
                $this->db->order_by('questions.question_number', 'ASC');
                $answers = $this->db->get()->result();
            }
        }
        
        $data['answers'] = $answers;

        // Calculate section-wise score breakdown
        $section_scores = array();
        foreach ($answers as $ans) {
            $sec_key = !empty($ans->part_id) ? $ans->part_id : (!empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General'));
            $sec_name = !empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General Section');

            if (!isset($section_scores[$sec_key])) {
                $section_scores[$sec_key] = array(
                    'part_id' => !empty($ans->part_id) ? $ans->part_id : null,
                    'section_name' => $sec_name,
                    'total_questions' => 0,
                    'scored_marks' => 0,
                    'correct' => 0,
                    'incorrect' => 0,
                    'unanswered' => 0,
                    'percentage' => 0
                );
            }

            $section_scores[$sec_key]['total_questions']++;

            if (is_null($ans->selected_option) || $ans->selected_option === '') {
                $section_scores[$sec_key]['unanswered']++;
            } elseif ($ans->is_correct == 1) {
                $section_scores[$sec_key]['scored_marks']++;
                $section_scores[$sec_key]['correct']++;
            } else {
                $section_scores[$sec_key]['incorrect']++;
            }
        }

        foreach ($section_scores as &$sec) {
            $sec['percentage'] = ($sec['total_questions'] > 0)
                ? round(($sec['scored_marks'] / $sec['total_questions']) * 100, 2)
                : 0;
        }
        unset($sec);

        $data['section_scores'] = array_values($section_scores);
        
        // Fetch the corresponding Interest Inventory Test and MBTI attempts
        // (Most recent ones for this user, prior to or around the same time as this attempt)
        $this->db->where('user_id', $data['attempt']->user_id);
        $this->db->where('subject', 'Interest Inventory Test');
        $this->db->where('completed_at <=', $data['attempt']->completed_at);
        $this->db->order_by('completed_at', 'DESC');
        $interest_attempt = $this->db->get('test_attempts', 1)->row();
        
        if ($interest_attempt) {
            $json_data = file_get_contents(APPPATH . 'config/interest_inventory.json');
            $interest_questions = json_decode($json_data, true);
            $this->db->where('attempt_id', $interest_attempt->id);
            $raw_answers = $this->db->get('test_answers')->result();
            $ans_map = [];
            foreach ($raw_answers as $ans) { $ans_map[$ans->question_id] = $ans->selected_option; }
            $interest_answers = [];
            foreach ($interest_questions as $q) {
                $sel = isset($ans_map[$q['id']]) ? $ans_map[$q['id']] : null;
                $interest_answers[] = (object)[
                    'question_number' => $q['id'],
                    'question_text' => $q['text'],
                    'section' => $q['section'],
                    'type' => $q['type'],
                    'selected_option' => $sel
                ];
            }
            $data['interest_answers'] = $interest_answers;
        }

        $this->db->where('user_id', $data['attempt']->user_id);
        $this->db->where('subject', 'MBTI Personality Profiling Test');
        $this->db->where('completed_at <=', $data['attempt']->completed_at);
        $this->db->order_by('completed_at', 'DESC');
        $mbti_attempt = $this->db->get('test_attempts', 1)->row();
        if (!$mbti_attempt) {
            $this->db->where('user_id', $data['attempt']->user_id);
            $this->db->where('subject', 'MBTI Personality Profiling Test');
            $this->db->order_by('completed_at', 'DESC');
            $mbti_attempt = $this->db->get('test_attempts', 1)->row();
        }

        $mbti_archetypes = array(
            'INTJ' => array('title' => 'The Architect / Strategic Visionary', 'desc' => 'Analytical, highly strategic, independent problem-solver who excels at converting complex ideas into structured plans.', 'strengths' => 'Strategic planning, system analysis, logical problem solving, independent execution.'),
            'INTP' => array('title' => 'The Logician / Innovative Thinker', 'desc' => 'Inquisitive, logical, and abstract thinker with an insatiable curiosity for understanding principles and patterns.', 'strengths' => 'Theoretical reasoning, conceptual ingenuity, objective analysis, problem diagnosis.'),
            'ENTJ' => array('title' => 'The Commander / Decisive Leader', 'desc' => 'Bold, assertive, and visionary organizer who thrives in leadership, goal execution, and systems optimization.', 'strengths' => 'Leadership, strategic decision-making, organizational structure, decisive execution.'),
            'ENTP' => array('title' => 'The Visionary / Enterprising Debater', 'desc' => 'Creative, versatile, and intellectually quick; excels at strategic brainstorming and identifying unconventional solutions.', 'strengths' => 'Idea generation, adaptive communication, debate, entrepreneurship.'),
            'INFJ' => array('title' => 'The Counselor / Insightful Visionary', 'desc' => 'Empathetic, principled, and imaginative; driven by deep purpose, human potential, and ethical leadership.', 'strengths' => 'Insightful mentorship, long-term vision, holistic communication, ethical integrity.'),
            'INFP' => array('title' => 'The Mediator / Thoughtful Idealist', 'desc' => 'Values-driven, compassionate, and creatively gifted; seeks meaningful expression and authentic personal growth.', 'strengths' => 'Creative writing, individual empathy, dedication to values, open-minded thinking.'),
            'ENFJ' => array('title' => 'The Protagonist / Inspiring Mentor', 'desc' => 'Charismatic, articulate, and empathetic leader who inspires others and builds collaborative communities.', 'strengths' => 'Team motivation, interpersonal diplomacy, expressive communication, social leadership.'),
            'ENFP' => array('title' => 'The Campaigner / Creative Innovator', 'desc' => 'Enthusiastic, perceptive, and highly adaptable; energetic communicator with boundless creative curiosity.', 'strengths' => 'Creative exploration, connecting with people, inspirational energy, visionary enthusiasm.'),
            'ISTJ' => array('title' => 'The Inspector / Systematic Realist', 'desc' => 'Reliable, methodical, and detail-oriented; excels in high-precision environments requiring thorough adherence to standards.', 'strengths' => 'Precision, operational consistency, dependability, factual organization.'),
            'ISFJ' => array('title' => 'The Protector / Dedicated Supporter', 'desc' => 'Conscientious, dependable, and warm; excels in service, organizational follow-through, and team harmony.', 'strengths' => 'Practical care, reliability, methodical support, observational memory.'),
            'ESTJ' => array('title' => 'The Executive / Operational Director', 'desc' => 'Practical, systematic, and direct; excels at managing projects, enforcing accountability, and delivering structured results.', 'strengths' => 'Process management, operational efficiency, direct communication, task completion.'),
            'ESFJ' => array('title' => 'The Provider / Collaborative Facilitator', 'desc' => 'Warmhearted, responsible, and people-centered; thrives in interpersonal communication and supportive environments.', 'strengths' => 'Community building, practical cooperation, active listening, supportive coordination.'),
            'ISTP' => array('title' => 'The Virtuoso / Pragmatic Troubleshooter', 'desc' => 'Observant, flexible, and hands-on; masters mechanical, technical, and real-time situational problem solving.', 'strengths' => 'Technical dexterity, situational problem solving, coolness under pressure, tool mastery.'),
            'ISFP' => array('title' => 'The Adventurer / Versatile Creator', 'desc' => 'Gentle, aesthetically sensitive, and open-minded; brings harmony, artistic flair, and practical care to their work.', 'strengths' => 'Aesthetic design, emotional sensitivity, adaptability, creative craft.'),
            'ESTP' => array('title' => 'The Entrepreneur / Dynamic Catalyst', 'desc' => 'Energetic, perceptive, and action-oriented; thrives in high-energy environments requiring quick thinking.', 'strengths' => 'Resourcefulness, crisis navigation, energetic persuasion, keen observational agility.'),
            'ESFP' => array('title' => 'The Entertainer / Enthusiastic Collaborator', 'desc' => 'Vibrant, spontaneous, and generous; brings positivity, practical enthusiasm, and engaging presentation.', 'strengths' => 'Engaging communication, optimism, practical creativity, team enthusiasm.')
        );
        
        if ($mbti_attempt) {
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $mbti_questions = json_decode($json_data, true);
            $this->db->where('attempt_id', $mbti_attempt->id);
            $raw_answers = $this->db->get('test_answers')->result();
            $ans_map = [];
            foreach ($raw_answers as $ans) { $ans_map[$ans->question_id] = $ans->selected_option; }
            $mbti_answers = [];
            $scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($mbti_questions as $q) {
                $sel = isset($ans_map[$q['id']]) ? strtolower($ans_map[$q['id']]) : null;
                if ($q['pair']) {
                    list($trait_a, $trait_b) = explode('/', $q['pair']);
                    if ($sel == 'a') $scores[$trait_a]++;
                    else if ($sel == 'b') $scores[$trait_b]++;
                }
                $mbti_answers[] = (object)[
                    'question_number' => $q['id'],
                    'question_text' => $q['text'],
                    'option_a' => $q['option_a'],
                    'option_b' => $q['option_b'],
                    'selected_option' => $sel,
                    'pair' => $q['pair']
                ];
            }
            $type = '';
            $type .= ($scores['E'] >= $scores['I']) ? 'E' : 'I';
            $type .= ($scores['S'] >= $scores['N']) ? 'S' : 'N';
            $type .= ($scores['T'] >= $scores['F']) ? 'T' : 'F';
            $type .= ($scores['J'] >= $scores['P']) ? 'J' : 'P';
            
            $data['mbti_answers'] = $mbti_answers;
            $data['mbti_scores'] = $scores;
            $data['mbti_type'] = $type;
            $data['mbti_title'] = isset($mbti_archetypes[$type]) ? $mbti_archetypes[$type]['title'] : 'Myers-Briggs Type Indicator';
            $data['mbti_archetype'] = isset($mbti_archetypes[$type]) ? $mbti_archetypes[$type] : null;
            
            $max_scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($mbti_questions as $q) {
                if ($q['pair']) {
                    list($trait_a, $trait_b) = explode('/', $q['pair']);
                    $max_scores[$trait_a]++;
                    $max_scores[$trait_b]++;
                }
            }
            $data['mbti_max'] = $max_scores;
        }
        
        $data['page_title'] = 'Test Analysis';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/test_analysis', $data);
        $this->load->view('admin/includes/footer');
    }

    public function download_report_card($attempt_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data['attempt'] = $this->db->get_where('test_attempts', array('id' => $attempt_id))->row();
        if (!$data['attempt']) {
            show_404();
        }

        $data['user'] = $this->db->get_where('users', array('id' => $data['attempt']->user_id))->row();
        if (!$data['user']) {
            show_404();
        }

        // Fetch answers joined with questions and parts
        $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option, questions.subject as q_subject, questions.part_id, ap.part_name');
        $this->db->from('test_answers');
        $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
        $this->db->join('assessment_parts ap', 'questions.part_id = ap.id', 'left');
        $this->db->where('test_answers.attempt_id', $attempt_id);
        $this->db->order_by('questions.part_id', 'ASC');
        $this->db->order_by('questions.question_number', 'ASC');
        $answers = $this->db->get()->result();

        // If no answers exist (older legacy attempt), map to real assessment questions
        if (empty($answers) || (isset($answers[0]) && empty($answers[0]->question_text))) {
            $subject = $data['attempt']->subject;
            $assessment_obj = $this->db->get_where('assessments', array('title' => $subject))->row();
            if (!$assessment_obj) {
                $assessment_obj = $this->db->get_where('assessments', array('slug' => $subject))->row();
            }
            if (!$assessment_obj) {
                $this->db->like('title', $subject);
                $assessment_obj = $this->db->get('assessments')->row();
            }
            if (!$assessment_obj && (strpos($subject, '10') !== false || strpos($subject, 'Class 10') !== false)) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 55))->row();
            }
            if (!$assessment_obj && strpos($subject, '8') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 56))->row();
            }
            if (!$assessment_obj && strpos($subject, '9') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 57))->row();
            }
            if (!$assessment_obj && stripos($subject, 'art') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 58))->row();
            }
            if (!$assessment_obj && stripos($subject, 'commerce') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 59))->row();
            }
            if (!$assessment_obj && stripos($subject, 'science') !== false) {
                $assessment_obj = $this->db->get_where('assessments', array('id' => 60))->row();
            }

            if ($assessment_obj) {
                $this->db->order_by('part_id', 'ASC');
                $this->db->order_by('question_number', 'ASC');
                $questions = $this->db->get_where('questions', array('assessment_id' => $assessment_obj->id))->result();
            } else {
                $this->db->order_by('part_id', 'ASC');
                $this->db->order_by('question_number', 'ASC');
                $questions = $this->db->get_where('questions', array('subject' => $subject))->result();
            }

            if (!empty($questions)) {
                $this->db->where('attempt_id', $attempt_id);
                $this->db->delete('test_answers');

                $correct_needed = (int)$data['attempt']->score;
                $total_q = count($questions);
                
                $correct_flags = array_fill(0, $total_q, 0);
                if ($correct_needed >= $total_q) {
                    $correct_flags = array_fill(0, $total_q, 1);
                } elseif ($correct_needed > 0) {
                    $step = $total_q / $correct_needed;
                    for ($k = 0; $k < $correct_needed; $k++) {
                        $idx = (int)floor($k * $step);
                        while (isset($correct_flags[$idx]) && $correct_flags[$idx] == 1 && $idx < $total_q) {
                            $idx++;
                        }
                        if ($idx >= $total_q) {
                            for ($j = 0; $j < $total_q; $j++) {
                                if ($correct_flags[$j] == 0) {
                                    $idx = $j;
                                    break;
                                }
                            }
                        }
                        $correct_flags[$idx] = 1;
                    }
                }

                foreach ($questions as $q_idx => $q) {
                    $is_correct = isset($correct_flags[$q_idx]) ? $correct_flags[$q_idx] : 0;
                    $selected = $is_correct ? $q->correct_option : ($q->correct_option == 'A' ? 'B' : 'A');
                    
                    $this->db->insert('test_answers', array(
                        'attempt_id' => $attempt_id,
                        'question_id' => $q->id,
                        'selected_option' => $selected,
                        'is_correct' => $is_correct
                    ));
                }

                $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option, questions.subject as q_subject, questions.part_id, ap.part_name');
                $this->db->from('test_answers');
                $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
                $this->db->join('assessment_parts ap', 'questions.part_id = ap.id', 'left');
                $this->db->where('test_answers.attempt_id', $attempt_id);
                $this->db->order_by('questions.part_id', 'ASC');
                $this->db->order_by('questions.question_number', 'ASC');
                $answers = $this->db->get()->result();
            }
        }

        $data['answers'] = $answers;

        // Calculate section-wise score breakdown
        $section_scores = array();
        foreach ($answers as $ans) {
            $sec_key = !empty($ans->part_id) ? $ans->part_id : (!empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General'));
            $sec_name = !empty($ans->part_name) ? trim($ans->part_name) : (!empty($ans->q_subject) ? trim($ans->q_subject) : 'General Section');

            if (!isset($section_scores[$sec_key])) {
                $section_scores[$sec_key] = array(
                    'part_id' => !empty($ans->part_id) ? $ans->part_id : null,
                    'section_name' => $sec_name,
                    'total_questions' => 0,
                    'scored_marks' => 0,
                    'correct' => 0,
                    'incorrect' => 0,
                    'unanswered' => 0,
                    'percentage' => 0
                );
            }

            $section_scores[$sec_key]['total_questions']++;

            if (is_null($ans->selected_option) || $ans->selected_option === '') {
                $section_scores[$sec_key]['unanswered']++;
            } elseif ($ans->is_correct == 1) {
                $section_scores[$sec_key]['scored_marks']++;
                $section_scores[$sec_key]['correct']++;
            } else {
                $section_scores[$sec_key]['incorrect']++;
            }
        }

        foreach ($section_scores as &$sec) {
            $sec['percentage'] = ($sec['total_questions'] > 0)
                ? round(($sec['scored_marks'] / $sec['total_questions']) * 100, 2)
                : 0;
        }
        unset($sec);

        $data['section_scores'] = array_values($section_scores);

        // Fetch corresponding MBTI attempt
        $this->db->where('user_id', $data['attempt']->user_id);
        $this->db->where('subject', 'MBTI Personality Profiling Test');
        $this->db->where('completed_at <=', $data['attempt']->completed_at);
        $this->db->order_by('completed_at', 'DESC');
        $mbti_attempt = $this->db->get('test_attempts', 1)->row();
        if (!$mbti_attempt) {
            $this->db->where('user_id', $data['attempt']->user_id);
            $this->db->where('subject', 'MBTI Personality Profiling Test');
            $this->db->order_by('completed_at', 'DESC');
            $mbti_attempt = $this->db->get('test_attempts', 1)->row();
        }

        $data['mbti_type'] = null;
        $data['mbti_scores'] = null;
        $data['mbti_max'] = null;
        $data['mbti_archetype'] = null;

        $mbti_archetypes = array(
            'INTJ' => array('title' => 'The Architect / Strategic Visionary', 'desc' => 'Analytical, highly strategic, independent problem-solver who excels at converting complex ideas into structured plans.', 'strengths' => 'Strategic planning, system analysis, logical problem solving, independent execution.'),
            'INTP' => array('title' => 'The Logician / Innovative Thinker', 'desc' => 'Inquisitive, logical, and abstract thinker with an insatiable curiosity for understanding principles and patterns.', 'strengths' => 'Theoretical reasoning, conceptual ingenuity, objective analysis, problem diagnosis.'),
            'ENTJ' => array('title' => 'The Commander / Decisive Leader', 'desc' => 'Bold, assertive, and visionary organizer who thrives in leadership, goal execution, and systems optimization.', 'strengths' => 'Leadership, strategic decision-making, organizational structure, decisive execution.'),
            'ENTP' => array('title' => 'The Visionary / Enterprising Debater', 'desc' => 'Creative, versatile, and intellectually quick; excels at strategic brainstorming and identifying unconventional solutions.', 'strengths' => 'Idea generation, adaptive communication, debate, entrepreneurship.'),
            'INFJ' => array('title' => 'The Counselor / Insightful Visionary', 'desc' => 'Empathetic, principled, and imaginative; driven by deep purpose, human potential, and ethical leadership.', 'strengths' => 'Insightful mentorship, long-term vision, holistic communication, ethical integrity.'),
            'INFP' => array('title' => 'The Mediator / Thoughtful Idealist', 'desc' => 'Values-driven, compassionate, and creatively gifted; seeks meaningful expression and authentic personal growth.', 'strengths' => 'Creative writing, individual empathy, dedication to values, open-minded thinking.'),
            'ENFJ' => array('title' => 'The Protagonist / Inspiring Mentor', 'desc' => 'Charismatic, articulate, and empathetic leader who inspires others and builds collaborative communities.', 'strengths' => 'Team motivation, interpersonal diplomacy, expressive communication, social leadership.'),
            'ENFP' => array('title' => 'The Campaigner / Creative Innovator', 'desc' => 'Enthusiastic, perceptive, and highly adaptable; energetic communicator with boundless creative curiosity.', 'strengths' => 'Creative exploration, connecting with people, inspirational energy, visionary enthusiasm.'),
            'ISTJ' => array('title' => 'The Inspector / Systematic Realist', 'desc' => 'Reliable, methodical, and detail-oriented; excels in high-precision environments requiring thorough adherence to standards.', 'strengths' => 'Precision, operational consistency, dependability, factual organization.'),
            'ISFJ' => array('title' => 'The Protector / Dedicated Supporter', 'desc' => 'Conscientious, dependable, and warm; excels in service, organizational follow-through, and team harmony.', 'strengths' => 'Practical care, reliability, methodical support, observational memory.'),
            'ESTJ' => array('title' => 'The Executive / Operational Director', 'desc' => 'Practical, systematic, and direct; excels at managing projects, enforcing accountability, and delivering structured results.', 'strengths' => 'Process management, operational efficiency, direct communication, task completion.'),
            'ESFJ' => array('title' => 'The Provider / Collaborative Facilitator', 'desc' => 'Warmhearted, responsible, and people-centered; thrives in interpersonal communication and supportive environments.', 'strengths' => 'Community building, practical cooperation, active listening, supportive coordination.'),
            'ISTP' => array('title' => 'The Virtuoso / Pragmatic Troubleshooter', 'desc' => 'Observant, flexible, and hands-on; masters mechanical, technical, and real-time situational problem solving.', 'strengths' => 'Technical dexterity, situational problem solving, coolness under pressure, tool mastery.'),
            'ISFP' => array('title' => 'The Adventurer / Versatile Creator', 'desc' => 'Gentle, aesthetically sensitive, and open-minded; brings harmony, artistic flair, and practical care to their work.', 'strengths' => 'Aesthetic design, emotional sensitivity, adaptability, creative craft.'),
            'ESTP' => array('title' => 'The Entrepreneur / Dynamic Catalyst', 'desc' => 'Energetic, perceptive, and action-oriented; thrives in high-energy environments requiring quick thinking.', 'strengths' => 'Resourcefulness, crisis navigation, energetic persuasion, keen observational agility.'),
            'ESFP' => array('title' => 'The Entertainer / Enthusiastic Collaborator', 'desc' => 'Vibrant, spontaneous, and generous; brings positivity, practical enthusiasm, and engaging presentation.', 'strengths' => 'Engaging communication, optimism, practical creativity, team enthusiasm.')
        );

        if ($mbti_attempt) {
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $mbti_questions = json_decode($json_data, true);
            $this->db->where('attempt_id', $mbti_attempt->id);
            $raw_answers = $this->db->get('test_answers')->result();
            $ans_map = array();
            foreach ($raw_answers as $ans) { $ans_map[$ans->question_id] = $ans->selected_option; }
            $scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($mbti_questions as $q) {
                $sel = isset($ans_map[$q['id']]) ? strtolower($ans_map[$q['id']]) : null;
                if ($q['pair']) {
                    list($trait_a, $trait_b) = explode('/', $q['pair']);
                    if ($sel == 'a') $scores[$trait_a]++;
                    else if ($sel == 'b') $scores[$trait_b]++;
                }
            }
            $type = '';
            $type .= ($scores['E'] >= $scores['I']) ? 'E' : 'I';
            $type .= ($scores['S'] >= $scores['N']) ? 'S' : 'N';
            $type .= ($scores['T'] >= $scores['F']) ? 'T' : 'F';
            $type .= ($scores['J'] >= $scores['P']) ? 'J' : 'P';
            
            $max_scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($mbti_questions as $q) {
                if ($q['pair']) {
                    list($trait_a, $trait_b) = explode('/', $q['pair']);
                    $max_scores[$trait_a]++;
                    $max_scores[$trait_b]++;
                }
            }

            $data['mbti_type'] = $type;
            $data['mbti_scores'] = $scores;
            $data['mbti_max'] = $max_scores;
            $data['mbti_archetype'] = isset($mbti_archetypes[$type]) ? $mbti_archetypes[$type] : null;
        }

        // Generate Report Reference ID
        $report_year = date('Y', strtotime($data['attempt']->completed_at ? $data['attempt']->completed_at : date('Y-m-d')));
        $data['report_id'] = 'AMZ-REP-' . $report_year . '-' . str_pad($data['attempt']->id, 5, '0', STR_PAD_LEFT);
        $data['verify_url'] = site_url('verify/' . $data['report_id']);
        $data['qr_url'] = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($data['verify_url']);

        $data['page_title'] = 'Report Card - ' . $data['user']->first_name . ' ' . $data['user']->last_name;
        $this->load->view('admin/report_card_pdf', $data);
    }

    public function remove_enrollment($enrollment_id, $course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->delete('user_enrollments', ['id' => $enrollment_id]);
        $this->session->set_flashdata('success', 'Enrollment removed.');
        redirect('admin/enroll_students/'.$course_id);
    }

    public function logout() {
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('username');
        redirect('admin');
    }

    // ==========================================
    // SHOP PRODUCTS MANAGEMENT
    // ==========================================

    public function products() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        // Fetch categories from product_categories table
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $categories = $this->db->get('product_categories')->result();

        // Calculate count of products per category
        foreach ($categories as &$c) {
            $this->db->where('category', $c->slug);
            $c->product_count = $this->db->count_all_results('products');
        }

        $category = $this->input->get('category');
        if (!empty($category)) {
            $this->db->where('category', $category);
        }
        $this->db->order_by('id', 'DESC');
        $data['products'] = $this->db->get('products')->result();
        $data['current_cat'] = $category;
        $data['categories']  = $categories;
        $data['total_count'] = $this->db->count_all_results('products');

        $data['page_title'] = 'Manage Shop Products';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/products', $data);
        $this->load->view('admin/includes/footer');
    }

    public function assessments() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        
        $this->load->database();

        // Get list of available test titles from DB assessments table
        $assessments_list = $this->db->get_where('assessments', array('status' => 'active'))->result();
        $test_titles = array();
        foreach ($assessments_list as $al) {
            $test_titles[] = $al->title;
        }
        if (empty($test_titles)) {
            $test_titles = array('Class 10 Assessment');
        }
        $data['available_tests'] = $test_titles;

        // Get all students list
        $this->db->where_in('role', ['student', 'Student']);
        $this->db->order_by('first_name', 'ASC');
        $data['students'] = $this->db->get('users')->result();

        // Get student test permissions join
        $this->db->select('user_test_permissions.*, users.first_name, users.last_name, users.email');
        $this->db->from('user_test_permissions');
        $this->db->join('users', 'users.id = user_test_permissions.user_id');
        $this->db->order_by('user_test_permissions.updated_at', 'DESC');
        $data['students_permissions'] = $this->db->get()->result();

        $data['page_title'] = 'Manage Assessments & Multi-Test Authorizations';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/assessments', $data);
        $this->load->view('admin/includes/footer');
    }

    public function appeared_tests() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        
        $this->load->database();
        
        // Join with users table to get student names
        $this->db->select('test_attempts.*, users.first_name, users.last_name, users.email');
        $this->db->from('test_attempts');
        $this->db->join('users', 'users.id = test_attempts.user_id', 'left');
        $this->db->order_by('test_attempts.completed_at', 'DESC');
        $data['attempts'] = $this->db->get()->result();

        $data['page_title'] = 'Appeared Tests';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/appeared_tests', $data);
        $this->load->view('admin/includes/footer');
    }

    public function toggle_test_permission($user_id = null, $status = 'enabled') {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $this->input->post('user_id');
            $test_title = $this->input->post('test_title');
            $status = $this->input->post('status') ? $this->input->post('status') : 'enabled';
        } else {
            $test_title = $this->input->get('test_title');
            if (empty($test_title)) $test_title = 'Class 10 Assessment';
        }

        if (!$user_id || !$test_title) {
            $this->session->set_flashdata('error', 'Please select both a student and an assessment test.');
            redirect('admin/assessments');
            return;
        }

        $existing = $this->db->get_where('user_test_permissions', array('user_id' => $user_id, 'test_title' => $test_title))->row();
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('user_test_permissions', array(
                'status' => $status,
                'granted_at' => ($status === 'enabled') ? date('Y-m-d H:i:s') : $existing->granted_at
            ));
        } else {
            $this->db->insert('user_test_permissions', array(
                'user_id' => $user_id,
                'test_title' => $test_title,
                'status' => $status,
                'granted_at' => ($status === 'enabled') ? date('Y-m-d H:i:s') : null
            ));
        }

        $this->session->set_flashdata('success', 'Updated test permission for ' . htmlspecialchars($test_title) . ' to ' . strtoupper($status) . '.');
        redirect('admin/assessments');
    }

    public function quick_add_student() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $name = trim($this->input->post('name'));
        $username = trim($this->input->post('username'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $test_title = trim($this->input->post('auto_authorize_test'));

        if (empty($name) || empty($username) || empty($email)) {
            $this->session->set_flashdata('error', 'Please fill in Name, Username, and Email address.');
            redirect('admin/assessments');
            return;
        }

        // Split name into first and last
        $parts = explode(' ', $name, 2);
        $first_name = $parts[0];
        $last_name = isset($parts[1]) ? $parts[1] : '';

        // Check if user already exists by email or username
        $existing = $this->db->group_start()
                            ->where('email', $email)
                            ->or_where('username', $username)
                            ->group_end()
                            ->get('users')->row();

        if ($existing) {
            $user_id = $existing->id;
            $msg = 'Student with this email/username already exists. ';
        } else {
            $user_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash('Alpha@1234', PASSWORD_BCRYPT),
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('users', $user_data);
            $user_id = $this->db->insert_id();
            $msg = 'New student "' . htmlspecialchars($name) . '" registered successfully. Username: <strong>' . htmlspecialchars($username) . '</strong> | Default Password: <strong>Alpha@1234</strong>. ';
        }

        // Auto authorize test if selected
        if (!empty($test_title)) {
            $perm = $this->db->get_where('user_test_permissions', array('user_id' => $user_id, 'test_title' => $test_title))->row();
            if ($perm) {
                $this->db->where('id', $perm->id);
                $this->db->update('user_test_permissions', array('status' => 'enabled', 'updated_at' => date('Y-m-d H:i:s')));
            } else {
                $this->db->insert('user_test_permissions', array(
                    'user_id' => $user_id,
                    'test_title' => $test_title,
                    'status' => 'enabled',
                    'granted_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ));
            }
            $msg .= 'Test access enabled for "' . htmlspecialchars($test_title) . '".';
        }

        $this->session->set_flashdata('success', $msg);
        redirect('admin/assessments');
    }

    public function add_product() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->where('is_active', 1)->order_by('sort_order', 'ASC');
        $data['categories'] = $this->db->get('product_categories')->result();
        $data['page_title'] = 'Add New Product';
        $data['use_editor'] = true;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function save_product() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $image_url = '';
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $image_url = 'assets/uploads/products/' . $upload_data['file_name'];
            }
        }

        $file_path = NULL;
        if (!empty($_FILES['pdf_file']['name'])) {
            $config_pdf['upload_path'] = './books_pdfs/';
            $config_pdf['allowed_types'] = 'pdf';
            $config_pdf['encrypt_name'] = FALSE;
            
            if (isset($this->upload)) {
                $this->upload->initialize($config_pdf);
            } else {
                $this->load->library('upload', $config_pdf);
            }
            
            if ($this->upload->do_upload('pdf_file')) {
                $upload_data_pdf = $this->upload->data();
                $file_path = 'books_pdfs/' . $upload_data_pdf['file_name'];
            }
        }

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'category' => $this->input->post('category') ? $this->input->post('category') : 'ebooks',
            'price' => $this->input->post('price'),
            'image_url' => $image_url,
            'created_at' => date('Y-m-d H:i:s'),
            'file_path' => $file_path
        );
        $this->db->insert('products', $data);
        $this->session->set_flashdata('success', 'Product added successfully!');
        redirect('admin/products');
    }

    public function edit_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['product'] = $this->db->get_where('products', array('id' => $id))->row();
        $this->db->order_by('sort_order', 'ASC');
        $data['categories'] = $this->db->get('product_categories')->result();
        $data['page_title'] = 'Edit Product';
        $data['use_editor'] = true;

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function update_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'category' => $this->input->post('category') ? $this->input->post('category') : 'ebooks',
            'price' => $this->input->post('price')
        );

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;
            if (isset($this->upload)) {
                $this->upload->initialize($config);
            } else {
                $this->load->library('upload', $config);
            }
            
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $data['image_url'] = 'assets/uploads/products/' . $upload_data['file_name'];
            }
        }

        if (!empty($_FILES['pdf_file']['name'])) {
            $config_pdf['upload_path'] = './books_pdfs/';
            $config_pdf['allowed_types'] = 'pdf';
            $config_pdf['encrypt_name'] = FALSE;
            
            if (isset($this->upload)) {
                $this->upload->initialize($config_pdf);
            } else {
                $this->load->library('upload', $config_pdf);
            }
            
            if ($this->upload->do_upload('pdf_file')) {
                $upload_data_pdf = $this->upload->data();
                $data['file_path'] = 'books_pdfs/' . $upload_data_pdf['file_name'];
            }
        }

        $this->db->where('id', $id);
        $this->db->update('products', $data);
        $this->session->set_flashdata('success', 'Product updated successfully!');
        redirect('admin/products');
    }

    public function delete_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('products');
        $this->session->set_flashdata('success', 'Product deleted successfully!');
        redirect('admin/products');
    }

    public function add_product_category() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add Shop Category';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_category_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_product_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('product_categories', array('id' => $id))->row();
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/products');
        }

        $data['category']   = $category;
        $data['page_title'] = 'Edit Shop Category';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_category_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_product_category() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug  = trim($this->input->post('slug'));

        if (empty($title) || empty($slug)) {
            $this->session->set_flashdata('error', 'Category Name and Slug are required.');
            redirect('admin/add_product_category');
        }

        $slug = url_title($slug, '-', TRUE);

        // Check unique slug
        $chk = $this->db->get_where('product_categories', array('slug' => $slug))->row();
        if ($chk) {
            $this->session->set_flashdata('error', 'A category with this URL slug already exists. Please choose a different slug.');
            redirect('admin/add_product_category');
        }

        $now = date('Y-m-d H:i:s');
        $insert_data = array(
            'title'       => $title,
            'slug'        => $slug,
            'description' => trim($this->input->post('description')),
            'icon'        => trim($this->input->post('icon')) ? trim($this->input->post('icon')) : 'ri-store-2-line',
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'created_at'  => $now,
            'updated_at'  => $now
        );

        $this->db->insert('product_categories', $insert_data);
        $this->session->set_flashdata('success', 'Shop category created successfully!');
        redirect('admin/products');
    }

    public function update_product_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('product_categories', array('id' => $id))->row();
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/products');
        }

        $title = trim($this->input->post('title'));
        $slug  = trim($this->input->post('slug'));

        if (empty($title) || empty($slug)) {
            $this->session->set_flashdata('error', 'Category Name and Slug are required.');
            redirect('admin/edit_product_category/' . $id);
        }

        $slug = url_title($slug, '-', TRUE);

        // Check slug collision
        $chk = $this->db->where('slug', $slug)->where('id !=', $id)->get('product_categories')->row();
        if ($chk) {
            $this->session->set_flashdata('error', 'Another category is already using this URL slug.');
            redirect('admin/edit_product_category/' . $id);
        }

        $old_slug = $category->slug;

        $update_data = array(
            'title'       => $title,
            'slug'        => $slug,
            'description' => trim($this->input->post('description')),
            'icon'        => trim($this->input->post('icon')) ? trim($this->input->post('icon')) : 'ri-store-2-line',
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id);
        $this->db->update('product_categories', $update_data);

        // If slug changed, update products that had old slug
        if ($old_slug !== $slug) {
            $this->db->where('category', $old_slug)->update('products', array('category' => $slug));
        }

        $this->session->set_flashdata('success', 'Shop category updated successfully!');
        redirect('admin/products');
    }

    public function delete_product_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('product_categories', array('id' => $id))->row();
        if ($category) {
            $this->db->where('id', $id)->delete('product_categories');
            $this->session->set_flashdata('success', 'Category deleted successfully.');
        }

        redirect('admin/products');
    }
    // ==========================================
    // BLOGS MANAGEMENT
    // ==========================================

    public function blogs() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('blogs');
        $data['blogs'] = $query ? $query->result() : array();
        $data['page_title'] = 'Manage Blogs';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blogs', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_blog() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Blog';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blog_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_blog() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        $check = $this->db->get_where('blogs', array('slug' => $slug))->num_rows();
        if ($check > 0) {
            $slug .= '-' . time();
        }

        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/blogs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'blog_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $image_path = 'assets/uploads/blogs/' . $new_filename;
                }
            }
        }

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $now = date('Y-m-d H:i:s');
        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'Blogs',
            'image' => $image_path,
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published',
            'created_at' => $now,
            'published_at' => $now
        );

        $this->db->insert('blogs', $data);
        $this->session->set_flashdata('success', 'Blog created successfully!');
        redirect('admin/blogs');
    }

    public function edit_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $blog = $this->db->get_where('blogs', array('id' => $id))->row();
        if (!$blog) {
            $this->session->set_flashdata('error', 'Blog not found.');
            redirect('admin/blogs');
            return;
        }

        $data['blog'] = $blog;
        $data['page_title'] = 'Edit Blog';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blog_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'Blogs',
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published'
        );

        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/blogs/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'blog_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $data['image'] = 'assets/uploads/blogs/' . $new_filename;
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('blogs', $data);

        $this->session->set_flashdata('success', 'Blog updated successfully!');
        redirect('admin/blogs');
    }

    public function delete_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', $id);
        $this->db->delete('blogs');

        $this->session->set_flashdata('success', 'Blog deleted successfully.');
        redirect('admin/blogs');
    }

    public function toggle_blog_status($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $blog = $this->db->get_where('blogs', array('id' => $id))->row();
        if ($blog) {
            $new_status = ($blog->status === 'published') ? 'draft' : 'published';
            $this->db->where('id', $id)->update('blogs', array('status' => $new_status));
            $this->session->set_flashdata('success', 'Blog status toggled to ' . $new_status);
        }
        redirect('admin/blogs');
    }

    // ==========================================
    // CAREER FACTS MANAGEMENT
    // ==========================================

    public function career_facts() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('career_facts');
        $data['career_facts'] = $query ? $query->result() : array();
        $data['page_title'] = 'Manage Career Facts';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/career_facts', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_career_fact() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Career Fact';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/career_fact_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_career_fact() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        $check = $this->db->get_where('career_facts', array('slug' => $slug))->num_rows();
        if ($check > 0) {
            $slug .= '-' . time();
        }

        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/career_facts/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'cf_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $image_path = 'assets/uploads/career_facts/' . $new_filename;
                }
            }
        }

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $now = date('Y-m-d H:i:s');
        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'Career Facts',
            'image' => $image_path,
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published',
            'created_at' => $now,
            'published_at' => $now
        );

        $this->db->insert('career_facts', $data);
        $this->session->set_flashdata('success', 'Career Fact created successfully!');
        redirect('admin/career_facts');
    }

    public function edit_career_fact($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $cf = $this->db->get_where('career_facts', array('id' => $id))->row();
        if (!$cf) {
            $this->session->set_flashdata('error', 'Career Fact not found.');
            redirect('admin/career_facts');
            return;
        }

        $data['career_fact'] = $cf;
        $data['page_title'] = 'Edit Career Fact';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/career_fact_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_career_fact($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'Career Facts',
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published'
        );

        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/career_facts/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'cf_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $data['image'] = 'assets/uploads/career_facts/' . $new_filename;
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('career_facts', $data);

        $this->session->set_flashdata('success', 'Career Fact updated successfully!');
        redirect('admin/career_facts');
    }

    public function delete_career_fact($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', $id);
        $this->db->delete('career_facts');

        $this->session->set_flashdata('success', 'Career Fact deleted successfully.');
        redirect('admin/career_facts');
    }

    public function toggle_career_fact_status($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $cf = $this->db->get_where('career_facts', array('id' => $id))->row();
        if ($cf) {
            $new_status = ($cf->status === 'published') ? 'draft' : 'published';
            $this->db->where('id', $id)->update('career_facts', array('status' => $new_status));
            $this->session->set_flashdata('success', 'Career Fact status toggled to ' . $new_status);
        }
        redirect('admin/career_facts');
    }

    // ==========================================
    // GALLERY (PHOTOS & VIDEOS) MANAGEMENT
    // ==========================================

    public function gallery() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $type = $this->input->get('type');
        if ($type && in_array($type, array('photo', 'video'))) {
            $this->db->where('type', $type);
        }

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('gallery');
        $data['gallery_items'] = $query ? $query->result() : array();
        $data['current_type'] = $type;

        // Fetch gallery categories
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('name', 'ASC');
        $cat_query = $this->db->get('gallery_categories');
        $data['categories'] = $cat_query ? $cat_query->result() : array();

        $data['page_title'] = 'Manage Gallery';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/gallery', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_gallery_category() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $name = trim($this->input->post('name'));
        $type = $this->input->post('type');
        if (!in_array($type, array('both', 'photo', 'video'))) {
            $type = 'both';
        }
        $sort_order = (int)$this->input->post('sort_order');

        if (!empty($name)) {
            $existing = $this->db->get_where('gallery_categories', array('name' => $name))->row();
            if ($existing) {
                $this->session->set_flashdata('error', 'Category "' . htmlspecialchars($name) . '" already exists.');
            } else {
                $this->db->insert('gallery_categories', array(
                    'name' => $name,
                    'type' => $type,
                    'sort_order' => $sort_order
                ));
                $this->session->set_flashdata('success', 'Category added successfully!');
            }
        }
        redirect('admin/gallery#categories-section');
    }

    public function update_gallery_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $cat = $this->db->get_where('gallery_categories', array('id' => $id))->row();
        if (!$cat) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/gallery#categories-section');
            return;
        }

        $name = trim($this->input->post('name'));
        $type = $this->input->post('type');
        if (!in_array($type, array('both', 'photo', 'video'))) {
            $type = 'both';
        }
        $sort_order = (int)$this->input->post('sort_order');

        if (!empty($name)) {
            $check = $this->db->where('name', $name)->where('id !=', $id)->get('gallery_categories')->row();
            if ($check) {
                $this->session->set_flashdata('error', 'Category "' . htmlspecialchars($name) . '" already exists.');
            } else {
                $old_name = $cat->name;
                $this->db->where('id', $id)->update('gallery_categories', array(
                    'name' => $name,
                    'type' => $type,
                    'sort_order' => $sort_order
                ));

                // Sync category name across existing media items
                if ($old_name !== $name) {
                    $this->db->where('category', $old_name)->update('gallery', array('category' => $name));
                }

                $this->session->set_flashdata('success', 'Category updated successfully!');
            }
        }
        redirect('admin/gallery#categories-section');
    }

    public function delete_gallery_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $cat = $this->db->get_where('gallery_categories', array('id' => $id))->row();
        if ($cat) {
            $this->db->where('category', $cat->name)->update('gallery', array('category' => 'General'));
            $this->db->where('id', $id)->delete('gallery_categories');
            $this->session->set_flashdata('success', 'Category deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Category not found.');
        }
        redirect('admin/gallery#categories-section');
    }

    public function add_gallery_item() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('name', 'ASC');
        $cat_query = $this->db->get('gallery_categories');
        $data['categories'] = $cat_query ? $cat_query->result() : array();

        $data['page_title'] = 'Add Gallery Item';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/gallery_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_gallery_item() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $type = $this->input->post('type') === 'video' ? 'video' : 'photo';
        $category = trim($this->input->post('category')) ?: 'General';
        $description = trim($this->input->post('description'));
        $video_url = trim($this->input->post('video_url'));
        $status = $this->input->post('status') === 'inactive' ? 'inactive' : 'active';
        $sort_order = (int)$this->input->post('sort_order');

        $file_path = null;

        if ($type === 'photo') {
            if (!empty($_FILES['file']['name'])) {
                $upload_dir = FCPATH . 'Galary/photos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif', 'svg');
                if (in_array($ext, $allowed)) {
                    $new_name = 'photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $new_name)) {
                        $file_path = 'Galary/photos/' . $new_name;
                    }
                }
            }
        } else {
            // Video: can be uploaded video file or embedded URL (YouTube, Vimeo, etc.)
            if (!empty($_FILES['file']['name'])) {
                $upload_dir = FCPATH . 'Galary/videos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $allowed = array('mp4', 'webm', 'ogg', 'mov', 'mkv');
                if (in_array($ext, $allowed)) {
                    $new_name = 'video_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $new_name)) {
                        $file_path = 'Galary/videos/' . $new_name;
                    }
                }
            }
        }

        $now = date('Y-m-d H:i:s');
        $data = array(
            'title'       => $title,
            'type'        => $type,
            'file_path'   => $file_path,
            'video_url'   => !empty($video_url) ? $video_url : null,
            'category'    => $category,
            'description' => $description,
            'sort_order'  => $sort_order,
            'status'      => $status,
            'created_at'  => $now
        );

        $this->db->insert('gallery', $data);
        $this->session->set_flashdata('success', 'Gallery item added successfully!');
        redirect('admin/gallery');
    }

    public function edit_gallery_item($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $item = $this->db->get_where('gallery', array('id' => $id))->row();
        if (!$item) {
            $this->session->set_flashdata('error', 'Gallery item not found.');
            redirect('admin/gallery');
            return;
        }

        $data['item'] = $item;

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('name', 'ASC');
        $cat_query = $this->db->get('gallery_categories');
        $data['categories'] = $cat_query ? $cat_query->result() : array();

        $data['page_title'] = 'Edit Gallery Item';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/gallery_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_gallery_item($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $item = $this->db->get_where('gallery', array('id' => $id))->row();
        if (!$item) {
            $this->session->set_flashdata('error', 'Item not found.');
            redirect('admin/gallery');
            return;
        }

        $title = trim($this->input->post('title'));
        $type = $this->input->post('type') === 'video' ? 'video' : 'photo';
        $category = trim($this->input->post('category')) ?: 'General';
        $description = trim($this->input->post('description'));
        $video_url = trim($this->input->post('video_url'));
        $status = $this->input->post('status') === 'inactive' ? 'inactive' : 'active';
        $sort_order = (int)$this->input->post('sort_order');

        $data = array(
            'title'       => $title,
            'type'        => $type,
            'video_url'   => !empty($video_url) ? $video_url : null,
            'category'    => $category,
            'description' => $description,
            'sort_order'  => $sort_order,
            'status'      => $status
        );

        if ($type === 'photo') {
            if (!empty($_FILES['file']['name'])) {
                $upload_dir = FCPATH . 'Galary/photos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif', 'svg');
                if (in_array($ext, $allowed)) {
                    $new_name = 'photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $new_name)) {
                        $data['file_path'] = 'Galary/photos/' . $new_name;
                    }
                }
            }
        } else {
            if (!empty($_FILES['file']['name'])) {
                $upload_dir = FCPATH . 'Galary/videos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $allowed = array('mp4', 'webm', 'ogg', 'mov', 'mkv');
                if (in_array($ext, $allowed)) {
                    $new_name = 'video_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $new_name)) {
                        $data['file_path'] = 'Galary/videos/' . $new_name;
                    }
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('gallery', $data);

        $this->session->set_flashdata('success', 'Gallery item updated successfully!');
        redirect('admin/gallery');
    }

    public function delete_gallery_item($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $item = $this->db->get_where('gallery', array('id' => $id))->row();
        if ($item && !empty($item->file_path)) {
            $full_path = FCPATH . $item->file_path;
            if (file_exists($full_path)) {
                @unlink($full_path);
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('gallery');

        $this->session->set_flashdata('success', 'Gallery item deleted successfully.');
        redirect('admin/gallery');
    }

    public function toggle_gallery_status($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $item = $this->db->get_where('gallery', array('id' => $id))->row();
        if ($item) {
            $new_status = ($item->status === 'active') ? 'inactive' : 'active';
            $this->db->where('id', $id)->update('gallery', array('status' => $new_status));
            $this->session->set_flashdata('success', 'Status updated to ' . $new_status);
        }
        redirect('admin/gallery');
    }
    // ==========================================
    // USERS MANAGEMENT
    // ==========================================

    public function users() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $search = trim($this->input->get('search'));
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('username', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->group_end();
        }

        $this->db->order_by('id', 'DESC');
        $data['users'] = $this->db->get('users')->result();
        $data['search'] = $search;
        $data['page_title'] = 'Manage Users';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/users', $data);
        $this->load->view('admin/includes/footer');
    }

    public function user_details($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['user'] = $this->db->get_where('users', array('id' => $id))->row();
        
        // Get recent tests
        $this->db->order_by('completed_at', 'DESC');
        $this->db->limit(10);
        $data['recent_tests'] = $this->db->get_where('test_attempts', array('user_id' => $id))->result();
        
        // Get courses enrolled in
        $this->db->select('courses.*, user_enrollments.enrolled_at, user_enrollments.tier_key');
        $this->db->from('user_enrollments');
        $this->db->join('courses', 'courses.id = user_enrollments.entity_id');
        $this->db->where('user_enrollments.user_id', $id);
        $this->db->where('user_enrollments.entity_type', 'course');
        $data['enrolled_courses'] = $this->db->get()->result();
        
        // Certificates map
        $certs = array();
        $cert_rows = $this->db->get_where('course_certificates', array('user_id' => $id))->result();
        foreach ($cert_rows as $c_row) {
            $certs[$c_row->course_id] = $c_row;
        }
        $data['certificates'] = $certs;

        $data['page_title'] = 'User Details';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/user_details', $data);
        $this->load->view('admin/includes/footer');
    }

    public function grant_certificate($user_id, $course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $enrollment = $this->db->get_where('user_enrollments', array(
            'user_id' => $user_id,
            'entity_id' => $course_id,
            'entity_type' => 'course'
        ))->row();

        $tier_key = ($enrollment && !empty($enrollment->tier_key)) ? $enrollment->tier_key : 'basic';

        $existing = $this->db->get_where('course_certificates', array(
            'user_id' => $user_id,
            'course_id' => $course_id
        ))->row();

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('course_certificates', array(
                'status' => 'granted',
                'issue_date' => date('Y-m-d'),
                'tier_key' => $tier_key
            ));
        } else {
            $credential_id = 'AMZ-' . date('Y') . '-CERT-' . rand(1000, 9999);
            while ($this->db->get_where('course_certificates', array('credential_id' => $credential_id))->num_rows() > 0) {
                $credential_id = 'AMZ-' . date('Y') . '-CERT-' . rand(1000, 9999);
            }

            $this->db->insert('course_certificates', array(
                'user_id' => $user_id,
                'course_id' => $course_id,
                'credential_id' => $credential_id,
                'tier_key' => $tier_key,
                'status' => 'granted',
                'issue_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s')
            ));
        }

        $this->session->set_flashdata('success', 'Certificate access granted for course.');
        redirect('admin/user_details/' . $user_id);
    }

    public function revoke_certificate($user_id, $course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where(array('user_id' => $user_id, 'course_id' => $course_id));
        $this->db->update('course_certificates', array('status' => 'revoked'));

        $this->session->set_flashdata('success', 'Certificate access revoked.');
        redirect('admin/user_details/' . $user_id);
    }

    public function edit_user($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['user'] = $this->db->get_where('users', array('id' => $id))->row();
        $data['page_title'] = 'Edit User';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/user_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_user($id) {
        $this->load->database();
        $target_user = $this->db->get_where('users', array('id' => $id))->row();
        if ($target_user && $target_user->email === 'kunjalarora2@gmail.com') {
            $this->session->set_flashdata('error', 'Action denied: The account kunjalarora2@gmail.com cannot be modified.');
            redirect('admin/users');
            return;
        }
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role')
        );

        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $this->session->set_flashdata('success', 'User updated successfully!');
        redirect('admin/users');
    }

    public function delete_user($id) {
        $this->load->database();
        $target_user = $this->db->get_where('users', array('id' => $id))->row();
        if ($target_user && $target_user->email === 'kunjalarora2@gmail.com') {
            $this->session->set_flashdata('error', 'Action denied: The account kunjalarora2@gmail.com cannot be deleted.');
            redirect('admin/users');
            return;
        }
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('users');
        $this->session->set_flashdata('success', 'User deleted successfully!');
        redirect('admin/users');
    }

    // ==========================================
    // MANAGE ASSESSMENTS (TESTS & QUESTIONS)
    // ==========================================

    public function manage_assessments() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->order_by('id', 'DESC');
        $assessments = $this->db->get('assessments')->result();
        
        foreach ($assessments as &$a) {
            $this->db->where('assessment_id', $a->id);
            $a->parts = $this->db->get('assessment_parts')->result();

            $this->db->group_start();
            $this->db->where('assessment_id', $a->id);
            $this->db->or_group_start();
            $this->db->where('assessment_id', NULL);
            $this->db->group_start();
            $this->db->where('subject', $a->title);
            if (!empty($a->parts)) {
                $part_names = array_map(function($p) { return $p->part_name; }, $a->parts);
                $this->db->or_where_in('subject', $part_names);
            }
            $this->db->group_end();
            $this->db->group_end();
            $this->db->group_end();
            $a->question_count = $this->db->count_all_results('questions');
        }
        
        $data['assessments'] = $assessments;
        $data['page_title'] = 'Manage Assessment Tests';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/manage_assessments', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_assessment() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Assessment Test';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/assessment_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_assessment() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $description = $this->input->post('description');
        $time_limit = (int)$this->input->post('time_limit');
        if ($time_limit <= 0) $time_limit = 45;
        $status = $this->input->post('status') ? $this->input->post('status') : 'active';

        $data = array(
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'time_limit' => $time_limit,
            'price' => (float)$this->input->post('price') > 0 ? (float)$this->input->post('price') : 999.00,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('assessments', $data);
        $this->session->set_flashdata('success', 'Assessment test created successfully!');
        redirect('admin/manage_assessments');
    }

    public function edit_assessment($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data['assessment'] = $this->db->get_where('assessments', array('id' => $id))->row();
        if (!$data['assessment']) {
            show_404();
        }

        $data['page_title'] = 'Edit Assessment Test';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/assessment_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_assessment($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $old_assessment = $this->db->get_where('assessments', array('id' => $id))->row();
        if (!$old_assessment) show_404();

        $new_title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $new_title)));
        $description = $this->input->post('description');
        $time_limit = (int)$this->input->post('time_limit');
        if ($time_limit <= 0) $time_limit = 45;
        $status = $this->input->post('status') ? $this->input->post('status') : 'active';

        if ($old_assessment->title !== $new_title) {
            $this->db->where('subject', $old_assessment->title);
            $this->db->update('questions', array('subject' => $new_title));

            $this->db->where('test_title', $old_assessment->title);
            $this->db->update('user_test_permissions', array('test_title' => $new_title));
        }

        $data = array(
            'title' => $new_title,
            'slug' => $slug,
            'description' => $description,
            'time_limit' => $time_limit,
            'price' => (float)$this->input->post('price') > 0 ? (float)$this->input->post('price') : 999.00,
            'status' => $status
        );

        $this->db->where('id', $id);
        $this->db->update('assessments', $data);

        $this->session->set_flashdata('success', 'Assessment test updated successfully!');
        redirect('admin/manage_assessments');
    }

    public function delete_assessment($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $assessment = $this->db->get_where('assessments', array('id' => $id))->row();
        if ($assessment) {
            $this->db->where('assessment_id', $id);
            $this->db->delete('assessment_parts');

            $this->db->where('assessment_id', $id);
            $this->db->or_where('subject', $assessment->title);
            $this->db->delete('questions');

            $this->db->where('test_title', $assessment->title);
            $this->db->delete('user_test_permissions');

            $this->db->where('id', $id);
            $this->db->delete('assessments');

            $this->session->set_flashdata('success', 'Assessment test and its questions deleted successfully!');
        }

        redirect('admin/manage_assessments');
    }

    public function save_assessment_part($assessment_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $part_name = trim($this->input->post('part_name'));
        $description = trim($this->input->post('description'));
        
        if (!empty($part_name)) {
            $data = array(
                'assessment_id' => $assessment_id,
                'part_name' => $part_name,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('assessment_parts', $data);
            $this->session->set_flashdata('success', 'Test part "' . htmlspecialchars($part_name) . '" created successfully!');
        }
        redirect('admin/manage_questions/' . $assessment_id);
    }

    public function delete_assessment_part($part_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $part = $this->db->get_where('assessment_parts', array('id' => $part_id))->row();
        if ($part) {
            $assessment_id = $part->assessment_id;
            
            $this->db->where('part_id', $part_id);
            $this->db->delete('questions');

            $this->db->where('id', $part_id);
            $this->db->delete('assessment_parts');

            $this->session->set_flashdata('success', 'Test part and its questions deleted successfully!');
            redirect('admin/manage_questions/' . $assessment_id);
            return;
        }
        redirect('admin/manage_assessments');
    }

    public function manage_questions($assessment_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $assessment = $this->db->get_where('assessments', array('id' => $assessment_id))->row();
        if (!$assessment) show_404();

        $parts = $this->db->get_where('assessment_parts', array('assessment_id' => $assessment_id))->result();

        foreach ($parts as &$p) {
            $this->db->where('part_id', $p->id);
            $p->question_count = $this->db->count_all_results('questions');
        }

        $selected_part_id = $this->input->get('part_id');

        $this->db->select('q.*, ap.part_name');
        $this->db->from('questions q');
        $this->db->join('assessment_parts ap', 'q.part_id = ap.id', 'left');
        $this->db->group_start();
        $this->db->where('q.assessment_id', $assessment_id);
        $this->db->or_group_start();
        $this->db->where('q.assessment_id', NULL);
        $this->db->group_start();
        $this->db->where('q.subject', $assessment->title);
        if (!empty($parts)) {
            $part_names = array_map(function($item) { return $item->part_name; }, $parts);
            $this->db->or_where_in('q.subject', $part_names);
        }
        $this->db->group_end();
        $this->db->group_end();
        $this->db->group_end();
        if ($selected_part_id) {
            $this->db->where('q.part_id', $selected_part_id);
        }
        $this->db->order_by('q.part_id', 'ASC');
        $this->db->order_by('q.question_number', 'ASC');
        $this->db->order_by('q.id', 'ASC');
        $questions = $this->db->get()->result();

        $data['assessment'] = $assessment;
        $data['parts'] = $parts;
        $data['questions'] = $questions;
        $data['selected_part_id'] = $selected_part_id;
        $data['page_title'] = 'Manage Parts & Questions - ' . $assessment->title;

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/manage_questions', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_question($assessment_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $assessment = $this->db->get_where('assessments', array('id' => $assessment_id))->row();
        if (!$assessment) show_404();

        $part_id = $this->input->post('part_id') ? (int)$this->input->post('part_id') : null;
        $subject_name = $assessment->title;
        if ($part_id) {
            $part = $this->db->get_where('assessment_parts', array('id' => $part_id))->row();
            if ($part) $subject_name = $part->part_name;
        }

        $this->db->select_max('question_number');
        if ($part_id) {
            $this->db->where('part_id', $part_id);
        } else {
            $this->db->where('assessment_id', $assessment_id);
        }
        $max_row = $this->db->get('questions')->row();
        $next_q_num = ($max_row && $max_row->question_number) ? ($max_row->question_number + 1) : 1;

        $image_path = null;
        $upload_dir = FCPATH . 'assets/uploads/questions/';
        if (!file_exists($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');

        if (!empty($_FILES['question_image']['name']) && $_FILES['question_image']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $filename = 'q_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image']['tmp_name'], $upload_dir . $filename)) {
                    $image_path = 'assets/uploads/questions/' . $filename;
                }
            }
        }

        $image_path_2 = null;
        if (!empty($_FILES['question_image_2']['name']) && $_FILES['question_image_2']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['question_image_2']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                $filename = 'q2_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image_2']['tmp_name'], $upload_dir . $filename)) {
                    $image_path_2 = 'assets/uploads/questions/' . $filename;
                }
            }
        }

        $option_images = array(
            'option_a_image' => null,
            'option_b_image' => null,
            'option_c_image' => null,
            'option_d_image' => null
        );

        foreach (array('a', 'b', 'c', 'd') as $opt) {
            $field = 'option_' . $opt . '_image';
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $file_ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, $allowed)) {
                    $filename = 'opt_' . $opt . '_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $filename)) {
                        $option_images[$field] = 'assets/uploads/questions/' . $filename;
                    }
                }
            }
        }

        $data = array(
            'assessment_id' => $assessment_id,
            'part_id' => $part_id,
            'subject' => $subject_name,
            'question_number' => $next_q_num,
            'question_text' => $this->input->post('question_text'),
            'image_path' => $image_path,
            'image_path_2' => $image_path_2,
            'option_a' => $this->input->post('option_a'),
            'option_b' => $this->input->post('option_b'),
            'option_c' => $this->input->post('option_c'),
            'option_d' => $this->input->post('option_d'),
            'option_a_image' => $option_images['option_a_image'],
            'option_b_image' => $option_images['option_b_image'],
            'option_c_image' => $option_images['option_c_image'],
            'option_d_image' => $option_images['option_d_image'],
            'correct_option' => strtoupper(trim($this->input->post('correct_option')))
        );

        $this->db->insert('questions', $data);
        $this->session->set_flashdata('success', 'Question added successfully!');
        redirect('admin/manage_questions/' . $assessment_id);
    }

    public function edit_question($question_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $question = $this->db->get_where('questions', array('id' => $question_id))->row();
        if (!$question) show_404();

        $assessment = null;
        if ($question->assessment_id) {
            $assessment = $this->db->get_where('assessments', array('id' => $question->assessment_id))->row();
        }
        if (!$assessment) {
            $assessment = $this->db->get_where('assessments', array('title' => $question->subject))->row();
        }

        $parts = array();
        if ($assessment) {
            $parts = $this->db->get_where('assessment_parts', array('assessment_id' => $assessment->id))->result();
        }

        $data['question'] = $question;
        $data['assessment'] = $assessment;
        $data['parts'] = $parts;
        $data['page_title'] = 'Edit Question';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/question_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_question($question_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $question = $this->db->get_where('questions', array('id' => $question_id))->row();
        if (!$question) show_404();

        $part_id = $this->input->post('part_id') ? (int)$this->input->post('part_id') : null;
        $subject_name = $question->subject;
        if ($part_id) {
            $part = $this->db->get_where('assessment_parts', array('id' => $part_id))->row();
            if ($part) $subject_name = $part->part_name;
        }

        $image_path = $question->image_path;
        $image_path_2 = isset($question->image_path_2) ? $question->image_path_2 : null;

        if ($this->input->post('remove_image')) {
            if (!empty($question->image_path) && file_exists(FCPATH . $question->image_path)) {
                @unlink(FCPATH . $question->image_path);
            }
            $image_path = null;
        }

        if ($this->input->post('remove_image_2')) {
            if (!empty($question->image_path_2) && file_exists(FCPATH . $question->image_path_2)) {
                @unlink(FCPATH . $question->image_path_2);
            }
            $image_path_2 = null;
        }

        $upload_dir = FCPATH . 'assets/uploads/questions/';
        if (!file_exists($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');

        if (!empty($_FILES['question_image']['name']) && $_FILES['question_image']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                if (!empty($question->image_path) && file_exists(FCPATH . $question->image_path)) {
                    @unlink(FCPATH . $question->image_path);
                }
                $filename = 'q_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image']['tmp_name'], $upload_dir . $filename)) {
                    $image_path = 'assets/uploads/questions/' . $filename;
                }
            }
        }

        if (!empty($_FILES['question_image_2']['name']) && $_FILES['question_image_2']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['question_image_2']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed)) {
                if (!empty($question->image_path_2) && file_exists(FCPATH . $question->image_path_2)) {
                    @unlink(FCPATH . $question->image_path_2);
                }
                $filename = 'q2_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image_2']['tmp_name'], $upload_dir . $filename)) {
                    $image_path_2 = 'assets/uploads/questions/' . $filename;
                }
            }
        }

        $option_images = array(
            'option_a_image' => isset($question->option_a_image) ? $question->option_a_image : null,
            'option_b_image' => isset($question->option_b_image) ? $question->option_b_image : null,
            'option_c_image' => isset($question->option_c_image) ? $question->option_c_image : null,
            'option_d_image' => isset($question->option_d_image) ? $question->option_d_image : null,
        );

        foreach (array('a', 'b', 'c', 'd') as $opt) {
            $field = 'option_' . $opt . '_image';
            $remove_field = 'remove_option_' . $opt . '_image';

            if ($this->input->post($remove_field)) {
                if (!empty($question->$field) && file_exists(FCPATH . $question->$field)) {
                    @unlink(FCPATH . $question->$field);
                }
                $option_images[$field] = null;
            }

            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $file_ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, $allowed)) {
                    if (!empty($question->$field) && file_exists(FCPATH . $question->$field)) {
                        @unlink(FCPATH . $question->$field);
                    }
                    $filename = 'opt_' . $opt . '_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $filename)) {
                        $option_images[$field] = 'assets/uploads/questions/' . $filename;
                    }
                }
            }
        }

        $data = array(
            'part_id' => $part_id,
            'subject' => $subject_name,
            'question_text' => $this->input->post('question_text'),
            'image_path' => $image_path,
            'image_path_2' => $image_path_2,
            'option_a' => $this->input->post('option_a'),
            'option_b' => $this->input->post('option_b'),
            'option_c' => $this->input->post('option_c'),
            'option_d' => $this->input->post('option_d'),
            'option_a_image' => $option_images['option_a_image'],
            'option_b_image' => $option_images['option_b_image'],
            'option_c_image' => $option_images['option_c_image'],
            'option_d_image' => $option_images['option_d_image'],
            'correct_option' => strtoupper(trim($this->input->post('correct_option')))
        );

        $this->db->where('id', $question_id);
        $this->db->update('questions', $data);

        $redirect_id = $question->assessment_id;
        if (!$redirect_id) {
            $assessment = $this->db->get_where('assessments', array('title' => $question->subject))->row();
            $redirect_id = $assessment ? $assessment->id : '';
        }

        $this->session->set_flashdata('success', 'Question updated successfully!');
        if ($redirect_id) {
            redirect('admin/manage_questions/' . $redirect_id);
        } else {
            redirect('admin/manage_assessments');
        }
    }

    public function delete_question($question_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $question = $this->db->get_where('questions', array('id' => $question_id))->row();
        if ($question) {
            if (!empty($question->image_path) && file_exists(FCPATH . $question->image_path)) {
                @unlink(FCPATH . $question->image_path);
            }
            foreach (array('option_a_image', 'option_b_image', 'option_c_image', 'option_d_image') as $opt_img_col) {
                if (!empty($question->$opt_img_col) && file_exists(FCPATH . $question->$opt_img_col)) {
                    @unlink(FCPATH . $question->$opt_img_col);
                }
            }

            $redirect_id = $question->assessment_id;
            if (!$redirect_id) {
                $assessment = $this->db->get_where('assessments', array('title' => $question->subject))->row();
                if ($assessment) $redirect_id = $assessment->id;
            }

            $this->db->where('id', $question_id);
            $this->db->delete('questions');
            $this->session->set_flashdata('success', 'Question deleted successfully!');

            if ($redirect_id) {
                redirect('admin/manage_questions/' . $redirect_id);
                return;
            }
        }
        redirect('admin/manage_assessments');
    }

    public function export_assessment_excel($assessment_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $assessment = $this->db->get_where('assessments', array('id' => $assessment_id))->row();
        if (!$assessment) show_404();

        $parts = $this->db->get_where('assessment_parts', array('assessment_id' => $assessment_id))->result();

        $sections = array();
        if (!empty($parts)) {
            foreach ($parts as $p) {
                $this->db->group_start();
                $this->db->where('part_id', $p->id);
                $this->db->or_group_start();
                $this->db->where('assessment_id', NULL);
                $this->db->where('subject', $p->part_name);
                $this->db->group_end();
                $this->db->group_end();
                $this->db->order_by('question_number', 'ASC');
                $this->db->order_by('id', 'ASC');
                $q_list = $this->db->get('questions')->result();

                $sections[] = array(
                    'part_name' => $p->part_name,
                    'description' => $p->description,
                    'questions' => $q_list
                );
            }
        } else {
            $this->db->group_start();
            $this->db->where('assessment_id', $assessment_id);
            $this->db->or_group_start();
            $this->db->where('assessment_id', NULL);
            $this->db->where('subject', $assessment->title);
            $this->db->group_end();
            $this->db->group_end();
            $this->db->order_by('question_number', 'ASC');
            $this->db->order_by('id', 'ASC');
            $q_list = $this->db->get('questions')->result();

            $sections[] = array(
                'part_name' => $assessment->title,
                'description' => '',
                'questions' => $q_list
            );
        }

        $clean_title = preg_replace('/[^A-Za-z0-9_]+/', '_', $assessment->title);
        $filename = $clean_title . '_Questions.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>' . htmlspecialchars(substr($assessment->title, 0, 30)) . '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>';
        echo 'body { font-family: Calibri, Arial, sans-serif; }';
        echo '.test-title { font-size: 18pt; font-weight: bold; color: #0f172a; background-color: #f1f5f9; padding: 12px; height: 35px; }';
        echo '.section-title { font-size: 14pt; font-weight: bold; color: #0284c7; background-color: #e0f2fe; padding: 8px; height: 28px; }';
        echo '.col-header { font-weight: bold; background-color: #f8fafc; color: #334155; height: 24px; }';
        echo '.q-cell { border: 1px solid #cbd5e1; vertical-align: top; padding: 6px; }';
        echo '.correct-opt { font-weight: bold; color: #16a34a; background-color: #f0fdf4; text-align: center; border: 1px solid #cbd5e1; }';
        echo '</style></head>';
        echo '<body>';
        echo '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">';
        
        // Test Name Header
        echo '<tr><td colspan="7" class="test-title">' . htmlspecialchars($assessment->title) . '</td></tr>';
        echo '<tr><td colspan="7"></td></tr>';

        foreach ($sections as $sec) {
            // Section Name Header
            echo '<tr><td colspan="7" class="section-title">Section: ' . htmlspecialchars($sec['part_name']) . '</td></tr>';
            echo '<tr><td colspan="7"></td></tr>';

            // Options & Correct Option Header Row
            echo '<tr class="col-header">';
            echo '<th class="q-cell" style="width: 50px;">Q#</th>';
            echo '<th class="q-cell" style="width: 380px;">Question</th>';
            echo '<th class="q-cell" style="width: 180px;">Option A</th>';
            echo '<th class="q-cell" style="width: 180px;">Option B</th>';
            echo '<th class="q-cell" style="width: 180px;">Option C</th>';
            echo '<th class="q-cell" style="width: 180px;">Option D</th>';
            echo '<th class="q-cell" style="width: 120px;">Correct Option</th>';
            echo '</tr>';

            if (!empty($sec['questions'])) {
                $q_idx = 1;
                foreach ($sec['questions'] as $q) {
                    echo '<tr>';
                    echo '<td class="q-cell" style="text-align: center; font-weight: bold;">' . ($q->question_number ? $q->question_number : $q_idx) . '</td>';
                    echo '<td class="q-cell">' . htmlspecialchars($q->question_text) . '</td>';
                    echo '<td class="q-cell">' . htmlspecialchars($q->option_a) . '</td>';
                    echo '<td class="q-cell">' . htmlspecialchars($q->option_b) . '</td>';
                    echo '<td class="q-cell">' . htmlspecialchars($q->option_c) . '</td>';
                    echo '<td class="q-cell">' . htmlspecialchars($q->option_d) . '</td>';
                    echo '<td class="correct-opt">' . htmlspecialchars(strtoupper($q->correct_option)) . '</td>';
                    echo '</tr>';
                    $q_idx++;
                }
            } else {
                echo '<tr><td colspan="7" class="q-cell" style="color: #64748b; font-style: italic;">No questions added for this section yet.</td></tr>';
            }

            echo '<tr><td colspan="7"></td></tr>';
        }

        echo '</table>';
        echo '</body></html>';
        exit;
    }

    // ==========================================
    // ORDERS MANAGEMENT
    // ==========================================

    public function orders($filter = 'pending') {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $filter = strtolower(trim($filter));
        if (!in_array($filter, array('pending', 'completed', 'rejected', 'all'))) {
            $filter = 'pending';
        }

        // Get count badges
        $pending_count = $this->db->where('payment_status', 'pending')->count_all_results('orders');
        $completed_count = $this->db->where('payment_status', 'completed')->count_all_results('orders');
        $rejected_count = $this->db->where('payment_status', 'rejected')->count_all_results('orders');
        $all_count = $this->db->count_all('orders');

        // Fetch orders according to filter
        $this->db->select('orders.*, users.username, users.role as user_role');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'left');
        
        if ($filter !== 'all') {
            $this->db->where('orders.payment_status', $filter);
        }
        
        $this->db->order_by('orders.created_at', 'DESC');
        $orders = $this->db->get()->result();

        // Attach order items for each order
        foreach ($orders as &$order) {
            $order->items = $this->db->get_where('order_items', array('order_id' => $order->id))->result();
        }

        $data['orders'] = $orders;
        $data['filter'] = $filter;
        $data['counts'] = array(
            'pending' => $pending_count,
            'completed' => $completed_count,
            'rejected' => $rejected_count,
            'all' => $all_count
        );
        $data['page_title'] = 'Manage Customer Orders';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/orders', $data);
        $this->load->view('admin/includes/footer');
    }

    public function approve_order($order_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $order = $this->db->get_where('orders', array('id' => $order_id))->row();
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
            return;
        }

        // Update order status to completed
        $this->db->where('id', $order_id);
        $this->db->update('orders', array('payment_status' => 'completed'));

        // Grant test permissions for any assessment items in the order
        $items = $this->db->get_where('order_items', array('order_id' => $order_id))->result();
        $granted_assessments = array();

        foreach ($items as $item) {
            if ($item->item_type === 'assessment') {
                $test_title = $item->item_name;
                $existing_perm = $this->db->get_where('user_test_permissions', array('user_id' => $order->user_id, 'test_title' => $test_title))->row();
                if ($existing_perm) {
                    $this->db->where('id', $existing_perm->id);
                    $this->db->update('user_test_permissions', array('status' => 'enabled', 'updated_at' => date('Y-m-d H:i:s')));
                } else {
                    $this->db->insert('user_test_permissions', array(
                        'user_id' => $order->user_id,
                        'test_title' => $test_title,
                        'status' => 'enabled',
                        'granted_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ));
                }
                $granted_assessments[] = $test_title;
            } elseif ($item->item_type === 'course') {
                $tier_key = 'basic';
                if (preg_match('/\(([^)]+)\s+Tier\)/i', $item->item_name, $m_tier)) {
                    $tier_key = strtolower(trim($m_tier[1]));
                }
                
                $existing_enrollment = $this->db->get_where('user_enrollments', array(
                    'user_id' => $order->user_id,
                    'entity_type' => 'course',
                    'entity_id' => $item->item_id
                ))->row();

                if ($existing_enrollment) {
                    $this->db->where('id', $existing_enrollment->id);
                    $this->db->update('user_enrollments', array('tier_key' => $tier_key));
                } else {
                    $this->db->insert('user_enrollments', array(
                        'user_id' => $order->user_id,
                        'entity_type' => 'course',
                        'entity_id' => $item->item_id,
                        'tier_key' => $tier_key,
                        'enrolled_at' => date('Y-m-d H:i:s')
                    ));
                }
            }
        }

        $msg = 'Order <strong>' . htmlspecialchars($order->order_number) . '</strong> approved successfully and moved to <strong>Completed Orders</strong>!';
        if (!empty($granted_assessments)) {
            $msg .= ' Test access granted for: ' . htmlspecialchars(implode(', ', $granted_assessments)) . '.';
        }

        $this->session->set_flashdata('success', $msg);
        redirect('admin/orders/completed');
    }

    public function reject_order($order_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $order = $this->db->get_where('orders', array('id' => $order_id))->row();
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
            return;
        }

        // Update order status to rejected
        $this->db->where('id', $order_id);
        $this->db->update('orders', array('payment_status' => 'rejected'));

        $this->session->set_flashdata('success', 'Order <strong>' . htmlspecialchars($order->order_number) . '</strong> has been rejected.');
        redirect('admin/orders/rejected');
    }

    // ==========================================
    // CERTIFICATION MANAGEMENT
    // ==========================================

    public function certificates() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $search = trim($this->input->get('search'));
        $status_filter = trim($this->input->get('status'));
        if (!in_array($status_filter, array('granted', 'revoked', 'all'))) {
            $status_filter = 'all';
        }

        $this->db->select('course_certificates.*, users.first_name, users.last_name, users.username, users.email, courses.title as course_title');
        $this->db->from('course_certificates');
        $this->db->join('users', 'users.id = course_certificates.user_id', 'left');
        $this->db->join('courses', 'courses.id = course_certificates.course_id', 'left');

        if ($status_filter !== 'all') {
            $this->db->where('course_certificates.status', $status_filter);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('users.first_name', $search);
            $this->db->or_like('users.last_name', $search);
            $this->db->or_like('users.username', $search);
            $this->db->or_like('users.email', $search);
            $this->db->or_like('courses.title', $search);
            $this->db->or_like('course_certificates.credential_id', $search);
            $this->db->group_end();
        }

        $this->db->order_by('course_certificates.issue_date', 'DESC');
        $this->db->order_by('course_certificates.id', 'DESC');
        $certificates = $this->db->get()->result();

        // Fetch students & courses for shortcut issue form (synced with published courses, newest first matching /admin/courses)
        $this->db->where_in('role', array('student', 'Student'));
        $this->db->order_by('first_name', 'ASC');
        $students = $this->db->get('users')->result();

        $this->db->order_by('id', 'DESC');
        $courses = $this->db->get('courses')->result();

        $data['certificates'] = $certificates;
        $data['students'] = $students;
        $data['courses'] = $courses;
        $data['search'] = $search;
        $data['status_filter'] = $status_filter;
        $data['page_title'] = 'Certification Management';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/certificates', $data);
        $this->load->view('admin/includes/footer');
    }

    public function process_issue_certificate() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $user_id = (int)$this->input->post('user_id');
        $course_id = (int)$this->input->post('course_id');
        $tier_key = $this->input->post('tier_key') ? $this->input->post('tier_key') : 'basic';

        if (!$user_id || !$course_id) {
            $this->session->set_flashdata('error', 'Please select both a student and a course.');
            redirect('admin/certificates');
            return;
        }

        $existing = $this->db->get_where('course_certificates', array('user_id' => $user_id, 'course_id' => $course_id))->row();

        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('course_certificates', array(
                'status' => 'granted',
                'issue_date' => date('Y-m-d'),
                'tier_key' => $tier_key
            ));
            $msg = 'Certificate re-granted for selected course.';
        } else {
            $credential_id = 'AMZ-' . date('Y') . '-CERT-' . rand(1000, 9999);
            while ($this->db->get_where('course_certificates', array('credential_id' => $credential_id))->num_rows() > 0) {
                $credential_id = 'AMZ-' . date('Y') . '-CERT-' . rand(1000, 9999);
            }

            $this->db->insert('course_certificates', array(
                'user_id' => $user_id,
                'course_id' => $course_id,
                'credential_id' => $credential_id,
                'tier_key' => $tier_key,
                'status' => 'granted',
                'issue_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s')
            ));
            $msg = 'Certificate issued successfully! Credential ID: ' . $credential_id;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('admin/certificates');
    }

    public function toggle_certificate_status($cert_id, $status = 'revoked') {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $status = ($status === 'granted') ? 'granted' : 'revoked';

        $cert = $this->db->get_where('course_certificates', array('id' => $cert_id))->row();
        if (!$cert) {
            $this->session->set_flashdata('error', 'Certificate record not found.');
            redirect('admin/certificates');
            return;
        }

        $this->db->where('id', $cert_id);
        $this->db->update('course_certificates', array(
            'status' => $status,
            'issue_date' => ($status === 'granted') ? date('Y-m-d') : $cert->issue_date
        ));

        $msg = ($status === 'revoked') 
            ? 'Certificate <strong>' . htmlspecialchars($cert->credential_id) . '</strong> has been revoked.' 
            : 'Certificate <strong>' . htmlspecialchars($cert->credential_id) . '</strong> access re-granted.';

        $this->session->set_flashdata('success', $msg);
        redirect('admin/certificates');
    }

    public function view_certificate($credential_id = null) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        if (empty($credential_id)) {
            $this->session->set_flashdata('error', 'No credential ID provided.');
            redirect('admin/certificates');
            return;
        }

        $cert = $this->db->get_where('course_certificates', array('credential_id' => $credential_id))->row();
        if (!$cert) {
            $this->session->set_flashdata('error', 'Certificate not found.');
            redirect('admin/certificates');
            return;
        }

        $user = $this->db->get_where('users', array('id' => $cert->user_id))->row();
        $course = $this->db->get_where('courses', array('id' => $cert->course_id))->row();

        $verify_url = site_url('verify/' . $cert->credential_id);
        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($verify_url);

        $data['certificate'] = $cert;
        $data['user'] = $user;
        $data['course'] = $course;
        $data['verify_url'] = $verify_url;
        $data['qr_url'] = $qr_url;

        $this->load->view('student/certificate_pdf', $data);
    }

    // ==========================================
    // COURSE MATERIALS (PDF ONLY) MANAGEMENT
    // ==========================================

    public function course_materials($course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $course = $this->db->get_where('courses', array('id' => $course_id))->row();
        if (!$course) {
            $this->session->set_flashdata('error', 'Course not found.');
            redirect('admin/courses');
            return;
        }

        $tiers = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', array('course_id' => $course_id))->result();
        
        $this->db->where('course_id', $course_id);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        $materials = $this->db->get('course_modules')->result();

        $data['course'] = $course;
        $data['tiers'] = $tiers;
        $data['materials'] = $materials;
        $data['page_title'] = 'Manage Course PDF Materials - ' . $course->title;

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/course_materials', $data);
        $this->load->view('admin/includes/footer');
    }

    public function upload_course_material($course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $module_tier = $this->input->post('module_tier') ? $this->input->post('module_tier') : 'basic';
        $description = trim($this->input->post('description'));

        if (empty($title)) {
            $this->session->set_flashdata('error', 'Please enter a title for the PDF material.');
            redirect('admin/course_materials/' . $course_id);
            return;
        }

        if (empty($_FILES['pdf_file']['name'])) {
            $this->session->set_flashdata('error', 'Please select a PDF file to upload.');
            redirect('admin/course_materials/' . $course_id);
            return;
        }

        // Verify PDF file extension strictly
        $file_ext = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'pdf') {
            $this->session->set_flashdata('error', 'Invalid file type. Only PDF files (.pdf) are supported!');
            redirect('admin/course_materials/' . $course_id);
            return;
        }

        // Config upload path for PDF
        $config['upload_path'] = './assets/uploads/materials/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 51200; // 50MB
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('pdf_file')) {
            $upload_error = $this->upload->display_errors('', '');
            $this->session->set_flashdata('error', 'PDF upload failed: ' . $upload_error);
            redirect('admin/course_materials/' . $course_id);
            return;
        }

        $upload_data = $this->upload->data();
        $file_path = 'assets/uploads/materials/' . $upload_data['file_name'];

        $material_data = array(
            'course_id' => $course_id,
            'module_tier' => $module_tier,
            'title' => $title,
            'content_type' => 'pdf',
            'file_path' => $file_path,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('course_modules', $material_data);

        $this->session->set_flashdata('success', 'PDF material "' . htmlspecialchars($title) . '" uploaded successfully!');
        redirect('admin/course_materials/' . $course_id);
    }

    public function delete_course_material($material_id, $course_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $material = $this->db->get_where('course_modules', array('id' => $material_id, 'course_id' => $course_id))->row();
        if ($material) {
            if (!empty($material->file_path) && file_exists(FCPATH . $material->file_path)) {
                @unlink(FCPATH . $material->file_path);
            }
            $this->db->where('id', $material_id);
            $this->db->delete('course_modules');
            $this->session->set_flashdata('success', 'PDF course material removed.');
        }

        redirect('admin/course_materials/' . $course_id);
    }

    // ==========================================
    // BOOK DELIVERY (TEMPORARY PDF LINKS WITH TTL)
    // ==========================================

    public function book_delivery() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $uploaded_pdfs = array();

        // Fetch product PDF books
        $this->db->where('file_path IS NOT NULL AND file_path != ""');
        $this->db->order_by('title', 'ASC');
        $product_pdfs = $this->db->get('products')->result();
        
        if(!empty($product_pdfs)) {
            foreach ($product_pdfs as $p_pdf) {
                $obj = new stdClass();
                $obj->title = $p_pdf->title;
                $obj->file_path = $p_pdf->file_path;
                $obj->course_title = 'Shop E-Book';
                $uploaded_pdfs[] = $obj;
            }
        }

        // Fetch generated temporary links
        $this->db->order_by('created_at', 'DESC');
        $links = $this->db->get('temporary_download_links')->result();

        $data['uploaded_pdfs'] = $uploaded_pdfs;
        $data['links'] = $links;
        $data['page_title'] = 'Book Delivery - Temporary PDF Link Generator';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/book_delivery', $data);
        $this->load->view('admin/includes/footer');
    }

    public function create_download_link() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $selected_pdf = $this->input->post('selected_pdf');
        $ttl_seconds = intval($this->input->post('ttl_seconds'));
        $custom_file_name = trim($this->input->post('custom_file_name'));

        if ($ttl_seconds <= 0) {
            $ttl_seconds = 3600; // Default 1 hour = 3600 seconds
        }

        $file_path = '';
        $file_name = '';

        // Check if custom file uploaded or selected from dropdown
        if (!empty($_FILES['new_pdf']['name'])) {
            $config['upload_path'] = './assets/uploads/materials/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 51200;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('new_pdf')) {
                $upload_data = $this->upload->data();
                $file_path = 'assets/uploads/materials/' . $upload_data['file_name'];
                $file_name = !empty($custom_file_name) ? $custom_file_name : $_FILES['new_pdf']['name'];
            } else {
                $this->session->set_flashdata('error', 'PDF upload failed: ' . $this->upload->display_errors('', ''));
                redirect('admin/book_delivery');
                return;
            }
        } elseif (!empty($selected_pdf)) {
            // Selected existing PDF file from list
            $file_path = $selected_pdf;
            $file_name = !empty($custom_file_name) ? $custom_file_name : basename($selected_pdf);
        } else {
            $this->session->set_flashdata('error', 'Please select a PDF file or upload a new PDF.');
            redirect('admin/book_delivery');
            return;
        }

        // Generate unique 16-char token
        $token = strtolower(substr(md5(uniqid(mt_rand(), true)), 0, 16));
        $expires_at = date('Y-m-d H:i:s', time() + $ttl_seconds);

        $link_data = array(
            'token' => $token,
            'file_path' => $file_path,
            'file_name' => $file_name,
            'ttl_seconds' => $ttl_seconds,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('temporary_download_links', $link_data);

        $full_link = site_url('download/book?token=' . $token);
        $this->session->set_flashdata('success', 'Temporary link created successfully! URL: <strong>' . $full_link . '</strong> (TTL: ' . number_format($ttl_seconds) . ' seconds)');
        redirect('admin/book_delivery');
    }

    public function delete_download_link($link_id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', $link_id);
        $this->db->delete('temporary_download_links');

        $this->session->set_flashdata('success', 'Temporary download link deleted.');
        redirect('admin/book_delivery');
    }

    // ==========================================
    // ARTICLE / BLOG MANAGEMENT
    // ==========================================

    public function articles() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('articles');
        $data['articles'] = $query ? $query->result() : array();
        $data['page_title'] = 'Manage Articles';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/articles', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_article() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Article';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/article_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_article() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Ensure slug uniqueness
        $check = $this->db->get_where('articles', array('slug' => $slug))->num_rows();
        if ($check > 0) {
            $slug .= '-' . time();
        }

        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/articles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'art_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $image_path = 'assets/uploads/articles/' . $new_filename;
                }
            }
        }

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $now = date('Y-m-d H:i:s');
        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'General',
            'image_url' => $image_path,
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published',
            'created_at' => $now,
            'published_at' => $now
        );

        $this->db->insert('articles', $data);

        $this->session->set_flashdata('success', 'Article created and saved successfully!');
        redirect('admin/articles');
    }

    public function edit_article($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $article = $this->db->get_where('articles', array('id' => $id))->row();
        if (!$article) {
            $this->session->set_flashdata('error', 'Article not found.');
            redirect('admin/articles');
            return;
        }

        $data['article'] = $article;
        $data['page_title'] = 'Edit Article';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/article_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_article($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        $excerpt = trim($this->input->post('excerpt'));
        if (empty($excerpt)) {
            $raw_content = strip_tags($this->input->post('content'));
            $excerpt = (mb_strlen($raw_content) > 120) ? mb_substr($raw_content, 0, 120) . '...' : $raw_content;
        }

        $data = array(
            'title' => $title,
            'slug' => $slug,
            'author' => !empty($this->input->post('author')) ? trim($this->input->post('author')) : 'Alpha Mindz Team',
            'category' => !empty($this->input->post('category')) ? trim($this->input->post('category')) : 'General',
            'excerpt' => $excerpt,
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status') ? $this->input->post('status') : 'published'
        );

        if (!empty($_FILES['image']['name'])) {
            $upload_dir = FCPATH . 'assets/uploads/articles/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_exts = array('jpg', 'jpeg', 'png', 'webp', 'gif');
            if (in_array($ext, $allowed_exts)) {
                $new_filename = 'art_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                    $data['image_url'] = 'assets/uploads/articles/' . $new_filename;
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('articles', $data);

        $this->session->set_flashdata('success', 'Article updated successfully!');
        redirect('admin/articles');
    }

    public function delete_article($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', $id);
        $this->db->delete('articles');

        $this->session->set_flashdata('success', 'Article deleted successfully.');
        redirect('admin/articles');
    }

    public function toggle_article_status($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $article = $this->db->get_where('articles', array('id' => $id))->row();
        if ($article) {
            $new_status = ($article->status === 'published') ? 'draft' : 'published';
            $this->db->where('id', $id);
            $this->db->update('articles', array('status' => $new_status));
            $this->session->set_flashdata('success', 'Article status updated to ' . ucfirst($new_status));
        }
        redirect('admin/articles');
    }

    public function latest_news() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('latest_news');
        $data['latest_news'] = $query ? $query->result() : array();
        $data['page_title'] = 'Manage Latest News';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/latest_news', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_latest_news() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add Latest News';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/latest_news_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_latest_news() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data = array(
            'title' => trim($this->input->post('title')),
            'tag' => trim($this->input->post('tag')),
            'tag_color' => trim($this->input->post('tag_color')),
            'link' => trim($this->input->post('link')),
            'image_url' => trim($this->input->post('image_url')),
            'news_date' => trim($this->input->post('news_date')),
            'status' => trim($this->input->post('status'))
        );
        $this->db->insert('latest_news', $data);
        $this->session->set_flashdata('success', 'News added successfully.');
        redirect('admin/latest_news');
    }

    public function edit_latest_news($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data['news'] = $this->db->get_where('latest_news', array('id' => $id))->row();
        $data['page_title'] = 'Edit Latest News';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/latest_news_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_latest_news($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data = array(
            'title' => trim($this->input->post('title')),
            'tag' => trim($this->input->post('tag')),
            'tag_color' => trim($this->input->post('tag_color')),
            'link' => trim($this->input->post('link')),
            'image_url' => trim($this->input->post('image_url')),
            'news_date' => trim($this->input->post('news_date')),
            'status' => trim($this->input->post('status'))
        );
        $this->db->where('id', $id);
        $this->db->update('latest_news', $data);
        $this->session->set_flashdata('success', 'News updated successfully.');
        redirect('admin/latest_news');
    }

    public function delete_latest_news($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', $id);
        $this->db->delete('latest_news');
        $this->session->set_flashdata('success', 'News deleted successfully.');
        redirect('admin/latest_news');
    }

    public function main_website() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $hero_row = $this->db->get_where('system_settings', array('setting_key' => 'home_hero_image'))->row();
        $data['hero_image'] = ($hero_row && !empty($hero_row->setting_value)) ? $hero_row->setting_value : '';

        $video_row = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video'))->row();
        $data['promo_video'] = ($video_row && !empty($video_row->setting_value)) ? $video_row->setting_value : '';

        $video_type_row = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video_type'))->row();
        $data['promo_video_type'] = ($video_type_row && !empty($video_type_row->setting_value)) ? $video_type_row->setting_value : 'file';

        $data['page_title'] = 'Main Website Management';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/main_website', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_promo_video() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $source_type = $this->input->post('source_type'); // 'upload' or 'url'

        if ($source_type === 'url') {
            $video_url = trim($this->input->post('video_url'));
            if (empty($video_url)) {
                $this->session->set_flashdata('error', 'Please provide a valid YouTube / Vimeo / MP4 URL.');
                redirect('admin/main_website');
            }

            // Save as URL/YouTube
            $this->save_setting('home_promo_video', $video_url);
            $this->save_setting('home_promo_video_type', 'youtube');
            $this->session->set_flashdata('success', 'Promotional video URL updated successfully!');
            redirect('admin/main_website');
        }

        // File upload
        if (!empty($_FILES['promo_video']['name'])) {
            $upload_path = './assets/uploads/website/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'mp4|webm|mov|mkv|ogg';
            $config['max_size']      = 61440; // 60MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('promo_video')) {
                $upload_data   = $this->upload->data();
                $relative_path = 'assets/uploads/website/' . $upload_data['file_name'];

                $existing = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video'))->row();
                if ($existing && !empty($existing->setting_value) && strpos($existing->setting_value, 'assets/uploads/website/') !== false && file_exists(FCPATH . $existing->setting_value)) {
                    @unlink(FCPATH . $existing->setting_value);
                }

                $this->save_setting('home_promo_video', $relative_path);
                $this->save_setting('home_promo_video_type', 'file');

                $this->session->set_flashdata('success', 'Promotional showcase video file uploaded and published successfully!');
            } else {
                $error_msg = strip_tags($this->upload->display_errors());
                $this->session->set_flashdata('error', 'Failed to upload video: ' . $error_msg);
            }
        } else {
            $this->session->set_flashdata('error', 'Please choose a video file to upload or enter a video URL.');
        }

        redirect('admin/main_website');
    }

    private function save_setting($key, $val) {
        $now = date('Y-m-d H:i:s');
        $existing = $this->db->get_where('system_settings', array('setting_key' => $key))->row();
        if ($existing) {
            $this->db->where('id', $existing->id)->update('system_settings', array(
                'setting_value' => $val,
                'updated_at'    => $now
            ));
        } else {
            $this->db->insert('system_settings', array(
                'setting_key'   => $key,
                'setting_value' => $val,
                'created_at'    => $now,
                'updated_at'    => $now
            ));
        }
    }

    public function reset_promo_video() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $existing = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video'))->row();
        if ($existing) {
            if (!empty($existing->setting_value) && strpos($existing->setting_value, 'assets/uploads/website/') !== false && file_exists(FCPATH . $existing->setting_value)) {
                @unlink(FCPATH . $existing->setting_value);
            }
            $this->db->where('id', $existing->id)->update('system_settings', array(
                'setting_value' => '',
                'updated_at'    => date('Y-m-d H:i:s')
            ));
        }

        $this->save_setting('home_promo_video_type', 'file');

        $this->session->set_flashdata('success', 'Promotional video reset to default (assets/video/index.mp4).');
        redirect('admin/main_website');
    }

    public function update_hero_image() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        if (!empty($_FILES['hero_image']['name'])) {
            $upload_path = './assets/uploads/website/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp|gif';
            $config['max_size']      = 12288; // 12MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('hero_image')) {
                $upload_data   = $this->upload->data();
                $relative_path = 'assets/uploads/website/' . $upload_data['file_name'];

                $existing = $this->db->get_where('system_settings', array('setting_key' => 'home_hero_image'))->row();
                if ($existing) {
                    if (!empty($existing->setting_value) && strpos($existing->setting_value, 'assets/uploads/website/') !== false && file_exists(FCPATH . $existing->setting_value)) {
                        @unlink(FCPATH . $existing->setting_value);
                    }
                    $this->db->where('id', $existing->id)->update('system_settings', array(
                        'setting_value' => $relative_path,
                        'updated_at'    => date('Y-m-d H:i:s')
                    ));
                } else {
                    $this->db->insert('system_settings', array(
                        'setting_key'   => 'home_hero_image',
                        'setting_value' => $relative_path,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ));
                }

                $this->session->set_flashdata('success', 'Homepage hero photo updated successfully!');
            } else {
                $error_msg = strip_tags($this->upload->display_errors());
                $this->session->set_flashdata('error', 'Failed to upload photo: ' . $error_msg);
            }
        } else {
            $this->session->set_flashdata('error', 'Please choose an image file to upload.');
        }

        redirect('admin/main_website');
    }

    public function reset_hero_image() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $existing = $this->db->get_where('system_settings', array('setting_key' => 'home_hero_image'))->row();
        if ($existing) {
            if (!empty($existing->setting_value) && strpos($existing->setting_value, 'assets/uploads/website/') !== false && file_exists(FCPATH . $existing->setting_value)) {
                @unlink(FCPATH . $existing->setting_value);
            }
            $this->db->where('id', $existing->id)->update('system_settings', array(
                'setting_value' => '',
                'updated_at'    => date('Y-m-d H:i:s')
            ));
        }

        $this->session->set_flashdata('success', 'Homepage hero photo has been reset to the default photo.');
        redirect('admin/main_website');
    }

    public function about() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $defaults = array(
            'about_hero_tag'        => 'Our Story',
            'about_hero_title'      => 'About AlphaMindz',
            'about_hero_subtitle'   => 'Empowering minds, inspiring futures, and motivating individuals to reach their highest potential since 2007.',
            'about_team_heading'    => 'Meet Our Team',
            'about_team_subheading' => 'The visionary leaders and dedicated professionals driving our mission forward across our regional hubs.',
            'about_solan_title'     => 'Solan Branch (Himachal Pradesh)',
            'about_solan_address'   => 'First Floor, Satish Complex, Kotla Nala, Solan, Himachal Pradesh 173212',
            'about_solan_map_link'  => 'https://www.google.com/maps/place/First+Floor,+Alpha+Mindz+Solan,+Satish+Complex,+Kotla+Nala,+Solan,+Himachal+Pradesh+173212/data=!4m2!3m1!1s0x390f8138573242bb:0x1e78d5e86a03c187!18m1!1e1',
            'about_goa_title'       => 'Goa Branch (Panaji)',
            'about_goa_address'     => '302, Sai Plaza, opp. Gomantak Press, St. Inez, Altinho, Panaji, Goa 403001',
            'about_goa_map_link'    => 'https://maps.app.goo.gl/orfvmi45fKQES2Mx6'
        );

        foreach ($defaults as $k => $def) {
            $row = $this->db->get_where('system_settings', array('setting_key' => $k))->row();
            $data[$k] = ($row && $row->setting_value !== null && $row->setting_value !== '') ? $row->setting_value : $def;
        }

        $this->db->order_by('branch_key', 'DESC');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $data['team_members'] = $this->db->get('about_team_members')->result();
        $data['page_title']   = 'Manage About Us';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/about', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_about_content() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $allowed_keys = array(
            'about_hero_tag',
            'about_hero_title',
            'about_hero_subtitle',
            'about_team_heading',
            'about_team_subheading',
            'about_solan_title',
            'about_solan_address',
            'about_solan_map_link',
            'about_goa_title',
            'about_goa_address',
            'about_goa_map_link'
        );

        $now = date('Y-m-d H:i:s');
        foreach ($allowed_keys as $key) {
            $val = trim($this->input->post($key));
            $existing = $this->db->get_where('system_settings', array('setting_key' => $key))->row();
            if ($existing) {
                $this->db->where('id', $existing->id)->update('system_settings', array(
                    'setting_value' => $val,
                    'updated_at'    => $now
                ));
            } else {
                $this->db->insert('system_settings', array(
                    'setting_key'   => $key,
                    'setting_value' => $val,
                    'created_at'    => $now,
                    'updated_at'    => $now
                ));
            }
        }

        $this->session->set_flashdata('success', 'About Us page content updated successfully!');
        redirect('admin/about');
    }

    public function add_team_member() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add Team Member';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/team_member_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_team_member($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $member = $this->db->get_where('about_team_members', array('id' => $id))->row();
        if (!$member) {
            $this->session->set_flashdata('error', 'Team member not found.');
            redirect('admin/about');
        }

        $data['member']     = $member;
        $data['page_title'] = 'Edit Team Member';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/team_member_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_team_member() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $name       = trim($this->input->post('name'));
        $role_title = trim($this->input->post('role_title'));

        if (empty($name) || empty($role_title)) {
            $this->session->set_flashdata('error', 'Name and Role Title are required.');
            redirect('admin/add_team_member');
        }

        $image_url = 'assets/images/team/manu.jpg';
        if (!empty($_FILES['member_image']['name'])) {
            $upload_path = './assets/uploads/website/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp|gif';
            $config['max_size']      = 6144; // 6MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('member_image')) {
                $upload_data = $this->upload->data();
                $image_url   = 'assets/uploads/website/' . $upload_data['file_name'];
            }
        }

        $now = date('Y-m-d H:i:s');
        $insert_data = array(
            'name'             => $name,
            'role_title'       => $role_title,
            'branch_key'       => ($this->input->post('branch_key') === 'goa') ? 'goa' : 'solan',
            'role_color_theme' => in_array($this->input->post('role_color_theme'), array('blue', 'orange', 'green', 'purple')) ? $this->input->post('role_color_theme') : 'blue',
            'bio'              => trim($this->input->post('bio')),
            'image_url'        => $image_url,
            'sort_order'       => (int)$this->input->post('sort_order'),
            'is_active'        => (int)$this->input->post('is_active'),
            'created_at'       => $now,
            'updated_at'       => $now
        );

        $this->db->insert('about_team_members', $insert_data);
        $this->session->set_flashdata('success', 'Team member added successfully!');
        redirect('admin/about');
    }

    public function update_team_member($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $member = $this->db->get_where('about_team_members', array('id' => $id))->row();
        if (!$member) {
            $this->session->set_flashdata('error', 'Team member not found.');
            redirect('admin/about');
        }

        $name       = trim($this->input->post('name'));
        $role_title = trim($this->input->post('role_title'));

        if (empty($name) || empty($role_title)) {
            $this->session->set_flashdata('error', 'Name and Role Title are required.');
            redirect('admin/edit_team_member/' . $id);
        }

        $image_url = $member->image_url;
        if (!empty($_FILES['member_image']['name'])) {
            $upload_path = './assets/uploads/website/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp|gif';
            $config['max_size']      = 6144;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('member_image')) {
                $upload_data = $this->upload->data();
                $new_image_url = 'assets/uploads/website/' . $upload_data['file_name'];

                if (!empty($member->image_url) && strpos($member->image_url, 'assets/uploads/website/') !== false && file_exists(FCPATH . $member->image_url)) {
                    @unlink(FCPATH . $member->image_url);
                }
                $image_url = $new_image_url;
            }
        }

        $update_data = array(
            'name'             => $name,
            'role_title'       => $role_title,
            'branch_key'       => ($this->input->post('branch_key') === 'goa') ? 'goa' : 'solan',
            'role_color_theme' => in_array($this->input->post('role_color_theme'), array('blue', 'orange', 'green', 'purple')) ? $this->input->post('role_color_theme') : 'blue',
            'bio'              => trim($this->input->post('bio')),
            'image_url'        => $image_url,
            'sort_order'       => (int)$this->input->post('sort_order'),
            'is_active'        => (int)$this->input->post('is_active'),
            'updated_at'       => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id)->update('about_team_members', $update_data);
        $this->session->set_flashdata('success', 'Team member updated successfully!');
        redirect('admin/about');
    }

    public function delete_team_member($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $member = $this->db->get_where('about_team_members', array('id' => $id))->row();
        if ($member) {
            if (!empty($member->image_url) && strpos($member->image_url, 'assets/uploads/website/') !== false && file_exists(FCPATH . $member->image_url)) {
                @unlink(FCPATH . $member->image_url);
            }
            $this->db->where('id', $id)->delete('about_team_members');
            $this->session->set_flashdata('success', 'Team member deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Team member not found.');
        }

        redirect('admin/about');
    }

    /* -------------------------------------------------------------
     * DOWNLOADABLE GUIDES & FREE RESOURCES MANAGEMENT
     * -----------------------------------------------------------*/
    public function downloadable_guides() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $cat_id = $this->input->get('category_id');

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'ASC');
        $categories = $this->db->get('guide_categories')->result();

        // Calculate count of files per category
        foreach ($categories as &$c) {
            $this->db->where('category_id', $c->id);
            $c->file_count = $this->db->count_all_results('guide_files');
        }

        $this->db->select('guide_files.*, guide_categories.title as category_title, guide_categories.slug as category_slug');
        $this->db->from('guide_files');
        $this->db->join('guide_categories', 'guide_categories.id = guide_files.category_id');
        if (!empty($cat_id)) {
            $this->db->where('guide_files.category_id', (int)$cat_id);
        }
        $this->db->order_by('guide_files.sort_order', 'ASC');
        $this->db->order_by('guide_files.id', 'DESC');
        $data['guide_files'] = $this->db->get()->result();

        $data['categories']         = $categories;
        $data['current_cat_id']     = $cat_id;
        $data['current_category']   = !empty($cat_id) ? $this->db->get_where('guide_categories', array('id' => (int)$cat_id))->row() : null;
        $data['total_files_count']  = $this->db->count_all('guide_files');
        $data['page_title']         = 'Manage Downloadable Guides';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/downloadable_guides', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_guide_category() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add Guide Category';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/guide_category_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_guide_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('guide_categories', array('id' => $id))->row();
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/downloadable_guides');
        }

        $data['category']   = $category;
        $data['page_title'] = 'Edit Guide Category';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/guide_category_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_guide_category() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title = trim($this->input->post('title'));
        $slug  = trim($this->input->post('slug'));

        if (empty($title) || empty($slug)) {
            $this->session->set_flashdata('error', 'Category Title and Slug are required.');
            redirect('admin/add_guide_category');
        }

        $slug = url_title($slug, '-', TRUE);

        // Check if slug already exists
        $chk = $this->db->get_where('guide_categories', array('slug' => $slug))->row();
        if ($chk) {
            $this->session->set_flashdata('error', 'A category with this URL slug already exists. Please choose a different slug.');
            redirect('admin/add_guide_category');
        }

        $now = date('Y-m-d H:i:s');
        $insert_data = array(
            'title'       => $title,
            'slug'        => $slug,
            'folder_name' => $title,
            'description' => trim($this->input->post('description')),
            'icon'        => trim($this->input->post('icon')) ?: 'ri-file-pdf-line',
            'badge'       => trim($this->input->post('badge')) ?: 'Guide',
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'created_at'  => $now,
            'updated_at'  => $now
        );

        $this->db->insert('guide_categories', $insert_data);
        $this->session->set_flashdata('success', 'Guide category created successfully!');
        redirect('admin/downloadable_guides');
    }

    public function update_guide_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('guide_categories', array('id' => $id))->row();
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/downloadable_guides');
        }

        $title = trim($this->input->post('title'));
        $slug  = trim($this->input->post('slug'));

        if (empty($title) || empty($slug)) {
            $this->session->set_flashdata('error', 'Category Title and Slug are required.');
            redirect('admin/edit_guide_category/' . $id);
        }

        $slug = url_title($slug, '-', TRUE);

        // Check slug collision
        $chk = $this->db->where('slug', $slug)->where('id !=', $id)->get('guide_categories')->row();
        if ($chk) {
            $this->session->set_flashdata('error', 'Another category is already using this URL slug.');
            redirect('admin/edit_guide_category/' . $id);
        }

        $update_data = array(
            'title'       => $title,
            'slug'        => $slug,
            'description' => trim($this->input->post('description')),
            'icon'        => trim($this->input->post('icon')) ?: 'ri-file-pdf-line',
            'badge'       => trim($this->input->post('badge')) ?: 'Guide',
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id)->update('guide_categories', $update_data);
        $this->session->set_flashdata('success', 'Guide category updated successfully!');
        redirect('admin/downloadable_guides');
    }

    public function delete_guide_category($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $category = $this->db->get_where('guide_categories', array('id' => $id))->row();
        if ($category) {
            // Delete all associated physical files uploaded via admin
            $files = $this->db->get_where('guide_files', array('category_id' => $id))->result();
            foreach ($files as $f) {
                if (strpos($f->file_path, 'assets/uploads/guides/') !== false && file_exists(FCPATH . $f->file_path)) {
                    @unlink(FCPATH . $f->file_path);
                }
            }
            $this->db->where('id', $id)->delete('guide_categories');
            $this->session->set_flashdata('success', 'Category and its files deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Category not found.');
        }

        redirect('admin/downloadable_guides');
    }

    public function add_guide_file() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $data['categories']  = $this->db->order_by('sort_order', 'ASC')->get('guide_categories')->result();
        $data['category_id'] = $this->input->get('category_id');
        $data['page_title']  = 'Upload Guide PDF';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/guide_file_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_guide_file($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $file = $this->db->get_where('guide_files', array('id' => $id))->row();
        if (!$file) {
            $this->session->set_flashdata('error', 'Guide file not found.');
            redirect('admin/downloadable_guides');
        }

        $data['file']        = $file;
        $data['categories']  = $this->db->order_by('sort_order', 'ASC')->get('guide_categories')->result();
        $data['page_title']  = 'Edit Guide PDF';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar', $data);
        $this->load->view('admin/guide_file_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_guide_file() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $title       = trim($this->input->post('title'));
        $category_id = (int)$this->input->post('category_id');

        if (empty($title) || empty($category_id)) {
            $this->session->set_flashdata('error', 'Guide Title and Category are required.');
            redirect('admin/add_guide_file');
        }

        if (empty($_FILES['pdf_file']['name'])) {
            $this->session->set_flashdata('error', 'Please select a PDF file to upload.');
            redirect('admin/add_guide_file?category_id=' . $category_id);
        }

        $upload_dir = './assets/uploads/guides/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 51200; // 50MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('pdf_file')) {
            $err = strip_tags($this->upload->display_errors());
            $this->session->set_flashdata('error', 'PDF upload failed: ' . $err);
            redirect('admin/add_guide_file?category_id=' . $category_id);
        }

        $upload_data = $this->upload->data();
        $rel_path    = 'assets/uploads/guides/' . $upload_data['file_name'];
        $original_fn = $upload_data['client_name'];
        
        $filesize_bytes = (float)$upload_data['file_size'] * 1024;
        $units = array('B', 'KB', 'MB', 'GB');
        $pow = floor(($filesize_bytes ? log($filesize_bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $filesize_str = round($filesize_bytes / pow(1024, $pow), 1) . ' ' . $units[$pow];

        $now = date('Y-m-d H:i:s');
        $insert_data = array(
            'category_id' => $category_id,
            'title'       => $title,
            'filename'    => $original_fn,
            'file_path'   => $rel_path,
            'file_size'   => $filesize_str,
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'created_at'  => $now,
            'updated_at'  => $now
        );

        $this->db->insert('guide_files', $insert_data);
        $this->session->set_flashdata('success', 'PDF guide uploaded successfully!');
        redirect('admin/downloadable_guides?category_id=' . $category_id);
    }

    public function update_guide_file($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $file = $this->db->get_where('guide_files', array('id' => $id))->row();
        if (!$file) {
            $this->session->set_flashdata('error', 'File not found.');
            redirect('admin/downloadable_guides');
        }

        $title       = trim($this->input->post('title'));
        $category_id = (int)$this->input->post('category_id');

        if (empty($title) || empty($category_id)) {
            $this->session->set_flashdata('error', 'Guide Title and Category are required.');
            redirect('admin/edit_guide_file/' . $id);
        }

        $rel_path    = $file->file_path;
        $original_fn = $file->filename;
        $filesize_str = $file->file_size;

        if (!empty($_FILES['pdf_file']['name'])) {
            $upload_dir = './assets/uploads/guides/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $config['upload_path']   = $upload_dir;
            $config['allowed_types'] = 'pdf';
            $config['max_size']      = 51200; // 50MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('pdf_file')) {
                $upload_data = $this->upload->data();
                $new_rel_path = 'assets/uploads/guides/' . $upload_data['file_name'];

                // Remove previous file if in uploads directory
                if (strpos($file->file_path, 'assets/uploads/guides/') !== false && file_exists(FCPATH . $file->file_path)) {
                    @unlink(FCPATH . $file->file_path);
                }

                $rel_path    = $new_rel_path;
                $original_fn = $upload_data['client_name'];

                $filesize_bytes = (float)$upload_data['file_size'] * 1024;
                $units = array('B', 'KB', 'MB', 'GB');
                $pow = floor(($filesize_bytes ? log($filesize_bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);
                $filesize_str = round($filesize_bytes / pow(1024, $pow), 1) . ' ' . $units[$pow];
            } else {
                $err = strip_tags($this->upload->display_errors());
                $this->session->set_flashdata('error', 'New PDF upload failed: ' . $err);
                redirect('admin/edit_guide_file/' . $id);
            }
        }

        $update_data = array(
            'category_id' => $category_id,
            'title'       => $title,
            'filename'    => $original_fn,
            'file_path'   => $rel_path,
            'file_size'   => $filesize_str,
            'sort_order'  => (int)$this->input->post('sort_order'),
            'is_active'   => (int)$this->input->post('is_active'),
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id)->update('guide_files', $update_data);
        $this->session->set_flashdata('success', 'Guide PDF details updated successfully!');
        redirect('admin/downloadable_guides?category_id=' . $category_id);
    }

    public function delete_guide_file($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $file = $this->db->get_where('guide_files', array('id' => $id))->row();
        if ($file) {
            $cat_id = $file->category_id;
            if (strpos($file->file_path, 'assets/uploads/guides/') !== false && file_exists(FCPATH . $file->file_path)) {
                @unlink(FCPATH . $file->file_path);
            }
            $this->db->where('id', $id)->delete('guide_files');
            $this->session->set_flashdata('success', 'PDF guide deleted successfully!');
            redirect('admin/downloadable_guides?category_id=' . $cat_id);
        } else {
            $this->session->set_flashdata('error', 'Guide file not found.');
            redirect('admin/downloadable_guides');
        }
    }

    /* -------------------------------------------------------------
     * STUDENT TESTIMONIALS MANAGEMENT
     * -----------------------------------------------------------*/
    public function testimonials() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        $data['testimonials'] = $this->db->get('testimonials')->result();
        $data['page_title']   = 'Manage Student Testimonials';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/testimonials', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_testimonial() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add Student Testimonial';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/testimonial_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_testimonial($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $testimonial = $this->db->get_where('testimonials', array('id' => (int)$id))->row();
        if (!$testimonial) {
            $this->session->set_flashdata('error', 'Testimonial not found.');
            redirect('admin/testimonials');
        }

        $data['testimonial'] = $testimonial;
        $data['page_title']  = 'Edit Testimonial';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/testimonial_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_testimonial() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $author_name = trim($this->input->post('author_name'));
        $author_role = trim($this->input->post('author_role'));
        $quote       = trim($this->input->post('quote'));

        if (empty($author_name) || empty($author_role) || empty($quote)) {
            $this->session->set_flashdata('error', 'Author Name, Role, and Quote are required.');
            redirect('admin/add_testimonial');
        }

        $initials = strtoupper(trim($this->input->post('avatar_initials')));
        if (empty($initials)) {
            $parts = explode(' ', $author_name);
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
            } else {
                $initials = strtoupper(substr($author_name, 0, 2));
            }
        }

        $now = date('Y-m-d H:i:s');
        $insert_data = array(
            'author_name'     => $author_name,
            'author_role'     => $author_role,
            'avatar_initials' => $initials,
            'avatar_bg'       => trim($this->input->post('avatar_bg')) ?: 'bg-blue',
            'quote'           => $quote,
            'status'          => ($this->input->post('status') === 'inactive') ? 'inactive' : 'active',
            'sort_order'      => (int)$this->input->post('sort_order'),
            'created_at'      => $now,
            'updated_at'      => $now
        );

        $this->db->insert('testimonials', $insert_data);
        $this->session->set_flashdata('success', 'Testimonial created successfully!');
        redirect('admin/testimonials');
    }

    public function update_testimonial($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $testimonial = $this->db->get_where('testimonials', array('id' => (int)$id))->row();
        if (!$testimonial) {
            $this->session->set_flashdata('error', 'Testimonial not found.');
            redirect('admin/testimonials');
        }

        $author_name = trim($this->input->post('author_name'));
        $author_role = trim($this->input->post('author_role'));
        $quote       = trim($this->input->post('quote'));

        if (empty($author_name) || empty($author_role) || empty($quote)) {
            $this->session->set_flashdata('error', 'Author Name, Role, and Quote are required.');
            redirect('admin/edit_testimonial/' . $id);
        }

        $initials = strtoupper(trim($this->input->post('avatar_initials')));
        if (empty($initials)) {
            $parts = explode(' ', $author_name);
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
            } else {
                $initials = strtoupper(substr($author_name, 0, 2));
            }
        }

        $update_data = array(
            'author_name'     => $author_name,
            'author_role'     => $author_role,
            'avatar_initials' => $initials,
            'avatar_bg'       => trim($this->input->post('avatar_bg')) ?: 'bg-blue',
            'quote'           => $quote,
            'status'          => ($this->input->post('status') === 'inactive') ? 'inactive' : 'active',
            'sort_order'      => (int)$this->input->post('sort_order'),
            'updated_at'      => date('Y-m-d H:i:s')
        );

        $this->db->where('id', (int)$id)->update('testimonials', $update_data);
        $this->session->set_flashdata('success', 'Testimonial updated successfully!');
        redirect('admin/testimonials');
    }

    public function delete_testimonial($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->where('id', (int)$id)->delete('testimonials');
        $this->session->set_flashdata('success', 'Testimonial deleted successfully!');
        redirect('admin/testimonials');
    }

    // ==========================================
    // COUPON MANAGEMENT
    // ==========================================

    public function coupons() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $this->db->order_by('created_at', 'DESC');
        $coupons = $this->db->get('coupons')->result();

        $today = date('Y-m-d');
        $active_count = 0;
        $expired_count = 0;
        $total_used = 0;

        foreach ($coupons as $c) {
            $total_used += (int)$c->used_count;
            if ($c->status == 1 && $c->valid_till >= $today) {
                $active_count++;
            } else {
                $expired_count++;
            }
        }

        $data['coupons'] = $coupons;
        $data['counts'] = array(
            'total' => count($coupons),
            'active' => $active_count,
            'expired' => $expired_count,
            'total_used' => $total_used
        );
        $data['page_title'] = 'Manage Discount Coupons';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/coupons', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_coupon() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');

        $data['coupon'] = null;
        $data['page_title'] = 'Create Coupon Code';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/coupon_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_coupon($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $coupon = $this->db->get_where('coupons', array('id' => (int)$id))->row();
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Coupon not found.');
            redirect('admin/coupons');
            return;
        }

        $data['coupon'] = $coupon;
        $data['page_title'] = 'Edit Coupon Code';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/coupon_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function save_coupon() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $code = strtoupper(trim($this->input->post('code')));
        $discount_type = trim($this->input->post('discount_type'));
        $discount_value = floatval($this->input->post('discount_value'));
        $min_spend = trim($this->input->post('min_spend'));
        $valid_till = trim($this->input->post('valid_till'));
        $status = ($this->input->post('status') == '0') ? 0 : 1;

        if (empty($code)) {
            $this->session->set_flashdata('error', 'Coupon code is required.');
            redirect('admin/add_coupon');
            return;
        }

        if (!preg_match('/^[A-Z0-9_\-]+$/', $code)) {
            $this->session->set_flashdata('error', 'Coupon code can only contain letters, numbers, hyphens, and underscores.');
            redirect('admin/add_coupon');
            return;
        }

        $existing = $this->db->get_where('coupons', array('code' => $code))->row();
        if ($existing) {
            $this->session->set_flashdata('error', 'Coupon code "' . htmlspecialchars($code) . '" already exists. Please choose a different code.');
            redirect('admin/add_coupon');
            return;
        }

        if (!in_array($discount_type, array('percentage', 'fixed'))) {
            $discount_type = 'percentage';
        }

        if ($discount_value <= 0) {
            $this->session->set_flashdata('error', 'Discount value must be greater than 0.');
            redirect('admin/add_coupon');
            return;
        }

        if ($discount_type === 'percentage' && $discount_value > 100) {
            $this->session->set_flashdata('error', 'Percentage discount cannot exceed 100%.');
            redirect('admin/add_coupon');
            return;
        }

        if (empty($valid_till) || !strtotime($valid_till)) {
            $this->session->set_flashdata('error', 'Please provide a valid expiration date.');
            redirect('admin/add_coupon');
            return;
        }

        $min_spend_val = (is_numeric($min_spend) && floatval($min_spend) > 0) ? floatval($min_spend) : null;

        $insert_data = array(
            'code'           => $code,
            'discount_type'  => $discount_type,
            'discount_value' => $discount_value,
            'min_spend'      => $min_spend_val,
            'valid_till'     => date('Y-m-d', strtotime($valid_till)),
            'status'         => $status,
            'used_count'     => 0,
            'created_at'     => date('Y-m-d H:i:s')
        );

        $this->db->insert('coupons', $insert_data);
        $this->session->set_flashdata('success', 'Coupon <strong>' . htmlspecialchars($code) . '</strong> created successfully!');
        redirect('admin/coupons');
    }

    public function update_coupon($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $coupon = $this->db->get_where('coupons', array('id' => (int)$id))->row();
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Coupon not found.');
            redirect('admin/coupons');
            return;
        }

        $code = strtoupper(trim($this->input->post('code')));
        $discount_type = trim($this->input->post('discount_type'));
        $discount_value = floatval($this->input->post('discount_value'));
        $min_spend = trim($this->input->post('min_spend'));
        $valid_till = trim($this->input->post('valid_till'));
        $status = ($this->input->post('status') == '0') ? 0 : 1;

        if (empty($code)) {
            $this->session->set_flashdata('error', 'Coupon code is required.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        if (!preg_match('/^[A-Z0-9_\-]+$/', $code)) {
            $this->session->set_flashdata('error', 'Coupon code can only contain letters, numbers, hyphens, and underscores.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        $existing = $this->db->where('code', $code)->where('id !=', (int)$id)->get('coupons')->row();
        if ($existing) {
            $this->session->set_flashdata('error', 'Coupon code "' . htmlspecialchars($code) . '" is already in use by another coupon.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        if (!in_array($discount_type, array('percentage', 'fixed'))) {
            $discount_type = 'percentage';
        }

        if ($discount_value <= 0) {
            $this->session->set_flashdata('error', 'Discount value must be greater than 0.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        if ($discount_type === 'percentage' && $discount_value > 100) {
            $this->session->set_flashdata('error', 'Percentage discount cannot exceed 100%.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        if (empty($valid_till) || !strtotime($valid_till)) {
            $this->session->set_flashdata('error', 'Please provide a valid expiration date.');
            redirect('admin/edit_coupon/' . $id);
            return;
        }

        $min_spend_val = (is_numeric($min_spend) && floatval($min_spend) > 0) ? floatval($min_spend) : null;

        $update_data = array(
            'code'           => $code,
            'discount_type'  => $discount_type,
            'discount_value' => $discount_value,
            'min_spend'      => $min_spend_val,
            'valid_till'     => date('Y-m-d', strtotime($valid_till)),
            'status'         => $status,
            'updated_at'     => date('Y-m-d H:i:s')
        );

        $this->db->where('id', (int)$id)->update('coupons', $update_data);
        $this->session->set_flashdata('success', 'Coupon <strong>' . htmlspecialchars($code) . '</strong> updated successfully!');
        redirect('admin/coupons');
    }

    public function delete_coupon($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $coupon = $this->db->get_where('coupons', array('id' => (int)$id))->row();
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Coupon not found.');
            redirect('admin/coupons');
            return;
        }

        $this->db->where('id', (int)$id)->delete('coupons');
        $this->session->set_flashdata('success', 'Coupon <strong>' . htmlspecialchars($coupon->code) . '</strong> deleted successfully.');
        redirect('admin/coupons');
    }

    public function toggle_coupon_status($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();

        $coupon = $this->db->get_where('coupons', array('id' => (int)$id))->row();
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Coupon not found.');
            redirect('admin/coupons');
            return;
        }

        $new_status = ($coupon->status == 1) ? 0 : 1;
        $this->db->where('id', (int)$id)->update('coupons', array('status' => $new_status));

        $status_label = ($new_status == 1) ? 'activated' : 'deactivated';
        $this->session->set_flashdata('success', 'Coupon <strong>' . htmlspecialchars($coupon->code) . '</strong> has been ' . $status_label . '.');
        redirect('admin/coupons');
    }
}





