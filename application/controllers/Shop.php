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

        // Fetch dynamic categories from product_categories table
        $this->db->where('is_active', 1)->order_by('sort_order', 'ASC')->order_by('id', 'ASC');
        $categories = $this->db->get('product_categories')->result();

        // Calculate count for each category
        foreach ($categories as &$cat) {
            $this->db->where('category', $cat->slug);
            $cat->product_count = $this->db->count_all_results('products');
        }

        $this->db->order_by('id', 'ASC');

        if (!empty($category) && $category !== 'all') {
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
        $data['categories'] = $categories;
        $data['total_count'] = $this->db->count_all('products');

        // Find active category object for title & description
        $active_cat_obj = null;
        if (!empty($category) && $category !== 'all') {
            foreach ($categories as $cat) {
                if (strtolower($cat->slug) === strtolower($category)) {
                    $active_cat_obj = $cat;
                    break;
                }
            }
        }
        $data['active_category_obj'] = $active_cat_obj;

        $page_title = 'Shop E-Books & Workbooks | AlphaMindz';
        if ($active_cat_obj) {
            $page_title = $active_cat_obj->title . ' | AlphaMindz Shop';
        }

        $this->load->view('public_header', array('title' => $page_title));
        $this->load->view('shop', $data);
        $this->load->view('public_footer');
    }
}
