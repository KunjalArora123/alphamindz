<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('record_admin_login_log')) {
    function record_admin_login_log($username, $email = '', $status = 'success') {
        date_default_timezone_set('Asia/Kolkata');
        $CI =& get_instance();
        $CI->load->database();
        $CI->load->library('user_agent');

        $ip = $CI->input->ip_address();
        if (empty($ip) || $ip === '::1') {
            $ip = '127.0.0.1';
        }

        $browser = 'Unknown Browser';
        if ($CI->agent->is_browser()) {
            $browser = $CI->agent->browser() . ' ' . $CI->agent->version();
        } elseif ($CI->agent->is_robot()) {
            $browser = 'Bot (' . $CI->agent->robot() . ')';
        } elseif ($CI->agent->is_mobile()) {
            $browser = 'Mobile (' . $CI->agent->mobile() . ')';
        }

        $platform = $CI->agent->platform() ? $CI->agent->platform() : 'Unknown OS';

        $device_type = 'Desktop';
        if ($CI->agent->is_mobile()) {
            $device_type = 'Mobile';
        }

        $log_data = array(
            'username' => $username,
            'email' => !empty($email) ? $email : $username,
            'ip_address' => $ip,
            'user_agent' => substr($CI->agent->agent_string(), 0, 500),
            'device_type' => $device_type,
            'browser' => $browser,
            'platform' => $platform,
            'status' => $status,
            'login_time' => date('Y-m-d H:i:s')
        );

        $CI->db->insert('admin_login_logs', $log_data);
    }
}
