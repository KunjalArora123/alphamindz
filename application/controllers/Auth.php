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
            $email = trim($this->input->post('email'));
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
            $first_name = trim($this->input->post('first_name'));
            $last_name = trim($this->input->post('last_name'));
            $email = trim($this->input->post('email'));
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

    public function forgot_password() {
        if ($this->session->userdata('user_logged_in')) {
            redirect('student');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $email = trim($this->input->post('email'));
            $user = $this->db->get_where('users', array('email' => $email))->row();

            if ($user) {
                // Generate secure random token and expiration (1 hour)
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $this->db->where('id', $user->id);
                $this->db->update('users', array(
                    'reset_token' => $token,
                    'reset_token_expires' => $expires
                ));

                // Send email
                $reset_link = site_url('auth/reset_password/' . $token);

                $this->load->config('email');
                $this->load->library('email');

                $from_email = getenv('FROM_EMAIL') ?: 'info.alphamindz@gmail.com';
                $from_name  = getenv('FROM_NAME') ?: 'AlphaMindz';

                $this->email->from($from_email, $from_name);
                $this->email->to($email);
                $this->email->subject('Password Reset Request - AlphaMindz');

                $message = '
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
    <div style="max-width: 550px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e9ecef;">
        <div style="background-color: #2c3e50; padding: 25px; text-align: center;">
            <h2 style="color: #ffffff; margin: 0; font-size: 22px; font-family: \'Playfair Display\', Georgia, serif;">AlphaMindz</h2>
        </div>
        <div style="padding: 30px; color: #333333; line-height: 1.6;">
            <p style="font-size: 16px;">Hello <strong>' . htmlspecialchars($user->first_name) . '</strong>,</p>
            <p style="font-size: 15px;">We received a request to reset the password for your AlphaMindz account. Click the button below to reset your password:</p>
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $reset_link . '" style="background-color: #007bff; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; font-size: 15px;">Reset Password</a>
            </div>
            <p style="font-size: 14px; color: #6c757d;">This link is valid for 1 hour. If you did not request a password reset, you can safely ignore this email.</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 25px 0;">
            <p style="font-size: 12px; color: #888888;">If the button above does not work, copy and paste this URL into your web browser:<br><a href="' . $reset_link . '" style="color: #007bff; word-break: break-all;">' . $reset_link . '</a></p>
        </div>
        <div style="background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; border-top: 1px solid #e9ecef;">
            &copy; ' . date('Y') . ' AlphaMindz. All rights reserved.
        </div>
    </div>
</body>
</html>';

                $this->email->message($message);

                if (!$this->email->send()) {
                    log_message('error', 'Email send error: ' . $this->email->print_debugger(array('headers')));
                }
            }

            $this->session->set_flashdata('success', 'If an account with that email address exists, a password reset link has been sent.');
            redirect('auth/forgot_password');
        }

        $this->load->view('student/includes/header', array('title' => 'Forgot Password | AlphaMindz'));
        $this->load->view('forgot_password');
        $this->load->view('student/includes/footer');
    }

    public function reset_password($token = NULL) {
        if ($this->session->userdata('user_logged_in')) {
            redirect('student');
        }

        if (empty($token)) {
            $this->session->set_flashdata('error', 'Invalid password reset request.');
            redirect('auth/forgot_password');
        }

        // Verify token in database and ensure it has not expired
        $this->db->where('reset_token', $token);
        $this->db->where('reset_token_expires >=', date('Y-m-d H:i:s'));
        $user = $this->db->get('users')->row();

        if (!$user) {
            $this->session->set_flashdata('error', 'The password reset link is invalid or has expired. Please request a new one.');
            redirect('auth/forgot_password');
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $password = $this->input->post('password');
            $confirm_password = $this->input->post('confirm_password');

            if ($password !== $confirm_password) {
                $this->session->set_flashdata('error', 'Passwords do not match.');
                redirect('auth/reset_password/' . $token);
            }

            if (strlen($password) < 6) {
                $this->session->set_flashdata('error', 'Password must be at least 6 characters long.');
                redirect('auth/reset_password/' . $token);
            }

            // Update user's password and clear reset token
            $this->db->where('id', $user->id);
            $this->db->update('users', array(
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => NULL,
                'reset_token_expires' => NULL
            ));

            $this->session->set_flashdata('success', 'Your password has been reset successfully! You can now log in.');
            redirect('auth/login');
        }

        $data = array(
            'title' => 'Reset Password | AlphaMindz',
            'token' => $token
        );

        $this->load->view('student/includes/header', $data);
        $this->load->view('reset_password', $data);
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
