<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('courses');
        $data['courses'] = $query->result();

        $this->load->view('public_header', array('title' => 'Our Courses | AlphaMindz'));
        $this->load->view('courses', $data);
        $this->load->view('public_footer');
    }
}
