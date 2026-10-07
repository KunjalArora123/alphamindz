<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
    }

        public function index() {
        $this->db->where('status', 'active');
        $this->db->where('title !=', 'Interest Inventory Test');
        $this->db->where('title !=', 'MBTI Personality Profiling Test');
        $active_assessments = $this->db->get('assessments')->result();
        
        $data['active_assessments'] = $active_assessments;
        $this->load->view('assessments', $data);
    }

    public function take_test($test_param = null) {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('auth/login');
        }

        $user_id = $this->session->userdata('user_id');
        $test_title = $this->input->get('test');
        if (empty($test_title)) {
            $test_title = 'Class 10 Assessment';
        }

        // System tests are always enabled as prerequisites
        if ($test_title === 'Interest Inventory Test' || $test_title === 'MBTI Personality Profiling Test') {
            $status = 'enabled';
        } else {
            $perm = $this->db->get_where('user_test_permissions', array('user_id' => $user_id, 'test_title' => $test_title))->row();
            $status = $perm ? strtolower($perm->status) : 'disabled';
        }

        if ($status !== 'enabled') {
            $this->session->set_flashdata('error', 'The assessment test "' . htmlspecialchars($test_title) . '" is currently locked. Your admin must authorize this test for your account in the Admin Panel.');
            redirect('assessments');
            return;
        }

        // Workflow Enforcer: Interest -> MBTI -> Target Test
        if ($test_title !== 'Interest Inventory Test' && $test_title !== 'MBTI Personality Profiling Test') {
            $this->session->set_userdata('target_test', $test_title);
            
            if (!$this->session->userdata('completed_interest')) {
                redirect('assessments/take_test?test=' . urlencode('Interest Inventory Test'));
                return;
            }
            if (!$this->session->userdata('completed_mbti')) {
                redirect('assessments/take_test?test=' . urlencode('MBTI Personality Profiling Test'));
                return;
            }
        }

        // Setup session for test
        if (!$this->session->userdata('test_started') || $this->session->userdata('test_subject') != $test_title) {
            $this->session->set_userdata('test_started', time());
            $this->session->set_userdata('test_subject', $test_title);
        }

        // Fetch assessment record and parts
        $assessment_obj = $this->db->get_where('assessments', array('title' => $test_title))->row();
        if (!$assessment_obj) {
            $assessment_obj = $this->db->get_where('assessments', array('slug' => $test_title))->row();
        }
        if (!$assessment_obj) {
            $this->db->like('title', $test_title);
            $assessment_obj = $this->db->get('assessments')->row();
        }
        if (!$assessment_obj && ($test_title === 'Class 10 Assessment' || strpos($test_title, '10') !== false)) {
            $assessment_obj = $this->db->get_where('assessments', array('id' => 36))->row();
        }
        $parts = array();
        if ($assessment_obj) {
            $parts = $this->db->get_where('assessment_parts', array('assessment_id' => $assessment_obj->id))->result();
        }

        if ($test_title == 'MBTI Personality Profiling Test') {
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $raw_questions = json_decode($json_data, true);
            
            $formatted_questions = [];
            foreach ($raw_questions as $q) {
                $obj = new stdClass();
                $obj->id = $q['id'];
                $obj->question_number = $q['id'];
                
                if ($q['id'] >= 1 && $q['id'] <= 26) {
                    $obj->part_name = 'Part 1 - Which answer comes closest to describing how you usually feel or act?';
                } else if ($q['id'] >= 27 && $q['id'] <= 58) {
                    $obj->part_name = 'Part 2 - Which word in Each pair appears to you more? Think about what words mean, not about how they look or how they sound';
                } else if ($q['id'] >= 59 && $q['id'] <= 78) {
                    $obj->part_name = 'Part 3 - Which Answer Comes Closest to describing how you usually feel or act';
                } else if ($q['id'] >= 79 && $q['id'] <= 93) {
                    $obj->part_name = 'Part 4 - Which Word in Each pair appeals to you more? Think About what the words mean and not about how they look or they sound';
                } else {
                    $obj->part_name = '';
                }

                $obj->subject = 'MBTI Personality Profiling Test';
                $obj->question_text = $q['text'];
                $obj->option_a = $q['option_a'];
                $obj->option_b = $q['option_b'];
                $obj->option_c = '';
                $obj->option_d = '';
                $obj->option_a_image = '';
                $obj->option_b_image = '';
                $obj->option_c_image = '';
                $obj->option_d_image = '';
                $obj->image_path = '';
                $formatted_questions[] = $obj;
            }
            
            $data['questions'] = $formatted_questions;
            $data['subject'] = $test_title;
            $data['start_time'] = $this->session->userdata('test_started');
            $data['time_limit'] = 0; 

            $this->load->view('assessments/test', $data);
            return;
        }

        if ($test_title == 'Interest Inventory Test') {
            $json_data = file_get_contents(APPPATH . 'config/interest_inventory.json');
            $data['questions'] = json_decode($json_data, true);
            $data['subject'] = $test_title;
            $data['start_time'] = $this->session->userdata('test_started');
            $data['time_limit'] = 0; 

            $this->load->view('interest/test', $data);
            return;
        }

        $this->db->select('q.*, ap.part_name');
        $this->db->from('questions q');
        $this->db->join('assessment_parts ap', 'q.part_id = ap.id', 'left');
        if ($assessment_obj) {
            $this->db->where('q.assessment_id', $assessment_obj->id);
        } else {
            $this->db->where('q.subject', $test_title);
            if (!empty($parts)) {
                $part_names = array_map(function($p) { return $p->part_name; }, $parts);
                $this->db->or_where_in('q.subject', $part_names);
            }
        }
        $this->db->order_by('q.part_id', 'ASC');
        $this->db->order_by('q.question_number', 'ASC');
        $data['questions'] = $this->db->get()->result();
        
        $time_limit_mins = ($assessment_obj && $assessment_obj->time_limit > 0) ? (int)$assessment_obj->time_limit : 45;

        $data['subject'] = $test_title;
        $data['start_time'] = $this->session->userdata('test_started');
        $data['time_limit'] = $time_limit_mins * 60; // in seconds

        $this->load->view('assessments/test', $data);
    }

    public function submit_test() {
        if (!$this->session->userdata('user_logged_in') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('assessments');
        }

        $user_id = $this->session->userdata('user_id');
        $subject = $this->session->userdata('test_subject');
        $started_at = $this->session->userdata('test_started');
        
        // Grade questions
        $assessment_obj = $this->db->get_where('assessments', array('title' => $subject))->row();
        if (!$assessment_obj) {
            $assessment_obj = $this->db->get_where('assessments', array('slug' => $subject))->row();
        }
        if (!$assessment_obj) {
            $this->db->like('title', $subject);
            $assessment_obj = $this->db->get('assessments')->row();
        }
        if (!$assessment_obj && ($subject === 'Class 10 Assessment' || strpos($subject, '10') !== false)) {
            $assessment_obj = $this->db->get_where('assessments', array('id' => 36))->row();
        }
        $parts = array();
        if ($assessment_obj) {
            $parts = $this->db->get_where('assessment_parts', array('assessment_id' => $assessment_obj->id))->result();
        }

        if ($subject == 'Interest Inventory Test') {
            $json_data = file_get_contents(APPPATH . 'config/interest_inventory.json');
            $questions = json_decode($json_data, true);
            $submitted_answers = $this->input->post('answers');
            if (!is_array($submitted_answers)) $submitted_answers = array();

            $data = array(
                'user_id' => $user_id,
                'subject' => $subject,
                'score' => 0,
                'total_questions' => count($questions),
                'percentage' => 100, // marking as completed
                'started_at' => date('Y-m-d H:i:s', $started_at),
                'completed_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('test_attempts', $data);
            $attempt_id = $this->db->insert_id();

            foreach ($questions as $q) {
                $ans = isset($submitted_answers[$q['id']]) ? $submitted_answers[$q['id']] : null;
                // Check if it's an array (checkboxes) and serialize it
                if (is_array($ans)) {
                    $ans = implode(', ', $ans);
                }
                
                $this->db->insert('test_answers', array(
                    'attempt_id' => $attempt_id,
                    'question_id' => $q['id'],
                    'selected_option' => $ans,
                    'is_correct' => 1 // mark as correct for progress
                ));
            }

            $this->session->set_userdata('completed_interest', true);
            $this->session->unset_userdata('test_started');
            $this->session->unset_userdata('test_subject');
            $this->session->set_flashdata('success', 'Interest Inventory Test completed successfully.');
            
            $target_test = $this->session->userdata('target_test');
            if ($target_test) {
                redirect('assessments/take_test?test=' . urlencode($target_test));
            } else {
                redirect('assessments/result/' . $attempt_id);
            }
            return;
        }

        if ($subject == 'MBTI Personality Profiling Test') {
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $questions = json_decode($json_data, true);
            $submitted_answers = $this->input->post('answers');
            if (!is_array($submitted_answers)) $submitted_answers = array();

            $scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($questions as $q) {
                $pair = $q['pair'];
                if (!$pair) continue;
                list($trait_a, $trait_b) = explode('/', $pair);
                
                $ans = isset($submitted_answers[$q['id']]) ? strtolower($submitted_answers[$q['id']]) : null;
                if ($ans == 'a') $scores[$trait_a]++;
                else if ($ans == 'b') $scores[$trait_b]++;
            }
            
            $type = '';
            $type .= ($scores['E'] >= $scores['I']) ? 'E' : 'I';
            $type .= ($scores['S'] >= $scores['N']) ? 'S' : 'N';
            $type .= ($scores['T'] >= $scores['F']) ? 'T' : 'F';
            $type .= ($scores['J'] >= $scores['P']) ? 'J' : 'P';
            
            // Serialize MBTI scores into score column or percentage to differentiate
            // Actually, we can just save it as percentage = 100 for completed, score = 0
            $data = array(
                'user_id' => $user_id,
                'subject' => $subject,
                'score' => 0,
                'total_questions' => count($questions),
                'percentage' => 100, // marking as completed successfully
                'started_at' => date('Y-m-d H:i:s', $started_at),
                'completed_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('test_attempts', $data);
            $attempt_id = $this->db->insert_id();

            foreach ($questions as $q) {
                $ans = isset($submitted_answers[$q['id']]) ? $submitted_answers[$q['id']] : null;
                $this->db->insert('test_answers', array(
                    'attempt_id' => $attempt_id,
                    'question_id' => $q['id'],
                    'selected_option' => $ans,
                    'is_correct' => 1
                ));
            }

            $this->session->set_userdata('completed_mbti', true);
            $this->session->set_flashdata('mbti_scores', $scores);
            $this->session->set_flashdata('mbti_type', $type);
            
            $target_test = $this->session->userdata('target_test');
            if ($target_test) {
                // Have to clear test session vars since we are redirecting mid-function
                $this->session->unset_userdata('test_started');
                $this->session->unset_userdata('test_subject');
                
                redirect('assessments/take_test?test=' . urlencode($target_test));
                return;
            }
        } else {
            // Grade standard questions
            $this->db->select('q.*');
            $this->db->from('questions q');
            if ($assessment_obj) {
                $this->db->where('q.assessment_id', $assessment_obj->id);
            } else {
                $this->db->where('q.subject', $subject);
                if (!empty($parts)) {
                    $part_names = array_map(function($p) { return $p->part_name; }, $parts);
                    $this->db->or_where_in('q.subject', $part_names);
                }
            }
            $questions = $this->db->get()->result();
            
            $score = 0;
            $total = count($questions);
            
            $submitted_answers = $this->input->post('answers');
            if (!is_array($submitted_answers)) {
                $submitted_answers = array();
            }

            foreach ($questions as $q) {
                $ans = isset($submitted_answers[$q->id]) ? $submitted_answers[$q->id] : null;
                if ($ans && $ans === $q->correct_option) {
                    $score++;
                }
            }

            $percentage = ($total > 0) ? ($score / $total) * 100 : 0;
            
            $data = array(
                'user_id' => $user_id,
                'subject' => $subject,
                'score' => $score,
                'total_questions' => $total,
                'percentage' => $percentage,
                'started_at' => date('Y-m-d H:i:s', $started_at),
                'completed_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert('test_attempts', $data);
            $attempt_id = $this->db->insert_id();
        }

        // Update test permission status to 'completed' for this specific test
        $existing = $this->db->get_where('user_test_permissions', array('user_id' => $user_id, 'test_title' => $subject))->row();
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('user_test_permissions', array('status' => 'completed'));
        } else {
            $this->db->insert('user_test_permissions', array('user_id' => $user_id, 'test_title' => $subject, 'status' => 'completed'));
        }

        // Clear test session
        $this->session->unset_userdata('test_started');
        $this->session->unset_userdata('test_subject');
        
        // Clear the workflow session variables so next time they take a test, they have to take the pre-tests again
        $this->session->unset_userdata('target_test');
        $this->session->unset_userdata('completed_interest');
        $this->session->unset_userdata('completed_mbti');

        redirect('assessments/result/'.$attempt_id);
    }

    public function result($attempt_id) {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('auth/login');
        }

        $this->db->where('id', $attempt_id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $attempt = $this->db->get('test_attempts')->row();

        if (!$attempt) {
            redirect('assessments');
        }

        $data['attempt'] = $attempt;
        
        if ($attempt->subject == 'Interest Inventory Test') {
            $this->load->view('student/includes/header', array('title' => 'Test Result | AlphaMindz'));
            $this->load->view('interest/result', $data);
            $this->load->view('student/includes/footer');
            return;
        }

        if ($attempt->subject == 'MBTI Personality Profiling Test') {
            $data['scores'] = $this->session->flashdata('mbti_scores');
            $data['type'] = $this->session->flashdata('mbti_type');
            
            // Recompute max_scores for display since it's not saved
            $json_data = file_get_contents(APPPATH . 'config/mbti_questions.json');
            $questions = json_decode($json_data, true);
            $max_scores = array('E'=>0, 'I'=>0, 'S'=>0, 'N'=>0, 'T'=>0, 'F'=>0, 'J'=>0, 'P'=>0);
            foreach ($questions as $q) {
                if ($q['pair']) {
                    list($trait_a, $trait_b) = explode('/', $q['pair']);
                    $max_scores[$trait_a]++;
                    $max_scores[$trait_b]++;
                }
            }
            $data['max_scores'] = $max_scores;
            
            $this->load->view('student/includes/header', array('title' => 'MBTI Result | AlphaMindz'));
            $this->load->view('mbti/result', $data);
            $this->load->view('student/includes/footer');
        } else {
            $this->load->view('student/includes/header', array('title' => 'Test Result | AlphaMindz'));
            $this->load->view('assessments/result', $data);
            $this->load->view('student/includes/footer');
        }
    }
}

