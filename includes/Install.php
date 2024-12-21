<?php
namespace SimpleShop;

class Install {
    public static function activate() {
        self::create_tables();
        self::create_pages();
        self::setup_roles();
        self::set_default_options();
        
        // Set a flag to trigger redirect to setup wizard
        set_transient('simple_shop_activation_redirect', true, 30);
    }

    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Orders table
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_orders (
            order_id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            order_status varchar(50) NOT NULL,
            order_total decimal(10,2) NOT NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (order_id)
        ) $charset_collate;";

        // Order items table
        $sql .= "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_order_items (
            item_id bigint(20) NOT NULL AUTO_INCREMENT,
            order_id bigint(20) NOT NULL,
            product_id bigint(20) NOT NULL,
            quantity int(11) NOT NULL,
            price decimal(10,2) NOT NULL,
            PRIMARY KEY  (item_id)
        ) $charset_collate;";

        // Order meta table
        $sql .= "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_order_meta (
            meta_id bigint(20) NOT NULL AUTO_INCREMENT,
            order_id bigint(20) NOT NULL,
            meta_key varchar(255) NOT NULL,
            meta_value longtext NOT NULL,
            PRIMARY KEY  (meta_id)
        ) $charset_collate;";

        // Sessions table for cart data
        $sql .= "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}simple_shop_sessions (
            session_id varchar(255) NOT NULL,
            session_data longtext NOT NULL,
            session_expiry datetime NOT NULL,
            PRIMARY KEY  (session_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private static function create_pages() {
        $pages = [
            'shop' => [
                'title' => __('Shop', 'simple-shop'),
                'content' => '[simple_shop_products]'
            ],
            'cart' => [
                'title' => __('Cart', 'simple-shop'),
                'content' => '[simple_shop_cart]'
            ],
            'checkout' => [
                'title' => __('Checkout', 'simple-shop'),
                'content' => '[simple_shop_checkout]'
            ],
            'my-account' => [
                'title' => __('My Account', 'simple-shop'),
                'content' => '[simple_shop_my_account]'
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

            if ($page_id) {
                update_option('simple_shop_' . $slug . '_page_id', $page_id);
            }
        }
    }

    private static function setup_roles() {
        add_role('simple_shop_manager', __('Shop Manager', 'simple-shop'), [
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'publish_posts' => true,
            'upload_files' => true,
            'manage_simple_shop' => true,
            'edit_simple_shop_orders' => true,
            'edit_others_simple_shop_orders' => true,
            'publish_simple_shop_orders' => true,
            'read_private_simple_shop_orders' => true,
            'delete_simple_shop_orders' => true,
            'delete_others_simple_shop_orders' => true,
            'delete_private_simple_shop_orders' => true,
            'delete_published_simple_shop_orders' => true,
            'edit_private_simple_shop_orders' => true,
            'edit_published_simple_shop_orders' => true,
        ]);

        // Add capabilities to administrator
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('manage_simple_shop');
            $admin->add_cap('edit_simple_shop_orders');
            $admin->add_cap('edit_others_simple_shop_orders');
            $admin->add_cap('publish_simple_shop_orders');
            $admin->add_cap('read_private_simple_shop_orders');
            $admin->add_cap('delete_simple_shop_orders');
            $admin->add_cap('delete_others_simple_shop_orders');
            $admin->add_cap('delete_private_simple_shop_orders');
            $admin->add_cap('delete_published_simple_shop_orders');
            $admin->add_cap('edit_private_simple_shop_orders');
            $admin->add_cap('edit_published_simple_shop_orders');
        }
    }

    private static function set_default_options() {
        $default_options = [
            'simple_shop_currency' => 'USD',
            'simple_shop_tax_rate' => '0',
            'simple_shop_weight_unit' => 'kg',
            'simple_shop_dimension_unit' => 'cm',
            'simple_shop_order_prefix' => 'SS-',
            'simple_shop_enable_guest_checkout' => 'yes',
            'simple_shop_enable_tax' => 'yes',
            'simple_shop_enable_shipping' => 'yes',
            'simple_shop_enable_coupons' => 'yes',
            'simple_shop_enable_reviews' => 'yes',
            'simple_shop_manage_stock' => 'yes',
            'simple_shop_low_stock_threshold' => '5',
            'simple_shop_notify_low_stock' => 'yes',
            'simple_shop_notify_out_of_stock' => 'yes',
            'simple_shop_default_country' => 'US',
            'simple_shop_base_location' => 'US:CA',
            'simple_shop_default_customer_address' => 'base',
            'simple_shop_calc_taxes' => 'yes',
            'simple_shop_enable_coupons' => 'yes',
            'simple_shop_enable_reviews' => 'yes',
            'simple_shop_review_rating_required' => 'yes',
            'simple_shop_review_moderation' => 'yes',
            'simple_shop_enable_signup_and_login_from_checkout' => 'yes',
            'simple_shop_enable_myaccount_registration' => 'yes',
            'simple_shop_registration_generate_username' => 'yes',
            'simple_shop_registration_generate_password' => 'yes'
        ];

        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                update_option($key, $value);
            }
        }
    }
}