<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Career_facts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        // Self-healing: clean up any legacy localhost/127.0.0.1 image URLs in the database
        $this->db->query("UPDATE `career_facts` SET `image` = NULL WHERE `image` LIKE '%localhost%' OR `image` LIKE '%127.0.0.1%'");

        $this->db->group_start();
        $this->db->where('status', 'published');
        $this->db->or_where('status IS NULL', NULL, FALSE);
        $this->db->group_end();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('career_facts');
        $career_facts = $query ? $query->result() : array();

        // Sanitize career facts image URLs in memory
        foreach ($career_facts as $cf) {
            if (!empty($cf->image) && (stripos($cf->image, 'localhost') !== false || stripos($cf->image, '127.0.0.1') !== false)) {
                $cf->image = null;
            }
        }
        $data['career_facts'] = $career_facts;

        $this->load->view('public_header', array('title' => 'Career Facts | AlphaMindz'));
        $this->load->view('career_facts', $data);
        $this->load->view('public_footer');
    }

    public function view($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('career_facts');

        if (!$query || $query->num_rows() == 0) {
            show_404();
        }

        $career_fact = $query->row();
        if (!empty($career_fact->image) && (stripos($career_fact->image, 'localhost') !== false || stripos($career_fact->image, '127.0.0.1') !== false)) {
            $career_fact->image = null;
        }

        $data['career_fact'] = $career_fact;

        $this->load->view('public_header', array('title' => $data['career_fact']->title . ' | AlphaMindz'));
        $this->load->view('career_fact_single', $data);
        $this->load->view('public_footer');
    }
}
