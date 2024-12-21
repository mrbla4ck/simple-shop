<?php
namespace SimpleShop\Frontend;

class Checkout {
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_ajax_process_checkout', [$this, 'process_checkout']);
        add_action('wp_ajax_nopriv_process_checkout', [$this, 'process_checkout']);
    }

    public function init() {
        add_shortcode('simple_shop_checkout', [$this, 'render_checkout_form']);
    }

    public function render_checkout_form() {
        if (!isset($_SESSION['simple_shop_cart']) || empty($_SESSION['simple_shop_cart'])) {
            return '<p>' . __('Your cart is empty', 'simple-shop') . '</p>';
        }

        ob_start();
        include SIMPLE_SHOP_PLUGIN_DIR . 'frontend/views/checkout-form.php';
        return ob_get_clean();
    }

    public function process_checkout() {
        check_ajax_referer('simple_shop_checkout', 'nonce');

        // Validate checkout data
        $order_data = $this->validate_checkout_data($_POST);
        if (is_wp_error($order_data)) {
            wp_send_json_error(['message' => $order_data->get_error_message()]);
        }

        // Create order
        $order_id = $this->create_order($order_data);
        if (!$order_id) {
            wp_send_json_error(['message' => __('Failed to create order', 'simple-shop')]);
        }

        // Clear cart
        unset($_SESSION['simple_shop_cart']);

        wp_send_json_success([
            'message' => __('Order placed successfully', 'simple-shop'),
            'order_id' => $order_id
        ]);
    }

    private function validate_checkout_data($data) {
        $required_fields = ['billing_name', 'billing_email', 'billing_address'];
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return new \WP_Error('validation_error', sprintf(__('%s is required', 'simple-shop'), $field));
            }
        }

        return [
            'billing_name' => sanitize_text_field($data['billing_name']),
            'billing_email' => sanitize_email($data['billing_email']),
            'billing_address' => sanitize_textarea_field($data['billing_address']),
        ];
    }

    private function create_order($order_data) {
        $cart = new Cart();
        $order_data['total'] = $cart->get_cart_total();
        $order_data['items'] = $cart->get_cart_contents();

        $order_id = wp_insert_post([
            'post_type' => 'simple_shop_order',
            'post_status' => 'publish',
            'post_title' => sprintf(__('Order %s', 'simple-shop'), uniqid()),
        ]);

        if ($order_id) {
            foreach ($order_data as $key => $value) {
                update_post_meta($order_id, '_' . $key, $value);
            }
        }

        return $order_id;
    }
}