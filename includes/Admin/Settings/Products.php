<?php
namespace SimpleShop\Admin\Settings;

class Products {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {
        register_setting('simple_shop_product_options', 'simple_shop_product_settings');

        add_settings_section(
            'simple_shop_product_settings',
            __('Product Settings', 'simple-shop'),
            [$this, 'render_settings_section'],
            'simple_shop_products'
        );
    }

    public function render_settings_section() {
        echo '<p>' . __('Configure product-related settings below.', 'simple-shop') . '</p>';
    }
}