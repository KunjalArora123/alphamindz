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
        $user_id = $this->session->userdata('user_id');
        
        // Fetch recent test attempts
        $this->db->where('user_id', $user_id);
        $this->db->order_by('completed_at', 'DESC');
        $this->db->limit(5);
        $data['recent_attempts'] = $this->db->get('test_attempts')->result();

        // Fetch enrolled courses with tier level
        $this->db->select('courses.*, user_enrollments.tier_key, user_enrollments.enrolled_at');
        $this->db->from('user_enrollments');
        $this->db->join('courses', 'courses.id = user_enrollments.entity_id');
        $this->db->where('user_enrollments.user_id', $user_id);
        $this->db->where('user_enrollments.entity_type', 'course');
        $enrolled_courses = $this->db->get()->result();

        // Fetch granted certificates
        $certificates = array();
        $cert_rows = $this->db->get_where('course_certificates', array('user_id' => $user_id, 'status' => 'granted'))->result();
        foreach ($cert_rows as $cr) {
            $certificates[$cr->course_id] = $cr;
        }

        foreach ($enrolled_courses as &$ec) {
            $ec->certificate = isset($certificates[$ec->id]) ? $certificates[$ec->id] : null;
        }
        $data['enrolled_courses'] = $enrolled_courses;

        $data['first_name'] = $this->session->userdata('first_name');
        if (empty($data['first_name'])) {
            $name_parts = explode(' ', $this->session->userdata('user_name'));
            $data['first_name'] = $name_parts[0];
        }

        $this->load->view('student/includes/header', array('title' => 'Student Dashboard | AlphaMindz'));
        $this->load->view('student/dashboard', $data);
        $this->load->view('student/includes/footer');
    }

        public function assessment() {
        $this->db->where('status', 'active');
        $this->db->where('title !=', 'Interest Inventory Test');
        $this->db->where('title !=', 'MBTI Personality Profiling Test');
        $active_assessments = $this->db->get('assessments')->result();
        
        $data['active_assessments'] = $active_assessments;
        $user_id = $this->session->userdata('user_id');

        // Check test authorization status per test
        $perms = $this->db->get_where('user_test_permissions', array('user_id' => $user_id))->result();
        $permissions_map = array();
        foreach ($perms as $p) {
            $permissions_map[$p->test_title] = strtolower($p->status);
        }
        $data['permissions_map'] = $permissions_map;

        // Get past attempts
        $this->db->where('user_id', $user_id);
        $this->db->order_by('completed_at', 'DESC');
        $data['attempts'] = $this->db->get('test_attempts')->result();

        $this->load->view('student/includes/header', array('title' => 'My Assessments | AlphaMindz'));
        $this->load->view('student/assessment', $data);
        $this->load->view('student/includes/footer');
    }

    public function certificate($credential_id) {
        $user_id = $this->session->userdata('user_id');

        $cert = $this->db->get_where('course_certificates', array(
            'credential_id' => $credential_id,
            'status' => 'granted'
        ))->row();

        if (!$cert) {
            $this->session->set_flashdata('error', 'Certificate not found or not granted.');
            redirect('student');
        }

        // Verify ownership
        if ($cert->user_id != $user_id) {
            $this->session->set_flashdata('error', 'Unauthorized access to certificate.');
            redirect('student');
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

    public function course($course_id) {
        $user_id = $this->session->userdata('user_id');

        // Verify student enrollment
        $enrollment = $this->db->get_where('user_enrollments', array(
            'user_id' => $user_id,
            'entity_id' => $course_id,
            'entity_type' => 'course'
        ))->row();

        if (!$enrollment) {
            $this->session->set_flashdata('error', 'You are not enrolled in this course.');
            redirect('student');
        }

        $course = $this->db->get_where('courses', array('id' => $course_id))->row();
        if (!$course) {
            show_404();
        }

        $tier_key = strtolower($enrollment->tier_key ? $enrollment->tier_key : 'basic');
        $tier_info = $this->db->get_where('course_tiers', array('course_id' => $course_id, 'tier_key' => $tier_key))->row();

        // Determine accessible module tiers based on Additive Rules:
        // Basic: Tier 1 (Basic)
        // Standard: Tier 1 (Basic) + Tier 2 (Standard)
        // Advanced: Tier 1 (Basic) + Tier 2 (Standard) + Tier 3 (Advanced)
        // Premium: All
        $allowed_tiers = array('basic');
        if (in_array($tier_key, array('standard', 'advanced', 'premium'))) {
            $allowed_tiers[] = 'standard';
        }
        if (in_array($tier_key, array('advanced', 'premium'))) {
            $allowed_tiers[] = 'advanced';
        }
        if ($tier_key === 'premium') {
            $allowed_tiers[] = 'premium';
        }

        $this->db->where('course_id', $course_id);
        $this->db->where_in('module_tier', $allowed_tiers);
        $this->db->order_by('sort_order', 'ASC');
        $modules = $this->db->get('course_modules')->result();

        // If no modules exist yet in database, create sample structured modules for demo/initialization
        if (empty($modules)) {
            $sample_modules = array(
                array(
                    'course_id' => $course_id,
                    'module_tier' => 'basic',
                    'title' => 'Tier 1 Module: Fundamental PDF Notes & Core Reading',
                    'content_type' => 'pdf',
                    'description' => 'Strictly View-Only PDF content. Direct downloading, text selection, scraping and right-click are disabled.',
                    'sort_order' => 1
                ),
                array(
                    'course_id' => $course_id,
                    'module_tier' => 'standard',
                    'title' => 'Tier 2 Module: Interactive Video Lectures & Standard Case Studies',
                    'content_type' => 'video',
                    'description' => 'Session-authenticated video streaming via secure signed URLs.',
                    'sort_order' => 2
                ),
                array(
                    'course_id' => $course_id,
                    'module_tier' => 'advanced',
                    'title' => 'Tier 3 Module: Advanced Problem Solving & Token Streamed Masterclasses',
                    'content_type' => 'stream',
                    'description' => 'Cryptographically signed temporary streaming tokens.',
                    'sort_order' => 3
                )
            );

            foreach ($sample_modules as $sm) {
                if (in_array($sm['module_tier'], $allowed_tiers)) {
                    $this->db->insert('course_modules', $sm);
                }
            }

            $this->db->where('course_id', $course_id);
            $this->db->where_in('module_tier', $allowed_tiers);
            $this->db->order_by('sort_order', 'ASC');
            $modules = $this->db->get('course_modules')->result();
        }

        $data['course'] = $course;
        $data['enrollment'] = $enrollment;
        $data['tier_info'] = $tier_info;
        $data['tier_key'] = $tier_key;
        $data['modules'] = $modules;
        $data['allowed_tiers'] = $allowed_tiers;

        $this->load->view('student/includes/header', array('title' => $course->title . ' | Student Panel'));
        $this->load->view('student/course_view', $data);
        $this->load->view('student/includes/footer');
    }
}



