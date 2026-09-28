<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('download');
    }

    public function book($token = null) {
        if (!$token) {
            $token = $this->input->get('token');
        }

        if (empty($token)) {
            $data['error_title'] = 'Invalid Download Link';
            $data['error_message'] = 'No download token was provided. Please check the link URL.';
            $this->load->view('download_error', $data);
            return;
        }

        // Fetch link record
        $link = $this->db->get_where('temporary_download_links', array('token' => $token))->row();

        if (!$link) {
            $data['error_title'] = 'Download Link Not Found';
            $data['error_message'] = 'This download link token does not exist or has been removed.';
            $this->load->view('download_error', $data);
            return;
        }

        // Check Expiration Time (TTL)
        $current_time = time();
        $expires_time = strtotime($link->expires_at);

        if ($current_time > $expires_time) {
            $data['error_title'] = 'Download Link Expired';
            $data['error_message'] = 'This temporary download link expired on ' . date('M d, Y h:i:s A', $expires_time) . '. The link TTL of ' . number_format($link->ttl_seconds) . ' seconds has elapsed. Please request a new link.';
            $this->load->view('download_error', $data);
            return;
        }

        // File path check
        $full_path = FCPATH . $link->file_path;
        if (!file_exists($full_path)) {
            $data['error_title'] = 'File Not Found';
            $data['error_message'] = 'The requested PDF file is currently unavailable on the server.';
            $this->load->view('download_error', $data);
            return;
        }

        // Force PDF download
        $file_data = file_get_contents($full_path);
        $download_filename = !empty($link->file_name) ? $link->file_name : basename($full_path);
        if (strtolower(pathinfo($download_filename, PATHINFO_EXTENSION)) !== 'pdf') {
            $download_filename .= '.pdf';
        }

        force_download($download_filename, $file_data);
    }
}
