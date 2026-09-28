<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reachus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = 'Reach Us | Our Branch Locations | AlphaMindz';
        $this->load->view('reachus', $data);
    }
}
