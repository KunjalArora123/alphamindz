<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        // In a real application, you might fetch these from a database.
        // For now, we are using the static view layout.
        $this->load->view('assessments');
    }
}
