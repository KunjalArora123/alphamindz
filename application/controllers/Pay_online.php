<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay_online extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = 'Pay Online | Scan & Pay | AlphaMindz';
        $this->load->view('pay_online', $data);
    }
}
