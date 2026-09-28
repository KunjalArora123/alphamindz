<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index($credential_id = NULL) {
        $search_id = $credential_id ? $credential_id : $this->input->get('id');
        $search_id = trim($search_id);

        $certificate = null;
        $user = null;
        $course = null;
        $searched = false;

        if (!empty($search_id)) {
            $searched = true;
            $certificate = $this->db->get_where('course_certificates', array(
                'credential_id' => $search_id,
                'status' => 'granted'
            ))->row();

            if ($certificate) {
                $user = $this->db->get_where('users', array('id' => $certificate->user_id))->row();
                $course = $this->db->get_where('courses', array('id' => $certificate->course_id))->row();
            }
        }

        $data = array(
            'search_id' => $search_id,
            'searched' => $searched,
            'certificate' => $certificate,
            'user' => $user,
            'course' => $course
        );

        $this->load->view('verify_index', $data);
    }
}
