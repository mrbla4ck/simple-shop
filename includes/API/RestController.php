<?php
namespace SimpleShop\API;

class RestController {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('simple-shop/v1', '/products', [
            'methods' => 'GET',
            'callback' => [$this, 'get_products'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('simple-shop/v1', '/orders', [
            'methods' => 'GET',
            'callback' => [$this, 'get_orders'],
            'permission_callback' => [$this, 'check_authentication']
        ]);

        register_rest_route('simple-shop/v1', '/cart', [
            'methods' => 'GET',
            'callback' => [$this, 'get_cart'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function get_products(\WP_REST_Request $request) {
        $args = [
            'post_type' => 'simple_shop_product',
            'posts_per_page' => $request->get_param('per_page') ?: 10,
            'paged' => $request->get_param('page') ?: 1
        ];

        $products = get_posts($args);
        return rest_ensure_response($this->format_products($products));
    }

    public function get_orders(\WP_REST_Request $request) {
        $args = [
            'post_type' => 'simple_shop_order',
            'posts_per_page' => $request->get_param('per_page') ?: 10,
            'paged' => $request->get_param('page') ?: 1
        ];

        $orders = get_posts($args);
        return rest_ensure_response($this->format_orders($orders));
    }

    public function get_cart(\WP_REST_Request $request) {
        $cart = new \SimpleShop\Frontend\Cart();
        return rest_ensure_response([
            'items' => $cart->get_cart_contents(),
            'total' => $cart->get_cart_total()
        ]);
    }

    private function check_authentication() {
        return current_user_can('manage_options');
    }

    private function format_products($products) {
        return array_map(function($product) {
            return [
                'id' => $product->ID,
                'title' => $product->post_title,
                'price' => get_post_meta($product->ID, '_simple_shop_price', true),
                'stock' => \SimpleShop\Inventory\Stock::get_stock_quantity($product->ID)
            ];
        }, $products);
    }

    private function format_orders($orders) {
        return array_map(function($order) {
            return [
                'id' => $order->ID,
                'status' => get_post_meta($order->ID, '_status', true),
                'total' => get_post_meta($order->ID, '_total', true),
                'date' => $order->post_date
            ];
        }, $orders);
    }
}