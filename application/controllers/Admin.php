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
        
        $data = array(
            'title' => $this->input->post('title'),
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title')))),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
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
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price')
        );
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
}
