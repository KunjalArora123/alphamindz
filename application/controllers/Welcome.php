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

		// Fetch products for homepage
		$this->db->order_by('id', 'DESC');
		$this->db->limit(4);
		$p_query = $this->db->get('products');
		$data['products'] = $p_query ? $p_query->result() : array();

		// Fetch latest news for homepage
		$this->db->where('status', 'active');
		$this->db->order_by('news_date', 'DESC');
		$this->db->limit(3);
		$n_query = $this->db->get('latest_news');
		$data['latest_news'] = $n_query ? $n_query->result() : array();

		// Fetch homepage hero image setting
		$hero_setting = $this->db->get_where('system_settings', array('setting_key' => 'home_hero_image'))->row();
		$data['hero_image'] = ($hero_setting && !empty($hero_setting->setting_value)) ? $hero_setting->setting_value : null;

		// Fetch promotional showcase video settings
		$video_setting = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video'))->row();
		$data['promo_video'] = ($video_setting && !empty($video_setting->setting_value)) ? $video_setting->setting_value : null;

		$video_type_setting = $this->db->get_where('system_settings', array('setting_key' => 'home_promo_video_type'))->row();
		$data['promo_video_type'] = ($video_type_setting && !empty($video_type_setting->setting_value)) ? $video_type_setting->setting_value : 'file';

		$this->load->view('public_header');
		$this->load->view('home', $data);
		$this->load->view('public_footer');
	}
}
