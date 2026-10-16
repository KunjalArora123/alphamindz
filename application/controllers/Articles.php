<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        $this->db->group_start();
        $this->db->where('status', 'published');
        $this->db->or_where('status IS NULL', NULL, FALSE);
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('articles');
        $data['articles'] = $query ? $query->result() : array();

        $this->load->view('public_header', array('title' => 'Articles | AlphaMindz'));
        $this->load->view('articles', $data);
        $this->load->view('public_footer');
    }

    public function view($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('articles');

        if (!$query || $query->num_rows() == 0) {
            show_404();
        }

        $data['article'] = $query->row();

        $this->load->view('public_header', array('title' => $data['article']->title . ' | AlphaMindz'));
        $this->load->view('article_single', $data);
        $this->load->view('public_footer');
    }
}
