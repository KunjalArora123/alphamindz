<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        $search_query = $this->input->get('q');
        
        $this->db->order_by('id', 'DESC');
        
        if (!empty($search_query)) {
            $this->db->group_start();
            $this->db->like('title', $search_query);
            $this->db->or_like('description', $search_query);
            $this->db->group_end();
        }
        
        $query = $this->db->get('products');
        $data['products'] = $query->result();
        $data['search_query'] = $search_query;

        $this->load->view('public_header', array('title' => 'Shop | AlphaMindz'));
        $this->load->view('shop', $data);
        $this->load->view('public_footer');
    }
}
