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

        $this->session->set_flashdata('success', 'Course updated successfully!');
        redirect('admin/courses');
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

        // Get answers
        $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option');
        $this->db->from('test_answers');
        $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
        $this->db->where('test_answers.attempt_id', $attempt_id);
        $this->db->order_by('questions.question_number', 'ASC');
        $answers = $this->db->get()->result();
        
        // Mock data generator for demo if no answers exist
        if (empty($answers)) {
            $questions = $this->db->get_where('questions', ['subject' => $data['attempt']->subject])->result();
            
            // If no questions exist, create dummy ones
            if (empty($questions)) {
                $total_needed = $data['attempt']->total_questions;
                if ($total_needed <= 0) $total_needed = 10;
                
                for ($i = 1; $i <= $total_needed; $i++) {
                    $this->db->insert('questions', [
                        'subject' => $data['attempt']->subject,
                        'question_number' => $i,
                        'question_text' => 'Sample Question ' . $i . ' for ' . $data['attempt']->subject,
                        'option_a' => 'Option A',
                        'option_b' => 'Option B',
                        'option_c' => 'Option C',
                        'option_d' => 'Option D',
                        'correct_option' => ['A','B','C','D'][rand(0,3)]
                    ]);
                }
                $questions = $this->db->get_where('questions', ['subject' => $data['attempt']->subject])->result();
            }

            if(!empty($questions)) {
                $correct_needed = $data['attempt']->score;
                $inserted = 0;
                foreach($questions as $index => $q) {
                    $is_correct = ($inserted < $correct_needed) ? 1 : 0;
                    $selected = $is_correct ? $q->correct_option : ($q->correct_option == 'A' ? 'B' : 'A'); // Mock wrong answer
                    
                    // Randomly leave some unanswered (NULL) if incorrect
                    if (!$is_correct && rand(0, 100) > 60) {
                        $selected = NULL;
                    }
                    
                    $this->db->insert('test_answers', [
                        'attempt_id' => $attempt_id,
                        'question_id' => $q->id,
                        'selected_option' => $selected,
                        'is_correct' => $is_correct
                    ]);
                    if($is_correct) $inserted++;
                }
                
                // Re-fetch
                $this->db->select('test_answers.*, questions.question_text, questions.question_number, questions.correct_option');
                $this->db->from('test_answers');
                $this->db->join('questions', 'questions.id = test_answers.question_id', 'left');
                $this->db->where('test_answers.attempt_id', $attempt_id);
                $this->db->order_by('questions.question_number', 'ASC');
                $answers = $this->db->get()->result();
            }
        }
        
        $data['answers'] = $answers;
        
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
        $this->db->order_by('id', 'DESC');
        $data['products'] = $this->db->get('products')->result();
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
    // ==========================================
    // BLOGS / ARTICLES ALIASES
    // ==========================================

    public function blogs() { $this->articles(); }
    public function add_blog() { $this->add_article(); }
    public function save_blog() { $this->save_article(); }
    public function edit_blog($id) { $this->edit_article($id); }
    public function update_blog($id) { $this->update_article($id); }
    public function delete_blog($id) { $this->delete_article($id); }
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
        if (!empty($_FILES['question_image']['name']) && $_FILES['question_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = FCPATH . 'questions-images/';
            if (!file_exists($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }
            $file_ext = strtolower(pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');
            if (in_array($file_ext, $allowed)) {
                $filename = 'q_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image']['tmp_name'], $upload_dir . $filename)) {
                    $image_path = 'questions-images/' . $filename;
                }
            }
        }

        $image_path_2 = null;
        if (!empty($_FILES['question_image_2']['name']) && $_FILES['question_image_2']['error'] === UPLOAD_ERR_OK) {
            $file_ext = strtolower(pathinfo($_FILES['question_image_2']['name'], PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');
            if (in_array($file_ext, $allowed)) {
                $filename = 'q2_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['question_image_2']['tmp_name'], $upload_dir . $filename)) {
                    $image_path_2 = 'questions-images/' . $filename;
                }
            }
        }

        $option_images = array(
            'option_a_image' => null,
            'option_b_image' => null,
            'option_c_image' => null,
            'option_d_image' => null
        );
        $allowed = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');
        $upload_dir = FCPATH . 'questions-images/';
        if (!file_exists($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        foreach (array('a', 'b', 'c', 'd') as $opt) {
            $field = 'option_' . $opt . '_image';
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $file_ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, $allowed)) {
                    $filename = 'opt_' . $opt . '_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $filename)) {
                        $option_images[$field] = 'questions-images/' . $filename;
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

        $upload_dir = FCPATH . 'questions-images/';
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
                    $image_path = 'questions-images/' . $filename;
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
                    $image_path_2 = 'questions-images/' . $filename;
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
                        $option_images[$field] = 'questions-images/' . $filename;
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

        // Fetch students & courses for shortcut issue form
        $this->db->where_in('role', array('student', 'Student'));
        $this->db->order_by('first_name', 'ASC');
        $students = $this->db->get('users')->result();

        $this->db->order_by('title', 'ASC');
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
}





