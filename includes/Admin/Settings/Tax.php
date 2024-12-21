<?php
namespace SimpleShop\Admin\Settings;

class Tax {
    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {
        register_setting('simple_shop_tax_options', 'simple_shop_tax_settings');

        add_settings_section(
            'simple_shop_tax_settings',
            __('Tax Settings', 'simple-shop'),
            [$this, 'render_settings_section'],
            'simple_shop_tax'
        );
    }

    public function render_settings_section() {
        echo '<p>' . __('Configure tax-related settings below.', 'simple-shop') . '</p>';
    }
}