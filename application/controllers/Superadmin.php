<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form', 'login_logger'));
        $this->load->library('session');
    }

    private function _check_auth() {
        if (!$this->session->userdata('superadmin_logged_in')) {
            redirect('superadmin/login');
            exit;
        }
    }

    public function index() {
        if ($this->session->userdata('superadmin_logged_in')) {
            redirect('superadmin/dashboard');
        } else {
            $this->login();
        }
    }

    public function login() {
        if ($this->session->userdata('superadmin_logged_in')) {
            redirect('superadmin/dashboard');
        }
        $this->load->view('superadmin/login');
    }

    public function authenticate() {
        $email = trim($this->input->post('email'));
        $password = $this->input->post('password');

        if (empty($email) || empty($password)) {
            $this->session->set_flashdata('error', 'Please enter email/username and password.');
            redirect('superadmin/login');
            return;
        }

        $authenticated = false;
        $superadmin_data = null;

        // 1. Check specified Superadmin password & email explicitly
        if (($email === 'kunjalarora2@gmail.com' || $email === 'superadmin') && $password === 'password@711') {
            $authenticated = true;
            $superadmin_data = array(
                'email' => 'kunjalarora2@gmail.com',
                'username' => 'kunjalarora2',
                'name' => 'Super Admin',
                'role' => 'superadmin'
            );
        }

        // 2. Check DB 'admins' table if explicit check wasn't triggered
        if (!$authenticated) {
            $admin_row = $this->db->group_start()
                ->where('username', $email)
                ->or_where('email', $email)
                ->group_end()
                ->get('admins')->row();

            if ($admin_row) {
                if ($password === $admin_row->password || password_verify($password, $admin_row->password)) {
                    $authenticated = true;
                    $superadmin_data = array(
                        'email' => !empty($admin_row->email) ? $admin_row->email : $admin_row->username,
                        'username' => $admin_row->username,
                        'name' => 'Super Admin (' . $admin_row->username . ')',
                        'role' => isset($admin_row->role) ? $admin_row->role : 'superadmin'
                    );
                }
            }
        }

        // 3. Check DB 'users' table for superadmin role if needed
        if (!$authenticated) {
            $user_row = $this->db->get_where('users', array('email' => $email, 'role' => 'superadmin'))->row();
            if ($user_row && (password_verify($password, $user_row->password) || $password === 'password@711')) {
                $authenticated = true;
                $superadmin_data = array(
                    'email' => $user_row->email,
                    'username' => isset($user_row->username) ? $user_row->username : $user_row->email,
                    'name' => $user_row->first_name . ' ' . $user_row->last_name,
                    'role' => 'superadmin'
                );
            }
        }

        if ($authenticated) {
            $session_data = array(
                'superadmin_logged_in' => true,
                'admin_logged_in' => true, // also give admin access
                'superadmin_email' => $superadmin_data['email'],
                'superadmin_username' => $superadmin_data['username'],
                'superadmin_name' => $superadmin_data['name'],
                'superadmin_role' => $superadmin_data['role']
            );
            $this->session->set_userdata($session_data);
            record_admin_login_log($superadmin_data['username'], $superadmin_data['email'], 'success');
            $this->session->set_flashdata('success', 'Welcome back to Superadmin Portal!');
            redirect('superadmin/dashboard');
        } else {
            record_admin_login_log($email, $email, 'failed');
            $this->session->set_flashdata('error', 'Invalid Superadmin credentials.');
            redirect('superadmin/login');
        }
    }

    public function dashboard() {
        $this->_check_auth();

        $data['page_title'] = 'Superadmin Control Center';
        
        // System metrics
        $data['total_users'] = $this->db->count_all_results('users');
        $data['total_admins'] = $this->db->count_all_results('admins');
        
        if ($this->db->table_exists('courses')) {
            $data['total_courses'] = $this->db->count_all_results('courses');
        } else {
            $data['total_courses'] = 0;
        }

        if ($this->db->table_exists('assessments')) {
            $data['total_assessments'] = $this->db->count_all_results('assessments');
        } else {
            $data['total_assessments'] = 0;
        }

        if ($this->db->table_exists('payment_orders')) {
            $data['total_orders'] = $this->db->count_all_results('payment_orders');
        } else {
            $data['total_orders'] = 0;
        }

        if ($this->db->table_exists('admin_login_logs')) {
            $data['total_logs'] = $this->db->count_all_results('admin_login_logs');
        } else {
            $data['total_logs'] = 0;
        }

        // Fetch recent users
        $data['recent_users'] = $this->db->order_by('id', 'DESC')->limit(10)->get('users')->result();
        
        // Fetch all admins
        $data['admins_list'] = $this->db->order_by('id', 'DESC')->get('admins')->result();

        $this->load->view('superadmin/includes/header', $data);
        $this->load->view('superadmin/includes/sidebar');
        $this->load->view('superadmin/dashboard', $data);
        $this->load->view('superadmin/includes/footer');
    }

    public function users() {
        $this->_check_auth();

        $data['page_title'] = 'Manage System Users & Roles';
        $search = $this->input->get('search');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('first_name', $search);
            $this->db->or_like('last_name', $search);
            $this->db->or_like('email', $search);
            if ($this->db->field_exists('username', 'users')) {
                $this->db->or_like('username', $search);
            }
            $this->db->group_end();
            $data['search'] = $search;
        }

        $data['users'] = $this->db->order_by('id', 'DESC')->get('users')->result();

        $this->load->view('superadmin/includes/header', $data);
        $this->load->view('superadmin/includes/sidebar');
        $this->load->view('superadmin/users', $data);
        $this->load->view('superadmin/includes/footer');
    }

    public function create_admin() {
        $this->_check_auth();

        $username = trim($this->input->post('username'));
        $email = trim($this->input->post('email'));
        $password = $this->input->post('password');
        $role = $this->input->post('role') ? $this->input->post('role') : 'admin';

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username and password are required.');
            redirect('superadmin/dashboard');
            return;
        }

        // Check if admin already exists
        $existing = $this->db->get_where('admins', array('username' => $username))->row();
        if ($existing) {
            $this->session->set_flashdata('error', 'Admin user already exists.');
            redirect('superadmin/dashboard');
            return;
        }

        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('admins', $data);
        $this->session->set_flashdata('success', 'Admin user created successfully.');
        redirect('superadmin/dashboard');
    }

    public function change_user_role($id) {
        $target_user = $this->db->get_where('users', array('id' => $id))->row();
        if ($target_user && $target_user->email === 'kunjalarora2@gmail.com') {
            $this->session->set_flashdata('error', 'Action denied: The role for kunjalarora2@gmail.com cannot be changed.');
            redirect('superadmin/users');
            return;
        }
        $this->_check_auth();
        $new_role = $this->input->post('role');
        
        if (in_array($new_role, array('student', 'admin', 'superadmin'))) {
            $this->db->where('id', $id)->update('users', array('role' => $new_role));
            
            // If made admin/superadmin, ensure record in admins table too
            if ($new_role === 'admin' || $new_role === 'superadmin') {
                $user = $this->db->get_where('users', array('id' => $id))->row();
                if ($user) {
                    $u_name = !empty($user->username) ? $user->username : explode('@', $user->email)[0];
                    $adm_exists = $this->db->get_where('admins', array('username' => $u_name))->row();
                    if (!$adm_exists) {
                        $this->db->insert('admins', array(
                            'username' => $u_name,
                            'email' => $user->email,
                            'password' => 'password@711', // default password
                            'role' => $new_role,
                            'created_at' => date('Y-m-d H:i:s')
                        ));
                    }
                }
            }

            $this->session->set_flashdata('success', 'User role updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Invalid role selected.');
        }

        redirect('superadmin/users');
    }

    public function delete_user($id) {
        $target_user = $this->db->get_where('users', array('id' => $id))->row();
        if ($target_user && $target_user->email === 'kunjalarora2@gmail.com') {
            $this->session->set_flashdata('error', 'Action denied: The account kunjalarora2@gmail.com cannot be deleted.');
            redirect('superadmin/users');
            return;
        }
        $this->_check_auth();
        $this->db->where('id', $id)->delete('users');
        $this->session->set_flashdata('success', 'User deleted successfully.');
        redirect('superadmin/users');
    }

    public function delete_admin($id) {
        $target_admin = $this->db->get_where('admins', array('id' => $id))->row();
        if ($target_admin && $target_admin->email === 'kunjalarora2@gmail.com') {
            $this->session->set_flashdata('error', 'Action denied: The account kunjalarora2@gmail.com cannot be deleted.');
            redirect('superadmin/dashboard');
            return;
        }
        $this->_check_auth();
        $this->db->where('id', $id)->delete('admins');
        $this->session->set_flashdata('success', 'Admin account deleted.');
        redirect('superadmin/dashboard');
    }

    public function logs() {
        $this->_check_auth();

        $data['page_title'] = 'Admin Activity & Login Logs';
        $search = $this->input->get('search');
        $status = $this->input->get('status');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('username', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('ip_address', $search);
            $this->db->or_like('browser', $search);
            $this->db->or_like('platform', $search);
            $this->db->group_end();
            $data['search'] = $search;
        }

        if (!empty($status) && in_array($status, array('success', 'failed'))) {
            $this->db->where('status', $status);
            $data['status'] = $status;
        }

        $data['logs'] = $this->db->order_by('id', 'DESC')->limit(200)->get('admin_login_logs')->result();
        $data['total_logs'] = $this->db->count_all_results('admin_login_logs');

        $this->load->view('superadmin/includes/header', $data);
        $this->load->view('superadmin/includes/sidebar');
        $this->load->view('superadmin/logs', $data);
        $this->load->view('superadmin/includes/footer');
    }

    public function clear_logs() {
        $this->_check_auth();
        $this->db->empty_table('admin_login_logs');
        $this->session->set_flashdata('success', 'All login activity logs cleared.');
        redirect('superadmin/logs');
    }

    public function website_access() {
        $this->_check_auth();

        $data['page_title'] = 'Website Access & Maintenance Mode';

        $mode_row = $this->db->get_where('system_settings', array('setting_key' => 'maintenance_mode'))->row();
        $msg_row = $this->db->get_where('system_settings', array('setting_key' => 'maintenance_message'))->row();

        $data['maintenance_mode'] = ($mode_row) ? $mode_row->setting_value : '0';
        $data['maintenance_message'] = ($msg_row) ? $msg_row->setting_value : 'We are currently performing scheduled maintenance. We will be back online shortly!';

        $this->load->view('superadmin/includes/header', $data);
        $this->load->view('superadmin/includes/sidebar');
        $this->load->view('superadmin/website_access', $data);
        $this->load->view('superadmin/includes/footer');
    }

    public function toggle_maintenance() {
        $this->_check_auth();

        $target_mode = $this->input->post('target_mode');
        $new_mode = ($target_mode == '1') ? '1' : '0';

        $check = $this->db->get_where('system_settings', array('setting_key' => 'maintenance_mode'))->row();
        if ($check) {
            $this->db->where('setting_key', 'maintenance_mode')->update('system_settings', array('setting_value' => $new_mode));
        } else {
            $this->db->insert('system_settings', array('setting_key' => 'maintenance_mode', 'setting_value' => $new_mode));
        }

        if ($new_mode == '1') {
            $this->session->set_flashdata('error', 'Website is now OFFLINE (Maintenance Mode Enabled). Main site, student panel & admin panel are blocked.');
        } else {
            $this->session->set_flashdata('success', 'Website is now ONLINE. All services are restored.');
        }

        redirect('superadmin/website_access');
    }

    public function update_maintenance_message() {
        $this->_check_auth();

        $message = trim($this->input->post('maintenance_message'));
        if (!empty($message)) {
            $check = $this->db->get_where('system_settings', array('setting_key' => 'maintenance_message'))->row();
            if ($check) {
                $this->db->where('setting_key', 'maintenance_message')->update('system_settings', array('setting_value' => $message));
            } else {
                $this->db->insert('system_settings', array('setting_key' => 'maintenance_message', 'setting_value' => $message));
            }
            $this->session->set_flashdata('success', 'Maintenance announcement message updated.');
        }

        redirect('superadmin/website_access');
    }

    public function branding() {
        $this->_check_auth();
        $this->load->helper('form');
        $this->load->library('upload');
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $config['upload_path'] = FCPATH . 'assets/images/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|ico';
            $config['max_size'] = 5000;
            $config['overwrite'] = true;
            
            $this->upload->initialize($config);
            $success = false;

            if (!empty($_FILES['logo']['name'])) {
                $config['file_name'] = 'logo.png';
                $this->upload->initialize($config);
                if ($this->upload->do_upload('logo')) {
                    $success = true;
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('',''));
                }
            }

            if (!empty($_FILES['favicon']['name'])) {
                $config['file_name'] = 'favicon.png';
                $this->upload->initialize($config);
                if ($this->upload->do_upload('favicon')) {
                    $success = true;
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('',''));
                }
            }
            
            if ($success) {
                $this->session->set_flashdata('success', 'Branding images updated successfully. Please hard refresh (Ctrl+F5) to see the changes.');
            }
            
            redirect('superadmin/branding');
            return;
        }

        $data['page_title'] = 'Site Branding';
        $this->load->view('superadmin/includes/header', $data);
        $this->load->view('superadmin/includes/sidebar');
        $this->load->view('superadmin/branding');
        $this->load->view('superadmin/includes/footer');
    }

    public function logout() {
        $this->session->unset_userdata(array('superadmin_logged_in', 'superadmin_email', 'superadmin_username', 'superadmin_name', 'superadmin_role'));
        $this->session->set_flashdata('success', 'Superadmin logged out successfully.');
        redirect('superadmin/login');
    }
}

