<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index($category_param = null)
    {
        $category = $this->input->get('category');
        if (empty($category) && !empty($category_param)) {
            $category = $category_param;
        }

        $search_query = $this->input->get('q');

        $this->db->order_by('id', 'ASC');

        if (!empty($category) && in_array(strtolower($category), array('ielts', 'achievers', 'ebooks', 'kits'))) {
            $this->db->where('category', strtolower($category));
        }

        if (!empty($search_query)) {
            $this->db->group_start();
            $this->db->like('title', $search_query);
            $this->db->or_like('description', $search_query);
            $this->db->group_end();
        }

        $query = $this->db->get('products');
        $data['products'] = $query->result();
        $data['search_query'] = $search_query;
        $data['current_category'] = !empty($category) ? strtolower($category) : 'all';

        // Counts per category
        $data['total_count']     = $this->db->count_all('products');
        $data['ielts_count']     = $this->db->where('category', 'ielts')->count_all_results('products');
        $data['achievers_count'] = $this->db->where('category', 'achievers')->count_all_results('products');
        $data['ebooks_count']    = $this->db->where('category', 'ebooks')->count_all_results('products');
        $data['kits_count']      = $this->db->where('category', 'kits')->count_all_results('products');

        $page_title = 'Shop E-Books & Workbooks | AlphaMindz';
        if ($data['current_category'] === 'ielts') {
            $page_title = 'IELTS Workbooks & Materials | AlphaMindz Shop';
        } elseif ($data['current_category'] === 'achievers') {
            $page_title = 'Achievers Workbooks for Children | AlphaMindz Shop';
        }

        $this->load->view('public_header', array('title' => $page_title));
        $this->load->view('shop', $data);
        $this->load->view('public_footer');
    }
}
