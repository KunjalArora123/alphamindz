<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MaintenanceHook {

    public function check_maintenance() {
        $CI =& get_instance();

        // 1. Determine requested URI
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        // 2. Allow Superadmin routes and static assets to bypass maintenance mode completely
        if (strpos($request_uri, '/superadmin') !== false || strpos($request_uri, 'superadmin') !== false) {
            return;
        }

        // Ignore static asset requests if any
        if (preg_match('/\.(png|jpg|jpeg|gif|css|js|ico|svg|woff2?|ttf)$/i', $request_uri)) {
            return;
        }

        // 3. Check system_settings database table
        $CI->load->database();
        if (!$CI->db->table_exists('system_settings')) {
            return;
        }

        $row = $CI->db->get_where('system_settings', array('setting_key' => 'maintenance_mode'))->row();
        $is_maintenance = ($row && $row->setting_value == '1');

        if ($is_maintenance) {
            $msg_row = $CI->db->get_where('system_settings', array('setting_key' => 'maintenance_message'))->row();
            $data['maintenance_message'] = ($msg_row && !empty($msg_row->setting_value)) 
                ? $msg_row->setting_value 
                : 'We are currently performing scheduled maintenance. We will be back online shortly!';

            $CI->load->view('maintenance', $data);
            exit;
        }
    }
}
