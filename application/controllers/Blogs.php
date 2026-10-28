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
        // Self-healing: clean up any legacy localhost/127.0.0.1 image URLs in the database
        $this->db->query("UPDATE `blogs` SET `image` = NULL WHERE `image` LIKE '%localhost%' OR `image` LIKE '%127.0.0.1%'");

        $this->db->group_start();
        $this->db->where('status', 'published');
        $this->db->or_where('status IS NULL', NULL, FALSE);
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('blogs');
        $blogs = $query ? $query->result() : array();

        // Sanitize blog image URLs in memory
        foreach ($blogs as $b) {
            if (!empty($b->image) && (stripos($b->image, 'localhost') !== false || stripos($b->image, '127.0.0.1') !== false)) {
                $b->image = null;
            }
        }
        $data['blogs'] = $blogs;

        $this->load->view('public_header', array('title' => 'Blogs | AlphaMindz'));
        $this->load->view('blogs', $data);
        $this->load->view('public_footer');
    }

    public function view($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('blogs');

        if (!$query || $query->num_rows() == 0) {
            show_404();
        }

        $blog = $query->row();
        if (!empty($blog->image) && (stripos($blog->image, 'localhost') !== false || stripos($blog->image, '127.0.0.1') !== false)) {
            $blog->image = null;
        }

        $data['blog'] = $blog;

        $this->load->view('public_header', array('title' => $data['blog']->title . ' | AlphaMindz'));
        $this->load->view('blog_single', $data);
        $this->load->view('public_footer');
    }
}
