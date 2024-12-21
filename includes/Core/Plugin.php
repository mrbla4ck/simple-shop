<?php
namespace SimpleShop\Core;

class Plugin {
    private static $instance = null;
    private $modules = [];

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->define_constants();
        $this->init_hooks();
        $this->load_dependencies();
    }

    private function define_constants() {
        if (!defined('SIMPLE_SHOP_ABSPATH')) {
            define('SIMPLE_SHOP_ABSPATH', dirname(SIMPLE_SHOP_PLUGIN_FILE) . '/');
        }
    }

    private function init_hooks() {
        add_action('init', [$this, 'init'], 0);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue_scripts']);
    }

    private function load_dependencies() {
        // Post Types
        $this->modules['product'] = new \SimpleShop\PostTypes\Product();
        $this->modules['order'] = new \SimpleShop\PostTypes\Order();

        // Core Functionality
        $this->modules['cart'] = new \SimpleShop\Frontend\Cart();
        $this->modules['checkout'] = new \SimpleShop\Frontend\Checkout();
        $this->modules['product_display'] = new \SimpleShop\Frontend\ProductDisplay();

        // Admin
        if (is_admin()) {
            $this->modules['settings'] = new \SimpleShop\Admin\Settings();
        }

        // Initialize UI components
        \SimpleShop\UI\Alert::init();
        \SimpleShop\Utils\Logger::init();
    }

    public function init() {
        do_action('simple_shop_before_init');
        
        // Load text domain
        load_plugin_textdomain('simple-shop', false, SIMPLE_SHOP_ABSPATH . 'languages/');
        
        do_action('simple_shop_init');
    }

    public function admin_enqueue_scripts() {
        wp_enqueue_style('simple-shop-admin', SIMPLE_SHOP_PLUGIN_URL . 'assets/css/admin.css', [], SIMPLE_SHOP_VERSION);
        wp_enqueue_script('simple-shop-admin', SIMPLE_SHOP_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], SIMPLE_SHOP_VERSION, true);
    }

    public function frontend_enqueue_scripts() {
        wp_enqueue_style('simple-shop-frontend', SIMPLE_SHOP_PLUGIN_URL . 'assets/css/frontend.css', [], SIMPLE_SHOP_VERSION);
        wp_enqueue_script('simple-shop-frontend', SIMPLE_SHOP_PLUGIN_URL . 'assets/js/frontend.js', ['jquery'], SIMPLE_SHOP_VERSION, true);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('SimpleShop', 'simple-shop'),
            __('SimpleShop', 'simple-shop'),
            'manage_options',
            'simple-shop',
            [$this, 'render_admin_page'],
            'dashicons-cart',
            58
        );
    }

    public function render_admin_page() {
        include SIMPLE_SHOP_PLUGIN_DIR . 'admin/views/main-page.php';
    }
}