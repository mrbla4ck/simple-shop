<?php
namespace SimpleShop\Install;

class Installer {
    public static function activate() {
        self::create_tables();
        self::create_pages();
        self::setup_roles();
        self::set_default_options();
        
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }

    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_orders (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                status varchar(50) NOT NULL,
                total decimal(10,2) NOT NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id)
            )",
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_order_items (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                order_id bigint(20) NOT NULL,
                product_id bigint(20) NOT NULL,
                quantity int(11) NOT NULL,
                price decimal(10,2) NOT NULL,
                PRIMARY KEY  (id)
            )",
            "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_sessions (
                session_id varchar(255) NOT NULL,
                session_data longtext NOT NULL,
                session_expiry datetime NOT NULL,
                PRIMARY KEY  (session_id)
            )"
        ];

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        foreach ($tables as $table) {
            dbDelta($table . $charset_collate);
        }
    }

    private static function create_pages() {
        $pages = [
            'shop' => [
                'title' => __('Shop', 'simple-shop'),
                'content' => '<!-- wp:simple-shop/products /-->'
            ],
            'cart' => [
                'title' => __('Cart', 'simple-shop'),
                'content' => '<!-- wp:simple-shop/cart /-->'
            ],
            'checkout' => [
                'title' => __('Checkout', 'simple-shop'),
                'content' => '<!-- wp:simple-shop/checkout /-->'
            ],
            'my-account' => [
                'title' => __('My Account', 'simple-shop'),
                'content' => '<!-- wp:simple-shop/my-account /-->'
            ]
        ];

        foreach ($pages as $slug => $page) {
            $page_id = wp_insert_post([
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ]);

            if (!is_wp_error($page_id)) {
                update_option('simple_shop_' . $slug . '_page_id', $page_id);
            }
        }
    }

    private static function setup_roles() {
        add_role('simple_shop_manager', __('Shop Manager', 'simple-shop'), [
            'read' => true,
            'edit_posts' => true,
            'manage_simple_shop' => true
        ]);

        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('manage_simple_shop');
        }
    }

    private static function set_default_options() {
        $defaults = [
            'simple_shop_currency' => 'USD',
            'simple_shop_tax_rate' => '0',
            'simple_shop_weight_unit' => 'kg',
            'simple_shop_dimension_unit' => 'cm',
            'simple_shop_enable_tax' => 'yes',
            'simple_shop_enable_shipping' => 'yes'
        ];

        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                update_option($key, $value);
            }
        }
    }
}