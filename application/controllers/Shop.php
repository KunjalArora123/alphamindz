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
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('products');
        $data['products'] = $query->result();

        $this->load->view('public_header', array('title' => 'Shop | AlphaMindz'));
        $this->load->view('shop', $data);
        $this->load->view('public_footer');
    }
}
