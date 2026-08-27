<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('public_header');
		$this->load->view('home');
		$this->load->view('public_footer');
	}
}
