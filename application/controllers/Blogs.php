<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blogs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->db->where('status', 'published');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('blogs');
        $data['blogs'] = $query->result();

        $this->load->view('public_header', array('title' => 'Blogs | AlphaMindz'));
        $this->load->view('blogs', $data);
        $this->load->view('public_footer');
    }

    public function view($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->where('status', 'published');
        $query = $this->db->get('blogs');

        if ($query->num_rows() == 0) {
            show_404();
        }

        $data['blog'] = $query->row();

        $this->load->view('public_header', array('title' => $data['blog']->title . ' | AlphaMindz'));
        $this->load->view('blog_single', $data);
        $this->load->view('public_footer');
    }
}
