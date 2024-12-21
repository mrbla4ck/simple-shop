<?php
namespace SimpleShop\Admin;

class AdminManager {
    private $menu;
    private $settings;

    public function __construct() {
        $this->menu = new Menu();
        $this->settings = new Settings();
        
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_init', [$this, 'init_admin']);
    }

    public function enqueue_admin_assets() {
        wp_enqueue_style(
            'simple-shop-admin', 
            SIMPLE_SHOP_PLUGIN_URL . 'assets/css/admin.css',
            [],
            SIMPLE_SHOP_VERSION
        );

        wp_enqueue_script(
            'simple-shop-admin',
            SIMPLE_SHOP_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            SIMPLE_SHOP_VERSION,
            true
        );
    }

    public function init_admin() {
        // Initialize admin-specific functionality
        new Settings\General();
        new Settings\Products();
        new Settings\Orders();
        new Settings\Payments();
        new Settings\Shipping();
        new Settings\Tax();
    }
}