<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        $category = $this->input->get('category');
        $type = $this->input->get('type');

        $this->db->where('status', 'active');
        if (!empty($category) && $category !== 'all') {
            $this->db->where('category', $category);
        }
        if (!empty($type) && in_array($type, array('photo', 'video'))) {
            $this->db->where('type', $type);
        }

        $this->db->order_by('sort_order', 'ASC');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('gallery');
        $data['items'] = $query ? $query->result() : array();

        // Get distinct categories
        $cat_query = $this->db->select('category, COUNT(*) as count')
                              ->where('status', 'active')
                              ->group_by('category')
                              ->get('gallery');
        $data['categories'] = $cat_query ? $cat_query->result() : array();

        $data['active_category'] = $category ?: 'all';
        $data['active_type'] = $type ?: 'all';

        $this->load->view('public_header', array('title' => 'Gallery | AlphaMindz'));
        $this->load->view('gallery', $data);
        $this->load->view('public_footer');
    }
}
