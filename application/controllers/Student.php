<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');

        // Check if user is logged in
        if (!$this->session->userdata('user_logged_in')) {
            $this->session->set_flashdata('error', 'Please login to access your student panel.');
            redirect('auth/login');
        }
    }

    public function index() {
        // Fetch student specific data if needed
        $user_id = $this->session->userdata('user_id');
        
        // Let's get recent test attempts for dashboard
        $this->db->where('user_id', $user_id);
        $this->db->order_by('completed_at', 'DESC');
        $this->db->limit(5);
        $data['recent_attempts'] = $this->db->get('test_attempts')->result();

        $data['first_name'] = $this->session->userdata('first_name');
        if (empty($data['first_name'])) {
            // Fallback if not set directly in session
            $name_parts = explode(' ', $this->session->userdata('user_name'));
            $data['first_name'] = $name_parts[0];
        }

        $this->load->view('student/includes/header', array('title' => 'Student Dashboard | AlphaMindz'));
        $this->load->view('student/dashboard', $data);
        $this->load->view('student/includes/footer');
    }
}
