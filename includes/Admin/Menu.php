<?php
namespace SimpleShop\Admin;

class Menu {
    public function __construct() {
        add_action('admin_menu', [$this, 'register_menus']);
    }

    public function register_menus() {
        add_menu_page(
            __('SimpleShop', 'simple-shop'),
            __('SimpleShop', 'simple-shop'),
            'manage_options',
            'simple-shop',
            [$this, 'render_dashboard'],
            'dashicons-cart',
            58
        );

        $submenus = [
            'products' => __('Products', 'simple-shop'),
            'orders' => __('Orders', 'simple-shop'),
            'customers' => __('Customers', 'simple-shop'),
            'payments' => __('Payments', 'simple-shop'),
            'shipping' => __('Shipping', 'simple-shop'),
            'taxes' => __('Taxes', 'simple-shop'),
            'coupons' => __('Coupons', 'simple-shop'),
            'reports' => __('Reports', 'simple-shop'),
            'settings' => __('Settings', 'simple-shop'),
        ];

        foreach ($submenus as $slug => $title) {
            add_submenu_page(
                'simple-shop',
                $title,
                $title,
                'manage_options',
                'simple-shop-' . $slug,
                [$this, 'render_' . $slug]
            );
        }
    }

    public function render_dashboard() {
        include SIMPLE_SHOP_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    // Add other render methods for submenus...
}