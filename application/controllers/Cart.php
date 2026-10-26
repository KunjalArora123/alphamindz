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

    private function _calculate_totals() {
        $cart = $this->_get_cart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price']) * intval($item['qty']);
        }

        $applied_coupon = $this->session->userdata('applied_coupon');
        $discount = 0.00;
        $coupon_notice = null;

        if (!empty($applied_coupon)) {
            // Verify coupon against database
            $coupon = $this->db->get_where('coupons', array('id' => $applied_coupon['id'], 'status' => 1))->row();
            $today = date('Y-m-d');
            
            if (!$coupon || $coupon->valid_till < $today) {
                $this->session->unset_userdata('applied_coupon');
                $applied_coupon = null;
                $coupon_notice = 'The applied coupon has expired or is no longer active.';
            } elseif (!empty($coupon->min_spend) && floatval($coupon->min_spend) > 0 && $subtotal < floatval($coupon->min_spend)) {
                $this->session->unset_userdata('applied_coupon');
                $applied_coupon = null;
                $coupon_notice = 'Coupon removed: Minimum cart spend requirement is ₹' . number_format($coupon->min_spend, 2) . '.';
            } else {
                if ($coupon->discount_type === 'percentage') {
                    $discount = round(($subtotal * floatval($coupon->discount_value)) / 100, 2);
                } else {
                    $discount = floatval($coupon->discount_value);
                }
                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
                $applied_coupon['discount_amount'] = $discount;
                $this->session->set_userdata('applied_coupon', $applied_coupon);
            }
        }

        $total = max(0, $subtotal - $discount);

        return array(
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'applied_coupon' => $applied_coupon,
            'coupon_notice'  => $coupon_notice
        );
    }

    public function index() {
        $cart = $this->_get_cart();
        $totals = $this->_calculate_totals();

        if (!empty($totals['coupon_notice'])) {
            $this->session->set_flashdata('error', $totals['coupon_notice']);
        }

        $data['cart']           = $cart;
        $data['subtotal']       = $totals['subtotal'];
        $data['discount']       = $totals['discount'];
        $data['total']          = $totals['total'];
        $data['applied_coupon'] = $totals['applied_coupon'];
        $data['cart_count']     = $this->_get_cart_count();

        $this->load->view('public_header', array('title' => 'Shopping Cart | AlphaMindz'));
        $this->load->view('cart', $data);
        $this->load->view('public_footer');
    }

    public function apply_coupon() {
        $code = strtoupper(trim($this->input->post('coupon_code')));
        $is_ajax = $this->input->is_ajax_request();

        if (empty($code)) {
            $msg = 'Please enter a coupon code.';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        $cart = $this->_get_cart();
        if (empty($cart)) {
            $msg = 'Your cart is empty. Add items before applying a coupon.';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['price']) * intval($item['qty']);
        }

        $this->db->where('UPPER(code)', $code);
        $coupon = $this->db->get('coupons')->row();

        if (!$coupon) {
            $msg = 'Invalid coupon code "' . htmlspecialchars($code) . '".';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        if ($coupon->status != 1) {
            $msg = 'This coupon code is currently inactive.';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        if (strtotime($coupon->valid_till . ' 23:59:59') < time()) {
            $msg = 'Coupon "' . htmlspecialchars($coupon->code) . '" expired on ' . date('d M Y', strtotime($coupon->valid_till)) . '.';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        if (!empty($coupon->min_spend) && floatval($coupon->min_spend) > 0 && $subtotal < floatval($coupon->min_spend)) {
            $msg = 'Coupon "' . htmlspecialchars($coupon->code) . '" requires a minimum cart spend of ₹' . number_format($coupon->min_spend, 2) . '. Your cart subtotal is ₹' . number_format($subtotal, 2) . '.';
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                return;
            }
            $this->session->set_flashdata('error', $msg);
            redirect('cart');
            return;
        }

        // Calculate discount
        if ($coupon->discount_type === 'percentage') {
            $discount = round(($subtotal * floatval($coupon->discount_value)) / 100, 2);
        } else {
            $discount = floatval($coupon->discount_value);
        }
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $this->session->set_userdata('applied_coupon', array(
            'id'              => $coupon->id,
            'code'            => $coupon->code,
            'discount_type'   => $coupon->discount_type,
            'discount_value'  => floatval($coupon->discount_value),
            'min_spend'       => $coupon->min_spend ? floatval($coupon->min_spend) : null,
            'discount_amount' => $discount
        ));

        $total = max(0, $subtotal - $discount);

        $discount_text = ($coupon->discount_type === 'percentage') 
            ? floatval($coupon->discount_value) . '% OFF' 
            : '₹' . number_format($coupon->discount_value, 2) . ' OFF';

        $msg = 'Coupon "' . htmlspecialchars($coupon->code) . '" applied successfully! (' . $discount_text . ')';

        if ($is_ajax) {
            echo json_encode([
                'status'         => 'success',
                'message'        => $msg,
                'coupon_code'    => $coupon->code,
                'discount_type'  => $coupon->discount_type,
                'discount_value' => floatval($coupon->discount_value),
                'discount_text'  => $discount_text,
                'subtotal'       => number_format($subtotal, 2),
                'discount'       => number_format($discount, 2),
                'total'          => number_format($total, 2)
            ]);
            return;
        }

        $this->session->set_flashdata('success', $msg);
        redirect('cart');
    }

    public function remove_coupon() {
        $this->session->unset_userdata('applied_coupon');
        $is_ajax = $this->input->is_ajax_request();

        $totals = $this->_calculate_totals();

        if ($is_ajax) {
            echo json_encode([
                'status'   => 'success',
                'message'  => 'Coupon removed from your cart.',
                'subtotal' => number_format($totals['subtotal'], 2),
                'discount' => '0.00',
                'total'    => number_format($totals['total'], 2)
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Coupon removed from your cart.');
        redirect('cart');
    }

    public function add() {
        $item_type = $this->input->post('item_type'); // 'product', 'assessment', or 'course'
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
                $cart[$key]['qty'] = 1;
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
                if ($cart[$key]['type'] === 'assessment') {
                    $cart[$key]['qty'] = 1;
                } else {
                    $cart[$key]['qty'] = $qty;
                }
            }
            $this->_save_cart($cart);
        }

        if (empty($cart)) {
            $this->session->unset_userdata('applied_coupon');
        }

        if ($this->input->is_ajax_request()) {
            $totals = $this->_calculate_totals();
            echo json_encode([
                'status'        => 'success',
                'cart_count'    => $this->_get_cart_count(),
                'subtotal'      => number_format($totals['subtotal'], 2),
                'discount'      => number_format($totals['discount'], 2),
                'total'         => number_format($totals['total'], 2),
                'has_coupon'    => !empty($totals['applied_coupon']),
                'coupon_code'   => !empty($totals['applied_coupon']) ? $totals['applied_coupon']['code'] : '',
                'item_qty'      => isset($cart[$key]) ? $cart[$key]['qty'] : 0,
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

        if (empty($cart)) {
            $this->session->unset_userdata('applied_coupon');
        }

        redirect('cart');
    }

    public function clear() {
        $this->session->unset_userdata('cart');
        $this->session->unset_userdata('applied_coupon');
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

        $totals = $this->_calculate_totals();

        $data['cart']           = $cart;
        $data['subtotal']       = $totals['subtotal'];
        $data['discount']       = $totals['discount'];
        $data['total']          = $totals['total'];
        $data['applied_coupon'] = $totals['applied_coupon'];
        $data['user_logged_in'] = $this->session->userdata('user_logged_in');
        $data['user_name']      = $this->session->userdata('user_name');
        $data['user_email']     = $this->session->userdata('user_email');
        
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

        $customer_name  = trim($this->input->post('customer_name'));
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
            $config['upload_path']   = './assets/uploads/payment_proofs/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['encrypt_name']  = TRUE;
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
                $this->session->set_userdata([
                    'user_logged_in' => TRUE,
                    'user_id'        => $existing_user->id,
                    'user_name'      => $existing_user->first_name . ' ' . $existing_user->last_name,
                    'user_email'     => $existing_user->email,
                    'user_role'      => $existing_user->role
                ]);
            } else {
                // Register new user automatically
                $name_parts = explode(' ', $customer_name, 2);
                $first_name = $name_parts[0];
                $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
                $password = password_hash('Alpha@' . rand(1000, 9999), PASSWORD_BCRYPT);

                $user_data = array(
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'email'      => $customer_email,
                    'password'   => $password,
                    'role'       => 'student',
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('users', $user_data);
                $user_id = $this->db->insert_id();

                $this->session->set_userdata([
                    'user_logged_in' => TRUE,
                    'user_id'        => $user_id,
                    'user_name'      => $customer_name,
                    'user_email'     => $customer_email,
                    'user_role'      => 'student'
                ]);
            }
        }

        // Calculate totals including coupon discount
        $totals = $this->_calculate_totals();

        $order_number = 'AM-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        $order_data = array(
            'order_number'    => $order_number,
            'user_id'         => $user_id,
            'customer_name'   => $customer_name,
            'customer_email'  => $customer_email,
            'customer_phone'  => $customer_phone,
            'subtotal_amount' => $totals['subtotal'],
            'coupon_code'     => !empty($totals['applied_coupon']) ? $totals['applied_coupon']['code'] : null,
            'discount_amount' => $totals['discount'],
            'total_amount'    => $totals['total'],
            'payment_status'  => 'pending',
            'payment_method'  => $payment_method,
            'transaction_id'  => $transaction_id,
            'payment_proof'   => $payment_proof,
            'created_at'      => date('Y-m-d H:i:s')
        );

        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        // Increment coupon used count if a coupon was used
        if (!empty($totals['applied_coupon']['id'])) {
            $this->db->where('id', $totals['applied_coupon']['id'])->set('used_count', 'used_count + 1', FALSE)->update('coupons');
        }

        // Save order items
        foreach ($cart as $item) {
            $item_subtotal = floatval($item['price']) * intval($item['qty']);
            $order_item_data = array(
                'order_id'   => $order_id,
                'item_type'  => $item['type'],
                'item_id'    => $item['id'],
                'item_name'  => $item['title'],
                'price'      => floatval($item['price']),
                'quantity'   => intval($item['qty']),
                'subtotal'   => $item_subtotal
            );
            $this->db->insert('order_items', $order_item_data);
        }

        // Clear cart and coupon sessions
        $this->session->unset_userdata('cart');
        $this->session->unset_userdata('applied_coupon');

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
