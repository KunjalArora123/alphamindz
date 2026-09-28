<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
    }

    private function _get_cart() {
        $cart = $this->session->userdata('cart');
        return is_array($cart) ? $cart : array();
    }

    private function _save_cart($cart) {
        $this->session->set_userdata('cart', $cart);
    }

    private function _get_cart_count() {
        $cart = $this->_get_cart();
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)$item['qty'];
        }
        return $count;
    }

    public function index() {
        $cart = $this->_get_cart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price']) * intval($item['qty']);
        }

        $data['cart'] = $cart;
        $data['subtotal'] = $subtotal;
        $data['total'] = $subtotal;
        $data['cart_count'] = $this->_get_cart_count();

        $this->load->view('public_header', array('title' => 'Shopping Cart | AlphaMindz'));
        $this->load->view('cart', $data);
        $this->load->view('public_footer');
    }

    public function add() {
        $item_type = $this->input->post('item_type'); // 'product' or 'assessment'
        $item_id = intval($this->input->post('item_id'));
        $qty = intval($this->input->post('qty')) > 0 ? intval($this->input->post('qty')) : 1;

        if (!$item_type || !$item_id) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid item selection']);
                return;
            }
            redirect('cart');
            return;
        }

        $cart = $this->_get_cart();

        if ($item_type === 'product') {
            $product = $this->db->get_where('products', array('id' => $item_id))->row();
            if (!$product) {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
                    return;
                }
                redirect('shop');
                return;
            }

            $key = 'product_' . $product->id;
            // Clean price numerical string
            $raw_price = preg_replace('/[^0-9.]/', '', $product->price);
            $price = floatval($raw_price);

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'key' => $key,
                    'type' => 'product',
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $price,
                    'qty' => $qty,
                    'image_url' => $product->image_url ? base_url($product->image_url) : ''
                ];
            }
        } elseif ($item_type === 'assessment') {
            $assessment = $this->db->get_where('assessments', array('id' => $item_id))->row();
            if (!$assessment) {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Assessment not found']);
                    return;
                }
                redirect('assessments');
                return;
            }

            $key = 'assessment_' . $assessment->id;
            $price = floatval($assessment->price);

            if (isset($cart[$key])) {
                $cart[$key]['qty'] = 1; // max 1 enrollment per assessment test
            } else {
                $cart[$key] = [
                    'key' => $key,
                    'type' => 'assessment',
                    'id' => $assessment->id,
                    'title' => $assessment->title,
                    'price' => $price,
                    'qty' => 1,
                    'image_url' => ''
                ];
            }
        } elseif ($item_type === 'course') {
            $course = $this->db->get_where('courses', array('id' => $item_id))->row();
            if (!$course) {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Course not found']);
                    return;
                }
                redirect('courses');
                return;
            }

            $tier_key = strtolower($this->input->post('tier_key') ? $this->input->post('tier_key') : 'basic');
            $tier_row = $this->db->get_where('course_tiers', array('course_id' => $course->id, 'tier_key' => $tier_key))->row();

            $tier_name = $tier_row ? $tier_row->tier_name : ucfirst($tier_key);
            $price = $tier_row ? floatval($tier_row->price) : floatval($course->price);

            $key = 'course_' . $course->id . '_' . $tier_key;

            if (isset($cart[$key])) {
                $cart[$key]['qty'] = 1;
            } else {
                $cart[$key] = [
                    'key' => $key,
                    'type' => 'course',
                    'id' => $course->id,
                    'tier_key' => $tier_key,
                    'title' => $course->title . ' (' . $tier_name . ' Tier)',
                    'price' => $price,
                    'qty' => 1,
                    'image_url' => ''
                ];
            }
        }

        $this->_save_cart($cart);
        $total_count = $this->_get_cart_count();

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Added to cart successfully!',
                'cart_count' => $total_count,
                'redirect' => base_url('cart')
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Item added to cart!');
        redirect('cart');
    }

    public function update() {
        $key = $this->input->post('key');
        $qty = intval($this->input->post('qty'));

        $cart = $this->_get_cart();
        if (isset($cart[$key])) {
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                // If it's an assessment, limit to 1
                if ($cart[$key]['type'] === 'assessment') {
                    $cart[$key]['qty'] = 1;
                } else {
                    $cart[$key]['qty'] = $qty;
                }
            }
            $this->_save_cart($cart);
        }

        if ($this->input->is_ajax_request()) {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += floatval($item['price']) * intval($item['qty']);
            }
            echo json_encode([
                'status' => 'success',
                'cart_count' => $this->_get_cart_count(),
                'subtotal' => number_format($subtotal, 2),
                'item_qty' => isset($cart[$key]) ? $cart[$key]['qty'] : 0,
                'item_subtotal' => isset($cart[$key]) ? number_format($cart[$key]['price'] * $cart[$key]['qty'], 2) : '0.00'
            ]);
            return;
        }

        redirect('cart');
    }

    public function remove($key = null) {
        if (!$key) {
            $key = $this->input->post('key');
        }
        $cart = $this->_get_cart();
        if (isset($cart[$key])) {
            unset($cart[$key]);
            $this->_save_cart($cart);
        }
        redirect('cart');
    }

    public function clear() {
        $this->session->unset_userdata('cart');
        redirect('cart');
    }

    public function count() {
        echo json_encode(['count' => $this->_get_cart_count()]);
    }

    public function checkout() {
        $cart = $this->_get_cart();
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Your shopping cart is empty.');
            redirect('cart');
            return;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price']) * intval($item['qty']);
        }

        $data['cart'] = $cart;
        $data['subtotal'] = $subtotal;
        $data['total'] = $subtotal;
        $data['user_logged_in'] = $this->session->userdata('user_logged_in');
        $data['user_name'] = $this->session->userdata('user_name');
        $data['user_email'] = $this->session->userdata('user_email');
        
        if ($data['user_logged_in'] && $this->session->userdata('user_id')) {
            $user = $this->db->get_where('users', array('id' => $this->session->userdata('user_id')))->row();
            if ($user) {
                $data['user_info'] = $user;
            }
        }

        $this->load->view('public_header', array('title' => 'Checkout | AlphaMindz'));
        $this->load->view('checkout', $data);
        $this->load->view('public_footer');
    }

    public function process_checkout() {
        $cart = $this->_get_cart();
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('cart');
            return;
        }

        $customer_name = trim($this->input->post('customer_name'));
        $customer_email = trim($this->input->post('customer_email'));
        $customer_phone = trim($this->input->post('customer_phone'));
        $transaction_id = trim($this->input->post('transaction_id'));
        $payment_method = 'qr_code';

        if (empty($customer_name) || empty($customer_email)) {
            $this->session->set_flashdata('error', 'Please fill in your name and email address.');
            redirect('cart/checkout');
            return;
        }

        // Upload payment proof screenshot
        $payment_proof = '';
        if (!empty($_FILES['payment_proof']['name'])) {
            $config['upload_path'] = './assets/uploads/payment_proofs/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('payment_proof')) {
                $upload_data = $this->upload->data();
                $payment_proof = 'assets/uploads/payment_proofs/' . $upload_data['file_name'];
            } else {
                $upload_error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Payment screenshot upload failed: ' . $upload_error);
                redirect('cart/checkout');
                return;
            }
        }

        $user_id = 0;
        // Determine User ID (logged in or register/lookup)
        if ($this->session->userdata('user_logged_in')) {
            $user_id = $this->session->userdata('user_id');
        } else {
            // Check if email already exists in users table
            $existing_user = $this->db->get_where('users', array('email' => $customer_email))->row();
            if ($existing_user) {
                $user_id = $existing_user->id;
                // Auto-login session for user
                $this->session->set_userdata([
                    'user_logged_in' => TRUE,
                    'user_id' => $existing_user->id,
                    'user_name' => $existing_user->first_name . ' ' . $existing_user->last_name,
                    'user_email' => $existing_user->email,
                    'user_role' => $existing_user->role
                ]);
            } else {
                // Register new user automatically
                $name_parts = explode(' ', $customer_name, 2);
                $first_name = $name_parts[0];
                $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
                $password = password_hash('Alpha@' . rand(1000, 9999), PASSWORD_BCRYPT);

                $user_data = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $customer_email,
                    'password' => $password,
                    'role' => 'student',
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('users', $user_data);
                $user_id = $this->db->insert_id();

                $this->session->set_userdata([
                    'user_logged_in' => TRUE,
                    'user_id' => $user_id,
                    'user_name' => $customer_name,
                    'user_email' => $customer_email,
                    'user_role' => 'student'
                ]);
            }
        }

        // Calculate total amount
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price']) * intval($item['qty']);
        }

        $order_number = 'AM-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        $order_data = array(
            'order_number' => $order_number,
            'user_id' => $user_id,
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'total_amount' => $subtotal,
            'payment_status' => 'pending',
            'payment_method' => $payment_method,
            'transaction_id' => $transaction_id,
            'payment_proof' => $payment_proof,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        // Save order items
        foreach ($cart as $item) {
            $item_subtotal = floatval($item['price']) * intval($item['qty']);
            $order_item_data = array(
                'order_id' => $order_id,
                'item_type' => $item['type'],
                'item_id' => $item['id'],
                'item_name' => $item['title'],
                'price' => floatval($item['price']),
                'quantity' => intval($item['qty']),
                'subtotal' => $item_subtotal
            );
            $this->db->insert('order_items', $order_item_data);
        }

        // Clear cart session
        $this->session->unset_userdata('cart');

        redirect('cart/success/' . $order_number);
    }

    public function success($order_number = null) {
        if (!$order_number) {
            redirect('cart');
            return;
        }

        $order = $this->db->get_where('orders', array('order_number' => $order_number))->row();
        if (!$order) {
            redirect('cart');
            return;
        }

        $this->db->where('order_id', $order->id);
        $items = $this->db->get('order_items')->result();

        $assessments_purchased = array();
        foreach ($items as $item) {
            if ($item->item_type === 'assessment') {
                $assessments_purchased[] = $item;
            }
        }

        $data['order'] = $order;
        $data['items'] = $items;
        $data['assessments_purchased'] = $assessments_purchased;

        $this->load->view('public_header', array('title' => 'Order Success | AlphaMindz'));
        $this->load->view('order_success', $data);
        $this->load->view('public_footer');
    }
}
