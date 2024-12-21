<?php
namespace SimpleShop\Product;

class ProductManager {
    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('add_meta_boxes', [$this, 'add_product_meta_boxes']);
        add_action('save_post_simple_shop_product', [$this, 'save_product_meta']);
    }

    public function register_post_type() {
        register_post_type('simple_shop_product', [
            'labels' => [
                'name' => __('Products', 'simple-shop'),
                'singular_name' => __('Product', 'simple-shop'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'menu_icon' => 'dashicons-cart',
            'show_in_rest' => true,
        ]);
    }

    public function add_product_meta_boxes() {
        add_meta_box(
            'simple_shop_product_data',
            __('Product Data', 'simple-shop'),
            [$this, 'render_product_data_meta_box'],
            'simple_shop_product'
        );
    }

    public function render_product_data_meta_box($post) {
        // Implementation
    }

    public function save_product_meta($post_id) {
        // Implementation
    }
}