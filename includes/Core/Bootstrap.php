<?php
namespace SimpleShop\Core;

class Bootstrap {
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
        $this->load_modules();
    }

    private function define_constants() {
        $constants = [
            'SIMPLE_SHOP_VERSION' => '1.0.0',
            'SIMPLE_SHOP_DB_VERSION' => '1.0.0',
            'SIMPLE_SHOP_PLUGIN_DIR' => plugin_dir_path(dirname(dirname(__FILE__))),
            'SIMPLE_SHOP_PLUGIN_URL' => plugin_dir_url(dirname(dirname(__FILE__))),
            'SIMPLE_SHOP_TEMPLATE_PATH' => 'simple-shop/'
        ];

        foreach ($constants as $name => $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }

    private function init_hooks() {
        add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
        add_action('init', [$this, 'init']);
    }

    private function load_modules() {
        // Core Modules
        $this->modules['admin'] = new \SimpleShop\Admin\AdminManager();
        $this->modules['products'] = new \SimpleShop\Product\ProductManager();
        $this->modules['orders'] = new \SimpleShop\Order\OrderManager();
        $this->modules['payments'] = new \SimpleShop\Payment\PaymentManager();
        $this->modules['shipping'] = new \SimpleShop\Shipping\ShippingManager();
        $this->modules['customers'] = new \SimpleShop\Customer\CustomerManager();
        $this->modules['cart'] = new \SimpleShop\Cart\CartManager();
        $this->modules['checkout'] = new \SimpleShop\Checkout\CheckoutManager();
        $this->modules['tax'] = new \SimpleShop\Tax\TaxManager();
        $this->modules['reports'] = new \SimpleShop\Report\ReportManager();
    }

    public function init() {
        do_action('simple_shop_before_init');
        
        // Initialize features
        $this->init_features();
        
        do_action('simple_shop_after_init');
    }

    private function init_features() {
        // Register post types
        \SimpleShop\PostType\PostTypeRegistrar::register_types();
        
        // Register taxonomies
        \SimpleShop\Taxonomy\TaxonomyRegistrar::register_taxonomies();
        
        // Initialize REST API
        \SimpleShop\API\RestAPI::init();
    }

    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'simple-shop',
            false,
            dirname(plugin_basename(SIMPLE_SHOP_PLUGIN_DIR)) . '/languages/'
        );
    }
}