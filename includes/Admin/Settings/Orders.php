<?php
namespace SimpleShop\Admin\Settings;

class Orders {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {
        register_setting('simple_shop_order_options', 'simple_shop_order_settings');

        add_settings_section(
            'simple_shop_order_settings',
            __('Order Settings', 'simple-shop'),
            [$this, 'render_settings_section'],
            'simple_shop_orders'
        );
    }

    public function render_settings_section() {
        echo '<p>' . __('Configure order-related settings below.', 'simple-shop') . '</p>';
    }
}