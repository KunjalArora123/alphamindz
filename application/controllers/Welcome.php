<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		$this->db->limit(4);
		$query = $this->db->get('articles');
		$data['recent_articles'] = $query ? $query->result() : array();

		// Fetch active testimonials from database
		if ($this->db->table_exists('testimonials')) {
			$this->db->where('status', 'active');
			$this->db->order_by('sort_order', 'ASC');
			$t_query = $this->db->get('testimonials');
			$data['testimonials'] = $t_query ? $t_query->result_array() : array();
		} else {
			$data['testimonials'] = array();
		}

		$this->load->view('public_header');
		$this->load->view('home', $data);
		$this->load->view('public_footer');
	}
}
