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
        $courses = $this->db->get_where('courses', array('status' => 'publish'))->result();

        foreach ($courses as &$c) {
            $c->tiers = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', array('course_id' => $c->id))->result();
        }

        $data['courses'] = $courses;

        $this->load->view('public_header', array('title' => 'Our Courses | AlphaMindz'));
        $this->load->view('courses', $data);
        $this->load->view('public_footer');
    }

    public function detail($id)
    {
        $course = $this->db->get_where('courses', array('id' => $id))->row();
        if (!$course) {
            $course = $this->db->get_where('courses', array('slug' => $id))->row();
        }

        if (!$course) {
            show_404();
        }

        $tiers = $this->db->order_by('sort_order', 'ASC')->get_where('course_tiers', array('course_id' => $course->id))->result();

        $data['course'] = $course;
        $data['tiers'] = $tiers;

        $this->load->view('public_header', array('title' => $course->title . ' | AlphaMindz'));
        $this->load->view('course_detail', $data);
        $this->load->view('public_footer');
    }
}
