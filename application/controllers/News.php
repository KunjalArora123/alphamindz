<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
	}

	public function index()
	{
		$this->db->where('status', 'active');
		$this->db->order_by('news_date', 'DESC');
		$query = $this->db->get('latest_news');
		$data['all_news'] = $query ? $query->result() : array();

		$data['page_title'] = 'Latest News';

		$this->load->view('public_header', $data);
		$this->load->view('news', $data);
		$this->load->view('public_footer');
	}
}
