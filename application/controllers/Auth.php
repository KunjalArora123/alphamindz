<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
    }

    public function login() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('student');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->db->get_where('users', array('email' => $email))->row();

            if ($user && password_verify($password, $user->password)) {
                $userdata = array(
                    'user_id' => $user->id,
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'first_name' => $user->first_name, // Store first_name separately for greetings
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'user_logged_in' => TRUE
                );
                $this->session->set_userdata($userdata);
                $this->session->set_flashdata('success', 'Welcome back, ' . $user->first_name . '!');
                redirect('student');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                redirect('auth/login');
            }
        }

        $this->load->view('student/includes/header', array('title' => 'Login | AlphaMindz'));
        $this->load->view('login');
        $this->load->view('student/includes/footer');
    }

    public function register() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('student');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $confirm_password = $this->input->post('confirm_password');

            if ($password !== $confirm_password) {
                $this->session->set_flashdata('error', 'Passwords do not match.');
                redirect('auth/register');
            }

            // Check if email exists
            $existing_user = $this->db->get_where('users', array('email' => $email))->row();
            if ($existing_user) {
                $this->session->set_flashdata('error', 'Email address is already registered.');
                redirect('auth/register');
            }

            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s')
            );

            if ($this->db->insert('users', $data)) {
                $this->session->set_flashdata('success', 'Registration successful! Please login.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'An error occurred. Please try again.');
                redirect('auth/register');
            }
        }

        $this->load->view('student/includes/header', array('title' => 'Register | AlphaMindz'));
        $this->load->view('register');
        $this->load->view('student/includes/footer');
    }

    public function dashboard() {
        redirect('student');
    }

    public function logout() {
        $this->session->unset_userdata(array('user_id', 'user_name', 'user_email', 'user_role', 'user_logged_in'));
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('auth/login');
    }
}
