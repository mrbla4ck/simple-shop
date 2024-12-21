<?php
namespace SimpleShop\PostType;

class PostTypeRegistrar {
    public static function register_types() {
        self::register_product_type();
        self::register_order_type();
    }

    private static function register_product_type() {
        register_post_type('simple_shop_product', [
            'labels' => [
                'name' => __('Products', 'simple-shop'),
                'singular_name' => __('Product', 'simple-shop')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'menu_icon' => 'dashicons-cart',
            'show_in_rest' => true
        ]);
    }

    private static function register_order_type() {
        register_post_type('simple_shop_order', [
            'labels' => [
                'name' => __('Orders', 'simple-shop'),
                'singular_name' => __('Order', 'simple-shop')
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'simple-shop',
            'supports' => ['title'],
            'capability_type' => 'post'
        ]);
    }
}