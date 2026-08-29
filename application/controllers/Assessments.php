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
        if (!$this->session->userdata('user_logged_in')) {
            $this->session->set_flashdata('error', 'Please log in to access the assessments.');
            redirect('auth/login');
        }

        // Get past attempts
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->order_by('completed_at', 'DESC');
        $data['attempts'] = $this->db->get('test_attempts')->result();

        $this->load->view('student/includes/header', array('title' => 'Assessments | AlphaMindz'));
        $this->load->view('assessments/index', $data);
        $this->load->view('student/includes/footer');
    }

    public function take_test() {
        if (!$this->session->userdata('user_logged_in')) {
            redirect('auth/login');
        }

        $test_title = 'Comprehensive Aptitude Assessment';

        // Setup session for test
        if (!$this->session->userdata('test_started') || $this->session->userdata('test_subject') != $test_title) {
            $this->session->set_userdata('test_started', time());
            $this->session->set_userdata('test_subject', $test_title);
        }

        // Fetch all questions except Verbal Ability
        $this->db->where('subject !=', 'Verbal Ability');
        // Sort by subject first so we can group them in the view, then by question number
        $this->db->order_by('subject', 'ASC');
        $this->db->order_by('question_number', 'ASC');
        $data['questions'] = $this->db->get('questions')->result();
        
        $data['subject'] = $test_title;
        $data['start_time'] = $this->session->userdata('test_started');
        $data['time_limit'] = 2700; // 45 minutes in seconds

        $this->load->view('assessments/test', $data);
    }

    public function submit_test() {
        if (!$this->session->userdata('user_logged_in') || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('assessments');
        }

        $subject = $this->session->userdata('test_subject');
        $started_at = $this->session->userdata('test_started');
        
        // Grade all non-Verbal questions
        $this->db->where('subject !=', 'Verbal Ability');
        $questions = $this->db->get('questions')->result();
        
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
            'user_id' => $this->session->userdata('user_id'),
            'subject' => $subject,
            'score' => $score,
            'total_questions' => $total,
            'percentage' => $percentage,
            'started_at' => date('Y-m-d H:i:s', $started_at),
            'completed_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('test_attempts', $data);
        $attempt_id = $this->db->insert_id();

        // Clear test session
        $this->session->unset_userdata('test_started');
        $this->session->unset_userdata('test_subject');

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
        $this->load->view('student/includes/header', array('title' => 'Test Result | AlphaMindz'));
        $this->load->view('assessments/result', $data);
        $this->load->view('student/includes/footer');
    }
}
