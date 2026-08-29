<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard');
        } else {
            $this->load->view('admin/login');
        }
    }

    public function authenticate() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $query = $this->db->get_where('admins', array('username' => $username, 'password' => $password));

        if ($query->num_rows() > 0) {
            $this->session->set_userdata('admin_logged_in', true);
            $this->session->set_userdata('username', $username);
            redirect('admin/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('admin');
        }
    }

    public function dashboard() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->view('admin/dashboard');
    }

    public function courses() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin');
        }
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('courses');
        $data['courses'] = $query->result();
        
        $this->load->view('admin/courses', $data);
    }

    public function add_course() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->view('admin/course_form');
    }

    public function save_course() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'duration' => $this->input->post('duration'),
            'status' => $this->input->post('status'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('courses', $data);
        $this->session->set_flashdata('success', 'Course added successfully!');
        redirect('admin/courses');
    }

    public function edit_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['course'] = $this->db->get_where('courses', array('id' => $id))->row();
        $this->load->view('admin/course_form', $data);
    }

    public function update_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'duration' => $this->input->post('duration'),
            'status' => $this->input->post('status')
        );
        $this->db->where('id', $id);
        $this->db->update('courses', $data);
        $this->session->set_flashdata('success', 'Course updated successfully!');
        redirect('admin/courses');
    }

    public function delete_course($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('courses');
        $this->session->set_flashdata('success', 'Course deleted successfully!');
        redirect('admin/courses');
    }

    public function logout() {
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('username');
        redirect('admin');
    }

    // ==========================================
    // SHOP PRODUCTS MANAGEMENT
    // ==========================================

    public function products() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $data['products'] = $this->db->get('products')->result();
        $data['page_title'] = 'Manage Shop Products';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/products', $data);
        $this->load->view('admin/includes/footer');
    }

    public function assessments() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        
        $this->load->database();
        
        // Join with users table to get student names
        $this->db->select('test_attempts.*, users.first_name, users.last_name, users.email');
        $this->db->from('test_attempts');
        $this->db->join('users', 'users.id = test_attempts.user_id', 'left');
        $this->db->order_by('test_attempts.completed_at', 'DESC');
        $data['attempts'] = $this->db->get()->result();

        $data['page_title'] = 'Manage Assessments';

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/assessments', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_product() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Product';
        $data['use_editor'] = true;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function save_product() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $image_url = '';
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $image_url = 'assets/uploads/products/' . $upload_data['file_name'];
            }
        }

        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'price' => $this->input->post('price'),
            'image_url' => $image_url,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('products', $data);
        $this->session->set_flashdata('success', 'Product added successfully!');
        redirect('admin/products');
    }

    public function edit_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['product'] = $this->db->get_where('products', array('id' => $id))->row();
        $data['page_title'] = 'Edit Product';
        $data['use_editor'] = true;

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/product_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function update_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'price' => $this->input->post('price')
        );

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './assets/uploads/products/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $data['image_url'] = 'assets/uploads/products/' . $upload_data['file_name'];
            }
        }

        $this->db->where('id', $id);
        $this->db->update('products', $data);
        $this->session->set_flashdata('success', 'Product updated successfully!');
        redirect('admin/products');
    }

    public function delete_product($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('products');
        $this->session->set_flashdata('success', 'Product deleted successfully!');
        redirect('admin/products');
    }
    // ==========================================
    // BLOGS MANAGEMENT
    // ==========================================

    public function blogs() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $data['blogs'] = $this->db->get('blogs')->result();
        $data['page_title'] = 'Manage Blogs';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blogs', $data);
        $this->load->view('admin/includes/footer');
    }

    public function add_blog() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $data['page_title'] = 'Add New Blog';
        $data['use_editor'] = true;
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blog_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function save_blog() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'author' => $this->input->post('author'),
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('blogs', $data);
        $this->session->set_flashdata('success', 'Blog added successfully!');
        redirect('admin/blogs');
    }

    public function edit_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['blog'] = $this->db->get_where('blogs', array('id' => $id))->row();
        $data['page_title'] = 'Edit Blog';
        $data['use_editor'] = true;

        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/blog_form', $data);
        $this->load->view('admin/includes/footer', $data);
    }

    public function update_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'author' => $this->input->post('author'),
            'content' => $this->input->post('content'),
            'status' => $this->input->post('status')
        );
        $this->db->where('id', $id);
        $this->db->update('blogs', $data);
        $this->session->set_flashdata('success', 'Blog updated successfully!');
        redirect('admin/blogs');
    }

    public function delete_blog($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('blogs');
        $this->session->set_flashdata('success', 'Blog deleted successfully!');
        redirect('admin/blogs');
    }
    // ==========================================
    // USERS MANAGEMENT
    // ==========================================

    public function users() {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        $this->db->order_by('id', 'DESC');
        $data['users'] = $this->db->get('users')->result();
        $data['page_title'] = 'Manage Users';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/users', $data);
        $this->load->view('admin/includes/footer');
    }

    public function edit_user($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data['user'] = $this->db->get_where('users', array('id' => $id))->row();
        $data['page_title'] = 'Edit User';
        
        $this->load->view('admin/includes/header', $data);
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/user_form', $data);
        $this->load->view('admin/includes/footer');
    }

    public function update_user($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role')
        );

        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $this->session->set_flashdata('success', 'User updated successfully!');
        redirect('admin/users');
    }

    public function delete_user($id) {
        if (!$this->session->userdata('admin_logged_in')) redirect('admin');
        $this->load->database();
        
        $this->db->where('id', $id);
        $this->db->delete('users');
        $this->session->set_flashdata('success', 'User deleted successfully!');
        redirect('admin/users');
    }
}
