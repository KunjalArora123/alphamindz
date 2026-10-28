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
        // Self-healing: clean up any legacy localhost/127.0.0.1 image URLs in the database
        $this->db->query("UPDATE `articles` SET `image_url` = NULL WHERE `image_url` LIKE '%localhost%' OR `image_url` LIKE '%127.0.0.1%'");

        $this->db->group_start();
        $this->db->where('status', 'published');
        $this->db->or_where('status IS NULL', NULL, FALSE);
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('articles');
        $articles = $query ? $query->result() : array();

        // Sanitize article image URLs in memory
        foreach ($articles as $a) {
            if (!empty($a->image_url) && (stripos($a->image_url, 'localhost') !== false || stripos($a->image_url, '127.0.0.1') !== false)) {
                $a->image_url = null;
            }
        }
        $data['articles'] = $articles;

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

        $article = $query->row();
        if (!empty($article->image_url) && (stripos($article->image_url, 'localhost') !== false || stripos($article->image_url, '127.0.0.1') !== false)) {
            $article->image_url = null;
        }

        $data['article'] = $article;

        $this->load->view('public_header', array('title' => $data['article']->title . ' | AlphaMindz'));
        $this->load->view('article_single', $data);
        $this->load->view('public_footer');
    }
}
