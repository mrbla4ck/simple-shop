<?php
namespace SimpleShop\Frontend;

class Cart {
    private $cart_items = [];

    public function __construct() {
        if (!session_id()) {
            session_start();
        }

        add_action('init', [$this, 'init']);
        add_action('wp_ajax_add_to_cart', [$this, 'add_to_cart']);
        add_action('wp_ajax_nopriv_add_to_cart', [$this, 'add_to_cart']);
    }

    public function init() {
        if (!isset($_SESSION['simple_shop_cart'])) {
            $_SESSION['simple_shop_cart'] = [];
        }
        $this->cart_items = &$_SESSION['simple_shop_cart'];
    }

    public function add_to_cart() {
        check_ajax_referer('simple_shop_ajax', 'nonce');

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($product_id > 0) {
            if (isset($this->cart_items[$product_id])) {
                $this->cart_items[$product_id] += $quantity;
            } else {
                $this->cart_items[$product_id] = $quantity;
            }

            wp_send_json_success([
                'message' => __('Product added to cart', 'simple-shop'),
                'cart_count' => count($this->cart_items)
            ]);
        }

        wp_send_json_error(['message' => __('Failed to add product to cart', 'simple-shop')]);
    }

    public function get_cart_contents() {
        return $this->cart_items;
    }

    public function get_cart_total() {
        $total = 0;
        foreach ($this->cart_items as $product_id => $quantity) {
            $price = get_post_meta($product_id, '_simple_shop_price', true);
            $total += floatval($price) * $quantity;
        }
        return $total;
    }
}